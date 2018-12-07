<?php

class Se_db_browser_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function browser_filter( $filter = array() ) {
		$default_filter = array(
			'display' => '',
			'edible' => '',
			'nontoxic' => '',
			'instores' => ''
		);
		
		return array_merge( $default_filter, $filter );
	} // function
	
	public function load_nearby_species( $country_id, $territory_id = 0, $limit = 4, $offset = 0, $get_count = false, $filter = array() ) {
		$country_id_esc = $this->db->escape( $country_id );
		$territory_id_esc = $this->db->escape( $territory_id );
		
		$filter_join = '';
		$filter_where = '';
		
		$where_stmts = array();
		
		if( !empty( $filter ) && count(array_unique(array_values($filter))) > 0 ):
			$filter = $this->browser_filter( $filter );
			
			$filter_join .= "JOIN taxon_data USING(taxon_id)";
			
			if( !empty( $filter['display'] ) && $filter['display'] != 'all' ):
				
				switch( $filter['display'] ):
					case 'forbs':
						$where_stmts['display'] = "(taxon_data.key_term_id = '52' AND taxon_data.value_term_id = '44')";
						break;
					case 'shrubs':
						$where_stmts['display'] = "(taxon_data.key_term_id = '52' AND (taxon_data.value_term_id = '48' OR taxon_data.value_term_id = '49') )";
						break;
					case 'trees':
						$where_stmts['display'] = "(taxon_data.key_term_id = '52' AND taxon_data.value_term_id = '50')";
						break;
					case 'vines':
						$where_stmts['display'] = "(taxon_data.key_term_id = '52' AND taxon_data.value_term_id = '51')";
						break;
					case 'grasses':
						$where_stmts['display'] = "(taxon_data.key_term_id = '52' AND taxon_data.value_term_id = '45')";
						break;
					case 'all':
					default:
						break;
				endswitch;
				
			endif;
			
			if( !empty( $filter['nontoxic'] ) && $filter['nontoxic'] == 1 ):
				$where_stmts['nontoxic'] = "(taxon_data.key_term_id = '11' AND (taxon_data.value_term_id = '81' OR taxon_data.value_term_id IS NULL) )";
			endif;
			
			if( !empty( $filter['instores'] ) && $filter['instores'] == 1 ):
				$where_stmts['instores'] = "(taxon_data.key_term_id = '134' AND taxon_data.value_term_id = '176' )";
			endif;
			
			$filter_where .= " AND ( ";
			foreach( $where_stmts as $f => $query_string ):
				$filter_where .= "{$query_string} AND ";
			endforeach;
			$filter_where = rtrim( $filter_where, ' AND ' );
			$filter_where .= " ) ";
		endif;
		
		if( $get_count === true ):
			$query = "SELECT COUNT(DISTINCT taxa.taxon_id) as total
			FROM taxa
			JOIN taxon_locations USING(taxon_id)
			JOIN taxon_maps ON taxon_id = specie_id
			JOIN taxon_names USING(taxon_name_id)
			JOIN taxon_ranks USING(taxon_rank_id)
			{$filter_join}
			JOIN taxon_information USING(taxon_id)
			WHERE taxon_maps.kingdom_id = '75088' AND taxon_locations.country_id = {$country_id_esc} AND taxon_locations.territory_id = {$territory_id_esc}{$filter_where}";
		else:
			$query = "SELECT taxon_ranks.taxon_rank_id, taxon_ranks.rank_name, taxon_ranks.rank_url, taxa.taxon_id, taxon_names.taxon_name, taxon_names.taxon_url, images.image_filename, taxon_information.brief_description
			FROM taxa
			JOIN taxon_locations USING(taxon_id)
			JOIN taxon_maps ON taxon_id = specie_id
			JOIN taxon_names USING(taxon_name_id)
			JOIN taxon_ranks USING(taxon_rank_id)
			{$filter_join}
			JOIN taxon_information USING(taxon_id)
			LEFT JOIN images USING(image_id)
			WHERE taxon_maps.kingdom_id = '75088' AND taxon_locations.country_id = {$country_id_esc} AND taxon_locations.territory_id = {$territory_id_esc}{$filter_where} GROUP BY taxa.taxon_id ORDER BY taxon_locations.territory_id ASC, country_id ASC, taxon_names.taxon_name ASC";
			
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
	
		if( $get_count === true )
			return 0;
		else
			return array();
	} // function
	
	public function get_plant_by_url( $taxon_url ) {
		$taxon_url_esc = $this->db->escape( $taxon_url );
		
		$query = "SELECT taxa.*, taxon_names.*, taxon_ranks.*, images.*, taxon_descriptions.* 
		FROM taxon_names 
		JOIN taxa USING(taxon_name_id) 
		JOIN taxon_maps ON taxa.taxon_id = taxon_maps.specie_id
		JOIN taxon_ranks USING(taxon_rank_id) 
		LEFT JOIN images USING(image_id) 
		LEFT JOIN taxon_descriptions USING(taxon_id)
		WHERE taxon_maps.kingdom_id = '75088' AND taxon_names.taxon_url = {$taxon_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
} // class