<?php
/**
 * Retrieves information from the database.
 *
 * This requires the information being retrieved from the database should be
 * specified by an incoming key. If no key is specified or a value is not found
 * then an empty string will be returned.
 *
 * @package very-simple-wp-maker-map
 */
class VSWPMM_Deserializer {
    /**
	 * Retrieves the value for the option identified by the specified key. If
	 * the option value doesn't exist, then an empty string will be returned.
	 *
	 * @param  string $option_key The key used to identify the option.
	 * @return string             The value of the option or an empty string.
	 */
    public function get_value( $option_key ) {
		global $wpdb;
		$sql = "
			SELECT option_value
			FROM {$wpdb->options}
			WHERE option_name like '" . $option_key . "'
		";
		$results = $wpdb->get_results($sql, ARRAY_A);
		foreach( $results as $value ) {
			$ouput .= '[vswpmakermap ' . $value['option_value'] . ']#makermap#';
		}
		return trim($ouput, '#makermap#');
    }
	
    public function get_filter( $id ) {
		global $wpdb;
		$sql = "
			SELECT option_value
			FROM {$wpdb->options}
			WHERE option_name = '" . $id . "'
		";
		$results = $wpdb->get_results($sql, ARRAY_A);
		return $results[0]['option_value'];
    }
}