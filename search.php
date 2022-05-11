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
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('rpi_reli_frontend_search_style', plugin_dir_url(__FILE__) . 'search.css');
        });
        add_shortcode('rpi-reli-frontend-search', array($this, 'search'));
        define('__RPI_RELI_FRONTEND_DIR__', plugin_dir_path(__FILE__));
        define('__RPI_RELI_FRONTEND_URI__', plugin_dir_url(__FILE__));
    }

    function search()
    {
        ob_start();
        define('__RPI_RELI_FRONTEND_SEARCH_URL__', site_url());

        //TODO: fill search-filters automatically ? via option page ? -> select which facet should be displayed in search
        //  NOTE: Find way to get all FACETS of facetwp

        ?>
        <div class="search-page-grid">
            <div class="search-bar">
                <?php echo facetwp_display('facet', 'search'); ?>
                <button class="wp-block-search__button" name="filter-button"> Erweiterte Suche</button>
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

    static function get_taxonomy_to_html($postID, $taxonomy_name, $search_URL = __RPI_RELI_FRONTEND_SEARCH_URL__)
    {
        $taxonomies = '';
        if (is_array($results = wp_get_post_terms($postID, $taxonomy_name))) {
            $lastkey = array_key_last($results);
            foreach ($results as $key => $result) {
                if (is_a($result, 'WP_Term')) {
                    $taxonomies .= '<a href="' . $search_URL . '?_' . $taxonomy_name . '=' . $result->slug . '">' . $result->slug . '</a>';
                    if (count($results) > 1 && $key != $lastkey)
                    $taxonomies .= ', ';
                }
            }
        }
        return $taxonomies;
    }
}

new RpiReliFrontendSearch();