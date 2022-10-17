<?php
ob_start();
$startDate = get_field('startdate', get_the_ID());
$endDate = get_field('enddate', get_the_ID());

if (!empty($startDate) && !empty($endDate))
?>
<div class="fortbildung-date">
    <span class=""><?php echo $startDate . ' - ' . $endDate ?></span>
</div>

<?php
$organisationIds = get_field('organisation', get_the_ID());
if (!empty($organisationIds)) {
    ?>
    <div class="fortbildung-organisation">
        <h4>Veranstalter</h4>
        <?php
        foreach ($organisationIds as $organisationId) {
            ?>
            <div class="single-organisation">
                <a href="<?php echo get_post_permalink($organisationId) ?>">
                    <div class="single-organisation-spacer">

                        <?php
                        echo get_the_post_thumbnail($organisationId);
                        echo get_the_title($organisationId)
                        ?>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
<div class="fortbildung-contact">

</div>
<?php
echo ob_get_clean();
?>
