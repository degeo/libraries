<?php

if( !function_exists( 'get_exif_coords' ) ):
function get_exif_coords( $exif_data ){
	if( empty( $exif_data ) || !array_key_exists( 'GPS', $exif_data ) )
		return false;
	
	$exif_data = $exif_data['GPS'];
    if (isset($exif_data['GPSLatitude']) && isset($exif_data['GPSLongitude']) &&
        isset($exif_data['GPSLatitudeRef']) && isset($exif_data['GPSLongitudeRef']) &&
        in_array($exif_data['GPSLatitudeRef'], array('E','W','N','S')) && in_array($exif_data['GPSLongitudeRef'], array('E','W','N','S'))) {

        $GPSLatitudeRef  = strtolower(trim($exif_data['GPSLatitudeRef']));
        $GPSLongitudeRef = strtolower(trim($exif_data['GPSLongitudeRef']));

        $lat_degrees_a = explode('/',$exif_data['GPSLatitude'][0]);
        $lat_minutes_a = explode('/',$exif_data['GPSLatitude'][1]);
        $lat_seconds_a = explode('/',$exif_data['GPSLatitude'][2]);
        $lng_degrees_a = explode('/',$exif_data['GPSLongitude'][0]);
        $lng_minutes_a = explode('/',$exif_data['GPSLongitude'][1]);
        $lng_seconds_a = explode('/',$exif_data['GPSLongitude'][2]);

        $lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
        $lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
        $lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
        $lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
        $lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
        $lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];

        $lat = (float) $lat_degrees+((($lat_minutes*60)+($lat_seconds))/3600);
        $lng = (float) $lng_degrees+((($lng_minutes*60)+($lng_seconds))/3600);

        //If the latitude is South, make it negative. 
        //If the longitude is west, make it negative
        $GPSLatitudeRef  == 's' ? $lat *= -1 : '';
        $GPSLongitudeRef == 'w' ? $lng *= -1 : '';

        return array(
            'latitude' => $lat,
            'longitude' => $lng
        );
	} // if

    return false;
} // function
endif;