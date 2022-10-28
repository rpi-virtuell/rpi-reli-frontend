<?php
?>
<div class="author-profile">
    <div class="author-profile-card">
        <?php echo get_avatar(get_the_author_meta('ID')) ?>
        <h1> <?php echo get_the_author_meta('display_name');
            $first_name = get_the_author_meta('first_name');
            $last_name = get_the_author_meta('last_name');
            if (!empty($first_name) && !empty($last_name)) {
                echo ' (' . $first_name . ' ' . $last_name . ')';
            }
            ?></h1>
    </div>
    <p> <?php echo get_the_author_meta('user_description') ?></p>
</div>
