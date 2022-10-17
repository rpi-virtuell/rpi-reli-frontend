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
                <span class="termin-date">Datum: <?php echo $termin['termin_datumzeit']; ?></span><br>
                <span class="termin-duration">Dauer: <?php echo $termin['termin_dauer']; ?> Stunde/n</span><br>
                <?php if (!empty($termin['termin_hinweis'])) { ?>
                    <span class="termin-note">Hinweis: <?php echo $termin['termin_hinweis']; ?></span>
                <?php } ?>
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
                        <?php
                        echo get_avatar($contactPerson['name']);
                        echo get_the_author_meta('display_name', $contactPerson['name']);
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
<div class="reli-sidebar-section">
    <div class="fortbildung-joinlink">
        <a class="button" href="<?php echo 'https://test.rpi-virtuell.de/anmeldeformular/?fobi=' . get_the_ID() ?>">Einschreiben</a>
    </div>
</div>
