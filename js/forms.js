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
            $(this).parent('details').toggleClass('open');
        });
    });

    $=jQuery;
    $('.organisation-edit-section summary.button').click(e=>{
        if($('.organisation-edit-section[open]').length>0){
             $('article.organisation').slideDown();

        }else{
            $('article.organisation').slideUp();

        }
    });
});
