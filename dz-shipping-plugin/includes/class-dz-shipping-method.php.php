<?php
class DZ_Shipping {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }
    
    private function init() {
        // Include required files
        $this->includes();
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Initialize shipping method
        add_action('woocommerce_shipping_init', array($this, 'shipping_init'));
        add_filter('woocommerce_shipping_methods', array($this, 'add_shipping_method'));
    }
    
    private function includes() {
        // Admin files
        if (is_admin()) {
            require_once DZ_SHIPPING_PLUGIN_DIR . 'includes/admin/settings.php';
            require_once DZ_SHIPPING_PLUGIN_DIR . 'includes/admin/courier-apis.php';
        }
        
        // Checkout files
        require_once DZ_SHIPPING_PLUGIN_DIR . 'includes/checkout/fields.php';
        require_once DZ_SHIPPING_PLUGIN_DIR . 'includes/data/wilayas.php';
        require_once DZ_SHIPPING_PLUGIN_DIR . 'includes/data/communes.php';
    }
    
    public function enqueue_scripts() {
        if (is_checkout()) {
            wp_enqueue_style('dz-shipping', DZ_SHIPPING_PLUGIN_URL . 'assets/css/dz-shipping.css', array(), DZ_SHIPPING_VERSION);
            wp_enqueue_script('dz-shipping', DZ_SHIPPING_PLUGIN_URL . 'assets/js/dz-shipping.js', array('jquery'), DZ_SHIPPING_VERSION, true);
            
            wp_localize_script('dz-shipping', 'dzShippingParams', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'communes' => dz_get_communes_data(),
                'select_wilaya' => __('Select Wilaya', 'dz-shipping'),
                'select_commune' => __('Select Commune', 'dz-shipping'),
            ));
        }
    }
    
    public function admin_enqueue_scripts($hook) {
        if ($hook === 'woocommerce_page_wc-settings') {
            wp_enqueue_style('dz-shipping-admin', DZ_SHIPPING_PLUGIN_URL . 'assets/css/admin.css', array(), DZ_SHIPPING_VERSION);
            wp_enqueue_script('dz-shipping-admin', DZ_SHIPPING_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), DZ_SHIPPING_VERSION, true);
        }
    }
    
    public function shipping_init() {
        require_once DZ_SHIPPING_PLUGIN_DIR . 'includes/class-dz-shipping-method.php';
    }
    
    public function add_shipping_method($methods) {
        $methods['dz_shipping'] = 'DZ_Shipping_Method';
        return $methods;
    }
}