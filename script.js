jQuery(document).ready(function($) {
    // Actualizar el precio de envío al seleccionar una provincia
    $('#provincia').on('input', function() {
        var provincia = $(this).val();
        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: {
                action: 'actualizar_precio_envio',
                provincia: provincia
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar el resumen del carrito
                    $(document.body).trigger('update_checkout');
                }
            }
        });
    });

    // Añadir la provincia seleccionada como un campo oculto al añadir al carrito
    $(document).on('click', '.single_add_to_cart_button', function() {
        var provincia = $('#provincia').val();
        $('<input>').attr({
            type: 'hidden',
            name: 'provincia',
            value: provincia
        }).appendTo('form.cart');
    });

    // Actualizar el precio de envío en el backend
    function actualizarPrecioEnvio() {
        var provincia = $('#provincia').val();
        $.post(wc_add_to_cart_params.ajax_url, {
            action: 'actualizar_precio_envio',
            provincia: provincia
        }, function(response) {
            if (response.success) {
                // Actualizar el resumen del carrito
                $(document.body).trigger('update_checkout');
            }
        });
    }
});
