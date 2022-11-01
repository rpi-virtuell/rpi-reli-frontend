<?php
$organisationLogo = get_field('logo_organisation', get_the_ID());
?>
    <div class="organisation-title-logo">
        <img class="organisation-logo" src="<?php echo $organisationLogo ?>">
        <h1><?php the_title() ?></h1>
    </div>
    <span><?php the_excerpt() ?></span>


<?php $tags = get_the_terms(get_the_ID(), 'bundesland');
if (!empty($tags)) {
    ?>
    <div class="organisation-tags">
        <?php foreach ($tags as $tag) {
            if (!empty($tag->name)) {
                ?>
                <a class="button" href="<?php echo get_term_link($tag) ?>">
                    <?php echo $tag->name ?>
                </a>
                <?php
            }
        } ?>
    </div>
    <?php
}