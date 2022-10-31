<?php
?>
<div class="author-profile">
    <div class="author-profile-card">
        <?php echo get_avatar(get_the_author_meta('ID')) ?>

    </div>
    <div class="author-profile-card-details">
        <h1> <?php echo get_the_author_meta('display_name');
            ?></h1>
        <?php $description = get_the_author_meta('user_description');
        if (!empty($description)) {
            ?>
            <p> <?php echo $description ?></p>
            <?php
        }
        ?>
    </div>
</div>
