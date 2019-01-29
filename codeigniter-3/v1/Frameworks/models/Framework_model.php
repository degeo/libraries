<?php

class Framework_model extends CI_Model {

	protected $version;

	protected $row = 'row';

	protected $column = '';

	protected $columns = array(
		'xs' => 'col-xs-',
		'sm' => 'col-sm-',
		'md' => 'col-md-',
		'lg' => 'col-lg-',
		'xl' => 'col-xl-'
	);

	public function __construct() {
		parent::__construct();
	} // function

	public function row() {
		return $this->row;
	} // function

	public function column( $size = 'xs', $grid_columns ) {
		return $this->columns[ $size ] . $grid_columns;
	} // function

} // class