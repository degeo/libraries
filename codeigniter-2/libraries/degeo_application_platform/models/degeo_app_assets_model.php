<?php

/**
 *
 * Example:
 * $this->load->model('Degeo_app_assets_model','Degeo_assets');
 * $this->Degeo_assets->add_host( 'main', array( 'host_domain' => 'mydomain.com', 'host_path' => 'assets' ) );
 * $this->Degeo_assets->add_host( 'cdn', array( 'host_domain' => 'cdn.mydomain.com', 'host_path' => '' ) );
 * $this->Degeo_assets->add_host( 'server1', array( 'host_domain' => 'a.mydomain.com', 'host_path' => '' ) );
 * $this->Degeo_assets->add_host( 'server2', array( 'host_domain' => 'b.mydomain.com', 'host_path' => '' ) );
 * $this->Degeo_assets->add_host( 'server3', array( 'host_domain' => 'c.mydomain.com', 'host_path' => '' ) );
 * echo "<img src='{$this->Degeo_assets->get_url( 'logo.png' )}' />";
 * echo "<img src='{$this->Degeo_assets->get_url( 'logo.png', 'main' )}' />";
 * echo "<img src='{$this->Degeo_assets->get_url( 'sprites.png', 'cdn' )}' />";
 * echo "<img src='{$this->Degeo_assets->get_url( 'sprites.png', false, true )}' />";
 *
 */
class Degeo_app_assets_model extends CI_Model {
	
	public $hosts = array(
		'default' => array(
			'host_key' => 'default',
			'host_domain' => '',
			'host_path' => ''
		)
	);
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function add_host( $host_key, $config = array() ) {
		$host_domain = '';
		$host_path = '';
		
		if( array_key_exists( 'host_domain', $config ) ):
			$host_domain = str_replace( array( 'http:', 'https:' ), '', rtrim( $config['host_domain'], '/' ) );
		endif;
		
		if( array_key_exists( 'host_path', $config ) ):
			$host_path = trim( $config['host_path'], '/' );
		endif;
		
		$config = array(
			'host_key' => $host_key,
			'host_domain' => $host_domain,
			'host_path' => $host_path
		);
		
		$this->hosts[$host_key] = $config;
	} // function
	
	public function get_url( $asset_path, $host_key = 'default', $use_random_host = false ) {
		if( $use_random_host === true ):
			$total_hosts = count( $this->hosts ) - 1;
			$key = rand( 0, $total_hosts );
			$host = $this->hosts[$key];
		elseif( array_key_exists( $host_key, $this->hosts ) ):
			$host = $this->hosts[$host_key];
		else:
			$host = $this->hosts['default'];
		endif;
		
		if( !empty( $host['host_domain'] ) && !empty( $host['host_path'] ) ):
			$asset_url = "//{$host['host_domain']}/{$host['host_path']}/{$asset_path}";
		elseif( !empty( $host['host_domain'] ) && empty( $host['host_path'] ) ):
			$asset_url = "//{$host['host_domain']}/{$asset_path}";
		elseif( empty( $host['host_domain'] ) && !empty( $host['host_path'] ) ):
			$asset_url = "/{$host['host_path']}/{$asset_path}";
		else:
			$asset_url = "/{$asset_path}";
		endif;
		
		return $asset_url;
	} // function
	
} // class