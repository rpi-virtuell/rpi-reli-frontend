<h1><?php the_title() ?></h1>
<h3><?php echo get_field('subtitle', get_the_ID()) ?></h3>
<span><?php the_excerpt() ?></span>

<?php $tags = get_tags(array('taxonomy' => 'bundesland'));
if (!empty($tags)) {
    ?>
    <div class="fortbildung-tags">
        <?php foreach ($tags as $tag) {
            ?>
            <a class="button" href="<?php echo get_tag_link($tag) ?>">
                <?php echo $tag->name ?>
            </a>
            <?php
        } ?>
    </div>
    <?php
}