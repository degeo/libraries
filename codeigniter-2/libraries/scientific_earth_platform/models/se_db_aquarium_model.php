<?php

class Se_db_aquarium_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_aquarium_by_id( $aquarium_id ) {
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$query = "SELECT *, aquariums.aquarium_id as aquarium_id FROM aquariums JOIN ecosystems USING(ecosystem_id) LEFT JOIN (SELECT * FROM aquarium_readings GROUP BY aquarium_id DESC ORDER BY aquarium_last_reading DESC) as ar ON aquariums.aquarium_id = ar.aquarium_id WHERE aquariums.aquarium_id = {$aquarium_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function get_aquarium_readings( $aquarium_id ) {
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$query = "SELECT * FROM aquarium_readings WHERE aquarium_id = {$aquarium_id_esc} ORDER BY aquarium_last_reading DESC";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_aquarium_species( $aquarium_id ) {
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$query = "SELECT taxa.*, taxon_names.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
		FROM aquarium_taxa JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) LEFT JOIN taxon_information USING(taxon_id) LEFT JOIN taxon_images USING(taxon_id) LEFT JOIN images ON taxon_images.image_id = images.image_id LEFT JOIN taxon_behaviors USING(taxon_id) LEFT JOIN behaviors USING(behavior_id) WHERE aquarium_id = {$aquarium_id_esc} GROUP BY taxa.taxon_id ORDER BY taxon_names.taxon_name ASC";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$results = $sql->result_array();
			
			foreach( $results as $k => $taxon ):
				$this->load->model('Se_db_taxon_model', 'Se_db_taxon');
				$results[$k]['synonyms'] = $this->Se_db_taxon->get_taxon_synonyms( $taxon['taxon_id'] );
			endforeach;
			
			return $results;
		endif;

		return array();
	} // function
	
	public function get_aquarium_discussions( $account_id, $limit = 10, $offset = 0, $taxa = '' ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$taxa_where = '';
		if( !empty( $taxa ) ):
			$taxa_where = " AND ( ";
			foreach( $taxa as $taxon ):
				$taxon_id_esc = $this->db->escape( $taxon['taxon_id'] );
				$taxa_where .= "taxon_discussions.taxon_id = {$taxon_id_esc} OR ";
			endforeach;
			$taxa_where = rtrim( $taxa_where, ' OR ' );
			$taxa_where .= " )";
		endif;
		
		$query = "SELECT * FROM taxon_discussions JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN discussions USING(discussion_id) WHERE (taxa.ecosystem_id = '3' OR taxa.ecosystem_id = '4') AND account_id != {$account_id_esc} AND ( discussions.parent_discussion_id IS NULL OR discussions.parent_discussion_id = 0 ){$taxa_where} GROUP BY discussions.discussion_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_aquarium_images( $account_id, $limit = 10, $offset = 0, $taxa = '' ) {
		$account_id_esc = $this->db->escape( $account_id );
		
		$taxa_where = '';
		if( !empty( $taxa ) ):
			$taxa_where = " AND ( ";
			foreach( $taxa as $taxon ):
				$taxon_id_esc = $this->db->escape( $taxon['taxon_id'] );
				$taxa_where .= "taxon_images.taxon_id = {$taxon_id_esc} OR ";
			endforeach;
			$taxa_where = rtrim( $taxa_where, ' OR ' );
			$taxa_where .= " )";
		endif;
		
		$query = "SELECT * FROM taxon_images JOIN images USING(image_id) JOIN taxa USING(taxon_id) WHERE (taxa.ecosystem_id = '3' OR taxa.ecosystem_id = '4') AND images.account_id != {$account_id_esc}{$taxa_where}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_species( $random = false, $limit = 10, $offset = 0, $get_count = false, $filter = '', $exclude = '' ) {
		
		$filter_where = '';
		if( !empty( $filter ) ):
			
			$where_stmts = array();
			
			if( array_key_exists( 'water_type', $filter ) && !empty($filter['water_type']) ):
				$ecosystem_esc = $this->db->escape( $filter['water_type'] );
				$where_stmts[] = "( taxa.ecosystem_id = {$ecosystem_esc} )";
				
				if( $filter['water_type'] == '3' ):
				
					if( array_key_exists( 'sg_level', $filter ) && !empty($filter['sg_level']) ):
						$sg_esc = $this->db->escape( $filter['sg_level'] );
						$where_stmts[] = "( taxon_information.sg_minimum <= {$sg_esc} AND taxon_information.sg_maximum >= {$sg_esc} )";
					endif;

					if( array_key_exists( 'kh_level', $filter ) && !empty($filter['kh_level']) ):
						$kh_esc = $this->db->escape( $filter['kh_level'] );
						$where_stmts[] = "( taxon_information.kh_minimum <= {$kh_esc} AND taxon_information.kh_maximum >= {$kh_esc} )";
					endif;
				
				endif;
			endif;
			
			if( array_key_exists( 'gallons', $filter ) && !empty($filter['gallons']) ):
				$gallons_esc = $this->db->escape( $filter['gallons'] );
				$where_stmts[] = "( taxon_information.aquarium_minimum_gallons <= {$gallons_esc} )";
			endif;
			
			if( array_key_exists( 'temperature', $filter ) && !empty($filter['temperature']) ):
				$temp_esc = $this->db->escape( $filter['temperature'] );
				$where_stmts[] = "( taxon_information.temperature_minimum_f <= {$temp_esc} AND taxon_information.temperature_maximum_f >= {$temp_esc} )";
			endif;
			
			if( array_key_exists( 'ph_level', $filter ) && !empty($filter['ph_level']) ):
				$ph_esc = $this->db->escape( $filter['ph_level'] );
				$where_stmts[] = "( taxon_information.ph_minimum <= {$ph_esc} AND taxon_information.ph_maximum >= {$ph_esc} )";
			endif;
			
			foreach( $where_stmts as $stmt ):
				$filter_where .= " AND {$stmt}";
			endforeach;
			
		endif;
		
		$exclude_where = '';
		if( is_array( $exclude ) && !empty( $exclude ) ):
			foreach( $exclude as $taxon_id ):
				$taxon_id_esc = $this->db->escape( $taxon_id );
				$exclude_where .= " AND taxa.taxon_id != {$taxon_id_esc}";
			endforeach;
		endif;
		
		$ecosystem_id_esc = $this->db->escape( $filter['water_type'] );
		
		if( $get_count === true ):
			$query = "SELECT COUNT(DISTINCT taxa.taxon_id) as total FROM taxa JOIN taxon_names USING(taxon_name_id) JOIN taxon_information USING(taxon_id) LEFT JOIN images USING(image_id) WHERE ( taxa.taxon_rank_id = '9' OR taxa.taxon_rank_id = '15' ){$filter_where}{$exclude_where}";
		else:
			if( $random === true ):
				$query = "SELECT taxa.*, taxon_names.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
				FROM taxa 
				JOIN taxon_names USING(taxon_name_id) 
				LEFT JOIN taxon_information USING(taxon_id) 
				LEFT JOIN images USING(image_id) 
				LEFT JOIN taxon_behaviors USING(taxon_id) 
				LEFT JOIN behaviors USING(behavior_id) 
				WHERE ( taxa.taxon_rank_id IN (9,15,18) ){$filter_where}{$exclude_where} 
				GROUP BY taxa.taxon_id ORDER BY RAND()";
			else:
				$query = "SELECT taxa.*, taxon_names.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
				FROM taxa 
				JOIN taxon_names USING(taxon_name_id) 
				LEFT JOIN taxon_information USING(taxon_id) 
				LEFT JOIN images USING(image_id) 
				LEFT JOIN taxon_behaviors USING(taxon_id) 
				LEFT JOIN behaviors USING(behavior_id) 
				WHERE ( taxa.taxon_rank_id IN (9,15,18) ){$filter_where}{$exclude_where} 
				GROUP BY taxa.taxon_id ORDER BY taxon_names.taxon_name ASC";
			endif;
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				$results = $sql->result_array();

				foreach( $results as $k => $taxon ):
					$this->load->model('Se_db_taxon_model', 'Se_db_taxon');
					$results[$k]['synonyms'] = $this->Se_db_taxon->get_taxon_synonyms( $taxon['taxon_id'] );
				endforeach;

				return $results;
			endif;
		endif;
		
		return array();
	} // function
	
	public function update_aquarium( $account_id, $aquarium_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$aquarium_name = $this->input->post('aquarium_name');
		$water_type = $this->input->post('water_type');
		$gallons = $this->input->post('gallons');
		$temperature = $this->input->post('temperature');
		$nitrate_level = $this->input->post('nitrate_level');
		$nitrite_level = $this->input->post('nitrite_level');
		$chlorine_level = $this->input->post('chlorine_level');
		$gh_level = $this->input->post('gh_level');
		$ph_level = $this->input->post('ph_level');
		$kh_level = $this->input->post('kh_level');
		$ammonia_level = $this->input->post('ammonia_level');
		$sg_level = $this->input->post('sg_level');
		
		$set = '';
		
		if( !empty( $aquarium_name ) ):
			$aquarium_name_esc = $this->db->escape( $aquarium_name );
		
			$aquarium_url = format_user_url( $aquarium_name );
			$aquarium_url_esc = $this->db->escape( $aquarium_url );
			
			$set .= "aquarium_name = {$aquarium_name_esc}, aquarium_url = {$aquarium_url_esc}, ";
		endif;
		
		if( !empty( $water_type ) ):
			$water_type_esc = $this->db->escape( $water_type );
		
			$set .= "ecosystem_id = {$water_type_esc}, ";
		endif;
		
		if( !empty( $gallons ) ):
			$gallons_esc = $this->db->escape( $gallons );
			
			$set .= "aquarium_gallons_size = {$gallons_esc}, ";
		endif;
		
		if( !empty( $set ) ):
			$set = rtrim( $set, ', ' );
		
			$query = "UPDATE aquariums SET {$set} WHERE account_id = {$account_id_esc} AND aquarium_id = {$aquarium_id_esc}";
			
			$sql = $this->db->query( $query );

		endif;
		
		
		$set = "aquarium_id = {$aquarium_id_esc}, ";
		
		if( !empty( $temperature ) ):
			$temperature_esc = $this->db->escape( $temperature );
			
			$set .= "aquarium_temperature_f = {$temperature_esc}, ";
		endif;
		
		if( !empty( $nitrate_level ) ):
			$nitrate_level_esc = $this->db->escape( $nitrate_level );
			
			$set .= "aquarium_nitrate_level = {$nitrate_level_esc}, ";
		endif;
		
		if( !empty( $nitrite_level ) ):
			$nitrite_level_esc = $this->db->escape( $nitrite_level );
			
			$set .= "aquarium_nitrite_level = {$nitrite_level_esc}, ";
		endif;

		if( !empty( $gh_level ) ):
			$gh_level_esc = $this->db->escape( $gh_level );
			
			$set .= "aquarium_gh_level = {$gh_level_esc}, ";
		endif;

		if( !empty( $chlorine_level ) ):
			$chlorine_level_esc = $this->db->escape( $chlorine_level );
			
			$set .= "aquarium_chlorine_level = {$chlorine_level_esc}, ";
		endif;

		if( !empty( $ph_level ) ):
			$ph_level_esc = $this->db->escape( $ph_level );
			
			$set .= "aquarium_ph_level = {$ph_level_esc}, ";
		endif;

		if( !empty( $kh_level ) ):
			$kh_level_esc = $this->db->escape( $kh_level );
			
			$set .= "aquarium_kh_level = {$kh_level_esc}, ";
		endif;
		
		if( !empty( $ammonia_level ) ):
			$ammonia_level_esc = $this->db->escape( $ammonia_level );
			
			$set .= "aquarium_ammonia_level = {$ammonia_level_esc}, ";
		endif;
		
		if( !empty( $sg_level ) ):
			$sg_level_esc = $this->db->escape( $sg_level );
			
			$set .= "aquarium_sg_level = {$sg_level_esc}, ";
		endif;
		
		if( !empty( $set ) ):
			
			$set = rtrim( $set, ', ' );
			$query = "INSERT INTO aquarium_readings SET {$set}";
		
			$sql = $this->db->query( $query );

			if( $this->db->affected_rows() > 0 ):
				# @se-points Points Earned - Reason: User updated an aquarium
				$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'user updated an aquarium' );
			
				return true;
			endif;
			
		endif;

		return false;
	} // function
	
	public function is_aquarium_account( $account_id, $aquarium_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$query = "SELECT * FROM aquariums WHERE account_id = {$account_id_esc} AND aquarium_id = {$aquarium_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function add_species( $account_id, $aquarium_id, $taxon_id ) {
		if( !$this->is_aquarium_account( $account_id, $aquarium_id ) )
			return false;
		
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "INSERT INTO aquarium_taxa VALUES( NULL, {$aquarium_id_esc}, {$taxon_id_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			# @se-points Points Earned - Reason: User added a species to an aquarium
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'user added a species to an aquarium' );
		
			return $this->db->insert_id();
		endif;

		return false;
	} // function
	
	public function remove_species( $account_id, $aquarium_id, $taxon_id ) {
		if( !$this->is_aquarium_account( $account_id, $aquarium_id ) )
			return false;
		
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "DELETE FROM aquarium_taxa WHERE aquarium_id = {$aquarium_id_esc} AND taxon_id = {$taxon_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function get_notes( $aquarium_id ) {
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$query = "SELECT * FROM aquarium_notes WHERE aquarium_id = {$aquarium_id_esc} ORDER BY note_timestamp DESC";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function add_note( $account_id, $aquarium_id ) {
		if( !$this->is_aquarium_account( $account_id, $aquarium_id ) )
			return false;
		
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		
		$note_content = $this->input->post( 'note_content' );
		$note_content_esc = $this->db->escape( $note_content );
		
		$query = "INSERT IGNORE INTO aquarium_notes VALUES( NULL, {$aquarium_id_esc}, {$note_content_esc}, CURRENT_TIMESTAMP() )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			# @se-points Points Earned - Reason: User added an aquarium
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'user added a note to an aquarium' );
		
			return $this->db->insert_id();
		endif;

		return false;
	} // function
	
	public function remove_note( $account_id, $aquarium_id, $note_id ) {
		if( !$this->is_aquarium_account( $account_id, $aquarium_id ) )
			return false;
		
		$aquarium_id_esc = $this->db->escape( $aquarium_id );
		$note_id_esc = $this->db->escape( $note_id );
		
		$query = "DELETE FROM aquarium_notes WHERE aquarium_id = {$aquarium_id_esc} AND note_id = {$note_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function get_aquarium_alerts( $aquarium_id, $aquarium_species = '', $aquarium_last_reading = '' ) {
		if( empty( $aquarium_id ) )
			return false;
			
		if( empty( $aquarium_species ) )
			$aquarium_species = $this->get_aquarium_species( $aquarium_id );
		
		if( empty( $aquarium_last_reading ) ):
			$readings = $this->get_aquarium_readings( $aquarium_id );
			if( !empty( $readings ) )
				$aquarium_last_reading = $readings[0];
		endif;
		
		if( empty( $aquarium_last_reading ) )
			return false;
		
#		print_r($aquarium_last_reading);
#		print_r($aquarium_species[0]);
#		die();
		$alerts = array();
		foreach( $aquarium_species as $species ):
		
			# water temperature alerts
			if( $aquarium_last_reading['aquarium_temperature_f'] ):
				if( $species['temperature_minimum_f'] != 0 && $aquarium_last_reading['aquarium_temperature_f'] <= $species['temperature_minimum_f'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "Water temperatures are to low for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/pic/article.cfm?aid=56",
						'article_title' => "How Temperature Change Affects Your Fish"
					);
				elseif( $species['temperature_maximum_f'] != 0 && $aquarium_last_reading['aquarium_temperature_f'] >= $species['temperature_maximum_f'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "Water temperatures are to high for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/pic/article.cfm?aid=56",
						'article_title' => "How Temperature Change Affects Your Fish"
					);
				endif;
			endif;
		
			# ph level alerts
			if( !empty( $aquarium_last_reading['aquarium_ph_level'] ) ):
				if( $species['ph_minimum'] != 0 && $aquarium_last_reading['aquarium_ph_level'] < $species['ph_minimum'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "pH levels are to low for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/PIC/article.cfm?aid=61",
						'article_title' => "Basic Water Chemistry Part 2: The pH Scale & Your Aquarium"
					);
				elseif( $species['ph_maximum'] != 0 && $aquarium_last_reading['aquarium_ph_level'] > $species['ph_maximum'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "pH levels are to high for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/PIC/article.cfm?aid=61",
						'article_title' => "Basic Water Chemistry Part 2: The pH Scale & Your Aquarium"
					);
				endif;
			endif;
			
			# kh alerts
			if( !empty( $aquarium_last_reading['aquarium_kh_level'] ) ):
				if( $species['kh_minimum'] != 0 && $aquarium_last_reading['aquarium_kh_level'] < $species['kh_minimum'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "kH levels are to low for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/pic/article.cfm?aid=60",
						'article_title' => "Basic Water Chemistry Part 1: Water Hardness"
					);
				elseif( $species['kh_maximum'] != 0 && $aquarium_last_reading['aquarium_kh_level'] > $species['kh_maximum'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "kH levels are to high for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/pic/article.cfm?aid=60",
						'article_title' => "Basic Water Chemistry Part 1: Water Hardness"
					);
				endif;
			endif;
			
			# sg alerts
			if( !empty( $aquarium_last_reading['aquarium_sg_level'] ) ):
				if( $species['sg_minimum'] != 0 && $aquarium_last_reading['aquarium_sg_level'] < $species['sg_minimum'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "Specific gravity levels are to low for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/PIC/article.cfm?aid=185",
						'article_title' => "How to Maintain Proper Specific Gravity"
					);
				elseif( $species['sg_maximum'] != 0 && $aquarium_last_reading['aquarium_sg_level'] > $species['sg_maximum'] ):
					$alerts[] = array(
						'severity' => 0,
						'message' => "Specific gravity levels are to high for {$species['taxon_name']}",
						'article_url' => "http://www.liveaquaria.com/PIC/article.cfm?aid=185",
						'article_title' => "How to Maintain Proper Specific Gravity"
					);
				endif;
			endif;
			
		endforeach;

		return $alerts;
	} // function
	
	public function get_aquarium_health_rating( $aquarium_id, $aquarium_species = '', $aquarium_alerts = '' ) {
		if( empty( $aquarium_id ) )
			return 0;
		
		if( empty( $aquarium_species ) )
			$aquarium_species = $this->get_aquarium_species( $aquarium_id );
		
		if( empty( $aquarium_alerts ) )
			$aquarium_alerts = $this->get_aquarium_alerts( $aquarium_id, $aquarium_species );
		
		$aquarium_readings = $this->get_aquarium_readings( $aquarium_id );
		
		$total_readings = count( $aquarium_readings );
		$total_species = count( $aquarium_species );
		$total_alerts = count( $aquarium_alerts );
		
		if( $total_readings == 0 && $total_species == 0 ):
			return 0;
		elseif( $total_alerts == 0 || $total_species == 0 ):
			return 5;
		endif;
		
		$rating = ( $total_species * 3 ) / $total_alerts;
		
		return floor($rating);
	} // function
	
} // class