<?php

class Query_model extends CI_Model {

	protected $primary_table = '';
	protected $primary_key = '';

	protected $relations = array();

	public function __construct() {
		parent::__construct();
	} // function

	public function get_table_info( $table = '' ) {
		if( empty( $table ) )
			$table = $primary_table;

		$info = array(
			'exists' => $this->db->table_exists( $table )
		);

		if( $info['exists'] === true ):
			$info['fields'] = $this->db->field_data( $table );

			$info['field_count'] = count( $info['fields'] );

			foreach( $info['fields'] as $key => $field ):
				if( $field->primary_key === 1 ):
					$info['primary_key'] = $field->name;
				endif;
			endforeach;
		endif;

		return $info;
	} // function

	public function limit( $query, $limit, $offset = '' ) {
		if( empty( $query ) )
			return false;

		if( !empty($limit) && !empty( $offset ) ):
			$query .= " LIMIT {$offset}, {$limit}";
		elseif( !empty( $limit ) ):
			$query .= " LIMIT {$limit}";
		endif;

		return $query;
	} // function

	public function orderby( $query, $orderby, $order = 'ASC' ) {
		if( empty( $query ) || empty( $orderby ) )
			return $query;

		$query .= " ORDER BY ";

		if( is_array( $orderby ) ):
			foreach( $orderby as $field ):
				$query .= " {$field},";
			endforeach;
			$query = rtrim( $query, ',' );
		else:
			$query .= " {$orderby}";
		endif;

		$query .= " {$order}";

		return $query;
	} // function

	public function groupby( $query, $groupby, $order = 'ASC' ) {
		if( empty( $query ) || empty( $groupby ) )
			return $query;

		$query .= " GROUP BY ";

		if( is_array( $groupby ) ):
			foreach( $groupby as $field ):
				$query .= " {$field},";
			endforeach;
			$query = rtrim( $query, ',' );
		else:
			$query .= " {$groupby}";
		endif;

		$query .= " {$order}";

		return $query;
	} // function

	public function get( $get_what = 'results', $query, $get_count = false ) {
		$sql = $this->db->query( $query );

		if( $get_count === true ):

			if( $sql->num_rows() > 0 ):
				$result = $sql->row_array();
				return $result['total'];
			endif;

		else:

			switch( $get_what ):
				case 'first_column':
					$result = $sql->row_array();
					return current($result);
					break;
				case 'insert_id':
					return $this->db->insert_id();
					break;
				case 'affected_rows':
					if( $this->db->affected_rows() > 0 ):
						return $this->db->affected_rows();
					endif;
					break;
				case 'returned_rows':
					if( $sql->num_rows() > 0 ):
						return $sql->num_rows();
					endif;
					break;
				case 'result':
					if( $sql->num_rows() > 0 ):
						return $sql->row_array();
					endif;
					break;
				case 'results':
				default:
					if( $sql->num_rows() > 0 ):
						return $sql->result_array();
					endif;
					break;
			endswitch;

		endif;

		if( empty($result) && ( $get_count === true || $get_what == 'affected_rows' ) )
			return 0;

		return array();
	} // function

	public function get_affected_rows( $query ) {
		return $this->get( 'affected_rows', $query );
	} // function

	public function get_results( $query, $get_count = false ) {
		return $this->get( 'results', $query, $get_count );
	} // function

	public function get_result( $query, $get_count = false ) {
		return $this->get( 'result', $query, $get_count );
	} // function

	public function get_table_total( $table = '' ) {
		if( empty( $table ) )
			$table = $this->primary_table;

		return $this->db->count_all( $table );
	} // function

	protected function array_to_sql_string( array $array, $delimiter = 'AND', $insert_values = false, $update_values = false ) {
		$sql_string = '';

		$fields = '';
		$values = '';
		foreach( $array as $column => $value ):
			if( $insert_values === true ):
				$fields .= "`{$column}`,";
				$values .= $this->clean_value($value) . ",";
			else:
				if( $value == 'NULL' || $value == '' ):
					if( $update_values === true ):
						$sql_string .= "`{$column}` = '' {$delimiter} ";
					else:
						$sql_string .= "`{$column}` IS NULL {$delimiter} ";
					endif;
				else:
					$sql_string .= "`{$column}` = " . $this->clean_value($value) ." {$delimiter} ";
				endif;
			endif;
		endforeach;

		if( $insert_values === true ):
			$fields = rtrim($fields,',');
			$values = rtrim($values,',');
			$sql_string = '(' . $fields . ') VALUES (' . $values . ')';
		else:
			$sql_string = rtrim( $sql_string, " {$delimiter} " );
		endif;

		return $sql_string;
	} // function

	protected function array_to_eav_sql_string( array $array, $delimiter = 'AND', $insert_values = false, $update_values = false ) {
		$sql_string = '';

		$fields = '';
		$values = '';
		foreach( $array as $column => $value ):
			if( is_array( $value ) ):
				$value = $value['value'];
				$sql_string .= "(`attribute_key` = " . $this->clean_value($column) ." {$delimiter} `value` = " . $this->clean_value($value) .")";
			else:
				if( $insert_values === true ):
					$fields .= "`{$column}`,";
					$values .= $this->clean_value($value) . ",";
				else:
					if( $value == 'NULL' || $value == '' ):
						if( $update_values === true ):
							$sql_string .= "`{$column}` = '' {$delimiter} ";
						else:
							$sql_string .= "`{$column}` IS NULL {$delimiter} ";
						endif;
					else:
						$sql_string .= "`{$column}` = " . $this->clean_value($value) ." {$delimiter} ";
					endif;
				endif;
			endif;
		endforeach;

		if( $insert_values === true ):
			$fields = rtrim($fields,',');
			$values = rtrim($values,',');
			$sql_string = '(' . $fields . ') VALUES (' . $values . ')';
		else:
			$sql_string = rtrim( $sql_string, " {$delimiter} " );
		endif;

		return $sql_string;
	} // function

	public function clean_value( $value ) {
		$value = trim( $value );
		$value = $this->db->escape( $value );

		return $value;
	} // function

	public function get_relational_object_by_id( $object, $id ) {
		$object_table = $object . '_' . $this->primary_table;
		$object_id_field = $object . '_id';
		$id = $this->Query->clean_value( $id );

		$query = "SELECT * FROM {$object_table} JOIN {$this->primary_table} USING({$this->primary_key}) WHERE {$object_id_field} = {$id}";

		return $this->get( 'results', $query );
	} // function

	public function get_relationship( $foreign_table ) {
		if( array_key_exists( $foreign_table, $this->relations ) ):
			return $this->relations[ $foreign_table ];
		endif;

		return false;
	} // function

} // class