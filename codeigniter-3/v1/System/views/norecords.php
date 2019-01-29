<?php

$panel = array(
	'title' => 'No Records Found',
	'body' => 'We could not find any ' . $this->Application->get_page_title()
);

$this->load->view( 'Bootstrap/v3/panel', array( 'panel' => $panel ) );
?>