jQuery(document).ready(function($) {
    // Handle wilaya change to load communes
    $(document).on('change', '#shipping_state', function() {
        var wilayaCode = $(this).val();
        var communeSelect = $('#shipping_city');
        
        if (!wilayaCode) {
            communeSelect.empty().append('<option value="">' + dzShippingParams.select_commune + '</option>');
            return;
        }
        
        communeSelect.empty().append('<option value="">' + dzShippingParams.loading_text + '</option>');
        
        $.ajax({
            url: dzShippingParams.ajax_url,
            type: 'POST',
            data: {
                action: 'dz_get_communes',
                wilaya_code: wilayaCode
            },
            success: function(response) {
                if (response.success) {
                    var options = '<option value="">' + dzShippingParams.select_commune + '</option>';
                    $.each(response.data, function(code, name) {
                        options += '<option value="' + code + '">' + name + '</option>';
                    });
                    communeSelect.empty().append(options);
                }
            }
        });
    });
    
    // Initialize commune field if wilaya is already selected
    if ($('#shipping_state').val()) {
        $('#shipping_state').trigger('change');
    }
});