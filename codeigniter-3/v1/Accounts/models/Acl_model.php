<?php

class Acl_model extends Account_Security_model {

	protected $primary_table = 'account_groups';

	public function account_in_group( $account_id, $group_key ) {
		$query = "SELECT account_group_id FROM account_groups JOIN groups USING(group_id) WHERE account_id = {$account_id} AND group_key = '{$group_key}'";

		$result = $this->get( 'returned_rows', $query );

		if( $result > 0 && !is_array( $result ) )
			return true;

		return false;
	} // function

} // class