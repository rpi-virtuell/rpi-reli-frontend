<?php
$contacts = get_field('contacts', get_the_ID());
if (!empty($contacts)) {
    ?>
    <div class="organisation-contactperson reli-sidebar-section">
        <h5>Ansprechpartner:innen</h5>
        <?php
        foreach ($contacts as $key => $contact) {
            $userId = $contact['contact_person'];
            $userName = get_the_author_meta('display_name', $userId);
            ?>
            <div class="single-contactperson">
                <a href="<?php echo get_author_posts_url($userId) ?>">
                    <div class="single-contactperson-spacer">
                        <?php echo get_avatar($userId) ?>
                        <?php echo $userName . ' (' . $contact['contact_section'] . ')' ?>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
$url_organisation = get_field('url_organisation', get_the_ID());
$url_fortbildungen = get_field('url_fortbildungen', get_the_ID());
if (!empty($url_oranisation) || !empty($url_fortbildungen)) {
    ?>
    <div class="organisation-referral-links reli-sidebar-section">
        <?php if (!empty($url_organisation)) { ?>
            <a class="button" href="<?php echo $url_organisation ?>" target="_blank"
               rel="noopener noreferrer">Link zur Organisation</a>
            <?php
        }
        if (!empty($url_fortbildungen)) {
            ?>
            <a class="button" href="<?php echo $url_fortbildungen ?>" target="_blank"
               rel="noopener noreferrer">Fortbildungsangebote</a>
            <?php
        }
        ?>
    </div>
    <?php
}
$fortbildungen = get_field('fortbildungen', get_the_ID());
if (!empty($fortbildungen)){

    foreach ($fortbildungen as $fortbildung){

            ?>
         <details>
        <summary>
            <?php
            echo $fortbildung->post_title;
            ?>
        </summary>
             <div>
                 <a href="<?php echo get_post_permalink($fortbildung->ID); ?>">Zur Fortbildung</a>
                 <?php
                $termine = get_field('termine',$fortbildung->ID);
                 ?>
                 <?php
                 // TODO: REFACTOR
                 foreach ($termine as $termin) {
                     ?>
                     <div class="single-termin">
                         <div class="termin-date-box">
                             <div class="termin-day"><?php echo date('d',strtotime($termin['termin_datumzeit'])) ?></div>
                             <div class="termin-month"><?php echo date('M Y',strtotime($termin['termin_datumzeit'])) ?></div>
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
         </details>
        <?php
    }

}