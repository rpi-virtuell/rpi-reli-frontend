<div data-prefix="materialien_archive">
    <div class="material-grid entries">
        <?php
        while (have_posts()) :
            the_post();

            include_once __RPI_RELI_FRONTEND_DIR__ . '/helper/material_frontend_helper.php';
            $frontendHelper = new MaterialFrontendHelper(RpiReliFrontendSearch::getSearchPage());
            ob_start();
            ?>
            <article class="entry-card">
                <div class="facet-treffer">
                    <div class="material-title">
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
                        <div class="ct-ghost"> </div>
                        <div style="clear: both"></div>
                        <div class="taxonomien">

                            <?php
                            echo $frontendHelper->get_tags_as_html('urheberschaft', true);
                            echo $frontendHelper->get_tags_as_html('formal', true);
                            echo $frontendHelper->get_tags_as_html('inhalt', true);
                            echo $frontendHelper->get_tags_as_html('tags', true);
                            ?>
                        </div>
                    </div>
            </article>

            <?php
            $buffer = ob_get_contents();
            ob_end_clean();
            echo $buffer;
        endwhile;
        ?>
    </div>
</div>