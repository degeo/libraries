<?php

class Se_db_life_taxon_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_taxon_by_url( $rank_url, $taxon_url ) {
		$rank_url_esc = $this->db->escape( $rank_url );
		$taxon_url_esc = $this->db->escape( $taxon_url );
		
		$where = "taxon_names.taxon_url = {$taxon_url_esc}";
		if( !empty( $rank_url ) )
			$where .= " AND taxon_ranks.rank_url = {$rank_url_esc}";
		
		$query = "SELECT * FROM taxon_names JOIN taxa USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) LEFT JOIN images USING(image_id) LEFT JOIN taxon_information USING(taxon_id) LEFT JOIN taxon_descriptions USING(taxon_id) WHERE {$where} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_taxon_by_id( $taxon_id ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxa JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id) LEFT JOIN taxon_information USING(taxon_id) LEFT JOIN images USING(image_id) WHERE taxa.taxon_id = {$taxon_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_taxon_synonyms( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_synonyms JOIN taxon_names USING(taxon_name_id) JOIN taxa USING(taxon_id) WHERE taxa.taxon_name_id != taxon_synonyms.taxon_name_id AND taxon_synonyms.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_taxonomy( $taxon_id, $rank = '', $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxonomy_maps WHERE";
		
		switch( str_replace('-','',strtolower($rank)) ):
			case 'kingdom':
				$query .= " kingdom_id = '{$taxon_id}'";
				break;
			case 'subkingdom':
				$query .= " sub_kingdom_id = '{$taxon_id}'";
				break;
			case 'phylum':
				$query .= " phylum_id = '{$taxon_id}'";
				break;
			case 'superdivision':
				$query .= " super_division_id = '{$taxon_id}'";
				break;
			case 'division':
				$query .= " division_id = '{$taxon_id}'";
				break;
			case 'subdivision':
				$query .= " sub_division_id = '{$taxon_id}'";
				break;
			case 'superclass':
				$query .= " super_class_id = '{$taxon_id}'";
				break;
			case 'class':
				$query .= " class_id = '{$taxon_id}'";
				break;
			case 'subclass':
				$query .= " sub_class_id = '{$taxon_id}'";
				break;
			case 'order':
				$query .= " order_id = '{$taxon_id}'";
				break;
			case 'superfamily':
				$query .= " super_family_id = '{$taxon_id}'";
				break;
			case 'family':
				$query .= " family_id = '{$taxon_id}'";
				break;
			case 'genus':
				$query .= " genus_id = '{$taxon_id}'";
				break;
			case 'species':
				$query .= " species_id = '{$taxon_id}'";
				break;
			case 'subspecies':
			case 'infraspecies':
				$query .= " sub_species_id = '{$taxon_id}'";
				break;
			default:
				break;
		endswitch;
		
		$query .= " LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->row_array();
			
			$data = array(
#				'domain' => $this->get_taxon_by_id( $result['domain_id'] ),
				'kingdom' => $this->get_taxon_by_id( $result['kingdom_id'] ),
				'subkingdom' => $this->get_taxon_by_id( $result['sub_kingdom_id'] ),
				'phylum' => $this->get_taxon_by_id( $result['phylum_id'] ),
				'superdivision' => $this->get_taxon_by_id( $result['super_division_id'] ),
				'division' => $this->get_taxon_by_id( $result['division_id'] ),
				'subdivision' => $this->get_taxon_by_id( $result['sub_division_id'] ),
				'class' => $this->get_taxon_by_id( $result['class_id'] ),
				'subclass' => $this->get_taxon_by_id( $result['sub_class_id'] ),
				'order' => $this->get_taxon_by_id( $result['order_id'] ),
				'superfamily' => $this->get_taxon_by_id( $result['super_family_id'] ),
				'family' => $this->get_taxon_by_id( $result['family_id'] ),
				'genus' => $this->get_taxon_by_id( $result['genus_id'] ),
				'species' => $this->get_taxon_by_id( $result['species_id'] ),
				'subspecies' => $this->get_taxon_by_id( $result['sub_species_id'] )
			);
			
			switch( $rank ):
				case 'species':
					array_pop( $data );
					array_pop( $data );
					break;
				default:
					array_pop( $data );
			endswitch;
			
			return $data;
		endif;
		
		return array();
	} // function
	
	public function get_taxon_rank_synonym( $rank, $rank_data ) {
			if( empty( $rank_data ) )
				return '';
		
			$rank = strtolower($rank);
			$type = '';
			
			if( $rank == 'kingdom' ):
			
				switch( $rank_data['taxon_name'] ):
					case 'Animalia':
						$type = 'Animal';
						break;
					case 'Plantae':
						$type = 'Plant';
						break;
					default:
						$type = '';
						break;
				endswitch;
			
			elseif( $rank == 'phylum' ):

				switch( $rank_data['taxon_name'] ):
					case 'Nematoda':
						$type = 'Nematode';
						break;
					default:
						$type = '';
						break;
				endswitch;
				
			elseif( $rank == 'superclass' ):
			
				switch( $rank_data['taxon_name'] ):
					default:
						$type = '';
						break;
				endswitch;
			
			elseif( $rank == 'class' ):
				
				switch( $rank_data['taxon_name'] ):
					case 'Actinopterygii':
					case 'Agnatha':
					case 'Chondrichthyes':
					case 'Placodermi':
					case 'Osteichthyes':
						$type = 'Fish';
						break;
					case 'Aves':
						$type = 'Bird';
						break;
					case 'Insecta':
						$type = 'Insect';
						break;
					case 'Mammalia':
						$type = 'Mammal';
						break;
					case 'Reptilia':
						$type = 'Reptile';
						break;
					default:
						$type = '';
						break;
				endswitch;
			
			elseif( $rank == 'order' ):

				switch( $rank_data['taxon_name'] ):
					case 'Siluriformes':
						$type = 'Catfish';
						break;
					default:
						$type = '';
						break;
				endswitch;
			
			elseif( $rank == 'family' ):
			
				switch( $rank_data['taxon_name'] ):
					case 'Spheniscidae':
						$type = 'Penguin';
						break;
					case 'Lamiaceae':
						$type = 'Mint';
						break;
					case 'Solanaceae':
						$type = 'Nightshade';
						break;
					default:
						$type = '';
						break;
				endswitch;
			
			endif;
			
			return $type;
	} // function
	
	public function get_taxon_interactions( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_interactions as ti JOIN interaction_types USING(interaction_type_id) LEFT JOIN articles ON ti.article_id = articles.article_id WHERE ti.x_taxon = {$taxon_id_esc} OR ti.y_taxon = {$taxon_id_esc}";
		
#		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			foreach( $result as $k => $row ):
				$result[$k]['x_taxon'] = $this->get_taxon_by_id( $row['x_taxon'] );
				$result[$k]['y_taxon'] = $this->get_taxon_by_id( $row['y_taxon'] );
			endforeach;
			
			return $result;
		endif;
		
		return array();
	} // function
	
	public function get_taxon_data( $taxon_id, $limit = 10, $offset = 0, $grouped_results = true ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT t1.term_id as key_term_id, t1.term_name as key_term_name, t1.term_url as key_term_url, a1.article_url as key_article_url, a1.article_summary as key_article_summary, t2.term_id as value_term_id, t2.term_name as value_term_name, t2.term_url as value_term_url, a2.article_url as value_article_url, a2.article_summary as value_article_summary FROM taxon_data as td1 JOIN terms as t1 ON t1.term_id = td1.key_term_id LEFT JOIN articles as a1 ON a1.article_id = t1.article_id JOIN terms as t2 ON t2.term_id = td1.value_term_id LEFT JOIN articles as a2 ON a2.article_id = t2.article_id WHERE td1.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$data = $sql->result_array();
			
			if( TRUE === $grouped_results ):
				$darrel = &$data;
				foreach( $data as $key => $set ):
					if( count($data) <= 1 )
						break;
					
					$key_id = $set['key_term_id'];
					$value_id = $set['value_term_id'];
				
					foreach( $data as $k => $s ):
						if( count($data) <= 1 )
							break;
						
						if( $k != $key && $s['key_term_id'] == $key_id ):
							$darrel[$key]['values'][] = $s;
							unset($darrel[$k]);
						endif;
					endforeach;

				endforeach;
			
				return $darrel;
			endif;
			
			return $data;
		endif;
		
		return array();
	} // function
	
	public function get_taxon_recipes( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM recipe_taxa JOIN recipes USING(recipe_id) WHERE recipe_taxa.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_journals( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM journal_taxa JOIN journals USING(journal_id) WHERE journal_taxa.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_collections( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM collection_taxa JOIN collections USING(collection_id) WHERE collection_taxa.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_articles( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_articles JOIN articles USING(article_id) WHERE taxon_articles.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_categories( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_categories JOIN categories USING(category_id) WHERE taxon_categories.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_resources( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_resources JOIN resources USING(resource_id) WHERE taxon_resources.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_discussions( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_discussions JOIN discussions USING(discussion_id) JOIN accounts USING(account_id) WHERE taxon_discussions.taxon_id = {$taxon_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			foreach( $result as $k => $row ):
				$result[$k]['discussion_children'] = $this->Se_db_discussions->get_discussions( $row['discussion_id'] );
			endforeach;
			
			return $result;
		endif;
		
		return array();
	} // function
	
	public function get_taxon_images( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_images JOIN images USING(image_id) WHERE taxon_images.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_locations( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_locations JOIN geodata_countries USING(country_id) LEFT JOIN geodata_country_territories USING(territory_id) WHERE taxon_locations.taxon_id = {$taxon_id_esc}";
		
#		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_rating( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_ratings JOIN ratings USING(rating_id) WHERE taxon_ratings.taxon_id = {$taxon_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_terms( $taxon_id, $limit = 10, $offset = 0 ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$query = "SELECT * FROM taxon_terms JOIN terms USING(term_id) LEFT JOIN articles USING(article_id) WHERE taxon_terms.taxon_id = {$taxon_id_esc} GROUP BY terms.article_id ORDER BY terms.term_name ASC";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function insert_material_property( $taxon_id, $material_property_id, $material_property_value_id ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		$material_property_id_esc = $this->db->escape( $material_property_id );
		$material_property_value_id_esc = $this->db->escape( $material_property_value_id );
		
		$query = "INSERT INTO life_taxon_material_properties VALUES( NULL, {$taxon_id_esc}, {$material_property_id_esc}, {$material_property_value_id_esc} )";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // functions
	
} // class