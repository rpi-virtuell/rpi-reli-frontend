<?php
$organisationLogo = get_field('logo_organisation', get_the_ID());
?>
    <div class="organisation-title-logo">
        <?php if (!empty($organisationLogo)){
            ?>
            <img class="organisation-logo" src="<?php echo $organisationLogo ?>">
            <?php
        }else{
            ?>
            <img class="organisation-logo" src="<?php echo __RPI_RELI_FRONTEND_URI__ .'assets/organisation_logo.png' ?>">
            <?php
        } ?>
        <h1><?php the_title() ?></h1>
    </div>


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