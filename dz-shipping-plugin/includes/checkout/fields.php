<?php
function dz_shipping_checkout_fields($fields) {
    // Billing fields
    $fields['billing']['billing_first_name']['label'] = __('الاسم - Nom', 'dz-shipping');
    $fields['billing']['billing_last_name']['label'] = __('القب - Prénom', 'dz-shipping');
    $fields['billing']['billing_phone']['label'] = __('رقم الهاتف - Téléphone', 'dz-shipping');
    $fields['billing']['billing_address_1']['label'] = __('العنوان - Adresse', 'dz-shipping');
    
    // Add custom note field
    $fields['order']['order_comments']['label'] = __('ملاحضة - Note', 'dz-shipping');
    
    // Add wilaya and commune fields
    $fields['shipping']['shipping_state'] = array(
        'label' => __('Wilaya - الولاية', 'dz-shipping'),
        'required' => true,
        'type' => 'select',
        'class' => array('form-row-wide', 'address-field'),
        'options' => dz_get_wilayas_options(),
        'priority' => 50,
    );
    
    $fields['shipping']['shipping_city'] = array(
        'label' => __('Commune - البلدية', 'dz-shipping'),
        'required' => true,
        'type' => 'select',
        'class' => array('form-row-wide', 'address-field'),
        'options' => array('' => __('Select Commune', 'dz-shipping')),
        'priority' => 60,
    );
    
    // Add shipping type field
    $fields['shipping']['shipping_type'] = array(
        'label' => __('Livraison - التوصيل', 'dz-shipping'),
        'required' => true,
        'type' => 'radio',
        'class' => array('form-row-wide', 'shipping-type'),
        'options' => array(
            'home' => __('À la maison - إلى المنزل', 'dz-shipping'),
            'office' => __('Au bureau - إلى المكتب', 'dz-shipping'),
        ),
        'default' => 'home',
        'priority' => 70,
    );
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'dz_shipping_checkout_fields');

function dz_get_wilayas_options() {
    $wilayas = dz_get_wilayas_data();
    $options = array('' => __('Select Wilaya', 'dz-shipping'));
    
    foreach ($wilayas as $code => $name) {
        $options[$code] = $name;
    }
    
    return $options;
}

// AJAX handler for communes
function dz_get_communes_ajax() {
    if (!isset($_POST['wilaya_code'])) {
        wp_send_json_error();
    }
    
    $wilaya_code = sanitize_text_field($_POST['wilaya_code']);
    $communes = dz_get_communes_by_wilaya($wilaya_code);
    
    wp_send_json_success($communes);
}
add_action('wp_ajax_dz_get_communes', 'dz_get_communes_ajax');
add_action('wp_ajax_nopriv_dz_get_communes', 'dz_get_communes_ajax');