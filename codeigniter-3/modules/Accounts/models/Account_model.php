<?php

class Account_model extends Account_Security_model {

	protected $primary_table = 'accounts';

	public function get_id() {
		return $this->account['account_id'];
	} // function

	public function get_field( $field ) {
		if( !array_key_exists( $field, $this->account ) || empty( $this->account[$field] ) )
			return '';

		return $this->account[$field];
	} // function

	public function get_username() {
		return $this->get_field( 'account_username' );
	} // function

	public function get_info( $account_id ) {
		$query = "SELECT * FROM accounts WHERE account_id = {$account_id}";

		$query = $this->Query->limit( $query, 1, 0 );

		$result = $this->get( 'results', $query );

		return $result[0];
	} // function

	public function get_column( $account_id, $column ) {
		$query = "SELECT {$column} FROM accounts WHERE account_id = {$account_id}";

		$query = $this->Query->limit( $query, 1, 0 );

		$result = $this->get( 'result', $query );

		return $result;
	} // function

	public function in_group( $group_key ) {
		return $this->ACL->account_in_group( $this->get_id(), $group_key );
	} // function

} // class