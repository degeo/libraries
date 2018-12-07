<?php

/**
 *
 * Example:
 * $this->load->model('Degeo_app_assets_model','Degeo_assets');
 * $this->Degeo_assets->add_host( 'main', array( 'domain' => 'mydomain.com', 'path' => 'assets' ) );
 * $this->Degeo_assets->add_host( 'cdn', array( 'domain' => 'cdn.mydomain.com', 'path' => '' ) );
 * $this->Degeo_assets->add_host( 'server1', array( 'domain' => 'a.mydomain.com', 'path' => '' ) );
 * $this->Degeo_assets->add_host( 'server2', array( 'domain' => 'b.mydomain.com', 'path' => '' ) );
 * $this->Degeo_assets->add_host( 'server3', array( 'domain' => 'c.mydomain.com', 'path' => '' ) );
 * echo "<img src='{$this->Degeo_assets->get_url( 'logo.png' )}' />";
 * echo "<img src='{$this->Degeo_assets->get_url( 'logo.png', 'main' )}' />";
 * echo "<img src='{$this->Degeo_assets->get_url( 'sprites.png', 'cdn' )}' />";
 * echo "<img src='{$this->Degeo_assets->get_url( 'sprites.png', false, true )}' />";
 *
 */
class Hosts_model extends CI_Model {

	public $host = 'default';

	public $hosts = array(
		'default' => array(
			'key' => '',
			'domain' => '',
			'path' => ''
		)
	);

	public function __construct() {
		parent::__construct();
	} // function

	public function set_host( $host = 'default' ) {
		$this->host = $host;

		return true;
	} // function

	public function add_host( $key, $config = array() ) {
		$domain = '';
		$path = '';

		if( array_key_exists( 'domain', $config ) ):
			$domain = str_replace( array( 'http:', 'https:' ), '', rtrim( $config['domain'], '/' ) );
		endif;

		if( array_key_exists( 'path', $config ) ):
			$path = trim( $config['path'], '/' );
		endif;

		$config = array(
			'key' => $key,
			'domain' => $domain,
			'path' => $path
		);

		$this->hosts[$key] = $config;

		return true;
	} // function

	public function get_url( $asset_path, $key = '', $use_random_host = false ) {
		if( empty( $key ) )
			$key = $this->host;

		if( $use_random_host === true ):
			$total_hosts = count( $this->hosts ) - 1;
			# rand() starts at 1 to ignore default at 0 key position
			$key = rand( 1, $total_hosts );
			$host = $this->hosts[$key];
		elseif( array_key_exists( $key, $this->hosts ) ):
			$host = $this->hosts[$key];
		else:
			$host = $this->hosts[$this->host];
		endif;

		if( !empty( $host['domain'] ) && !empty( $host['path'] ) ):
			$asset_url = "//{$host['domain']}/{$host['path']}/{$asset_path}";
		elseif( !empty( $host['domain'] ) && empty( $host['path'] ) ):
			$asset_url = "//{$host['domain']}/{$asset_path}";
		elseif( empty( $host['domain'] ) && !empty( $host['path'] ) ):
			$asset_url = "/{$host['path']}/{$asset_path}";
		else:
			$asset_url = "/{$asset_path}";
		endif;

		return $asset_url;
	} // function

} // class