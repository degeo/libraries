<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EAV_Controller extends Degeo_Controller {

	protected $input_fields = array(
		'classification_key',
		'classification_label'
	);

	public function __construct() {
		parent::__construct();

		$this->Application->set_page_title( __CLASS__ );
		$this->Breadcrumbs->add_breadcrumb( strtolower( __CLASS__ ), __CLASS__, 10 );

		$this->load->model( 'EAV/EAV_model', 'EAV' );
	} // function

	public function index(){
		$records = $this->EAV->{__CLASS__}->read();

		$this->Application->add_data( 'records', $records );

		$this->Layout->add_content( 'index', 'EAV/eav/index', 50 );

		$this->Layout->view( $this->Application->get_data() );
	} // function

	public function create(){
		if( $this->form_validation->run( strtolower( __CLASS__ ) ) === TRUE ):

			$input_data = set_values_from_fields( $this->input_fields );

			if( $record_id = $this->EAV->{__CLASS__}->create( $input_data ) ):
				$this->Messages->add( 'info', singular( __CLASS__ ) . ' Created' );

				redirect( site_url( strtolower( __CLASS__ ) . '/read/' . $record_id ) );
			else:
				$this->Messages->add( 'warning', 'Could Not Update ' . singular( __CLASS__ ) );
			endif;
		endif;

		$validation_errors = rtrim( validation_errors( '', ',' ), ',');
		$validation_errors = explode( ',', $validation_errors );
		if( !empty( $validation_errors ) ):
			foreach( $validation_errors as $error ):
				$this->Messages->add( 'warning', $error );
			endforeach;
		endif;

		$this->Layout->add_content( 'index', 'EAV/eav/create', 50 );

		$this->Layout->view( $this->Application->get_data() );
	} // function

	public function read( $record_id = '' ){
		if( empty( $record_id ) )
			redirect( site_url( strtolower( __CLASS__ ) ) );

		$record = $this->EAV->{__CLASS__}->read_record( array( $this->EAV->{__CLASS__}->get_primary_key() => $record_id ) );

		$this->Application->add_data( 'record', $record );

		$this->Layout->add_content( 'index', 'EAV/eav/read', 50 );

		$this->Layout->view( $this->Application->get_data() );
	} // function

	public function update(){
		$record_id = $this->input->post( $this->EAV->{__CLASS__}->get_primary_key() );

		if( empty( $record_id ) )
			redirect( site_url( strtolower( __CLASS__ ) ) );

		if( $this->form_validation->run( strtolower( __CLASS__ ) ) === TRUE ):

			$input_data = set_values_from_fields( $this->input_fields );

			if( empty( $record_id ) ):
				$this->Messages->add( 'warning', 'No ' . singular( __CLASS__ ) . ' ID Provided' );
			else:
				if( $this->EAV->{__CLASS__}->update( array( $this->EAV->{__CLASS__}->get_primary_key() => $record_id ), $input_data ) ):
					$this->Messages->add( 'info', singular( __CLASS__ ) . ' Updated' );

					redirect( site_url( strtolower( __CLASS__ ) . '/read/' . $record_id ) );
				else:
					$this->Messages->add( 'warning', 'Could Not Update ' . singular( __CLASS__ ) );
				endif;
			endif;
		endif;

		$validation_errors = rtrim( validation_errors( '', ',' ), ',');
		$validation_errors = explode( ',', $validation_errors );
		if( !empty( $validation_errors ) ):
			foreach( $validation_errors as $error ):
				$this->Messages->add( 'warning', $error );
			endforeach;
		endif;

		$this->read( $record_id );
	} // function

	public function delete(){
		$record_id = $this->input->post( $this->EAV->{__CLASS__}->get_primary_key() );

		if( empty( $record_id ) )
			redirect( site_url( strtolower( __CLASS__ ) ) );

		if( $this->form_validation->run( strtolower( __CLASS__ ) . '/delete' ) === TRUE ):

			if( empty( $record_id ) ):
				$this->Messages->add( 'warning', 'No ' . singular( __CLASS__ ) . ' ID Provided' );
			else:
				if( $this->EAV->{__CLASS__}->delete( array( $this->EAV->{__CLASS__}->get_primary_key() => $record_id ) ) ):
					$this->Messages->add( 'info', singular( __CLASS__ ) . ' Deleted' );

					redirect( site_url( strtolower( __CLASS__ ) ) );
				else:
					$this->Messages->add( 'warning', 'Could Not Delete ' . singular( __CLASS__ ) );
				endif;
			endif;

		endif;

		$record = $this->EAV->{__CLASS__}->read_record( array( $this->EAV->{__CLASS__}->get_primary_key() => $record_id ) );

		$this->Application->add_data( 'record', $record );

		$this->Layout->add_content( 'index', 'EAV/eav/delete', 50 );

		$this->Layout->view( $this->Application->get_data() );
	} // function

} // class
