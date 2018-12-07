<?php

class Se_db_account_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_account( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT DISTINCT accounts.*, SUM(account_points_history.points) as total_points FROM accounts LEFT JOIN account_points_history USING(account_id) WHERE account_id = {$account_id} GROUP BY account_id";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function get_account_by_email( $user_email ) {
		$user_email_esc = $this->db->escape( $user_email );
		
		$query = "SELECT * FROM accounts WHERE email = {$user_email_esc} LIMIT 1";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function get_account_by_url( $user_url ) {
		$user_url_esc = $this->db->escape( $user_url );
		
		$query = "SELECT * FROM accounts WHERE user_url = {$user_url_esc} LIMIT 1";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function authorize_login() {
		$email = $this->input->post( 'user_email' );
		$email_esc = $this->db->escape( $email );
		
		$password = $this->input->post( 'user_password' );
		$password_esc = $this->db->escape( $password );

		if( empty( $email ) || empty( $password ) )
			return array();

		$query = "SELECT DISTINCT accounts.*, account_types.*, 0 + SUM(account_points_history.points) as total_points FROM accounts JOIN account_authorization USING(account_id) LEFT JOIN account_types USING(account_type_id) LEFT JOIN account_points_history USING(account_id) WHERE email = {$email_esc} AND account_authorization.password = {$password_esc}";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$userdata = $sql->row_array();
			
			$this->update_account_last_known( $userdata['account_id'] );
			
			$this->Se_app_network_sessions->start_session( $userdata['account_id'] );
			
			# @se-points Points Earned - Reason: User logged in to account
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $userdata['account_id'], '100', 'user logged in to account' );
			
			return $userdata;
		endif;

		return array();
	} // function
	
	# @deprecated use Se_app_user_model->destroy_session instead
	public function authorize_logout() {
		$userdata = $this->session->userdata('user');
		
		$this->Se_app_network_sessions->delete_session( $userdata['account_id'] );
		
		$this->session->sess_destroy();
		
		return true;
	} // function
	
	public function get_account_id_by_thirdparty_link_token( $thirdparty_link_token ) {
		$thirdparty_link_token_esc = $this->db->escape( $thirdparty_link_token );
		
		$query = "SELECT account_id FROM account_thirdparty_links WHERE account_thirdparty_link_token = {$thirdparty_link_token_esc} LIMIT 1";
				
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$result = $sql->row_array();
			
			return $result['account_id'];
		endif;

		return null;
	} // function
	
	public function get_thirdparty_link_token_by_account_id( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT account_thirdparty_link_token FROM account_thirdparty_links WHERE account_id = {$account_id_esc} LIMIT 1";
				
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$result = $sql->row_array();
			
			return $result['account_thirdparty_link_token'];
		endif;

		return null;
	} // function
	
	public function link_thirdparty_token_to_account( $thirdparty_link_token, $account_id ) {
		$thirdparty_link_token_esc = $this->db->escape( $thirdparty_link_token );
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "INSERT INTO account_thirdparty_links VALUES( NULL, {$account_id_esc}, {$thirdparty_link_token_esc} )";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 ):
			# @se-points Points Earned - Reason: User logged in to account
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'user linked a third-party account' );
			
			return true;
		endif;
		
		return false;
	} // function
	
	public function unlink_thirdparty_token_from_account( $thirdparty_link_token, $account_id ) {
		$thirdparty_link_token_esc = $this->db->escape( $thirdparty_link_token );
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "DELETE FROM account_thirdparty_links WHERE account_id = {$account_id_esc} AND thirdparty_link_token = {$thirdparty_link_token_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 )
			return true;
		
		return false;
	} // function
	
	public function get_account_roles( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM account_roles JOIN account_authorization_roles USING(account_authorization_role_id) WHERE account_id = {$account_id_esc}";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return set_role_as_key($sql->result_array());
		endif;

		return array();
	} // function
	
	public function get_account_articles( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM articles WHERE account_id = {$account_id}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_collections( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM collections WHERE account_id = {$account_id}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_discussions( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM discussions WHERE account_id = {$account_id} AND ( discussions.parent_discussion_id IS NULL OR discussions.parent_discussion_id = 0 ) GROUP BY discussion_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_favorite_feed( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url, updates.update, updates.update_timestamp, accounts.username as update_username, accounts.user_url as update_user_url FROM account_favorite_taxa JOIN taxon_updates USING(taxon_id) JOIN updates USING(update_id) JOIN accounts ON accounts.account_id = updates.account_id  JOIN taxa USING(taxon_id) JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) WHERE account_favorite_taxa.account_id = {$account_id} ORDER BY updates.update_timestamp DESC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_favorite_taxa( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url FROM account_favorite_taxa JOIN taxa USING(taxon_id) JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) WHERE account_favorite_taxa.account_id = {$account_id}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_favorite_aquatic_taxa( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url FROM account_favorite_taxa JOIN taxa USING(taxon_id) JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) WHERE ( taxa.ecosystem_id = '3' || taxa.ecosystem_id = '4' ) AND account_favorite_taxa.account_id = {$account_id}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_images( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM images WHERE account_id = {$account_id} ORDER BY image_id DESC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_journals( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT journals.*, accounts.*, COUNT(journal_entries.journal_entry_id) as total_entries FROM journals JOIN accounts USING(account_id) LEFT JOIN journal_entries USING(journal_id) WHERE journals.account_id = {$account_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_recipes( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM recipes JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id) WHERE recipes.account_id = {$account_id}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_browsing_history( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT * FROM account_browsing_history WHERE account_id = {$account_id}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function update_account_last_known( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$ip = $this->input->ip_address();
		$ip_esc = $this->db->escape( $ip );
		
		$query = "UPDATE accounts SET last_login_date = CURRENT_TIMESTAMP(), last_known_ip = {$ip_esc} WHERE account_id = {$account_id_esc}";

		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function update_account_settings( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$set = '';
		
		if( $email = $this->input->post('user_email') ):
			$email_esc = $this->db->escape( $email );
			
			$set .= "email = {$email_esc}, ";
		endif;
		
		if( $username = $this->input->post('user_username') ):
			$username_esc = $this->db->escape( $username );
			
			$url = format_user_url( $username );
			$url_esc = $this->db->escape( $url );

			$set .= "username = {$username_esc}, user_url = {$url_esc}, ";
		endif;
		
		if( $dob = $this->input->post('user_dob') ):
			$dob_esc = $this->db->escape( $dob );
			
			$set .= "dob = {$dob_esc}, ";
		endif;
		
		if( $job_title = $this->input->post('user_job_title') ):
			$job_title_esc = $this->db->escape( $job_title );
			
			$set .= "job_title = {$job_title_esc}, ";
		endif;
		
		if( $password = $this->input->post('user_password') ):
			if( !empty( $password ) ):
				$password_updated = $this->update_account_password( $account_id, $password );
				if( empty( $set ) && $password_updated === TRUE )
					return true;
			endif;
		endif;
		
		if( empty( $set ) )
			return false;
		
		$set = rtrim( $set, ',' );
		
		$query = "UPDATE accounts SET {$set} WHERE account_id = {$account_id_esc}";

		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function update_account_password( $account_id, $password = '' ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		if( empty( $password ) ):
			$this->load->helper('string');
			$password = random_string( 'alnum', 12 );
		endif;
		
		$password_esc = $this->db->escape( $password );
		
		$query = "UPDATE account_authorization SET password = {$password_esc} WHERE account_id = {$account_id_esc}";

		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function get_account_aquariums( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT aquariums.*, ecosystems.*, ar.*, aquariums.aquarium_id as aquarium_id FROM aquariums JOIN ecosystems USING(ecosystem_id) LEFT JOIN (SELECT * FROM aquarium_readings GROUP BY aquarium_id DESC ORDER BY aquarium_last_reading DESC) as ar ON aquariums.aquarium_id = ar.aquarium_id WHERE aquariums.account_id = {$account_id_esc} ORDER BY aquarium_last_reading ASC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_aquarium_species( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT taxa.*, taxon_names.*, ecosystems.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
		FROM aquarium_taxa 
		JOIN aquariums USING(aquarium_id) 
		JOIN taxa USING(taxon_id) 
		JOIN taxon_names USING(taxon_name_id) 
		JOIN taxon_information USING(taxon_id) 
		LEFT JOIN taxon_images USING(taxon_id) 
		LEFT JOIN images ON images.image_id = taxon_images.image_id 
		LEFT JOIN ecosystems ON taxa.ecosystem_id = ecosystems.ecosystem_id 
		LEFT JOIN taxon_behaviors USING(taxon_id) 
		LEFT JOIN behaviors USING(behavior_id) 
		WHERE aquariums.account_id = {$account_id_esc} GROUP BY taxa.taxon_id ORDER BY taxon_names.taxon_name ASC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		$results = $sql->result_array();
		
		foreach( $results as $k => $taxon ):
			$this->load->model('Se_db_taxon_model', 'Se_db_taxon');
			$results[$k]['synonyms'] = $this->Se_db_taxon->get_taxon_synonyms( $taxon['taxon_id'] );
		endforeach;
		
		return $results;

		return array();
	} // function
	
	public function get_account_aquarium_ecosystem_ids( $account_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT ecosystem_id FROM aquariums WHERE account_id = {$account_id_esc} GROUP BY ecosystem_id";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;
		
		return array();
	} // function
	
	public function lost_password() {
		$email = $this->input->post( 'user_email' );
		$email_esc = $this->db->escape( $email );

		if( empty( $email ) )
			return false;

		$query = "SELECT account_id, email, username, SHA1(CONCAT( email, last_login_date, registration_ip )) as mailkey FROM accounts WHERE email = {$email_esc} LIMIT 1";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$results = $sql->row_array();
		
			$this->load->model('Mailer');
			$this->Mailer->send_password_reset( $results['email'], $results['username'], $results['mailkey'] );
			
			return true;
		endif;

		return false;
	} // function
	
	public function lost_password_reset( $mailkey ) {
		$email = $this->input->post( 'user_email' );
		$email_esc = $this->db->escape( $email );

		$password = $this->input->post('new_user_password');
		$password_esc = $this->db->escape( $password );

		$mailkey_esc = $this->db->escape( $mailkey );

		$query = "SELECT account_id FROM accounts WHERE email = {$email_esc} AND SHA1(CONCAT( email, last_login_date, registration_ip )) = {$mailkey_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$results = $sql->row_array();
			
			return $this->update_account_password( $results['account_id'], $password );
		endif;

		return false;
	} // function
	
	public function get_account_gardens( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT gardens.*, ar.* FROM gardens JOIN (SELECT * FROM garden_readings GROUP BY garden_id DESC ORDER BY garden_last_reading DESC) as ar ON gardens.garden_id = ar.garden_id WHERE gardens.account_id = {$account_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_account_garden_species( $account_id, $limit = 10, $offset = 0 ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$query = "SELECT taxa.*, taxon_names.*, ecosystems.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
		FROM garden_taxa 
		JOIN gardens USING(garden_id) 
		JOIN taxa USING(taxon_id) 
		JOIN taxon_names USING(taxon_name_id) 
		JOIN taxon_information USING(taxon_id) 
		LEFT JOIN taxon_images USING(taxon_id) 
		LEFT JOIN images ON images.image_id = taxon_images.image_id 
		LEFT JOIN ecosystems ON taxa.ecosystem_id = ecosystems.ecosystem_id 
		LEFT JOIN taxon_behaviors USING(taxon_id) 
		LEFT JOIN behaviors USING(behavior_id) 
		WHERE gardens.account_id = {$account_id_esc} GROUP BY taxa.taxon_id ORDER BY taxon_names.taxon_name ASC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		$results = $sql->result_array();
		
		foreach( $results as $k => $taxon ):
			$this->load->model('Se_db_taxon_model', 'Se_db_taxon');
			$results[$k]['synonyms'] = $this->Se_db_taxon->get_taxon_synonyms( $taxon['taxon_id'] );
		endforeach;
		
		return $results;

		return array();
	} // function
	
	public function update_account_session_userdata( $userdata ) {
		
		return $userdata;
	} // function
	
} // class