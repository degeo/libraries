<?php
/**
 * Data Array Object
 * @author Jay Fortner <jay@degeo.net>
 */
class Data_array_object {

	protected $data = array();

	/**
	 * Create
	 */
	public function create( $key, $value = '' ){
		$this->data[$key] = array();

		if( !empty( $value ) )
			$this->data[$key] = $value;

		return;
	} // function

	/**
	 * Read
	 */
	public function read( $key ){
		return $this->data[$key];
	} // function

	/**
	 * Update
	 */
	public function update( $key, $value ){
		$this->data[$key] = $value;

		return;
	} // function

	/**
	 * Delete
	 */
	public function delete( $key ){
		unset( $this->data[$key] );
		return;
	} // function

} // class