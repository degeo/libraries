<?php

class Account_model extends Account_Security_model {

	protected $primary_table = 'accounts';

	public function get_id() {
		return $this->account['account_id'];
	} // function

	public function get_username() {
		if( !array_key_exists( 'account_username', $this->account ) || empty( $this->account['account_username'] ) )
			return '';

		return $this->account['account_username'];
	} // function

	public function get_info( $account_id ) {
		$query = "SELECT * FROM accounts WHERE account_id = {$account_id}";

		$query = $this->Query->limit( $query, 1, 0 );

		$result = $this->get( 'results', $query );

		return $result[0];
	} // function

} // class