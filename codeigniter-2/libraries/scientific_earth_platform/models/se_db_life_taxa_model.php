<?php

class Se_db_life_taxa_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_taxa( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM life_taxa";
		else:
			$query = "SELECT life_taxa.life_taxon_id, life_taxa.life_taxon_rank_id, life_taxon_names.life_taxon_name, life_taxon_names.life_taxon_url, life_taxon_ranks.life_taxon_rank_name, life_taxon_ranks.life_taxon_rank_url, life_taxa.life_taxon_last_updated, (SELECT COUNT(*) FROM life_taxon_material_properties WHERE life_taxon_material_properties.life_taxon_id = life_taxa.life_taxon_id GROUP BY life_taxon_id) as total_material_properties, (SELECT COUNT(*) FROM life_taxon_data WHERE life_taxon_data.life_taxon_id = life_taxa.life_taxon_id GROUP BY life_taxon_data.life_taxon_id) as total_data FROM life_taxa JOIN life_taxon_ranks USING(life_taxon_rank_id) JOIN life_taxon_names USING(life_taxon_name_id) ORDER BY life_taxon_id DESC";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_taxa_ranks( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM life_taxon_ranks";
		else:
			$query = "SELECT life_taxon_ranks.life_taxon_rank_id, life_taxon_ranks.rank_name, life_taxon_ranks.rank_url, life_taxon_ranks.rank_sort_order FROM life_taxon_ranks ORDER BY rank_sort_order DESC";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_taxa_refined( $args = array(), $limit = 20, $offset = 0, $get_count = false ) {
		$join = '';
		$and_where = '';
		
		if( !empty( $args ) ):
		
			$default_args = array(
				'in_kingdom' => false, # @dev int kingdom_id
				'in_group' => false, # @dev int value_term_id
				'moisture_use' => false, # @dev int value_term_id
				'shade_tolerance' => false, # @dev int value_term_id
				'drought_tolerance' => false, # @dev int value_term_id
				'anaerobic_tolerance' => false, # @dev int value_term_id
				'salinity_tolerance' => false, # @dev int value_term_id
				'duration' => false, # @dev int value_term_id
				'lifespan' => false, # @dev int value_term_id
				'growth_habit' => false, # @dev int value_term_id
				'active_growth_period' => false, # @dev int value_term_id
				'bloom_period' => false, # @dev int value_term_id
				'carbon_to_nitrogen_ratio' => false, # @dev int value_term_id
				'after_harvest_regrowth_period' => false, # @dev int value_term_id
				'known_allelopath' => false, # @dev int value_term_id
				'commercial_availability' => false, # @dev int value_term_id
				'growth_rate' => false, # @dev int value_term_id
				'nitrogen_fixation' => false, # @dev int value_term_id
				'resprout_ability' => false, # @dev int value_term_id
				'flower_color' => false, # @dev int value_term_id
				'foliage_color' => false, # @dev int value_term_id
				'fruit_color' => false, # @dev int value_term_id
				'ph_min' => false, # @dev int ph_minimum
				'ph_max' => false, # @dev int ph_maximum
				'temp_min_f' => false, # @dev int ph_maximum
			);
	
			$args = array_merge( $default_args, $args );
	
			extract( $args );
		
			if( $in_kingdom ):
				$kingdom_id_esc = $this->db->escape( $in_kingdom );
				
				$join .= " JOIN taxon_maps ON taxa.taxon_id = taxon_maps.specie_id";
				$and_where .= " AND taxon_maps.kingdom_id = {$kingdom_id_esc}";
			endif;
		
			if( $ph_min || $ph_max || $temp_min_f ):
			
				$join .= " JOIN taxon_information USING(taxon_id)";
			
				if( $temp_min_f ):
					$temp_min_f_esc = $this->db->escape( $temp_min_f );
					$and_where .= " AND ( taxon_information.temperature_minimum_f >= {$temp_min_f_esc} )";
				endif;
			
				if( $ph_min || $ph_max ):
					if( !$ph_min )
						$ph_min = 0;
				
					if( !$ph_max )
						$ph_max = 10;
				
					$ph_min_esc = $this->db->escape( $ph_min );
					$ph_max_esc = $this->db->escape( $ph_max );
					
					$and_where .= " AND ( taxon_information.ph_minimum >= {$ph_min_esc} AND taxon_information.ph_maximum <= {$ph_max_esc} )";
				endif;
				
			endif;
		
			if( 
				$in_group || $moisture_use || $shade_tolerance || $drought_tolerance || $anaerobic_tolerance || $salinity_tolerance || $duration || $lifespan || $growth_habit ||
				$active_growth_period || $bloom_period || $carbon_to_nitrogen_ratio || $after_harvest_regrowth_period || $known_allelopath || $commercial_availability ||
				$growth_rate || $nitrogen_fixation || $resprout_ability || $flower_color || $foliage_color || $fruit_color
				 ):
			
				$join .= " JOIN taxon_data USING(taxon_id)";
			
				if( $in_group ):
					$group_id_esc = $this->db->escape( $in_group );
					$and_where .= " AND ( taxon_data.key_term_id = '60' AND taxon_data.value_term_id = {$group_id_esc} )";
				endif;
				
				if( $moisture_use ):
					$moisture_use_esc = $this->db->escape( $moisture_use );
					$and_where .= " AND ( taxon_data.key_term_id = '115' AND taxon_data.value_term_id = {$moisture_use_esc} )";
				endif;
				
				if( $shade_tolerance ):
					$shade_tolerance_esc = $this->db->escape( $shade_tolerance );
					$and_where .= " AND ( taxon_data.key_term_id = '117' AND taxon_data.value_term_id = {$shade_tolerance_esc} )";
				endif;
			
				if( $drought_tolerance ):
					$drought_tolerance_esc = $this->db->escape( $drought_tolerance );
					$and_where .= " AND ( taxon_data.key_term_id = '110' AND taxon_data.value_term_id = {$drought_tolerance_esc} )";
				endif;
				
				if( $anaerobic_tolerance ):
					$anaerobic_tolerance_esc = $this->db->escape( $anaerobic_tolerance );
					$and_where .= " AND ( taxon_data.key_term_id = '107' AND taxon_data.value_term_id = {$anaerobic_tolerance_esc} )";
				endif;
			
				if( $salinity_tolerance ):
					$salinity_tolerance_esc = $this->db->escape( $salinity_tolerance );
					$and_where .= " AND ( taxon_data.key_term_id = '116' AND taxon_data.value_term_id = {$salinity_tolerance_esc} )";
				endif;
				
				if( $duration ):
					$duration_esc = $this->db->escape( $duration );
					$and_where .= " AND ( taxon_data.key_term_id = '8' AND taxon_data.value_term_id = {$duration_esc} )";
				endif;
				
				if( $lifespan ):
					$lifespan_esc = $this->db->escape( $lifespan );
					$and_where .= " AND ( taxon_data.key_term_id = '9' AND taxon_data.value_term_id = {$lifespan_esc} )";
				endif;
			
				if( $growth_habit ):
					$growth_habit_esc = $this->db->escape( $growth_habit );
					$and_where .= " AND ( taxon_data.key_term_id = '52' AND taxon_data.value_term_id = {$growth_habit_esc} )";
				endif;
			
				if( $active_growth_period ):
					$active_growth_period_esc = $this->db->escape( $active_growth_period );
					$and_where .= " AND ( taxon_data.key_term_id = '61' AND taxon_data.value_term_id = {$active_growth_period_esc} )";
				endif;
				
				if( $bloom_period ):
					$bloom_period_esc = $this->db->escape( $bloom_period );
					$and_where .= " AND ( taxon_data.key_term_id = '133' AND taxon_data.value_term_id = {$bloom_period_esc} )";
				endif;
				
				if( $carbon_to_nitrogen_ratio ):
					$carbon_to_nitrogen_ratio_esc = $this->db->escape( $carbon_to_nitrogen_ratio );
					$and_where .= " AND ( taxon_data.key_term_id = '83' AND taxon_data.value_term_id = {$carbon_to_nitrogen_ratio_esc} )";
				endif;
				
				if( $after_harvest_regrowth_period ):
					$after_harvest_regrowth_period_esc = $this->db->escape( $after_harvest_regrowth_period );
					$and_where .= " AND ( taxon_data.key_term_id = '77' AND taxon_data.value_term_id = {$after_harvest_regrowth_period_esc} )";
				endif;
				
				if( $known_allelopath ):
					$known_allelopath_esc = $this->db->escape( $known_allelopath );
					$and_where .= " AND ( taxon_data.key_term_id = '98' AND taxon_data.value_term_id = {$known_allelopath_esc} )";
				endif;
				
				if( $commercial_availability ):
					$commercial_availability_esc = $this->db->escape( $commercial_availability );
					$and_where .= " AND ( taxon_data.key_term_id = '134' AND taxon_data.value_term_id = {$commercial_availability_esc} )";
				endif;
				
				if( $growth_rate ):
					$growth_rate_esc = $this->db->escape( $growth_rate );
					$and_where .= " AND ( taxon_data.key_term_id = '10' AND taxon_data.value_term_id = {$growth_rate_esc} )";
				endif;
				
				if( $nitrogen_fixation ):
					$nitrogen_fixation_esc = $this->db->escape( $nitrogen_fixation );
					$and_where .= " AND ( taxon_data.key_term_id = '12' AND taxon_data.value_term_id = {$nitrogen_fixation_esc} )";
				endif;
				
				if( $resprout_ability ):
					$resprout_ability_esc = $this->db->escape( $resprout_ability );
					$and_where .= " AND ( taxon_data.key_term_id = '102' AND taxon_data.value_term_id = {$resprout_ability_esc} )";
				endif;
				
				if( $flower_color ):
					$flower_color_esc = $this->db->escape( $flower_color );
					$and_where .= " AND ( taxon_data.key_term_id = '87' AND taxon_data.value_term_id = {$flower_color_esc} )";
				endif;

				if( $foliage_color ):
					$foliage_color_esc = $this->db->escape( $foliage_color );
					$and_where .= " AND ( taxon_data.key_term_id = '88' AND taxon_data.value_term_id = {$foliage_color_esc} )";
				endif;

				if( $fruit_color ):
					$fruit_color_esc = $this->db->escape( $fruit_color );
					$and_where .= " AND ( taxon_data.key_term_id = '93' AND taxon_data.value_term_id = {$fruit_color_esc} )";
				endif;
				
			endif;
			
		endif;
		
		if( $get_count === true ):
			$query = "SELECT DISTINCT COUNT(*) as total FROM taxa JOIN taxon_names USING(taxon_name_id){$join} WHERE ( taxa.taxon_rank_id = '9' OR taxa.taxon_rank_id = '15' OR taxa.taxon_rank_id = '18' ){$and_where} ORDER BY taxon_name ASC";
		else:
			$query = "SELECT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url, images.image_thumbnail_filename, images.image_filename FROM taxa JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id){$join} LEFT JOIN images USING(image_id) WHERE ( taxa.taxon_rank_id = '9' OR taxa.taxon_rank_id = '15' OR taxa.taxon_rank_id = '18' ){$and_where} ORDER BY taxon_name ASC";

			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				$results = $sql->result_array();
				foreach( $results as $k => $row ):
					$results[$k]['data'] = $this->Se_db_taxon->get_taxon_data( $row['taxon_id'] );
				endforeach;

				return $results;
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_taxa_by_rank_id( $rank_id, $limit = 12, $offset = 0, $get_count = false ) {
		$rank_id_esc = $this->db->escape( $rank_id );
		
		if( $get_count === true ):
			$query = "SELECT COUNT(DISTINCT taxa.taxon_id) as total FROM taxa JOIN taxon_names USING(taxon_name_id) WHERE taxa.taxon_rank_id = {$rank_id_esc} ORDER BY taxon_name ASC";
		else:
			$query = "SELECT * FROM taxa JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) LEFT JOIN images USING(image_id) LEFT JOIN taxon_information USING(taxon_id) WHERE taxa.taxon_rank_id = {$rank_id_esc} GROUP BY taxa.taxon_id ORDER BY taxon_name ASC";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_taxa_kingdoms() {
		$query = "SELECT taxa.taxon_id, taxon_names.taxon_name, taxon_names.taxon_url FROM taxon_ranks JOIN taxa USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) WHERE taxon_ranks.taxon_rank_id = '3' GROUP BY taxa.taxon_id ORDER BY taxon_name ASC";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxa_data( $limit = 20, $offset = 0 ) {
		$query = "SELECT * FROM life_taxon_data";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxa_data_by_key( $key_term_id ) {
		$key_term_id_esc = $this->db->escape( $key_term_id );
		
		$query = "SELECT terms.term_id, terms.term_name, terms.term_url FROM taxon_data JOIN terms ON value_term_id = term_id WHERE key_term_id = {$key_term_id_esc} GROUP BY value_term_id ORDER BY terms.term_name ASC";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_random_species() {
		$query = "SELECT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url FROM taxa JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) WHERE taxa.taxon_rank_id = '9' ORDER BY RAND() LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_updated( $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT DISTINCT COUNT(*) as total FROM taxon_updates JOIN updates USING(update_id) JOIN taxa USING(taxon_id) JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_information USING(taxon_id) GROUP BY taxon_updates.taxon_id ORDER BY updates.update_timestamp DESC";
		else:
			$query = "SELECT DISTINCT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url, taxon_information.brief_description, images.image_filename, images.image_thumbnail_filename FROM taxon_updates JOIN updates USING(update_id) JOIN taxa USING(taxon_id) JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_information USING(taxon_id) LEFT JOIN images USING(image_id) GROUP BY taxon_updates.taxon_id ORDER BY updates.update_timestamp DESC";
			
			$query = limit_query( $query, $limit, $offset );
		endif;
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $get_count === true ):
				$results = $sql->row_array();
				return $results['total'];
			else:
				return $sql->result_array();
			endif;
		endif;
		
		return array();
	} // function
	
	public function get_popular( $limit = 10, $offset = 0 ) {
		$query = "";
		return array();
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_nearby( $limit = 10, $offset = 0 ) {
		$query = "";
		return array();
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_articles( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM article_taxa JOIN articles USING(article_id) JOIN taxon_articles USING(article_id)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_categories( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM taxon_categories JOIN categories USING(category_id) GROUP BY taxon_categories.category_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_resources( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM taxon_resources JOIN resources USING(resource_id) GROUP BY taxon_resources.resource_id";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_discussions( $limit = 10, $offset = 0 ) {
		$query = "SELECT * FROM taxon_discussions JOIN discussions USING(discussion_id) JOIN taxa USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) WHERE discussions.discussion_subject IS NOT NULL AND discussions.discussion_subject != ''";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function lookup_names( $search_term, $limit = 10, $offset = 0 ) {
		if( empty( $search_term ) )
			return array();
		
		$search_term_esc = $this->db->escape( "%{$search_term}%" );
/*				
		$query = "SELECT taxon_id, taxon_names.taxon_name, tns.taxon_name as common_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url
		FROM taxa
		JOIN taxon_names USING(taxon_name_id)
		JOIN taxon_ranks USING(taxon_rank_id)
		LEFT JOIN taxon_synonyms USING( taxon_id )
		LEFT JOIN taxon_names as tns ON taxon_synonyms.taxon_name_id = tns.taxon_name_id
		WHERE ( taxa.taxon_rank_id IN (9,15,17,18) ) {$term_conditions}
		GROUP BY taxa.taxon_id, tns.taxon_name, taxon_names.taxon_name
		ORDER BY tns.taxon_name ASC, taxon_names.taxon_name ASC";
---
		
		$term_conditions = "";
		if( str_word_count( $search_term ) > 1 ):
			$term_conditions = "AND ( MATCH( taxon_names.taxon_name ) AGAINST ( {$search_term_esc} ) OR MATCH( tns.taxon_name ) AGAINST ( {$search_term_esc} ) )";
		else:
			$term_conditions = "AND ( MATCH( taxon_names.taxon_name ) AGAINST ( {$search_term_esc} ) OR MATCH( tns.taxon_name ) AGAINST ( {$search_term_esc} ) )";
		endif;
		
		if( empty( $term_conditions ) )
			return array();
*/
		$query = "SELECT _index_taxon_names.*, ( MATCH( common_name ) AGAINST ( {$search_term_esc} IN BOOLEAN MODE ) + MATCH( taxon_name ) AGAINST ( {$search_term_esc} IN BOOLEAN MODE ) ) as relevance
				FROM _index_taxon_names
				WHERE ( taxon_rank_id IN (9,15,18) )
				AND ( MATCH( taxon_name ) AGAINST ( {$search_term_esc} ) OR MATCH( common_name ) AGAINST ( {$search_term_esc} ) )
				ORDER BY relevance DESC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class