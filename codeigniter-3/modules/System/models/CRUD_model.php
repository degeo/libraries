<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CRUD_model extends Query_model {

	protected $primary_table = '';

	public function set_primary_table( $primary_table ) {
		$this->primary_table = $primary_table;
	} // function

	public function get_primary_table( $primary_table ) {
		return $this->primary_table;
	} // function

	public function create( array $record_data ) {
		if( empty( $record_data ) )
			return false;

		$conditions = $this->array_to_sql_string( $record_data, 'AND', true );

		if( empty( $conditions ) )
			return false;

		$query = "INSERT INTO {$this->primary_table} {$conditions}";

		$results = $this->get( 'affected_rows', $query );

		$insert_id = $this->db->insert_id();

		return $insert_id;
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
		if( empty( $record_data ) )
			return false;

		$where_conditions = $this->array_to_sql_string( $where_data, 'AND' );

		if( empty( $where_conditions ) )
			return false;

		$set_conditions = $this->array_to_sql_string( $record_data, ',' );

		if( empty( $set_conditions ) )
			return false;

		$query = "UPDATE {$this->primary_table} SET {$set_conditions} WHERE {$where_conditions}";

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

} // class
