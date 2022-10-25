/**
 * Bearbeiten Formular ein uns ausblenden
 */
jQuery(document).ready($=>{
    $('details summary').each(function(){
        $(this).nextAll().wrapAll('<div id="wrap"></div>');
    });



    $('details').attr('open','').find('#wrap').css('display','none');
    $('details.open div#wrap').css('display','block');
    $('details summary').click(function(e) {

        e.preventDefault();
        $(this).siblings('div#wrap').slideToggle(function(){
            console.log('toggle');
            $(this).parent('details').toggleClass('open');

            if($(e.target).closest('.edit-section').length>0){
                $('article.fortbildung').toggle();
                $('article.organisation').toggle();
            }



        });
    });

    $('.acf-field[data-name="teilnahme_datum"] select').on('change',e=>{
        location.href = '?termin='+ e.target.value;
    })
});
