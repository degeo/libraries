<?php

class Query_model extends CI_Model {

	public function __construct() {
		parent::__construct();
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
				case 'affected_rows':
					if( $this->db->affected_rows() > 0 ):
						return $this->db->affected_rows();
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

	protected function array_to_sql_string( array $array, $delimiter = 'AND', $insert_values = false ) {
		$sql_string = '';

		$fields = '';
		$values = '';
		foreach( $array as $column => $value ):
			if( $insert_values === true ):
				$fields .= "`{$column}`,";
				$values .= $this->db->escape($value) . ",";
			else:
				if( $value == 'NULL' || $value == '' ):
					$sql_string .= "`{$column}` IS NULL {$delimiter} ";
				else:
					$sql_string .= "`{$column}` = " . $this->db->escape($value) ." {$delimiter} ";
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
		$value = $this->db->escape( $value );

		return $value;
	} // function

} // class