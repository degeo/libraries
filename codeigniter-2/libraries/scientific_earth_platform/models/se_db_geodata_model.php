<?php

class Se_db_geodata_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function get_continents( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM geodata_continents";
		else:
			$query = "SELECT * FROM geodata_continents";
			
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
	
	public function get_countries( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM geodata_countries";
		else:
			$query = "SELECT * FROM geodata_countries LEFT JOIN geodata_continents USING(continent_id) ORDER BY country_name ASC";
			
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
	
	public function get_territories( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM geodata_country_territories";
		else:
			$query = "SELECT * FROM geodata_country_territories LEFT JOIN geodata_countries USING(country_id) ORDER BY territory_name ASC";
			
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
	
	public function get_locations( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM geodata_locations";
		else:
			$query = "SELECT * FROM geodata_locations LEFT JOIN geodata_countries USING(country_id) LEFT JOIN geodata_country_territories USING(territory_id) LEFT JOIN geodata_location_names USING(location_name_id) LEFT JOIN geodata_coordinates USING(coordinate_id) ORDER BY location_name ASC";
			
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
	
	public function get_postal_codes( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM geodata_postal_codes";
		else:
			$query = "SELECT * FROM geodata_postal_codes";
			
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
	
	public function get_timezones( $limit = 20, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM geodata_timezones";
		else:
			$query = "SELECT * FROM geodata_timezones";
			
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
	
	/**
	 * set @lon1 = @orig_lon-@dist/abs(cos(radians(@orig_lat))*69);
	 * set @lon2 = @orig_lon+@dist/abs(cos(radians(@orig_lat))*69);
	 * set @lat1 = @orig_lat-(@dist/69);
	 * set @lat2 = @orig_lat+(@dist/69);
	**/
	public function get_client_locations( $latitude, $longitude, $distance = 10, $limit = 3 ) {
		$lon1 = $longitude - $distance / abs(cos(deg2rad($latitude))*69);
		$lon2 = $longitude + $distance / abs(cos(deg2rad($latitude))*69);
		$lat1 = $latitude - ( $distance / 69 );
		$lat2 = $latitude + ( $distance / 69 );
		
		$query = "SELECT coords.latitude, coords.longitude, coords.elevation, l.location_id, l.country_id, gc.country_code, gc.country_name, l.territory_id, ln.location_name, gct.territory_name, gct.territory_abbreviation, 
			3956 * 2 * ASIN(SQRT( POWER(SIN(({$latitude} - abs( coords.latitude)) * pi()/180 / 2),2) + COS({$latitude} * pi()/180 ) * COS( abs(coords.latitude) *  pi()/180) * POWER(SIN(({$longitude} - coords.longitude) *  pi()/180 / 2), 2) )) AS distance 
			FROM geodata_coordinates AS coords 
			JOIN geodata_locations as l USING(coordinate_id) 
			JOIN geodata_location_names as ln USING(location_name_id)
			JOIN geodata_countries as gc USING(country_id) 
			JOIN geodata_country_territories as gct USING(territory_id) 
			WHERE coords.longitude BETWEEN {$lon1} AND {$lon2} AND coords.latitude BETWEEN {$lat1} AND {$lat2} GROUP BY l.location_id ORDER BY distance ASC";
		
		if( !empty($limit) )
			$query .= " LIMIT {$limit}";
			
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			if( $limit > 1 ):
				return $sql->result_array();
			else:
				return $sql->row_array();
			endif;
		endif;
		
		return array();
	} // function
	
} // class