<div class="material-grid">
    <?php
    while (have_posts()) :
        the_post();

        include_once __RPI_RELI_FRONTEND_DIR__ . '/helper/material_frontend_helper.php';
        $frontendHelper = new MaterialFrontendHelper(get_the_ID(), RpiReliFrontendSearch::getSearchPage());
        ob_start();
        ?>
        <article class="entry-card">
            <div class="facet-treffer">
                <div class="material-title">
                    <h2>
                        <a href="<?php the_permalink(); ?>"> <?php the_title() ?> </a>
                    </h2>
                </div>
                <?php if (!empty(get_the_post_thumbnail_url())) { ?>
                    <a class="material-picture boundless-image" href=" <?php the_permalink(); ?>">
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="">
                        <span class="ct-ratio" style="padding-bottom: 75%"></span>
                    </a>
                <?php } ?>
                <div class="excerpt">
                    <?php echo get_the_excerpt() ?>
                </div>
                <div class="ct-ghost"></div>
                <div style="clear: both"></div>
                <div class="taxonomien">
                    <div class="author">
                        <img class="taxonomy-icon"
                             src="<?php echo __RPI_RELI_FRONTEND_URI__ . "assets/author.svg" ?>"
                             alt="">
                        <a href="<?php echo get_site_url() . '/author/' . get_the_author() ?>"> <?php the_author() ?> </a>
                    </div>
                    <?php
                    echo $frontendHelper->get_tags_as_html('formal');
                    echo $frontendHelper->get_tags_as_html('inhalt');
                    ?>
                </div>
        </article>

        <?php
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    endwhile;
    ?>
</div>