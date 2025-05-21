<?php
function dz_shipping_add_settings_page() {
    add_submenu_page(
        'woocommerce',
        __('DZ Shipping Settings', 'dz-shipping'),
        __('DZ Shipping', 'dz-shipping'),
        'manage_options',
        'dz-shipping-settings',
        'dz_shipping_render_settings_page'
    );
}
add_action('admin_menu', 'dz_shipping_add_settings_page');

function dz_shipping_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('DZ Shipping Plugin Settings', 'dz-shipping'); ?></h1>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('dz_shipping_settings');
            do_settings_sections('dz-shipping-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function dz_shipping_register_settings() {
    register_setting('dz_shipping_settings', 'dz_shipping_options');
    
    add_settings_section(
        'dz_shipping_main',
        __('Main Settings', 'dz-shipping'),
        'dz_shipping_main_section_cb',
        'dz-shipping-settings'
    );
    
    add_settings_field(
        'base_shipping_cost',
        __('Base Shipping Cost (DA)', 'dz-shipping'),
        'dz_shipping_base_cost_cb',
        'dz-shipping-settings',
        'dz_shipping_main'
    );
    
    add_settings_field(
        'free_shipping_threshold',
        __('Free Shipping Threshold (DA)', 'dz-shipping'),
        'dz_shipping_free_threshold_cb',
        'dz-shipping-settings',
        'dz_shipping_main'
    );
    
    // Add more settings fields as needed
}
add_action('admin_init', 'dz_shipping_register_settings');

function dz_shipping_main_section_cb() {
    echo '<p>' . __('Configure the main settings for DZ Shipping plugin.', 'dz-shipping') . '</p>';
}

function dz_shipping_base_cost_cb() {
    $options = get_option('dz_shipping_options');
    echo '<input type="number" name="dz_shipping_options[base_cost]" value="' . esc_attr($options['base_cost'] ?? '4200') . '" class="regular-text" /> DA';
}

function dz_shipping_free_threshold_cb() {
    $options = get_option('dz_shipping_options');
    echo '<input type="number" name="dz_shipping_options[free_threshold]" value="' . esc_attr($options['free_threshold'] ?? '') . '" class="regular-text" /> DA';
    echo '<p class="description">' . __('Leave empty to disable free shipping', 'dz-shipping') . '</p>';
}