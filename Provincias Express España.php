<?php
/*
Plugin Name: Provincias Express España
Plugin URL: https://arturomerchan.com/plugins
Description: Provincias Express España es un plugin de WordPress creado por Arturo Mercán de Mercán.Dev, diseñado para gestionar envíos dentro de las provincias de España. Integrado con WooCommerce, permite configurar tarifas de envío específicas por provincia, mejorando la eficiencia y experiencia del cliente. Con una interfaz intuitiva, facilita la automatización del cálculo de envíos y ofrece actualizaciones regulares y soporte técnico.
Version: 1.0.1
Author: Arturo Merchan | Merchan.Dev
Author URL: https://arturomerchan.com/
License: Licencia de Uso Privado
Provincias Express España Autor: Arturo Mercán Desarrollador: Mercán.Dev URL: arturomerchan.com

Derechos Reservados: Todos los derechos del plugin Provincias Express España están reservados a Arturo Mercán de Mercán.Dev.

1. Uso del Software: Este software está autorizado para su uso solo bajo los términos de esta licencia. Está prohibido el uso no autorizado, la modificación y la distribución del software sin el permiso expreso del autor.

2. Modificación del Software: No se permite la modificación del software sin el consentimiento previo y por escrito del autor.

3. Distribución del Software: Queda prohibida la distribución de este software, ya sea de forma gratuita o comercial, sin el consentimiento previo y por escrito del autor.

4. Propiedad Intelectual: Este software es propiedad exclusiva de Arturo Mercán. Cualquier infracción de los términos de esta licencia será perseguida conforme a la legislación vigente.

5. Garantía y Responsabilidad: Este software se proporciona "tal cual", sin garantía de ningún tipo, expresa o implícita. En ningún caso el autor será responsable de ningún daño derivado del uso de este software.

6. Revocación de Licencia: El autor se reserva el derecho de revocar esta licencia en cualquier momento si se violan los términos establecidos.

Contacto: Para cualquier consulta sobre los términos de esta licencia o para solicitar permisos adicionales, contactar a Arturo Mercán en soporte@arturomerchan.com.
*/

// Enqueue scripts y styles
function envios_woocommerce_enqueue_scripts() {
    wp_enqueue_style('envios-woocommerce-style', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script('envios-woocommerce-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'envios_woocommerce_enqueue_scripts');

// Agregar menú en WordPress
add_action('admin_menu', 'envios_woocommerce_menu');
function envios_woocommerce_menu() {
    add_menu_page(
        'Envios WooCommerce',
        'Envios WooCommerce',
        'manage_options',
        'envios-woocommerce',
        'envios_woocommerce_admin_page',
        'dashicons-admin-site'
    );
}

// Página de administración del plugin
function envios_woocommerce_admin_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de Envios WooCommerce</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('envios-woocommerce');
            do_settings_sections('envios-woocommerce');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar configuraciones
add_action('admin_init', 'envios_woocommerce_settings');
function envios_woocommerce_settings() {
    add_settings_section('envios_section', 'Configuración de Envios', null, 'envios-woocommerce');
    
    $provincias = array('Álava', 'Albacete', 'Alicante', 'Almería', 'Asturias', 'Ávila', 'Badajoz', 'Barcelona', 'Burgos', 'Cáceres', 'Cádiz', 'Cantabria', 'Castellón', 'Ciudad Real', 'Córdoba', 'La Coruña', 'Cuenca', 'Gerona', 'Granada', 'Guadalajara', 'Guipúzcoa', 'Huelva', 'Huesca', 'Islas Baleares', 'Jaén', 'León', 'Lérida', 'Lugo', 'Madrid', 'Málaga', 'Murcia', 'Navarra', 'Orense', 'Palencia', 'Las Palmas', 'Pontevedra', 'La Rioja', 'Salamanca', 'Segovia', 'Sevilla', 'Soria', 'Tarragona', 'Santa Cruz de Tenerife', 'Teruel', 'Toledo', 'Valencia', 'Valladolid', 'Vizcaya', 'Zamora', 'Zaragoza'); // Lista de provincias
    
    foreach ($provincias as $provincia) {
        add_settings_field(
            'envio_' . strtolower(str_replace(' ', '_', $provincia)),
            'Precio de envío para ' . $provincia,
            'envios_woocommerce_setting_callback',
            'envios-woocommerce',
            'envios_section',
            array('provincia' => $provincia)
        );
        register_setting('envios-woocommerce', 'envio_' . strtolower(str_replace(' ', '_', $provincia)));
    }
}

function envios_woocommerce_setting_callback($args) {
    echo '<input type="number" name="envio_' . strtolower(str_replace(' ', '_', $args['provincia'])) . '" value="' . get_option('envio_' . strtolower(str_replace(' ', '_', $args['provincia']))) . '" />';
}

// Mostrar selector de provincia en la página del producto
add_action('woocommerce_single_product_summary', 'mostrar_provincias_envio', 20);
function mostrar_provincias_envio() {
    echo '<div id="provincia-envio">
            <label for="provincia">Provincia:</label>
            <input type="text" id="provincia" name="provincia" list="provincias" placeholder="Selecciona una provincia" oninput="actualizarPrecioEnvio()">
            <datalist id="provincias">';
    
    $provincias = array('Álava', 'Albacete', 'Alicante', 'Almería', 'Asturias', 'Ávila', 'Badajoz', 'Barcelona', 'Burgos', 'Cáceres', 'Cádiz', 'Cantabria', 'Castellón', 'Ciudad Real', 'Córdoba', 'La Coruña', 'Cuenca', 'Gerona', 'Granada', 'Guadalajara', 'Guipúzcoa', 'Huelva', 'Huesca', 'Islas Baleares', 'Jaén', 'León', 'Lérida', 'Lugo', 'Madrid', 'Málaga', 'Murcia', 'Navarra', 'Orense', 'Palencia', 'Las Palmas', 'Pontevedra', 'La Rioja', 'Salamanca', 'Segovia', 'Sevilla', 'Soria', 'Tarragona', 'Santa Cruz de Tenerife', 'Teruel', 'Toledo', 'Valencia', 'Valladolid', 'Vizcaya', 'Zamora', 'Zaragoza');
    
    foreach ($provincias as $provincia) {
        echo '<option value="' . $provincia . '">';
    }

    echo '  </datalist>
          </div>';
}

// Guardar la provincia seleccionada en el carrito
function guardar_provincia_envio($cart_item_data, $product_id) {
    if (isset($_POST['provincia'])) {
        $cart_item_data['provincia'] = sanitize_text_field($_POST['provincia']);
        WC()->session->set('provincia_envio', $cart_item_data['provincia']);
    }
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'guardar_provincia_envio', 10, 2);

// Mostrar la provincia seleccionada en el carrito y checkout
function mostrar_provincia_envio_checkout($item_data, $cart_item) {
    if (isset($cart_item['provincia'])) {
        $item_data[] = array(
            'name' => __('Provincia de Envío', 'envios-woocommerce'),
            'value' => $cart_item['provincia'],
        );
    }
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'mostrar_provincia_envio_checkout', 10, 2);

// Agregar precio de envío al total del pedido
function agregar_precio_envio($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $provincia = WC()->session->get('provincia_envio');
    if ($provincia) {
        $precio_envio = get_option('envio_' . strtolower(str_replace(' ', '_', $provincia)), 0);
        $cart->add_fee(__('Envío (' . $provincia . ')', 'envios-woocommerce'), $precio_envio);
    }
}
add_action('woocommerce_cart_calculate_fees', 'agregar_precio_envio');
