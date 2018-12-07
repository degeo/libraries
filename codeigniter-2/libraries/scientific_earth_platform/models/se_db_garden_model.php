<?php

class Se_db_garden_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_garden_by_id( $garden_id ) {
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$query = "SELECT * FROM gardens JOIN (SELECT * FROM garden_readings GROUP BY garden_id DESC ORDER BY garden_last_reading DESC) as ar ON gardens.garden_id = ar.garden_id WHERE gardens.garden_id = {$garden_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function get_garden_readings( $garden_id ) {
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$query = "SELECT * FROM garden_readings WHERE garden_id = {$garden_id_esc} ORDER BY garden_last_reading ASC";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function get_garden_species( $garden_id ) {
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$query = "SELECT taxa.*, taxon_names.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
		FROM garden_taxa JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_information USING(taxon_id) LEFT JOIN taxon_images USING(taxon_id) LEFT JOIN images ON taxon_images.image_id = images.image_id LEFT JOIN taxon_behaviors USING(taxon_id) LEFT JOIN behaviors USING(behavior_id) WHERE garden_id = {$garden_id_esc} GROUP BY taxa.taxon_id ORDER BY taxon_names.taxon_name ASC";
		
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
	
	public function get_garden_discussions( $account_id, $limit = 10, $offset = 0, $taxa = '' ) {
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
	
	public function get_garden_images( $account_id, $limit = 10, $offset = 0, $taxa = '' ) {
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
				$where_stmts[] = "( taxon_information.garden_minimum_gallons <= {$gallons_esc} )";
			endif;
			
			if( array_key_exists( 'soil_depth', $filter ) && !empty($filter['soil_depth']) ):
				$soil_depth_esc = $this->db->escape( $filter['soil_depth'] );
				$where_stmts[] = "( taxon_information.root_depth_minimum_inches <= {$soil_depth_esc} )";
			endif;
			
			if( array_key_exists( 'temperature', $filter ) && !empty($filter['temperature']) ):
				$temp_esc = $this->db->escape( $filter['temperature'] );
				$where_stmts[] = "( taxon_information.temperature_minimum_f <= {$temp_esc} )";
			#	$where_stmts[] = "( taxon_information.temperature_minimum_f <= {$temp_esc} AND taxon_information.temperature_maximum_f >= {$temp_esc} )";
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
		
#		$ecosystem_id_esc = $this->db->escape( $filter['water_type'] );
		
		if( $get_count === true ):
			$query = "SELECT COUNT(DISTINCT taxa.taxon_id) as total FROM taxa JOIN taxon_names USING(taxon_name_id) JOIN taxon_information USING(taxon_id) JOIN taxon_maps ON taxa.taxon_id = taxon_maps.specie_id WHERE taxon_maps.kingdom_id = '75088' AND ( taxa.taxon_rank_id = '9' OR taxa.taxon_rank_id = '15' ){$filter_where}{$exclude_where}";
		else:
			if( $random === true ):
				$query = "SELECT taxa.*, taxon_names.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
				FROM taxa 
				JOIN taxon_names USING(taxon_name_id) 
				JOIN taxon_information USING(taxon_id) 
				JOIN taxon_maps ON taxa.taxon_id = taxon_maps.specie_id
				LEFT JOIN images USING(image_id) 
				LEFT JOIN taxon_behaviors USING(taxon_id) 
				LEFT JOIN behaviors USING(behavior_id) 
				WHERE taxon_maps.kingdom_id = '75088' AND ( taxa.taxon_rank_id = '9' OR taxa.taxon_rank_id = '15' ){$filter_where}{$exclude_where} 
				GROUP BY taxa.taxon_id ORDER BY RAND()";
			else:
				$query = "SELECT taxa.*, taxon_names.*, taxon_information.*, images.*, (SELECT COUNT(DISTINCT image_id) FROM taxon_images WHERE taxon_id = taxa.taxon_id) as total_images, behaviors.*, (SELECT COUNT(DISTINCT discussion_id) FROM taxon_discussions WHERE taxon_id = taxa.taxon_id) as total_discussions 
				FROM taxa 
				JOIN taxon_names USING(taxon_name_id) 
				JOIN taxon_information USING(taxon_id) 
				JOIN taxon_maps ON taxa.taxon_id = taxon_maps.specie_id
				LEFT JOIN images USING(image_id) 
				LEFT JOIN taxon_behaviors USING(taxon_id) 
				LEFT JOIN behaviors USING(behavior_id) 
				WHERE taxon_maps.kingdom_id = '75088' AND ( taxa.taxon_rank_id = '9' OR taxa.taxon_rank_id = '15' ){$filter_where}{$exclude_where} 
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
	
	public function update_garden( $account_id, $garden_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$garden_name = $this->input->post('garden_name');
		$water_type = $this->input->post('water_type');
		$gallons = $this->input->post('gallons');
		$temperature = $this->input->post('temperature');
		$nitrate_level = $this->input->post('nitrate_level');
		$nitrite_level = $this->input->post('nitrite_level');
		$chlorine_level = $this->input->post('chlorine_level');
		$gh_level = $this->input->post('gh_level');
		$ph_level = $this->input->post('ph_level');
		$kh_level = $this->input->post('kh_level');
		$sg_level = $this->input->post('sg_level');
		
		$set = '';
		
		if( !empty( $garden_name ) ):
			$garden_name_esc = $this->db->escape( $garden_name );
		
			$garden_url = format_user_url( $garden_name );
			$garden_url_esc = $this->db->escape( $garden_url );
			
			$set .= "garden_name = {$garden_name_esc}, garden_url = {$garden_url_esc}, ";
		endif;
		
		if( !empty( $soil_type ) ):
			$soil_type_esc = $this->db->escape( $soil_type );
		
			$set .= "soil_texture_id = {$soil_type_esc}, ";
		endif;
		
		if( !empty( $soil_depth ) ):
			$soil_depth_esc = $this->db->escape( $soil_depth );
			
			$set .= "garden_soil_depth_inches = {$soil_depth_esc}, ";
		endif;
		
		if( !empty( $set ) ):
			$set = rtrim( $set, ', ' );
		
			$query = "UPDATE gardens SET {$set} WHERE account_id = {$account_id_esc} AND garden_id = {$garden_id_esc}";
			
			$sql = $this->db->query( $query );

		endif;
		
		
		$set = "garden_id = {$garden_id_esc}, ";
		
		if( !empty( $temperature ) ):
			$temperature_esc = $this->db->escape( $temperature );
			
			$set .= "garden_temperature_f = {$temperature_esc}, ";
		endif;
		
		if( !empty( $precipitation_level ) ):
			$precipitation_level_esc = $this->db->escape( $precipitation_level );
			
#			$set .= "garden_precipitation_level = {$precipitation_level_esc}, ";
		endif;
		
		if( !empty( $ph_level ) ):
			$ph_level_esc = $this->db->escape( $ph_level );
			
			$set .= "garden_ph_level = {$ph_level_esc}, ";
		endif;
		
		if( !empty( $nitrogen_level ) ):
			$nitrogen_level_esc = $this->db->escape( $nitrogen_level );
			
#			$set .= "garden_nitrogen_level = {$nitrogen_level_esc}, ";
		endif;

		if( !empty( $phosphorous_level ) ):
			$phosphorous_level_esc = $this->db->escape( $phosphorous_level );
			
#			$set .= "garden_phosphorous_level = {$phosphorous_level_esc}, ";
		endif;

		if( !empty( $potash_level ) ):
			$potash_level_esc = $this->db->escape( $potash_level );
			
#			$set .= "garden_potash_level = {$potash_level_esc}, ";
		endif;
		
		if( !empty( $set ) ):
			
			$set = rtrim( $set, ', ' );
			$query = "INSERT INTO garden_readings SET {$set}";
		
			$sql = $this->db->query( $query );

			if( $this->db->affected_rows() > 0 ):
				return true;
			endif;
			
		endif;

		return false;
	} // function
	
	public function is_garden_account( $account_id, $garden_id ) {
		$account_id_esc = $this->db->escape( $account_id );
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$query = "SELECT * FROM gardens WHERE account_id = {$account_id_esc} AND garden_id = {$garden_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function add_species( $account_id, $garden_id, $taxon_id ) {
		if( !$this->is_garden_account( $account_id, $garden_id ) )
			return false;
		
		$garden_id_esc = $this->db->escape( $garden_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "INSERT IGNORE INTO garden_taxa VALUES( NULL, {$garden_id_esc}, {$taxon_id_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return false;
	} // function
	
	public function remove_species( $account_id, $garden_id, $taxon_id ) {
		if( !$this->is_garden_account( $account_id, $garden_id ) )
			return false;
		
		$garden_id_esc = $this->db->escape( $garden_id );
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "DELETE FROM garden_taxa WHERE garden_id = {$garden_id_esc} AND taxon_id = {$taxon_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function get_notes( $garden_id ) {
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$query = "SELECT * FROM garden_notes WHERE garden_id = {$garden_id_esc} ORDER BY note_timestamp DESC";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->result_array();
		endif;

		return array();
	} // function
	
	public function add_note( $account_id, $garden_id ) {
		if( !$this->is_garden_account( $account_id, $garden_id ) )
			return false;
		
		$garden_id_esc = $this->db->escape( $garden_id );
		
		$note_content = $this->input->post( 'note_content' );
		$note_content_esc = $this->db->escape( $note_content );
		
		$query = "INSERT IGNORE INTO garden_notes VALUES( NULL, {$garden_id_esc}, {$note_content_esc}, CURRENT_TIMESTAMP() )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return false;
	} // function
	
	public function remove_note( $account_id, $garden_id, $note_id ) {
		if( !$this->is_garden_account( $account_id, $garden_id ) )
			return false;
		
		$garden_id_esc = $this->db->escape( $garden_id );
		$note_id_esc = $this->db->escape( $note_id );
		
		$query = "DELETE FROM garden_notes WHERE garden_id = {$garden_id_esc} AND note_id = {$note_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
} // class