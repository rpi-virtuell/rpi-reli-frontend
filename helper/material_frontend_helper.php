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
    function get_tags_as_html($field_group_name)
    {
        $html = '';
        $formal_tags = get_field($field_group_name);
        if (is_array($formal_tags)) {
            foreach ($formal_tags as $formal_key => $formal_tag) {
                if ($formal_tag) {
                    if ($formal_key == 'post_author') {
                        if (isset($formal_tags['coautor']))
                            $co_author = $formal_tags['coautor'];
                        $html .= '<div class="author">';
                        $html .= '<img class="taxonomy-icon" src="' . __RPI_RELI_FRONTEND_URI__ . 'assets/author.svg" title="Autor" alt="">';
                        $html .= get_the_author_posts_link();
                        if (!empty($co_author)) {
                            $html .= ', ' . $co_author;
                        }
                        $html .= ' (' . get_the_date() . ')';
                        continue;
                    }
                    if ($formal_key == 'coautor')
                        continue;

                    $taxonomy = get_taxonomy($formal_key);
                    $html .= '<div class="' . $formal_key . '">';
                    $html .= '<img class="taxonomy-icon" src="' . __RPI_RELI_FRONTEND_URI__ . 'assets/' . $formal_key . '.svg" title="' . $taxonomy->label . '" alt="">';
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
                    $html .= '</div>';
                }
            }
        }
        return $html;
    }


    function get_report_as_html()
    {

    }


}
