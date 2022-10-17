<?php
ob_start()
?>
<div>

    <?php the_post_thumbnail(); ?>

</div>
<div>
    <?php the_content(); ?>
</div>

<?php
echo ob_get_clean();
?>