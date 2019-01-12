<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CRUD_model extends Query_model {

	protected $primary_table = '';
	protected $primary_key = '';

	public function set_primary_table( $primary_table ) {
		$this->primary_table = $primary_table;
	} // function

	public function get_primary_table( $primary_table ) {
		return $this->primary_table;
	} // function

	public function create( array $record_data ) {
		if( empty( $record_data ) )
			return false;

		$query = $this->db->insert_string( $this->primary_table, $record_data );

		return $this->get( 'insert_id', $query );
	} // function

	public function create_relationship( $relational_table = '', array $record_data = array() ) {
		if( empty( $relational_table ) || empty( $record_data ) )
			return false;

		$query = $this->db->insert_string( $relational_table, $record_data );

		return $this->get( 'insert_id', $query );
	} // function

	public function read( array $record_data = array(), $condition_type = 'AND', $limit = 10, $offset = 0, $orderby = '', $order = 'ASC' ) {
		if( !empty( $record_data ) )
			$conditions = $this->array_to_sql_string( $record_data, $condition_type );

		if( empty( $conditions ) )
			$conditions = '1=1';

		$query = "SELECT * FROM {$this->primary_table} WHERE {$conditions}";

		if( !empty( $orderby ) )
			$query .= " ORDER BY {$orderby} {$order}";

		$results = $this->get( 'results', $query );

		return $results;
	} // function

	public function update( array $where_data, array $record_data ) {
		if( empty( $record_data ) || empty( $where_data ) )
			return false;

		$where_statement = $this->array_to_sql_string( $where_data, 'AND' );

		$query = $this->db->update_string( $this->primary_table, $record_data, $where_statement );

		$results = $this->get( 'affected_rows', $query );

		return $results;
	} // function

	public function delete( array $record_data ) {
		if( empty( $record_data ) )
			return false;

		$conditions = $this->array_to_sql_string( $record_data, 'AND' );

		if( empty( $conditions ) )
			return false;

		$query = "DELETE FROM {$this->primary_table} WHERE {$conditions}";

		$results = $this->get( 'affected_rows', $query );

		return $results;
	} // function

	/**
	 * @param Array $join_tables - Multi-level array with `name` and `primary_key` keys.
	 * @param Array $fields - Single-level array with [n] => `table_column` pairings.
	 */
	public function search( $query, $type = 'broad', $primary_table, $join_tables = array(), $fields = array() ) {
		if( empty( $primary_table ) ):
			$primary_table = $this->primary_table;
		endif;

		switch( $type ):
			case 'exact':
				$delimiter = 'AND';
				break;
			case 'broad':
			default:
				$delimiter = 'OR';
				break;
		endswitch;

		$from_statement = "{$primary_table} ";
		$where_statement = '';

		if( !empty( $join_tables ) ):
			foreach( $join_tables as $table ):
				$from_statement = "LEFT JOIN {$table['name']} USING( {$table['primary_key']} ) ";
			endforeach;
		endif;

		$query_strings = explode( ' ', $query );

		foreach( $query_strings as $string ):
			if( empty( $string ) )
				continue;

			#$string = "[:<<:]{$string}[:>>:]";
			$string = $this->clean_value( $string );
			foreach( $fields as $field ):
				$where_statement .= "`{$field}` RLIKE {$string} {$delimiter} ";
			endforeach;
		endforeach;

		$from_statement = trim( $from_statement );
		$where_statement = trim( $where_statement, " {$delimiter} " );

		$query = "SELECT * FROM {$from_statement} WHERE {$where_statement}";

		return $this->get( 'results', $query );
	} // function

} // class
