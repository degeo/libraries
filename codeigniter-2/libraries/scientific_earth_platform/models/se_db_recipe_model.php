<?php

class Se_db_recipe_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_recipe_by_url( $recipe_url ) {
		$recipe_url_esc = $this->db->escape( $recipe_url );
		
		$query = "SELECT * FROM recipes JOIN recipe_ingredients USING(recipe_id) JOIN recipe_instructions USING(recipe_id) JOIN accounts USING(account_id) LEFT JOIN categories USING(category_id) LEFT JOIN images USING(image_id) WHERE recipe_url = {$recipe_url_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_recipe_ingredients( $recipe_id ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT recipe_ingredients FROM recipe_ingredients WHERE recipe_id = {$recipe_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_recipe_instructions( $recipe_id ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT recipe_instructions FROM recipe_instructions WHERE recipe_id = {$recipe_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->row_array();
		
		return array();
	} // function
	
	public function get_recipe_categories( $recipe_id, $limit = 10, $offset = 0 ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT ac.category_id, ac.recipe_id, c1.category_name, c1.category_url, c2.category_name as parent_category_name, c2.category_url as parent_category_url FROM recipe_categories as ac JOIN categories as c1 USING(category_id) LEFT JOIN categories as c2 ON ac.parent_category_id = c2.category_id WHERE ac.recipe_id = {$recipe_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_recipe_discussions( $recipe_id, $limit = 10, $offset = 0 ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT * FROM recipe_discussions as ad JOIN discussions as d USING(discussion_id) JOIN accounts USING(account_id) WHERE ad.recipe_id = {$recipe_id_esc} AND (parent_discussion_id IS NULL OR parent_discussion_id = 0)";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$result = $sql->result_array();
			
			foreach( $result as $k => $row ):
				$result[$k]['discussion_children'] = $this->Discussions_model->get_discussions( $row['discussion_id'] );
			endforeach;
			
			return $result;
		endif;
		
		return array();
	} // function
	
	public function get_recipe_images( $recipe_id, $limit = 10, $offset = 0 ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT * FROM recipe_images as ai JOIN images as i USING(image_id) WHERE ai.recipe_id = {$recipe_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_recipe_locations( $recipe_id, $limit = 10, $offset = 0 ) {
		
	} // function
	
	public function get_recipe_rating( $recipe_id, $limit = 10, $offset = 0 ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT * FROM recipe_ratings as ar JOIN ratings as r USING(rating_id) WHERE ar.recipe_id = {$recipe_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_recipe_taxa( $recipe_id, $limit = 10, $offset = 0 ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT * FROM recipe_taxa as at JOIN taxa as t USING(taxon_id) JOIN taxon_names USING(taxon_name_id) JOIN taxon_ranks USING(taxon_rank_id)  WHERE at.recipe_id = {$recipe_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
	public function get_recipe_terms( $recipe_id, $limit = 10, $offset = 0 ) {
		$recipe_id_esc = $this->db->escape( $recipe_id );
		
		$query = "SELECT * FROM recipe_terms as at JOIN terms as t USING(term_id) WHERE at.recipe_id = {$recipe_id_esc}";
		
		$query = limit_query( $query, $limit, $offset );
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 )
			return $sql->result_array();
		
		return array();
	} // function
	
} // class