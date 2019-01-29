<?php

/**
 * Returns the time since the timestamp provided
 */
function time_since( $timestamp ){

	$time = strtotime( $timestamp );

    $time = time() - $time;
    $time = ( $time < 1 ) ? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

} // function

/**
 * Returns the time between two dates
 */
function how_long( $start_date_str, $end_date_str ) {
	$start_date = date_create( $start_date_str );

	$end_date = date_create( $end_date_str );

	$howlong = date_diff( $start_date, $end_date );

	$howlong_years = $howlong->format('%y');
	$howlong_months = $howlong->format('%m');

	$howlong_text = '';

	if( $howlong_years > 0 ):

		$howlong_text .= $howlong_years;

		if( $howlong_years > 1 ):
			$howlong_text .= ' years';
		else:
			$howlong_text .= ' year';
		endif;

	endif;

	if( $howlong_months > 0 ):

		$howlong_text .= ' ' . $howlong_months;

		if( $howlong_months > 1 ):
			$howlong_text .= ' months';
		else:
			$howlong_text .= ' months';
		endif;

	endif;

	$howlong_text = trim($howlong_text);

	return $howlong_text;
} // function