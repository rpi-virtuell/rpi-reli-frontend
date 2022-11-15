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
    static $post_types  = [
            'fortbildung',
        'organisation',
        'materialien'
    ];
    function __construct()
    {

        define('__RPI_RELI_FRONTEND_DIR__', plugin_dir_path(__FILE__));
        define('__RPI_RELI_FRONTEND_URI__', plugin_dir_url(__FILE__));

        include_once plugin_dir_path(__FILE__) . '/helper/material_frontend_helper.php';

        add_action('wp', function () {
            if ($_SERVER['REQUEST_URI'] === '/meinprofil') {
                if (is_user_logged_in()) {
                    $user = get_userdata(get_current_user_id());
                    wp_redirect(home_url('author/' . $user->user_login));
                }
            }
    });

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('rpi_reli_frontend_search_style', plugin_dir_url(__FILE__) . 'css/search.css');
            wp_enqueue_style('rpi_reli_frontend_forms_style', plugin_dir_url(__FILE__) . 'css/forms.css');
            wp_enqueue_style('rpi_reli_frontend_templates_style', plugin_dir_url(__FILE__) . 'css/templates.css');
            wp_enqueue_script('rpi_reli_frontend_forms_js', plugin_dir_url(__FILE__) . 'js/forms.js', array(), false, true);
            wp_enqueue_script('rpi_reli_frontend_scripts_js', plugin_dir_url(__FILE__) . 'js/scripts.js', array(), false, true);
        });
        add_shortcode('rpi-reli-frontend-search', array($this, 'search'));

        add_action('init', function (){
            if (!isset($_COOKIE['relimentar_first_visit'])) {
                setcookie('relimentar_first_visit', 'false', strtotime('+1 year'));
            }
        });
        // ADD edit forms for all post types add form under header of page
        add_action('blocksy:content:top', function () {

            if (!is_author() && in_array(get_post_type(), ["organisation", "fortbildung"]) && is_single() && ( current_user_can('edit_'. get_post_type(), get_the_ID()) || current_user_can('edit_others_posts', get_the_ID()) )) {
                ?>
                <div class="ct-container top-buttons">

                    <details class="edit-section">
                        <summary class="button">Bearbeiten
                            <img src="<?php echo __RPI_RELI_FRONTEND_URI__ . 'assets/edit.svg' ?>">
                        </summary>
                        <div class="organisation-edit-form">
                            <?php
                            if (get_post_type() === 'organisation') {

                                acfe_form('organisationpage-edit');

                            } else {
                                acfe_form('fortbildung-edit');
                            }
                            ?>
                        </div>
                    </details>
                    <?php if (get_post_type() === 'fortbildung') {
                        ?>
                        <details class="teilnehmer-liste">
                            <summary class="button">
                                <?php include_once 'assets/anlass.svg'?>
                                Teilnahme Check
                            </summary>
                            <?php acfe_form('anmeldungen');?>
                        </details>
                        <?php
                    }?>

                </div>
                <?php
            }
            if (!is_author() && get_post_type() === 'materialien') {
                if (is_user_logged_in() && (current_user_can('edit_material',get_the_ID()) || current_user_can('edit_others_materials'))) {
                    ?>
                    <div class="ct-container edit-section">
                        <a class="button"
                           href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . get_the_ID() ?> &action=edit">
                            Bearbeiten
                            <img src="<?php echo __RPI_RELI_FRONTEND_URI__ . 'assets/edit.svg' ?>">
                        </a>
                    </div>
                    <?php
                }
            }
            if (is_author())
            {

                $currentUser = wp_get_current_user();
                $authorId = get_the_author_meta('ID');
                if (is_user_logged_in() && user_can($currentUser,'edit_others_materials' ))
                {
                    $anbieterinStatus = get_user_meta($authorId, 'anbieterin_status',true);
                    if($anbieterinStatus === 'pending'){
                        ?>
                        <div class="ct-container anbieter-box">
                            <p class="search-tutorial"> Diese:r Nutzer:in hat angefragt ein:e Anbieter:in für Fortbildungen zu werden sollen diese Rechte vergeben werden</p>
                            <a href="?role=grant" class="button accept">Rechte gewähren</a>
                            <a href="?role=deny" class="button deny">Rechte verbieten</a>
                        </div>
                        <?php
                    }
                    if (isset($_GET['role']))
                    {
                      if ($_GET['role'] === 'grant')
                      {
                          update_user_meta($authorId, 'anbieterin_status', 'granted');
                          get_userdata($authorId)->set_role('anbieterin');
                          wp_redirect('');


                      }
                      elseif ($_GET['role'] === 'deny')
                      {
                          update_user_meta($authorId, 'anbieterin_status', 'denied');
                          wp_redirect('');
                      }
                    }

                }

                if (is_user_logged_in() && get_the_author() === $currentUser->display_name)
                {
                    ?>
                    <div class="ct-container">
                        <details class="edit-section">
                            <summary class="button">Bearbeiten
                                <img src="<?php echo __RPI_RELI_FRONTEND_URI__ . 'assets/edit.svg' ?>"></summary>
                            <div class="organisation-edit-form">
                                <?php
                                echo do_shortcode('[basic-user-avatars]');
                                acfe_form('user_profile_settings');
                                ?>
                            </div>
                        </details>
                    </div>
                    <?php
                }
            }
        });

        // Remove default content of posttypes
        add_action('blocksy:single:content:top', function () {
            if (in_array(get_post_type(), RpiReliFrontendSearch::$post_types) && is_single()) {
                ob_start();
            }
        });
        // Remove default content of author
        add_action('blocksy:content:top', function (){
            if (is_author()){
                ob_start();
            }
        });

        // Apply Template to Author
        add_action('blocksy:footer:before', function (){
            if (is_author()){
                $postType = 'author';
                ob_end_clean();

                //Ausgabe von neuen Templates
                ?>
                <div class="ct-container-full" data-content="normal" data-vertical-spacing="top:bottom">
                    <article>
                        <?php
                        $this->reliTemplateOutput($postType);
                        ?>
                    </article>
                </div>
                <?php
            }
        });
        // Apply Template to posttypes
        add_action('blocksy:single:content:bottom', function () {
            $postType = get_post_type();
            if (in_array($postType, RpiReliFrontendSearch::$post_types) && is_single()) {
                ob_end_clean();

                //Ausgabe von neuen Templates
                $this->reliTemplateOutput($postType);
            }
        });

        //ADD designs to search cards of two posttypes
        add_action('blocksy:loop:card:start', function (){
            if (get_post_type() === 'organisation'){
                ?>
                <div class="card-tag-bar">
                <div title="Organisation" class="card-tag organisation-tag">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><rect fill="none" height="24" width="24"/><path d="M12,7V3H2v18h20V7H12z M10,19H4v-2h6V19z M10,15H4v-2h6V15z M10,11H4V9h6V11z M10,7H4V5h6V7z M20,19h-8V9h8V19z M18,11h-4v2 h4V11z M18,15h-4v2h4V15z"/></svg>
                </div>
                </div>
                <?php
            }
            if (get_post_type() === 'fortbildung'){
	            ?>
                <div class="card-tag-bar">
                <div title="Fortbildung" class="card-tag fortbildung-tag">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><g><rect fill="none" height="24" width="24"/></g><g><g><path d="M20,8h-3V6c0-1.1-0.9-2-2-2H9C7.9,4,7,4.9,7,6v2H4c-1.1,0-2,0.9-2,2v10h20V10C22,8.9,21.1,8,20,8z M9,6h6v2H9V6z M20,18H4 v-3h2v1h2v-1h8v1h2v-1h2V18z M18,13v-1h-2v1H8v-1H6v1H4v-3h3h10h3v3H18z"/></g></g></svg>
                </div>
                </div>
                <?php
            }
        });
        //ADD search facet to organisation search
        add_action('blocksy:loop:before',function (){
            if (get_post_type() === 'organisation')
            {
                $taxonomy = get_taxonomy('bundesland')
                ?>
                <h1>Anbieter</h1>
                <div class="search-bar">
                    <?php echo facetwp_display('facet', 'search'); ?>
                    <?php echo facetwp_display('facet','reset'); ?>
                </div>
                <div class="search-filter-selections">
                    <?php echo facetwp_display( 'selections' ); ?>
                </div>
                <details class="organisation-search-filters">
                    <summary class="button">
                        🧩 Erweiterte Suche
                    </summary>
                    <div class="search-filters">
                        <div class="single-filter">
                            <h4>
                                <img class="filter-icon"
                                     src="<?php echo __RPI_RELI_FRONTEND_URI__ . "assets/" . $taxonomy->name . ".svg" ?>"
                                     alt="">
                                <?php echo $taxonomy->label ?>
                            </h4>
                            <?php echo facetwp_display('facet', $taxonomy->name); ?>
                        </div>
                    </div>
                </details>
                <?php
            }
        });
    }

    public function reliTemplateOutput($postType){
        ob_start();
        $wallpaperURL = get_field('hintergrundbild','user_'.get_the_author_meta('ID'));
        ?>
        <div class="reli-post-grid <?php echo $postType?>">
            <div class="reli-top-section <?php echo $postType  ?>"
            <?php
            if ($postType === 'author' && !empty($wallpaperURL)){
                ?>
                style="background-image: url('<?php echo $wallpaperURL?>');"
                <?php
            }
            ?>
            >
                <div class="reli-header <?php echo $postType ?>">
                    <?php require_once plugin_dir_path(__FILE__) . 'templates/' . $postType . '/header.php'; ?>
                </div>
                <div class="reli-sidebar <?php echo $postType ?>">
                    <?php require_once plugin_dir_path(__FILE__) . 'templates/' . $postType . '/sidebar.php'; ?>
                </div>
            </div>
            <div class="reli-content <?php echo $postType ?>">
                <?php require_once plugin_dir_path(__FILE__) . 'templates/' . $postType . '/content.php'; ?>
            </div>
        </div>
        <?php
        echo ob_get_clean();

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
            <details <?php echo isset($_COOKIE['relimentar_first_visit']) ? '' : 'class= "open" open="open"'  ?>>
                <summary class="search-tutorial-button button">ℹ Info</summary>
               <div class="search-tutorial">
                <?php  echo get_the_content(null, false , get_option('options_tutorial_template')) ?>
               </div>
            </details>
            <h1>Materialien</h1>
            
            <div class="search-bar">
                <?php echo facetwp_display('facet', 'search'); ?>
                <?php echo facetwp_display('facet','reset'); ?>
            </div>

            <div class="search-filter-selections">
                <?php echo facetwp_display( 'selections' ); ?>
            </div>
            <details>
                <summary class="button">
                    🧩 Erweiterte Suche
                </summary>
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
            </details>
            <div class="search-results">
                <?php echo facetwp_display('template', 'material_suchausgabe'); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

}

add_shortcode('terminsuche', function () {
    ob_start();

    if (isset($_GET['startdate'])) {
        $startDate = $_GET['startdate'];
    } elseif (isset($atts['startdate'])) {
        $startDate = $atts['startdate'];
    } else {
        $startDate = date('Y-m-d');
    }

    ?>
    <div class="reli-termine-list">
        <form id="termineSearchForm" name="Termin Suche" method="get">
            <?php
            $terms = get_terms(array('taxonomy' => 'bundesland'));
            ?>
            <details class="open" open="open">
                <summary class="button">
                    🧩 Erweiterte Suche
                </summary>
                <div class="termin-term-filter">
                    <div>
                        <h4>
                            <img class="filter-icon"
                                 src="<?php echo __RPI_RELI_FRONTEND_URI__ . "assets/bundesland.svg" ?>"
                                 alt="">
                            Bundesländer
                        </h4>
                        <?php
                        foreach ($terms as $term) {
                            if (isset($_GET[$term->slug]))
                                $activeTerms[] = $term->slug;
                            ?>
                            <input type="checkbox" id="<?php echo $term->slug; ?>" name="<?php echo $term->slug; ?>"
                                   value="1" <?php echo isset($_GET[$term->slug]) ? 'checked' : ''; ?>>
                            <label for="<?php echo $term->slug; ?>"><?php echo $term->name; ?></label><br>
                            <?php
                        }
                        ?>
                    </div>
                    <label for="dateSelector">Startdatum</label>
                    <input type="date" name="startdate" id="dateSelector" value="<?php echo $startDate ?>">
                    <input class="relilab-submit-button" type="submit" value="Filter anwenden">
                </div>
            </details>
        </form>
    </div>
    <?php

     $termIds = [];
        if (isset($activeTerms))
            {
                $args = [
             'post_type' => 'fortbildung',
             'numberposts' => -1,
             'tax_query' => [
                                          'relation'=> 'OR',
                     [
                     'taxonomy' => 'bundesland',
                     'field' => 'slug',
                     'terms' => $activeTerms]
                     ]
             ];
	 $termPosts = get_posts($args);
     foreach ($termPosts as $post){
         $termIds[] = $post->ID;
     }
            }
if (!empty($termPosts) || empty($activeTerms))
    {
        $termine = RpiReliFrontendFormsHandler::get_termine('ASC',strtotime($startDate),false,$termIds);
foreach ($termine as $termin)
{
    ?>
    <div class="termin-list-card">
    <div class="single-termin">
    <div class="termin-spacer">
        <div class="termin-date-box">
            <div class="termin-day">
            <?php echo date('d', $termin->timestamp) ?>
            </div>
            <div class="termin-month">
            <?php echo date('M Y', $termin->timestamp) ?>
            </div>
        </div>
        </div>


        <div class="fortbildung-name">
        <a href="<?php echo get_post_permalink($termin->post_id); ?>" >
        <div class="termin-list-card-header">
<div class="termin-daytime"><?php
                $startTime = date('H:i', $termin->timestamp);
                $endTime = date('H:i', $termin->timestamp + 3600 * $termin->dauer);
                echo $startTime . ' - ' . $endTime . ' Uhr';
                ?></div>   <h4>
                    <?php echo $termin->title; ?>
                    </h4>
                    </div>
        </a>
                   <div class="termin-list-card-content">
                    <?php if (!empty($termin->subtitle)) { ?>
                    <span class="termin-subtitle">
                    <?php echo $termin->subtitle; ?>
                    </span>
                <?php } ?>
                    <?php if (!empty($termin->hinweis)) { ?>
                    <br>
                    <span>
                    <?php echo $termin->hinweis ?>
                    </span>
                <?php } ?>
                      <div class="termin-tags">
                <?php $terminTags =  wp_get_post_terms($termin->post_id, 'bundesland');
                foreach ($terminTags as $terminTag){
                    ?>
                     <a class="button" href="<?php echo home_url('bundesland/'. $terminTag->slug)?>"> <?php echo $terminTag->name ?></a>
                     <?php
                }
                ?>
                </div>

                </div>
                </div>
    </div>
    </div>
    <?php
}
    }else{
    ?>
    <br>
    <span>
    Es wurden keine Fortbildungen gefunden
</span>
        <?php
    }


    return ob_get_clean();
});
new RpiReliFrontendSearch();


include_once "helper/forms_handler.php";
