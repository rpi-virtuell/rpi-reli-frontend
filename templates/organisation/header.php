
<h1><?php the_title() ?></h1>
<span><?php the_excerpt() ?></span>


<?php $tags = get_the_terms(get_the_ID(), 'bundesland');
if (!empty($tags)) {
    ?>
    <div class="organisation-tags">
        <?php foreach ($tags as $tag) {
            if (!empty($tag->name)){
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