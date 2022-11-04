<?php ?>
<div class="ct-container">
    <?php
    $editing = false;
    $post_status = 'publish';

    if (is_user_logged_in() && get_current_user_id() == get_the_author_meta('ID') || current_user_can('edit_others_materials')) {
        $editing = true;
        $post_status = ['publish', 'draft'];
    }
    $args = [
        'post_type' => 'materialien',
        'post_status' => $post_status,
        'author' => get_the_author_meta('ID')
    ];
    $query = new WP_Query($args);


    if ($query->have_posts()) {
        ?>

        <div data-prefix="author">
            <div class="material-grid entries">
                <?php
                while ($query->have_posts()) {
                    $query->the_post();
                    include_once __RPI_RELI_FRONTEND_DIR__ . '/helper/material_frontend_helper.php';
                    $frontendHelper = new MaterialFrontendHelper(RpiReliFrontendSearch::getSearchPage());
                    ob_start();
                    ?>
                    <article class="entry-card">
                        <div class="facet-treffer">
                            <div class="material-title <?php echo get_post_status() === 'draft' ? 'draft' : '' ?>">
                                <h2>
                                    <a href="<?php the_permalink(); ?>"> <?php the_title() ?> </a>
                                </h2>
                            </div>
                            <div class="material-card-content">
                                <?php if (!empty(get_the_post_thumbnail_url())) { ?>
                                    <a class="material-picture boundless-image" href=" <?php the_permalink(); ?>">
                                        <img src="<?php the_post_thumbnail_url(); ?>" alt="">
                                        <span class="ct-ratio" style="padding-bottom: 75%"></span>
                                    </a>
                                <?php } ?>
                                <div class="excerpt">
                                    <?php the_excerpt() ?>
                                </div>
                                <div class="ct-ghost"></div>
                                <div style="clear: both"></div>
                                <div class="taxonomien">

                                    <?php
                                    echo $frontendHelper->get_tags_as_html('urheberschaft', true);
                                    echo $frontendHelper->get_tags_as_html('formal', true);
                                    echo $frontendHelper->get_tags_as_html('inhalt', true);
                                    echo $frontendHelper->get_tags_as_html('tags', true);
                                    ?>
                                </div>
                                <?php if ($editing) { ?>
                                    <div class="editing-section">
                                        <span style="float:right">
                                            <a title="Bearbeiten" href="<?php echo get_edit_post_link(); ?>">✏</a>
                                            <a title="Löschen" href="<?php echo get_delete_post_link(); ?>">❌</a>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </article>

                    <?php
                    $buffer = ob_get_contents();
                    ob_end_clean();
                    echo $buffer;
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>
