<?php
if (!class_exists('KSA_Wsa_WC_Settings_Tab')) :

class KSA_Wsa_WC_Settings_Tab extends \WC_Settings_Page {

    public function __construct() {
        $this->id    = 'ksa_wsa';
        $this->label = __( 'Discount percentage', 'woocommerce' );
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 99 );
        // Add new section to the page
        add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
        // Add settings
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        // Process/save the settings
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    public function get_settings() {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'Discount settings'),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_demo_section_title'
            ),
            array(
                'title'             => __( 'Discount percentage' ),
                'desc'              => __( 'This sets the discount percentage in the displayed price'),
                'id'                => 'woocommerce_discount_percentage_num_decimals',
                'css'               => 'width:100px;',
                'default'           => '0',
                'type'              => 'number',
                'custom_attributes' => array(
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                ),
            ),
            array(
                'title'             => __( 'Discount percentage when out' ),
                'desc'              => __( 'This sets the discount percentage in the displayed price when out from site'),
                'id'                => 'woocommerce_discount_percentage_outsite_num_decimals',
                'css'               => 'width:100px;',
                'default'           => '0',
                'type'              => 'number',
                'custom_attributes' => array(
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 0.5,
                ),
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_demo_section_end'
            )
        );

        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
    }

}

return new KSA_Wsa_WC_Settings_Tab();

endif;