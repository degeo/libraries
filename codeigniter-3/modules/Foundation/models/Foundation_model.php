<?php

class Foundation_model extends Framework_model {

	protected $version = '6.4.3';

	protected $row = 'row';

	protected $column = 'column';

	public function __construct() {
		parent::__construct();

		# load jQuery
		$this->load->model( 'Assets/Jquery_model', 'jQuery' );

		# load Extras
	} // function

	public function load( $version = '' ){
		if( empty( $version ) )
			$version = $this->version;
		else
			$this->version = $version;

		switch( $version ):
			case '6.4.3':
			default:
				$this->jQuery->load_version( '3.1.1' );

				$this->Assets->add_asset( 'header', 'css-foundation', '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css" integrity="sha256-itWEYdFWzZPBG78bJOOiQIn06QCgN/F0wMDcC4nOhxY=" crossorigin="anonymous" />', 10 );
				$this->Assets->add_asset( 'header', 'css-bootstrap-theme', '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />', 11 );

				$this->Assets->add_asset( 'footer', 'js-foundation', '<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js" integrity="sha256-Nd2xznOkrE9HkrAMi4xWy/hXkQraXioBg9iYsBrcFrs=" crossorigin="anonymous"></script>', 22 );
				break;
		endswitch;
	} // function

} // class