<?php
$frontend_helper = new MaterialFrontendHelper(RpiReliFrontendSearch::getSearchPage());
?>
<?php
if (!empty(get_field('inhalt'))) {
    ?>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('urheberschaft', true, 'Autor:innen');
        ?>
    </div>
    <?php
}
?>
<?php
if (!empty(get_field('inhalt'))) {
    ?>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('formal', true, 'Formal');
        ?>
    </div>
    <?php
}
?>
<?php
if (!empty(get_field('inhalt'))) {

    ?>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('inhalt', true, 'Inhalt');
        ?>
    </div>
    <?php
}
?>
<?php
if (!empty(get_field('tags'))) {
    ?>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('tags', true, 'Schlagworte');
        ?>
    </div>
    <?php
}
?>
<?php
if (!empty(get_field('inhalt'))) {
    ?>

    <div class="reli-report-section reli-sidebar-section">
        <?php
        echo $frontend_helper->get_report_as_html();
        ?>
    </div>
    <?php
}
?>