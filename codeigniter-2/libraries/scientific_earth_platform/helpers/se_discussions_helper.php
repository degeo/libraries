<?php

if( !function_exists( 'interactive_discussions' ) ):
	function interactive_discussions( $data, $primary_id_name, $primary_id_value, $current_user ) {
		
		$interactive_data = array(
			'dataset' => $data,
			'primary_id_name' => $primary_id_name,
			'primary_id_value' => $primary_id_value,
			'current_user' => $current_user
		);
		
		$CI =& get_instance();
		$CI->load->view( "se-loops/discussions-list-interactive", $interactive_data );
		
		return true;
	} // function
endif;

if( !function_exists( 'interactive_article_discussions' ) ):
	function interactive_article_discussions( $article_id, $data = array() ) {
		$CI =& get_instance();
		interactive_discussions( $data, 'article_id', $article_id, $CI->Se_app_user->get_info() );
	} // function
endif;

if( !function_exists( 'interactive_collection_discussions' ) ):
	function interactive_collection_discussions( $collection_id, $data = array() ) {
		$CI =& get_instance();
		interactive_discussions( $data, 'collection_id', $collection_id, $CI->Se_app_user->get_info() );
	} // function
endif;

if( !function_exists( 'interactive_journal_discussions' ) ):
	function interactive_journal_discussions( $journals_id, $data = array() ) {
		$CI =& get_instance();
		interactive_discussions( $data, 'journals_id', $journals_id, $CI->Se_app_user->get_info() );
	} // function
endif;

if( !function_exists( 'interactive_recipe_discussions' ) ):
	function interactive_recipe_discussions( $recipe_id, $data = array() ) {
		$CI =& get_instance();
		interactive_discussions( $data, 'recipe_id', $recipe_id, $CI->Se_app_user->get_info() );
	} // function
endif;

if( !function_exists( 'interactive_taxon_discussions' ) ):
	function interactive_taxon_discussions( $taxon_id, $data = array() ) {
		$CI =& get_instance();
		interactive_discussions( $data, 'taxon_id', $taxon_id, $CI->Se_app_user->get_info() );
	} // function
endif;

if( !function_exists( 'interactive_image_discussions' ) ):
	function interactive_image_discussions( $image_id, $data = array() ) {
		$CI =& get_instance();
		interactive_discussions( $data, 'image_id', $image_id, $CI->Se_app_user->get_info() );
	} // function
endif;