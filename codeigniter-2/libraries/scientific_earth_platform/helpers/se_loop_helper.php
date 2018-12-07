<?php

if( !function_exists( 'loop_view' ) ):
	function loop_view( $dataset, $view, $view_secondary = '' ) {
		$data = array( 'dataset' => $dataset );
		
		$CI =& get_instance();
		
		$v = '';
		if( !empty( $view_secondary ) )
			$v = "-{$view_secondary}";
		
		$CI->load->view( "se-loops/{$view}{$v}", $data );
		
		return true;
	}
endif;

if( !function_exists( 'loop_accounts' ) ):
	function loop_accounts( $accounts, $view = '' ) {
		return loop_view( $accounts, 'accounts', $view );
	} // function
endif;

if( !function_exists( 'loop_categories' ) ):
	function loop_categories( $categories, $view = '' ) {
		return loop_view( $categories, 'categories', $view );
	} // function
endif;

if( !function_exists( 'loop_articles' ) ):
	function loop_articles( $articles, $view = '' ) {
		return loop_view( $articles, 'articles', $view );
	} // function
endif;

if( !function_exists( 'loop_images' ) ):
	function loop_images( $images, $view = '' ) {
		return loop_view( $images, 'images', $view );
	} // function
endif;

if( !function_exists( 'loop_resources' ) ):
	function loop_resources( $resources, $view = '' ) {
		return loop_view( $resources, 'resources', $view );
	} // function
endif;

if( !function_exists( 'loop_recipes' ) ):
	function loop_recipes( $recipes, $view = '' ) {
		return loop_view( $recipes, 'recipes', $view );
	} // function
endif;

if( !function_exists( 'loop_discussions' ) ):
	function loop_discussions( $discussions, $view = '' ) {
		return loop_view( $discussions, 'discussions', $view );
	} // function
endif;

if( !function_exists( 'loop_journals' ) ):
	function loop_journals( $journals, $view = '' ) {
		return loop_view( $journals, 'journals', $view );
	} // function
endif;

if( !function_exists( 'loop_collections' ) ):
	function loop_collections( $collections, $view = '' ) {
		return loop_view( $collections, 'collections', $view );
	} // function
endif;

if( !function_exists( 'loop_locations' ) ):
	function loop_locations( $locations, $view = '' ) {
		return loop_view( $locations, 'locations', $view );
	} // function
endif;

if( !function_exists( 'loop_terms' ) ):
	function loop_terms( $terms, $view = '' ) {
		return loop_view( $terms, 'terms', $view );
	} // function
endif;

if( !function_exists( 'loop_feed' ) ):
	function loop_feed( $feed, $view = '' ) {
		return loop_view( $feed, 'feed', $view );
	} // function
endif;

if( !function_exists( 'loop_interactions' ) ):
	function loop_interactions( $interactions, $view = '' ) {
		return loop_view( $interactions, 'interactions', $view );
	} // function
endif;

if( !function_exists( 'loop_taxa' ) ):
	function loop_taxa( $taxa, $view = '' ) {
		return loop_view( $taxa, 'taxa', $view );
	} // function
endif;

if( !function_exists( 'loop_taxon_synonyms' ) ):
	function loop_taxon_synonyms( $synonyms, $view = '' ) {
		return loop_view( $synonyms, 'taxon-synonyms', $view );
	} // function
endif;

if( !function_exists( 'loop_taxon_data' ) ):
	function loop_taxon_data( $data, $view = '' ) {
		return loop_view( $data, 'taxon-data', $view );
	} // function
endif;

if( !function_exists( 'loop_paragraph_content' ) ):
	function loop_paragraph_content( $content, $view = '' ) {
		return loop_view( $content, 'paragraph', $view );
	} // function
endif;