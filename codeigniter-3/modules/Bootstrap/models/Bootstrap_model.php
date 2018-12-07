<?php

class Bootstrap_model extends Framework_model {

	protected $version = '3.3.7';

	protected $row = 'row';

	protected $column = '';

	public function __construct() {
		parent::__construct();

		# load jQuery
		$this->load->model( 'Assets/Jquery_model', 'jQuery' );

		# load Bootstrap Modals
		$this->load->model('Bootstrap/Modals_model', 'Modals');
	} // function

	public function load( $version = '' ){
		if( empty( $version ) )
			$version = $this->version;
		else
			$this->version = $version;

		switch( $version ):
			case '4.0.0':
				$this->columns['xs'] = 'col-';

				$this->jQuery->load_version( '3.2.1' );

				$this->Assets->add_asset( 'header', 'css-bootstrap', '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>', 10 );

				$this->Assets->add_asset( 'footer', 'js-popper', '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>', 21 );
				$this->Assets->add_asset( 'footer', 'js-bootstrap', '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>', 22 );
				break;
			case '3.3.7':
			default:
				$this->columns['xl'] = 'col-lg-';

				$this->jQuery->load_version( '3.1.1' );

				$this->Assets->add_asset( 'header', 'css-bootstrap', '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">', 10 );
				$this->Assets->add_asset( 'header', 'css-bootstrap-theme', '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />', 11 );

				$this->Assets->add_asset( 'footer', 'js-bootstrap', '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>', 22 );
				break;
		endswitch;
	} // function

} // class