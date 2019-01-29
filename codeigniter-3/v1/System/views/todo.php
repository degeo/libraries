<?php
$jumbotron = array(
	'heading' => 'Coming Soon',
	'content' => 'This page is coming soon.',
#	'link' => site_url( 'account' ),
#	'link_label' => 'View Your Account'
);
$this->load->view( 'Bootstrap/v3/jumbotron', array( 'jumbotron' => $jumbotron ) ); ?>