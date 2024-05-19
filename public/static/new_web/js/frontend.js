$(document).on('change', '#monthly-yearly-button', function () {
    if ($(this).is(':checked') == true) {
        $(document).find('.price-yearly').removeClass('d-none');
        $(document).find('.price-monthly').addClass('d-none');
        $(document).find('.plan_type').val(2);
    }
    else {
        $(document).find('.price-yearly').addClass('d-none');
        $(document).find('.price-monthly').removeClass('d-none');
        $(document).find('.plan_type').val(1);
    }
});