<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EAV_model extends CRUD_model {

	protected $primary_table = 'eav';
	protected $primary_key = 'eav_id';

	public function __construct(){
		$this->load->model( 'EAV/Classifications_model', 'Classifications' );
		$this->load->model( 'EAV/Entities_model', 'Entities' );
		$this->load->model( 'EAV/Attributes_model', 'Attributes' );
		$this->load->model( 'EAV/Values_model', 'Values' );

		return;
	} // function

	public function read_entity( $entity_id ) {
		return $this->Entities->read_entity( $entity_id );
	} // function

	public function read_entity_values( $entity_id ) {
		return $this->Entities->read_entity_values( $entity_id );
	} // function

	public function read_entity_by_attribute_value( $attribute_key, $value ) {
		return $this->Entities->read_entity_by_attribute_value( $attribute_key, $value );
	} // function

	public function read_entities( $classification = '' ) {
		if( !empty( $classification ) ):
			return $this->Entities->read_classification( $classification );
		endif;

		return $this->Entities->read();
	} // function

	public function read_entity_attributes( $entity_id = '' ) {
		if( !empty( $entity_id ) ):
			return $this->Entities->read( array( 'entity_type' => $entity_type ) );
		endif;

		return $this->Entities->read();
	} // function

} // class
