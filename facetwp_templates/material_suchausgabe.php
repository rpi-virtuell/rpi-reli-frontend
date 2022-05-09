<div class="material-grid">
    <?php
    while (have_posts())
        the_post();
    ob_start();
    ?>
    <article class="entry-card">
        <div class="facet-treffer">
            <div class="einrichtung">

            </div>
            <div class="material-title">
                <h2>
                    <a href="<?php the_permalink(); ?>"> <?php the_title() ?> </a>
                </h2>
            </div>
            <div class="picture">
                <img src="<?php the_post_thumbnail_url(); ?>" alt="">
            </div>
            <div class="excerpt"></div>
            <div class="taxonomien">

                <div class="author">

                </div>
                <div class="altergruppe">

                </div>
                <div class="materialtyp">

                </div>
                <div class="anlaesse">

                </div>
                <div class="kinderaktivitaet"></div>

                <div class="kinderfahrung">

                </div>
                <div class="schlagworte">

                </div>
                <div class="religioese-geschichten-erleben">

                </div>
                <div class="lizenzen">

                </div>
            </div>
    </article>

    <?php
    $buffer = ob_get_contents();
    ob_end_clean();
    ?>
</div>