<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Values_model extends CRUD_model {

	protected $primary_table = 'values';
	protected $primary_key = 'value_id';

	public function get_values_by_object_id( $object, $id ) {
		return $this->get_relational_object_by_id( $object, $id );
	} // function

} // class
