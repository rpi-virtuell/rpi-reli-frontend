<?php

class RpiReliFrontendFormsHandler{

	public function __construct() {
		add_action('acfe/form/submit/form=fortbildung-create', [$this,'update_termine'], 10, 2);
	}

	public function update_termine ($form, $post_id) {

		var_dump($form,$post_id);
		die();

	}
}
new RpiReliFrontendFormsHandler();
