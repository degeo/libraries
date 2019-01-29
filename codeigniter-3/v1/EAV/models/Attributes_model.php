<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attributes_model extends CRUD_model {

	protected $primary_table = 'attributes';
	protected $primary_key = 'attribute_id';

	public function get_attributes_by_object_id( $object, $id ) {
		return $this->get_relational_object_by_id( $object, $id );
	} // function

} // class
