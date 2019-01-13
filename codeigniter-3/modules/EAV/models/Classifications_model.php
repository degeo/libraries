<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classifications_model extends CRUD_model {

	protected $primary_table = 'classifications';
	protected $primary_key = 'classification_id';

	public function get_classifications_by_object_id( $object, $id ) {
		return $this->get_relational_object_by_id( $object, $id );
	} // function

} // class
