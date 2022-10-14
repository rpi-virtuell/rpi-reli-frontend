<?php

class RpiReliFrontendFormsHandler{

	public function __construct() {
		add_action('acfe/form/submit/form=fortbildung-create', [$this,'update_termine'], 10, 2);
		add_action('acfe/form/submit/form=fortbildung-edit', [$this,'update_termine'], 10, 2);
	}

	public function update_termine ($form, $post_id) {

		delete_post_meta($post_id,'fortbildung_termin');

		$termine = get_field('termine',$post_id);
		if(is_array($termine)){
			foreach ($termine as $termin){

				$termin_string = strtotime($termin["termin_datumzeit"]);
				$termin_string .= '|'.$termin["termin_datumzeit"].'|'.$termin["termin_dauer"].'|'.$termin["termin_hinweis"];
				add_post_meta($post_id,'fortbildung_termin',$termin_string);
			}
		}

	}


	/**
	 * gibt alle termine zwischen zwei timestamps als Objekte aus
	 * @use:	RpiReliFrontendFormsHandler::get_termine();
	 * @example : $termine = RpiReliFrontendFormsHandler::get_termine('ASC', $von_timestamp, $von_timestamp);
	 *
	 * @param string $order ASC|DESC
	 * @param timestamp $after_ts
	 * @param timestamp $before_ts
	 * @param integer $post_id      ID einer Fortbildung
	 *
	 * @return array
	 */
	static function get_termine ( $order = 'ASC', $after_ts = false, $before_ts = false, $post_id =0 ) {


		$after_ts = $after_ts?$after_ts:time()-84600;
		$before_ts = $before_ts?$before_ts:strtotime('2100/01/01');

		global $wpdb;

		$termine = array();

		$sub_query = '';
		if($post_id > 0){
			$sub_query =  'AND post_id = '. intval($post_id);
		}

		$querystr = "
		    SELECT DISTINCT post_id, meta_value 
		    FROM $wpdb->postmeta 
		    WHERE meta_key = 'fortbildung_termin' AND  meta_value < $before_ts  AND meta_value > $after_ts $sub_query
		    ORDER BY meta_value $order
		";
		$results = $wpdb->get_results( $querystr, OBJECT );
		foreach ($results as $termin){

			$fortbildung = get_post($termin->post_id);

			if($fortbildung){

				list($timestamp,$date,$duration,$hint) = explode('|',$termin->meta_value);

				$termin->post_id = $fortbildung->ID;
				$termin->title = $fortbildung->post_title;
				$termin->subtitle = get_field('subtitle',$termin->post_id);
				$termin->excerpt = $fortbildung->post_excerpt;
				$termin->timestamp = $timestamp;
				$termin->datum_uhrzeit = $date;
				$termin->dauer = $duration;
				$termin->hinweis = $hint;
				$termin->image = get_the_post_thumbnail($fortbildung);
				unset($termin->meta_value);
				$termine[] = $termin;

			}
		}

		return $termine;

	}
}
new RpiReliFrontendFormsHandler();
