<?php
$frontend_helper = new MaterialFrontendHelper(RpiReliFrontendSearch::getSearchPage());
?>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('urheberschaft', true, 'Autor:innen:');

        ?>
    </div>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('formal', true, 'Formal:');
        ?>
    </div>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('inhalt', true, 'Inhalt:');
        ?>
    </div>
    <div class="reli-sidebar-section">
        <?php
        echo $frontend_helper->get_tags_as_html('tags', true, 'Schlagworte:');
        ?>
    </div>
    <div class="reli-report-section reli-sidebar-section">
        <?php
        echo $frontend_helper->get_report_as_html();
        ?>
    </div>
<?php
?>