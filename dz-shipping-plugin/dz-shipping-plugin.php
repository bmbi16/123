<?php
class DZ_Shipping_Method extends WC_Shipping_Method {
    public function __construct($instance_id = 0) {
        parent::__construct($instance_id);
        
        $this->id = 'dz_shipping';
        $this->method_title = __('Algerian Shipping', 'dz-shipping');
        $this->method_description = __('Shipping method for Algerian wilayas and communes', 'dz-shipping');
        
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
        );
        
        $this->init();
    }
    
    private function init() {
        $this->init_form_fields();
        $this->init_settings();
        
        $this->title = $this->get_option('title');
        $this->tax_status = $this->get_option('tax_status');
        $this->cost = $this->get_option('cost');
        $this->free_shipping_threshold = $this->get_option('free_shipping_threshold');
        
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }
    
    public function init_form_fields() {
        $this->form_fields = array(
            'title' => array(
                'title' => __('Title', 'dz-shipping'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'dz-shipping'),
                'default' => __('Algerian Shipping', 'dz-shipping'),
                'desc_tip' => true,
            ),
            'tax_status' => array(
                'title' => __('Tax Status', 'dz-shipping'),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'default' => 'taxable',
                'options' => array(
                    'taxable' => __('Taxable', 'dz-shipping'),
                    'none' => __('None', 'dz-shipping'),
                ),
            ),
            'cost' => array(
                'title' => __('Base Cost', 'dz-shipping'),
                'type' => 'text',
                'description' => __('Base shipping cost (will be adjusted based on wilaya)', 'dz-shipping'),
                'default' => '4200',
                'desc_tip' => true,
            ),
            'free_shipping_threshold' => array(
                'title' => __('Free Shipping Threshold', 'dz-shipping'),
                'type' => 'text',
                'description' => __('Minimum order amount for free shipping (leave empty to disable)', 'dz-shipping'),
                'default' => '',
                'desc_tip' => true,
            ),
        );
    }
    
    public function calculate_shipping($package = array()) {
        $cost = floatval($this->cost);
        $wilaya_cost = 0;
        
        // Get selected wilaya from package
        if (isset($package['destination']['state']) && !empty($package['destination']['state'])) {
            $wilaya_code = $package['destination']['state'];
            $wilaya_cost = $this->get_wilaya_cost($wilaya_code);
        }
        
        // Check for free shipping threshold
        $cart_total = WC()->cart->get_subtotal();
        if (!empty($this->free_shipping_threshold) && $cart_total >= floatval($this->free_shipping_threshold)) {
            $cost = 0;
        } else {
            $cost += $wilaya_cost;
        }
        
        $rate = array(
            'id' => $this->id,
            'label' => $this->title,
            'cost' => $cost,
            'package' => $package,
        );
        
        $this->add_rate($rate);
    }
    
    private function get_wilaya_cost($wilaya_code) {
        // Implement wilaya-specific costs here
        // You can load this from a database table or a settings page
        return 0; // Default no additional cost
    }
}