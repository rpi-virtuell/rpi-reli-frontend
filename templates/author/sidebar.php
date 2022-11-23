<?php
$args = [
    'post_type' => 'organisation',
    'meta_query' => [
        'relation' => 'AND',
        [
            'key' => 'organisation_kontaktperson',
            'value' => get_the_author_meta('ID'),
            'compare' => '=',
            'type' => 'NUMERIC'
        ],
    ]
];
$organisationen = get_posts($args);
if (!empty($organisationen)) {
    ?>
    <div class="fortbildung-organisation reli-sidebar-section">
        <h4>Mitarbeit in:</h4>
        <?php
        foreach ($organisationen as $organisation) {
            ?>
            <div class="single-organisation">
                <a href="<?php echo get_post_permalink($organisation->ID) ?>">
                    <div class="single-organisation-spacer">
                        <?php $organisationLogo = get_field('logo_organisation', $organisation->ID) ?>
                        <?php if (!empty($organisationLogo)) {
                            ?>
                            <div class="single-logo"
                                 style="background-image: url('<?php echo $organisationLogo ?>')"></div>
                            <?php
                        } else {
                            ?>
                            <div class="single-logo"
                                 style="background-image: url('<?php echo __RPI_RELI_FRONTEND_URI__ . 'assets/organisation_logo.png' ?>')"></div>
                            <?php
                        } ?>
                        <span>
                        <?php
                        echo get_the_title($organisation->ID)
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

$args = [
    'post_type' => 'fortbildung',
    'meta_query' => [
        'relation' => 'AND',
        [
            'key' => 'fortbildung_kontaktperson',
            'value' => get_the_author_meta('ID'),
            'compare' => '=',
            'type' => 'NUMERIC'
        ],
    ]
];
$fortbildungen = get_posts($args);
if (!empty($fortbildungen)) {
    ?>
    <details class="author-fortbildungen-anmeldungen reli-sidebar-section open">
        <summary class="author-fortbildungen-anmeldungen-summary"><h4>Meine Fortbildungsangebote</h4></summary>
        <?php
        foreach ($fortbildungen as $fortbildung) {
            if (!empty($fortbildung)) {
                ?>

                <a href="<?php echo get_post_permalink($fortbildung->ID) ?>">
                    <div class="author-single-fortbildung">
                        <h5 class="author-single-fortbildung-title"> <?php echo $fortbildung->post_title ?> </h5>

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
        ?>
    </details>
    <?php
}

if (is_user_logged_in() && get_the_author() === get_user_meta(get_current_user_id(), 'nickname', true)) {
    ?>
    <?php
    $args = [
        'post_type' => 'anmeldung',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'user',
                'value' => get_the_author_meta('ID'),
                'compare' => '=',
                'type' => 'NUMERIC'
            ],
        ]
    ];
    $anmeldungen = get_posts($args);
    if (!empty($anmeldungen)) {
        ?>
        <details class="author-fortbildungen-anmeldungen reli-sidebar-section open">
            <summary class="author-fortbildungen-anmeldungen-summary"><h4>Angemeldet für:</h4></summary>
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
            ?>
        </details>
        <?php
    }
}

?>
