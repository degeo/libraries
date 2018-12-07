<?php

class Se_db_images_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	} // function
	
	public function upload_image( $account_id, $filename, $thumbnail, $filesize, $width, $height, $imagetype, $latitude = false, $longitude = false, $elevation = false ) {
		$account_id_esc = $this->db->escape( $account_id );
		$filename_esc = $this->db->escape( $filename );
		$thumbnail_esc = $this->db->escape( $thumbnail );
		$filesize_esc = $this->db->escape( $filesize );
		$width_esc = $this->db->escape( $width );
		$height_esc = $this->db->escape( $height );
		$imagetype_esc = $this->db->escape( $imagetype );
		
		$query = "INSERT INTO images VALUES ( NULL, {$account_id_esc}, {$filename_esc}, {$thumbnail_esc}, {$filesize_esc}, {$width_esc}, {$height_esc}, {$imagetype_esc}, CURRENT_TIMESTAMP() )";
		
		$sql = $this->db->query( $query );
		
		if( $this->db->affected_rows() > 0 ):
			$image_id = $this->db->insert_id();
			$image_id_esc = $this->db->escape( $image_id );
		
			if( !empty( $latitude ) && !empty( $longitude ) ):
				$this->load->model('Geodata_model');
				$coordinates_id = $this->Geodata_model->create_coordinates( $latitude, $longitude, $elevation );
				$coordinates_id_esc = $this->db->escape( $coordinates_id );
				
				# insert into image_coordinates tables
				$query = "INSERT INTO image_coordinates VALUES( NULL, {$image_id_esc}, {$coordinates_id_esc} )";
				$sql = $this->db->query( $query );
				
				if( $this->db->affected_rows() > 0 ):
					# @se-points Points Earned - Reason: Uploading an Image with Location data
					$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'uploaded an image with location data' );
				
					return true;
				else:
					return false;
				endif;
				
			endif;
			
			# @se-points Points Earned - Reason: Uploading an Image
			$this->Se_db_account_points->insert_points( $this->config->item('se_application_id'), $account_id, '100', 'uploaded an image' );
			
			return true;
		endif;
		
		return false;
	} // function
	
	public function create_thumbnail( $tmpdir, $source_image, $file_ext, $width = 48, $height = 48 ) {
		$config['image_library'] = 'gd2';
		$config['source_image']	= $source_image;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width']	 = $width;
		$config['height']	= $height;
		$config['thumb_marker'] = "_thumb{$width}x{$height}";
		
		$this->load->library('image_lib', $config);
		
		$this->image_lib->initialize($config);

		$thumb = $this->image_lib->resize();
		
		$this->image_lib->clear();
		
		$thumbnail_name = $tmpdir . "_thumb{$width}x{$height}" . $file_ext;
		
		return $thumbnail_name;
	} // function
	
	public function get_images( $random = false, $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM images JOIN accounts USING(account_id)";
		else:
			if( $random === true ):
				$query = "SELECT * FROM images JOIN accounts USING(account_id) JOIN taxon_images USING(image_id) LEFT JOIN image_captions USING(image_id) GROUP BY image_id ORDER BY RAND()";
			else:
				$query = "SELECT * FROM images JOIN accounts USING(account_id) LEFT JOIN image_captions USING(image_id) ORDER BY image_id DESC";
			endif;

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
		
		return array();
	} // function
	
	public function get_images_without_taxa( $random = false, $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(*) as total FROM images JOIN accounts USING(account_id) LEFT JOIN image_captions USING(image_id) LEFT JOIN taxon_images ON images.image_id = taxon_images.image_id WHERE taxon_images.taxon_id IS NULL";
		else:
			if( $random === true ):
				$query = "SELECT * FROM images JOIN accounts USING(account_id) LEFT JOIN image_captions USING(image_id) LEFT JOIN taxon_images ON images.image_id = taxon_images.image_id WHERE taxon_images.taxon_id IS NULL ORDER BY RAND()";
			else:
				$query = "SELECT * FROM images JOIN accounts USING(account_id) LEFT JOIN image_captions USING(image_id) LEFT JOIN taxon_images ON images.image_id = taxon_images.image_id WHERE taxon_images.taxon_id IS NULL ORDER BY images.image_id ASC";
			endif;

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
		
		return array();
	} // function
	
	public function get_taxa_with_images( $random = false, $limit = 10, $offset = 0, $get_count = false ) {
		if( $get_count === true ):
			$query = "SELECT COUNT(DISTINCT taxon_id) as total FROM taxa JOIN taxon_images USING(taxon_id) JOIN images ON taxon_images.image_id = images.image_id JOIN taxon_names USING(taxon_name_id)";
		else:
			if( $random === true ):
				$query = "SELECT * FROM taxa JOIN taxon_images USING(taxon_id) JOIN taxon_names USING(taxon_name_id) GROUP BY taxa.taxon_id ORDER BY RAND()";
			else:
				$query = "SELECT * FROM taxa JOIN taxon_ranks USING(taxon_rank_id) JOIN taxon_images USING(taxon_id) JOIN images ON taxon_images.image_id = images.image_id JOIN taxon_names USING(taxon_name_id) LEFT JOIN taxon_information USING(taxon_id) GROUP BY taxa.taxon_id ORDER BY taxon_names.taxon_name ASC";
			endif;

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
		
		return array();
	} // function
	
	public function lookup_image( $filename ) {
		$filename_esc = $this->db->escape( $filename );
		
		$query = "SELECT * FROM images JOIN accounts USING(account_id) LEFT JOIN image_captions USING(image_id) WHERE image_filename = {$filename_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );

		if( $sql->num_rows() > 0 ):
			return $sql->row_array();
		endif;

		return array();
	} // function
	
	public function edit_caption() {
		$image_id = $this->input->post('image_id');
		$image_id_esc = $this->db->escape( $image_id );
		
		$caption_title = $this->input->post('caption_title');
		$caption_title_esc = $this->db->escape( $caption_title );
		
		$caption_content = $this->input->post('caption_content');
		$caption_content_esc = $this->db->escape( $caption_content );
		
		$query = "SELECT image_id FROM image_captions WHERE image_id = {$image_id_esc} LIMIT 1";
		
		$sql = $this->db->query( $query );
		
		if( $sql->num_rows() > 0 ):
			$query = "UPDATE image_captions SET caption_title = {$caption_title_esc}, caption_content = {$caption_content_esc} WHERE image_id = {$image_id_esc}";
		else:
			$query = "INSERT INTO image_captions SET image_id = {$image_id_esc}, caption_title = {$caption_title_esc}, caption_content = {$caption_content_esc}";
		endif;
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
	public function edit_taxa() {
		$taxa = $this->input->post('image_taxa');
		
		foreach( $taxa as $k => $taxon ):
			$this->edit_taxon( $taxon );
		endforeach;
	} // function
	
	public function edit_taxon( $taxon_id = '' ) {
		if( empty( $taxon_id ) ):
			$taxon_id = $this->input->post('image_taxon');
		endif;
		
		$taxon_id_esc = $this->db->escape( $taxon_id );
		
		$image_id = $this->input->post('image_id');
		$image_id_esc = $this->db->escape( $image_id );
		
		$query = "INSERT INTO taxon_images SET taxon_id = {$taxon_id_esc}, image_id = {$image_id_esc}";
		
		$sql = $this->db->query( $query );

		if( $this->db->affected_rows() > 0 ):
			return true;
		endif;

		return false;
	} // function
	
} // class