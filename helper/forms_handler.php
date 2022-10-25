<?php

class RpiReliFrontendFormsHandler{

	public function __construct() {
		add_action('acfe/form/submit/form=fortbildung-create', [$this,'update_termine'], 10, 2);
		add_action('acfe/form/submit/form=fortbildung-edit', [$this,'update_termine'], 10, 2);
		add_filter('acf/load_field/name=teilnehmende', [$this,'load_teilnehmende']);
		add_filter('acf/load_field/name=teilnahme_datum', [$this,'load_teilnahme_datum']);
	}

	public function load_teilnehmende ($field) {

		$fobi = get_the_ID();

		if('fortbildung'!=get_post_type($fobi))
			return $field;

		$args = [
			'post_type' => 'anmeldung',
			'post_status'=> 'any',
			'meta_query'=>[
				[
					'key'=>'fobi',
					'value'=> intval($fobi),
					'compare'=>'=',
					'type' => 'NUMERIC'
				]
			]
		];
		$anmeldungen = get_posts($args);


		if ($anmeldungen !== false) {
			foreach ($anmeldungen as $anm){

				$user_id = get_post_meta($anm->ID, 'user', true);
				$user_organisation = (string) get_post_meta($anm->ID, 'anmeldung_organisation', true);

				// $user = WP_USER
				$user= get_userdata($user_id);
				$field['choices'][ $user_id ] = '<strong>'. $user->display_name .'</strong> ('.$user_organisation.')' ;

			}

			$checked =  get_post_meta(intval($fobi), 'teilnehmende', true);
			//var_dump($checked);
			$field['default_value']=$checked;
		}

		return $field;
	}
	public function load_teilnahme_datum ($field) {
		$fobi = get_the_ID();
		$termine = get_field('termine', get_the_ID());

		$now = date('ymd',time());

		$current_termin  = null;

		foreach ($termine as $termin){
			$ts = strtotime($termin['termin_datumzeit']);
			$field['choices'][ $ts ] = $termin['termin_datumzeit']  ;

			if($now >= date('ymd',$ts)){

				$field['default_value']= $ts;

			}
		}
		return $field;

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
	 * gibt Forbildungstermine zwischen zwei Timestamps als Array von Standardobjekten zurück
	 *
	 * @use:	RpiReliFrontendFormsHandler::get_termine(string $order, $string $von_timestamp, string $bis_timestamp, post_ids[] $post__in );
	 * @example : $termine = RpiReliFrontendFormsHandler::get_termine('ASC', $von_timestamp, $bis_timestamp);
	 *
	 * @param string $order ASC|DESC
	 * @param timestamp $after_ts
	 * @param timestamp $before_ts
	 * @param array $post__in     Termine beschränkt aus IDs der Fortbildungen, notwendig
	 *                            um Fortbildungen einer bestimmten taxonomie anzuzeigen
	 *
	 *                            @example:
	 *                            $posts = get_posts(
	 *                              [
	 *                                  'post_type'=>'fortbildung',
	 *                                  'numberposts'=>-1,
	 *                                  'tax_query'=>[
	 *                                      'taxonomy'=>'bundesland',
	 *                                      'field'=>'slug',
	 *                                      'terms'=>['hessen','bayern']
	 *                                  ]
	 *                              ]
	 *                            );
	 *                            $ids = [];
	 *                            foreach($posts as $post){
	 *                                $ids[] = $post->ID
	 *                            }
	 *                            $termine = get_termine('ASC', false, false,$ids);
	 * @return stdClass[] array
	 *
	 *      $termin->post_id
	 *      $termin->title
	 * 	    $termin->subtitle
	 * 	    $termin->excerpt
	 * 	    $termin->timestamp
	 * 	    $termin->datum_uhrzeit
	 * 	    $termin->dauer
	 * 	    $termin->hinweis
	 * 	    $termin->image

	 */
	static function get_termine ( $order = 'ASC', $after_ts = false, $before_ts = false, $post__in = array() ) {


		$after_ts = $after_ts?$after_ts:time()-84600;
		$before_ts = $before_ts?$before_ts:strtotime('2100/01/01');

		global $wpdb;

		$termine = array();

		$sub_query = '';
		if(is_array($post__in) && count($post__in) > 0){
			$sub_query =  'AND post_id in ('. implode(',',$post__in) .')';
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
