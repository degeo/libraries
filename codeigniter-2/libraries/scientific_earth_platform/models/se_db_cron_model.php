<?php

class Se_db_cron_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_taxa( $limit = 10, $offset = 0, $random = false ) {
		$query = "SELECT taxa.taxon_id, taxa.taxon_rank_id, taxon_names.taxon_name, taxon_names.taxon_url, taxon_ranks.rank_name, taxon_ranks.rank_url FROM taxa JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_names USING(taxon_name_id) WHERE (col_verified = 0 || col_verified IS NULL) AND ( taxon_rank_id = '9' OR taxon_rank_id = '15' OR taxon_rank_id = '18' ) GROUP BY taxon_name_id";
		
		if( $random === true ):
			$query .= " ORDER BY RAND()";
		else:
			$query .= " ORDER BY taxon_name ASC";
		endif;
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_taxon_rank_id( $rank_name ) {
		$ranks = array(
			'life' => 1,
			'domain' => 2,
			'kingdom' => 3,
			'phylum' => 4,
			'class' => 5,
			'order' => 6,
			'family' => 7,
			'genus' => 8,
			'species' => 9,
			'division' => 10,
			'sub-class' => 11,
			'subclass' => 11,
			'sub-division' => 12,
			'subdivision' => 12,
			'super-division' => 13,
			'superdivision' => 13,
			'sub-kingdom' => 14,
			'subkingdom' => 14,
			'infraspecies' => 15,
			'super-family' => 16,
			'superfamily' => 16,
			'' => 17,
			'sub-species' => 18,
			'subspecies' => 18,
			'super-class' => 19,
			'superclass' => 19
		);
		
		$rank_name = strtolower($rank_name);
		if( array_key_exists( $rank_name, $ranks ) )
			return $ranks[$rank_name];
		
		return false;
	} // function
	
	public function get_taxon_name_id( $taxon_name ) {
		$taxon_name_esc = $this->db->escape( $taxon_name );
		$taxon_url_esc = $this->db->escape(str_replace(' ','-',strtolower($taxon_name)));
		
		$query = "SELECT taxon_name_id FROM taxon_names WHERE taxon_name = {$taxon_name_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$results = $sql->row_array();
			return $results['taxon_name_id'];
		else:
			$query = "INSERT INTO taxon_names VALUES( NULL, {$taxon_name_esc}, {$taxon_url_esc} )";
		endif;
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return false;
	} // function
	
	public function get_taxon_id( $taxon_rank_id, $taxon_name_id ) {
		$taxon_rank_id = $this->db->escape( $taxon_rank_id );
		$taxon_name_id = $this->db->escape( $taxon_name_id );
		
		$query = "SELECT taxon_id FROM taxa WHERE taxon_rank_id = {$taxon_rank_id} AND taxon_name_id = {$taxon_name_id} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$results = $sql->row_array();
			return $results['taxon_id'];
		else:
			$query = "INSERT INTO taxa VALUES( {$taxon_rank_id}, NULL, {$taxon_name_id}, NULL, NULL, 1 )";
		endif;
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return false;
	} // function
	
	public function edit_taxon_description( $taxon_id, $description_content ) {
		$this->edit_taxon_brief_description( $taxon_id, $description_content );
		
		$taxon_id_esc = $this->db->escape( $taxon_id );
		$description_content_esc = $this->db->escape( $description_content );
		
		$brief_description_content = '';
		if( !empty( $description_content ) ):
			$brief = explode( "\n", $description_content );
			$brief_description_content = $brief[0];
		endif;
		$brief_description_content_esc = $this->db->escape( $brief_description_content );
		
		$query = "SELECT long_description FROM taxon_descriptions WHERE taxon_id = '{$taxon_id}' LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$query = "UPDATE taxon_descriptions SET long_description = {$description_content_esc} WHERE taxon_id = {$taxon_id_esc}";
		else:
			$query = "INSERT INTO taxon_descriptions SET taxon_id = {$taxon_id_esc}, long_description = {$description_content_esc}";
		endif;
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function edit_taxon_brief_description( $taxon_id, $description_content ) {
		$taxon_id_esc = $this->db->escape( $taxon_id );
		$description_content_esc = $this->db->escape( $description_content );
		
		$brief_description_content = '';
		if( !empty( $description_content ) ):
			$brief = explode( "\n", $description_content );
			$brief_description_content = $brief[0];
		endif;
		$brief_description_content_esc = $this->db->escape( $brief_description_content );
		
		$query = "SELECT brief_description FROM taxon_information WHERE taxon_id = '{$taxon_id}' LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$query = "UPDATE taxon_information SET brief_description = {$brief_description_content_esc} WHERE taxon_id = {$taxon_id_esc}";
		else:
			$query = "INSERT INTO taxon_information SET taxon_id = {$taxon_id_esc}, brief_description = {$brief_description_content_esc}";
		endif;
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function edit_taxon_synonyms( $taxon_id, $synonyms = array() ) {
		foreach( $synonyms as $synonym ):
			$this->add_taxon_synonym( $taxon_id, $synonym['name'] );
		endforeach;
	} // function
	
	public function add_taxon_synonym( $taxon_id, $synonym ) {
		# does synonym exist in synonym table? get synonym id
		# does synonym exist taxon_synonyms tables? get taxon_synonyms id (not really)
		$taxon_id_esc = $this->db->escape( $taxon_id );
		$synonym_esc = $this->db->escape( $synonym );
		$synonym_url_esc = $this->db->escape(str_replace(' ','-',strtolower($synonym)));
		
		$query = "SELECT taxon_name_id FROM taxon_names WHERE taxon_url = {$synonym_url_esc} LIMIT 1";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$results = $sql->row_array();
			$taxon_name_id = $results['taxon_name_id'];
		else:
			$query = "INSERT INTO taxon_names VALUES( NULL, {$synonym_esc}, {$synonym_url_esc} )";
			
			$sql = $this->db->query( $query );
			
			if( $this->db->affected_rows() > 0 ):
				 $taxon_name_id = $this->db->insert_id();
			else:
				return false;
			endif;
		endif;
		
		$query = "SELECT taxon_synonym_id FROM taxon_synonyms WHERE taxon_id = {$taxon_id_esc} AND taxon_name_id = {$taxon_name_id} LIMIT 1";

		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			$results = $sql->row_array();
			$taxon_synonym_id = $results['taxon_synonym_id'];
		else:
			$query = "INSERT INTO taxon_synonyms VALUES( NULL, {$taxon_name_id}, {$taxon_id} )";
			
			$sql = $this->db->query( $query );
			
			if( $this->db->affected_rows() > 0 ):
				 $taxon_synonym_id = $this->db->insert_id();
			else:
				return false;
			endif;
		endif;
		
		if( !empty( $taxon_synonym_id ) ):
			return true;
		endif;
			
		return false;
	} // function
	
	public function add_col_classification( $taxon_id, $taxonomy ) {
		if( !empty( $taxonomy ) ):
			$set = "";
			
			foreach( $taxonomy as $child_taxon ):
				$taxon_name_id = $this->get_taxon_name_id( $child_taxon['name'] );
				
				switch( $child_taxon['rank'] ):
					case 'Kingdom':
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'kingdom' ), $taxon_name_id );
						$set .= "kingdom_id = '{$case_id}', ";
						break;
					case 'Subkingdom':
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'subkingdom' ), $taxon_name_id );
						$set .= "sub_kingdom_id = '{$case_id}', ";        
						break;                                            
					case 'Phylum':                                        
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'phylum' ), $taxon_name_id );
						$set .= "phylum_id = '{$case_id}', ";             
						break;                                            
					case 'Superdivision':                                 
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'superdivision' ), $taxon_name_id );
						$set .= "super_division_id = '{$case_id}', ";     
						break;                                            
					case 'Division':                                      
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'division' ), $taxon_name_id );
						$set .= "division_id = '{$case_id}', ";           
						break;                                            
					case 'Subdivision':                                   
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'subdivision' ), $taxon_name_id );
						$set .= "sub_division_id = '{$case_id}', ";       
						break;                                            
					case 'Superclass':                                    
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'superclass' ), $taxon_name_id );
						$set .= "super_class_id = '{$case_id}', ";        
						break;                                            
					case 'Class':                                         
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'class' ), $taxon_name_id );
						$set .= "class_id = '{$case_id}', ";              
						break;                                            
					case 'Subclass':                                      
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'subclass' ), $taxon_name_id );
						$set .= "sub_class_id = '{$case_id}', ";          
						break;                                            
					case 'Order':                                         
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'order' ), $taxon_name_id );
						$set .= "order_id = '{$case_id}', ";              
						break;                                            
					case 'Superfamily':                                   
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'superfamily' ), $taxon_name_id );
						$set .= "super_family_id = '{$case_id}', ";       
						break;                                            
					case 'Family':                                        
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'family' ), $taxon_name_id );
						$set .= "family_id = '{$case_id}', ";             
						break;                                            
					case 'Genus':                                         
						$case_id = $this->get_taxon_id( $this->get_taxon_rank_id( 'genus' ), $taxon_name_id );
						$set .= "genus_id = '{$case_id}', ";
						break;
					default:
						break;
		#			case 'Species':
		#				$case_id = get_taxon_id($child_taxon['name']);
		#				$set .= "specie_id = '{$case_id}', ";
		#				break;
				endswitch;
			endforeach;
		endif;
		
		switch( str_replace('-','',strtolower($rank)) ):
			case 'kingdom':
				$set .= "kingdom_id = '{$taxon_id}', ";
				break;
			case 'subkingdom':
				$set .= "sub_kingdom_id = '{$taxon_id}', ";
				break;
			case 'phylum':
				$set .= "phylum_id = '{$taxon_id}', ";
				break;
			case 'superdivision':
				$set .= "super_division_id = '{$taxon_id}', ";
				break;
			case 'division':
				$set .= "division_id = '{$taxon_id}', ";
				break;
			case 'subdivision':
				$set .= "sub_division_id = '{$taxon_id}', ";
				break;
			case 'superclass':
				$set .= "super_class_id = '{$taxon_id}', ";
				break;
			case 'class':
				$set .= "class_id = '{$taxon_id}', ";
				break;
			case 'subclass':
				$set .= "sub_class_id = '{$taxon_id}', ";
				break;
			case 'order':
				$set .= "order_id = '{$taxon_id}', ";
				break;
			case 'superfamily':
				$set .= "super_family_id = '{$taxon_id}', ";
				break;
			case 'family':
				$set .= "family_id = '{$taxon_id}', ";
				break;
			case 'genus':
				$set .= "genus_id = '{$taxon_id}', ";
				break;
			case 'species':
				$set .= "species_id = '{$taxon_id}', ";
				break;
			case 'subspecies':
			case 'infraspecies':
				$set .= "sub_species_id = '{$taxon_id}', ";
				break;
			default:
				break;
		endswitch;
		
		if( empty( $set ) )
			return false;
		
		$set = rtrim( $set, ', ' );
		
		$query = "INSERT IGNORE INTO taxonomy_maps SET {$set}";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return array();
	} // function
	
	public function merge_nas() {
		$query = "";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return $this->db->insert_id();
		endif;

		return array();
	} // function
	
	public function get_di_aquatic( $limit = 10, $offset = 0, $random = false ) {
		$query = "SELECT taxa.taxon_id, _di_dgt_aquatic.* FROM _di_dgt_aquatic JOIN taxon_names ON scientific_name = taxon_name JOIN taxa USING(taxon_name_id) GROUP BY taxa.taxon_id";
		
		if( $random === true ):
			$query .= " ORDER BY RAND()";
		else:
			$query .= " ORDER BY taxon_name ASC";
		endif;
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function harvest_aquatic( $taxon ) {
		$query = "SELECT taxon_id FROM taxon_information WHERE taxon_id = '{$taxon['taxon_id']}' LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$query = "UPDATE taxon_information SET ph_minimum = '{$taxon['ph_minimum']}', ph_maximum = '{$taxon['ph_maximum']}', temperature_minimum_f = '{$taxon['temperature_min_f']}', temperature_maximum_f = '{$taxon['temperature_max_f']}', maximum_size_inches = '{$taxon['max_size_inches']}', aquarium_minimum_gallons = '{$taxon['tank_size_min_gallons']}', kh_minimum = '{$taxon['water_kh_min']}', kh_maximum = '{$taxon['water_kh_max']}', sg_minimum = '{$taxon['sg_minimum']}', sg_maximum = '{$taxon['sg_maximum']}', aquarium_reef_safe = '{$taxon['reef_safe']}', venomous = '{$taxon['venomous']}' WHERE taxon_id = {$taxon['taxon_id']}";
		else:
			$query = "INSERT INTO taxon_information SET taxon_id = '{$taxon['taxon_id']}', ph_minimum = '{$taxon['ph_minimum']}', ph_maximum = '{$taxon['ph_maximum']}', temperature_minimum_f = '{$taxon['temperature_min_f']}', temperature_maximum_f = '{$taxon['temperature_max_f']}', maximum_size_inches = '{$taxon['max_size_inches']}', aquarium_minimum_gallons = '{$taxon['tank_size_min_gallons']}', kh_minimum = '{$taxon['water_kh_min']}', kh_maximum = '{$taxon['water_kh_max']}', sg_minimum = '{$taxon['sg_minimum']}', sg_maximum = '{$taxon['sg_maximum']}', aquarium_reef_safe = '{$taxon['reef_safe']}', venomous = '{$taxon['venomous']}'";
		endif;
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function

} // class