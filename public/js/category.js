$(document).ready(function() {
    // Mobile filter toggle
    $('#filter-toggle').click(function() {
        $('#filter-panel').toggleClass('show');
        $(this).find('.fa-chevron-down').toggleClass('fa-chevron-up');
    });

    // Sort form submission
    $('#sort-select').change(function() {
        $('#sort-form').submit();
    });

    // View toggle
    $('.view-btn').click(function() {
        $('.view-btn').removeClass('active');
        $(this).addClass('active');

        var view = $(this).data('view');
        if (view === 'list') {
            $('.products-grid').addClass('list-view');
            $('.product-card').addClass('list-card');
        } else {
            $('.products-grid').removeClass('list-view');
            $('.product-card').removeClass('list-card');
        }
    });

    // Brand filter
    $('.brand-filter').change(function() {
        var selectedBrands = [];
        $('.brand-filter:checked').each(function() {
            selectedBrands.push($(this).val());
        });

        // Update URL with brand filters
        var url = new URL(window.location);
        if (selectedBrands.length > 0) {
            url.searchParams.set('brands', selectedBrands.join(','));
        } else {
            url.searchParams.delete('brands');
        }

        window.location.href = url.toString();
    });

    // Clear filters
    $('#clear-filters').click(function() {
        window.location.href = window.location.pathname;
    });

    // Price slider (if jQuery UI present)
    if ($.fn.slider) {
        $('#price-slider').slider({
            range: true,
            min: 0,
            max: 10000,
            values: [0, 10000],
            slide: function(event, ui) {
                $('#min-price').val(ui.values[0]);
                $('#max-price').val(ui.values[1]);
                $('#price-range-display').text('৳' + ui.values[0] + ' - ৳' + ui.values[1]);
            }
        });
    }
});


