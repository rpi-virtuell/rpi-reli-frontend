<?php

class MaterialFrontendHelper
{
    private $frontend_uri;

    function __construct($frontend_uri)
    {
        $this->frontend_uri = $frontend_uri;
    }

    /**
     * Helper Function That returns a html string which can be put on a frontend page
     * @param string $field_group_name The Name of the ACF field group which needs to be put into html
     * @return string
     */
    function get_tags_as_html(string $field_group_name, bool $display_as_table = false, string $table_title = ""): string
    {
        $html = '';

        $formal_tags = get_field($field_group_name);
        if (is_array($formal_tags)) {
            if ($display_as_table) {
                $html .= '<table class="material-meta-table">';
                if ($table_title != "")
                    $html .= '<caption><h4>' . $table_title . '</h4></caption>';

                $table_data_bracket_open = '<td>';
                $table_data_bracket_close = '</td>';
                $tr_div_bracket_open = '<tr ';
                $tr_div_bracket_close = '</tr>';
            } else {
                $table_data_bracket_open = '';
                $table_data_bracket_close = '';
                $tr_div_bracket_open = '<div ';
                $tr_div_bracket_close = '</div>';
            }
            foreach ($formal_tags as $formal_key => $formal_tag) {
                if ($formal_tag) {

                    if ($formal_key == 'post_author') {
                        if (isset($formal_tags['coautor']))
                            $co_author = $formal_tags['coautor'];
                        $html .= $tr_div_bracket_open . 'class="author">';
                        $html .= $table_data_bracket_open;
                        $html .= '<img class="taxonomy-icon" src="' . __RPI_RELI_FRONTEND_URI__ . 'assets/author.svg" title="Autor" alt="">';
                        $html .= $table_data_bracket_close;
                        $html .= $table_data_bracket_open;
                        $html .= get_the_author_posts_link();
                        if (!empty($co_author)) {
                            $html .= ', ' . $co_author;
                        }
                        $term = wp_get_post_terms(get_the_ID(),'bundesland' );
                        if (!empty($term))
                        {
                            $termLink = '<a href="'.$this->frontend_uri.'?_'.'bundesland='.$term[0]->slug.'">'.$term[0]->name.'</a>';
                            $html .= ' (' .$termLink. ')';
                        }
                        $html .= $table_data_bracket_close;
                        $html .= $tr_div_bracket_close;
                        continue;
                    }
                    if ($formal_key == 'coautor')
                        continue;

                    $taxonomy = get_taxonomy($formal_key);
                    $html .= $tr_div_bracket_open . 'class="' . $formal_key . '">';
                    $html .= $table_data_bracket_open;
                    $html .= '<img class="taxonomy-icon" src="' . __RPI_RELI_FRONTEND_URI__ . 'assets/' . $formal_key . '.svg" title="' . $taxonomy->label . '" alt="">';
                    $html .= $table_data_bracket_close;
                    $html .= $table_data_bracket_open;
                    if (is_array($formal_tag)) {
                        $lastkey = array_key_last($formal_tag);
                        foreach ($formal_tag as $tag_key => $tag_id) {
                            $tag = get_term($tag_id);
                            $html .= '<a href="' . $this->frontend_uri . '?_' . $formal_key . '=' . $tag->name . '">' . $tag->name . '</a>';
                            if (count($formal_tag) > 1 && $tag_key != $lastkey)
                                $html .= ', ';
                        }
                    } else {
                        $term = get_term($formal_tag);
                        $html .= '<a href="' . $this->frontend_uri . '?_' . $formal_key . '=' . $term->name . '">' . $term->name . '</a>';
                    }
                    $html .= $table_data_bracket_close;
                    $html .= $tr_div_bracket_close;
                }
            }
        }
        if ($display_as_table)
            $html .= '</table>';
        return $html;
    }

	function get_report_as_html()
    {
	    $report = get_field('report');




		$criteria = get_field('kriterien');
	    $args = ['post_type' => 'material_criteria',
	             'numberposts' => -1,
	             'orderby'=>'menu_order',
	             'order'=>'ASC',
	             'tax_query' => array(
		             array(
			             'taxonomy' => 'version',
			             'field' => 'slug',
			             'terms' => get_option('current_criteria_version','v1') ,
			             'include_children' => true,
			             'operator' => 'IN'
		             )
	             )];
		$crits = get_posts($args);
	    ob_start();

        echo '<h3>Qualitätsmerkmale:</h3>';
        echo '<div class="material-report inner">';

	    if($report){


            foreach ($crits as $crit){
                $checked = '&nbsp; ';
                $class = 'missing';
                if(in_array($crit->post_name,$criteria)){
                    $checked = '✅️';
                    $class = 'available';
                }


                ?>
                <details class="material-report">
                    <summary class="<?php echo $class;?>">
                        <span><?php echo $checked;?></span>
                        <?php echo $crit->post_title;?>
                    </summary>
                    <div class="material-report-description">
                        <?php echo $crit->post_content;?>
                    </div>
                </details>
                <?php


            }
	    }else{
            ?>
                <div class="unchecked">
                    <p>Dieser Beitrag wurde nocht nicht redaktionell geprüft.</p>
                </div>
            <?php
	    }

	    echo '</div>';
		return ob_get_clean();
    }

    static function fortbildung_enroll_button($fobi=0){

	    $args = [
		    'post_type' => 'anmeldung',
		    'meta_query'=>[
			    'relation' => 'AND',
			    [
				    'key'=>'user',
				    'value'=> get_current_user_id(),
				    'compare'=>'=',
				    'type' => 'NUMERIC'
			    ],
			    [
				    'key'=>'fobi',
				    'value'=> intval($fobi),
				    'compare'=>'=',
				    'type' => 'NUMERIC'
			    ]
		    ]
	    ];
	    $posts = get_posts($args);

        if(count($posts)>0){
            echo "Du bist für diese  Fortbildung angemeldet. Hier geht es zum ";
	        echo   '<strong><a href="'. get_field('join_url',get_the_ID()) .'">Konferenzraum</a></strong>.';
        }else{

            echo   '<a class="button" href="'. home_url() . '/anmeldeformular/?fobi=' . $fobi .'">Zur Fortbildung anmelden</a>';
        }

    }

}
