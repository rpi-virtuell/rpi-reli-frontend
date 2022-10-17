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
            $('article.fortbildung').toggle();
            $('article.organisation').toggle();
        });
    });

});
