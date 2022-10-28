<?php




if (is_user_logged_in() && get_the_author() === get_user_meta(get_current_user_id(), 'nickname', true)) {
    ?>
    <?php
    $args = [
        'post_type' => 'anmeldung',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'user',
                'value' => get_current_user_id(),
                'compare' => '=',
                'type' => 'NUMERIC'
            ],
        ]
    ];
    $anmeldungen = get_posts($args);
    if (!empty($anmeldungen)) {
        ?>
        <details class="author-fortbildungen-anmeldungen reli-sidebar-section">
        <summary class="author-fortbildungen-anmeldungen-summary"><h3>Angemeldete Fortbildungen</h3></summary>
        <?php
        foreach ($anmeldungen as $anmeldung) {
            $fortbildung = get_post(get_post_meta($anmeldung->ID, 'fobi', true));
            if (!empty($fortbildung)) {
                ?>

                <a href="<?php echo get_post_permalink($fortbildung->ID) ?>">
                    <div class="author-single-fortbildung">
                        <h4 class="author-single-fortbildung-title"> <?php echo $fortbildung->post_title ?> </h4>

                        <div class="author-single-fortbildung-thumbnail"> <?php echo get_the_post_thumbnail($fortbildung->ID) ?> </div>
                        <?php
                        $termine = get_field('termine', $fortbildung->ID);
                        if (!empty($termine)) {
                            ?>
                            <div class="author-single-fortbildung-termine">
                                <?php
                                foreach ($termine as $termin) {

                                    if (strtotime($termin['termin_datumzeit']) > date()) {
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
                                        break;
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <?php ?>
                    </div>
                </a>
                <?php
            }
        }
    }
}
    $args = [
        'post_type' => 'fortbildung',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'fortbildung_kontaktperson',
                'value' => get_current_user_id(),
                'compare' => '=',
                'type' => 'NUMERIC'
            ],
        ]
    ];
    $fortbildungen = get_posts($args);
     if (!empty($fortbildungen)) {
        ?>
        <details class="author-fortbildungen-anmeldungen reli-sidebar-section">
        <summary class="author-fortbildungen-anmeldungen-summary"><h3>Meine Fortbildungsangebote</h3></summary>
        <?php
        foreach ($fortbildungen as $fortbildung) {
            if (!empty($fortbildung)) {
                ?>

                <a href="<?php echo get_post_permalink($fortbildung->ID) ?>">
                    <div class="author-single-fortbildung">
                        <h4 class="author-single-fortbildung-title"> <?php echo $fortbildung->post_title ?> </h4>

                        <div class="author-single-fortbildung-thumbnail"> <?php echo get_the_post_thumbnail($fortbildung->ID) ?> </div>
                        <?php
                        $termine = get_field('termine', $fortbildung->ID);
                        if (!empty($termine)) {
                            ?>
                            <div class="author-single-fortbildung-termine">
                                <?php
                                foreach ($termine as $termin) {

                                    if (strtotime($termin['termin_datumzeit']) > date()) {
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
                                        break;
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <?php ?>
                    </div>
                </a>
                <?php
            }
        }
    }
     ?>
