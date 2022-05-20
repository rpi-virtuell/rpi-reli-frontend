<?php
/*
Plugin Name: Rpi Reli Frontend
Plugin URI: https://github.com/rpi-virtuell/rpi-material-input-template
Description: Wordpress Plugin to ADD shortcode which displays frontend pages
Version: 1.0
Author: Daniel Reintanz
Author URI: https://github.com/FreelancerAMP
License: A "Slug" license name e.g. GPL2
*/


class RpiReliFrontendSearch
{
    function __construct()
    {

        define('__RPI_RELI_FRONTEND_DIR__', plugin_dir_path(__FILE__));
        define('__RPI_RELI_FRONTEND_URI__', plugin_dir_url(__FILE__));

        include_once plugin_dir_path(__FILE__) . '/helper/material_frontend_helper.php';

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('rpi_reli_frontend_search_style', plugin_dir_url(__FILE__) . 'css/search.css');
            wp_enqueue_script('rpi_reli_frontend_js', plugin_dir_url(__FILE__) . 'js/search_filters.js', array(), false, true);
        });
        add_shortcode('rpi-reli-frontend-search', array($this, 'search'));
        add_filter('the_content', array($this, 'alter_frontend_material_content'));
    }

    static function getSearchPage()
    {
        if (empty($searchPage = get_page_by_title('Suche'))) {
            $searchPage = get_post(wp_insert_post(array('post_title' => 'Suche', 'post_type' => 'page', 'post_content' => '[rpi-reli-frontend-search]')));
        }
        if (is_a($searchPage, 'WP_Post')) {
            return get_permalink($searchPage);
        }
        return '';
    }

    function search()
    {
        ob_start();

        ?>
        <div class="search-page-grid">
            <div class="search-bar">
                <?php echo facetwp_display('facet', 'search'); ?>
                <button class="wp-block-search__button" id="search-filter-button" name="filter-button" type="button">
                    Erweiterte Suche
                </button>
            </div>
            <div class="search-filters">

                <?php
                $taxonomies = get_object_taxonomies('materialien', 'objects');
                foreach ($taxonomies as $taxonomy) {
                    if (is_a($taxonomy, 'WP_Taxonomy')) {
                        ?>
                        <div class="single-filter">
                            <h4>
                                <img class="filter-icon"
                                     src="<?php echo __RPI_RELI_FRONTEND_URI__ . "assets/" . $taxonomy->name . ".svg" ?>"
                                     alt="">
                                <?php echo $taxonomy->label ?>
                            </h4>
                            <?php echo facetwp_display('facet', $taxonomy->name); ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="search-results">
                <?php echo facetwp_display('template', 'material_suchausgabe'); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    function alter_frontend_material_content($content)
    {

        if (get_post_type(get_the_ID()) === "materialien") {

            $frontend_helper = new MaterialFrontendHelper(RpiReliFrontendSearch::getSearchPage());

            $urheberschaft = $frontend_helper->get_tags_as_html('urheberschaft');
            $formal = $frontend_helper->get_tags_as_html('formal');
            $inhalt = $frontend_helper->get_tags_as_html('inhalt');

            $report = $frontend_helper->get_report_as_html();

            if (!empty($urheberschaft))
                $urheberschaft = '<h3>Herkunft:</h3>' . $urheberschaft;
            if (!empty($formal))
                $formal = '<h3>Formal:</h3>' . $formal;
            if (!empty($inhalt)) {
                $inhalt = '<h3>Inhalt:</h3>' . $inhalt;
            }

            $currentUser = wp_get_current_user();
            $result = "";
            if (is_user_logged_in() && (get_the_author() === $currentUser->display_name || current_user_can('edit_others_materials'))) {
                $result .= '<div class="edit-spacer"> <a class="wp-block-button__link" href="' .
                    get_site_url() . '/wp-admin/post.php?post=' . get_the_ID() . '&action=edit">' .
                    'Bearbeiten' .
                    '<img src="' . __RPI_RELI_FRONTEND_URI__ . 'assets/edit.svg"> </a> </div>';
            }

            $result .=
                '<div class="material-detail-grid">' .
                '<div class="material-content">' .
                $content .
                '</div>';

            if (!empty($urheberschaft) || !empty($formal) || !empty($inhalt)) {
                $result .=
                    '<div class="material-taxonomies">' .
                    '<div class="material-origin">' .
                    $urheberschaft .
                    '</div>' .
                    '<div class="material-formal-tags">' .
                    $formal .
                    '</div>' .
                    '<div class="material-content-tags">' .
                    $inhalt .
                    '</div>' .
                    '</div>' .
                    '<div class="material-report">' .
                    $report .
                    '</div>';
            }

            $result .=
                '</div>';

            return $result;

        }
        return $content;
    }
}

new RpiReliFrontendSearch();