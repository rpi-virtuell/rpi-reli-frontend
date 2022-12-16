<?php
$termine = get_field('termine', get_the_ID());
if (!empty($termine)) {
    ?>
    <div class="reli-sidebar-section">
        <h4>Termine</h4>
        <?php
        foreach ($termine as $termin) {
            ?>
            <div class="single-termin">
                <div class="termin-date-box">
                    <div class="termin-day"><?php echo date('d', strtotime($termin['termin_datumzeit'])) ?></div>
                    <div class="termin-month"><?php echo date('M Y', strtotime($termin['termin_datumzeit'])) ?></div>
                </div>
                <div class="termin-daytime">
                    <?php
                    $startTime = date('H:i', strtotime($termin['termin_datumzeit']));
                    $endTime = date('H:i', strtotime($termin['termin_datumzeit']) + 3600 * $termin['termin_dauer']);
                    echo $startTime . ' - ' . $endTime . ' Uhr';
                    ?>
                    <?php if (!empty($termin['termin_hinweis'])) { ?>
                        <div class="termin-note"><?php echo $termin['termin_hinweis']; ?></div>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
$organisationIds = get_field('organisation', get_the_ID());
if (!empty($organisationIds)) {
    ?>
    <div class="fortbildung-organisation reli-sidebar-section">
        <h4>Veranstalter</h4>
        <?php
        foreach ($organisationIds as $organisationId) {
            ?>
            <div class="single-organisation">
                <a href="<?php echo get_post_permalink($organisationId) ?>">
                    <div class="single-organisation-spacer">
                        <?php  $organisationLogo = get_field('logo_organisation',$organisationId) ?>
                        <div class="single-logo" style="background-image: url('<?php echo $organisationLogo ?>')">
                        </div>
                        <span>
                        <?php
                        echo get_the_title($organisationId)
                        ?>
                        </span>
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
<?php
$contactPersons = get_field('kontaktperson', get_the_ID());
if (!empty($contactPersons)) {
    ?>
    <div class="fortbildung-contactperson reli-sidebar-section">
        <h4>Kontakt Personen</h4>
        <?php
        foreach ($contactPersons as $contactPerson) {
            ?>
            <div class="single-contactperson">
                <a href="<?php echo get_author_posts_url($contactPerson['name']) ?>">
                    <div class="single-contactperson-spacer">
                        <?php  $contactLogo = get_avatar_url($contactPerson['name']) ?>
                        <div class="single-logo" style="background-image: url('<?php echo $contactLogo ?>')">
                        </div>
                        <span>
                        <?php
                        echo get_the_author_meta('display_name', $contactPerson['name']);
                        ?>
                        </span>
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
<div class="reli-sidebar-section">
    <div class="fortbildung-certificate-box">
        <?php MaterialFrontendHelper::fortbildung_certificate_box(); ?>
    </div>
</div>
