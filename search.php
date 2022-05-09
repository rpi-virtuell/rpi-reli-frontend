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
        add_shortcode('rpi-reli-fronend-search', array($this, 'search'));
    }

    function search()
    {
//        ob_start();
//        load_template(dirname(__FILE__) . '/templates/single-termin-block.php', false);
//       return ob_get_clean();

        //TODO: fill search-filters automatically ? via option page ? -> select which facet should be displayed in search
        //  NOTE: Find way to get all FACETS of facetwp

        wp_enqueue_style('rpi_reli_frontend_search_style', get_stylesheet_directory_uri() . '/search.css');
        ?>
        <div class="search-page-grid">
            <div class="search-bar">
                <?php echo facetwp_display('facet', 'search'); ?>
                <input type="button" value="Suche">
                <button> Erweiterte Suche</button>
            </div>
            <div class="search-filters">
                <?php echo facetwp_display('facet', 'materialtypen'); ?>
                <?php echo facetwp_display('facet', 'anlaesse'); ?>
                <?php echo facetwp_display('facet', 'altergruppe'); ?>
                <?php echo facetwp_display('facet', 'kinderaktivitaeten'); ?>
                <?php echo facetwp_display('facet', 'Einrichtungen'); ?>
                <?php echo facetwp_display('facet', 'Religiöse Geschichten erleben'); ?>
                <?php echo facetwp_display('facet', 'Schlagworte'); ?>
                <?php echo facetwp_display('facet', 'Lizenzen'); ?>
                <?php echo facetwp_display('facet', 'Sortieren'); ?>
            </div>
            <div class="search-results">

                <?php echo facetwp_display('template', 'material_suchausgabe'); ?>
            </div>
        </div>
        <?php
    }
}

new RpiReliFrontendSearch();