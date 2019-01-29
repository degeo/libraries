<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Metatags
 * @author Jay Fortner <jay@degeo.net>
 */
class Metatags {

	protected $codeigniter;

	public $meta_title;
	public $meta_description;
	public $meta_keywords;

	public function __construct() {
		$this->codeigniter =& get_instance();

		# Depends on Application/Assets library
		$this->codeigniter->load->library('Application/assets');
	} // function

	public function set_meta_title( $title = '' ) {
		$this->meta_title = $title;

		$this->assets->remove_asset( 'header', 'meta-title', 0 );
		$this->assets->add_asset( 'header', 'meta-title', "<title>{$this->meta_title}</title>", 0 );

		return true;
	} // function

	public function get_meta_title() {
		return $this->meta_title;
	} // function

	public function set_meta_description( $description = '' ) {
		$this->meta_description = $description;

		$this->assets->remove_asset( 'header', 'meta-description', 1 );
		$this->assets->add_asset( 'header', 'meta-description', "<meta name='description' content='{$this->meta_description}' />", 1 );

		return true;
	} // function

	public function get_meta_description() {
		return $this->meta_description;
	} // function

	public function set_meta_keywords( $keywords = '' ) {
		$this->meta_keywords = $keywords;

		$this->assets->remove_asset( 'header', 'meta-keywords', 2 );
		$this->assets->add_asset( 'header', 'meta-keywords', "<meta name='keywords' content='{$this->meta_keywords}' />", 2 );

		return true;
	} // function

	public function get_meta_keywords() {
		return $this->meta_keywords;
	} // function

} // class