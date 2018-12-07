<?php

class Se_db_account_points_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_leaderboard( $se_application_id = false, $limit = 10, $offset = 0 ) {
		$where = "";
		
		if( $se_application_id !== false ):
			$se_application_id_esc = $this->db->escape( $se_application_id );
			$where = " WHERE account_points_history.se_application_id = {$se_application_id_esc}";
		endif;
		
		$query = "SELECT accounts.*, SUM(account_points_history.points) as total_points FROM account_points_history JOIN accounts USING(account_id){$where} GROUP BY account_id ORDER BY SUM(account_points_history.points) DESC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_points( $se_application_id = false, $account_id ) {
		if( empty( $account_id ) )
			return array();
		
		$where = "";
		
		if( $se_application_id_esc !== false ):
			$se_application_id_esc = $this->db->escape( $se_application_id );
			$where = " se_application_id = {$se_application_id_esc} AND";
		endif;
		
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT total_points as SUM(points) FROM account_points_history WHERE{$where} account_id = {$account_id_esc} GROUP BY account_id";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function get_points_history( $se_application_id = false, $account_id, $limit = 10, $offset = 0 ) {
		if( empty( $account_id ) )
			return array();
		
		$where = "";
		
		if( $se_application_id_esc !== false ):
			$se_application_id_esc = $this->db->escape( $se_application_id );
			$where = " se_application_id = {$se_application_id_esc} AND";
		endif;
		
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM account_points_history WHERE{$where} account_id = {$account_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function insert_points( $se_application_id, $account_id, $points = 0, $reason = '' ) {
		if( empty( $se_application_id ) || empty( $account_id ) )
			return false;
		
		$se_application_id_esc = $this->db->escape( $se_application_id );
		$account_id_esc = $this->db->escape( $account_id );
		$points_esc = $this->db->escape( $points );
		$reason_esc = $this->db->escape( $reason );
		
		$query = "INSERT INTO account_points_history VALUES( {$se_application_id_esc}, NULL, {$account_id_esc}, {$points_esc}, {$reason_esc}, CURRENT_TIMESTAMP )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			$reward = array(
				'se_application_id' => $se_application_id,
				'account_id' => $account_id,
				'points' => $points,
				'reason' => $reason
			);
		
			#$this->session->set_flashdata( 'reward_notification', $reward );
			return true;
		endif;

		return false;
	} // function
	
	public function delete_points( $points_history_id, $account_id ) {
		if( empty( $points_history_id ) || empty( $account_id ) )
			return false;
		
		$points_history_id_esc = $this->db->escape( $points_history_id );
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "DELETE FROM account_points_history WHERE points_history_id = {$points_history_id_esc} AND account_id = {$account_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
} // class