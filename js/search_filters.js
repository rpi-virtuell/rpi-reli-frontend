jQuery(document).ready(function () {
    jQuery('.search-filters').hide();
    jQuery('.wp-block-search__button').on('click', (e) => {
        jQuery('.search-filters').slideToggle();
    });
    jQuery('.search-filters').on('mouseleave', () => {
        jQuery('.search-filters').slideUp({duration: 1000});
    });
})