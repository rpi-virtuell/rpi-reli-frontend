<ul class="my-material results">
    <?php if (is_user_logged_in()) { ?>
        <?php
        if (have_posts()) {
            while (have_posts()): the_post(); ?>
                <li>
                    <h3 class="entry-title my-material" style="font-size:20px; border-bottom: 1px solid #ddd;">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span style="float:right">
                            <a title="Bearbeiten" href="<?php echo get_edit_post_link(); ?>">✏</a>
                            <a title="Löschen" href="<?php echo get_delete_post_link(); ?>">❌</a>
                        </span>
                    </h3>
                    <?php the_excerpt(); ?>
                </li>
            <?php endwhile;
        } else {
            ?>
            <li>
                <span>Momentan hast du keine Materialien.</span>
            </li>
            <?php
        }
    } else { ?>
        <li>
            <span>Zum Anzeigen deiner Materialien musst du dich anmelden!</span>
        </li>
        <?php
    } ?>
</ul>