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
                    $html .= '<caption><h3>' . $table_title . '</h3></caption>';

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
                        $html .= ' (' . get_the_date() . ')';
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

    }


}
