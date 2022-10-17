<?php ob_start(); ?>

    <h1><?php the_title() ?></h1>
    <span><?php the_excerpt() ?></span>
<?php echo ob_get_clean() ?>