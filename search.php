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


        add_action('blocksy:hero:after', function () {
            if (is_post_type_archive('organisation')) {
                ?>
                <h1>Regionalseiten</h1>
                <?php
            }
        });

        add_action('blocksy:hero:custom_meta:after', function (){
            if (get_post_type() === "organisation")
            {
                $contacts = get_field('contacts', get_the_ID());
                ?>
                <div class="organisation-contacts">
                <?php
                foreach ($contacts as $key => $contact)
                {
                    $userId = $contact['contact_person'][0];
                    $userName = get_the_author_meta('display_name', $userId);
                    ?>
                    <a class="organisation-contact-links" href="<?php echo get_author_posts_url($userId) ?>"><?php echo $userName . '('.$contact['contact_section'].')' ?></a>
                    <br>
                    <?php
                }
                ?>
                </div>
                <?php
            }
        });

        add_action('blocksy:single:content:top', function () {
            if (get_post_type() === "organisation") {
                $url_organisation = get_field('url_organisation', get_the_ID());
                $url_fortbildungen = get_field('url_fortbildungen', get_the_ID());
                if (!empty($url_oranisation) || !empty($url_fortbildungen)) {
                    ?>
                    <div class="organisation-referal-links">
                        <?php if (!empty($url_organisation)) { ?>
                            <a class="button" href="<?php echo $url_organisation ?>" target="_blank"
                               rel="noopener noreferrer">Link zur Organisation</a>
                            <?php
                        }
                        if (!empty($url_fortbildungen)) {
                            ?>
                            <a class="button" href="<?php echo $url_fortbildungen ?>" target="_blank"
                               rel="noopener noreferrer">Fortbildungsangebote</a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
            }
        });

        add_action('blocksy:single:content:bottom', function () {
            if (in_array(get_post_type(),["organisation","fortbildung"]) && current_user_can('edit_post', get_the_ID())) {
                ?>
                <details class="organisation-edit-section">
                <summary class="button">Bearbeiten</summary>
                <div>
                    <?php
                    if(get_post_type("organisation")){
	                    acfe_form('organisationpage-edit');
                    }else{
	                    acfe_form('fortbildung-edit');
                    }
                    ?>
                </div>
                </details>
                <?php
            }
        });

        add_action('blocksy:loop:card:start', function (){
            if (get_post_type() === 'organisation'){
                ?>
                <div class="card-tag-bar">
                <div title="Organisation" class="card-tag organisation-tag">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><rect fill="none" height="24" width="24"/><path d="M12,7V3H2v18h20V7H12z M10,19H4v-2h6V19z M10,15H4v-2h6V15z M10,11H4V9h6V11z M10,7H4V5h6V7z M20,19h-8V9h8V19z M18,11h-4v2 h4V11z M18,15h-4v2h4V15z"/></svg>
                </div>
                </div>
                <?php
            }
            if (get_post_type() === 'fortbildung'){
                ?>
                <div class="card-tag-bar">
                <div title="Fortbildung" class="card-tag fortbildung-tag">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/></g><g><g><path d="M20,8h-3V6c0-1.1-0.9-2-2-2H9C7.9,4,7,4.9,7,6v2H4c-1.1,0-2,0.9-2,2v10h20V10C22,8.9,21.1,8,20,8z M9,6h6v2H9V6z M20,18H4 v-3h2v1h2v-1h8v1h2v-1h2V18z M18,13v-1h-2v1H8v-1H6v1H4v-3h3h10h3v3H18z"/></g></g></svg>
                </div>
                </div>
                <?php
            }
        });
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

            $urheberschaft = $frontend_helper->get_tags_as_html('urheberschaft', true, 'Autor:innen:');
            $formal = $frontend_helper->get_tags_as_html('formal', true, 'Formal:');
            $inhalt = $frontend_helper->get_tags_as_html('inhalt', true, 'Inhalt:');
            $schlagwort = $frontend_helper->get_tags_as_html('tags', true, 'Schlagworte:');

            $report = $frontend_helper->get_report_as_html();
            $currentUser = wp_get_current_user();
            $result = '<div class="material-detail-grid">';

            $result .= '<div class="edit-button">';
            if (is_user_logged_in() && (get_the_author() === $currentUser->display_name || current_user_can('edit_others_materials'))) {
                $result .= '<a class="wp-block-button__link" href="' .
                    get_site_url() . '/wp-admin/post.php?post=' . get_the_ID() . '&action=edit">' .
                    'Bearbeiten' .
                    '<img src="' . __RPI_RELI_FRONTEND_URI__ . 'assets/edit.svg"> </a>';
            }
            $result .= '</div>';
            $result .=
                '<div class="material-content">' .
                '<h1 class ="material-title">' . get_the_title() . '</h1>' .
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
                    '<div class="material-tags">' .
                    $schlagwort .
                    '</div>' .
                    '<div class="material-report outer">' .
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
