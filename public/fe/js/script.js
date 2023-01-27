$('[data-search]').on('keyup', function () {
    var searchVal = $(this).val();
    var filterItems = $('[data-filter-item]');

    if (searchVal != '') {
        filterItems.addClass('d-none');
        $('[data-filter-item][data-filter-name*="' + searchVal.toLowerCase() + '"]').removeClass('d-none');
    } else {
        filterItems.removeClass('d-none');
    }
});