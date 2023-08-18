<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin {
	const afrsm_shipping_post_type = 'wc_afrsm';
	const afrsm_zone_post_type     = 'wc_afrsm_zone';
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	public $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->afrsm_pro_load_dependencies();
			
	}
	
	/**
	 * List of location specific conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */ 
	public function afrsm_location_specific_action() {
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$list_cnd_comm = array(
					'country'  => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'state'    => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'city'     => esc_html__( 'City', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'postcode' => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'zone'     => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			} else {
				$list_cnd_comm = array(
					'country'  => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'state'    => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'city_in_pro' => esc_html__( 'City ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'postcode' => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'zone'     => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			}
		} else {
			$list_cnd_comm = array(
				'country'  => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'state'    => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'city_in_pro' => esc_html__( 'City ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'postcode' => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'zone'     => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		return apply_filters( 'afrsm_location_specific_ft', $list_cnd_comm );
	}
	/**
	 * List of Product specific conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_product_specific_action() {
		$list_cnd_comm = array(
			'product'  => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'category' => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'tag'      => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
		);
		$list_cnd      = array();
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$list_cnd = array(
					'variableproduct' => esc_html__( 'Cart contains variable product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'sku'             => esc_html__( 'Cart contains SKU\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'product_qty'     => esc_html__( 'Cart contains product\'s quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			} else {
                $list_cnd = array(
                    'variableproduct_in_pro' => esc_html__( 'Cart contains variable product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                    'sku_in_pro'             => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                    'product_qty_in_pro'     => esc_html__( 'Cart contains product\'s quantity ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                );
            }
		} else {
			$list_cnd = array(
                'variableproduct_in_pro' => esc_html__( 'Cart contains variable product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'sku_in_pro'             => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'product_qty_in_pro'     => esc_html__( 'Cart contains product\'s quantity ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            );
		}
		$loca_arr = array_merge( $list_cnd_comm, $list_cnd );
		return apply_filters( 'afrsm_product_specific_ft', $loca_arr );
	}
	/**
	 * List of Attribute specific conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_attribute_specific_action() {
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$loca_arr             = array();
		foreach ( $attribute_taxonomies as $attribute ) {
			$att_label             = $attribute->attribute_label;
			$att_name              = wc_attribute_taxonomy_name( $attribute->attribute_name );
            if ( afrsfw_fs()->is__premium_only() ) {
                if( afrsfw_fs()->can_use_premium_code() ) {
                    $loca_arr[ $att_name ] = $att_label;
                } else {
                    $loca_arr[ $att_name.'_in_pro' ] = $att_label;
                }
            } else {
                $loca_arr[ $att_name.'_in_pro' ] = $att_label;
            }
		};
		return apply_filters( 'afrsm_attribute_specific_ft', $loca_arr );
	}
	/**
	 * List of User specific conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_user_specific_action() {
		$list_cnd_comm = array(
			'user' => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
		);
		$list_cnd      = array();
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$list_cnd = array(
					'user_role' => esc_html__( 'User Role', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			} else {
                $list_cnd = array(
                    'user_role_in_pro' => esc_html__( 'User Role ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                );
            }
		} else {
			$list_cnd = array(
                'user_role_in_pro' => esc_html__( 'User Role ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            );
		}
		$loca_arr = array_merge( $list_cnd_comm, $list_cnd );
		return apply_filters( 'afrsm_user_specific_ft', $loca_arr );
	}
    /**
	 * List of Order History conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_order_history_action() {
		$list_cnd      = array();
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$list_cnd = array(
					'last_spent_order' => esc_html__( 'Last order spent', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			} else {
                $list_cnd = array(
                    'last_spent_order_in_pro' => esc_html__( 'Last order spent ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                );
            }
		} else {
			$list_cnd = array(
                'last_spent_order_in_pro' => esc_html__( 'Last order spent ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            );
		}
		return apply_filters( 'afrsm_order_history_ft', $list_cnd );
	}
	/**
	 * List of Cart specific conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_cart_specific_action() {
		$list_cnd_comm = array(
			'cart_total' => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'quantity'   => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'width'      => esc_html__( 'Width', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'height'     => esc_html__( 'Height', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'length'     => esc_html__( 'Length', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'volume'     => esc_html__( 'Volume', 'advanced-flat-rate-shipping-for-woocommerce' ),
		);
		$list_cnd      = array();
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$list_cnd = array(
					'cart_totalafter' 		=> esc_html__( 'Cart Subtotal (After Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_productspecific' 	=> esc_html__( 'Cart Subtotal (Product Specific)', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'weight'          		=> esc_html__( 'Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'coupon'          		=> esc_html__( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'shipping_class'  		=> esc_html__( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			} else {
				$list_cnd = array(
					'cart_totalafter_in_pro' 		=> esc_html__( 'Cart Subtotal (After Discount)  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'cart_productspecific_in_pro' 	=> esc_html__( 'Cart Subtotal (Product Specific)  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'weight_in_pro'          		=> esc_html__( 'Weight  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'coupon_in_pro'          		=> esc_html__( 'Coupon  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'shipping_class_in_pro'  		=> esc_html__( 'Shipping Class  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			}
		} else {
			$list_cnd = array(
				'cart_totalafter_in_pro' 		=> esc_html__( 'Cart Subtotal (After Discount)  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'cart_productspecific_in_pro' 	=> esc_html__( 'Cart Subtotal (Product Specific)  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'weight_in_pro'          		=> esc_html__( 'Weight  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'coupon_in_pro'          		=> esc_html__( 'Coupon  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'shipping_class_in_pro'  		=> esc_html__( 'Shipping Class  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		$loca_arr = array_merge( $list_cnd_comm, $list_cnd );
		return apply_filters( 'afrsm_cart_specific_ft', $loca_arr );
	}
	/**
	 * List of Checkout specific conditions.
	 *
	 * @return array $loca_arr
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_checkout_specific_action() {
		$loca_arr = array();
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$loca_arr = array(
					'payment_method' => esc_html__( 'Payment Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			} else {
                $loca_arr = array(
                    'payment_method_in_pro' => esc_html__( 'Payment Method  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                );
            }
		} else {
			$loca_arr = array(
				'payment_method_in_pro' => esc_html__( 'Payment Method  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		return apply_filters( 'afrsm_checkout_specific_ft', $loca_arr );
	}
	/**
	 * List of conditions
	 *
	 * @return array $final_data
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_conditions_list_action() {
		$final_data = array(
			'Location Specific'  => $this->afrsm_location_specific_action(),
			'Product Specific'   => $this->afrsm_product_specific_action(),
			'Attribute Specific' => $this->afrsm_attribute_specific_action(),
			'User Specific'      => $this->afrsm_user_specific_action(),
			'Order History'      => $this->afrsm_order_history_action(),
			'Cart Specific'      => $this->afrsm_cart_specific_action(),
			'Checkout Specific'  => $this->afrsm_checkout_specific_action(),
		);
		return apply_filters( 'afrsm_conditions_list_ft', $final_data );
	}
	/**
	 * List of Operator
	 *
	 * @param string $fees_conditions Check which condition is applying.
	 *
	 * @return array $final_data
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_operator_list_action( $fees_conditions ) {
		$cart_op_arr = array();
		$prd_op_arr  = array(
			'is_equal_to' => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'not_in'      => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
		);
		if( 'product' === $fees_conditions || 
			'category' === $fees_conditions || 
			'tag' === $fees_conditions ||
			'variableproduct' === $fees_conditions ||
			'sku' === $fees_conditions ||
            'shipping_class' === $fees_conditions
		) {
			$cart_op_arr = array(
				'only_equal_to'    => esc_html__( 'Only Equal to ( == )', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		if ( 'product_qty' === $fees_conditions ||
		     'cart_total' === $fees_conditions ||
		     'cart_totalafter' === $fees_conditions ||
			 'cart_productspecific' === $fees_conditions ||
		     'quantity' === $fees_conditions ||
		     'width' === $fees_conditions ||
		     'height' === $fees_conditions ||
		     'length' === $fees_conditions ||
		     'volume' === $fees_conditions ||
		     'weight' === $fees_conditions ||
             'last_spent_order' === $fees_conditions
		) {
			$cart_op_arr = array(
				'less_equal_to'    => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'less_then'        => esc_html__( 'Less than ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'greater_equal_to' => esc_html__( 'Greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'greater_then'     => esc_html__( 'Greater than ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		$final_data = array_merge( $prd_op_arr, $cart_op_arr );
		return apply_filters( 'afrsm_operator_list_crt_ft', $final_data );
	}
	/**
	 * List of advanced tab section.
	 *
	 * @return array $tab_array
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_advanced_tab_list_action() {
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$tab_array = array(
					'tab-1'     => esc_html__( 'Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-2'     => esc_html__( 'Cost on Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-3'     => esc_html__( 'Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-4'     => esc_html__( 'Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-5'     => esc_html__( 'Cost on Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-6'     => esc_html__( 'Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-7'     => esc_html__( 'Cost on Tag', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-8'     => esc_html__( 'Cost on Tag Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-9'     => esc_html__( 'Cost on Tag Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-10'    => esc_html__( 'Cost on Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-11'    => esc_html__( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-12'    => esc_html__( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-13'    => esc_html__( 'Cost on Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-14'    => esc_html__( 'Cost on Shipping Class Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-15'    => esc_html__( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-16'    => esc_html__( 'Cost on Product Attribute', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			}else{
				$tab_array = array(
					'tab-11'  => esc_html__( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
					'tab-12'  => esc_html__( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
				);
			}
		}else{
			$tab_array = array(
				'tab-11'  => esc_html__( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'tab-12'  => esc_html__( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
			);
		}
		return apply_filters( 'afrsm_advanced_tab_list_ft', $tab_array );
	}
	/**
	 * List of apply per qty section.
	 *
	 * @return array $afrsm_apq_array
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_apq_type_action() {
		$afrsm_apq_array = array(
			'qty_cart_based'    => esc_html__( 'Cart Based', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'qty_product_based' => esc_html__( 'Product Based', 'advanced-flat-rate-shipping-for-woocommerce' ),
		);
		return apply_filters( 'afrsm_apq_action_ft', $afrsm_apq_array );
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook display current page name
	 *
	 * @since    1.0.0
	 *
	 */
	public function afrsm_pro_enqueue_styles( $hook ) {
        
		if ( false !== strpos( $hook, '_page_afrsm' ) ) {
			wp_enqueue_style( $this->plugin_name . 'select2-min', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), 'all' );
			wp_enqueue_style( $this->plugin_name . '-jquery-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-timepicker-min-css', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css', $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . 'main-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), 'all' );
			wp_enqueue_style( $this->plugin_name . 'media-css', plugin_dir_url( __FILE__ ) . 'css/media.css', array(), 'all' );
            wp_enqueue_style( $this->plugin_name . 'plugin-new-style', plugin_dir_url( __FILE__ ) . 'css/plugin-new-style.css', array(), 'all' );
            wp_enqueue_style( $this->plugin_name . 'plugin-addon-style', plugin_dir_url( __FILE__ ) . 'css/afrsm-addon-style.css', array(), 'all' );
            wp_enqueue_style( $this->plugin_name . 'plugin-license-style', plugin_dir_url( __FILE__ ) . 'css/afrsm-license-style.css', array(), 'all' );
            if ( afrsfw_fs()->is__premium_only() ){
                if( afrsfw_fs()->can_use_premium_code() ) {
                } else {
                    wp_enqueue_style( $this->plugin_name . 'upgrade-dashboard-style', plugin_dir_url( __FILE__ ) . 'css/upgrade-dashboard.css', array(), 'all' );
                }
            } else{
                wp_enqueue_style( $this->plugin_name . 'upgrade-dashboard-style', plugin_dir_url( __FILE__ ) . 'css/upgrade-dashboard.css', array(), 'all' );
            }
            wp_enqueue_style( $this->plugin_name . 'plugin-setup-wizard', plugin_dir_url( __FILE__ ) . 'css/afrsm-plugin-setup-wizard.css', array(), 'all' );
		}
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $hook display current page name
	 *
	 * @since    1.0.0
	 *
	 */
	public function afrsm_pro_enqueue_scripts( $hook ) {
		global $wp;
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-tiptip' );
        wp_enqueue_script( 'jquery-blockui' );
        add_thickbox();
		if ( false !== strpos( $hook, 'page_afrsm' ) ) {
			wp_enqueue_script( $this->plugin_name . '-select2-full-min', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array(
				'jquery',
				'jquery-ui-datepicker',
			), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-tablesorter-js', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-timepicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( $this->plugin_name . '-help-scout-beacon-js', plugin_dir_url( __FILE__ ) . 'js/help-scout-beacon.js', array( 'jquery' ), $this->version, false );

			$current_url = home_url( add_query_arg( $wp->query_vars, $wp->request ) );
			if ( afrsfw_fs()->is__premium_only() ) {
				if ( afrsfw_fs()->can_use_premium_code() ) {
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin__premium_only.js', array(
						'jquery',
						'jquery-ui-dialog',
						'jquery-ui-accordion',
						'jquery-ui-sortable',
						'select2',
					), $this->version, true );
					wp_localize_script( $this->plugin_name, 'coditional_vars', array(
							'first_path'					   => admin_url( 'admin.php?page=afrsm-pro-list' ),
							'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
							'ajax_icon'                        => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
							'plugin_url'                       => plugin_dir_url( __FILE__ ),
							'dsm_ajax_nonce'                   => wp_create_nonce( 'dsm_nonce' ),
                            'dpb_api_url'                      => AFRSM_PROMOTIONAL_BANNER_API_URL,
							'country'                          => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'state'                            => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'city'                             => esc_html__( 'City', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'postcode'                         => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'zone'                             => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product'            => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_variable_product'   => esc_html__( 'Cart contains variable product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_category_product'   => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_tag_product'        => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_sku_product'        => esc_html__( 'Cart contains SKU\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product_qty'        => esc_html__( 'Cart contains product\'s quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user'                             => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user_role'                        => esc_html__( 'User Role', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'last_spent_order'                 => esc_html__( 'Last order spent', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_before_discount'    => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_after_discount'     => esc_html__( 'Cart Subtotal (After Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_productspecific'	   => esc_html__( 'Cart Subtotal (Product Specific)', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'quantity'                         => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'width'                            => esc_html__( 'Width', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'height'                           => esc_html__( 'Height', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'length'                           => esc_html__( 'Length', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'volume'                           => esc_html__( 'Volume', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'weight'                           => esc_html__( 'Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'coupon'                           => esc_html__( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'shipping_class'                   => esc_html__( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_quantity'                     => esc_html__( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_quantity'                     => esc_html__( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'amount'                           => esc_html__( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'equal_to'                         => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'not_equal_to'                     => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'only_equal_to'                    => esc_html__( 'Only Equal to ( == )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'less_or_equal_to'                 => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'less_than'                        => esc_html__( 'Less then ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'greater_or_equal_to'              => esc_html__( 'greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'greater_than'                     => esc_html__( 'greater then ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length1'               => esc_html__( 'Please enter 3 or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_country'                   => esc_html__( 'Select a Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_state'                     => esc_html__( 'Select a State', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_city'					   => esc_html__( "City 1\nCity 2", 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_postcode'				   => esc_html__( "Postcode 1\nPostcode 2", 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_zone'					   => esc_html__( 'Select a zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_product'                   => esc_html__( 'Select a Product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_category'                  => esc_html__( 'Select a Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_tag'                       => esc_html__( 'Select a Tag', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_sku'                       => esc_html__( 'Select a SKU', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_product_attribute'         => esc_html__( 'Select Product Attribute', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_user'					   => esc_html__( 'Select a user', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_user_role'			       => esc_html__( 'Select a user role', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_coupon'					   => esc_html__( 'Select a coupon', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_float_number'			   => esc_html__( '0.00', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_integer_number'			   => esc_html__( '10', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_shipping_class'            => esc_html__( 'Select a Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_payment'				   => esc_html__( 'Select a payment gateway', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'delete'                           => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'clone'                            => esc_html__( 'Clone', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_qty'                         => esc_html__( 'Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_weight'                      => esc_html__( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_weight'                       => esc_html__( 'Min Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_weight'                       => esc_html__( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal'                    => esc_html__( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_subtotal'                     => esc_html__( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_subtotal'                     => esc_html__( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length2'               => esc_html__( 'Please enter', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length3'               => esc_html__( 'or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'location_specific'                => esc_html__( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'product_specific'                 => esc_html__( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user_specific'                    => esc_html__( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'order_history'                    => esc_html__( 'Order History', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_specific'                    => esc_html__( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'attribute_specific'               => esc_html__( 'Attribute Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'checkout_specific'                => esc_html__( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'payment_method'                   => esc_html__( 'Payment Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_qty_error'                => esc_html__( 'Max qty should greater then min qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_weight_error'             => esc_html__( 'Max weight should greater then min weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_subtotal_error'           => esc_html__( 'Max subtotal should greater then min subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg1'                     => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg2'                     => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg1'                     => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbol like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
							'warning_msg2'                     => esc_html__( 'Please disable Advance Pricing Rule if you dont need because you have not created rule there.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg3'                     => esc_html__( 'You need to select product specific option in Shipping Method Rules for product based option', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg4'                     => esc_html__( 'If you active Apply Per Quantity option then Advance Pricing Rule will be disable and not working.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg5'                     => esc_html__( 'Please fill some required field in advance pricing rule section', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'attribute_list'                   => wp_json_encode( $this->afrsm_pro_attribute_list__premium_only() ),
							'note'                             => esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'click_here'                       => esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'weight_msg'                       => esc_html__( 'Please make sure that when you add rules in Advanced Pricing > Cost per weight Section, then this rule should be satisfy,
																		otherwise it may be not apply proper shipping charges. For more detail please view
                                                                        our documentation at ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product_msg'        => esc_html__( 'Please make sure that when you add rules in Advanced Pricing > Cost per product Section, then this rule should be satisfy,
							                                            otherwise it may be not apply proper shipping charges. For more detail please view
                                                                        our documentation at ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_category_msg'       => esc_html__( 'Please make sure that when you add rules in Advanced Pricing > Cost per category Section , then this rule should be satisfy,
							                                            otherwise it may be not apply proper shipping charges. For more detail please view
                                                                        our documentation at ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_products_qty_msg'   => esc_html__( 'This rule will only work if you have selected any one Product Specific option. ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_after_discount_msg' => esc_html__( 'After discount. In this case, subtotal amount is $25 – $10(Discount price) = $15, without discount $25 ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'payment_method_msg'               => esc_html__( 'This rule will work for all payment method in WooCommerce setting ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'current_url'                      => $current_url,
							'doc_url'                          => "https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce",
							'list_page_url'                    => add_query_arg( array( 'page' => 'afrsm-start-page' ), admin_url( 'admin.php' ) ),
                            'payment_page_url'                 => 'https://docs.thedotstore.com/article/406-how-to-add-shipping-method-based-on-the-specific-payment-gateway',
							'product_qty_page_url'             => "https://docs.thedotstore.com/article/104-product-specific-shipping-rule/",
							'cart_contains_city_msg'   		   => esc_html__( 'Make sure enter each city name in one line.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'city_url'             			   => "https://docs.thedotstore.com/article/358-how-to-add-city-based-shipping-rules",
							'tooltip_char_limit'			   => 100,
                            'select2_per_product_ajax'         => 5,
                            'select2_product_placeholder'      => esc_html__( 'Select products here', 'advanced-flat-rate-shipping-for-woocommerce' ),
						)
					);
				} else {
					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin.js', array(
						'jquery',
						'jquery-ui-dialog',
						'jquery-ui-accordion',
						'jquery-ui-sortable',
						'select2',
					), $this->version, false );
					wp_localize_script( $this->plugin_name, 'coditional_vars', array(
							'first_path'					 => admin_url( 'admin.php?page=afrsm-pro-list' ),
							'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
							'ajax_icon'                      => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
							'plugin_url'                     => plugin_dir_url( __FILE__ ),
							'dsm_ajax_nonce'                 => wp_create_nonce( 'dsm_nonce' ),
                            'dpb_api_url'                    => AFRSM_PROMOTIONAL_BANNER_API_URL,
							'country'                        => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'state'                          => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'city'                           => esc_html__( 'City ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce'),
							'postcode'                       => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'zone'                           => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product'          => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_variable_product' => esc_html__( 'Cart contains variable product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_category_product' => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_tag_product'      => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_sku_product'      => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product_qty'      => esc_html__( 'Cart contains product\'s quantity ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user'                           => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user_role'                      => esc_html__( 'User Role ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'last_spent_order'               => esc_html__( 'Last order spent ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_before_discount'  => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_after_discount'   => esc_html__( 'Cart Subtotal (After Discount) ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_productspecific'	 => esc_html__( 'Cart Subtotal (Product Specific) ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'quantity'                       => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'width'                          => esc_html__( 'Width', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'height'                         => esc_html__( 'Height', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'length'                         => esc_html__( 'Length', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'volume'                         => esc_html__( 'Volume', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'weight'                         => esc_html__( 'Weight ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'coupon'                         => esc_html__( 'Coupon ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'shipping_class'                 => esc_html__( 'Shipping Class ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'payment_method'                 => esc_html__( 'Payment Method ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'equal_to'                       => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'not_equal_to'                   => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'less_or_equal_to'               => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'less_than'                      => esc_html__( 'Less then ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'greater_or_equal_to'            => esc_html__( 'greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'greater_than'                   => esc_html__( 'greater then ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length1'             => esc_html__( 'Please enter 3 or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_country'                 => esc_html__( 'Select a Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_state'                   => esc_html__( 'Select a State', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_postcode'				 => esc_html__( "Postcode 1\nPostcode 2", 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_zone'					 => esc_html__( 'Select a zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_product'                 => esc_html__( 'Select Product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_category'                => esc_html__( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_tag'                     => esc_html__( 'Select Tag', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_user'					 => esc_html__( 'Select a user', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_float_number'			 => esc_html__( '0.00', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_integer_number'			 => esc_html__( '10', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'delete'                         => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length2'             => esc_html__( 'Please enter', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length3'             => esc_html__( 'or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'location_specific'              => esc_html__( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'product_specific'               => esc_html__( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user_specific'                  => esc_html__( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'order_history'                  => esc_html__( 'Order History', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_specific'                  => esc_html__( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'checkout_specific'              => esc_html__( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg1'                   => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg2'                   => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg1'                   => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
							'note'                           => esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'click_here'                     => esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'current_url'                    => $current_url,
							'doc_url'                        => "https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce",
							'list_page_url'                  => add_query_arg( array( 'page' => 'afrsm-start-page' ), admin_url( 'admin.php' ) ),
							'product_qty_page_url'           => "https://docs.thedotstore.com/article/104-product-specific-shipping-rule/",
							'cart_weight'                      => esc_html__( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_weight'                       => esc_html__( 'Min Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_weight'                       => esc_html__( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal'                    => esc_html__( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_subtotal'                     => esc_html__( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_subtotal'                     => esc_html__( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_qty_error'                => esc_html__( 'Max qty should greater then min qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_weight_error'             => esc_html__( 'Max weight should greater then min weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_subtotal_error'           => esc_html__( 'Max subtotal should greater then min subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg1'                     => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg2'                     => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg1'                     => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
							'warning_msg2'                     => esc_html__( 'Please disable Advance Pricing Rule if you dont need because you have not created rule there.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg3'                     => esc_html__( 'You need to select product specific option in Shipping Method Rules for product based option', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg4'                     => esc_html__( 'If you active Apply Per Quantity option then Advance Pricing Rule will be disable and not working.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg5'                     => esc_html__( 'Please fill some required field in advance pricing rule section', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'amount'                           => esc_html__( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'tooltip_char_limit'			   => 100,
						)
					);
				}
			} else {
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin.js', array(
					'jquery',
					'jquery-ui-dialog',
					'jquery-ui-accordion',
					'jquery-ui-sortable',
					'select2',
				), $this->version, false );
				wp_localize_script( $this->plugin_name, 'coditional_vars', array(
							'first_path'					 => admin_url( 'admin.php?page=afrsm-pro-list' ),
							'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
							'ajax_icon'                      => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
							'plugin_url'                     => plugin_dir_url( __FILE__ ),
							'dsm_ajax_nonce'                 => wp_create_nonce( 'dsm_nonce' ),
                            'dpb_api_url'                    => AFRSM_PROMOTIONAL_BANNER_API_URL,
							'country'                        => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'state'                          => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'city'                           => esc_html__( 'City ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce'),
							'postcode'                       => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'zone'                           => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product'          => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_variable_product' => esc_html__( 'Cart contains variable product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_category_product' => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_tag_product'      => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_sku_product'      => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_contains_product_qty'      => esc_html__( 'Cart contains product\'s quantity ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user'                           => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user_role'                      => esc_html__( 'User Role ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'last_spent_order'               => esc_html__( 'Last order spent ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_before_discount'  => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_after_discount'   => esc_html__( 'Cart Subtotal (After Discount) ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal_productspecific'	 => esc_html__( 'Cart Subtotal (Product Specific) ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'quantity'                       => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'width'                          => esc_html__( 'Width', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'height'                         => esc_html__( 'Height', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'length'                         => esc_html__( 'Length', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'volume'                         => esc_html__( 'Volume', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'weight'                         => esc_html__( 'Weight ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'coupon'                         => esc_html__( 'Coupon ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'shipping_class'                 => esc_html__( 'Shipping Class ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'payment_method'                 => esc_html__( 'Payment Method ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'equal_to'                       => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'not_equal_to'                   => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'less_or_equal_to'               => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'less_than'                      => esc_html__( 'Less then ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'greater_or_equal_to'            => esc_html__( 'greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'greater_than'                   => esc_html__( 'greater then ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length1'             => esc_html__( 'Please enter 3 or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_country'                 => esc_html__( 'Select a Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_state'                   => esc_html__( 'Select a State', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_postcode'				 => esc_html__( "Postcode 1\nPostcode 2", 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_zone'					 => esc_html__( 'Select a zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_product'                 => esc_html__( 'Select Product', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_category'                => esc_html__( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'select_tag'                     => esc_html__( 'Select Tag', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_user'					 => esc_html__( 'Select a user', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_float_number'			 => esc_html__( '0.00', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'select_integer_number'			 => esc_html__( '10', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'delete'                         => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length2'             => esc_html__( 'Please enter', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'validation_length3'             => esc_html__( 'or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'location_specific'              => esc_html__( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'product_specific'               => esc_html__( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'user_specific'                  => esc_html__( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                            'order_history'                  => esc_html__( 'Order History', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_specific'                  => esc_html__( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'checkout_specific'              => esc_html__( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg1'                   => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg2'                   => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg1'                   => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
							'note'                           => esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'click_here'                     => esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'current_url'                    => $current_url,
							'doc_url'                        => "https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce",
							'list_page_url'                  => add_query_arg( array( 'page' => 'afrsm-start-page' ), admin_url( 'admin.php' ) ),
							'product_qty_page_url'           => "https://docs.thedotstore.com/article/104-product-specific-shipping-rule/",
							'cart_weight'                      => esc_html__( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_weight'                       => esc_html__( 'Min Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_weight'                       => esc_html__( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'cart_subtotal'                    => esc_html__( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_subtotal'                     => esc_html__( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'max_subtotal'                     => esc_html__( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_qty_error'                => esc_html__( 'Max qty should greater then min qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_weight_error'             => esc_html__( 'Max weight should greater then min weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'min_max_subtotal_error'           => esc_html__( 'Max subtotal should greater then min subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg1'                     => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'success_msg2'                     => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg1'                     => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
							'warning_msg2'                     => esc_html__( 'Please disable Advance Pricing Rule if you dont need because you have not created rule there.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg3'                     => esc_html__( 'You need to select product specific option in Shipping Method Rules for product based option', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg4'                     => esc_html__( 'If you active Apply Per Quantity option then Advance Pricing Rule will be disable and not working.', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'warning_msg5'                     => esc_html__( 'Please fill some required field in advance pricing rule section', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'amount'                           => esc_html__( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ),
							'tooltip_char_limit'			   => 100,
					)
				);
			}

            //Wizard enqueue
            wp_enqueue_script( $this->plugin_name . '-wizard', plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-wizard.js', array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-sortable',
                'select2',
            ), $this->version, false );
            wp_localize_script( $this->plugin_name . '-wizard', 'afrsfw_wizard_conditional_vars', array(
                'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
                'setup_wizard_ajax_nonce'   => wp_create_nonce( 'afrsfw_wizard_nonce' )
                )
            );
		}
	}
	/**
	 * Load zone section
	 *
	 * @since    1.0.0
	 */
	private function afrsm_pro_load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-shipping-zone-page.php';
	}
	/*
     * Shipping method Pro Menu
     *
     * @since 3.0.0
     */
	public function afrsm_pro_dot_store_menu_shipping_method_pro() {
		global $GLOBALS;
		if ( empty( $GLOBALS['admin_page_hooks']['dots_store'] ) ) {
			add_menu_page( 'DotStore Plugins', __( 'DotStore Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ), 'null', 'dots_store', array(
				$this,
				'dot_store_menu_page',
			), 'dashicons-marker', 25 );
		}
        $afrsm_rule_list_hook = add_submenu_page( 'dots_store', AFRSM_PRO_PLUGIN_NAME, AFRSM_PRO_PLUGIN_NAME, 'manage_options', 'afrsm-pro-list', array(
            $this,
            'afrsm_pro_fee_list_page',
        ) );
        add_action( "load-$afrsm_rule_list_hook", array( $this, "afrsm_rule_screen_options" ) );
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
                //Pro condition
			} else {
                add_submenu_page( 'dots_store', 'Dashboard', 'Dashboard', 'manage_options', 'afrsm-pro-dashboard', array(
					$this,
					'afrsm_free_user_upgrade_page',
				) );
			}
		} else {
            add_submenu_page( 'dots_store', 'Dashboard', 'Dashboard', 'manage_options', 'afrsm-pro-dashboard', array(
                $this,
                'afrsm_free_user_upgrade_page',
            ) );
		}
		add_submenu_page( 'dots_store', 'Edit Shipping Method', 'Edit Shipping Method', 'manage_options', 'afrsm-pro-edit-shipping', array(
			$this,
			'afrsm_pro_edit_fee_page',
		) );
		add_submenu_page( 'dots_store', 'Manage Shipping Zones', 'Manage Shipping Zones', 'manage_options', 'afrsm-wc-shipping-zones', array(
			__CLASS__,
			'afrsm_pro_shipping_zone_page',
		) );
		add_submenu_page( 'dots_store', 'Import Export Shipping', 'Import Export Shipping', 'manage_options', 'afrsm-pro-import-export', array(
			$this,
			'afrsm_pro_import_export_fee',
		) );
		add_submenu_page( 'dots_store', 'Getting Started', 'Getting Started', 'manage_options', 'afrsm-pro-get-started', array(
			$this,
			'afrsm_pro_get_started_page',
		) );
		add_submenu_page( 'dots_store', 'Quick info', 'Quick info', 'manage_options', 'afrsm-pro-information', array(
			$this,
			'afrsm_pro_information_page',
		) );
        add_submenu_page( 'dots_store', 'General Settings', 'General Settings', 'manage_options', 'afrsm-page-general-settings', array(
			$this,
			'afrsm_general_settings_page',
		) );
        add_submenu_page( 'dots_store', 'Add-Ons', 'Add-Ons', 'manage_options', 'afrsm-page-add-ons', array(
			$this,
			'afrsm_add_on_page',
		) );
        //Remove footer WP version
        $get_page   = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$page       = !empty($get_page) ? sanitize_text_field($get_page) : '';
		if ( ! empty( $page ) && ( false !== strpos( $page, 'afrsm' ) ) ) {
			remove_filter( 'update_footer', 'core_update_footer' ); 
		}
	}
	/**
	 * Redirect to listing page from dotStore menu page
	 *
	 * @since    4.1.0
	 */
    public function dot_store_menu_page(){
		wp_redirect( admin_url( 'admin.php?page=afrsm-pro-list' ) );
		exit;
	}
	/**
	 * Shipping List Page
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_fee_list_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-list-page.php' );
		$afrsm_rule_lising_obj = new AFRSM_Rule_Listing_Page();
		$afrsm_rule_lising_obj->afrsm_sj_output();
	}
    /**
	 * Screen option for discount rule list
	 *
	 * @since    4.2.0
	 */
	public function afrsm_rule_screen_options() {
		$args = array(
			'label'   => esc_html__( 'List Per Page', 'advanced-flat-rate-shipping-for-woocommerce' ),
			'default' => 1,
			'option'  => 'afrsm_rule_per_page',
		);
		add_screen_option( 'per_page', $args );

        if ( ! class_exists( 'WC_Advanced_Flat_Rate_Shipping_Table' ) ) {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/list-tables/class-wc-flat-rate-rule-table.php';
        }
        $sagar = new WC_Advanced_Flat_Rate_Shipping_Table();
        $sagar->_column_headers = $sagar->get_column_info();    
	}
    /**
	 * Add screen option for per page
	 *
	 * @param bool   $status
	 * @param string $option
	 * @param int    $value
	 *
	 * @return int $value
	 * @since 4.2.0
	 *
	 */
	public function afrsm_set_screen_options( $status, $option, $value ) {
        
		$dpad_screens = array(
			'afrsm_rule_per_page',
		);
		if( 'afrsm_rule_per_page' === $option ){
			$value = !empty($value) && $value > 0 ? $value : get_option( 'afrsm_sm_count_per_page' );
		}
        
		if ( in_array( $option, $dpad_screens, true ) ) {
			return $value;
		}
		return $status;
	}
    /**
     * Specify the columns we wish to hide by default.
     *
     * @param array     $hidden Columns set to be hidden.
     * @param WP_Screen $screen Screen object.
     * @param bool      $use_defaults Whether to show the default columns.
     *
     * @return array
     * @since 4.2.0
     * 
     */
    public function afrsm_default_hidden_columns( $hidden, WP_Screen $screen  ) {
        
        if( !empty( $screen->id ) && 'dotstore-plugins_page_afrsm-pro-list' === $screen->id ){
            $hidden = array_merge( $hidden, array( 'date' ) );
        }
        
        return $hidden;
    }
	/**
	 * Shipping zone page
	 * @uses     AFRSM_Shipping_Zone class
	 * @uses     AFRSM_Shipping_Zone::output()
	 *
	 * @since    1.0.0
	 */
	public static function afrsm_pro_shipping_zone_page() {
		$shipping_zone_obj = new AFRSM_Shipping_Zone();
		$shipping_zone_obj->afrsm_pro_sz_output();
	}
	/**
	 * Quick guide page
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_get_started_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-get-started-page.php' );
	}
	/**
	 * Plugin information page
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_information_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-information-page.php' );
	}
    /**
	 * Plugin general settings page
	 *
	 * @since    1.0.0
	 */
    public function afrsm_general_settings_page() {
        require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-general-setting-page.php' );
    }
    /**
     * Plugin licenses page
     * 
     * @since   4.2.0
     */
    public function afrsm_licenses_page(){
        require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-licenses-page.php' );
    }
    /**
	 * Plugin add ons page
	 *
	 * @since    1.0.0
	 */
    public function afrsm_add_on_page() {
        require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-add-ons-page.php' );
    }
    /**
	 * Premium version info page
	 *
	 */
	public function afrsm_free_user_upgrade_page() {
		require_once( plugin_dir_path( __FILE__ ) . '/partials/afrsm-upgrade-dashboard.php' );
	}
	/**
	 * Import Export Setting page
	 *
	 */
	public function afrsm_pro_import_export_fee() {
		require_once( plugin_dir_path( __FILE__ ) . '/partials/afrsm-import-export-setting.php' );
	}
	/**
	 * Redirect to shipping list page
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_redirect_shipping_function() {
        $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
        $string_1 = sanitize_text_field( $get_section ); 
        $get_section = !empty($get_section) ? sanitize_text_field($get_section) : '';
		if ( ! empty( $get_section ) && 'advanced_flat_rate_shipping' === $get_section ) {
			wp_safe_redirect( add_query_arg( array( 'page' => 'afrsm-pro-list' ), admin_url( 'admin.php' ) ) );
			exit;
		}
	}
	/**
	 * Redirect to quick start guide after plugin activation
	 *
	 * @uses     afrsm_pro_register_post_type()
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_welcome_shipping_method_screen_do_activation_redirect() {
		$this->afrsm_pro_register_post_type();
		// if no activation redirect
		if ( ! get_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data' ) ) {
			return;
		}
		// Delete the redirect transient
		delete_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data' );
		// if activating from network, or bulk
		$activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( is_network_admin() || isset( $activate_multi ) ) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect( add_query_arg( array( 'page' => 'afrsm-pro-list' ), admin_url( 'admin.php' ) ) );
		exit;
	}
	/**
	 * Register post type
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_register_post_type() {
		register_post_type( self::afrsm_shipping_post_type, array(
			'labels' => array(
				'name'          => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
				'singular_name' => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
			),
		) );
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				register_post_type( self::afrsm_zone_post_type, array(
					'labels' => array(
						'name'          => __( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'singular_name' => __( 'Advance Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
					),
				) );
			}
		}
	}
	/**
	 * Remove submenu from admin screeen
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_remove_admin_submenus() {
		remove_submenu_page( 'dots_store', 'afrsm-pro-add-shipping' );
		remove_submenu_page( 'dots_store', 'afrsm-pro-edit-shipping' );
		remove_submenu_page( 'dots_store', 'afrsm-wc-shipping-zones' );
		remove_submenu_page( 'dots_store', 'afrsm-pro-import-export' );
		remove_submenu_page( 'dots_store', 'afrsm-pro-get-started' );
		remove_submenu_page( 'dots_store', 'afrsm-pro-information' );
		remove_submenu_page( 'dots_store', 'afrsm-page-general-settings' );
		remove_submenu_page( 'dots_store', 'afrsm-page-add-ons' );
		remove_submenu_page( 'dots_store', 'afrsm-pro-dashboard' );
        echo '<style>
            .toplevel_page_dots_store .dashicons-marker::after{content:"";border:3px solid;position:absolute;top:14px;left:14px;border-radius:50%;opacity: 1;}
            li.toplevel_page_dots_store:hover .dashicons-marker::after,li.toplevel_page_dots_store.current .dashicons-marker::after{opacity: 1;}
            @media screen and (min-width:961px){
                .toplevel_page_dots_store .dashicons-marker::after{left: 15px;}
            }
        </style>'; 
	}
	/**
	 * Match condition based on shipping list
	 *
	 * @param int          $sm_post_id
	 * @param array|object $package
	 *
	 * @return bool True if $final_condition_flag is 1, false otherwise. if $sm_status is off then also return false.
	 * @since    1.0.0
	 *
	 * @uses     afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses     afrsm_pro_get_woo_version_number()
	 * @uses     WC_Cart::get_cart()
	 * @uses     afrsm_pro_match_country_rules()
	 * @uses     afrsm_pro_match_state_rules()
	 * @uses     afrsm_pro_match_city_rules()
	 * @uses     afrsm_pro_match_postcode_rules()
	 * @uses     afrsm_pro_match_zone_rules()
	 * @uses     afrsm_pro_match_variable_products_rule__premium_only()
	 * @uses     afrsm_pro_match_simple_products_rule()
	 * @uses     afrsm_pro_match_category_rule()
	 * @uses     afrsm_pro_match_tag_rule()
	 * @uses     afrsm_pro_match_sku_rule__premium_only()
	 * @uses     afrsm_pro_match_user_rule()
	 * @uses     afrsm_pro_match_user_role_rule__premium_only()
	 * @uses     afrsm_pro_match_last_spent_order_rule__premium_only()
	 * @uses     afrsm_pro_match_coupon_rule__premium_only()
	 * @uses     afrsm_pro_match_cart_subtotal_before_discount_rule()
	 * @uses     afrsm_pro_match_cart_subtotal_after_discount_rule__premium_only()
	 * @uses	 afrsm_pro_match_cart_subtotal_specific_product_shipping_rule__premium_only()
	 * @uses     afrsm_pro_match_cart_total_cart_qty_rule()
	 * @uses     afrsm_pro_match_cart_total_width_rule()
	 * @uses     afrsm_pro_match_cart_total_height_rule()
     * @uses     afrsm_pro_match_cart_total_length_rule()
     * @uses     afrsm_pro_match_cart_total_volume_rule()
	 * @uses     afrsm_pro_match_cart_total_weight_rule__premium_only()
	 * @uses     afrsm_pro_match_shipping_class_rule__premium_only()
	 * @uses     afrsm_pro_match_attribute_rule__premium_only()
	 *
	 */
	public function afrsm_pro_condition_match_rules( $sm_post_id, $package = array() ) {
		
		if ( empty( $sm_post_id ) ) {
			return false;
		}

		global $sitepress;
		$default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
		if ( ! empty( $sitepress ) ) {
			$sm_post_id = apply_filters( 'wpml_object_id', $sm_post_id, 'wc_afrsm', true, $default_lang );
		} else {
			$sm_post_id = $sm_post_id;
		}

		$wc_curr_version              = $this->afrsm_pro_get_woo_version_number();
		$is_passed                    = array();
		$final_is_passed_general_rule = array();
		$new_is_passed                = array();
		$final_condition_flag         = array();
		$cart_array                   = !empty($package['contents']) ? $package['contents'] : $this->afrsm_pro_get_cart();
		$cart_product_ids_array       = $this->afrsm_pro_get_prd_var_id( $cart_array, $sitepress, $default_lang );
		$sm_status                    = get_post_status( $sm_post_id );
		$get_condition_array          = get_post_meta( $sm_post_id, 'sm_metabox', true );
		$general_rule_match           = 'all';
		$sm_select_log_in_user = get_post_meta( $sm_post_id, 'sm_select_log_in_user', true );
        $sm_first_order_for_user = get_post_meta( $sm_post_id, 'sm_select_first_order_for_user', true );
        if( !empty($sm_first_order_for_user) && 'yes' === $sm_first_order_for_user && is_user_logged_in() ){
            $current_user_id = get_current_user_id();
            $check_for_user = $this->afrsm_check_first_order_for_user( $current_user_id );
            if( !$check_for_user ){
                return false;
            }
        }
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$variation_cart_products_array = $this->afrsm_pro_get_var_name__premium_only( $cart_array );
				$sm_start_date                 = get_post_meta( $sm_post_id, 'sm_start_date', true );
				$sm_end_date                   = get_post_meta( $sm_post_id, 'sm_end_date', true );
				$sm_time_from                  = get_post_meta( $sm_post_id, 'sm_time_from', true );
				$sm_time_to                    = get_post_meta( $sm_post_id, 'sm_time_to', true );
				$sm_select_day_of_week         = get_post_meta( $sm_post_id, 'sm_select_day_of_week', true );
				$cost_rule_match               = get_post_meta( $sm_post_id, 'cost_rule_match', true );

				if ( ! empty( $cost_rule_match ) ) {
					if ( is_serialized( $cost_rule_match ) ) {
						$cost_rule_match = maybe_unserialize( $cost_rule_match );
					} else {
						$cost_rule_match = $cost_rule_match;
					}
					if ( array_key_exists( 'general_rule_match', $cost_rule_match ) ) {
						$general_rule_match = $cost_rule_match['general_rule_match'];
					} else {
						$general_rule_match = 'all';
					}
				} else {
					$general_rule_match = 'all';
				}
			} else {
                $general_rule_match    = 'all';
            }
		} else {
			$general_rule_match    = 'all';
		}
		if ( isset( $sm_status ) && 'off' === $sm_status ) {
			return false;
		}
		if ( ! empty( $get_condition_array ) || '' !== $get_condition_array || null !== $get_condition_array ) {
			$country_array    = array();
			$product_array    = array();
			$category_array   = array();
			$tag_array        = array();
			$user_array       = array();
			$cart_total_array = array();
			$quantity_array   = array();
            $width_array      = array();
            $height_array     = array();
            $length_array     = array();
            $volume_array     = array();
			$state_array      = array();
			$city_array       = array();
			$postcode_array   = array();
			$zone_array       = array();
			if ( afrsfw_fs()->is__premium_only() ) {
				if ( afrsfw_fs()->can_use_premium_code() ) {
					
					$variableproduct_array 		= array();
					$sku_array             		= array();
					$product_qty_array     		= array();
					$user_role_array       		= array();
					$last_spent_order_array 	= array();
					$cart_totalafter_array 		= array();
					$cart_productspecific_array = array();
					$weight_array          		= array();
					$coupon_array          		= array();
					$shipping_class_array  		= array();
					$payment_methods_array 		= array();
					$attribute_taxonomies  		= wc_get_attribute_taxonomies();
					$atta_name             		= array();
				}
			}
            
			foreach ( $get_condition_array as $key => $value ) {
				if ( array_search( 'country', $value, true ) ) {
					$country_array[ $key ] = $value;
				}
				if ( array_search( 'state', $value, true ) ) {
					$state_array[ $key ] = $value;
				}
				if ( array_search( 'city', $value, true ) ) {
					$city_array[ $key ] = $value;
				}
				if ( array_search( 'postcode', $value, true ) ) {
					$postcode_array[ $key ] = $value;
				}
				if ( array_search( 'zone', $value, true ) ) {
					$zone_array[ $key ] = $value;
				}
				if ( array_search( 'product', $value, true ) ) {
					$product_array[ $key ] = $value;
				}
				if ( array_search( 'category', $value, true ) ) {
					$category_array[ $key ] = $value;
				}
				if ( array_search( 'tag', $value, true ) ) {
					$tag_array[ $key ] = $value;
				}
				if ( array_search( 'user', $value, true ) ) {
					$user_array[ $key ] = $value;
				}
				if ( array_search( 'cart_total', $value, true ) ) {
					$cart_total_array[ $key ] = $value;
				}
				if ( array_search( 'quantity', $value, true ) ) {
					$quantity_array[ $key ] = $value;
				}
                if ( array_search( 'width', $value, true ) ) {
					$width_array[ $key ] = $value;
				}
                if ( array_search( 'height', $value, true ) ) {
					$height_array[ $key ] = $value;
				}
                if ( array_search( 'length', $value, true ) ) {
					$length_array[ $key ] = $value;
				}
                if ( array_search( 'volume', $value, true ) ) {
					$volume_array[ $key ] = $value;
				}
				if ( afrsfw_fs()->is__premium_only() ) {
					if ( afrsfw_fs()->can_use_premium_code() ) {
						
						if ( array_search( 'variableproduct', $value, true ) ) {
							$variableproduct_array[ $key ] = $value;
						}
						if ( array_search( 'sku', $value, true ) ) {
							$sku_array[ $key ] = $value;
						}
						if ( array_search( 'product_qty', $value, true ) ) {
							$product_qty_array[ $key ] = $value;
						}
						if ( $attribute_taxonomies ) {
							foreach ( $attribute_taxonomies as $attribute ) {
								$att_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
								if ( array_search( $att_name, $value, true ) ) {
									$atta_name[ 'att_' . $att_name ] = $value;
								}
							}
						}
						if ( array_search( 'user_role', $value, true ) ) {
							$user_role_array[ $key ] = $value;
						}
                        if ( array_search( 'last_spent_order', $value, true ) ) {
							$last_spent_order_array[ $key ] = $value;
						}
						if ( array_search( 'cart_totalafter', $value, true ) ) {
							$cart_totalafter_array[ $key ] = $value;
						}
						if ( array_search( 'cart_productspecific', $value, true ) ) {
							$cart_productspecific_array[ $key ] = $value;
						}
						if ( array_search( 'weight', $value, true ) ) {
							$weight_array[ $key ] = $value;
						}
						if ( array_search( 'coupon', $value, true ) ) {
							$coupon_array[ $key ] = $value;
						}
						if ( array_search( 'shipping_class', $value, true ) ) {
							$shipping_class_array[ $key ] = $value;
						}
						if ( array_search( 'payment_method', $value, true ) ) {
							$payment_methods_array[ $key ] = $value;
						}
					}
				}
				//Check if is country exist
				if ( is_array( $country_array ) && isset( $country_array ) && ! empty( $country_array ) && ! empty( $cart_product_ids_array ) ) {
					$country_passed = $this->afrsm_pro_match_country_rules( $country_array, $general_rule_match );
					if ( 'yes' === $country_passed ) {
						$is_passed['has_fee_based_on_country'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_country'] = 'no';
					}
				}
				//Check if is product exist
				if ( is_array( $product_array ) && isset( $product_array ) && ! empty( $product_array ) && ! empty( $cart_product_ids_array ) ) {
					$product_passed = $this->afrsm_pro_match_simple_products_rule( $cart_array, $product_array, $general_rule_match, $sm_post_id );
					if ( 'yes' === $product_passed ) {
						$is_passed['has_fee_based_on_product'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_product'] = 'no';
					}
				}
				//Check if is category exist
				if ( is_array( $category_array ) && isset( $category_array ) && ! empty( $category_array ) && ! empty( $cart_product_ids_array ) ) {
					$category_passed = $this->afrsm_pro_match_category_rule( $cart_array, $category_array, $general_rule_match );
					if ( 'yes' === $category_passed ) {
						$is_passed['has_fee_based_on_category'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_category'] = 'no';
					}
				}
				//Check if is tag exist
				if ( is_array( $tag_array ) && isset( $tag_array ) && ! empty( $tag_array ) && ! empty( $cart_product_ids_array ) ) {
					$tag_passed = $this->afrsm_pro_match_tag_rule( $cart_array, $tag_array, $general_rule_match );
					if ( 'yes' === $tag_passed ) {
						$is_passed['has_fee_based_on_tag'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_tag'] = 'no';
					}
				}
				//Check if is user exist
				if ( is_array( $user_array ) && isset( $user_array ) && ! empty( $user_array ) && ! empty( $cart_product_ids_array ) ) {
					$user_passed = $this->afrsm_pro_match_user_rule( $user_array, $general_rule_match );
					if ( 'yes' === $user_passed ) {
						$is_passed['has_fee_based_on_user'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_user'] = 'no';
					}
				}
				//Check if is Cart Subtotal (Before Discount) exist
				if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && ! empty( $cart_total_array ) ) {
					$cart_total_before_passed = $this->afrsm_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match, $sm_post_id );
					if ( 'yes' === $cart_total_before_passed ) {
						$is_passed['has_fee_based_on_cart_total_before'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_cart_total_before'] = 'no';
					}
				}
				//Check if is quantity exist
				if ( is_array( $quantity_array ) && isset( $quantity_array ) && ! empty( $quantity_array ) ) {
					$quantity_passed = $this->afrsm_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match );
					if ( 'yes' === $quantity_passed ) {
						$is_passed['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_quantity'] = 'no';
					}
				}

                //Check if is width exist
				if ( is_array( $width_array ) && isset( $width_array ) && ! empty( $width_array ) && ! empty( $cart_product_ids_array ) ) {
					$width_passed = $this->afrsm_pro_match_cart_total_width_rule( $cart_array, $width_array, $general_rule_match );
					if ( 'yes' === $width_passed ) {
						$is_passed['has_fee_based_on_width'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_width'] = 'no';
					}
				}

                //Check if is height exist
				if ( is_array( $height_array ) && isset( $height_array ) && ! empty( $height_array ) && ! empty( $cart_product_ids_array ) ) {
					$height_passed = $this->afrsm_pro_match_cart_total_height_rule( $cart_array, $height_array, $general_rule_match );
					if ( 'yes' === $height_passed ) {
						$is_passed['has_fee_based_on_height'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_height'] = 'no';
					}
				}

                //Check if is length exist
				if ( is_array( $length_array ) && isset( $length_array ) && ! empty( $length_array ) && ! empty( $cart_product_ids_array ) ) {
					$length_passed = $this->afrsm_pro_match_cart_total_length_rule( $cart_array, $length_array, $general_rule_match );
					if ( 'yes' === $length_passed ) {
						$is_passed['has_fee_based_on_length'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_length'] = 'no';
					}
				}

                //Check if is volume exist
				if ( is_array( $volume_array ) && isset( $volume_array ) && ! empty( $volume_array ) && ! empty( $cart_product_ids_array ) ) {
					$volume_passed = $this->afrsm_pro_match_cart_total_volume_rule( $cart_array, $volume_array, $general_rule_match );
					if ( 'yes' === $volume_passed ) {
						$is_passed['has_fee_based_on_volume'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_volume'] = 'no';
					}
				}

				//Check if is state exist
				if ( is_array( $state_array ) && isset( $state_array ) && ! empty( $state_array ) && ! empty( $cart_product_ids_array ) ) {
					$state_passed = $this->afrsm_pro_match_state_rules( $state_array, $general_rule_match );
					if ( 'yes' === $state_passed ) {
						$is_passed['has_fee_based_on_state'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_state'] = 'no';
					}
				}
				//Check if is city exist
				if ( is_array( $city_array ) && isset( $city_array ) && ! empty( $city_array ) && ! empty( $cart_product_ids_array ) ) {
					$city_passed = $this->afrsm_pro_match_city_rules( $city_array, $general_rule_match );
					if ( 'yes' === $city_passed ) {
						$is_passed['has_fee_based_on_postcode'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_postcode'] = 'no';
					}
				}
				//Check if is postcode exist
				if ( is_array( $postcode_array ) && isset( $postcode_array ) && ! empty( $postcode_array ) && ! empty( $cart_product_ids_array ) ) {
					$postcode_passed = $this->afrsm_pro_match_postcode_rules( $postcode_array, $general_rule_match );
					if ( 'yes' === $postcode_passed ) {
						$is_passed['has_fee_based_on_postcode'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_postcode'] = 'no';
					}
				}
				//Check if is zone exist
				if ( is_array( $zone_array ) && isset( $zone_array ) && ! empty( $zone_array ) && ! empty( $cart_product_ids_array ) ) {
					$zone_passed = $this->afrsm_pro_match_zone_rules( $zone_array, $package, $general_rule_match );
					if ( 'yes' === $zone_passed ) {
						$is_passed['has_fee_based_on_zone'] = 'yes';
					} else {
						$is_passed['has_fee_based_on_zone'] = 'no';
					}
				}

				if ( afrsfw_fs()->is__premium_only() ) {
					if ( afrsfw_fs()->can_use_premium_code() ) {		
						//Check if is variable product exist
						if ( is_array( $variableproduct_array ) && isset( $variableproduct_array ) && ! empty( $variableproduct_array ) && ! empty( $cart_product_ids_array ) ) {
							$variable_prd_passed = $this->afrsm_pro_match_variable_products_rule__premium_only( $cart_array, $variableproduct_array, $general_rule_match );
							if ( 'yes' === $variable_prd_passed ) {
								$is_passed['has_fee_based_on_variable_prd'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_variable_prd'] = 'no';
							}
						}
						//Check if sku exist
						if ( is_array( $sku_array ) && isset( $sku_array ) && ! empty( $sku_array ) && ! empty( $cart_product_ids_array ) ) {
							$sku_passed = $this->afrsm_pro_match_sku_rule__premium_only( $cart_array, $sku_array, $general_rule_match );
							if ( 'yes' === $sku_passed ) {
								$is_passed['has_fee_based_on_sku'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_sku'] = 'no';
							}
						}
						//Check if product quantity exist
						if ( is_array( $product_qty_array ) && isset( $product_qty_array ) && ! empty( $product_qty_array ) && ! empty( $cart_product_ids_array ) ) {
							$product_qty_passed = $this->afrsm_pro_match_product_qty_rule__premium_only( $sm_post_id, $cart_array, $product_qty_array, $general_rule_match, $sitepress, $default_lang );
							if ( 'yes' === $product_qty_passed ) {
								$is_passed['has_fee_based_on_product_qty'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_product_qty'] = 'no';
							}
						}
						if ( ! empty( $attribute_taxonomies ) ) {
							if ( is_array( $atta_name ) && isset( $atta_name ) && ! empty( $atta_name ) && ! empty( $cart_product_ids_array ) ) {
								$attribute_passed = $this->afrsm_pro_match_attribute_rule__premium_only( $variation_cart_products_array, $atta_name, $general_rule_match );
								if ( 'yes' === $attribute_passed ) {
									$is_passed['has_fee_based_on_product_att'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_product_att'] = 'no';
								}
							}
						}
						//Check if is user role exist
						if ( is_array( $user_role_array ) && isset( $user_role_array ) && ! empty( $user_role_array ) && ! empty( $cart_product_ids_array ) ) {
							$user_role_passed = $this->afrsm_pro_match_user_role_rule__premium_only( $user_role_array, $general_rule_match );
							if ( 'yes' === $user_role_passed ) {
								$is_passed['has_fee_based_on_user_role'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_user_role'] = 'no';
							}
						}
                        //Check if is last order spent exist
						if ( is_array( $last_spent_order_array ) && isset( $last_spent_order_array ) && ! empty( $last_spent_order_array ) && ! empty( $cart_product_ids_array ) ) {
							$last_spent_order_passed = $this->afrsm_pro_match_last_spent_order_rule__premium_only( $last_spent_order_array, $general_rule_match );
							if ( 'yes' === $last_spent_order_passed ) {
								$is_passed['has_fee_based_on_last_spent_order'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_last_spent_order'] = 'no';
							}
						}
						//Check if is coupon exist
						if ( is_array( $coupon_array ) && isset( $coupon_array ) && ! empty( $coupon_array ) && ! empty( $cart_product_ids_array ) ) {
							$coupon_passed = $this->afrsm_pro_match_coupon_rule__premium_only( $wc_curr_version, $coupon_array, $general_rule_match );
							if ( 'yes' === $coupon_passed ) {
								$is_passed['has_fee_based_on_coupon'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_coupon'] = 'no';
							}
						}
						//Check if is Cart Subtotal (After Discount) exist
						if ( is_array( $cart_totalafter_array ) && isset( $cart_totalafter_array ) && ! empty( $cart_totalafter_array ) && ! empty( $cart_product_ids_array ) ) {
							$cart_total_after_passed = $this->afrsm_pro_match_cart_subtotal_after_discount_rule__premium_only( $wc_curr_version, $cart_totalafter_array, $general_rule_match, $sm_post_id );
							if ( 'yes' === $cart_total_after_passed ) {
								$is_passed['has_fee_based_on_cart_total_after'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_cart_total_after'] = 'no';
							}
						}
						//Check if is Cart Subtotal (Product Specific) exist
						if ( is_array( $cart_productspecific_array ) && isset( $cart_productspecific_array ) && ! empty( $cart_productspecific_array ) && ! empty( $cart_product_ids_array ) ) {
							$cart_total_specific_product_passed = $this->afrsm_pro_match_cart_subtotal_specific_product_shipping_rule__premium_only( $cart_array, $wc_curr_version, $cart_productspecific_array, $general_rule_match, $sm_post_id );
							if ( 'yes' === $cart_total_specific_product_passed ) {
								$is_passed['has_fee_based_on_cart_total_specific_product'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_cart_total_specific_product'] = 'no';
							}
						}
						//Check if is weight exist
						if ( is_array( $weight_array ) && isset( $weight_array ) && ! empty( $weight_array ) && ! empty( $cart_product_ids_array ) ) {
							$weight_passed = $this->afrsm_pro_match_cart_total_weight_rule__premium_only( $cart_array, $weight_array, $general_rule_match );
							if ( 'yes' === $weight_passed ) {
								$is_passed['has_fee_based_on_weight'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_weight'] = 'no';
							}
						}
						//Check if is shipping class exist
						if ( is_array( $shipping_class_array ) && isset( $shipping_class_array ) && ! empty( $shipping_class_array ) && ! empty( $cart_product_ids_array ) ) {
							$shipping_class_passed = $this->afrsm_pro_match_shipping_class_rule__premium_only( $cart_array, $shipping_class_array, $general_rule_match );
							if ( 'yes' === $shipping_class_passed ) {
								$is_passed['has_fee_based_on_shipping_class'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_shipping_class'] = 'no';
							}
						}
                        //Check if is payment exist
						if ( is_array( $payment_methods_array ) && isset( $payment_methods_array ) && ! empty( $payment_methods_array ) ) {
							$payment_methods_passed = $this->afrsm_pro_match_payment_gateway_rule__premium_only( $payment_methods_array, $general_rule_match );
							if ( 'yes' === $payment_methods_passed ) {
								$is_passed['has_fee_based_on_payment'] = 'yes';
							} else {
								$is_passed['has_fee_based_on_payment'] = 'no';
							}
						}
						/**
						 * Filter for matched general conditional rules.
						 *
						 * @since  3.8
						 *
						 * @author jb
						 */
						$is_passed = apply_filters( 'afrsm_pro_is_passed_conditional_rule', $is_passed, $key, $value, $general_rule_match );
					}
				}
            }            
			if ( isset( $is_passed ) && ! empty( $is_passed ) && is_array( $is_passed ) ) {
				$fnispassed = array();
				foreach ( $is_passed as $val ) {
					if ( '' !== $val ) {
						$fnispassed[] = $val;
					}
				}
				if ( 'all' === $general_rule_match ) {
					if ( in_array( 'no', $fnispassed, true ) ) {
						$final_is_passed_general_rule['passed'] = 'no';
					} else {
						$final_is_passed_general_rule['passed'] = 'yes';
					}
				} else {
					if ( in_array( 'yes', $fnispassed, true ) ) {
						$final_is_passed_general_rule['passed'] = 'yes';
					} else {
						$final_is_passed_general_rule['passed'] = 'no';
					}
				}
			}
		}
		
		if ( empty( $final_is_passed_general_rule ) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
			$new_is_passed['passed'] = 'no';
		} else if ( ! empty( $final_is_passed_general_rule ) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
			$new_is_passed['passed'] = 'no';
		} else if ( empty( $final_is_passed_general_rule ) && in_array( '', $final_is_passed_general_rule, true ) ) {
			$new_is_passed['passed'] = 'no';
		} else if ( ! empty( $final_is_passed_general_rule ) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
			$new_is_passed['passed'] = 'yes';
		}

		if ( isset( $new_is_passed ) && ! empty( $new_is_passed ) && is_array( $new_is_passed ) ) {
			if ( ! in_array( 'no', $new_is_passed, true ) ) {
				if ( afrsfw_fs()->is__premium_only() ) {
					if ( afrsfw_fs()->can_use_premium_code() ) {
						$current_date  = strtotime( gmdate( 'd-m-Y' ) );
						$sm_start_date = ( isset( $sm_start_date ) && ! empty( $sm_start_date ) ) ? strtotime( $sm_start_date ) : '';
						$sm_end_date   = ( isset( $sm_end_date ) && ! empty( $sm_end_date ) ) ? strtotime( $sm_end_date ) : '';
						/*Check for date*/
						if ( ( $current_date >= $sm_start_date || '' === $sm_start_date ) && ( $current_date <= $sm_end_date || '' === $sm_end_date ) ) {
							$final_condition_flag['date'] = 'yes';
						} else {
							$final_condition_flag['date'] = 'no';
						}
						/*Check for time*/
						$local_nowtimestamp = current_time( 'timestamp' );
						$sm_time_from       = ( isset( $sm_time_from ) && ! empty( $sm_time_from ) ) ? strtotime( $sm_time_from ) : '';
						$sm_time_to         = ( isset( $sm_time_to ) && ! empty( $sm_time_to ) ) ? strtotime( $sm_time_to ) : '';
						if ( ( $local_nowtimestamp >= $sm_time_from || '' === $sm_time_from ) && ( $local_nowtimestamp <= $sm_time_to || '' === $sm_time_to ) ) {
							$final_condition_flag['time'] = 'yes';
						} else {
							$final_condition_flag['time'] = 'no';
						}
						/*Check for day*/
						$today = strtolower( gmdate('D', $local_nowtimestamp) );
						if ( ! empty( $sm_select_day_of_week ) ) {
							if ( in_array( $today, $sm_select_day_of_week, true ) || '' === $sm_select_day_of_week ) {
								$final_condition_flag['day'] = 'yes';
							} else {
								$final_condition_flag['day'] = 'no';
							}
						}
					} else {
                        $final_condition_flag[] = 'yes';
                    }
				} else {
					$final_condition_flag[] = 'yes';
				}
			} else {
				$final_condition_flag[] = 'no';
			}
		}

		if( (isset( $sm_select_log_in_user )) && ("yes" === $sm_select_log_in_user) ){
			if ( ! is_user_logged_in() ) {
				return false;
			}else{
				if ( empty( $final_condition_flag ) && $final_condition_flag === '' ) {
					return false;
				} else if ( ! empty( $final_condition_flag ) && in_array( 'no', $final_condition_flag, true ) ) {
					return false;
				} else if ( empty( $final_condition_flag ) && in_array( '', $final_condition_flag, true ) ) {
					return false;
				} else if ( ! empty( $final_condition_flag ) && in_array( 'yes', $final_condition_flag, true ) ) {
					return true;
				}
			}
		} else {
			if ( empty( $final_condition_flag ) && $final_condition_flag === '' ) {
				return false;
			} else if ( ! empty( $final_condition_flag ) && in_array( 'no', $final_condition_flag, true ) ) {
				return false;
			} else if ( empty( $final_condition_flag ) && in_array( '', $final_condition_flag, true ) ) {
				return false;
			} else if ( ! empty( $final_condition_flag ) && in_array( 'yes', $final_condition_flag, true ) ) {
				return true;
			}
		}
	}
	/**
	 * Check product type for front.
	 *
	 * @param object $_product Get product object.
	 *
	 * @param array  $value    Cart details.
	 *
	 * @return boolean $flag.
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_check_product_type_for_front( $_product, $value ) {
		$flag = false;

		if( $_product instanceof WC_Product ) {
            // Virtual product check
            if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
				$flag = true;
			}
		}
		return apply_filters( 'afrsm_check_product_type_for_front_ft', $flag, $_product, $value );
	}
    /**
	 * Check non-bundle product and subproducts for front.
	 *
	 * @param object $_product          Get cart object.
	 * @param array  $woo_cart_item     Rule type.
	 *
	 * @return int/array $return_value.
	 *
	 * @since  4.2.0
	 *
	 * @author sj
	 */
	public function afrsm_check_non_bundle_product_conditions( $_product, $woo_cart_item ) {

        $non_bundle_product_check = $bundle_porduct_check_with_ship_individual = false;

        //Non-bundle products
        $check_virtual = $this->afrsm_check_product_type_for_front( $_product, $woo_cart_item );
        if ( true === $check_virtual //For virtual product skip  
            && !isset($woo_cart_item['wooco_parent_key']) //For composite product skip  
            && !$woo_cart_item['data']->is_type('bundle') // For Bundle main product skip 
            && !( isset($woo_cart_item['bundled_by']) && !empty($woo_cart_item['bundled_by']) ) // For Bundle sub product skip 
            ) {
            $non_bundle_product_check = true;
            
        }
        
        //Ship individually with non-virtual Bundle sub products 
        if( isset($woo_cart_item['bundled_by']) && !empty($woo_cart_item['bundled_by']) && function_exists('wc_pb_get_bundled_cart_item_container') ){
            $bundle_container_item = wc_pb_get_bundled_cart_item_container( $woo_cart_item );
            $bundled_item_id = $woo_cart_item[ 'bundled_item_id' ];
            $bundled_item = $bundle_container_item[ 'data' ]->get_bundled_item( $bundled_item_id );
            if( $bundled_item->is_shipped_individually() ) {
                $bundle_porduct_check_with_ship_individual = true;
            }
        }

        //Check and process bundle products for rules
        if( $non_bundle_product_check || $bundle_porduct_check_with_ship_individual ){
            return true;
        } else {
            return false;
        }

    }
    /**
	 * Check bundle product type for front.
	 *
	 * @param object $cart_value    Get cart object.
	 * @param array  $type          Rule type.
	 *
	 * @return int/array $return_value.
	 *
	 * @since  4.2.0
	 *
	 * @author sj
	 */
	public function afrsm_get_bundle_product_data_by_type( $cart_value, $type ) {

        //This array will return type of data which return in string of array, other will return with sum of total
        $array_type = array( 'shipping_class', 'category', 'tag', 'sku', 'product_attr' );
        if( in_array( $type, $array_type, true ) ){
            $return_value = array();
        } else {
            $return_value = 0;
            $bundle_qty = !empty( $cart_value['quantity'] ) ? intval($cart_value['quantity']) : 0;
        }

        $_product = !empty($cart_value) && isset($cart_value['data']) && !empty($cart_value['data']) ? $cart_value['data'] : array();

        if( !empty($_product) && $_product->is_type('bundle') ){
            
            $ship_individual_arr = array();
            foreach($_product->get_bundled_items() as $single_bundle ) {
                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
            }

            $bundle_data = $cart_value['stamp'] ? $cart_value['stamp'] : array();
            if( !empty( $bundle_data ) ) {
                foreach( $bundle_data as $bd_index => $bd ) {
                    $prod_id = isset($bd['variation_id']) && !empty($bd['variation_id']) ? $bd['variation_id'] : $bd['product_id'];
                    if( !empty($prod_id) ) {
                        $prod_obj = wc_get_product($prod_id);
                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                            $prod_qty = !empty($bd['quantity']) ? $bd['quantity'] : 0;
                            if( 'qty' === $type ){
                                $return_value += $bundle_qty * $prod_qty;
                            }
                            if( 'width' === $type ){
                                $prod_width = $prod_obj->get_width() ? floatval($prod_obj->get_width()) : 0;
                                $return_value += $bundle_qty * $prod_qty * $prod_width;
                            }
                            if( 'height' === $type ){
                                $prod_height = $prod_obj->get_height() ? floatval($prod_obj->get_height()) : 0;
                                $return_value += $bundle_qty * $prod_qty * $prod_height;
                            }
                            if( 'length' === $type ){
                                $prod_length = $prod_obj->get_length() ? floatval($prod_obj->get_length()) : 0;
                                $return_value += $bundle_qty * $prod_qty * $prod_length;
                            }
                            if( 'volume' === $type ){
                                $prod_width = $prod_obj->get_width() ? floatval($prod_obj->get_width()) : 0;
                                $prod_height = $prod_obj->get_height() ? floatval($prod_obj->get_height()) : 0;
                                $prod_length = $prod_obj->get_length() ? floatval($prod_obj->get_length()) : 0;
                                $total_volume = $prod_width * $prod_height * $prod_length;
                                $return_value += $bundle_qty * $prod_qty * $total_volume;
                            }
                            if( 'category' === $type ){
                                if($prod_obj->is_type('variation')){
                                    $prod_id = $prod_obj->get_parent_id();
                                }
                                $cart_product_category = wp_get_post_terms( $prod_id, 'product_cat', array( 'fields' => 'ids' ) );
                                if ( isset( $cart_product_category ) && ! empty( $cart_product_category ) && is_array( $cart_product_category ) ) {
                                    $return_value[] = $cart_product_category;
                                }
                            }
                            if( 'tag' === $type ){
                                if($prod_obj->is_type('variation')){
                                    $prod_id = $prod_obj->get_parent_id();
                                }
                                $cart_product_tag = wp_get_post_terms( $prod_id, 'product_tag', array( 'fields' => 'ids' ) );
                                if ( isset( $cart_product_tag ) && ! empty( $cart_product_tag ) && is_array( $cart_product_tag ) ) {
                                    $return_value[] = $cart_product_tag;
                                }
                            }
                            if ( afrsfw_fs()->is__premium_only() ) {
                                if ( afrsfw_fs()->can_use_premium_code() ) {
                                    if( 'weight' === $type ){
                                        $prod_weight = $prod_obj->get_weight() ? floatval($prod_obj->get_weight()) : 0;
                                        $return_value += $bundle_qty * $prod_qty * $prod_weight;
                                    }
                                    if( 'shipping_class' === $type ){
                                        $products_shipping_class = $prod_obj->get_shipping_class();
                                        $return_value[] = ( ! empty( $products_shipping_class ) ) ? $products_shipping_class : '';
                                    }
                                    if( 'sku' === $type ){
                                        $products_sku = $prod_obj->get_sku();
                                        $return_value[] = ( ! empty( $products_sku ) ) ? $products_sku : '';
                                    }
                                    if( 'product_attr' === $type ){
                                        if ( $prod_obj->is_type( 'variation' ) ) {
                                            $variation               = new WC_Product_Variation( $prod_id );
                                            $variation_cart_products = $variation->get_variation_attributes();
                                            foreach($variation_cart_products as $variation_cart_product) {
                                                $return_value[] = $variation_cart_product;
                                            }
                                        } else if( $prod_obj->is_type( 'simple' ) ) {
                                            foreach( $prod_obj->get_attributes() as $sa_val ){
                                                foreach( $sa_val['options'] as $sa_option ){
                                                    $sa_data = get_term_by('id', $sa_option, $sa_val['name']);
                                                    if( $sa_data ) { // #71956 solution
                                                        $return_value[] = $sa_data->slug;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $return_value;
    }
    /**
	 * Get product ids from bundle product
	 *
	 * @param object $bundle_obj    Bundle product object.
	 *
	 * @return int/array $return_value.
	 *
	 * @since  4.2.0
	 *
	 * @author sj
	 */
	public function afrsm_get_product_ids_from_bundle_product( $cart_obj ) {
        
        $return_value = array();

        $_product = !empty($cart_obj) && isset($cart_obj['data']) && !empty($cart_obj['data']) ? $cart_obj['data'] : array();

        if( !empty($_product) && $_product->is_type('bundle') ){

            $ship_individual_arr = array();
            foreach($_product->get_bundled_items() as $single_bundle ) {
                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
            }

            $bundle_data = $cart_obj['stamp'] ? $cart_obj['stamp'] : array();
            if( !empty( $bundle_data ) ) {
                foreach( $bundle_data as $bd_index => $bd ) {
                    $prod_id = isset($bd['variation_id']) && !empty($bd['variation_id']) ? $bd['variation_id'] : $bd['product_id'];
                    if( !empty($prod_id) ) {
                        $prod_obj = wc_get_product($prod_id);
                        
                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                            $return_value[] = $prod_id;
                        }
                    }
                }
            }
        }

        return $return_value;
    }
	/**
	 * Check product type for admin.
	 *
	 * @param object $_product Get product object.
	 *
	 * @return boolean $flag.
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_check_product_type_for_admin( $_product ) {
		$flag = false;

		if( $_product instanceof WC_Product ) {
			if ( ! ( $_product->is_virtual( 'yes' ) ) && $_product->is_type( 'variable' ) ) {
				$flag = true;
			} elseif ( ! ( $_product->is_virtual( 'yes' ) ) && $_product->is_type( 'simple' ) ) {
				$flag = true;
			}
		}
		return apply_filters( 'afrsm_check_product_type_for_admin_ft', $flag, $_product );
	}
	/**
	 * Match country rules
	 *
	 * @param array  $country_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @uses     WC_Customer::get_shipping_country()
	 *
	 * @since    3.4
	 */
	public function afrsm_pro_match_country_rules( $country_array, $general_rule_match ) {
		$selected_country = WC()->customer->get_shipping_country();
		$is_passed        = array();
		foreach ( $country_array as $key => $country ) {
			if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
				if ( ! empty( $country['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'no';
					}
				}
				if ( empty( $country['product_fees_conditions_values'] ) ) {
					$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
				}
			}
			if ( 'not_in' === $country['product_fees_conditions_is'] ) {
				if ( ! empty( $country['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_country, $country['product_fees_conditions_values'], true )
					     || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters( 'afrsm_pro_match_country_rules_ft', $is_passed, $selected_country, $country_array, 'has_fee_based_on_country', $general_rule_match ),
			'has_fee_based_on_country',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match state rules
	 *
	 * @param array  $state_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    3.4
	 *
	 * @uses     WC_Customer::get_shipping_country()
	 * @uses     WC_Customer::get_shipping_state()
	 *
	 */
	public function afrsm_pro_match_state_rules( $state_array, $general_rule_match ) {
		$country        = WC()->customer->get_shipping_country();
		$state          = WC()->customer->get_shipping_state();
		$selected_state = $country . ':' . $state;
		$is_passed      = array();
		foreach ( $state_array as $key => $get_state ) {
			if ( 'is_equal_to' === $get_state['product_fees_conditions_is'] ) {
				if ( ! empty( $get_state['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'no';
					}
				}
			}
			if ( 'not_in' === $get_state['product_fees_conditions_is'] ) {
				if ( ! empty( $get_state['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters( 'afrsm_pro_match_state_rules_ft', $is_passed, $selected_state, $state_array, 'has_fee_based_on_state', $general_rule_match ),
			'has_fee_based_on_state',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match city rules
	 *
	 * @param array  $city_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     WC_Customer::get_shipping_postcode()
	 *
	 * @since    3.4
	 *
	 */
	public function afrsm_pro_match_city_rules( $city_array, $general_rule_match ) {
		$selected_city = strtolower(WC()->customer->get_shipping_city());
		$is_passed     = array();

		foreach ( $city_array as $key => $citycode ) {
			if ( 'is_equal_to' === $citycode['product_fees_conditions_is'] ) {
				if ( ! empty( $citycode['product_fees_conditions_values'] ) ) {
					$citystr        = str_replace( PHP_EOL, "<br/>", trim( $citycode['product_fees_conditions_values'] ) );
					
					$city_val_array = explode( '<br/>', $citystr );
					$new_city_array = array();
					foreach ( $city_val_array as $value ) {
						$new_city_array[] = trim( $value );
					}
					$new_city_array = array_map('strtolower', $new_city_array);
					
					if ( in_array( $selected_city, $new_city_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					}
				}
			}
			if ( 'not_in' === $citycode['product_fees_conditions_is'] ) {
				if ( ! empty( $citycode['product_fees_conditions_values'] ) ) {
					$citystr        = str_replace( PHP_EOL, "<br/>", $citycode['product_fees_conditions_values'] );
					$city_val_array = explode( '<br/>', $citystr );
					$city_val_array = array_map('strtolower', $city_val_array);
					if ( in_array( $selected_city, $city_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					}
				}
			}
		}
		
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters( 'afrsm_pro_match_city_rules_ft', $is_passed, $selected_city, $city_array, 'has_fee_based_on_postcode', $general_rule_match ),
			'has_fee_based_on_postcode',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match postcode rules
	 *
	 * @param array  $postcode_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     WC_Customer::get_shipping_postcode()
	 *
	 * @since    3.4
	 *
	 */
	public function afrsm_pro_match_postcode_rules( $postcode_array, $general_rule_match ) {
		$selected_postcode = WC()->customer->get_shipping_postcode();
		$is_passed         = array();
		foreach ( $postcode_array as $key => $postcode ) {
			if ( 'is_equal_to' === $postcode['product_fees_conditions_is'] ) {
				if ( ! empty( $postcode['product_fees_conditions_values'] ) ) {
					$postcodestr        = str_replace( PHP_EOL, "<br/>", trim( $postcode['product_fees_conditions_values'] ) );
					$postcode_val_array = explode( '<br/>', $postcodestr );
					$new_postcode_array = array();
					foreach ( $postcode_val_array as $value ) {
						$new_postcode_array[] = trim( $value );
					}
					if ( in_array( $selected_postcode, $new_postcode_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					}
				}
			}
			if ( 'not_in' === $postcode['product_fees_conditions_is'] ) {
				if ( ! empty( $postcode['product_fees_conditions_values'] ) ) {
					$postcodestr        = str_replace( PHP_EOL, "<br/>", $postcode['product_fees_conditions_values'] );
					$postcode_val_array = explode( '<br/>', $postcodestr );
					if ( in_array( $selected_postcode, $postcode_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters( 'afrsm_pro_match_postcode_rules_ft', $is_passed, $selected_postcode, $postcode_array, 'has_fee_based_on_postcode', $general_rule_match ),
			'has_fee_based_on_postcode',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match zone rules
	 *
	 * @param array        $zone_array
	 * @param array|object $package
	 * @param              $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_check_zone_available()
	 *
	 */
	public function afrsm_pro_match_zone_rules( $zone_array, $package, $general_rule_match ) {
		$is_passed = array();	
		
		foreach ( $zone_array as $key => $zone ) {
			$zone['product_fees_conditions_values'] = array_map( 'intval', $zone['product_fees_conditions_values']);
			if ( 'is_equal_to' === $zone['product_fees_conditions_is'] ) {
				if ( ! empty( $zone['product_fees_conditions_values'] ) ) {
					$get_zonelist = $this->afrsm_pro_check_zone_available( $package, $zone['product_fees_conditions_values'] );
					if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'no';
					}
				}
			}
			if ( 'not_in' === $zone['product_fees_conditions_is'] ) {
				if ( ! empty( $zone['product_fees_conditions_values'] ) ) {
					$get_zonelist = $this->afrsm_pro_check_zone_available( $package, $zone['product_fees_conditions_values'] );
					if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters( 'afrsm_pro_match_zone_rules_ft', $is_passed, $zone_array, 'has_fee_based_on_zone', $general_rule_match ),
			'has_fee_based_on_zone',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match variable products rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $variableproduct_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_fee_array_column_admin()
	 *
	 */
	public function afrsm_pro_match_variable_products_rule__premium_only( $cart_array, $variableproduct_array, $general_rule_match ) {
		global $sitepress;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $is_passed = $cart_product_ids_array = array();		
        
        foreach ( $cart_array as $woo_cart_item ) {

			$id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );

            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                $cart_product_ids_array[] = $id;
            }

            //Retrieve sub poduct ids of bundle product
            $bundle_product_ids = $this->afrsm_get_product_ids_from_bundle_product($woo_cart_item);
            if( !empty($bundle_product_ids) ){
                $cart_product_ids_array = array_merge($cart_product_ids_array, $bundle_product_ids);
            }
        }
        
		$cart_product_ids_array = array_unique( $this->afrsm_pro_array_flatten( $cart_product_ids_array ) );

		foreach ( $variableproduct_array as $key => $product ) {
			if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						settype( $product_id, 'integer' );
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						settype( $product_id, 'integer' );
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
						}
					}
				}
			}
			if ( 'only_equal_to' === $product['product_fees_conditions_is'] ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $cart_product_ids_array as $product_id ) {

						settype( $product_id, 'integer' );
						$product['product_fees_conditions_values'] = array_map('intval', $product['product_fees_conditions_values']);

						if ( in_array( $product_id, $product['product_fees_conditions_values'], true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
							break;
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_variable_products_rule__premium_only_ft',
				$is_passed,
				$cart_product_ids_array,
				$variableproduct_array,
				'has_fee_based_on_product',
				$general_rule_match
			),
			'has_fee_based_on_product',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match simple products rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $product_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_fee_array_column_admin()
	 *
	 */
	public function afrsm_pro_match_simple_products_rule( $cart_array, $product_array, $general_rule_match, $sm_post_id ) {
		global $sitepress;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$is_passed = $cart_product_ids_array = array();
		$is_passed_free = false;

        foreach ( $cart_array as $woo_cart_item ) {

			$id = !empty( $woo_cart_item['product_id'] ) ? $woo_cart_item['product_id'] : 0;
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );

            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                $cart_product_ids_array[] = $id;
            }

            //Retrieve sub poduct ids of bundle product
            $bundle_product_ids = $this->afrsm_get_product_ids_from_bundle_product($woo_cart_item);
            if( !empty($bundle_product_ids) ){
                $cart_product_ids_array = array_merge($cart_product_ids_array, $bundle_product_ids);
            }
        }
        
		$cart_product_ids_array = array_unique( $this->afrsm_pro_array_flatten( $cart_product_ids_array ) );
        
		$free_shipping_based_on = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on', true );
		$sm_free_shipping_based_on_product = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on_product', true );

		if( ("min_simple_product" === $free_shipping_based_on) && ("" !== $sm_free_shipping_based_on_product) ){
			foreach ( $sm_free_shipping_based_on_product as $key => $free_shipping_product_id ) {
				settype( $free_shipping_product_id, 'integer' );
				if ( in_array( $free_shipping_product_id, $cart_product_ids_array, true ) ) {
					$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
					$is_passed_free = true;
					break;
				} else {
					$is_passed_free = false;
				}
			}
		}
		if( false === $is_passed_free ){
			foreach ( $product_array as $key => $product ) {
				if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
					if ( ! empty( $product['product_fees_conditions_values'] ) ) {
						foreach ( $product['product_fees_conditions_values'] as $product_id ) {
							settype( $product_id, 'integer' );
							if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
								$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
								break;
							} else {
								$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
							}
						}
					}
				}
				if ( 'not_in' === $product['product_fees_conditions_is'] ) {
					if ( ! empty( $product['product_fees_conditions_values'] ) ) {
						foreach ( $product['product_fees_conditions_values'] as $product_id ) {
							settype( $product_id, 'integer' );
							if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
								$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
								break;
							} else {
								$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							}
						}
					}
				}
				if ( 'only_equal_to' === $product['product_fees_conditions_is'] ) {
					if ( ! empty( $product['product_fees_conditions_values'] ) ) {
						foreach ( $cart_product_ids_array as $product_id ) {

							settype( $product_id, 'integer' );
							$product['product_fees_conditions_values'] = array_map('intval', $product['product_fees_conditions_values']);

							if ( in_array( $product_id, $product['product_fees_conditions_values'], true ) ) {
								$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							} else {
								$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
								break;
							}
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_simple_products_rule_ft',
				$is_passed,
				$cart_product_ids_array,
				$product_array,
				'has_fee_based_on_product',
				$general_rule_match
			),
			'has_fee_based_on_product',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match category rules
	 *
	 * @param array  $cart_array
	 * @param array  $category_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_fee_array_column_admin()
	 * @uses     WC_Product class
	 * @uses     WC_Product::is_virtual()
	 * @uses     wp_get_post_terms()
	 * @uses     afrsm_pro_array_flatten()
	 *
	 */
	public function afrsm_pro_match_category_rule( $cart_array, $category_array, $general_rule_match ) {
        global $sitepress;
        $default_lang           = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$cart_category_id = $is_passed = $cart_product_ids_array = array();

        foreach ( $cart_array as $woo_cart_item ) {

			$id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );
            
            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                if($_product->is_type('variation')){
                    $id = $_product->get_parent_id();
                }
                $cart_product_ids_array[] = $id;
                $cart_product_category = wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'ids' ) );
                if ( isset( $cart_product_category ) && ! empty( $cart_product_category ) && is_array( $cart_product_category ) ) {
                    $cart_category_id[] = $cart_product_category;
                }
            }

            //Retrieve sub poduct ids of bundle product
            $bundle_product_ids = $this->afrsm_get_product_ids_from_bundle_product($woo_cart_item);
            if( !empty($bundle_product_ids) ){
                $cart_product_ids_array = array_merge($cart_product_ids_array, $bundle_product_ids);
            }

            //Check and process bundle products for rules
            $cart_category_id = array_merge( $cart_category_id, $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'category' ) );
        }
        $get_cat_all = array_unique( $this->afrsm_pro_array_flatten( $cart_category_id ) );
		$cart_product_ids_array = array_unique( $this->afrsm_pro_array_flatten( $cart_product_ids_array ) );
        
		foreach ( $category_array as $key => $category ) {
			if ( 'is_equal_to' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $category['product_fees_conditions_values'] as $category_id ) {
						settype( $category_id, 'integer' );
						if ( in_array( $category_id, $get_cat_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $category['product_fees_conditions_values'] as $category_id ) {
						settype( $category_id, 'integer' );
						if ( in_array( $category_id, $get_cat_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
						}
					}
				}
			}
			if ( 'only_equal_to' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $cart_product_ids_array as $product_id ) {

						settype( $product_id, 'integer' );
						$cart_product_category_ids = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
						$category['product_fees_conditions_values'] = array_map('intval', $category['product_fees_conditions_values']);

						$common_ids = array_intersect($cart_product_category_ids, $category['product_fees_conditions_values']);

						if ( is_array($common_ids) && !empty($common_ids) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
							break;
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_category_rule_ft',
				$is_passed,
				$cart_product_ids_array,
				$category_array,
				'has_fee_based_on_category',
				$general_rule_match
			),
			'has_fee_based_on_category',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match tag rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $tag_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_fee_array_column_admin()
	 * @uses     WC_Product class
	 * @uses     WC_Product::is_virtual()
	 * @uses     wp_get_post_terms()
	 * @uses     afrsm_pro_array_flatten()
	 *
	 */
	public function afrsm_pro_match_tag_rule( $cart_array, $tag_array, $general_rule_match ) {
        global $sitepress;
        $default_lang           = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$tagid = $is_passed = $cart_product_ids_array = array();

        foreach ( $cart_array as $woo_cart_item ) {

			$id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );
            
            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                if($_product->is_type('variation')){
                    $id = $_product->get_parent_id();
                }
                $cart_product_ids_array[] = $id;
                $cart_product_tag = wp_get_post_terms( $id, 'product_tag', array( 'fields' => 'ids' ) );
                if ( isset( $cart_product_tag ) && ! empty( $cart_product_tag ) && is_array( $cart_product_tag ) ) {
                    $tagid[] = $cart_product_tag;
                }
            }

            //Retrieve sub poduct ids of bundle product
            $bundle_product_ids = $this->afrsm_get_product_ids_from_bundle_product($woo_cart_item);
            if( !empty($bundle_product_ids) ){
                $cart_product_ids_array = array_merge($cart_product_ids_array, $bundle_product_ids);
            }

            //Check and process bundle products
            $tagid = array_merge( $tagid, $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'tag' ) );
        }
        
		$get_tag_all = array_unique( $this->afrsm_pro_array_flatten( $tagid ) );
        $cart_product_ids_array = array_unique( $this->afrsm_pro_array_flatten( $cart_product_ids_array ) );

		foreach ( $tag_array as $key => $tag ) {
			if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
						settype( $tag_id, 'integer' );
						if ( in_array( $tag_id, $get_tag_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
						settype( $tag_id, 'integer' );
						if ( in_array( $tag_id, $get_tag_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
						}
					}
				}
			}
			if ( 'only_equal_to' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $cart_product_ids_array as $product_id ) {
						
						settype( $product_id, 'integer' );
						$cart_product_tag_ids = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );
						$tag['product_fees_conditions_values'] = array_map('intval', $tag['product_fees_conditions_values']);

						$common_ids = array_intersect($cart_product_tag_ids, $tag['product_fees_conditions_values']);

						if ( is_array($common_ids) && !empty($common_ids) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
							break;
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_tag_rule_ft',
				$is_passed,
				$cart_product_ids_array,
				$tag_array,
				'has_fee_based_on_tag',
				$general_rule_match
			),
			'has_fee_based_on_tag',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match sku rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $sku_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_fee_array_column_admin()
	 * @uses     WC_Product class
	 * @uses     WC_Product::is_virtual()
	 * @uses     WC_Product::get_sku()
	 * @uses     afrsm_pro_array_flatten()
	 *
	 */
	public function afrsm_pro_match_sku_rule__premium_only( $cart_array, $sku_array, $general_rule_match ) {
		global $sitepress;
        $default_lang           = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $sku_ids = $is_passed = $cart_product_ids_array = array();
        
        foreach ( $cart_array as $woo_cart_item ) {
            
            $id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );

            // prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                $cart_product_ids_array[] = $id;
                $product_sku = $_product->get_sku();
                if ( isset( $product_sku ) && ! empty( $product_sku ) ) {
                    $sku_ids[] = $product_sku;
                }
            }

            //Retrieve sub poduct ids of bundle product
            $bundle_product_ids = $this->afrsm_get_product_ids_from_bundle_product($woo_cart_item);
            if( !empty($bundle_product_ids) ){
                $cart_product_ids_array = array_merge($cart_product_ids_array, $bundle_product_ids);
            }

            //Combine product ids from cart
            $sku_ids = array_merge( $sku_ids, $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'sku' ) );
        }
        
		$get_all_unique_sku = array_unique( $this->afrsm_pro_array_flatten( $sku_ids ) );
        $cart_product_ids_array = array_unique( $this->afrsm_pro_array_flatten( $cart_product_ids_array ) );
        
		foreach ( $sku_array as $key => $sku ) {
			if ( 'is_equal_to' === $sku['product_fees_conditions_is'] ) {
				if ( ! empty( $sku['product_fees_conditions_values'] ) ) {
					foreach ( $sku['product_fees_conditions_values'] as $sku_name ) {
						if ( in_array( $sku_name, $get_all_unique_sku, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $sku['product_fees_conditions_is'] ) {
				if ( ! empty( $sku['product_fees_conditions_values'] ) ) {
					foreach ( $sku['product_fees_conditions_values'] as $sku_name ) {
						if ( in_array( $sku_name, $get_all_unique_sku, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'yes';
						}
					}
				}
			}
			if ( 'only_equal_to' === $sku['product_fees_conditions_is'] ) {
				if ( ! empty( $sku['product_fees_conditions_values'] ) ) {
					foreach ( $get_all_unique_sku as $sku_name ) {
						if ( in_array( $sku_name, $sku['product_fees_conditions_values'], true ) ) {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_sku'] = 'no';
							break;
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_sku_rule__premium_only_ft',
				$is_passed,
				$cart_product_ids_array,
				$sku_array,
				'has_fee_based_on_sku',
				$general_rule_match
			),
			'has_fee_based_on_sku',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match specific product quantity rules
	 *
	 * @param int    $shipping_method_id_val
	 * @param array  $cart_array
	 * @param array  $product_qty_array
	 * @param string $general_rule_match
	 *
	 * @param string $default_lang
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 */
	public function afrsm_pro_match_product_qty_rule__premium_only( $shipping_method_id_val, $cart_array, $product_qty_array, $general_rule_match, $sitepress, $default_lang ) {
		$products_based_qty = 0;
		$products_based_qty = $this->afrsm_shipping_fees_get_per_product_qty__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang );
		$main_is_passed     = $this->afrsm_pro_match_product_based_qty_rule( $products_based_qty, $product_qty_array, $general_rule_match );
		return $main_is_passed;
	}
	/**
	 * Count qty for product based and cart based when apply per qty option is on. This rule will apply when advance pricing rule will disable
	 *
	 * @param int    $shipping_method_id_val
	 * @param array  $cart_array
	 * @param int    $products_based_qty
	 * @param string $default_lang
	 *
	 * @return int $total_products_based_qty
	 * @uses  get_post_meta()
	 * @uses  get_post()
	 * @uses  get_terms()
	 *
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_fees_get_per_product_qty__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang ) {
		$productFeesArray = get_post_meta( $shipping_method_id_val, 'sm_metabox', true );
		$all_rule_check   = array();
		if ( ! empty( $productFeesArray ) ) {
			foreach ( $productFeesArray as $condition ) {
				if ( array_search( 'product', $condition, true ) ) {

					$cart_final_products_array = array();

					//Product Condition Start
                    foreach ( $cart_array as $key => $value ) {
                        $site_product_id = $value['product_id'] ? $value['product_id'] : 0;
                        if ( ! empty( $sitepress ) ) {
                            $site_product_id = apply_filters( 'wpml_object_id', $site_product_id, 'product', true, $default_lang );
                        }
                        $_product      = wc_get_product( $site_product_id );

                        //prepare data from non-bundle products
                        $product_ids = array_map('intval', $condition['product_fees_conditions_values']);
                        if( $this->afrsm_check_non_bundle_product_conditions($_product, $value) ){
                            if ( ! empty( $product_ids ) ) {
                                if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( in_array( $site_product_id, $product_ids, true ) ){
                                        $cart_final_products_array[ $key ] = $value;
                                    }
                                } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) { 
                                    if( !in_array( $site_product_id, $product_ids, true ) ){
                                        $cart_final_products_array[ $key ] = $value;
                                    }
                                } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if ( in_array( $site_product_id, $product_ids, true ) ) {
                                        $cart_final_products_array[ $key ] = $value;
                                    } else {
                                        $cart_final_products_array = array();
                                    }
                                }
                            }
                        }

                        //Check and process bundle products
                        if( !empty($_product) && $_product->is_type('bundle') ){
                            $bundle_qty = !empty( $value['quantity'] ) ? intval($value['quantity']) : 0;
                            $ship_individual_arr = array();
                            foreach($_product->get_bundled_items() as $single_bundle ) {
                                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
                            }
                
                            $bundle_data = $value['stamp'] ? $value['stamp'] : array();
                            if( !empty( $bundle_data ) ) {
                                foreach( $bundle_data as $bd_index => $bd ) {
                                    $prod_id = isset($bd['product_id']) && !empty($bd['product_id']) ? $bd['product_id'] : 0;
                                    settype( $prod_id, 'integer' );
                                    if( !empty($prod_id) ){
                                        $prod_obj = wc_get_product($prod_id);
                                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                                            $prod_qty = !empty($bd['quantity']) ? $bd['quantity'] : 0;
                                            if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                if( in_array( $prod_id, $product_ids, true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                }
                                            } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) { 
                                                if( !in_array( $prod_id, $product_ids, true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                }
                                            } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                if( in_array( $prod_id, $product_ids, true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                } else {
                                                    $all_rule_check = array();
                                                    $cart_final_products_array = array();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //After cart loop end check for non-bundle product qty to add with bundle product qty
					if ( ! empty( $cart_final_products_array ) ) {
                        foreach ( $cart_final_products_array as $cart_item ) {
                            if( array_key_exists( $cart_item['product_id'], $all_rule_check ) ) {
                                $all_rule_check[ $cart_item['product_id'] ] += $cart_item['quantity'];
                            } else {
                                $all_rule_check[ $cart_item['product_id'] ] = $cart_item['quantity'];
                            }
						}
					}
					//Product Condition End
				}

				if ( array_search( 'variableproduct', $condition, true ) ) {
					
					$cart_final_var_products_array = array();

					//Variable Product Condition Start
                    foreach ( $cart_array as $key => $value ) {
                        $site_product_id = isset($value['variation_id']) && !empty($value['variation_id']) ? $value['variation_id'] : $value['product_id'];
                        if ( ! empty( $sitepress ) ) {
                            $site_product_id = apply_filters( 'wpml_object_id', $site_product_id, 'product', true, $default_lang );
                        }
                        $_product = wc_get_product( $site_product_id );

                        //prepare data from non-bundle products
                        $product_ids = array_map('intval', $condition['product_fees_conditions_values']);
                        if( $this->afrsm_check_non_bundle_product_conditions($_product, $value) ){
                            if ( ! empty( $product_ids ) ) {
                                if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( in_array( $site_product_id, $product_ids, true ) ){
                                        $cart_final_var_products_array[ $key ] = $value;
                                    }
                                } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                                    if ( !in_array( $site_product_id, $product_ids, true ) ) {
                                        $cart_final_var_products_array[ $key ] = $value;
                                    }
                                } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( in_array( $site_product_id, $product_ids, true ) ){
                                        $cart_final_var_products_array[ $key ] = $value;
                                    } else {
                                        $cart_final_var_products_array = array();
                                    }
                                }
                            }
                        }
                        
                        //Check and process bundle products
                        if( !empty($_product) && $_product->is_type('bundle') ){
                            $bundle_qty = !empty( $value['quantity'] ) ? intval($value['quantity']) : 0;
                            $ship_individual_arr = array();
                            foreach($_product->get_bundled_items() as $single_bundle ) {
                                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
                            }
                
                            $bundle_data = $value['stamp'] ? $value['stamp'] : array();
                            if( !empty( $bundle_data ) ) {
                                foreach( $bundle_data as $bd_index => $bd ) {
                                    $prod_id = isset($bd['variation_id']) && !empty($bd['variation_id']) ? $bd['variation_id'] : 0;
                                    settype( $prod_id, 'integer' );
                                    if( !empty($prod_id) ){
                                        $prod_obj = wc_get_product($prod_id);
                                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                                            $prod_qty = !empty($bd['quantity']) ? $bd['quantity'] : 0;
                                            if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                if( in_array( $prod_id, $product_ids, true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                }
                                            } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) { 
                                                if( !in_array( $prod_id, $product_ids, true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                }
                                            } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                if( in_array( $prod_id, $product_ids, true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                } else {
                                                    $all_rule_check = array();
                                                    $cart_final_var_products_array = array();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

					if ( ! empty( $cart_final_var_products_array ) ) {
						foreach ( $cart_final_var_products_array as $cart_item ) {
                            if( array_key_exists( $cart_item['variation_id'], $all_rule_check ) ) {
                                $all_rule_check[ $cart_item['variation_id'] ] += $cart_item['quantity'];
                            } else {
                                $all_rule_check[ $cart_item['variation_id'] ] = $cart_item['quantity'];
                            }
						}
					}
					// Variable Product Condition End
				}

				if ( array_search( 'category', $condition, true ) ) {

                    //Category Condition Start
					$final_cart_products_cats_ids  = array();
					$cart_final_cat_products_array = array();
					$all_cats                      = get_terms(
						array(
							'taxonomy' => 'product_cat',
							'fields'   => 'ids'
						)
					);

                    //Get backend rule category array with filter
                    $category_ids = array_map('intval', $condition['product_fees_conditions_values']);
                    if ( ! empty( $category_ids ) ) {
                        if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_cats_ids = $category_ids;
                        } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_cats_ids = array_diff( $all_cats, $category_ids );
                        } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_cats_ids = array_intersect( $all_cats, $category_ids );
                        }
                    }
                    $final_cart_products_cats_ids = array_map('intval', $final_cart_products_cats_ids);

					foreach ( $cart_array as $key => $value ) {

                        //prepare data from non-bundle products
						$cart_product_category = wp_get_post_terms( $value['product_id'], 'product_cat', array( 'fields' => 'ids' ) );
						if ( ! empty( $cart_product_category ) ) {
							$id         = ! empty( $value['variation_id'] ) ? $value['variation_id'] : $value['product_id'];
							$_product   = wc_get_product( $id );
							if( $this->afrsm_check_non_bundle_product_conditions($_product, $value) ){
                                if ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( empty(array_diff($cart_product_category ,$final_cart_products_cats_ids)) ){
                                        $cart_final_cat_products_array[ $key ] = $value;
                                    } else {
                                        $cart_final_cat_products_array = array();
                                        break;
                                    }
                                } else {
                                    if ( !empty (array_intersect( $cart_product_category, $final_cart_products_cats_ids ) ) ) {
                                        $cart_final_cat_products_array[ $key ] = $value;
                                    }
                                }
							}
						}

                        //Check and process bundle products
                        if( !empty($_product) && $_product->is_type('bundle') ){
                            $bundle_qty = !empty( $value['quantity'] ) ? intval($value['quantity']) : 0;
                            $ship_individual_arr = array();
                            foreach($_product->get_bundled_items() as $single_bundle ) {
                                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
                            }
                
                            $bundle_data = $value['stamp'] ? $value['stamp'] : array();
                            if( !empty( $bundle_data ) ) {
                                foreach( $bundle_data as $bd_index => $bd ) {
                                    $prod_id = isset($bd['variation_id']) && !empty($bd['variation_id']) ? $bd['variation_id'] : $bd['product_id'];
                                    settype( $prod_id, 'integer' );
                                    if( !empty($prod_id) ){
                                        $prod_obj = wc_get_product($prod_id);
                                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                                            $prod_qty = !empty($bd['quantity']) ? $bd['quantity'] : 0;
                                            if($prod_obj->is_type('variation')){
                                                $prod_id = $prod_obj->get_parent_id();
                                            }
                                            $cart_product_category = wp_get_post_terms( $prod_id, 'product_cat', array( 'fields' => 'ids' ) );
                                            if ( ! empty( $cart_product_category ) ) {
                                                if ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                    if( empty(array_diff($cart_product_category ,$final_cart_products_cats_ids)) ){
                                                        if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                            $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                        } else {
                                                            $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                        }
                                                    } else {
                                                        $all_rule_check = array();
                                                    }
                                                } else {
                                                    if ( !empty (array_intersect( $cart_product_category, $final_cart_products_cats_ids ) ) ) {
                                                        if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                            $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                        } else {
                                                            $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
					}

					if ( ! empty( $cart_final_cat_products_array ) ) {
						foreach ( $cart_final_cat_products_array as $cart_item ) {
                            if( isset($all_rule_check[ $cart_item['product_id'] ]) ) {
                                $all_rule_check[ $cart_item['product_id'] ] += $cart_item['quantity'];
                            } else {
                                $all_rule_check[ $cart_item['product_id'] ] = $cart_item['quantity'];
                            }
						}
					}
                    //Category Condition Start
				}

				if ( array_search( 'tag', $condition, true ) ) {

					//Tag Condition Start
					$final_cart_products_tag_ids         = array();
					$cart_final_tag_products_array       = array();
					$all_tags                            = get_terms(
						array(
							'taxonomy' => 'product_tag',
							'fields'   => 'ids',
						)
					);

                    //Get backend rule tag array with filter
                    $tag_ids = array_map('intval', $condition['product_fees_conditions_values']);
                    if ( ! empty( $tag_ids ) ) {
                        if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_tag_ids = $tag_ids;
                        } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_tag_ids = array_diff( $all_tags, $tag_ids );
                        } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_tag_ids = array_intersect( $all_tags, $tag_ids );
                        }
                    }
                    $final_cart_products_tag_ids = array_map('intval', $final_cart_products_tag_ids);
					
					foreach ( $cart_array as $key => $value ) {

                        //prepare data from non-bundle products
                        $cart_product_tags = wp_get_post_terms( $value['product_id'], 'product_tag', array( 'fields' => 'ids' ) );
                        if( !empty($cart_product_tags) ){
                            $id            = ! empty( $value['variation_id'] ) ? $value['variation_id'] : $value['product_id'];
							$_product   = wc_get_product( $id );
                            if( $this->afrsm_check_non_bundle_product_conditions($_product, $value) ){
                                if ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( empty(array_diff($cart_product_tags ,$final_cart_products_tag_ids)) ){
                                        $cart_final_tag_products_array[ $key ] = $value;
                                    } else {
                                        $cart_final_tag_products_array = array();
                                        break;
                                    }
                                } else {
                                    if ( !empty (array_intersect( $cart_product_tags, $final_cart_products_tag_ids ) ) ) {
                                        $cart_final_tag_products_array[ $key ] = $value;
                                    }
                                }
                            }
                        }

                        //Check and process bundle products
                        if( !empty($_product) && $_product->is_type('bundle') ){
                            $bundle_qty = !empty( $value['quantity'] ) ? intval($value['quantity']) : 0;
                            $ship_individual_arr = array();
                            foreach($_product->get_bundled_items() as $single_bundle ) {
                                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
                            }
                
                            $bundle_data = $value['stamp'] ? $value['stamp'] : array();
                            if( !empty( $bundle_data ) ) {
                                foreach( $bundle_data as $bd_index => $bd ) {
                                    $prod_id = isset($bd['variation_id']) && !empty($bd['variation_id']) ? $bd['variation_id'] : $bd['product_id'];
                                    settype( $prod_id, 'integer' );
                                    if( !empty($prod_id) ){
                                        $prod_obj = wc_get_product($prod_id);
                                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                                            $prod_qty = !empty($bd['quantity']) ? $bd['quantity'] : 0;
                                            if($prod_obj->is_type('variation')){
                                                $prod_id = $prod_obj->get_parent_id();
                                            }
                                            $cart_product_tags = wp_get_post_terms( $prod_id, 'product_tag', array( 'fields' => 'ids' ) );
                                            if ( ! empty( $cart_product_tags ) ) {
                                                if ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                    if( empty(array_diff($cart_product_tags ,$final_cart_products_tag_ids)) ){
                                                        if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                            $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                        } else {
                                                            $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                        }
                                                    } else {
                                                        $all_rule_check = array();
                                                    }
                                                } else {
                                                    if ( !empty (array_intersect( $cart_product_tags, $final_cart_products_tag_ids ) ) ) {
                                                        if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                            $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                        } else {
                                                            $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
					}
					if ( ! empty( $cart_final_tag_products_array ) ) {
						foreach ( $cart_final_tag_products_array as $cart_item ) {
							if( isset($all_rule_check[ $cart_item['product_id'] ]) ) {
                                $all_rule_check[ $cart_item['product_id'] ] += $cart_item['quantity'];
                            } else {
                                $all_rule_check[ $cart_item['product_id'] ] = $cart_item['quantity'];
                            }
						}
					}
                    //Tag Condition End
				}
				if ( array_search( 'sku', $condition, true ) ) {
                    
					// Product SKU Condition Start
					$cart_final_skus_array = array();

                    foreach ( $cart_array as $key => $value ) {
                        $site_product_id = isset($value['variation_id']) && !empty($value['variation_id']) ? $value['variation_id'] : $value['product_id'];
                        if ( ! empty( $sitepress ) ) {
                            $site_product_id = apply_filters( 'wpml_object_id', $site_product_id, 'product', true, $default_lang );
                        }
                        $_product = wc_get_product( $site_product_id );

                        //prepare data from non-bundle products
                        if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
                            if( $this->afrsm_check_non_bundle_product_conditions($_product, $value) ){
                                $cart_product_sku = $_product->get_sku();
                                if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ){
                                        $cart_final_skus_array[ $key ] = $value;
                                    }
                                } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                                    if( !in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ){
                                        $cart_final_skus_array[ $key ] = $value;
                                    }
                                } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    if( in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ){
                                        $cart_final_skus_array[ $key ] = $value;
                                    } else {
                                        $cart_final_skus_array = array();
                                        break;
                                    }
                                }
                            }
                        }

                        //Check and process bundle products
                        if( !empty($_product) && $_product->is_type('bundle') ){
                            $bundle_qty = !empty( $value['quantity'] ) ? intval($value['quantity']) : 0;
                            $ship_individual_arr = array();
                            foreach($_product->get_bundled_items() as $single_bundle ) {
                                $ship_individual_arr[$single_bundle->get_id()] = $single_bundle->is_shipped_individually();
                            }
                
                            $bundle_data = $value['stamp'] ? $value['stamp'] : array();
                            if( !empty( $bundle_data ) ) {
                                foreach( $bundle_data as $bd_index => $bd ) {
                                    $prod_id = isset($bd['variation_id']) && !empty($bd['variation_id']) ? $bd['variation_id'] : 0;
                                    settype( $prod_id, 'integer' );
                                    if( !empty($prod_id) ){
                                        $prod_obj = wc_get_product($prod_id);
                                        if( !$prod_obj->is_virtual( 'yes' ) && !$ship_individual_arr[$bd_index] ){
                                            $cart_product_sku = $prod_obj->get_sku();
                                            $prod_qty = !empty($bd['quantity']) ? $bd['quantity'] : 0;
                                            if($prod_obj->is_type('variation')){
                                                $prod_id = $prod_obj->get_parent_id();
                                            }
                                            if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                if( in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                }
                                            } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) { 
                                                if( !in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                }
                                            } elseif ( 'only_equal_to' === $condition['product_fees_conditions_is'] ) {
                                                if( in_array( $cart_product_sku, $condition['product_fees_conditions_values'], true ) ){
                                                    if( array_key_exists( $prod_id, $all_rule_check ) ) {
                                                        $all_rule_check[ $prod_id ] += $bundle_qty * $prod_qty;
                                                    } else {
                                                        $all_rule_check[ $prod_id ] = $bundle_qty * $prod_qty;
                                                    }
                                                } else {
                                                    $all_rule_check = array();
                                                    $cart_final_skus_array = array();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

					}
					if ( ! empty( $cart_final_skus_array ) ) {
						foreach ( $cart_final_skus_array as $cart_item ) {
                            if( isset($all_rule_check[ $cart_item['product_id'] ]) ){
                                $all_rule_check[ $cart_item['product_id'] ] += $cart_item['quantity'];
                            } else {
                                $all_rule_check[ $cart_item['product_id'] ] = $cart_item['quantity'];
                            }
						}
					}
					// Product SKU Condition End
				}
				/** Custom code here */
				$final_cart_products_size_slugs       = array();
				$final_cart_products_size_not_in_flag = 0;
				if ( array_search( 'pa_size', $condition, true ) ) {
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							$final_cart_products_size_slugs = $condition['product_fees_conditions_values'];
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition['product_fees_conditions_values'] ) ) {
							$final_cart_products_size_not_in_flag = 1;
							$final_cart_products_size_slugs       = $condition['product_fees_conditions_values'];
						}
					}
				}
			}
		}
        
		if ( ! empty( $all_rule_check ) ) {
			foreach ( $all_rule_check as $cart_item ) {
				if ( is_array( $cart_item ) ) {
					/** Custom code here */
					if ( isset( $cart_item[1] ) && ! empty( $cart_item[1] ) && ! empty( $final_cart_products_size_slugs ) ) {
						if ( 0 === $final_cart_products_size_not_in_flag ) {
							if ( in_array( $cart_item[1], $final_cart_products_size_slugs, true ) ) {
								$products_based_qty += $cart_item[0];
							}
						} else {
							if ( ! in_array( $cart_item[1], $final_cart_products_size_slugs, true ) ) {
								$products_based_qty += $cart_item[0];
							}
						}
					} else {
						$products_based_qty += $cart_item[0];
					}
				} else {
					$products_based_qty += $cart_item;
				}
			}
		}
		/**
		 * Filter for apply per qty rule.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		return apply_filters( 'afrsm_product_per_qty_ft', $products_based_qty, $productFeesArray, $cart_array, $sitepress, $default_lang );
	}
	/**
	 * Match attribute rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param string $att_name
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_fee_array_column_admin()
	 *
	 */
	public function afrsm_pro_match_attribute_rule__premium_only( $cart_product_ids_array, $att_name, $general_rule_match ) {
		$is_passed = array();

		foreach ( $att_name as $key => $product ) {
			if ( $product['product_fees_conditions_is'] === 'is_equal_to' ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'no';
						}
					}
				}
			}
			if ( $product['product_fees_conditions_is'] === 'not_in' ) {
				if ( ! empty( $product['product_fees_conditions_values'] ) ) {
					foreach ( $product['product_fees_conditions_values'] as $product_id ) {
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'yes';
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_attribute_rule__premium_only_ft',
				$is_passed,
				$cart_product_ids_array,
				$att_name,
				'has_fee_based_on_product_att',
				$general_rule_match
			),
			'has_fee_based_on_product_att',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match user rules
	 *
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     get_current_user_id()
	 * @since    3.4
	 *
	 * @uses     is_user_logged_in()
	 */
	public function afrsm_pro_match_user_rule( $user_array, $general_rule_match ) {
		if ( ! is_user_logged_in() ) {
			return false;
		}
		$current_user_id = get_current_user_id();
		$is_passed       = array();
		foreach ( $user_array as $key => $user ) {
			$user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
			if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'no';
				}
			}
			if ( 'not_in' === $user['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'yes';
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_user_rule_ft',
				$is_passed,
				$user_array,
				'has_fee_based_on_user',
				$general_rule_match
			),
			'has_fee_based_on_user',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match user role rules
	 *
	 * @param array  $user_role_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     is_user_logged_in()
	 *
	 * @since    3.4
	 *
	 */
	public function afrsm_pro_match_user_role_rule__premium_only( $user_role_array, $general_rule_match ) {
		/**
		 * check user loggedin or not
		 */
		global $current_user;
		if ( is_user_logged_in() ) {
			$current_user_role = $current_user->roles[0];
		} else {
			$current_user_role = 'guest';
		}
		$is_passed = array();
		foreach ( $user_role_array as $key => $user_role ) {
			if ( 'is_equal_to' === $user_role['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_role, $user_role['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'no';
				}
			}
			if ( 'not_in' === $user_role['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_role, $user_role['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user_role'] = 'yes';
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_user_role_rule__premium_only_ft',
				$is_passed,
				$user_role_array,
				'has_fee_based_on_user_role',
				$general_rule_match
			),
			'has_fee_based_on_user_role',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match coupon role rules
	 *
	 * @param string $wc_curr_version
	 * @param array  $coupon_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     WC_Cart::get_coupons()
	 * @uses     WC_Coupon::is_valid()
	 *
	 * @since    3.4
	 *
	 */
	public function afrsm_pro_match_coupon_rule__premium_only( $wc_curr_version, $coupon_array, $general_rule_match ) {
		global $woocommerce;
		if ( $wc_curr_version >= 3.0 ) {
			$cart_coupon = WC()->cart->get_coupons();
		} else {
			$cart_coupon = isset( $woocommerce->cart->coupons ) && ! empty( $woocommerce->cart->coupons ) ? $woocommerce->cart->coupons : array();
		}
		$couponId  = array();
		$is_passed = array();
		foreach ( $cart_coupon as $cartCoupon ) {
			if ( $cartCoupon->is_valid() && isset( $cartCoupon ) && ! empty( $cartCoupon ) ) {
				if ( $wc_curr_version >= 3.0 ) {
					$couponId[] = $cartCoupon->get_id();
				} else {
					$couponId[] = $cartCoupon->id;
				}
			}
		}
		foreach ( $coupon_array as $key => $coupon ) {
			if ( 'is_equal_to' === $coupon['product_fees_conditions_is'] ) {
				if ( ! empty( $coupon['product_fees_conditions_values'] ) ) {
					foreach ( $coupon['product_fees_conditions_values'] as $coupon_id ) {
						settype( $coupon_id, 'integer' );
						if ( in_array( $coupon_id, $couponId, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $coupon['product_fees_conditions_is'] ) {
				if ( ! empty( $coupon['product_fees_conditions_values'] ) ) {
					foreach ( $coupon['product_fees_conditions_values'] as $coupon_id ) {
						settype( $coupon_id, 'integer' );
						if ( in_array( $coupon_id, $couponId, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
						}
					}
				}
				if ( empty( $cart_coupon ) ) {
					return 'yes';
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_coupon_rule__premium_only_ft',
				$is_passed,
				$wc_curr_version,
				$coupon_array,
				'has_fee_based_on_coupon',
				$general_rule_match
			),
			'has_fee_based_on_coupon',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on cart subtotal before discount
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_total_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_subtotal()
	 *
	 */
	public function afrsm_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match, $sm_post_id ) {
		global $woocommerce, $woocommerce_wpml;
		if ( $wc_curr_version >= 3.0 ) {
			$total = $this->afrsm_pro_get_cart_subtotal();
		} else {
			$total = $woocommerce->cart->subtotal;
		}
		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
		} else {
			$new_total = $total;
		}
		$is_passed = array();
		$is_allow_free_shipping = get_post_meta( $sm_post_id, 'is_allow_free_shipping', true );
		$free_shipping_based_on = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on', true );
		$free_shipping_costs = get_post_meta( $sm_post_id, 'sm_free_shipping_cost', true );
        
		//convert price if multi currency WOOCS exist
		$free_shipping_costs = $this->afrsm_woocs_convert_price($free_shipping_costs);
		
		if( !empty($is_allow_free_shipping) && "on" === $is_allow_free_shipping && ("min_order_amt" === $free_shipping_based_on) && ("" !== $free_shipping_costs) && ($new_total > $free_shipping_costs) ){
			foreach ( $cart_total_array as $key => $cart_total ) {
				$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
			}
		}else{
			foreach ( $cart_total_array as $key => $cart_total ) {
				$cart_total['product_fees_conditions_values'] = $this->afrsm_pro_price_based_on_switcher( $cart_total['product_fees_conditions_values'] ); // convert curranct for Multi Currency for WooCommerce
				$cart_total['product_fees_conditions_values'] = $this->afrsm_woocs_convert_price( $cart_total['product_fees_conditions_values'] ); // convert currency for WOOCS plugin
				settype( $cart_total['product_fees_conditions_values'], 'float' );
				if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
						if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
						if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
						if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
						if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
					$cart_total['product_fees_conditions_values'];
					if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
						if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_total['product_fees_conditions_values'] ) ) {
						if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_subtotal_before_discount_rule_ft',
				$is_passed,
				$wc_curr_version,
				$cart_total_array,
				'has_fee_based_on_cart_total',
				$general_rule_match
			),
			'has_fee_based_on_cart_total',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on cart subtotal after discount
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_totalafter_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_remove_currency_symbol()
	 * @uses     WC_Cart::get_subtotal()
	 * @uses     WC_Cart::get_total_discount()
	 *
	 */
	public function afrsm_pro_match_cart_subtotal_after_discount_rule__premium_only( $wc_curr_version, $cart_totalafter_array, $general_rule_match, $sm_post_id ) {
		global $woocommerce, $woocommerce_wpml;
		if ( $wc_curr_version >= 3.0 ) {
			$totalprice = $this->afrsm_pro_get_cart_subtotal();
		} else {
			$totalprice = $this->afrsm_pro_remove_currency_symbol( $woocommerce->cart->get_cart_subtotal() );
		}
		if ( $wc_curr_version >= 3.0 ) {
			$totaldisc = $this->afrsm_pro_remove_currency_symbol( WC()->cart->get_total_discount() );
		} else {
			$totaldisc = $this->afrsm_pro_remove_currency_symbol( $woocommerce->cart->get_total_discount() );
		}
		$resultprice = $totalprice - $totaldisc;
		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
		} else {
			$new_resultprice = $resultprice;
		}
		$is_passed = array();
		$is_allow_free_shipping = get_post_meta( $sm_post_id, 'is_allow_free_shipping', true );
		$free_shipping_based_on = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on', true );
		$free_shipping_costs = get_post_meta( $sm_post_id, 'sm_free_shipping_cost', true );

		//convert price if multi currency WOOCS exist
		$free_shipping_costs = $this->afrsm_woocs_convert_price($free_shipping_costs);

		if( !empty($is_allow_free_shipping) && "on" === $is_allow_free_shipping && ("min_order_amt" === $free_shipping_based_on) && ("" !== $free_shipping_costs) && ($new_resultprice > $free_shipping_costs) ){
			foreach ( $cart_totalafter_array as $key => $cart_total ) {
				$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
			}
		}else{
			foreach ( $cart_totalafter_array as $key => $cart_totalafter ) {
				$cart_totalafter['product_fees_conditions_values'] = $this->afrsm_pro_price_based_on_switcher( $cart_totalafter['product_fees_conditions_values'] ); // convert curranct for Multi Currency for WooCommerce
				$cart_totalafter['product_fees_conditions_values'] = $this->afrsm_woocs_convert_price( $cart_totalafter['product_fees_conditions_values'] ); // convert currency for WOOCS plugin
				settype( $cart_totalafter['product_fees_conditions_values'], 'float' );
				if ( 'is_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] === $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] >= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] > $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] <= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] < $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $new_resultprice === $cart_totalafter['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						}
					}
				}
			}
		}
		
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_subtotal_after_discount_rule__premium_only_ft',
				$is_passed,
				$wc_curr_version,
				$cart_totalafter_array,
				'has_fee_based_on_cart_totalafter',
				$general_rule_match
			),
			'has_fee_based_on_cart_totalafter',
			$general_rule_match
		);
		return $main_is_passed;
	}
    /**
	 * Match rule based on cart subtotal after discount
	 *
	 * @param string $wc_curr_version
	 * @param array  $last_spent_order_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_remove_currency_symbol()
	 * @uses     WC_Cart::get_subtotal()
	 * @uses     WC_Cart::get_total_discount()
	 *
	 */
	public function afrsm_pro_match_last_spent_order_rule__premium_only( $last_spent_order_array, $general_rule_match ) {
		global $current_user, $woocommerce_wpml;
		
		$user_id 		= $current_user->ID;
        $resultprice 	= $this->afrsm_check_order_for_user__premium_only($user_id);
        $is_passed      = array();

		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
		} else {
			$new_resultprice = $resultprice;
		}
        settype($new_resultprice, 'float');
        foreach ( $last_spent_order_array as $key => $last_spent_order ) {
            $last_spent_order['product_fees_conditions_values'] = $this->afrsm_pro_price_based_on_switcher( $last_spent_order['product_fees_conditions_values'] ); // convert currency for Multi Currency for WooCommerce
            $last_spent_order['product_fees_conditions_values'] = $this->afrsm_woocs_convert_price( $last_spent_order['product_fees_conditions_values'] ); // convert currency for WOOCS plugin
            settype( $last_spent_order['product_fees_conditions_values'], 'float' );
            if ( 'is_equal_to' === $last_spent_order['product_fees_conditions_is'] ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] === $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                    }
                }
            }
            if ( 'less_equal_to' === $last_spent_order['product_fees_conditions_is'] ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] >= $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                    }
                }
            }
            if ( 'less_then' === $last_spent_order['product_fees_conditions_is'] ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] > $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                    }
                }
            }
            if ( 'greater_equal_to' === $last_spent_order['product_fees_conditions_is'] ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] <= $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                    }
                }
            }
            if ( 'greater_then' === $last_spent_order['product_fees_conditions_is'] ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] < $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                    }
                }
            }
            if ( 'not_in' === $last_spent_order['product_fees_conditions_is'] ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $new_resultprice === $last_spent_order['product_fees_conditions_values'] ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    }
                }
            }
        }
		
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  4.2.0
		 *
		 * @author sj
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_last_spent_order_rule__premium_only_ft',
				$is_passed,
				$last_spent_order_array,
				'has_fee_based_on_last_spent_order',
				$general_rule_match
			),
			'has_fee_based_on_last_spent_order',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on specific product cart subtotal
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_productspecific_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     afrsm_pro_remove_currency_symbol()
	 * @uses     WC_Cart::get_subtotal()
	 * @uses     WC_Cart::get_total_discount()
	 *
	 */
	public function afrsm_pro_match_cart_subtotal_specific_product_shipping_rule__premium_only( $cart_array, $wc_curr_version, $cart_productspecific_array, $general_rule_match, $sm_post_id ) {
		global $woocommerce_wpml;
		$is_passed = array();
		$totalprice = 0;
		$new_resultprice = 0;
		$get_condition_array = get_post_meta( $sm_post_id, 'sm_metabox', true );
		// Loop over $cart items
        if( ! empty($cart_array) ) {
            foreach ( $cart_array as $cart_item ) {
                if( !empty($get_condition_array) ) {
                    foreach($get_condition_array as $pvalue){
                        
                        $product_id =  !empty($cart_item['variation_id']) &&  (0 !== $cart_item['variation_id']) ? intval($cart_item['variation_id']) : intval($cart_item['product_id']);
                        $parent_product_id = intval($cart_item['product_id']);
                        $product = wc_get_product( $parent_product_id );
                        $qty = $cart_item['quantity'];

                        if( array_search( 'product', $pvalue, true ) || array_search( 'variableproduct', $pvalue, true ) ){
                            $pvalue['product_fees_conditions_values'] = array_map( 'intval', $pvalue['product_fees_conditions_values'] );
                            if( in_array( $product_id, $pvalue['product_fees_conditions_values'], true ) ){		
                                if( 'variable' === $product->get_type() ){
                                    $variable_product = new WC_Product_Variation( $product_id );
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $variable_product, $qty ) );
                                } else {
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
                                }
                                $totalprice += $product_subtotal;
                            }
                        } else if( array_search( 'sku', $pvalue, true ) ) {
                            $product_sku = $product->get_sku();
                            if( in_array( $product_sku, $pvalue['product_fees_conditions_values'], true ) ){
                                if( 'variable' === $product->get_type() ){
                                    $variable_product = new WC_Product_Variation( $product_id );
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $variable_product, $qty ) );
                                } else {
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
                                }
                                $totalprice += $product_subtotal;
                            }
                        } else if( array_search( 'category', $pvalue, true ) ){
                            $category_list = $product->get_category_ids();
                            $common_category = ( is_array($category_list) && !empty($category_list) ) ? array_intersect($category_list, $pvalue['product_fees_conditions_values']) : array();
                            if( is_array($common_category) && !empty($common_category) ){
                                if( 'variable' === $product->get_type() ){
                                    $variable_product = new WC_Product_Variation( $product_id );
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $variable_product, $qty ) );
                                } else {
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
                                }
                                $totalprice += $product_subtotal;
                            }
                        } else if( array_search( 'tag', $pvalue, true ) ){
                            $tag_list = $product->get_tag_ids();
                            $common_tag = ( is_array($tag_list) && !empty($tag_list) ) ? array_intersect($tag_list, $pvalue['product_fees_conditions_values']) : array();
                            if( is_array($common_tag) && !empty($common_tag) ){
                                if( 'variable' === $product->get_type() ){
                                    $variable_product = new WC_Product_Variation( $product_id );
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $variable_product, $qty ) );
                                } else {
                                    $product_subtotal = $this->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
                                }
                                $totalprice += $product_subtotal;
                            }
                        }
                    }
                }
            }
        }
		
		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $totalprice );
		} else {
			$new_resultprice = $totalprice;
		}
        
		$is_allow_free_shipping = get_post_meta( $sm_post_id, 'is_allow_free_shipping', true );
		$free_shipping_based_on = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on', true );
		$free_shipping_costs = get_post_meta( $sm_post_id, 'sm_free_shipping_cost', true );
		if( !empty($is_allow_free_shipping) && "on" === $is_allow_free_shipping && ("min_order_amt" === $free_shipping_based_on) && ("" !== $free_shipping_costs) && ($new_resultprice > $free_shipping_costs) ){
			foreach ( $cart_productspecific_array as $key => $cart_total ) {
				$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
			}
		} else {
			foreach ( $cart_productspecific_array as $key => $cart_productspecific ) {
				$cart_productspecific['product_fees_conditions_values'] = $this->afrsm_pro_price_based_on_switcher( $cart_productspecific['product_fees_conditions_values'] );// convert curranct for Multi Currency for WooCommerce
				$cart_productspecific['product_fees_conditions_values'] = $this->afrsm_woocs_convert_price( $cart_productspecific['product_fees_conditions_values'] ); // convert currency for WOOCS plugin
				settype( $cart_productspecific['product_fees_conditions_values'], 'float' );
				if ( 'is_equal_to' === $cart_productspecific['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_productspecific['product_fees_conditions_values'] ) ) {
						if ( $cart_productspecific['product_fees_conditions_values'] === $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_productspecific['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_productspecific['product_fees_conditions_values'] ) ) {
						if ( $cart_productspecific['product_fees_conditions_values'] >= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_productspecific['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_productspecific['product_fees_conditions_values'] ) ) {
						if ( $cart_productspecific['product_fees_conditions_values'] > $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_productspecific['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_productspecific['product_fees_conditions_values'] ) ) {
						if ( $cart_productspecific['product_fees_conditions_values'] <= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_productspecific['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_productspecific['product_fees_conditions_values'] ) ) {
						if ( $cart_productspecific['product_fees_conditions_values'] < $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_productspecific['product_fees_conditions_is'] ) {
					if ( ! empty( $cart_productspecific['product_fees_conditions_values'] ) ) {
						if ( $new_resultprice === $cart_productspecific['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_productspecific'] = 'yes';
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_subtotal_specific_product_shipping_rule__premium_only_ft',
				$is_passed,
				$wc_curr_version,
				$cart_productspecific_array,
				'has_fee_based_on_cart_productspecific',
				$general_rule_match
			),
			'has_fee_based_on_cart_productspecific',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on total cart quantity
	 *
	 * @param array  $cart_array
	 * @param array  $quantity_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function afrsm_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match ) {
		global $sitepress;
        $default_lang   = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $is_passed      = array();
        $quantity_total = 0;
        
        if( !empty( $cart_array ) ){
            foreach ( $cart_array as $woo_cart_item ) {

                $id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
                if ( ! empty( $sitepress ) ) {
                    $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
                }
                $_product = wc_get_product( $id );

                //prepare data from non-bundle products
                if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                    $quantity_total += $woo_cart_item['quantity'];
                }

                //Check and process bundle products
                $quantity_total += $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'qty' );
            }
        }

        settype( $quantity_total, 'integer' );
		foreach ( $quantity_array as $key => $quantity ) {
			settype( $quantity['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_total_cart_qty_rule_ft',
				$is_passed,
				$cart_array,
				$quantity_array,
				'has_fee_based_on_quantity',
				$general_rule_match
			),
			'has_fee_based_on_quantity',
			$general_rule_match
		);
		return $main_is_passed;
	}
    /**
	 * Match rule based on total cart width
	 *
	 * @param array  $cart_array
	 * @param array  $width_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function afrsm_pro_match_cart_total_width_rule( $cart_array, $width_array, $general_rule_match ) {
		global $sitepress;
        $default_lang   = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $is_passed      = array();
        $width_total    = 0;

		if( !empty( $cart_array ) ){
            foreach ( $cart_array as $woo_cart_item ) {

                $id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
                if ( ! empty( $sitepress ) ) {
                    $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
                }
                $_product = wc_get_product( $id );

                //prepare data from non-bundle products
                if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                    $prod_width = $_product->get_width() ? floatval($_product->get_width()) : 0;
                    $prod_qty = $woo_cart_item['quantity'] ? intval($woo_cart_item['quantity']) : 1;
                    $width_total += $prod_qty * $prod_width;
                }
                
                //Check and process bundle products
                $width_total += $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'width' );
            }
        }
        
        settype( $width_total, 'float' );
		foreach ( $width_array as $key => $width ) {
			settype( $width['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $width['product_fees_conditions_is'] ) {
				if ( ! empty( $width['product_fees_conditions_values'] ) ) {
					if ( $width_total === $width['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $width['product_fees_conditions_is'] ) {
				if ( ! empty( $width['product_fees_conditions_values'] ) ) {
					if ( $width['product_fees_conditions_values'] >= $width_total ) {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'no';
					}
				}
			}
			if ( 'less_then' === $width['product_fees_conditions_is'] ) {
				if ( ! empty( $width['product_fees_conditions_values'] ) ) {
					if ( $width['product_fees_conditions_values'] > $width_total ) {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $width['product_fees_conditions_is'] ) {
				if ( ! empty( $width['product_fees_conditions_values'] ) ) {
					if ( $width['product_fees_conditions_values'] <= $width_total ) {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $width['product_fees_conditions_is'] ) {
				if ( ! empty( $width['product_fees_conditions_values'] ) ) {
					if ( $width['product_fees_conditions_values'] < $width_total ) {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'no';
					}
				}
			}
			if ( 'not_in' === $width['product_fees_conditions_is'] ) {
				if ( ! empty( $width['product_fees_conditions_values'] ) ) {
					if ( $width_total === $width['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_width'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_total_width_rule_ft',
				$is_passed,
				$cart_array,
				$width_array,
				'has_fee_based_on_width',
				$general_rule_match
			),
			'has_fee_based_on_width',
			$general_rule_match
		);
		return $main_is_passed;
	}
    /**
	 * Match rule based on total cart height
	 *
	 * @param array  $cart_array
	 * @param array  $height_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function afrsm_pro_match_cart_total_height_rule( $cart_array, $height_array, $general_rule_match ) {
		global $sitepress;
        $default_lang   = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $is_passed      = array();
        $height_total   = 0;

        if( !empty( $cart_array ) ) {
            foreach ( $cart_array as $woo_cart_item ) {

                $id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
                if ( ! empty( $sitepress ) ) {
                    $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
                }
                $_product = wc_get_product( $id );

                //prepare data from non-bundle products
                if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                    $prod_height = $_product->get_height() ? floatval($_product->get_height()) : 0;
                    $prod_qty = $woo_cart_item['quantity'] ? intval($woo_cart_item['quantity']) : 1;
                    $height_total += $prod_qty * $prod_height;
                }

                //Check and process bundle products
                $height_total += $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'height' );
            }
        }
        
		$is_passed = array();
        settype( $height_total, 'float' );
		foreach ( $height_array as $key => $height ) {
			settype( $height['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $height['product_fees_conditions_is'] ) {
				if ( ! empty( $height['product_fees_conditions_values'] ) ) {
					if ( $height_total === $height['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $height['product_fees_conditions_is'] ) {
				if ( ! empty( $height['product_fees_conditions_values'] ) ) {
					if ( $height['product_fees_conditions_values'] >= $height_total ) {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'no';
					}
				}
			}
			if ( 'less_then' === $height['product_fees_conditions_is'] ) {
				if ( ! empty( $height['product_fees_conditions_values'] ) ) {
					if ( $height['product_fees_conditions_values'] > $height_total ) {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $height['product_fees_conditions_is'] ) {
				if ( ! empty( $height['product_fees_conditions_values'] ) ) {
					if ( $height['product_fees_conditions_values'] <= $height_total ) {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $height['product_fees_conditions_is'] ) {
				if ( ! empty( $height['product_fees_conditions_values'] ) ) {
					if ( $height['product_fees_conditions_values'] < $height_total ) {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'no';
					}
				}
			}
			if ( 'not_in' === $height['product_fees_conditions_is'] ) {
				if ( ! empty( $height['product_fees_conditions_values'] ) ) {
					if ( $height_total === $height['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_height'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_total_height_rule_ft',
				$is_passed,
				$cart_array,
				$height_array,
				'has_fee_based_on_height',
				$general_rule_match
			),
			'has_fee_based_on_height',
			$general_rule_match
		);
		return $main_is_passed;
	}
    /**
	 * Match rule based on total cart length
	 *
	 * @param array  $cart_array
	 * @param array  $length_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function afrsm_pro_match_cart_total_length_rule( $cart_array, $length_array, $general_rule_match ) {
		global $sitepress;
        $default_lang   = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $is_passed      = array();
        $length_total   = 0;

        if( !empty( $cart_array ) ) {
            foreach ( $cart_array as $woo_cart_item ) {

                $id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
                if ( ! empty( $sitepress ) ) {
                    $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
                }
                $_product = wc_get_product( $id );

                //prepare data from non-bundle products
                if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                    $prod_length = $_product->get_length() ? floatval($_product->get_length()) : 0;
                    $prod_qty = $woo_cart_item['quantity'] ? intval($woo_cart_item['quantity']) : 1;
                    $length_total += $prod_qty * $prod_length;
                }

                //Check and process bundle products
                $length_total += $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'length' );
            }
        }
        
        settype( $length_total, 'float' );
		foreach ( $length_array as $key => $length ) {
			settype( $length['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $length['product_fees_conditions_is'] ) {
				if ( ! empty( $length['product_fees_conditions_values'] ) ) {
					if ( $length_total === $length['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $length['product_fees_conditions_is'] ) {
				if ( ! empty( $length['product_fees_conditions_values'] ) ) {
					if ( $length['product_fees_conditions_values'] >= $length_total ) {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'no';
					}
				}
			}
			if ( 'less_then' === $length['product_fees_conditions_is'] ) {
				if ( ! empty( $length['product_fees_conditions_values'] ) ) {
					if ( $length['product_fees_conditions_values'] > $length_total ) {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $length['product_fees_conditions_is'] ) {
				if ( ! empty( $length['product_fees_conditions_values'] ) ) {
					if ( $length['product_fees_conditions_values'] <= $length_total ) {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $length['product_fees_conditions_is'] ) {
				if ( ! empty( $length['product_fees_conditions_values'] ) ) {
					if ( $length['product_fees_conditions_values'] < $length_total ) {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'no';
					}
				}
			}
			if ( 'not_in' === $length['product_fees_conditions_is'] ) {
				if ( ! empty( $length['product_fees_conditions_values'] ) ) {
					if ( $length_total === $length['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_length'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_total_length_rule_ft',
				$is_passed,
				$cart_array,
				$length_array,
				'has_fee_based_on_length',
				$general_rule_match
			),
			'has_fee_based_on_length',
			$general_rule_match
		);
		return $main_is_passed;
	}
    /**
	 * Match rule based on total cart volume
	 *
	 * @param array  $cart_array
	 * @param array  $volume_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function afrsm_pro_match_cart_total_volume_rule( $cart_array, $volume_array, $general_rule_match ) {
		global $sitepress;
        $default_lang   = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $is_passed      = array();
        $volume_total   = 0;

        if( !empty( $cart_array ) ){
            foreach ( $cart_array as $woo_cart_item ) {

                $id = isset( $woo_cart_item['variation_id'] ) && !empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
                if ( ! empty( $sitepress ) ) {
                    $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
                }
                $_product = wc_get_product( $id );

                //prepare data from non-bundle products
                if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                    $prod_width = $_product->get_width() ? floatval($_product->get_width()) : 0;
                    $prod_height = $_product->get_height() ? floatval($_product->get_height()) : 0;
                    $prod_length = $_product->get_length() ? floatval($_product->get_length()) : 0;
                    $prod_qty = $woo_cart_item['quantity'] ? intval($woo_cart_item['quantity']) : 1;
                    $total_volume = $prod_width * $prod_height * $prod_length;
                    $volume_total += $prod_qty * $total_volume;
                }

                //Check and process bundle products
                $volume_total += $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'volume' );
            }
        }
        
        settype( $volume_total, 'float' );
		foreach ( $volume_array as $key => $volume ) {
			settype( $volume['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $volume['product_fees_conditions_is'] ) {
				if ( ! empty( $volume['product_fees_conditions_values'] ) ) {
					if ( $volume_total === $volume['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $volume['product_fees_conditions_is'] ) {
				if ( ! empty( $volume['product_fees_conditions_values'] ) ) {
					if ( $volume['product_fees_conditions_values'] >= $volume_total ) {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'no';
					}
				}
			}
			if ( 'less_then' === $volume['product_fees_conditions_is'] ) {
				if ( ! empty( $volume['product_fees_conditions_values'] ) ) {
					if ( $volume['product_fees_conditions_values'] > $volume_total ) {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $volume['product_fees_conditions_is'] ) {
				if ( ! empty( $volume['product_fees_conditions_values'] ) ) {
					if ( $volume['product_fees_conditions_values'] <= $volume_total ) {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $volume['product_fees_conditions_is'] ) {
				if ( ! empty( $volume['product_fees_conditions_values'] ) ) {
					if ( $volume['product_fees_conditions_values'] < $volume_total ) {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'no';
					}
				}
			}
			if ( 'not_in' === $volume['product_fees_conditions_is'] ) {
				if ( ! empty( $volume['product_fees_conditions_values'] ) ) {
					if ( $volume_total === $volume['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_volume'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_total_volume_rule_ft',
				$is_passed,
				$cart_array,
				$volume_array,
				'has_fee_based_on_volume',
				$general_rule_match
			),
			'has_fee_based_on_volume',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on product qty
	 *
	 * @param array  $cart_array
	 * @param array  $quantity_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function afrsm_pro_match_product_based_qty_rule( $product_qty, $quantity_array, $general_rule_match ) {
		$quantity_total = 0;
		if ( 0 < $product_qty ) {
			$quantity_total = $product_qty;
		}
		$is_passed = array();
		foreach ( $quantity_array as $key => $quantity ) {
			settype( $quantity['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_product_based_qty_rule_ft',
				$is_passed,
				$product_qty,
				$quantity_array,
				'has_fee_based_on_product_qty',
				$general_rule_match
			),
			'has_fee_based_on_product_qty',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on total cart weight
	 *
	 * @param array  $cart_array
	 * @param array  $weight_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 * @uses     WC_Product class
	 * @uses     WC_Product::is_virtual()
	 *
	 */
	public function afrsm_pro_match_cart_total_weight_rule__premium_only( $cart_array, $weight_array, $general_rule_match ) {
        global $sitepress;
        $default_lang           = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $sku_ids = $is_passed = array();
		$weight_total = 0;

		foreach ( $cart_array as $woo_cart_item ) {

            $id = isset($woo_cart_item['variation_id']) && !empty($woo_cart_item['variation_id']) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );

            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                $prod_weight = $woo_cart_item['data']->get_weight() ? floatval($woo_cart_item['data']->get_weight()) : 0;
                $prod_qty = $woo_cart_item['quantity'] ? intval($woo_cart_item['quantity']) : 1;
				$weight_total += $prod_qty * $prod_weight;
            }

            //Combine product ids from cart
            $weight_total += $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'weight' );
		}
		
		foreach ( $weight_array as $key => $weight ) {
			settype( $weight_total, 'float' );
			settype( $weight['product_fees_conditions_values'], 'float' );
			if ( 'is_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total === $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] >= $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'less_then' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] > $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] <= $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total > $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'not_in' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total === $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_cart_total_weight_rule__premium_only_ft',
				$is_passed,
				$cart_array,
				$weight_array,
				'has_fee_based_on_weight',
				$general_rule_match
			),
			'has_fee_based_on_weight',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on shipping class
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $shipping_class_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     afrsm_pro_array_flatten()
	 *
	 * @since    3.4
	 *
	 * @uses     WC_Product class
	 * @uses     WC_Product::is_virtual()
	 * @uses     get_the_terms()
	 */
	public function afrsm_pro_match_shipping_class_rule__premium_only( $cart_array, $shipping_class_array, $general_rule_match ) {
		global $sitepress;
        $default_lang  = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $shippingclass = $is_passed = array();

		foreach ( $cart_array as $woo_cart_item ) {

			$id          = ! empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );

            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                $products_shipping_class = $_product->get_shipping_class();
                $shippingclass[] = ( ! empty( $products_shipping_class ) ) ? $products_shipping_class : '';
            }

            //Check and process bundle products
            $shippingclass = array_merge( $shippingclass, $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'shipping_class' ) );
		}

		$shipping_class_id_array = array();
		if ( ! empty( $shippingclass ) ) {
			foreach ( $shippingclass as $shipping_slug ) {
				$product_shipping_class = get_term_by( 'slug', $shipping_slug, 'product_shipping_class' );
                $shipping_class_id_array[] = ( $product_shipping_class ) ? $product_shipping_class->term_id : 0;
			}
		}
		$get_shipping_class_all = array_unique( $this->afrsm_pro_array_flatten( $shipping_class_id_array ) );
		
		foreach ( $shipping_class_array as $key => $shipping_class ) {
			if ( 'is_equal_to' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
					foreach ( $shipping_class['product_fees_conditions_values'] as $shipping_class_slug ) {
						$shipping_class_term_id = get_term_by( 'slug', $shipping_class_slug, 'product_shipping_class' );
						if ( $shipping_class_term_id ) {
							$shipping_class_id = $shipping_class_term_id->term_id;
							settype( $shipping_class_id, 'integer' );
							if ( in_array( $shipping_class_id, $get_shipping_class_all, true ) ) {
								$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
								break;
							} else {
								$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
							}
						}
					}
				}
			}
			if ( 'not_in' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
					foreach ( $shipping_class['product_fees_conditions_values'] as $shipping_class_slug ) {
						$shipping_class_term_id = get_term_by( 'slug', $shipping_class_slug, 'product_shipping_class' );
						if ( $shipping_class_term_id ) {
							$shipping_class_id = $shipping_class_term_id->term_id;
							settype( $shipping_class_id, 'integer' );
							if ( in_array( $shipping_class_id, $get_shipping_class_all, true ) ) {
								$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
								break;
							} else {
								$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
							}
						}
					}
				}
			}
            if ( 'only_equal_to' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
                    foreach ( $get_shipping_class_all as $shipping_class_id ) {

                        settype( $shipping_class_id, 'integer' );

                        $new_shipping_class = array();
                        foreach( $shipping_class['product_fees_conditions_values'] as $sc_slug ){
                            $shipping_class_term_id = get_term_by( 'slug', $sc_slug, 'product_shipping_class' );
                            $new_shipping_class[] = $shipping_class_term_id->term_id;
                        }
                        $new_shipping_class = array_map('intval', $new_shipping_class);
		
                        if ( in_array( $shipping_class_id, $new_shipping_class, true ) ) {
                            $is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
                        } else {
                            $is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
                            break;
                        }
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_shipping_class_rule__premium_only_ft',
				$is_passed,
				$cart_array,
				$shipping_class_array,
				'has_fee_based_on_shipping_class',
				$general_rule_match
			),
			'has_fee_based_on_shipping_class',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Match rule based on payment gateway
	 *
	 * @param array $payment_methods_array
	 *
	 * @return array $is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Session::get()
	 *
	 */
	public function afrsm_pro_match_payment_gateway_rule__premium_only( $payment_methods_array, $general_rule_match ) {
		$is_passed             = array();
		$chosen_payment_method = WC()->session->get( 'chosen_payment_method' );
		foreach ( $payment_methods_array as $key => $payment ) {
			if ( $payment['product_fees_conditions_is'] === 'is_equal_to' ) {
				if ( in_array( $chosen_payment_method, $payment['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'no';
				}
			}
			if ( $payment['product_fees_conditions_is'] === 'not_in' ) {
				if ( in_array( $chosen_payment_method, $payment['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_payment'] = 'yes';
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->afrsm_pro_check_all_passed_general_rule(
			apply_filters(
				'afrsm_pro_match_payment_gateway_rule__premium_only_ft',
				$is_passed,
				$payment_methods_array,
				'has_fee_based_on_payment',
				$general_rule_match
			),
			'has_fee_based_on_payment',
			$general_rule_match
		);
		return $main_is_passed;
	}
	/**
	 * Find unique id based on given array
	 *
	 * @param array  $is_passed
	 * @param string $has_fee_based
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.6
	 *
	 */
	public function afrsm_pro_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match ) {
		$main_is_passed = 'no';
		$flag           = array();
		if ( ! empty( $is_passed ) ) {
			foreach ( $is_passed as $key => $is_passed_value ) {
				if ( 'yes' === $is_passed_value[ $has_fee_based ] ) {
					$flag[ $key ] = true;
				} else {
					$flag[ $key ] = false;
				}
			}
			if ( 'any' === $general_rule_match ) {
				if ( in_array( true, $flag, true ) ) {
					$main_is_passed = 'yes';
				} else {
					$main_is_passed = 'no';
				}
			} else {
				if ( in_array( false, $flag, true ) ) {
					$main_is_passed = 'no';
				} else {
					$main_is_passed = 'yes';
				}
			}
		}
		/**
		 * Filter for matched all passed general rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		return apply_filters(
			'afrsm_pro_check_all_passed_general_rule_ft',
			$main_is_passed,
			$is_passed,
			$has_fee_based,
			$general_rule_match
		);
	}
	/**
	 * Find unique id based on given array
	 *
	 * @param array $array
	 *
	 * @return array $result if $array is empty it will return false otherwise return array as $result
	 * @since    1.0.0
	 *
	 */
	public function afrsm_pro_array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = array_merge( $result, $this->afrsm_pro_array_flatten( $value ) );
			} else {
				$result[ $key ] = $value;
			}
		}
		return $result;
	}
	/**
	 * Find a matching zone for a given package.
	 *
	 * @param array|object $package
	 * @param array        $available_zone_id_array
	 *
	 * @return int $return_zone_id
	 * @uses   afrsm_pro_wc_make_numeric_postcode()
	 *
	 * @since  3.0.0
	 *
	 */
	public function afrsm_pro_check_zone_available( $package, $available_zone_id_array ) {
		$return_zone_id = '';
		
		//Cart page package selection
		$shipping_packages =  WC()->cart->get_shipping_packages();

		// Get the WC_Shipping_Zones instance object for the first package
		$shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) );
		$zone_id   = $shipping_zone->get_id(); // Get the zone ID

		if( !empty($zone_id) && $zone_id > 0 && in_array($zone_id, $available_zone_id_array, true) ){
			return $zone_id;
		}
        
		if($package){
			$country = $package['destination']['country'];
			if ( ! empty( $package['destination']['state'] ) && '' !== $package['destination']['state'] ) {
				$state = $country . ':' . $package['destination']['state'];
			} else {
				$state = '';
			}
			$postcode  = strtoupper( $package['destination']['postcode'] );
			$cart_city = $package['destination']['city'];

			$valid_postcodes = array( '*', $postcode );
			// Work out possible valid wildcard postcodes
			$postcode_length   = strlen( $postcode );
			$wildcard_postcode = $postcode;
			for ( $i = 0; $i < $postcode_length; $i ++ ) {
				$wildcard_postcode = substr( $wildcard_postcode, 0, - 1 );
				$valid_postcodes[] = $wildcard_postcode . '*';
			}
			foreach ( $available_zone_id_array as $available_zone_id ) {
				$postcode_ranges = new WP_Query( array(
					'post_type'      => 'wc_afrsm_zone',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'post__in'       => array( $available_zone_id ),
				) );
				$location_code   = array();
				foreach ( $postcode_ranges->posts as $postcode_ranges_value ) {
					$postcode_ranges_location_code               = get_post_meta( $postcode_ranges_value->ID, 'location_code', false );
					$zone_type                                   = get_post_meta( $postcode_ranges_value->ID, 'zone_type', true );
					$location_code[ $postcode_ranges_value->ID ] = $postcode_ranges_location_code;
					foreach ( $postcode_ranges_location_code as $location_code_sub_val ) {
						$country_array = array();
						$state_array   = array();
						foreach ( $location_code_sub_val as $location_country_state => $location_code_postcode_val ) {
							if ( 'postcodes' === $zone_type ) {
								if ( false !== strpos( $location_country_state, ':' ) ) {
									$location_country_state_explode = explode( ':', $location_country_state );
								} else {
									$state_array = array();
								}
								if ( ! empty( $location_country_state_explode ) ) {
									if ( array_key_exists( '0', $location_country_state_explode ) ) {
										$country_array[] = $location_country_state_explode[0];
									}
								} else {
									$country_array[] = $location_country_state;
								}
								if ( ! empty( $location_country_state_explode ) ) {
									if ( array_key_exists( '1', $location_country_state_explode ) ) {
										$state_array[] = $location_country_state;
									}
								}
								foreach ( $location_code_postcode_val as $location_code_val ) {
									if ( false !== strpos( $location_code_val, '=' ) ) {
										$location_code_val = str_replace( '=', ' ', $location_code_val );
									}
									if ( false !== strpos( $location_code_val, '-' ) ) {
										if ( $postcode_ranges->posts ) {
											$encoded_postcode     = $this->afrsm_pro_wc_make_numeric_postcode( $postcode );
											$encoded_postcode_len = strlen( $encoded_postcode );
											$range                = array_map( 'trim', explode( '-', $location_code_val ) );
											if ( 2 !== sizeof( $range ) ) {
												continue;
											}
											if ( is_numeric( $range[0] ) && is_numeric( $range[1] ) ) {
												$encoded_postcode = $postcode;
												$min              = $range[0];
												$max              = $range[1];
											} else {
												$min = $this->afrsm_pro_wc_make_numeric_postcode( $range[0] );
												$max = $this->afrsm_pro_wc_make_numeric_postcode( $range[1] );
												$min = str_pad( $min, $encoded_postcode_len, '0' );
												$max = str_pad( $max, $encoded_postcode_len, '9' );
											}
											if ( $encoded_postcode >= $min && $encoded_postcode <= $max ) {
												$return_zone_id = $available_zone_id;
											}
										}
									} elseif ( false !== strpos( $location_code_val, '*' ) ) {
										if ( in_array( $location_code_val, $valid_postcodes, true ) ) {
											$return_zone_id = $available_zone_id;
										}
									} else {
										if ( in_array( $country, $country_array, true ) ) {
											if ( ! empty( $state_array ) ) {
												if ( in_array( $state, $state_array, true ) ) {
													if ( in_array( $postcode, $location_code_postcode_val, true ) ) {
														$return_zone_id = $available_zone_id;
													}
												}
											} else {
												if ( $postcode === $location_code_val ) {
													$return_zone_id = $available_zone_id;
												}
											}
										}
									}
								}
							} elseif ( 'cities' === $zone_type ) {
								if ( false !== strpos( $location_country_state, ':' ) ) {
									$location_country_state_explode = explode( ':', $location_country_state );
								} else {
									$state_array = array();
								}
								if ( ! empty( $location_country_state_explode ) ) {
									if ( array_key_exists( '0', $location_country_state_explode ) ) {
										$country_array[] = $location_country_state_explode[0];
									}
								} else {
									$country_array[] = $location_country_state;
								}
								if ( ! empty( $location_country_state_explode ) ) {
									if ( array_key_exists( '1', $location_country_state_explode ) ) {
										$state_array[] = $location_country_state;
									}
								}
								foreach ( $location_code_postcode_val as $city_val ) {
									if ( in_array( $country, $country_array, true ) ) {
										if ( ! empty( $state_array ) ) {
											if ( in_array( $state, $state_array, true ) ) {
												if ( in_array( $cart_city, $location_code_postcode_val, true ) ) {
													$return_zone_id = $available_zone_id;
												}
											}
										} else {
											if ( $cart_city === $city_val ) {
												$return_zone_id = $available_zone_id;
											}
										}
									}
								}
							} elseif ( 'countries' === $zone_type ) {
								if ( ! empty( $country ) && in_array( $country, $location_code_postcode_val, true ) ) {
									$return_zone_id = $available_zone_id;
								}
							} elseif ( 'states' === $zone_type ) {
								if ( ! empty( $state ) && in_array( $state, $location_code_postcode_val, true ) ) {
									$return_zone_id = $available_zone_id;
								}
							}
						}
					}
				}
			}
		}
		return $return_zone_id;
	}
	/**
	 * Make numeric postcode function.
	 *
	 * @param mixed $postcode
	 *
	 * @return void
	 * @since  1.0.0
	 *
	 * Converts letters to numbers so we can do a simple range check on postcodes.
	 *
	 * E.g. PE30 becomes 16050300 (P = 16, E = 05, 3 = 03, 0 = 00)
	 *
	 * @access public
	 *
	 */
	function afrsm_pro_wc_make_numeric_postcode( $postcode ) {
		$postcode_length    = strlen( $postcode );
		$letters_to_numbers = array_merge( array( 0 ), range( 'A', 'Z' ) );
		$letters_to_numbers = array_flip( $letters_to_numbers );
		$numeric_postcode   = '';
		for ( $i = 0; $i < $postcode_length; $i ++ ) {
			if ( is_numeric( $postcode[ $i ] ) ) {
				$numeric_postcode .= str_pad( $postcode[ $i ], 2, '0', STR_PAD_LEFT );
			} elseif ( isset( $letters_to_numbers[ $postcode[ $i ] ] ) ) {
				$numeric_postcode .= str_pad( $letters_to_numbers[ $postcode[ $i ] ], 2, '0', STR_PAD_LEFT );
			} else {
				$numeric_postcode .= '00';
			}
		}
		return $numeric_postcode;
	}
	/**
	 * Display array column
	 *
	 * @param array $input
	 * @param int   $columnKey
	 * @param int   $indexKey
	 *
	 * @return array $array It will return array if any error generate then it will return false
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_fee_array_column_admin( array $input, $columnKey, $indexKey = null ) {
		$array = array();
		foreach ( $input as $value ) {
			if ( ! isset( $value[ $columnKey ] ) ) {
				wp_die( sprintf( esc_html_x( 'Key %d does not exist in array', esc_attr( $columnKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
				return false;
			}
			if ( is_null( $indexKey ) ) {
				$array[] = $value[ $columnKey ];
			} else {
				if ( ! isset( $value[ $indexKey ] ) ) {
					wp_die( sprintf( esc_html_x( 'Key %d does not exist in array', esc_attr( $indexKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
					return false;
				}
				if ( ! is_scalar( $value[ $indexKey ] ) ) {
					wp_die( sprintf( esc_html_x( 'Key %d does not contain scalar value', esc_attr( $indexKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
					return false;
				}
				$array[ $value[ $indexKey ] ] = $value[ $columnKey ];
			}
		}
		return $array;
	}
	/**
	 * Remove WooCommerce currency symbol
	 *
	 * @param float $price
	 *
	 * @return float $new_price2
	 * @since  1.0.0
	 *
	 * @uses   get_woocommerce_currency_symbol()
	 *
	 */
	public function afrsm_pro_remove_currency_symbol( $price ) {
		$wc_currency_symbol = get_woocommerce_currency_symbol();
		$cleanText	= wp_strip_all_tags($price);
		$new_price  = str_replace( $wc_currency_symbol, '', $cleanText );
		if( "," === wc_get_price_decimal_separator() ) {
			$new_price2 = (double)str_replace(",",".",str_replace(".","",$new_price));
		} else {
			$new_price2 = (double) preg_replace( '/[^.\d]/', '', $new_price );
		}
		return $new_price2;
	}
	/*
     * Get WooCommerce version number
     *
     * @since 1.0.0
     *
     * @return string if file is not exists then it will return null
     */
	function afrsm_pro_get_woo_version_number() {
		// If get_plugins() isn't available, require it
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		// Create the plugins folder and file variables
		$plugin_folder = get_plugins( '/' . 'woocommerce' );
		$plugin_file   = 'woocommerce.php';
		// If the plugin version number is set, return it
		if ( isset( $plugin_folder[ $plugin_file ]['Version'] ) ) {
			return $plugin_folder[ $plugin_file ]['Version'];
		} else {
			return null;
		}
	}
	/**
	 * Save shipping order in shipping list section
	 *
	 * @since 1.0.0
	 */
	public function afrsm_pro_sm_sort_order() {
		global $wpdb;

		$default_lang     = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$post_type 		  = self::afrsm_shipping_post_type;
		$paged      	  = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT);
		$get_smOrderArray = filter_input( INPUT_GET, 'smOrderArray', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
		$smOrderArray     = !empty( $get_smOrderArray ) ? array_map( 'sanitize_text_field', wp_unslash( $get_smOrderArray ) ) : '';
		
		//If order array empty then no need to order
		if( empty($smOrderArray) ){
			wp_die();
		}

		//Get all shipping post ids
        $query_args = array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => -1,
			'orderby'        => array( 
                'menu_order' =>'ASC', 
                'post_date' => 'DESC'
            ),
			'fields' 		 => 'ids'
		);
		$post_list = new WP_Query( $query_args );
		$results = $post_list->posts; 

		//Create the list of ID's
		$objects_ids = array();            
		foreach($results    as  $result) {
            settype( $result, 'integer' );
			$objects_ids[] = $result;   
		}
        
		//Here we switch order
		$objects_per_page = get_user_option( 'afrsm_rule_per_page' ) ? get_user_option( 'afrsm_rule_per_page' ) : get_option( 'afrsm_sm_count_per_page' );
		$edit_start_at = $paged * $objects_per_page - $objects_per_page;
		$index = 0;
		for( $i = $edit_start_at; $i < ($edit_start_at + $objects_per_page); $i++ ) {

			if(!isset($objects_ids[$i]))
				break;
				
			$objects_ids[$i] = (int)$smOrderArray[$index];
			$index++;
		}
		
		//Update the menu_order within database
		foreach( $objects_ids as $menu_order => $id ) {
            $data = array( 'menu_order' => $menu_order, 'ID' => $id);
            wp_update_post( $data );

			clean_post_cache( $id );
		}
		
		//Update for our global variable
		if ( isset( $objects_ids ) && ! empty( $objects_ids ) ) {
			update_option( 'sm_sortable_order_' . $default_lang, $objects_ids );
		}
		wp_die();
	}
	/**
	 * Save master settings data
	 *
	 * @since 1.0.0
	 */
	public function afrsm_pro_save_master_settings() {
		$get_shipping_display_mode				= filter_input( INPUT_GET, 'shipping_display_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_chk_enable_logging    				= filter_input( INPUT_GET, 'chk_enable_logging', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_afrsm_force_customer_to_select_sm  = filter_input( INPUT_GET, 'afrsm_force_customer_to_select_sm', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$shipping_display_mode     			= ! empty( $get_shipping_display_mode ) ? sanitize_text_field( wp_unslash( $get_shipping_display_mode ) ) : '';
		$afrsm_force_customer_to_select_sm  = ! empty( $get_afrsm_force_customer_to_select_sm ) ? sanitize_text_field( wp_unslash( $get_afrsm_force_customer_to_select_sm ) ) : '';
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$get_what_to_do                             = filter_input( INPUT_GET, 'what_to_do', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
				$get_combine_default_shipping_with_forceall = filter_input( INPUT_GET, 'combine_default_shipping_with_forceall', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
				$get_forceall_label                         = filter_input( INPUT_GET, 'forceall_label', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
				$get_afrsm_hide_other_shipping              = filter_input( INPUT_GET, 'afrsm_hide_other_shipping', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
				$what_to_do                                 = ! empty( $get_what_to_do ) ? sanitize_text_field( wp_unslash( $get_what_to_do ) ) : '';
				$combine_default_shipping_with_forceall     = ! empty( $get_combine_default_shipping_with_forceall ) ? sanitize_text_field( wp_unslash( $get_combine_default_shipping_with_forceall ) ) : '';
				$forceall_label                             = ! empty( $get_forceall_label ) ? sanitize_text_field( wp_unslash( $get_forceall_label ) ) : '';
				$afrsm_hide_other_shipping                  = ! empty( $get_afrsm_hide_other_shipping ) ? sanitize_text_field( wp_unslash( $get_afrsm_hide_other_shipping ) ) : '';
			}
		}
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				if ( isset( $what_to_do ) && ! empty( $what_to_do ) ) {
					update_option( 'what_to_do_method', $what_to_do );
					if ( 'allow_customer' === $what_to_do ) {
						if ( isset( $shipping_display_mode ) && ! empty( $shipping_display_mode ) ) {
							update_option( 'md_woocommerce_shipping_method_format', $shipping_display_mode );
						}
					} else {
						update_option( 'md_woocommerce_shipping_method_format', 'radio_button_mode' );
					}
					if ( isset( $combine_default_shipping_with_forceall ) && ! empty( $combine_default_shipping_with_forceall ) ) {
						update_option( 'combine_default_shipping_with_forceall', $combine_default_shipping_with_forceall );
					}
					if ( isset( $forceall_label ) && ! empty( $forceall_label ) ) {
						update_option( 'forceall_label', $forceall_label );
					} else {
						update_option( 'forceall_label', '' );
					}
					if ( isset( $afrsm_hide_other_shipping ) && ! empty( $afrsm_hide_other_shipping ) ) {
						update_option( 'afrsm_hide_other_shipping', $afrsm_hide_other_shipping );
					} else {
						update_option( 'afrsm_hide_other_shipping', '' );
					}
				}
			}
		} else {
			if ( isset( $shipping_display_mode ) && ! empty( $shipping_display_mode ) ) {
				update_option( 'md_woocommerce_shipping_method_format', $shipping_display_mode );
			}
		}
		if ( isset( $get_chk_enable_logging ) && ! empty( $get_chk_enable_logging ) ) {
			update_option( 'chk_enable_logging', $get_chk_enable_logging );
		}
		if ( isset( $afrsm_force_customer_to_select_sm ) && ! empty( $afrsm_force_customer_to_select_sm ) ) {
			update_option( 'afrsm_force_customer_to_select_sm', $afrsm_force_customer_to_select_sm );
		} else {
			update_option( 'afrsm_force_customer_to_select_sm', '' );
		}
		wp_die();
	}
	/**
	 * Display textfield and multiselect dropdown based on country, state, zone and etc
	 *
	 * @return string $html
	 * @since 1.0.0
	 *
	 * @uses  afrsm_pro_get_country_list()
	 * @uses  afrsm_pro_get_states_list()
	 * @uses  afrsm_pro_get_zones_list()
	 * @uses  afrsm_pro_get_product_list()
	 * @uses  afrsm_pro_get_varible_product_list__premium_only()
	 * @uses  afrsm_pro_get_category_list()
	 * @uses  afrsm_pro_get_tag_list()
	 * @uses  afrsm_pro_get_sku_list__premium_only()
	 * @uses  afrsm_pro_get_user_list()
	 * @uses  afrsm_pro_get_user_role_list__premium_only()
	 * @uses  afrsm_pro_get_coupon_list__premium_only()
	 * @uses  afrsm_pro_get_advance_flat_rate_class__premium_only()
	 * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
	 *
	 */
	public function afrsm_pro_product_fees_conditions_values_ajax() {
		$get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_count     = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
		$condition     = isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '';
		$count         = isset( $get_count ) ? sanitize_text_field( $get_count ) : '';
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$att_taxonomy = wc_get_attribute_taxonomy_names();
			}
		}
		$html = '';
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				if ( 'country' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_country_list( $count, [], true ) );
				} elseif ( 'state' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_states_list( $count, [], true ) );
				} elseif ( 'city' === $condition ) {
					$html .= 'textarea';	
				} elseif ( 'postcode' === $condition ) {
					$html .= 'textarea';
				} elseif ( 'zone' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_zones_list( $count, [], true ) );
				} elseif ( 'product' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_product_list( $count, [], true ) );
				} elseif ( 'variableproduct' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_varible_product_list__premium_only( $count, [], '', true ) );
				} elseif ( 'category' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_category_list( $count, [], true ) );
				} elseif ( 'tag' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_tag_list( $count, [], true ) );
				} elseif ( in_array( $condition, $att_taxonomy, true ) ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_att_term_list__premium_only( $count, $condition, [], true ) );
				} elseif ( 'sku' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_sku_list__premium_only( $count, [], true ) );
				} elseif ( 'product_qty' === $condition ) {
					$html .= 'input';
				} elseif ( 'user' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_user_list( $count, [], true ) );
				} elseif ( 'user_role' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_user_role_list__premium_only( $count, [], true ) );
				} elseif ( 'last_spent_order' === $condition ) {
					$html .= 'input';
				} elseif ( 'cart_total' === $condition ) {
					$html .= 'input';
				} elseif ( 'cart_totalafter' === $condition ) {
					$html .= 'input';
				} elseif ( 'cart_productspecific' === $condition ) {
					$html .= 'input';
				} elseif ( 'quantity' === $condition ) {
					$html .= 'input';
				} elseif ( 'width' === $condition ) {
					$html .= 'input';
				} elseif ( 'height' === $condition ) {
					$html .= 'input';
				} elseif ( 'length' === $condition ) {
					$html .= 'input';
				} elseif ( 'volume' === $condition ) {
					$html .= 'input';
				} elseif ( 'weight' === $condition ) {
					$html .= 'input';
				} elseif ( 'coupon' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_coupon_list__premium_only( $count, [], true ) );
				} elseif ( 'shipping_class' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_advance_flat_rate_class__premium_only( $count, [], true ) );
				} elseif ( 'payment_method' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_payment__premium_only( $count, [], true ) );
				}
			} else {
				if ( 'country' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_country_list( $count, [], true ) );
				} elseif ( 'state' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_states_list( $count, [], true ) );
				} elseif ( 'postcode' === $condition ) {
					$html .= 'textarea';
				} elseif ( 'zone' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_zones_list( $count, [], true ) );
				} elseif ( 'product' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_product_list( $count, [], true ) );
				} elseif ( 'category' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_category_list( $count, [], true ) );
				} elseif ( 'tag' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_tag_list( $count, [], true ) );
				} elseif ( 'user' === $condition ) {
					$html .= wp_json_encode( $this->afrsm_pro_get_user_list( $count, [], true ) );
				} elseif ( 'cart_total' === $condition ) {
					$html .= 'input';
				} elseif ( 'quantity' === $condition ) {
					$html .= 'input';
				} elseif ( 'width' === $condition ) {
					$html .= 'input';
				} elseif ( 'height' === $condition ) {
					$html .= 'input';
				} elseif ( 'length' === $condition ) {
					$html .= 'input';
				} elseif ( 'volume' === $condition ) {
					$html .= 'input';
				}
			}
		} else {
			if ( 'country' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_country_list( $count, [], true ) );
			} elseif ( 'state' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_states_list( $count, [], true ) );
			} elseif ( 'postcode' === $condition ) {
				$html .= 'textarea';
			} elseif ( 'zone' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_zones_list( $count, [], true ) );
			} elseif ( 'product' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_product_list( $count, [], true ) );
			} elseif ( 'category' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_category_list( $count, [], true ) );
			} elseif ( 'tag' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_tag_list( $count, [], true ) );
			} elseif ( 'user' === $condition ) {
				$html .= wp_json_encode( $this->afrsm_pro_get_user_list( $count, [], true ) );
			} elseif ( 'cart_total' === $condition ) {
				$html .= 'input';
			} elseif ( 'quantity' === $condition ) {
				$html .= 'input';
			} elseif ( 'width' === $condition ) {
				$html .= 'input';
			} elseif ( 'height' === $condition ) {
                $html .= 'input';
            } elseif ( 'length' === $condition ) {
                $html .= 'input';
            } elseif ( 'volume' === $condition ) {
                $html .= 'input';
            }
		}
		/**
		 * Filter for dynamic condition field value.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		echo wp_kses(
			apply_filters( 'afrsm_pro_product_fees_conditions_values_ajax_ft', $html, $condition, $count ),
			Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
		);
		wp_die();// this is required to terminate immediately and return a proper response
	}
	/**
	 * Get country list
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   WC_Countries() class
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_country_list( $count = '', $selected = array(), $json = false ) {
		$countries_obj = new WC_Countries();
		$getCountries  = $countries_obj->__get( 'countries' );
		$html          = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
		if ( ! empty( $getCountries ) ) {
			foreach ( $getCountries as $code => $country ) {
				$selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $code, $selected, true ) ? 'selected=selected' : '';
				$html        .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $country ) . '</option>';
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $getCountries );
		}
		return $html;
	}
	/**
	 * Get the states for a country.
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   WC_Countries::get_allowed_countries()
	 * @uses   WC_Countries::get_states()
	 *
	 */
	public function afrsm_pro_get_states_list( $count = '', $selected = array(), $json = false ) {
		$filter_states = [];
		$countries     = WC()->countries->get_allowed_countries();
		$html          = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_state" multiple="multiple">';
		if ( ! empty( $countries ) && is_array( $countries ) ):
			foreach ( $countries as $key => $val ) {
				$states = WC()->countries->get_states( $key );
				if ( ! empty( $states ) ) {
					foreach ( $states as $state_key => $state_value ) {
						$selectedVal                              = is_array( $selected ) && ! empty( $selected ) && in_array( esc_attr( $key . ':' . $state_key ), $selected, true ) ? 'selected=selected' : '';
						$html                                     .= '<option value="' . esc_attr( $key . ':' . $state_key ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $val . ' -> ' . $state_value ) . '</option>';
						$filter_states[ $key . ':' . $state_key ] = $val . ' -> ' . $state_value;
					}
				}
			}
		endif;
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_states );
		}
		return $html;
	}
	/**
	 * Get all zones list
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_zones_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang   = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$filter_zone    = [];
		
		// WooCommerce shipping zones
		$delivery_zones = WC_Shipping_Zones::get_zones();
		if( !empty($delivery_zones) ){
			foreach ( $delivery_zones as $key => $the_zone ) {
				if ( ! empty( $sitepress ) ) {
					$zone_id = apply_filters( 'wpml_object_id', $key, 'wc_afrsm_zone', true, $default_lang );
				} else {
					$zone_id = $key;
				}
				$filter_zone[ $zone_id ] = sanitize_text_field($the_zone['zone_name']);
			}
		}

		// Plugin's custom zones
		$get_all_zones  = new WP_Query( array(
			'post_type'      => 'wc_afrsm_zone',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		) );
		$get_zones_data = $get_all_zones->posts;
		
		if ( isset( $get_zones_data ) && ! empty( $get_zones_data ) ) {
			foreach ( $get_zones_data as $get_all_zone ) {
				if ( ! empty( $sitepress ) ) {
					$new_zone_id = apply_filters( 'wpml_object_id', $get_all_zone->ID, 'wc_afrsm_zone', true, $default_lang );
				} else {
					$new_zone_id = $get_all_zone->ID;
				}
				$filter_zone[ $new_zone_id ] = get_the_title( $new_zone_id );
			}
		}		

		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_zone );
		}

		$html           = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_zone" multiple="multiple">';
		if ( isset( $filter_zone ) && ! empty( $filter_zone ) ) {
			foreach ( $filter_zone as $get_all_zone_id => $get_all_zone ) {
				if ( ! empty( $sitepress ) ) {
					$new_zone_id = apply_filters( 'wpml_object_id', $get_all_zone_id, 'wc_afrsm_zone', true, $default_lang );
				} else {
					$new_zone_id = $get_all_zone_id;
				}
				$selected                    = array_map( 'intval', $selected );
				$selectedVal                 = is_array( $selected ) && ! empty( $selected ) && in_array( $new_zone_id, $selected, true ) ? 'selected=selected' : '';
				$html                        .= '<option value="' . esc_attr( $new_zone_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_zone ) . '</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}
	/**
	 * Get product list when edit method.
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_product_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang     		= $this->afrsm_pro_get_default_langugae_with_sitpress();
		$get_all_products_count = 900;
		$get_all_products = new WP_Query( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $get_all_products_count,
			'post__in'       => $selected,
		) );
		$html             = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_product product_fees_conditions_values_' . esc_attr( $count ) . '" multiple="multiple">';
		if ( isset( $get_all_products->posts ) && ! empty( $get_all_products->posts ) ) {
			foreach ( $get_all_products->posts as $get_all_product ) {
				if ( ! empty( $sitepress ) ) {
					$new_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
				} else {
					$new_product_id = $get_all_product->ID;
				}
                $product_type = WC_Product_Factory::get_product_type($new_product_id);
                if( 'simple' === $product_type ) {
                    $selected    = array_map( 'intval', $selected );
                    $selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '';
                    if ( '' !== $selectedVal ) {
                        $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                    }
                }
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return [];
		}
		return $html;
	}
	/**
	 * Get variable product list in Advance pricing rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   WC_Product::is_type()
	 *
	 * @since  3.4
	 *
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 */
	public function afrsm_pro_get_product_options( $count = '', $selected = array() ) {
		global $sitepress;
		$default_lang             = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$all_selected_product_ids = array();
		if ( ! empty( $selected ) && is_array( $selected ) ) {
			foreach ( $selected as $product_id ) {
				$_product = wc_get_product( $product_id );

				if ( 'product_variation' === $_product->post_type ) {
					$all_selected_product_ids[] = $_product->get_parent_id(); //parent_id;
				} else {
					$all_selected_product_ids[] = $product_id;
				}
			}
		}

		$all_selected_product_count = 900;
		$get_all_products               = new WP_Query( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $all_selected_product_count,
			'post__in'       => $all_selected_product_ids,
		) );

		$baselang_variation_product_ids = array();
		$defaultlang_simple_product_ids = array();
		$html                           = '';
		$get_all_products               = $get_all_products->posts;

		if ( isset( $get_all_products ) && ! empty( $get_all_products ) ) {
			foreach ( $get_all_products as $get_all_product ) {
				$_product      = wc_get_product( $get_all_product->ID );
				$check_virtual = $this->afrsm_check_product_type_for_admin( $_product );
				if ( true === $check_virtual ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_variation_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_variation_product_id = $value['variation_id'];
							}
							$baselang_variation_product_ids[] = $defaultlang_variation_product_id;
						}
					} else {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_simple_product_id = $get_all_product->ID;
						}
						$defaultlang_simple_product_ids[] = $defaultlang_simple_product_id;
					}
				}
			}
		}
		$baselang_product_ids = array_merge( $baselang_variation_product_ids, $defaultlang_simple_product_ids );

		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {

			foreach ( $baselang_product_ids as $baselang_product_id ) {
				$selected    = array_map( 'intval', $selected );
				$selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $baselang_product_id, $selected, true ) ? 'selected=selected' : '';
				if ( '' !== $selectedVal ) {
					$html .= '<option value="' . esc_attr( $baselang_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
				}
			}
		}

		return $html;
	}
	/**
	 * Get category list in Advance pricing rules
	 *
	 * @param array $selected
	 *
	 * @return string $html
	 * @since  3.4
	 *
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 *
	 */
	public function afrsm_pro_get_category_options__premium_only( $selected = array(), $json = true ) {
		global $sitepress;
		$default_lang         = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$filter_category_list = [];
		$taxonomy             = 'product_cat';
		$post_status          = 'publish';
		$orderby              = 'name';
		$hierarchical         = 1;
		$empty                = 0;
		$args                 = array(
			'post_type'      => 'product',
			'post_status'    => $post_status,
			'taxonomy'       => $taxonomy,
			'orderby'        => $orderby,
			'hierarchical'   => $hierarchical,
			'hide_empty'     => $empty,
			'posts_per_page' => - 1,
		);
		$get_all_categories   = get_categories( $args );
		$html                 = '';
		if ( isset( $get_all_categories ) && ! empty( $get_all_categories ) ) {
			foreach ( $get_all_categories as $get_all_category ) {
				if ( $get_all_category ) {
					if ( ! empty( $sitepress ) ) {
						$new_cat_id = apply_filters( 'wpml_object_id', $get_all_category->term_id, 'product_cat', true, $default_lang );
					} else {
						$new_cat_id = $get_all_category->term_id;
					}
					$category        = get_term_by( 'id', $new_cat_id, 'product_cat' );
					$parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
					if ( ! empty( $selected ) ) {
						$selected    = array_map( 'intval', $selected );
						$selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '';
						if ( $category->parent > 0 ) {
							$html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . '' . $parent_category->name . '->' . $category->name . '</option>';
						} else {
							$html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . $category->name . '</option>';
						}
					} else {
						if ( $category->parent > 0 ) {
							$filter_category_list[ $category->term_id ] = $parent_category->name . '->' . $category->name;
						} else {
							$filter_category_list[ $category->term_id ] = $category->name;
						}
					}
				}
			}
		}
		if ( true === $json ) {
			return wp_json_encode( $this->afrsm_pro_convert_array_to_json( $filter_category_list ) );
		} else {
			return $html;
		}
	}
    /**
	 * Get tag list in Advance pricing rules
	 *
	 * @param array $selected
	 *
	 * @return string $html
	 * @since  3.4
	 *
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 *
	 */
	public function afrsm_pro_get_tag_options__premium_only( $selected = array(), $json = true ) {
		global $sitepress;
		$default_lang         = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$filter_tag_list = [];
		$taxonomy             = 'product_tag';
		$post_status          = 'publish';
		$orderby              = 'name';
		$hierarchical         = 1;
		$empty                = 0;
		$args                 = array(
			'post_type'      => 'product',
			'post_status'    => $post_status,
			'taxonomy'       => $taxonomy,
			'orderby'        => $orderby,
			'hierarchical'   => $hierarchical,
			'hide_empty'     => $empty,
			'posts_per_page' => - 1,
		);
		$get_all_tags   = get_tags( $args );
		$html                 = '';
		if ( isset( $get_all_tags ) && ! empty( $get_all_tags ) ) {
			foreach ( $get_all_tags as $get_all_tag ) {
				if ( $get_all_tag ) {
					if ( ! empty( $sitepress ) ) {
						$new_cat_id = apply_filters( 'wpml_object_id', $get_all_tag->term_id, 'product_tag', true, $default_lang );
					} else {
						$new_cat_id = $get_all_tag->term_id;
					}
					$tag        = get_term_by( 'id', $new_cat_id, 'product_tag' );
					$parent_tag = get_term_by( 'id', $tag->parent, 'product_tag' );
					if ( ! empty( $selected ) ) {
						$selected    = array_map( 'intval', $selected );
						$selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '';
						if ( $tag->parent > 0 ) {
							$html .= '<option value=' . $tag->term_id . ' ' . $selectedVal . '>' . '' . $parent_tag->name . '->' . $tag->name . '</option>';
						} else {
							$html .= '<option value=' . $tag->term_id . ' ' . $selectedVal . '>' . $tag->name . '</option>';
						}
					} else {
						if ( $tag->parent > 0 ) {
							$filter_tag_list[ $tag->term_id ] = $parent_tag->name . '->' . $tag->name;
						} else {
							$filter_tag_list[ $tag->term_id ] = $tag->name;
						}
					}
				}
			}
		}
		if ( true === $json ) {
			return wp_json_encode( $this->afrsm_pro_convert_array_to_json( $filter_tag_list ) );
		} else {
			return $html;
		}
	}
	/**
	 * Get shipping class list
	 *
	 * @param array $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   WC_Shipping::get_shipping_classes()
	 *
	 */
	public function afrsm_pro_get_class_options__premium_only( $selected = array(), $json = true ) {
		$shipping_classes           = WC()->shipping->get_shipping_classes();
		$filter_shipping_class_list = [];
		$html                       = '';
		if ( isset( $shipping_classes ) && ! empty( $shipping_classes ) ) {
			foreach ( $shipping_classes as $shipping_classes_key ) {
				$selectedVal                                               = ! empty( $selected ) && in_array( $shipping_classes_key->slug, $selected, true ) ? 'selected=selected' : '';
				$html                                                      .= '<option value="' . esc_attr( $shipping_classes_key->slug ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $shipping_classes_key->name ) . '</option>';
				$filter_shipping_class_list[ $shipping_classes_key->slug ] = $shipping_classes_key->name;
			}
		}
		if ( true === $json ) {
			return wp_json_encode( $this->afrsm_pro_convert_array_to_json( $filter_shipping_class_list ) );
		} else {
			return $html;
		}
	}
    /**
	 * Get product attribute list
	 *
	 * @param array $selected
	 * @param array $json
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   wc_get_attribute_taxonomy_names()
	 *
	 */
	public function afrsm_get_product_attribute_options__premium_only( $selected = array(), $json = true ) {
        $att_taxonomy = wc_get_attribute_taxonomy_names();
		$filter_prod_attribute_list = [];
		$html                       = '';
		if ( isset( $att_taxonomy ) && ! empty( $att_taxonomy ) ) {
            foreach( $att_taxonomy as $condition ){
                $att_terms         = get_terms( array(
                    'taxonomy'   => $condition,
                    'parent'     => 0,
                    'hide_empty' => true,
                ) );
                $attribute_label = get_taxonomy( $condition )->labels->singular_name;
                foreach ( $att_terms as $att_terms_key ) {
                    $selectedVal = ! empty( $selected ) && in_array( $att_terms_key->slug, $selected, true ) ? 'selected=selected' : '';
                    $html        .= '<option value="' . esc_attr( $att_terms_key->slug ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $attribute_label. " - ". $att_terms_key->name ) . '</option>';
                    $filter_prod_attribute_list[ $att_terms_key->slug ] = $attribute_label. " - ".$att_terms_key->name;
                }
            }
		}
		if ( true === $json ) {
			return wp_json_encode( $this->afrsm_pro_convert_array_to_json( $filter_prod_attribute_list ) );
		} else {
			return $html;
		}
	}
	/**
	 * Get variable product list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   WC_Product::is_type()
	 * @uses   get_available_variations()
	 *
	 * @since  1.0.0
	 *
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 */
	public function afrsm_pro_get_varible_product_list__premium_only( $count = '', $selected = array(), $action = '', $json = false ) {
		global $sitepress;
		$default_lang     = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$post_in = '';
		if ( 'edit' === $action ) {
			$post_in        = $selected;
			$get_varible_product_list_count = -1;
		} else {
			$post_in        = '';
			$get_varible_product_list_count = 10;
		}
		$get_all_products = new WP_Query( array(
			'post_type'      => 'product_variation',
			'post_status'    => 'publish',
			'posts_per_page' => $get_varible_product_list_count,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'post__in'       => $post_in,
		) );
		$html             = '<select id="var-product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_var_product" multiple="multiple">';
		if ( ! empty( $get_all_products->posts ) ) {
			foreach ( $get_all_products->posts as $post ) {
				$_product = wc_get_product( $post->ID );
				if( $_product instanceof WC_Product ) {
					if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
						if ( ! empty( $sitepress ) ) {
							$new_product_id = apply_filters( 'wpml_object_id', $post->ID , 'product', true, $default_lang );
						} else {
							$new_product_id = $post->ID ;
						}
						$selected    = array_map( 'intval', $selected );
						$selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '';
						if ( '' !== $selectedVal ) {
							$html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
						}
					}
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return [];
		}
		return $html;
	}
	/**
	 * Get category list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   get_term_by()
	 *
	 * @since  1.0.0
	 *
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   get_categories()
	 */
	public function afrsm_pro_get_category_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang       = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$filter_categories  = [];
		$taxonomy           = 'product_cat';
		$post_status        = 'publish';
		$orderby            = 'name';
		$hierarchical       = 1;
		$empty              = 0;
		$args               = array(
			'post_type'      => 'product',
			'post_status'    => $post_status,
			'taxonomy'       => $taxonomy,
			'orderby'        => $orderby,
			'hierarchical'   => $hierarchical,
			'hide_empty'     => $empty,
			'posts_per_page' => - 1,
		);
		$get_all_categories = get_categories( $args );
		$html               = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_cat_product" multiple="multiple">';
		if ( isset( $get_all_categories ) && ! empty( $get_all_categories ) ) {
			foreach ( $get_all_categories as $get_all_category ) {
				if ( $get_all_category ) {
					if ( ! empty( $sitepress ) ) {
						$new_cat_id = apply_filters( 'wpml_object_id', $get_all_category->term_id, 'product_cat', true, $default_lang );
					} else {
						$new_cat_id = $get_all_category->term_id;
					}
					$selected        = array_map( 'intval', $selected );
					$selectedVal     = is_array( $selected ) && ! empty( $selected ) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '';
					$category        = get_term_by( 'id', $new_cat_id, 'product_cat' );
					$parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
					if ( $category->parent > 0 ) {
						$html                                    .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $parent_category->name ) . '->' . esc_html( $category->name ) . '</option>';
						$filter_categories[ $category->term_id ] = '#' . $parent_category->name . '->' . $category->name;
					} else {
						$html                                    .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . esc_html( $category->name ) . '</option>';
						$filter_categories[ $category->term_id ] = $category->name;
					}
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_categories );
		}
		return $html;
	}
	/**
	 * Get tag list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   get_term_by()
	 *
	 */
	public function afrsm_pro_get_tag_list( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$filter_tags  = [];
		$taxonomy     = 'product_tag';
		$orderby      = 'name';
		$hierarchical = 1;
		$empty        = 0;
		$args         = array(
			'post_type'        => 'product',
			'post_status'      => 'publish',
			'taxonomy'         => $taxonomy,
			'orderby'          => $orderby,
			'hierarchical'     => $hierarchical,
			'hide_empty'       => $empty,
			'posts_per_page'   => - 1,
			'suppress_filters' => false,
		);
		$get_all_tags = get_categories( $args );
		$html         = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_tag_product" multiple="multiple">';
		if ( isset( $get_all_tags ) && ! empty( $get_all_tags ) ) {
			foreach ( $get_all_tags as $get_all_tag ) {
				if ( $get_all_tag ) {
					if ( ! empty( $sitepress ) ) {
						$new_tag_id = apply_filters( 'wpml_object_id', $get_all_tag->term_id, 'product_tag', true, $default_lang );
					} else {
						$new_tag_id = $get_all_tag->term_id;
					}
					$selected                     = array_map( 'intval', $selected );
					$selectedVal                  = is_array( $selected ) && ! empty( $selected ) && in_array( $new_tag_id, $selected, true ) ? 'selected=selected' : '';
					$tag                          = get_term_by( 'id', $new_tag_id, 'product_tag' );
					$html                         .= '<option value="' . esc_attr( $tag->term_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $tag->name ) . '</option>';
					$filter_tags[ $tag->term_id ] = $tag->name;
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_tags );
		}
		return $html;
	}
	/**
	 * Get sku list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   get_post_meta()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_sku_list__premium_only( $count = '', $selected = array(), $json = false ) {
		global $sitepress;
		$default_lang                   = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$get_sku_products_list_count	=  900;
		$get_products_array             = new WP_Query( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $get_sku_products_list_count,
		) );
		$filter_skus                    = [];
		$products_array                 = $get_products_array->posts;
		$baselang_simple_product_ids    = array();
		$baselang_variation_product_ids = array();
		if ( ! empty( $products_array ) ) {
			foreach ( $products_array as $get_product ) {
				$_product      = wc_get_product( $get_product->ID );
				$check_virtual = $this->afrsm_check_product_type_for_admin( $_product );
				if ( true === $check_virtual ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_variation_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_variation_product_id = $value['variation_id'];
							}
							$baselang_variation_product_ids[] = $defaultlang_variation_product_id;
						}
					} else {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_simple_product_id = $get_product->ID;
						}
						$baselang_simple_product_ids[] = $defaultlang_simple_product_id;
					}
				}
			}
		}
		$baselang_product_ids = array_merge( $baselang_variation_product_ids, $baselang_simple_product_ids );
		$html                 = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_sku_product" multiple="multiple">';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				if ( ! empty( $baselang_product_id ) ) {
					$product_sku = get_post_meta( $baselang_product_id, '_sku', true );
				}
				settype( $product_sku, 'string' );
				$selectedVal = is_array( $selected ) && ! empty( $selected ) && in_array( $product_sku, $selected, true ) ? 'selected=selected' : '';
				if ( ! empty( $product_sku ) || $product_sku !== '' ) {
					$html .= '<option value="' . esc_attr( $product_sku ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $product_sku ) . '</option>';
				}
				if( !empty($product_sku) ){
					$filter_skus[ $product_sku ] = $product_sku;
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_skus );
		}
		return $html;
	}
	/**
	 * Get attribute list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param string $condition
	 * @param array  $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_att_term_list__premium_only( $count = '', $condition = '', $selected = array(), $json = false ) {
		$att_terms         = get_terms( array(
			'taxonomy'   => $condition,
			'parent'     => 0,
			'hide_empty' => false,
		) );
		$filter_attributes = [];
		$html              = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_att_term" multiple="multiple">';
		if ( ! empty( $att_terms ) ) {
			foreach ( $att_terms as $term ) {
				$term_name                       = $term->name;
				$term_slug                       = $term->slug;
				$selectedVal                     = is_array( $selected ) && ! empty( $selected ) && in_array( $term_slug, $selected, true ) ? 'selected=selected' : '';
				$html                            .= '<option value="' . $term_slug . '" ' . $selectedVal . '>' . $term_name . '</option>';
				$filter_attributes[ $term_slug ] = $term_name;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_attributes );
		}
		return $html;
	}
	/**
	 * Get user list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_user_list( $count = '', $selected = array(), $json = false ) {
		$filter_users  = [];
		$get_all_users = get_users();
		$html          = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_user" multiple="multiple">';
		if ( isset( $get_all_users ) && ! empty( $get_all_users ) ) {
			foreach ( $get_all_users as $get_all_user ) {
				$selectedVal                             = is_array( $selected ) && ! empty( $selected ) && in_array( $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '';
				$html                                    .= '<option value="' . esc_attr( $get_all_user->data->ID ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_user->data->user_login ) . '</option>';
				$filter_users[ $get_all_user->data->ID ] = $get_all_user->data->user_login;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_users );
		}
		return $html;
	}
	/**
	 * Get role user list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_user_role_list__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_user_roles = [];
		global $wp_roles;
		$html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_role" multiple="multiple">';
		if ( isset( $wp_roles->roles ) && ! empty( $wp_roles->roles ) ) {
			$defaultSel                 = ! empty( $selected ) && in_array( 'guest', $selected, true ) ? 'selected=selected' : '';
			$html                       .= '<option value="guest" ' . esc_attr( $defaultSel ) . '>' . esc_html__( 'Guest', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</option>';
			$filter_user_roles['guest'] = esc_html__( 'Guest', 'advanced-flat-rate-shipping-for-woocommerce' );
			foreach ( $wp_roles->roles as $user_role_key => $get_all_role ) {
				$selectedVal                         = is_array( $selected ) && ! empty( $selected ) && in_array( $user_role_key, $selected, true ) ? 'selected=selected' : '';
				$html                                .= '<option value="' . esc_attr( $user_role_key ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_role['name'] ) . '</option>';
				$filter_user_roles[ $user_role_key ] = $get_all_role['name'];
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_user_roles );
		}
		return $html;
	}
	/**
	 * Get coupon list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_coupon_list__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_coupon_list = [];
		$get_all_coupon     = new WP_Query( array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		) );
		$html               = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_coupon" multiple="multiple">';
		if ( isset( $get_all_coupon->posts ) && ! empty( $get_all_coupon->posts ) ) {
			foreach ( $get_all_coupon->posts as $get_all_coupon ) {
				$selected                                  = array_map( 'intval', $selected );
				$selectedVal                               = is_array( $selected ) && ! empty( $selected ) && in_array( $get_all_coupon->ID, $selected, true ) ? 'selected=selected' : '';
				$html                                      .= '<option value="' . esc_attr( $get_all_coupon->ID ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_coupon->post_title ) . '</option>';
				$filter_coupon_list[ $get_all_coupon->ID ] = $get_all_coupon->post_title;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_coupon_list );
		}
		return $html;
	}
	/**
	 * Get shipping class list in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   WC_Shipping::get_shipping_classes()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_advance_flat_rate_class__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_rate_class = [];
		$shipping_classes  = WC()->shipping->get_shipping_classes();
		$html              = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_class" multiple="multiple">';
		if ( isset( $shipping_classes ) && ! empty( $shipping_classes ) ) {
			foreach ( $shipping_classes as $shipping_classes_key ) {
				$selectedVal                                      = ! empty( $selected ) && in_array( $shipping_classes_key->slug, $selected, true ) ? 'selected=selected' : '';
				$html                                             .= '<option value="' . esc_attr( $shipping_classes_key->slug ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $shipping_classes_key->name ) . '</option>';
				$filter_rate_class[ $shipping_classes_key->slug ] = $shipping_classes_key->name;
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_rate_class );
		}
		return $html;
	}
	/**
	 * Get payment method in Shipping Method Rules
	 *
	 * @param string $count
	 * @param array  $selected
	 *
	 * @return string $html
	 * @uses   WC_Payment_Gateways::get_available_payment_gateways()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_get_payment__premium_only( $count = '', $selected = array(), $json = false ) {
		$filter_rate_payment = [];
		$gateways            = WC()->payment_gateways->payment_gateways();
		$html                = '<select rel-id="' . $count . '" name="fees[product_fees_conditions_values][value_' . $count . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_payment" multiple="multiple">';
		if ( isset( $gateways ) && ! empty( $gateways ) ) {
			foreach ( $gateways as $gateway ) {
				if ( $gateway->enabled === 'yes' ) {
					$selectedVal                         = ! empty( $selected ) && in_array( $gateway->id, $selected, true ) ? 'selected=selected' : '';
					$html                                .= '<option value="' . $gateway->id . '" ' . $selectedVal . '>' . esc_html__( $gateway->title, 'advanced-flat-rate-shipping-for-woocommerce' ) . '</option>';
					$filter_rate_payment[ $gateway->id ] = esc_html__( $gateway->title, 'advanced-flat-rate-shipping-for-woocommerce' );
				}
			}
		}
		$html .= '</select>';
		if ( $json ) {
			return $this->afrsm_pro_convert_array_to_json( $filter_rate_payment );
		}
		return $html;
	}
	/**
	 * Display product list based product specific option
	 *
	 * @return string $html
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_product_fees_conditions_values_product_ajax() {
		global $sitepress;
		$json                 = true;
		$filter_product_list  = [];
		$default_lang         = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$request_value        = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_value           = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$baselang_product_ids = array();
		function afrsm_pro_posts_where( $where, $wp_query ) {
            global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
				$where            .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
        $product_fees_conditions_count = filter_input( INPUT_GET, '_limit', FILTER_VALIDATE_INT );
        $product_fees_conditions_count = isset( $product_fees_conditions_count ) ? intval( $product_fees_conditions_count ) : 0;
        $product_page = filter_input( INPUT_GET, '_page', FILTER_VALIDATE_INT );
        $product_page = isset( $product_page ) ? intval( $product_page ) : 0;
		$product_args = array(
			'post_type'        => 'product',
			'posts_per_page'   => $product_fees_conditions_count,
			'search_pro_title' => $post_value,
			'post_status'      => 'publish',
			'orderby'          => 'title',
			'order'            => 'ASC',
            'offset'           => $product_fees_conditions_count * ( $product_page - 1 )
		);
		add_filter( 'posts_where', 'afrsm_pro_posts_where', 10, 2 );
		$get_wp_query = new WP_Query( $product_args );
		remove_filter( 'posts_where', 'afrsm_pro_posts_where', 10, 2 );
		$get_all_products = $get_wp_query->posts;

		if ( isset( $get_all_products ) && ! empty( $get_all_products ) ) {
			foreach ( $get_all_products as $get_all_product ) {
				$_product      = wc_get_product( $get_all_product->ID );
				$check_virtual = $this->afrsm_check_product_type_for_admin( $_product );
				if ( true === $check_virtual && $_product->is_type('simple') ) {
					if ( ! empty( $sitepress ) ) {
						$defaultlang_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
					} else {
						$defaultlang_product_id = $get_all_product->ID;
					}
					$baselang_product_ids[] = $defaultlang_product_id;
				}
			}
		}
		$html = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				$html                  .= '<option value="' . esc_attr( $baselang_product_id ) . '">' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
				$filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_product_list );
			wp_die();
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
		wp_die();
	}
    /**
	 * Display all product and variation list.
	 *
	 * @return string $html
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
	 *
	 * @since  1.0.0
	 *
	 */
    public function afrsm_products_list_ajax__premium_only(){
        global $sitepress;
		if ( ! empty( $sitepress ) ) {
			$default_lang 				= $sitepress->get_default_language();
		}
		$json                           = true;
		$filter_product_list            = [];
		$request_value                  = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$posts_per_page                 = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
		$offset                         = filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT );
		$post_value                     = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$posts_per_page                 = isset( $posts_per_page ) ? intval( $posts_per_page ) : 0;
		$offset                         = isset( $offset ) ? intval( $offset ) : 0;
		$baselang_simple_product_ids    = array();
		$baselang_variation_product_ids = array();
        $baselang_product_ids           = array();
		function wdpad_posts_where( $where, $wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where            .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
		$product_args = array(
			'post_type'         => array( 'product', 'product_variation' ),
			'posts_per_page'    => $posts_per_page,
			'search_pro_title'  => $post_value,
			'post_status'       => 'publish',
			'orderby'           => 'title',
			'order'             => 'ASC',
            'offset'            => $posts_per_page * ( $offset - 1 ),
            'fields'            => 'ids'
		);
        
		add_filter( 'posts_where', 'wdpad_posts_where', 10, 2 );
		$get_wp_query = new WP_Query( $product_args );
		remove_filter( 'posts_where', 'wdpad_posts_where', 10, 2 );
		$get_all_products = $get_wp_query->posts;
        
		if ( isset( $get_all_products ) && ! empty( $get_all_products ) ) {
			foreach ( $get_all_products as $get_all_product ) {
				$_product = wc_get_product( $get_all_product );
				if ( ! $_product->is_type( 'variable' ) ) {
                    if ( ! empty( $sitepress ) ) {
                        $defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_all_product, 'product', true, $default_lang );
                    } else {
                        $defaultlang_simple_product_id = $get_all_product;
                    }
                    $baselang_product_ids[] = $defaultlang_simple_product_id;
				}
			}
		}
		$html                 = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				$html                  .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
				$filter_product_list[] = array( $baselang_product_id, '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) );
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_product_list );
			wp_die();
		}
		echo wp_kses( $html, allowed_html_tags() );;
		wp_die();
    }
	/**
	 * Display variable product list based product specific option
	 *
	 * @return string $html
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 * @uses   WC_Product::is_type()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_product_fees_conditions_varible_values_product_ajax__premium_only() {
		global $sitepress;
		$default_lang                 = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$json                         = true;
		$filter_variable_product_list = [];
		$request_value                = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_value                   = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$baselang_product_ids         = array();
		function afrsm_posts_wheres( $where, $wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where            .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
		$product_fees_conditions_varible_count = -1;
		$product_args     = array(
			'post_type'        => 'product',
			'posts_per_page'   => $product_fees_conditions_varible_count,
			'search_pro_title' => $post_value,
			'post_status'      => 'publish',
			'orderby'          => 'title',
			'order'            => 'ASC',
		);
		add_filter( 'posts_where', 'afrsm_posts_wheres', 10, 2 );
		$get_all_products = new WP_Query( $product_args );
		add_filter( 'posts_where', 'afrsm_posts_wheres', 10, 2 );
		if ( ! empty( $get_all_products ) ) {
			foreach ( $get_all_products->posts as $get_all_product ) {
				$_product = wc_get_product( $get_all_product->ID );
				if( $_product instanceof WC_Product ) {
					if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
						if ( $_product->is_type( 'variable' ) ) {
							$variations = $_product->get_available_variations();
							foreach ( $variations as $value ) {
								if ( ! empty( $sitepress ) ) {
									$defaultlang_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
								} else {
									$defaultlang_product_id = $value['variation_id'];
								}
								$baselang_product_ids[] = $defaultlang_product_id;
							}
						}
					}
				}
			}
		}
		$html = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				$html                           .= '<option value="' . esc_attr( $baselang_product_id ) . '">' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
				$filter_variable_product_list[] = array(
					$baselang_product_id,
					get_the_title( $baselang_product_id ),
				);
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_variable_product_list );
			wp_die();
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
		wp_die();
	}
	/**
	 * Display simple and variable product list based product specific option in Advance Pricing Rules
	 *
	 * @return string $html
	 * @uses   afrsm_pro_get_default_langugae_with_sitpress()
	 * @uses   wc_get_product()
	 * @uses   WC_Product::is_type()
	 * @uses   get_available_variations()
	 * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
	 *
	 * @since  3.4
	 *
	 */
	public function afrsm_pro_simple_and_variation_product_list_ajax__premium_only() {
		global $sitepress;
		$default_lang                   = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$json                           = true;
		$filter_product_list            = [];
		$request_value                  = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_value                     = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
		$baselang_simple_product_ids    = array();
		$baselang_variation_product_ids = array();
		function afrsm_pro_posts_where( $where, $wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where            .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}
			return $where;
		}
		$simple_and_variation_product_count = 900;
		$product_args = array(
			'post_type'        => 'product',
			'posts_per_page'   => $simple_and_variation_product_count,
			'search_pro_title' => $post_value,
			'post_status'      => 'publish',
			'orderby'          => 'title',
			'order'            => 'ASC',
		);
		add_filter( 'posts_where', 'afrsm_pro_posts_where', 10, 2 );
		$get_wp_query = new WP_Query( $product_args );
		remove_filter( 'posts_where', 'afrsm_pro_posts_where', 10, 2 );
		$get_all_products = $get_wp_query->posts;
		if ( isset( $get_all_products ) && ! empty( $get_all_products ) ) {
			foreach ( $get_all_products as $get_all_product ) {
				$_product      = wc_get_product( $get_all_product->ID );
				$check_virtual = $this->afrsm_check_product_type_for_admin( $_product );
				if ( true === $check_virtual ) {
					if ( $_product->is_type( 'variable' ) ) {
						$variations = $_product->get_available_variations();
						foreach ( $variations as $value ) {
							if ( ! empty( $sitepress ) ) {
								$defaultlang_variation_product_id = apply_filters( 'wpml_object_id', $value['variation_id'], 'product', true, $default_lang );
							} else {
								$defaultlang_variation_product_id = $value['variation_id'];
							}
							$baselang_variation_product_ids[] = $defaultlang_variation_product_id;
						}
					} else {
						if ( ! empty( $sitepress ) ) {
							$defaultlang_simple_product_id = apply_filters( 'wpml_object_id', $get_all_product->ID, 'product', true, $default_lang );
						} else {
							$defaultlang_simple_product_id = $get_all_product->ID;
						}
						$baselang_simple_product_ids[] = $defaultlang_simple_product_id;
					}
				}
			}
		}
		$baselang_product_ids = array_merge( $baselang_variation_product_ids, $baselang_simple_product_ids );
		$html                 = '';
		if ( isset( $baselang_product_ids ) && ! empty( $baselang_product_ids ) ) {
			foreach ( $baselang_product_ids as $baselang_product_id ) {
				$html                  .= '<option value="' . esc_attr( $baselang_product_id ) . '">' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
				$filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
			}
		}
		if ( $json ) {
			echo wp_json_encode( $filter_product_list );
			wp_die();
		}
		echo wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
		wp_die();
	}
	/**
	 * Delete multiple shipping method
	 *
	 * @return string $result
	 * @uses   wp_delete_post()
	 *
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_wc_multiple_delete_shipping_method() {
		check_ajax_referer( 'dsm_nonce', 'nonce' );
		$result      = 0;
		$get_allVals = filter_input( INPUT_GET, 'allVals', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
		$allVals     = ! empty( $get_allVals ) ? array_map( 'sanitize_text_field', wp_unslash( $get_allVals ) ) : array();
		if ( ! empty( $allVals ) ) {
			foreach ( $allVals as $val ) {
				wp_delete_post( $val );
				$result = 1;
			}
		}
		echo (int) $result;
		wp_die();
	}
	/**
	 * Count total shipping method
	 *
	 * @return int $count_method
	 * @since    3.5
	 *
	 */
	public static function afrsm_pro_sm_count_method() {
		$shipping_method_args = array(
			'post_type'      => self::afrsm_shipping_post_type,
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => - 1,
			'orderby'        => 'ID',
			'order'          => 'DESC',
		);
		$sm_post_query        = new WP_Query( $shipping_method_args );
		$shipping_method_list = $sm_post_query->posts;
		return count( $shipping_method_list );
	}
	/**
	 * Save shipping method
	 *
	 * @param array $post
	 *
	 * @return bool false if post is empty otherwise it will redirect to shipping method list
	 * @since  1.0.0
	 *
	 * @uses   update_post_meta()
	 *
	 */
	function afrsm_pro_fees_conditions_save( $post ) {
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				global $sitepress;
			}
		}
		$default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
		if ( empty( $post ) ) {
			return false;
		}
        
		$action                    = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$post_type                 = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$afrsm_pro_conditions_save = filter_input( INPUT_POST, 'afrsm_pro_conditions_save', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( isset( $post_type ) && self::afrsm_shipping_post_type === sanitize_text_field( $post['post_type'] ) && wp_verify_nonce( sanitize_text_field( $afrsm_pro_conditions_save ), 'afrsm_pro_save_action' ) ) {
            $demo = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro();
			$method_id                          = filter_input( INPUT_POST, 'fee_post_id', FILTER_SANITIZE_NUMBER_INT );
			$fees                               = filter_input( INPUT_POST, 'fees', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
			$sm_status                          = filter_input( INPUT_POST, 'sm_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$fee_settings_product_fee_title     = filter_input( INPUT_POST, 'fee_settings_product_fee_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_condition_key                  = filter_input( INPUT_POST, 'condition_key', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
			$get_sm_product_cost                = filter_input( INPUT_POST, 'sm_product_cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_free_shipping_based_on      = filter_input( INPUT_POST, 'sm_free_shipping_based_on', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_is_allow_free_shipping         = filter_input( INPUT_POST, 'is_allow_free_shipping', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_free_shipping_cost          = filter_input( INPUT_POST, 'sm_free_shipping_cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_free_shipping_coupan_cost   = filter_input( INPUT_POST, 'sm_free_shipping_coupan_cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_free_shipping_label         = filter_input( INPUT_POST, 'sm_free_shipping_label', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_tooltip_type                = filter_input( INPUT_POST, 'sm_tooltip_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_tooltip_desc                = filter_input( INPUT_POST, 'sm_tooltip_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_select_log_in_user          = filter_input( INPUT_POST, 'sm_select_log_in_user', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_select_first_order_for_user = filter_input( INPUT_POST, 'sm_select_first_order_for_user', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_select_selected_shipping    = filter_input( INPUT_POST, 'sm_select_selected_shipping', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_select_taxable              = filter_input( INPUT_POST, 'sm_select_taxable', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_select_shipping_provider    = filter_input( INPUT_POST, 'sm_select_shipping_provider', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_sm_extra_cost                  = filter_input( INPUT_POST, 'sm_extra_cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
			$get_sm_extra_cost_calculation_type = filter_input( INPUT_POST, 'sm_extra_cost_calculation_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$sm_product_cost                    = isset( $get_sm_product_cost ) ? sanitize_text_field( $get_sm_product_cost ) : '';
			$sm_free_shipping_based_on          = isset( $get_sm_free_shipping_based_on ) ? sanitize_text_field( $get_sm_free_shipping_based_on ) : '';
			$is_allow_free_shipping             = isset( $get_is_allow_free_shipping ) ? sanitize_text_field( $get_is_allow_free_shipping ) : '';
			$sm_free_shipping_cost              = isset( $get_sm_free_shipping_cost ) ? sanitize_text_field( $get_sm_free_shipping_cost ) : '';
			$sm_free_shipping_coupan_cost       = isset( $get_sm_free_shipping_coupan_cost ) ? sanitize_text_field( $get_sm_free_shipping_coupan_cost ) : '';
			$sm_tooltip_type                    = isset( $get_sm_tooltip_type ) ? sanitize_text_field( $get_sm_tooltip_type ) : '';
			$sm_tooltip_desc                    = isset( $get_sm_tooltip_desc ) ? sanitize_textarea_field( substr( $get_sm_tooltip_desc, 0, 100 ) ) : '';
			$sm_select_log_in_user              = isset( $get_sm_select_log_in_user ) ? sanitize_text_field( $get_sm_select_log_in_user ) : '';
			$sm_select_first_order_for_user     = isset( $get_sm_select_first_order_for_user ) ? sanitize_text_field( $get_sm_select_first_order_for_user ) : 'no';
			$sm_select_selected_shipping     	= isset( $get_sm_select_selected_shipping ) ? sanitize_text_field( $get_sm_select_selected_shipping ) : '';
			$sm_select_taxable                  = isset( $get_sm_select_taxable ) ? sanitize_text_field( $get_sm_select_taxable ) : '';
			$sm_select_shipping_provider        = isset( $get_sm_select_shipping_provider ) ? sanitize_text_field( $get_sm_select_shipping_provider ) : '';
			$sm_extra_cost                      = isset( $get_sm_extra_cost ) ? array_map( 'sanitize_text_field', $get_sm_extra_cost ) : array();
			$sm_extra_cost_calculation_type     = isset( $get_sm_extra_cost_calculation_type ) ? sanitize_text_field( $get_sm_extra_cost_calculation_type ) : '';
			$get_cost_on_total_cart_weight_status       = filter_input( INPUT_POST, 'cost_on_total_cart_weight_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$get_cost_on_total_cart_subtotal_status     = filter_input( INPUT_POST, 'cost_on_total_cart_subtotal_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$cost_on_total_cart_weight_status           = isset( $get_cost_on_total_cart_weight_status ) ? sanitize_text_field( $get_cost_on_total_cart_weight_status ) : 'off';
			$cost_on_total_cart_subtotal_status         = isset( $get_cost_on_total_cart_subtotal_status ) ? sanitize_text_field( $get_cost_on_total_cart_subtotal_status ) : 'off';
			$get_ap_rule_status                         = filter_input( INPUT_POST, 'ap_rule_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			$ap_rule_status                             = isset( $get_ap_rule_status ) ? sanitize_text_field( $get_ap_rule_status ) : "off";
			if ( afrsfw_fs()->is__premium_only() ) {
				if ( afrsfw_fs()->can_use_premium_code() ) {
					$get_how_to_apply                           = filter_input( INPUT_POST, 'how_to_apply', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_fee_chk_qty_price                   = filter_input( INPUT_POST, 'sm_fee_chk_qty_price', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_fee_per_qty                         = filter_input( INPUT_POST, 'sm_fee_per_qty', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_extra_product_cost                  = filter_input( INPUT_POST, 'sm_extra_product_cost', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					$get_sm_estimation_delivery                 = filter_input( INPUT_POST, 'sm_estimation_delivery', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_start_date                          = filter_input( INPUT_POST, 'sm_start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_end_date                            = filter_input( INPUT_POST, 'sm_end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_product_status                 = filter_input( INPUT_POST, 'cost_on_product_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_product_weight_status          = filter_input( INPUT_POST, 'cost_on_product_weight_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_product_subtotal_status        = filter_input( INPUT_POST, 'cost_on_product_subtotal_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_category_status                = filter_input( INPUT_POST, 'cost_on_category_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_category_weight_status         = filter_input( INPUT_POST, 'cost_on_category_weight_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_category_subtotal_status       = filter_input( INPUT_POST, 'cost_on_category_subtotal_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $get_cost_on_tag_status                     = filter_input( INPUT_POST, 'cost_on_tag_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $get_cost_on_tag_subtotal_status            = filter_input( INPUT_POST, 'cost_on_tag_subtotal_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_tag_weight_status              = filter_input( INPUT_POST, 'cost_on_tag_weight_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_total_cart_qty_status          = filter_input( INPUT_POST, 'cost_on_total_cart_qty_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $get_cost_on_shipping_class_status          = filter_input( INPUT_POST, 'cost_on_shipping_class_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_shipping_class_weight_status   = filter_input( INPUT_POST, 'cost_on_shipping_class_weight_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_shipping_class_subtotal_status = filter_input( INPUT_POST, 'cost_on_shipping_class_subtotal_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_on_product_attribute_status       = filter_input( INPUT_POST, 'cost_on_product_attribute_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_select_day_of_week                  = filter_input( INPUT_POST, 'sm_select_day_of_week', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
					$get_sm_time_from                           = filter_input( INPUT_POST, 'sm_time_from', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_time_to                             = filter_input( INPUT_POST, 'sm_time_to', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_cost_rule_match                        = filter_input( INPUT_POST, 'cost_rule_match', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
					$get_main_rule_condition                    = filter_input( INPUT_POST, 'main_rule_condition', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_fee_settings_unique_shipping_title     = filter_input( INPUT_POST, 'fee_settings_unique_shipping_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_free_shipping_cost_before_discount  = filter_input( INPUT_POST, 'sm_free_shipping_cost_before_discount', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_free_shipping_cost_left_notice 		= filter_input( INPUT_POST, 'sm_free_shipping_cost_left_notice', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_free_shipping_cost_left_notice_msg	= filter_input( INPUT_POST, 'sm_free_shipping_cost_left_notice_msg', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$fee_settings_unique_shipping_title         = isset( $get_fee_settings_unique_shipping_title ) ? sanitize_text_field( $get_fee_settings_unique_shipping_title ) : '';
					$how_to_apply                               = isset( $get_how_to_apply ) ? sanitize_text_field( $get_how_to_apply ) : '';
					$sm_free_shipping_label                     = isset( $get_sm_free_shipping_label ) ? sanitize_text_field( $get_sm_free_shipping_label ) : '';
					$sm_fee_chk_qty_price                       = isset( $get_sm_fee_chk_qty_price ) ? sanitize_text_field( $get_sm_fee_chk_qty_price ) : '';
					$sm_fee_per_qty                             = isset( $get_sm_fee_per_qty ) ? sanitize_text_field( $get_sm_fee_per_qty ) : '';
					$sm_extra_product_cost                      = isset( $get_sm_extra_product_cost ) ? sanitize_text_field( $get_sm_extra_product_cost ) : 0;
					$sm_estimation_delivery                     = isset( $get_sm_estimation_delivery ) ? sanitize_text_field( $get_sm_estimation_delivery ) : '';
					$sm_start_date                              = isset( $get_sm_start_date ) ? sanitize_text_field( $get_sm_start_date ) : '';
					$sm_end_date                                = isset( $get_sm_end_date ) ? sanitize_text_field( $get_sm_end_date ) : '';
					$sm_free_shipping_cost_before_discount      = isset( $get_sm_free_shipping_cost_before_discount ) ? sanitize_text_field( $get_sm_free_shipping_cost_before_discount ) : '';
					$sm_free_shipping_cost_left_notice 			= isset( $get_sm_free_shipping_cost_left_notice ) ? sanitize_text_field( $get_sm_free_shipping_cost_left_notice ) : '';
					$sm_free_shipping_cost_left_notice_msg		= isset( $get_sm_free_shipping_cost_left_notice_msg ) ? sanitize_text_field( $get_sm_free_shipping_cost_left_notice_msg ) : '';
					$cost_on_product_status                     = isset( $get_cost_on_product_status ) ? sanitize_text_field( $get_cost_on_product_status ) : 'off';
					$cost_on_product_weight_status              = isset( $get_cost_on_product_weight_status ) ? sanitize_text_field( $get_cost_on_product_weight_status ) : 'off';
					$cost_on_product_subtotal_status            = isset( $get_cost_on_product_subtotal_status ) ? sanitize_text_field( $get_cost_on_product_subtotal_status ) : 'off';
					$cost_on_category_status                    = isset( $get_cost_on_category_status ) ? sanitize_text_field( $get_cost_on_category_status ) : 'off';
					$cost_on_category_weight_status             = isset( $get_cost_on_category_weight_status ) ? sanitize_text_field( $get_cost_on_category_weight_status ) : 'off';
					$cost_on_category_subtotal_status           = isset( $get_cost_on_category_subtotal_status ) ? sanitize_text_field( $get_cost_on_category_subtotal_status ) : 'off';
                    $cost_on_tag_status                         = isset( $get_cost_on_tag_status ) ? sanitize_text_field( $get_cost_on_tag_status ) : 'off';
					$cost_on_tag_subtotal_status                = isset( $get_cost_on_tag_subtotal_status ) ? sanitize_text_field( $get_cost_on_tag_subtotal_status ) : 'off';
					$cost_on_tag_weight_status                  = isset( $get_cost_on_tag_weight_status ) ? sanitize_text_field( $get_cost_on_tag_weight_status ) : 'off';
					$cost_on_total_cart_qty_status              = isset( $get_cost_on_total_cart_qty_status ) ? sanitize_text_field( $get_cost_on_total_cart_qty_status ) : 'off';
					$cost_on_shipping_class_status              = isset( $get_cost_on_shipping_class_status ) ? sanitize_text_field( $get_cost_on_shipping_class_status ) : 'off';
					$cost_on_shipping_class_weight_status       = isset( $get_cost_on_shipping_class_weight_status ) ? sanitize_text_field( $get_cost_on_shipping_class_weight_status ) : 'off';
					$cost_on_shipping_class_subtotal_status     = isset( $get_cost_on_shipping_class_subtotal_status ) ? sanitize_text_field( $get_cost_on_shipping_class_subtotal_status ) : 'off';
					$cost_on_product_attribute_status           = isset( $get_cost_on_product_attribute_status ) ? sanitize_text_field( $get_cost_on_product_attribute_status ) : 'off';
					$sm_select_day_of_week                      = isset( $get_sm_select_day_of_week ) ? array_map( 'sanitize_text_field', $get_sm_select_day_of_week ) : array();
					$sm_time_from                               = isset( $get_sm_time_from ) ? sanitize_text_field( $get_sm_time_from ) : '';
					$sm_time_to                                 = isset( $get_sm_time_to ) ? sanitize_text_field( $get_sm_time_to ) : '';
					$cost_rule_match                            = isset( $get_cost_rule_match ) ? array_map( 'sanitize_text_field', $get_cost_rule_match ) : array();
					$main_rule_condition                        = isset( $get_main_rule_condition ) ? sanitize_text_field( $get_main_rule_condition ) : '';

					$get_is_allow_custom_weight_base	    = filter_input( INPUT_POST, 'is_allow_custom_weight_base', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_custom_weight_base_cost		    = filter_input( INPUT_POST, 'sm_custom_weight_base_cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_custom_weight_base_per_each	    = filter_input( INPUT_POST, 'sm_custom_weight_base_per_each', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_custom_weight_base_over		    = filter_input( INPUT_POST, 'sm_custom_weight_base_over', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_is_allow_custom_qty_base		    = filter_input( INPUT_POST, 'is_allow_custom_qty_base', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_custom_qty_base_cost		    = filter_input( INPUT_POST, 'sm_custom_qty_base_cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_custom_qty_base_per_each	    = filter_input( INPUT_POST, 'sm_custom_qty_base_per_each', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_custom_qty_base_over		    = filter_input( INPUT_POST, 'sm_custom_qty_base_over', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					$get_sm_free_shipping_based_on_product  = filter_input( INPUT_POST, 'sm_free_shipping_based_on_product', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
					$get_sm_free_shipping_exclude_product   = filter_input( INPUT_POST, 'sm_free_shipping_exclude_product', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
                    $get_is_free_shipping_exclude_prod		= filter_input( INPUT_POST, 'is_free_shipping_exclude_prod', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
					
                    $is_allow_custom_weight_base        = isset( $get_is_allow_custom_weight_base ) ? sanitize_text_field( $get_is_allow_custom_weight_base ) : '';
					$sm_custom_weight_base_cost         = isset( $get_sm_custom_weight_base_cost ) ? sanitize_text_field( $get_sm_custom_weight_base_cost ) : '';
					$sm_custom_weight_base_per_each     = isset( $get_sm_custom_weight_base_per_each ) ? sanitize_text_field( $get_sm_custom_weight_base_per_each ) : '';
					$sm_custom_weight_base_over         = isset( $get_sm_custom_weight_base_over ) ? sanitize_text_field( $get_sm_custom_weight_base_over ) : '';
					$is_allow_custom_qty_base           = isset( $get_is_allow_custom_qty_base ) ? sanitize_text_field( $get_is_allow_custom_qty_base ) : '';
					$sm_custom_qty_base_cost            = isset( $get_sm_custom_qty_base_cost ) ? sanitize_text_field( $get_sm_custom_qty_base_cost ) : '';
					$sm_custom_qty_base_per_each        = isset( $get_sm_custom_qty_base_per_each ) ? sanitize_text_field( $get_sm_custom_qty_base_per_each ) : '';
					$sm_custom_qty_base_over            = isset( $get_sm_custom_qty_base_over ) ? sanitize_text_field( $get_sm_custom_qty_base_over ) : '';
					$sm_free_shipping_based_on_product  = isset( $get_sm_free_shipping_based_on_product ) ? array_map( 'sanitize_text_field', $get_sm_free_shipping_based_on_product ) : array();
					$sm_free_shipping_exclude_product   = isset( $get_sm_free_shipping_exclude_product ) ? array_map( 'sanitize_text_field', $get_sm_free_shipping_exclude_product ) : array();
                    $is_free_shipping_exclude_prod      = isset( $get_is_free_shipping_exclude_prod ) ? sanitize_text_field( $get_is_free_shipping_exclude_prod ) : 'off';
				}else{
					$sm_free_shipping_label = 'Free Shipping';
				}
			}else{
				$sm_free_shipping_label = 'Free Shipping';
			}
			$shipping_method_count = self::afrsm_pro_sm_count_method();
			settype( $method_id, 'integer' );
			if ( isset( $sm_status ) ) {
				$post_status = 'publish';
			} else {
				$post_status = 'draft';
			}
			if ( '' !== $method_id && 0 !== $method_id ) {
				$fee_post  = array(
					'ID'          => $method_id,
					'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
					'post_status' => $post_status,
					'post_type'   => self::afrsm_shipping_post_type,
				);
				$method_id = wp_update_post( $fee_post );
			} else {
				$fee_post  = array(
					'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
					'post_status' => $post_status,
					'menu_order'  => $shipping_method_count + 1,
					'post_type'   => self::afrsm_shipping_post_type,
				);
				$method_id = wp_insert_post( $fee_post );
			}
			if ( '' !== $method_id && 0 !== $method_id ) {
				if ( $method_id > 0 ) {
                    
					$feesArray               = array();
					$conditions_values_array = array();
					$condition_key           = isset( $get_condition_key ) ? $get_condition_key : array();
					$fees_conditions         = $fees['product_fees_conditions_condition'];
					$conditions_is           = $fees['product_fees_conditions_is'];
					$conditions_values       = isset( $fees['product_fees_conditions_values'] ) && ! empty( $fees['product_fees_conditions_values'] ) ? $fees['product_fees_conditions_values'] : array();
					$size                    = count( $fees_conditions );
					foreach ( array_keys( $condition_key ) as $key ) {
						if ( ! array_key_exists( $key, $conditions_values ) ) {
							$conditions_values[ $key ] = array();
						}
					}
                    //We have comment this uksort as in duplicate it's change order of value while saving, because we duplicate new value after main rule which duplicated which add new counter after duplicated value.
					// uksort( $conditions_values, 'strnatcmp' );
					foreach ( $conditions_values as $v ) {
						$conditions_values_array[] = $v;
					}
					for ( $i = 0; $i < $size; $i ++ ) {
						$feesArray[] = array(
							'product_fees_conditions_condition' => $fees_conditions[ $i ],
							'product_fees_conditions_is'        => $conditions_is[ $i ],
							'product_fees_conditions_values'    => $conditions_values_array[ $i ],
						);
					}
					update_post_meta( $method_id, 'sm_product_cost', $sm_product_cost );
					update_post_meta( $method_id, 'is_allow_free_shipping', $is_allow_free_shipping );
					update_post_meta( $method_id, 'sm_free_shipping_based_on', $sm_free_shipping_based_on );
					update_post_meta( $method_id, 'sm_free_shipping_cost', $sm_free_shipping_cost );
					update_post_meta( $method_id, 'sm_free_shipping_coupan_cost', $sm_free_shipping_coupan_cost );
					update_post_meta( $method_id, 'sm_free_shipping_label', $sm_free_shipping_label );
					update_post_meta( $method_id, 'sm_tooltip_type', $sm_tooltip_type );
					update_post_meta( $method_id, 'sm_tooltip_desc', $sm_tooltip_desc );
					update_post_meta( $method_id, 'sm_select_log_in_user', $sm_select_log_in_user );
					update_post_meta( $method_id, 'sm_select_first_order_for_user', $sm_select_first_order_for_user );
					update_post_meta( $method_id, 'sm_select_selected_shipping', $sm_select_selected_shipping );
					update_post_meta( $method_id, 'sm_select_taxable', $sm_select_taxable );
					update_post_meta( $method_id, 'sm_select_shipping_provider', $sm_select_shipping_provider );
					update_post_meta( $method_id, 'sm_metabox', $feesArray );
					update_post_meta( $method_id, 'sm_extra_cost', $sm_extra_cost );
					update_post_meta( $method_id, 'sm_extra_cost_calculation_type', $sm_extra_cost_calculation_type );

					$ap_total_cart_weight_arr       = array();
					$ap_total_cart_subtotal_arr     = array();
					//Total cart weight
					if ( isset( $fees['ap_total_cart_weight_fees_conditions_condition'] ) ) {
						$fees_total_cart_weight               = $fees['ap_total_cart_weight_fees_conditions_condition'];
						$fees_ap_total_cart_weight_min_weight = $fees['ap_fees_ap_total_cart_weight_min_weight'];
						$fees_ap_total_cart_weight_max_weight = $fees['ap_fees_ap_total_cart_weight_max_weight'];
						$fees_ap_price_total_cart_weight      = $fees['ap_fees_ap_price_total_cart_weight'];
						$total_cart_weight_arr                = array();
						foreach ( $fees_total_cart_weight as $fees_total_cart_weight_val ) {
							$total_cart_weight_arr[] = $fees_total_cart_weight_val;
						}
						$size_total_cart_weight_cond = count( $fees_total_cart_weight );
						if ( ! empty( $size_total_cart_weight_cond ) && $size_total_cart_weight_cond > 0 ):
							for ( $total_cart_weight_cnt = 0; $total_cart_weight_cnt < $size_total_cart_weight_cond; $total_cart_weight_cnt ++ ) {
								if ( ! empty( $total_cart_weight_arr ) && '' !== $total_cart_weight_arr ) {
									foreach ( $total_cart_weight_arr as $total_cart_weight_key => $total_cart_weight_val ) {
										if ( $total_cart_weight_key === $total_cart_weight_cnt ) {
											$ap_total_cart_weight_arr[] = array(
												'ap_fees_total_cart_weight'               => $total_cart_weight_val,
												'ap_fees_ap_total_cart_weight_min_weight' => $fees_ap_total_cart_weight_min_weight[ $total_cart_weight_cnt ],
												'ap_fees_ap_total_cart_weight_max_weight' => $fees_ap_total_cart_weight_max_weight[ $total_cart_weight_cnt ],
												'ap_fees_ap_price_total_cart_weight'      => $fees_ap_price_total_cart_weight[ $total_cart_weight_cnt ],
											);
										}
									}
								}
							}
						endif;
					}
					//Cart subtotal
					if ( isset( $fees['ap_total_cart_subtotal_fees_conditions_condition'] ) ) {
						$fees_total_cart_subtotal                 = $fees['ap_total_cart_subtotal_fees_conditions_condition'];
						$fees_ap_total_cart_subtotal_min_subtotal = $fees['ap_fees_ap_total_cart_subtotal_min_subtotal'];
						$fees_ap_total_cart_subtotal_max_subtotal = $fees['ap_fees_ap_total_cart_subtotal_max_subtotal'];
						$fees_ap_price_total_cart_subtotal        = $fees['ap_fees_ap_price_total_cart_subtotal'];
						$total_cart_subtotal_arr                  = array();
						foreach ( $fees_total_cart_subtotal as $total_cart_subtotal_key => $total_cart_subtotal_val ) {
							$total_cart_subtotal_arr[] = $total_cart_subtotal_val;
						}
						$size_total_cart_subtotal_cond = count( $fees_total_cart_subtotal );
						if ( ! empty( $size_total_cart_subtotal_cond ) && $size_total_cart_subtotal_cond > 0 ):
							for ( $total_cart_subtotal_cnt = 0; $total_cart_subtotal_cnt < $size_total_cart_subtotal_cond; $total_cart_subtotal_cnt ++ ) {
								if ( ! empty( $total_cart_subtotal_arr ) && $total_cart_subtotal_arr !== '' ) {
									foreach ( $total_cart_subtotal_arr as $total_cart_subtotal_key => $total_cart_subtotal_val ) {
										if ( $total_cart_subtotal_key === $total_cart_subtotal_cnt ) {
											$ap_total_cart_subtotal_arr[] = array(
												'ap_fees_total_cart_subtotal'                 => $total_cart_subtotal_val,
												'ap_fees_ap_total_cart_subtotal_min_subtotal' => $fees_ap_total_cart_subtotal_min_subtotal[ $total_cart_subtotal_cnt ],
												'ap_fees_ap_total_cart_subtotal_max_subtotal' => $fees_ap_total_cart_subtotal_max_subtotal[ $total_cart_subtotal_cnt ],
												'ap_fees_ap_price_total_cart_subtotal'        => $fees_ap_price_total_cart_subtotal[ $total_cart_subtotal_cnt ],
											);
										}
									}
								}
							}
						endif;
					}
					update_post_meta( $method_id, 'cost_on_total_cart_weight_status', $cost_on_total_cart_weight_status );
					update_post_meta( $method_id, 'cost_on_total_cart_subtotal_status', $cost_on_total_cart_subtotal_status );
					update_post_meta( $method_id, 'sm_metabox_ap_total_cart_weight', $ap_total_cart_weight_arr );
					update_post_meta( $method_id, 'sm_metabox_ap_total_cart_subtotal', $ap_total_cart_subtotal_arr );
					update_post_meta( $method_id, 'ap_rule_status', $ap_rule_status );
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							$ap_product_arr                 = array();
							$ap_product_weight_arr          = array();
							$ap_product_subtotal_arr        = array();
							$ap_category_arr                = array();
							$ap_category_weight_arr         = array();
							$ap_category_subtotal_arr       = array();
                            $ap_tag_arr                     = array();
                            $ap_tag_subtotal_arr            = array();
                            $ap_tag_weight_arr              = array();
							$ap_total_cart_qty_arr          = array();
                            $ap_shipping_class_arr          = array();
							$ap_shipping_class_weight_arr   = array();
							$ap_shipping_class_subtotal_arr = array();
							$ap_product_attribute_arr       = array();
							$ap_class_arr                   = array();
							//qty for Multiple product
							if ( isset( $fees['ap_product_fees_conditions_condition'] ) ) {
								$fees_products         = $fees['ap_product_fees_conditions_condition'];
								$fees_ap_prd_min_qty   = $fees['ap_fees_ap_prd_min_qty'];
								$fees_ap_prd_max_qty   = $fees['ap_fees_ap_prd_max_qty'];
								$fees_ap_price_product = $fees['ap_fees_ap_price_product'];
								$prd_arr               = array();
								foreach ( $fees_products as $fees_prd_val ) {
									$prd_arr[] = $fees_prd_val;
								}
								$size_product_cond = count( $fees_products );
								if ( ! empty( $size_product_cond ) && $size_product_cond > 0 ):
									for ( $product_cnt = 0; $product_cnt < $size_product_cond; $product_cnt ++ ) {
										foreach ( $prd_arr as $prd_key => $prd_val ) {
											if ( $prd_key === $product_cnt ) {
												$ap_product_arr[] = array(
													'ap_fees_products'         => $prd_val,
													'ap_fees_ap_prd_min_qty'   => $fees_ap_prd_min_qty[ $product_cnt ],
													'ap_fees_ap_prd_max_qty'   => $fees_ap_prd_max_qty[ $product_cnt ],
													'ap_fees_ap_price_product' => $fees_ap_price_product[ $product_cnt ],
												);
											}
										}
									}
								endif;
							}
							//product subtotal
							if ( isset( $fees['ap_product_subtotal_fees_conditions_condition'] ) ) {
								$fees_product_subtotal            = $fees['ap_product_subtotal_fees_conditions_condition'];
								$fees_ap_product_subtotal_min_qty = $fees['ap_fees_ap_product_subtotal_min_subtotal'];
								$fees_ap_product_subtotal_max_qty = $fees['ap_fees_ap_product_subtotal_max_subtotal'];
								$fees_ap_product_subtotal_price   = $fees['ap_fees_ap_price_product_subtotal'];
								$product_subtotal_arr             = array();
								foreach ( $fees_product_subtotal as $fees_product_subtotal_val ) {
									$product_subtotal_arr[] = $fees_product_subtotal_val;
								}
								$size_product_subtotal_cond = count( $fees_product_subtotal );
								if ( ! empty( $size_product_subtotal_cond ) && $size_product_subtotal_cond > 0 ):
									for ( $product_subtotal_cnt = 0; $product_subtotal_cnt < $size_product_subtotal_cond; $product_subtotal_cnt ++ ) {
										if ( ! empty( $product_subtotal_arr ) && '' !== $product_subtotal_arr ) {
											foreach ( $product_subtotal_arr as $product_subtotal_key => $product_subtotal_val ) {
												if ( $product_subtotal_key === $product_subtotal_cnt ) {
													$ap_product_subtotal_arr[] = array(
														'ap_fees_product_subtotal'                 => $product_subtotal_val,
														'ap_fees_ap_product_subtotal_min_subtotal' => $fees_ap_product_subtotal_min_qty[ $product_subtotal_cnt ],
														'ap_fees_ap_product_subtotal_max_subtotal' => $fees_ap_product_subtotal_max_qty[ $product_subtotal_cnt ],
														'ap_fees_ap_price_product_subtotal'        => $fees_ap_product_subtotal_price[ $product_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							//qty for Multiple category
							if ( isset( $fees['ap_category_fees_conditions_condition'] ) ) {
								$fees_categories        = $fees['ap_category_fees_conditions_condition'];
								$fees_ap_cat_min_qty    = $fees['ap_fees_ap_cat_min_qty'];
								$fees_ap_cat_max_qty    = $fees['ap_fees_ap_cat_max_qty'];
								$fees_ap_price_category = $fees['ap_fees_ap_price_category'];
								$cat_arr                = array();
								foreach ( $fees_categories as $fees_cat_val ) {
									$cat_arr[] = $fees_cat_val;
								}
								$size_category_cond = count( $fees_categories );
								if ( ! empty( $size_category_cond ) && $size_category_cond > 0 ):
									for ( $category_cnt = 0; $category_cnt < $size_category_cond; $category_cnt ++ ) {
										if ( ! empty( $cat_arr ) && '' !== $cat_arr ) {
											foreach ( $cat_arr as $cat_key => $cat_val ) {
												if ( $cat_key === $category_cnt ) {
													$ap_category_arr[] = array(
														'ap_fees_categories'        => $cat_val,
														'ap_fees_ap_cat_min_qty'    => $fees_ap_cat_min_qty[ $category_cnt ],
														'ap_fees_ap_cat_max_qty'    => $fees_ap_cat_max_qty[ $category_cnt ],
														'ap_fees_ap_price_category' => $fees_ap_price_category[ $category_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
                            //qty for Multiple tag
							if ( isset( $fees['ap_tag_fees_conditions_condition'] ) ) {
								$fees_tags              = $fees['ap_tag_fees_conditions_condition'];
								$fees_ap_tag_min_qty    = $fees['ap_fees_ap_tag_min_qty'];
								$fees_ap_tag_max_qty    = $fees['ap_fees_ap_tag_max_qty'];
								$fees_ap_price_tag      = $fees['ap_fees_ap_price_tag'];
								$tag_arr                = array();
								foreach ( $fees_tags as $fees_tag_val ) {
									$tag_arr[] = $fees_tag_val;
								}
								$size_tag_cond = count( $fees_tags );
								if ( ! empty( $size_tag_cond ) && $size_tag_cond > 0 ):
									for ( $tag_cnt = 0; $tag_cnt < $size_tag_cond; $tag_cnt ++ ) {
										if ( ! empty( $tag_arr ) && '' !== $tag_arr ) {
											foreach ( $tag_arr as $tag_key => $tag_val ) {
												if ( $tag_key === $tag_cnt ) {
													$ap_tag_arr[] = array(
														'ap_fees_tags'              => $tag_val,
														'ap_fees_ap_tag_min_qty'    => $fees_ap_tag_min_qty[ $tag_cnt ],
														'ap_fees_ap_tag_max_qty'    => $fees_ap_tag_max_qty[ $tag_cnt ],
														'ap_fees_ap_price_tag'      => $fees_ap_price_tag[ $tag_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							//category subtotal
							if ( isset( $fees['ap_category_subtotal_fees_conditions_condition'] ) ) {
								$fees_category_subtotal            = $fees['ap_category_subtotal_fees_conditions_condition'];
								$fees_ap_category_subtotal_min_qty = $fees['ap_fees_ap_category_subtotal_min_subtotal'];
								$fees_ap_category_subtotal_max_qty = $fees['ap_fees_ap_category_subtotal_max_subtotal'];
								$fees_ap_price_category_subtotal   = $fees['ap_fees_ap_price_category_subtotal'];
								$category_subtotal_arr             = array();
								foreach ( $fees_category_subtotal as $fees_category_subtotal_val ) {
									$category_subtotal_arr[] = $fees_category_subtotal_val;
								}
								$size_category_subtotal_cond = count( $fees_category_subtotal );
								if ( ! empty( $size_category_subtotal_cond ) && $size_category_subtotal_cond > 0 ):
									for ( $category_subtotal_cnt = 0; $category_subtotal_cnt < $size_category_subtotal_cond; $category_subtotal_cnt ++ ) {
										if ( ! empty( $category_subtotal_arr ) && '' !== $category_subtotal_arr ) {
											foreach ( $category_subtotal_arr as $category_subtotal_key => $category_subtotal_val ) {
												if ( $category_subtotal_key === $category_subtotal_cnt ) {
													$ap_category_subtotal_arr[] = array(
														'ap_fees_category_subtotal'                 => $category_subtotal_val,
														'ap_fees_ap_category_subtotal_min_subtotal' => $fees_ap_category_subtotal_min_qty[ $category_subtotal_cnt ],
														'ap_fees_ap_category_subtotal_max_subtotal' => $fees_ap_category_subtotal_max_qty[ $category_subtotal_cnt ],
														'ap_fees_ap_price_category_subtotal'        => $fees_ap_price_category_subtotal[ $category_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
                            //tag subtotal
							if ( isset( $fees['ap_tag_subtotal_fees_conditions_condition'] ) ) {
								$fees_tag_subtotal            = $fees['ap_tag_subtotal_fees_conditions_condition'];
								$fees_ap_tag_subtotal_min_qty = $fees['ap_fees_ap_tag_subtotal_min_subtotal'];
								$fees_ap_tag_subtotal_max_qty = $fees['ap_fees_ap_tag_subtotal_max_subtotal'];
								$fees_ap_price_tag_subtotal   = $fees['ap_fees_ap_price_tag_subtotal'];
								$category_subtotal_arr             = array();
								foreach ( $fees_tag_subtotal as $fees_tag_subtotal_val ) {
									$category_subtotal_arr[] = $fees_tag_subtotal_val;
								}
								$size_tag_subtotal_cond = count( $fees_tag_subtotal );
								if ( ! empty( $size_tag_subtotal_cond ) && $size_tag_subtotal_cond > 0 ):
									for ( $category_subtotal_cnt = 0; $category_subtotal_cnt < $size_tag_subtotal_cond; $category_subtotal_cnt ++ ) {
										if ( ! empty( $category_subtotal_arr ) && '' !== $category_subtotal_arr ) {
											foreach ( $category_subtotal_arr as $category_subtotal_key => $category_subtotal_val ) {
												if ( $category_subtotal_key === $category_subtotal_cnt ) {
													$ap_tag_subtotal_arr[] = array(
														'ap_fees_tag_subtotal'                 => $category_subtotal_val,
														'ap_fees_ap_tag_subtotal_min_subtotal' => $fees_ap_tag_subtotal_min_qty[ $category_subtotal_cnt ],
														'ap_fees_ap_tag_subtotal_max_subtotal' => $fees_ap_tag_subtotal_max_qty[ $category_subtotal_cnt ],
														'ap_fees_ap_price_tag_subtotal'        => $fees_ap_price_tag_subtotal[ $category_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							//qty for total cart qty
							if ( isset( $fees['ap_total_cart_qty_fees_conditions_condition'] ) ) {
								$fees_total_cart_qty            = $fees['ap_total_cart_qty_fees_conditions_condition'];
								$fees_ap_total_cart_qty_min_qty = $fees['ap_fees_ap_total_cart_qty_min_qty'];
								$fees_ap_total_cart_qty_max_qty = $fees['ap_fees_ap_total_cart_qty_max_qty'];
								$fees_ap_price_total_cart_qty   = $fees['ap_fees_ap_price_total_cart_qty'];
								$total_cart_qty_arr             = array();
								foreach ( $fees_total_cart_qty as $fees_total_cart_qty_val ) {
									$total_cart_qty_arr[] = $fees_total_cart_qty_val;
								}
								$size_total_cart_qty_cond = count( $fees_total_cart_qty );
								if ( ! empty( $size_total_cart_qty_cond ) && $size_total_cart_qty_cond > 0 ):
									for ( $total_cart_qty_cnt = 0; $total_cart_qty_cnt < $size_total_cart_qty_cond; $total_cart_qty_cnt ++ ) {
										if ( ! empty( $total_cart_qty_arr ) && '' !== $total_cart_qty_arr ) {
											foreach ( $total_cart_qty_arr as $total_cart_qty_key => $total_cart_qty_val ) {
												if ( $total_cart_qty_key === $total_cart_qty_cnt ) {
													$ap_total_cart_qty_arr[] = array(
														'ap_fees_total_cart_qty'            => $total_cart_qty_val,
														'ap_fees_ap_total_cart_qty_min_qty' => $fees_ap_total_cart_qty_min_qty[ $total_cart_qty_cnt ],
														'ap_fees_ap_total_cart_qty_max_qty' => $fees_ap_total_cart_qty_max_qty[ $total_cart_qty_cnt ],
														'ap_fees_ap_price_total_cart_qty'   => $fees_ap_price_total_cart_qty[ $total_cart_qty_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							//product weight
							if ( isset( $fees['ap_product_weight_fees_conditions_condition'] ) ) {
								$fees_product_weight            = $fees['ap_product_weight_fees_conditions_condition'];
								$fees_ap_product_weight_min_qty = $fees['ap_fees_ap_product_weight_min_weight'];
								$fees_ap_product_weight_max_qty = $fees['ap_fees_ap_product_weight_max_weight'];
								$fees_ap_price_product_weight   = $fees['ap_fees_ap_price_product_weight'];
								$product_weight_arr             = array();
								foreach ( $fees_product_weight as $fees_product_weight_val ) {
									$product_weight_arr[] = $fees_product_weight_val;
								}
								$size_product_weight_cond = count( $fees_product_weight );
								if ( ! empty( $size_product_weight_cond ) && $size_product_weight_cond > 0 ):
									for ( $product_weight_cnt = 0; $product_weight_cnt < $size_product_weight_cond; $product_weight_cnt ++ ) {
										if ( ! empty( $product_weight_arr ) && '' !== $product_weight_arr ) {
											foreach ( $product_weight_arr as $product_weight_key => $product_weight_val ) {
												if ( $product_weight_key === $product_weight_cnt ) {
													$ap_product_weight_arr[] = array(
														'ap_fees_product_weight'            => $product_weight_val,
														'ap_fees_ap_product_weight_min_qty' => $fees_ap_product_weight_min_qty[ $product_weight_cnt ],
														'ap_fees_ap_product_weight_max_qty' => $fees_ap_product_weight_max_qty[ $product_weight_cnt ],
														'ap_fees_ap_price_product_weight'   => $fees_ap_price_product_weight[ $product_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							//category weight
							if ( isset( $fees['ap_category_weight_fees_conditions_condition'] ) ) {
								$fees_category_weight            = $fees['ap_category_weight_fees_conditions_condition'];
								$fees_ap_category_weight_min_qty = $fees['ap_fees_ap_category_weight_min_weight'];
								$fees_ap_category_weight_max_qty = $fees['ap_fees_ap_category_weight_max_weight'];
								$fees_ap_price_category_weight   = $fees['ap_fees_ap_price_category_weight'];
								$category_weight_arr             = array();
								foreach ( $fees_category_weight as $fees_category_weight_val ) {
									$category_weight_arr[] = $fees_category_weight_val;
								}
								$size_category_weight_cond = count( $fees_category_weight );
								if ( ! empty( $size_category_weight_cond ) && $size_category_weight_cond > 0 ):
									for ( $category_weight_cnt = 0; $category_weight_cnt < $size_category_weight_cond; $category_weight_cnt ++ ) {
										if ( ! empty( $category_weight_arr ) && '' !== $category_weight_arr ) {
											foreach ( $category_weight_arr as $category_weight_key => $category_weight_val ) {
												if ( $category_weight_key === $category_weight_cnt ) {
													$ap_category_weight_arr[] = array(
														'ap_fees_categories_weight'          => $category_weight_val,
														'ap_fees_ap_category_weight_min_qty' => $fees_ap_category_weight_min_qty[ $category_weight_cnt ],
														'ap_fees_ap_category_weight_max_qty' => $fees_ap_category_weight_max_qty[ $category_weight_cnt ],
														'ap_fees_ap_price_category_weight'   => $fees_ap_price_category_weight[ $category_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
                            //Tag weight
							if ( isset( $fees['ap_tag_weight_fees_conditions_condition'] ) ) {
								$fees_tag_weight            = $fees['ap_tag_weight_fees_conditions_condition'];
								$fees_ap_tag_weight_min_qty = $fees['ap_fees_ap_tag_weight_min_weight'];
								$fees_ap_tag_weight_max_qty = $fees['ap_fees_ap_tag_weight_max_weight'];
								$fees_ap_price_tag_weight   = $fees['ap_fees_ap_price_tag_weight'];
								$tag_weight_arr             = array();
								foreach ( $fees_tag_weight as $fees_tag_weight_val ) {
									$tag_weight_arr[] = $fees_tag_weight_val;
								}
								$size_tag_weight_cond = count( $fees_tag_weight );
								if ( ! empty( $size_tag_weight_cond ) && $size_tag_weight_cond > 0 ):
									for ( $tag_weight_cnt = 0; $tag_weight_cnt < $size_tag_weight_cond; $tag_weight_cnt ++ ) {
										if ( ! empty( $tag_weight_arr ) && '' !== $tag_weight_arr ) {
											foreach ( $tag_weight_arr as $tag_weight_key => $tag_weight_val ) {
												if ( $tag_weight_key === $tag_weight_cnt ) {
													$ap_tag_weight_arr[] = array(
														'ap_fees_tag_weight'            => $tag_weight_val,
														'ap_fees_ap_tag_weight_min_qty' => $fees_ap_tag_weight_min_qty[ $tag_weight_cnt ],
														'ap_fees_ap_tag_weight_max_qty' => $fees_ap_tag_weight_max_qty[ $tag_weight_cnt ],
														'ap_fees_ap_price_tag_weight'   => $fees_ap_price_tag_weight[ $tag_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}

                            //Shipping Class Qty
                            if ( isset( $fees['ap_shipping_class_fees_conditions_condition'] ) ) {
								$fees_shipping_class_subtotal                 = $fees['ap_shipping_class_fees_conditions_condition'];
								$fees_ap_shipping_class_subtotal_min_subtotal = $fees['ap_fees_ap_shipping_class_min_qty'];
								$fees_ap_shipping_class_subtotal_max_subtotal = $fees['ap_fees_ap_shipping_class_max_qty'];
								$fees_ap_price_shipping_class_subtotal        = $fees['ap_fees_ap_price_shipping_class'];
								$shipping_class_arr                           = array();
								foreach ( $fees_shipping_class_subtotal as $shipping_class_subtotal_key => $shipping_class_subtotal_val ) {
									$shipping_class_arr[] = $shipping_class_subtotal_val;
								}
								$size_shipping_class_subtotal_cond = count( $fees_shipping_class_subtotal );
								if ( ! empty( $size_shipping_class_subtotal_cond ) && $size_shipping_class_subtotal_cond > 0 ):
									for ( $shipping_class_subtotal_cnt = 0; $shipping_class_subtotal_cnt < $size_shipping_class_subtotal_cond; $shipping_class_subtotal_cnt ++ ) {
										if ( ! empty( $shipping_class_arr ) && $shipping_class_arr !== '' ) {
											foreach ( $shipping_class_arr as $shipping_class_subtotal_key => $shipping_class_subtotal_val ) {
												if ( $shipping_class_subtotal_key === $shipping_class_subtotal_cnt ) {
													$ap_shipping_class_arr[] = array(
														'ap_fees_shipping_classes'          => $shipping_class_subtotal_val,
														'ap_fees_ap_shipping_class_min_qty' => $fees_ap_shipping_class_subtotal_min_subtotal[ $shipping_class_subtotal_cnt ],
														'ap_fees_ap_shipping_class_max_qty' => $fees_ap_shipping_class_subtotal_max_subtotal[ $shipping_class_subtotal_cnt ],
														'ap_fees_ap_price_shipping_class'   => $fees_ap_price_shipping_class_subtotal[ $shipping_class_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}

                            //Shipping Class Weight
							if ( isset( $fees['ap_shipping_class_weight_fees_conditions_condition'] ) ) {
								$fees_shipping_class_weight                 = $fees['ap_shipping_class_weight_fees_conditions_condition'];
								$fees_ap_shipping_class_weight_min_weight   = $fees['ap_fees_ap_shipping_class_weight_min_weight'];
								$fees_ap_shipping_class_weight_max_weight   = $fees['ap_fees_ap_shipping_class_weight_max_weight'];
								$fees_ap_price_shipping_class_weight        = $fees['ap_fees_ap_price_shipping_class_weight'];
								$shipping_class_weight_arr             = array();
								foreach ( $fees_shipping_class_weight as $fees_shipping_class_weight_val ) {
									$shipping_class_weight_arr[] = $fees_shipping_class_weight_val;
								}
								$size_shipping_class_weight_cond = count( $fees_shipping_class_weight );
								if ( ! empty( $size_shipping_class_weight_cond ) && $size_shipping_class_weight_cond > 0 ):
									for ( $shipping_class_weight_cnt = 0; $shipping_class_weight_cnt < $size_shipping_class_weight_cond; $shipping_class_weight_cnt ++ ) {
										if ( ! empty( $shipping_class_weight_arr ) && '' !== $shipping_class_weight_arr ) {
											foreach ( $shipping_class_weight_arr as $shipping_class_weight_key => $shipping_class_weight_val ) {
												if ( $shipping_class_weight_key === $shipping_class_weight_cnt ) {
													$ap_shipping_class_weight_arr[] = array(
														'ap_fees_shipping_class_weight'                 => $shipping_class_weight_val,
														'ap_fees_ap_shipping_class_weight_min_weight'   => $fees_ap_shipping_class_weight_min_weight[ $shipping_class_weight_cnt ],
														'ap_fees_ap_shipping_class_weight_max_weight'   => $fees_ap_shipping_class_weight_max_weight[ $shipping_class_weight_cnt ],
														'ap_fees_ap_price_shipping_class_weight'        => $fees_ap_price_shipping_class_weight[ $shipping_class_weight_cnt ],
													);
												}
											}
										}
									}
								endif;
							}

							//Shipping Class subtotal
							if ( isset( $fees['ap_shipping_class_subtotal_fees_conditions_condition'] ) ) {
								$fees_shipping_class_subtotal                 = $fees['ap_shipping_class_subtotal_fees_conditions_condition'];
								$fees_ap_shipping_class_subtotal_min_subtotal = $fees['ap_fees_ap_shipping_class_subtotal_min_subtotal'];
								$fees_ap_shipping_class_subtotal_max_subtotal = $fees['ap_fees_ap_shipping_class_subtotal_max_subtotal'];
								$fees_ap_price_shipping_class_subtotal        = $fees['ap_fees_ap_price_shipping_class_subtotal'];
								$shipping_class_subtotal_arr                  = array();
								foreach ( $fees_shipping_class_subtotal as $shipping_class_subtotal_key => $shipping_class_subtotal_val ) {
									$shipping_class_subtotal_arr[] = $shipping_class_subtotal_val;
								}
								$size_shipping_class_subtotal_cond = count( $fees_shipping_class_subtotal );
								if ( ! empty( $size_shipping_class_subtotal_cond ) && $size_shipping_class_subtotal_cond > 0 ):
									for ( $shipping_class_subtotal_cnt = 0; $shipping_class_subtotal_cnt < $size_shipping_class_subtotal_cond; $shipping_class_subtotal_cnt ++ ) {
										if ( ! empty( $shipping_class_subtotal_arr ) && $shipping_class_subtotal_arr !== '' ) {
											foreach ( $shipping_class_subtotal_arr as $shipping_class_subtotal_key => $shipping_class_subtotal_val ) {
												if ( $shipping_class_subtotal_key === $shipping_class_subtotal_cnt ) {
													$ap_shipping_class_subtotal_arr[] = array(
														'ap_fees_shipping_class_subtotals'                => $shipping_class_subtotal_val,
														'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $fees_ap_shipping_class_subtotal_min_subtotal[ $shipping_class_subtotal_cnt ],
														'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $fees_ap_shipping_class_subtotal_max_subtotal[ $shipping_class_subtotal_cnt ],
														'ap_fees_ap_price_shipping_class_subtotal'        => $fees_ap_price_shipping_class_subtotal[ $shipping_class_subtotal_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
                            //Product attribute
							if ( isset( $fees['ap_product_attribute_fees_conditions_condition'] ) ) {
								$fees_product_attribute             = $fees['ap_product_attribute_fees_conditions_condition'];
								$fees_ap_product_attribute_min_qty  = $fees['ap_fees_ap_product_attribute_min_qty'];
								$fees_ap_product_attribute_max_qty  = $fees['ap_fees_ap_product_attribute_max_qty'];
								$fees_ap_price_product_attribute    = $fees['ap_fees_ap_price_product_attribute'];
								$product_attribute_arr              = array();
								foreach ( $fees_product_attribute as $product_attribute_key => $product_attribute_val ) {
									$product_attribute_arr[] = $product_attribute_val;
								}
								$size_product_attribute_cond = count( $fees_product_attribute );
								if ( ! empty( $size_product_attribute_cond ) && $size_product_attribute_cond > 0 ):
									for ( $product_attribute_cnt = 0; $product_attribute_cnt < $size_product_attribute_cond; $product_attribute_cnt ++ ) {
										if ( ! empty( $product_attribute_arr ) && $product_attribute_arr !== '' ) {
											foreach ( $product_attribute_arr as $product_attribute_key => $product_attribute_val ) {
												if ( $product_attribute_key === $product_attribute_cnt ) {
													$ap_product_attribute_arr[] = array(
														'ap_fees_product_attributes'            => $product_attribute_val,
														'ap_fees_ap_product_attribute_min_qty'  => $fees_ap_product_attribute_min_qty[ $product_attribute_cnt ],
														'ap_fees_ap_product_attribute_max_qty'  => $fees_ap_product_attribute_max_qty[ $product_attribute_cnt ],
														'ap_fees_ap_price_product_attribute'    => $fees_ap_price_product_attribute[ $product_attribute_cnt ],
													);
												}
											}
										}
									}
								endif;
							}
							update_post_meta( $method_id, 'fee_settings_unique_shipping_title', $fee_settings_unique_shipping_title );
							update_post_meta( $method_id, 'sm_free_shipping_cost_before_discount', $sm_free_shipping_cost_before_discount );
							update_post_meta( $method_id, 'cost_rule_match', maybe_serialize( $cost_rule_match ) );
							update_post_meta( $method_id, 'main_rule_condition', $main_rule_condition );
							update_post_meta( $method_id, 'how_to_apply', $how_to_apply );
							/* Apply per quantity postmeta start */
							update_post_meta( $method_id, 'sm_fee_chk_qty_price', $sm_fee_chk_qty_price );
							update_post_meta( $method_id, 'sm_fee_per_qty', $sm_fee_per_qty );
							update_post_meta( $method_id, 'sm_extra_product_cost', $sm_extra_product_cost );
							/* Apply per quantity postmeta end */
							update_post_meta( $method_id, 'sm_estimation_delivery', $sm_estimation_delivery );
							update_post_meta( $method_id, 'sm_start_date', $sm_start_date );
							update_post_meta( $method_id, 'sm_end_date', $sm_end_date );
							/*Advance Pricing Rules Particular Status*/
							update_post_meta( $method_id, 'cost_on_product_status', $cost_on_product_status );
							update_post_meta( $method_id, 'cost_on_product_weight_status', $cost_on_product_weight_status );
							update_post_meta( $method_id, 'cost_on_product_subtotal_status', $cost_on_product_subtotal_status );
							update_post_meta( $method_id, 'cost_on_category_status', $cost_on_category_status );
							update_post_meta( $method_id, 'cost_on_category_weight_status', $cost_on_category_weight_status );
							update_post_meta( $method_id, 'cost_on_category_subtotal_status', $cost_on_category_subtotal_status );
                            update_post_meta( $method_id, 'cost_on_tag_status', $cost_on_tag_status );
							update_post_meta( $method_id, 'cost_on_tag_subtotal_status', $cost_on_tag_subtotal_status );
							update_post_meta( $method_id, 'cost_on_tag_weight_status', $cost_on_tag_weight_status );
							update_post_meta( $method_id, 'cost_on_total_cart_qty_status', $cost_on_total_cart_qty_status );
							update_post_meta( $method_id, 'cost_on_shipping_class_status', $cost_on_shipping_class_status );
							update_post_meta( $method_id, 'cost_on_shipping_class_weight_status', $cost_on_shipping_class_weight_status );
							update_post_meta( $method_id, 'cost_on_shipping_class_subtotal_status', $cost_on_shipping_class_subtotal_status );
							update_post_meta( $method_id, 'cost_on_product_attribute_status', $cost_on_product_attribute_status );
							update_post_meta( $method_id, 'sm_free_shipping_cost_left_notice', $sm_free_shipping_cost_left_notice );
							update_post_meta( $method_id, 'sm_free_shipping_cost_left_notice_msg', $sm_free_shipping_cost_left_notice_msg );
							if ( isset( $sm_select_day_of_week ) ) {
								update_post_meta( $method_id, 'sm_select_day_of_week', $sm_select_day_of_week );
							}
							if ( isset( $sm_time_from ) ) {
								update_post_meta( $method_id, 'sm_time_from', $sm_time_from );
							}
							if ( isset( $sm_time_to ) ) {
								update_post_meta( $method_id, 'sm_time_to', $sm_time_to );
							}
							update_post_meta( $method_id, 'sm_metabox_ap_product', $ap_product_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_product_weight', $ap_product_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_product_subtotal', $ap_product_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_category', $ap_category_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_category_weight', $ap_category_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_category_subtotal', $ap_category_subtotal_arr );
                            update_post_meta( $method_id, 'sm_metabox_ap_tag', $ap_tag_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_tag_subtotal', $ap_tag_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_tag_weight', $ap_tag_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_total_cart_qty', $ap_total_cart_qty_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_shipping_class', $ap_shipping_class_arr );
                            update_post_meta( $method_id, 'sm_metabox_ap_shipping_class_weight', $ap_shipping_class_weight_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_shipping_class_subtotal', $ap_shipping_class_subtotal_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_product_attribute', $ap_product_attribute_arr );
							update_post_meta( $method_id, 'sm_metabox_ap_class', $ap_class_arr );

							update_post_meta( $method_id, 'is_allow_custom_weight_base', $is_allow_custom_weight_base );
							update_post_meta( $method_id, 'sm_custom_weight_base_cost', $sm_custom_weight_base_cost );
							update_post_meta( $method_id, 'sm_custom_weight_base_per_each', $sm_custom_weight_base_per_each );
							update_post_meta( $method_id, 'sm_custom_weight_base_over', $sm_custom_weight_base_over );
							update_post_meta( $method_id, 'is_allow_custom_qty_base', $is_allow_custom_qty_base );
							update_post_meta( $method_id, 'sm_custom_qty_base_cost', $sm_custom_qty_base_cost );
							update_post_meta( $method_id, 'sm_custom_qty_base_per_each', $sm_custom_qty_base_per_each );
							update_post_meta( $method_id, 'sm_custom_qty_base_over', $sm_custom_qty_base_over );

                            update_post_meta( $method_id, 'sm_free_shipping_based_on_product', $sm_free_shipping_based_on_product );
                            update_post_meta( $method_id, 'sm_free_shipping_exclude_product', $sm_free_shipping_exclude_product );
                            update_post_meta( $method_id, 'is_free_shipping_exclude_prod', $is_free_shipping_exclude_prod );
							
							/**
							 * Filter for save data.
							 *
							 * @since  3.8
							 *
							 * @author jb
							 */
							apply_filters( 'afrsm_pro_fees_conditions_save_ft', $fees, $post, $feesArray, $method_id );
							if ( ! empty( $sitepress ) ) {
								do_action( 'wpml_register_single_string', 'advanced-flat-rate-shipping-for-woocommerce', sanitize_text_field( $fee_settings_product_fee_title ), sanitize_text_field( $fee_settings_product_fee_title ) );
							}
						}
					}
					if ( 'edit' !== $action ) {
						$getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
						if ( ! empty( $getSortOrder ) ) {
							foreach ( $getSortOrder as $getSortOrder_id ) {
								settype( $getSortOrder_id, 'integer' );
							}
							array_unshift( $getSortOrder, $method_id );
						}
						update_option( 'sm_sortable_order_' . $default_lang, $getSortOrder );
					}
				}
			} else {
				echo '<div class="updated error"><p>' . esc_html__( 'Error saving shipping method.', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</p></div>';
				return false;
			}
			$afrsmnonce = wp_create_nonce( 'afrsmnonce' );
			wp_safe_redirect( add_query_arg( array(
				'page'     => 'afrsm-pro-list',
				'_wpnonce' => esc_attr( $afrsmnonce ),
			), admin_url( 'admin.php' ) ) );
			exit();
		}
	}
	/**
	 * Review message in footer
	 *
	 * @return string
	 * @since  1.0.0
	 *
	 */
	public function afrsm_pro_admin_footer_review() {
		$url = '';
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$url = esc_url( 'https://www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce#tab-reviews' );
			}
		} else {
			$url = esc_url( 'https://wordpress.org/plugins/woo-extra-flat-rate/#reviews' );
		}
        $html = sprintf( 'If you like <strong>%2$s</strong> plugin, please leave us &#9733;&#9733;&#9733;&#9733;&#9733; ratings on <a href="%1$s" target="_blank">DotStore</a>.', esc_url( $url ), AFRSM_PRO_PLUGIN_NAME );
        echo wp_kses_post( $html );
	}
	/**
	 * Clone shipping method
	 *
	 * @return string true if current_shipping_id is empty then it will give message.
	 * @uses   get_post()
	 * @uses   wp_get_current_user()
	 * @uses   wp_insert_post()
	 *
	 * @since  3.4
	 *
	 */
	public function afrsm_pro_clone_shipping_method() {
		/* Check for post request */
		$get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
		$get_post_id             = isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '';
		if ( empty( $get_post_id ) ) {
			echo sprintf( wp_kses( __( '<strong>No post to duplicate has been supplied!</strong>', 'advanced-flat-rate-shipping-for-woocommerce' ), array( 'strong' => array() ) ) );
			wp_die();
		}
		/* End of if */
		/* Get the original post id */
		if ( ! empty( $get_post_id ) || '' !== $get_post_id ) {
			/* Get all the original post data */
			$post = get_post( $get_post_id );
			/* Get current user and make it new post user (duplicate post) */
			$current_user    = wp_get_current_user();
			$new_post_author = $current_user->ID;
			/* If post data exists, duplicate the data into new duplicate post */
			if ( isset( $post ) && null !== $post ) {
				/* New post data array */
				$args = array(
					'comment_status' => $post->comment_status,
					'ping_status'    => $post->ping_status,
					'post_author'    => $new_post_author,
					'post_content'   => $post->post_content,
					'post_excerpt'   => $post->post_excerpt,
					'post_name'      => $post->post_name,
					'post_parent'    => $post->post_parent,
					'post_password'  => $post->post_password,
					'post_status'    => 'draft',
					'post_title'     => $post->post_title . '-duplicate',
					'post_type'      => self::afrsm_shipping_post_type,
					'to_ping'        => $post->to_ping,
					'menu_order'     => $post->menu_order,
				);
				/* Duplicate the post by wp_insert_post() function */
				$new_post_id = wp_insert_post( $args );
				/* Duplicate all post meta-data */
				$post_meta_data = get_post_meta( $get_post_id );
				if ( 0 !== count( $post_meta_data ) ) {
					foreach ( $post_meta_data as $meta_key => $meta_data ) {
						if ( '_wp_old_slug' === $meta_key ) {
							continue;
						}
						$meta_value = maybe_unserialize( $meta_data[0] );
						update_post_meta( $new_post_id, $meta_key, $meta_value );
					}
				}
			}
			$afrsmnonce   = wp_create_nonce( 'afrsmnonce' );
			$redirect_url = add_query_arg( array(
				'page'     => 'afrsm-pro-edit-shipping',
				'id'       => $new_post_id,
				'action'   => 'edit',
				'_wpnonce' => esc_attr( $afrsmnonce ),
			), admin_url( 'admin.php' ) );
			echo wp_json_encode( array( true, $redirect_url ) );
		}
		wp_die();
	}
	/**
	 * Change shipping status from list of shipping method
	 *
	 * @since  3.4
	 *
	 * @uses   update_post_meta()
	 *
	 * if current_shipping_id is empty then it will give message.
	 */
	public function afrsm_pro_change_status_from_list_section() {
		global $sitepress;
		$default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$active_items = 0;

		/* Check for post request */
		$get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $sitepress ) ) {
			$get_current_shipping_id = apply_filters( 'wpml_object_id', $get_current_shipping_id, 'product', true, $default_lang );
		} else {
			$get_current_shipping_id = $get_current_shipping_id;
		}
		$get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_post_id       = isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '';
		if ( empty( $get_post_id ) ) {
			echo '<strong>' . esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong>';
			wp_die();
		}
		$current_value = isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '';
		$get_search  = filter_input( INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( 'true' === $current_value ) {
			$post_args   = array(
				'ID'          => $get_post_id,
				'post_status' => 'publish',
				'post_type'   => self::afrsm_shipping_post_type,
			);
			$post_update = wp_update_post( $post_args );
			update_post_meta( $get_post_id, 'sm_status', 'on' );
		} else {
			$post_args   = array(
				'ID'          => $get_post_id,
				'post_status' => 'draft',
				'post_type'   => self::afrsm_shipping_post_type,
			);
			$post_update = wp_update_post( $post_args );
			update_post_meta( $get_post_id, 'sm_status', 'off' );
		}
		if ( ! class_exists( 'WC_Advanced_Flat_Rate_Shipping_Table' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/list-tables/class-wc-flat-rate-rule-table.php';
			$WC_Advanced_Flat_Rate_Shipping_Table = new WC_Advanced_Flat_Rate_Shipping_Table();
            $args = array();
			if ( isset( $get_search ) && ! empty( $get_search ) ) {
				$args['s'] = trim( wp_unslash( $get_search ) );
			}
			$active_items = $WC_Advanced_Flat_Rate_Shipping_Table->afrsm_active_find($args);
		}
		
		if ( ! empty( $post_update ) ) {
			$message = esc_html__( 'Shipping status changed successfully.', 'advanced-flat-rate-shipping-for-woocommerce' );
		} else {
			$message = esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' );
		}
		
		wp_send_json( array( 'active_count' => $active_items, 'message' => $message ) );
	}
	/**
	 * Change Advance pricing rules status
	 *
	 * @return string true if current_shipping_id is empty then it will give message.
	 *
	 * @uses   update_post_meta()
	 *
	 * @since  3.4
	 *
	 */
	public function afrsm_pro_change_status_of_advance_pricing_rules() {
		$get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
		$get_current_value       = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_post_id             = isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '';
		if ( empty( $get_post_id ) ) {
			echo '<strong>' . esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong>';
			wp_die();
		}
		$current_value = isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '';
		if ( 'true' === $current_value ) {
			update_post_meta( $get_post_id, 'ap_rule_status', 'off' );
			echo esc_html( 'true' );
		}
		wp_die();
	}
	/**
	 * Get default site language
	 *
	 * @return string $default_lang
	 *
	 * @since  3.4
	 *
	 */
	public function afrsm_pro_get_default_langugae_with_sitpress() {
		global $sitepress;
		if ( ! empty( $sitepress ) ) {
			$default_lang = $sitepress->get_current_language();
		} else {
			$default_lang = $this->afrsm_pro_get_current_site_language();
		}
		return $default_lang;
	}
	/**
	 * Get AFRSM shipping method
	 *
	 * @param string $args
	 *
	 * @return string $default_lang
	 *
	 * @since  3.4
	 *
	 */
	public static function afrsm_pro_get_shipping_method( $args ) {
		$sm_args = array(
			'post_type'        => self::afrsm_shipping_post_type,
			'posts_per_page'   => -1,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'suppress_filters' => false,
		);
		if ( 'not_list' === $args ) {
			$sm_args['post_status'] = 'publish';
		}
		$get_all_shipping = new WP_Query( $sm_args );
		$get_all_shipping = $get_all_shipping->get_posts();
		return $get_all_shipping;
	}
	/**
	 * Convert array to json
	 *
	 * @param array $arr
	 *
	 * @return array $filter_data
	 * @since 1.0.0
	 *
	 */
	public function afrsm_pro_convert_array_to_json( $arr ) {
		$filter_data = [];
		foreach ( $arr as $key => $value ) {
			$option                        = [];
			$option['name']                = $value;
			$option['attributes']['value'] = $key;
			$filter_data[]                 = $option;
		}
		return $filter_data;
	}
	/**
	 * Convert array to json
	 *
	 * @param array $arr
	 *
	 * @return array $filter_data
	 * @since 1.0.0
	 *
	 */
	public function afrsm_pro_attribute_list__premium_only() {
		$filter_attr_data     = [];
		$filter_attr_json     = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( $attribute_taxonomies ) {
			foreach ( $attribute_taxonomies as $attribute ) {
				$att_label                               = $attribute->attribute_label;
				$att_name                                = wc_attribute_taxonomy_name( $attribute->attribute_name );
				$filter_attr_json['name']                = $att_label;
				$filter_attr_json['attributes']['value'] = esc_html__( $att_name, 'advanced-flat-rate-shipping-for-woocommerce' );
				$filter_attr_data[]                      = $filter_attr_json;
			}
		}
		return $filter_attr_data;
	}
	/**
	 * Get product id and variation id from cart
	 *
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $cart_product_ids_array
	 * @uses  afrsm_pro_get_cart();
	 *
	 * @since 1.0.0
	 *
	 */
	public function afrsm_pro_get_prd_var_id( $cart_array, $sitepress, $default_lang ) {
		$cart_product_ids_array = array();
        if( !empty( $cart_array ) ) {
            foreach ( $cart_array as $woo_cart_item ) {
                $_product = wc_get_product( $woo_cart_item['product_id'] );
                if ( afrsfw_fs()->is__premium_only() ) {
                    if ( afrsfw_fs()->can_use_premium_code() ) {
                        $_product_simp_var_id = 'variation_id';
                    } else {
                        $_product_simp_var_id = 'product_id';
                    }
                } else {
                    $_product_simp_var_id = 'product_id';
                }
                /**
                 * Updated and added code for fetch product from addon.
                 *
                 * @since  3.8
                 *
                 * @author jb
                 */
                $check_virtual = $this->afrsm_check_product_type_for_front( $_product, $woo_cart_item );
                if ( true === $check_virtual ) {
                    if ( $_product->is_type( 'variable' ) ) {
                        if ( ! empty( $sitepress ) ) {
                            $cart_product_ids_array[] = apply_filters( 'wpml_object_id', $woo_cart_item[ $_product_simp_var_id ], 'product', true, $default_lang );
                        } else {
                            $cart_product_ids_array[] = $woo_cart_item[ $_product_simp_var_id ];
                        }
                    } else {
                        if ( ! empty( $sitepress ) ) {
                            $cart_product_ids_array[] = apply_filters( 'wpml_object_id', $woo_cart_item['product_id'], 'product', true, $default_lang );
                        } else {
                            $cart_product_ids_array[] = $woo_cart_item['product_id'];
                        }
                    }
                }
            }
        }
		return $cart_product_ids_array;
	}
	/**
	 * Get variation name from cart
	 *
	 * @param string $sitepress
	 *
	 * @return array $cart_product_ids_array
	 * @uses  afrsm_pro_get_cart();
	 *
	 * @since 1.0.0
	 *
	 */
	public function afrsm_pro_get_var_name__premium_only( $cart_array ) {
        global $sitepress;
		$default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
		$product_attributes_array = array();

		foreach ( $cart_array as $woo_cart_item ) {
            
            $id = ! empty( $woo_cart_item['variation_id'] ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            if ( ! empty( $sitepress ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'product', true, $default_lang );
            }
			$_product = wc_get_product( $id );

            //prepare data from non-bundle products
            if( $this->afrsm_check_non_bundle_product_conditions($_product, $woo_cart_item) ){
                if ( $_product->is_type( 'variation' ) ) {
                    $variation               = new WC_Product_Variation( $id );
                    $variation_cart_products = $variation->get_variation_attributes();
                    foreach($variation_cart_products as $variation_cart_product) {
                        $product_attributes_array[] = $variation_cart_product;
                    }
                } else if( $_product->is_type( 'simple' ) ) {
                    foreach( $_product->get_attributes() as $sa_val ){
                        foreach( $sa_val['options'] as $sa_option ){
                            $sa_data = get_term_by('id', $sa_option, $sa_val['name']);
                            if( $sa_data ) { // #71956 solution
                                $product_attributes_array[] = $sa_data->slug;
                            }
                        }
                    }
                }
            }

            //Check and process bundle products
            $product_attributes_array = array_merge( $product_attributes_array, $this->afrsm_get_bundle_product_data_by_type( $woo_cart_item, 'product_attr' ) );
		}
        
		$product_attributes_array = array_values(array_unique($product_attributes_array));
		
        return $product_attributes_array;
	}
	/**
	 * Get product id and variation id from cart
	 *
	 * @return array $cart_array
	 * @since 1.0.0
	 *
	 */
	public function afrsm_pro_get_cart() {
		$cart_array = WC()->cart->get_cart();
		return $cart_array;
	}
	/**
	 * Get current site langugae
	 *
	 * @return string $default_lang
	 * @since 1.0.0
	 *
	 */
	public function afrsm_pro_get_current_site_language() {
		$get_site_language = get_bloginfo( 'language' );
		if ( false !== strpos( $get_site_language, '-' ) ) {
			$get_site_language_explode = explode( '-', $get_site_language );
			$default_lang              = $get_site_language_explode[0];
		} else {
			$default_lang = $get_site_language;
		}
		return $default_lang;
	}
	/**
	 * Remove section from shipping settings because we have added new menu in woocommece section
	 *
	 * @param array $sections
	 *
	 * @return array $sections
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_remove_section( $sections ) {
		unset( $sections['advanced_flat_rate_shipping'], $sections['forceall'] );
		return $sections;
	}
	/**
	 * Get cart subtotal
	 *
	 * @return float $subtotal
	 *
	 * @since    3.6
	 */
	public function afrsm_pro_get_cart_subtotal() {
		$get_customer            = WC()->cart->get_customer();
		$get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
		$tax_display_cart        = WC()->cart->get_tax_price_display_mode();//tax_display_cart; //WC()->cart->tax_display_cart;
		$wc_prices_include_tax   = wc_prices_include_tax();
		$tax_enable              = wc_tax_enabled();
		$cart_subtotal           = 0;
		if ( true === $tax_enable ) {
			if ( true === $wc_prices_include_tax ) {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
				} else {
					$cart_subtotal += WC()->cart->get_subtotal();
				}
			} else {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
				} else {
					$cart_subtotal += WC()->cart->get_subtotal();
				}
			}
		} else {
			$cart_subtotal += WC()->cart->get_subtotal();
		}
		return $cart_subtotal;
	}
	/**
	 * Fetch Zone
	 *
	 * @since    3.6
	 */
	public function afrsm_pro_fetch_shipping_zone() {
		global $wpdb;
		$sz_table_name  = "{$wpdb->prefix}wcextraflatrate_shipping_zones";
		$szl_table_name = "{$wpdb->prefix}wcextraflatrate_shipping_zone_locations";
        // WPCS: db call ok.
		if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $sz_table_name ) ) === $sz_table_name ) { // phpcs:ignore
			$get_zone_data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM %s as tbl1", $sz_table_name ) );
		}
		$i              = 0;
		$success_array  = array();
		$get_zone_array = array();
		if ( ! empty( $get_zone_data ) ) {
			foreach ( $get_zone_data as $value ) {
				$i ++;
				$zone_id      = $value->zone_id;
				$zone_enabled = $value->zone_enabled;
				if ( '1' === $zone_enabled ) {
					$post_status = 'publish';
				} else {
					$post_status = 'draft';
				}
				$get_zone_array[ $zone_id ]['ID']           = $value->zone_id;
				$get_zone_array[ $zone_id ]['zone_name']    = $value->zone_name;
				$get_zone_array[ $zone_id ]['zone_enabled'] = $post_status;
				$get_zone_array[ $zone_id ]['zone_type']    = $value->zone_type;
				$get_zone_array[ $zone_id ]['zone_order']   = $value->zone_order;
                // WPCS: db call ok.
				$locations                                  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s WHERE zone_id = %s;", $szl_table_name, $zone_id ) ); // phpcs:ignore
				if ( ! empty( $locations ) ) {
					$locations_list = array();
					foreach ( $locations as $locations_value ) {
						$location_type                         = $locations_value->location_type;
						$get_zone_array[ $i ]['location_type'] = $location_type;
						if ( 'country' === $location_type || 'state' === $location_type ) {
							$postcode_location_type = $locations_value->location_code;
						}
						if ( 'postcode' === $location_type ) {
							$locations_list[ $postcode_location_type ][] = $locations_value->location_code;
						}
						if ( 'postcode' === $location_type ) {
							$get_zone_array[ $zone_id ]['location_code'] = $locations_list;
						} else {
							$get_zone_array[ $zone_id ]['location_code'][] = $postcode_location_type;
						}
					}
				}
			}
		}
		if ( ! empty( $get_zone_array ) ) {
			foreach ( $get_zone_array as $get_zone_val ) {
				$zone_post   = array(
					'post_title'  => $get_zone_val['zone_name'],
					'post_status' => $get_zone_val['zone_enabled'],
					'menu_order'  => $get_zone_val['zone_order'] + 1,
					'post_type'   => 'wc_afrsm_zone',
				);
				$new_zone_id = wp_insert_post( $zone_post );
				if ( 'postcodes' === $get_zone_val['zone_type'] ) {
					update_post_meta( $new_zone_id, 'location_type', 'postcode' );
				} elseif ( 'countries' === $get_zone_val['zone_type'] ) {
					update_post_meta( $new_zone_id, 'location_type', 'country' );
				} elseif ( 'states' === $get_zone_val['zone_type'] ) {
					update_post_meta( $new_zone_id, 'location_type', 'state' );
				}
				update_post_meta( $new_zone_id, 'zone_type', $get_zone_val['zone_type'] );
				if ( 'postcodes' === $get_zone_val['zone_type'] ) {
					update_post_meta( $new_zone_id, 'location_code', $get_zone_val['location_code'] );
				} else {
					update_post_meta( $new_zone_id, 'location_code', array( $get_zone_val['location_code'] ) );
				}
			}
			$success_array[] = true;
		} else {
			$success_array[] = false;
		}
		$redirect_url = add_query_arg( array(
			'page' => 'afrsm-wc-shipping-zones',
		), admin_url( 'admin.php' ) );
		if ( in_array( true, $success_array, true ) ) {
			echo wp_json_encode( array( true, $redirect_url ) );
		} else {
			echo wp_json_encode( array( false, $redirect_url ) );
		}
		update_option( 'zone_migration', 'done' );
		wp_die();
	}

	/**
	 * Fetch slug based on id
	 *
	 * @since    3.6.1
	 */
	public function afrsm_pro_fetch_slug( $id_array, $condition ) {
		$return_array = array();
		if ( ! empty( $id_array ) ) {
			foreach ( $id_array as $key => $ids ) {
				if ( ! empty( $ids ) ) {
					if ( 'product' === $condition
					     || 'variableproduct' === $condition
					     || 'cpp' === $condition
					     || 'zone' === $condition ) {
						$get_posts = get_post( $ids );
						if ( ! empty( $get_posts ) ) {
							$return_array[] = $get_posts->post_name;
						}
					} elseif ( 'category' === $condition
					           || 'cpc' === $condition ) {
						$term = get_term( $ids, 'product_cat' );
						if ( ! empty( $term ) ) {
							$return_array[] = $term->slug;
						}
					} elseif ( 'tag' === $condition ) {
						$tag = get_term( $ids, 'product_tag' );
						if ( ! empty( $tag ) ) {
							$return_array[] = $tag->slug;
						}
					} elseif ( 'shipping_class' === $condition ) {
						$shipping_class = get_term( $key, 'product_shipping_class' );
						if ( ! empty( $shipping_class ) ) {
							$return_array[ $shipping_class->slug ] = $ids;
						}
					} elseif ( 'cpsc' === $condition ) {
						$return_array[] = $ids;
					} elseif ( 'cpp' === $condition ) {
						$cpp_posts = get_post( $ids );
						if ( ! empty( $cpp_posts ) ) {
							$return_array[] = $cpp_posts->post_name;
						}
					} else {
						$return_array[] = $ids;
					}
				}
			}
		}
		return $return_array;
	}
	/**
	 * Fetch id based on slug
	 *
	 * @since    3.6.1
	 */
	public function afrsm_pro_fetch_id( $slug_array, $condition ) {
		$return_array = array();
		if ( ! empty( $slug_array ) ) {
			foreach ( $slug_array as $slugs ) {
				if ( ! empty( $slugs ) ) {
					if ( 'product' === $condition ) {
						$post = get_page_by_path( $slugs, OBJECT, 'product' ); // phpcs:ignore
						if ( ! empty( $post ) ) {
							$id             = $post->ID;
							$return_array[] = $id;
						}
					} elseif ( 'variableproduct' === $condition ) {
						$args           = array(
							'post_type' => 'product_variation',
							'fields'    => 'ids',
							'name'      => $slugs
						);
						$variable_posts = new WP_Query( $args );
						if ( ! empty( $variable_posts->posts ) ) {
							foreach ( $variable_posts->posts as $val ) {
								$return_array[] = $val;
							}
						}
					} elseif ( 'category' === $condition
					           || 'cpc' === $condition ) {
						$term = get_term_by( 'slug', $slugs, 'product_cat' );
						if ( ! empty( $term ) ) {
							$return_array[] = $term->term_id;
						}
					} elseif ( 'tag' === $condition ) {
						$term_tag = get_term_by( 'slug', $slugs, 'product_tag' );
						if ( ! empty( $term_tag ) ) {
							$return_array[] = $term_tag->term_id;
						}
					} elseif ( 'shipping_class' === $condition
					           || 'cpsc' === $condition ) {
						$shipping_class = get_term_by( 'slug', $slugs, 'product_shipping_class' );
						if ( ! empty( $shipping_class ) ) {
							$return_array[ $shipping_class->term_id ] = $slugs;
						}
					} elseif ( 'cpp' === $condition ) {
						$args           = array(
							'post_type' => array( 'product_variation', 'product' ),
							'name'      => $slugs,
						);
						$variable_posts = new WP_Query( $args );
						if ( ! empty( $variable_posts->posts ) ) {
							foreach ( $variable_posts->posts as $val ) {
								$return_array[] = $val->ID;
							}
						}
					} elseif ( 'zone' === $condition ) {
						$post = get_page_by_path( $slugs, OBJECT, 'wc_afrsm_zone' ); // phpcs:ignore
						if ( ! empty( $post ) ) {
							$id             = $post->ID;
							$return_array[] = $id;
						}
					} else {
						$return_array[] = $slugs;
					}
				}
			}
		}
		return $return_array;
	}
	/**
	 * Export Shipping Method
	 *
	 * @since 3.6.1
	 *
	 */
	public function afrsm_pro_import_export_shipping_method() {
		$export_action = filter_input( INPUT_POST, 'afrsm_export_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$import_action = filter_input( INPUT_POST, 'afrsm_import_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$default_lang  = $this->afrsm_pro_get_default_langugae_with_sitpress();
		if ( ! empty( $export_action ) || 'export_settings' === $export_action ) {
			$get_all_fees_args  = array(
				'post_type'      => self::afrsm_shipping_post_type,
				'order'          => 'DESC',
				'posts_per_page' => - 1,
				'orderby'        => 'ID',
			);
			$get_all_fees_query = new WP_Query( $get_all_fees_args );
			$get_all_fees       = $get_all_fees_query->get_posts();
			$get_all_fees_count = $get_all_fees_query->found_posts;
			$get_sort_order     = get_option( 'sm_sortable_order_' . $default_lang );
			$sort_order         = array();
			if ( isset( $get_sort_order ) && ! empty( $get_sort_order ) ) {
				foreach ( $get_sort_order as $sort ) {
					$sort_order[ $sort ] = array();
				}
			}
			foreach ( $get_all_fees as $carrier_id => $carrier ) {
				$carrier_name = $carrier->ID;
				if ( array_key_exists( $carrier_name, $sort_order ) ) {
					$sort_order[ $carrier_name ][ $carrier_id ] = $get_all_fees[ $carrier_id ];
					unset( $get_all_fees[ $carrier_id ] );
				}
			}
			foreach ( $sort_order as $carriers ) {
				$get_all_fees = array_merge( $get_all_fees, $carriers );
			}
			$fees_data = array();
			$main_data = array();
			if ( $get_all_fees_count > 0 ) {
				foreach ( $get_all_fees as $fees ) {
					$request_post_id                        = $fees->ID;
					$sm_status                              = get_post_status( $request_post_id );
					$sm_title                               = __( get_the_title( $request_post_id ), 'advanced-flat-rate-shipping-for-woocommerce' );
					$sm_cost                                = get_post_meta( $request_post_id, 'sm_product_cost', true );
					$sm_free_shipping_based_on              = get_post_meta( $request_post_id, 'sm_free_shipping_based_on', true );
					$is_allow_free_shipping                 = get_post_meta( $request_post_id, 'is_allow_free_shipping', true );
					$sm_free_shipping_cost                  = get_post_meta( $request_post_id, 'sm_free_shipping_cost', true );
					$sm_free_shipping_cost_before_discount  = get_post_meta( $request_post_id, 'sm_free_shipping_cost_before_discount', true );
					$sm_free_shipping_cost_left_notice      = get_post_meta( $request_post_id, 'sm_free_shipping_cost_left_notice', true );
					$sm_free_shipping_cost_left_notice_msg  = get_post_meta( $request_post_id, 'sm_free_shipping_cost_left_notice_msg', true );
					$sm_free_shipping_coupan_cost           = get_post_meta( $request_post_id, 'sm_free_shipping_coupan_cost', true );
					$sm_free_shipping_label                 = get_post_meta( $request_post_id, 'sm_free_shipping_label', true );
					$sm_tooltip_type                        = get_post_meta( $request_post_id, 'sm_tooltip_type', true );
					$sm_tooltip_desc                        = get_post_meta( $request_post_id, 'sm_tooltip_desc', true );
					$sm_is_taxable                          = get_post_meta( $request_post_id, 'sm_select_taxable', true );
					$sm_select_shipping_provider            = get_post_meta( $request_post_id, 'sm_select_shipping_provider', true );
					$sm_metabox                             = get_post_meta( $request_post_id, 'sm_metabox', true );
					$sm_extra_cost                          = get_post_meta( $request_post_id, 'sm_extra_cost', true );
					$sm_extra_cost_calc_type                = get_post_meta( $request_post_id, 'sm_extra_cost_calculation_type', true );
					$ap_rule_status                         = get_post_meta( $request_post_id, 'ap_rule_status', true );
					$fee_settings_unique_shipping_title     = get_post_meta( $request_post_id, 'fee_settings_unique_shipping_title', true );
					$getFeesPerQtyFlag                      = get_post_meta( $request_post_id, 'sm_fee_chk_qty_price', true );
					$getFeesPerQty                          = get_post_meta( $request_post_id, 'sm_fee_per_qty', true );
					$extraProductCost                       = get_post_meta( $request_post_id, 'sm_extra_product_cost', true );
					$sm_estimation_delivery                 = get_post_meta( $request_post_id, 'sm_estimation_delivery', true );
					$sm_start_date                          = get_post_meta( $request_post_id, 'sm_start_date', true );
					$sm_end_date                            = get_post_meta( $request_post_id, 'sm_end_date', true );
					$sm_time_from                           = get_post_meta( $request_post_id, 'sm_time_from', true );
					$sm_time_to                             = get_post_meta( $request_post_id, 'sm_time_to', true );
					$sm_select_day_of_week                  = get_post_meta( $request_post_id, 'sm_select_day_of_week', true );
					$cost_on_product_status                 = get_post_meta( $request_post_id, 'cost_on_product_status', true );
					$cost_on_product_weight_status          = get_post_meta( $request_post_id, 'cost_on_product_weight_status', true );
					$cost_on_product_subtotal_status        = get_post_meta( $request_post_id, 'cost_on_product_subtotal_status', true );
					$cost_on_category_status                = get_post_meta( $request_post_id, 'cost_on_category_status', true );
					$cost_on_category_weight_status         = get_post_meta( $request_post_id, 'cost_on_category_weight_status', true );
					$cost_on_category_subtotal_status       = get_post_meta( $request_post_id, 'cost_on_category_subtotal_status', true );
                    $cost_on_tag_status                     = get_post_meta( $request_post_id, 'cost_on_tag_status', true );
                    $cost_on_tag_subtotal_status            = get_post_meta( $request_post_id, 'cost_on_tag_subtotal_status', true );
					$cost_on_tag_weight_status              = get_post_meta( $request_post_id, 'cost_on_tag_weight_status', true );
					$cost_on_total_cart_qty_status          = get_post_meta( $request_post_id, 'cost_on_total_cart_qty_status', true );
					$cost_on_total_cart_weight_status       = get_post_meta( $request_post_id, 'cost_on_total_cart_weight_status', true );
					$cost_on_total_cart_subtotal_status     = get_post_meta( $request_post_id, 'cost_on_total_cart_subtotal_status', true );
					$cost_on_shipping_class_status          = get_post_meta( $request_post_id, 'cost_on_shipping_class_status', true );
					$cost_on_shipping_class_weight_status   = get_post_meta( $request_post_id, 'cost_on_shipping_class_weight_status', true );
					$cost_on_shipping_class_subtotal_status = get_post_meta( $request_post_id, 'cost_on_shipping_class_subtotal_status', true );
					$cost_on_product_attribute_status       = get_post_meta( $request_post_id, 'cost_on_product_attribute_status', true );
					$sm_metabox_ap_product                  = get_post_meta( $request_post_id, 'sm_metabox_ap_product', true );
					$sm_metabox_ap_product_subtotal         = get_post_meta( $request_post_id, 'sm_metabox_ap_product_subtotal', true );
					$sm_metabox_ap_product_weight           = get_post_meta( $request_post_id, 'sm_metabox_ap_product_weight', true );
					$sm_metabox_ap_category                 = get_post_meta( $request_post_id, 'sm_metabox_ap_category', true );
					$sm_metabox_ap_category_subtotal        = get_post_meta( $request_post_id, 'sm_metabox_ap_category_subtotal', true );
					$sm_metabox_ap_category_weight          = get_post_meta( $request_post_id, 'sm_metabox_ap_category_weight', true );
                    $sm_metabox_ap_tag                      = get_post_meta( $request_post_id, 'sm_metabox_ap_tag', true );
                    $sm_metabox_ap_tag_subtotal             = get_post_meta( $request_post_id, 'sm_metabox_ap_tag_subtotal', true );
					$sm_metabox_ap_tag_weight               = get_post_meta( $request_post_id, 'sm_metabox_ap_tag_weight', true );
					$sm_metabox_ap_total_cart_qty           = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_qty', true );
					$sm_metabox_ap_total_cart_weight        = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_weight', true );
					$sm_metabox_ap_total_cart_subtotal      = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_subtotal', true );
					$sm_metabox_ap_shipping_class           = get_post_meta( $request_post_id, 'sm_metabox_ap_shipping_class', true );
					$sm_metabox_ap_shipping_class_weight    = get_post_meta( $request_post_id, 'sm_metabox_ap_shipping_class_weight', true );
					$sm_metabox_ap_shipping_class_subtotal  = get_post_meta( $request_post_id, 'sm_metabox_ap_shipping_class_subtotal', true );
					$sm_metabox_ap_product_attribute        = get_post_meta( $request_post_id, 'sm_metabox_ap_product_attribute', true );
                    
					$cost_rule_match                        = get_post_meta( $request_post_id, 'cost_rule_match', true );
					$sm_metabox_customize                   = array();
					if ( ! empty( $sm_metabox ) ) {
						foreach ( $sm_metabox as $key => $val ) {
							if ( 'product' === $val['product_fees_conditions_condition']
							     || 'variableproduct' === $val['product_fees_conditions_condition']
							     || 'category' === $val['product_fees_conditions_condition']
							     || 'tag' === $val['product_fees_conditions_condition']
							     || 'zone' === $val['product_fees_conditions_condition'] ) {
								$product_fees_conditions_values = $this->afrsm_pro_fetch_slug( $val['product_fees_conditions_values'], $val['product_fees_conditions_condition'] );
								$sm_metabox_customize[ $key ]   = array(
									'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
									'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
									'product_fees_conditions_values'    => $product_fees_conditions_values,
								);
							} else {
								$sm_metabox_customize[ $key ] = array(
									'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
									'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
									'product_fees_conditions_values'    => $val['product_fees_conditions_values'],
								);
							}
						}
					}
					if ( ! empty( $sm_extra_cost ) ) {
						foreach ( $sm_extra_cost as $key => $val ) {
							$shipping_class = $this->afrsm_pro_fetch_slug( $sm_extra_cost, 'shipping_class' );
						}
					} else {
						$shipping_class = array();
					}
					$sm_metabox_ap_product_customize = array();
					if ( ! empty( $sm_metabox_ap_product ) ) {
						foreach ( $sm_metabox_ap_product as $key => $val ) {
							$ap_fees_products_values                 = $this->afrsm_pro_fetch_slug( $val['ap_fees_products'], 'cpp' );
							$sm_metabox_ap_product_customize[ $key ] = array(
								'ap_fees_products'         => $ap_fees_products_values,
								'ap_fees_ap_prd_min_qty'   => $val['ap_fees_ap_prd_min_qty'],
								'ap_fees_ap_prd_max_qty'   => $val['ap_fees_ap_prd_max_qty'],
								'ap_fees_ap_price_product' => $val['ap_fees_ap_price_product'],
							);
						}
					}
					$sm_metabox_ap_product_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_product_subtotal ) ) {
						foreach ( $sm_metabox_ap_product_subtotal as $key => $val ) {
							$ap_fees_product_subtotal_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_product_subtotal'], 'cpp' );
							$sm_metabox_ap_product_subtotal_customize[ $key ] = array(
								'ap_fees_product_subtotal'                 => $ap_fees_product_subtotal_values,
								'ap_fees_ap_product_subtotal_min_subtotal' => $val['ap_fees_ap_product_subtotal_min_subtotal'],
								'ap_fees_ap_product_subtotal_max_subtotal' => $val['ap_fees_ap_product_subtotal_max_subtotal'],
								'ap_fees_ap_price_product_subtotal'        => $val['ap_fees_ap_price_product_subtotal'],
							);
						}
					}
					$sm_metabox_ap_product_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_product_weight ) ) {
						foreach ( $sm_metabox_ap_product_weight as $key => $val ) {
							$ap_fees_product_weight_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_product_weight'], 'cpp' );
							$sm_metabox_ap_product_weight_customize[ $key ] = array(
								'ap_fees_product_weight'            => $ap_fees_product_weight_values,
								'ap_fees_ap_product_weight_min_qty' => $val['ap_fees_ap_product_weight_min_qty'],
								'ap_fees_ap_product_weight_max_qty' => $val['ap_fees_ap_product_weight_max_qty'],
								'ap_fees_ap_price_product_weight'   => $val['ap_fees_ap_price_product_weight'],
							);
						}
					}
					$sm_metabox_ap_category_customize = array();
					if ( ! empty( $sm_metabox_ap_category ) ) {
						foreach ( $sm_metabox_ap_category as $key => $val ) {
							$ap_fees_category_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_categories'], 'cpc' );
							$sm_metabox_ap_category_customize[ $key ] = array(
								'ap_fees_categories'        => $ap_fees_category_values,
								'ap_fees_ap_cat_min_qty'    => $val['ap_fees_ap_cat_min_qty'],
								'ap_fees_ap_cat_max_qty'    => $val['ap_fees_ap_cat_max_qty'],
								'ap_fees_ap_price_category' => $val['ap_fees_ap_price_category'],
							);
						}
					}
					$sm_metabox_ap_category_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_category_subtotal ) ) {
						foreach ( $sm_metabox_ap_category_subtotal as $key => $val ) {
							$ap_fees_category_subtotal_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_category_subtotal'], 'cpc' );
							$sm_metabox_ap_category_subtotal_customize[ $key ] = array(
								'ap_fees_category_subtotal'                 => $ap_fees_category_subtotal_values,
								'ap_fees_ap_category_subtotal_min_subtotal' => $val['ap_fees_ap_category_subtotal_min_subtotal'],
								'ap_fees_ap_category_subtotal_max_subtotal' => $val['ap_fees_ap_category_subtotal_max_subtotal'],
								'ap_fees_ap_price_category_subtotal'        => $val['ap_fees_ap_price_category_subtotal'],
							);
						}
					}
					$sm_metabox_ap_category_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_category_weight ) ) {
						foreach ( $sm_metabox_ap_category_weight as $key => $val ) {
							$ap_fees_category_weight_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_categories_weight'], 'cpc' );
							$sm_metabox_ap_category_weight_customize[ $key ] = array(
								'ap_fees_categories_weight'          => $ap_fees_category_weight_values,
								'ap_fees_ap_category_weight_min_qty' => $val['ap_fees_ap_category_weight_min_qty'],
								'ap_fees_ap_category_weight_max_qty' => $val['ap_fees_ap_category_weight_max_qty'],
								'ap_fees_ap_price_category_weight'   => $val['ap_fees_ap_price_category_weight'],
							);
						}
					}
                    $sm_metabox_ap_tag_customize = array();
					if ( ! empty( $sm_metabox_ap_tag ) ) {
						foreach ( $sm_metabox_ap_tag as $key => $val ) {
							$ap_fees_tag_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_tags'], 'cpc' );
							$sm_metabox_ap_tag_customize[ $key ] = array(
								'ap_fees_tags'              => $ap_fees_tag_values,
								'ap_fees_ap_tag_min_qty'    => $val['ap_fees_ap_tag_min_qty'],
								'ap_fees_ap_tag_max_qty'    => $val['ap_fees_ap_tag_max_qty'],
								'ap_fees_ap_price_tag' => $val['ap_fees_ap_price_tag'],
							);
						}
					}
                    $sm_metabox_ap_tag_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_tag_subtotal ) ) {
						foreach ( $sm_metabox_ap_tag_subtotal as $key => $val ) {
							$ap_fees_tag_subtotal_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_tag_subtotal'], 'cpc' );
							$sm_metabox_ap_tag_subtotal_customize[ $key ] = array(
								'ap_fees_tag_subtotal'                 => $ap_fees_tag_subtotal_values,
								'ap_fees_ap_tag_subtotal_min_subtotal' => $val['ap_fees_ap_tag_subtotal_min_subtotal'],
								'ap_fees_ap_tag_subtotal_max_subtotal' => $val['ap_fees_ap_tag_subtotal_max_subtotal'],
								'ap_fees_ap_price_tag_subtotal'        => $val['ap_fees_ap_price_tag_subtotal'],
							);
						}
					}
                    $sm_metabox_ap_tag_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_tag_weight ) ) {
						foreach ( $sm_metabox_ap_tag_weight as $key => $val ) {
							$ap_fees_tag_weight_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_tag_weight'], 'cpc' );
							$sm_metabox_ap_tag_weight_customize[ $key ] = array(
								'ap_fees_tag_weight'            => $ap_fees_tag_weight_values,
								'ap_fees_ap_tag_weight_min_qty' => $val['ap_fees_ap_tag_weight_min_qty'],
								'ap_fees_ap_tag_weight_max_qty' => $val['ap_fees_ap_tag_weight_max_qty'],
								'ap_fees_ap_price_tag_weight'   => $val['ap_fees_ap_price_tag_weight'],
							);
						}
					}
					$sm_metabox_ap_total_cart_qty_customize = array();
					if ( ! empty( $sm_metabox_ap_total_cart_qty ) ) {
						foreach ( $sm_metabox_ap_total_cart_qty as $key => $val ) {
							$ap_fees_total_cart_qty_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_total_cart_qty'], '' );
							$sm_metabox_ap_total_cart_qty_customize[ $key ] = array(
								'ap_fees_total_cart_qty'            => $ap_fees_total_cart_qty_values,
								'ap_fees_ap_total_cart_qty_min_qty' => $val['ap_fees_ap_total_cart_qty_min_qty'],
								'ap_fees_ap_total_cart_qty_max_qty' => $val['ap_fees_ap_total_cart_qty_max_qty'],
								'ap_fees_ap_price_total_cart_qty'   => $val['ap_fees_ap_price_total_cart_qty'],
							);
						}
					}
					$sm_metabox_ap_total_cart_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_total_cart_weight ) ) {
						foreach ( $sm_metabox_ap_total_cart_weight as $key => $val ) {
							$ap_fees_total_cart_weight_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_total_cart_weight'], '' );
							$sm_metabox_ap_total_cart_weight_customize[ $key ] = array(
								'ap_fees_total_cart_weight'               => $ap_fees_total_cart_weight_values,
								'ap_fees_ap_total_cart_weight_min_weight' => $val['ap_fees_ap_total_cart_weight_min_weight'],
								'ap_fees_ap_total_cart_weight_max_weight' => $val['ap_fees_ap_total_cart_weight_max_weight'],
								'ap_fees_ap_price_total_cart_weight'      => $val['ap_fees_ap_price_total_cart_weight'],
							);
						}
					}
					$sm_metabox_ap_total_cart_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_total_cart_subtotal ) ) {
						foreach ( $sm_metabox_ap_total_cart_subtotal as $key => $val ) {
							$ap_fees_total_cart_subtotal_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_total_cart_subtotal'], '' );
							$sm_metabox_ap_total_cart_subtotal_customize[ $key ] = array(
								'ap_fees_total_cart_subtotal'                 => $ap_fees_total_cart_subtotal_values,
								'ap_fees_ap_total_cart_subtotal_min_subtotal' => $val['ap_fees_ap_total_cart_subtotal_min_subtotal'],
								'ap_fees_ap_total_cart_subtotal_max_subtotal' => $val['ap_fees_ap_total_cart_subtotal_max_subtotal'],
								'ap_fees_ap_price_total_cart_subtotal'        => $val['ap_fees_ap_price_total_cart_subtotal'],
							);
						}
					}
                    $sm_metabox_ap_shipping_class_customize = array();
					if ( ! empty( $sm_metabox_ap_shipping_class ) ) {
						foreach ( $sm_metabox_ap_shipping_class as $key => $val ) {
							$ap_fees_shipping_class_values                 = $this->afrsm_pro_fetch_slug( $val['ap_fees_shipping_classes'], 'cpsc' );
							$sm_metabox_ap_shipping_class_customize[ $key ] = array(
								'ap_fees_shipping_classes'          => $ap_fees_shipping_class_values,
								'ap_fees_ap_shipping_class_min_qty' => $val['ap_fees_ap_shipping_class_min_qty'],
								'ap_fees_ap_shipping_class_max_qty' => $val['ap_fees_ap_shipping_class_max_qty'],
								'ap_fees_ap_price_shipping_class'   => $val['ap_fees_ap_price_shipping_class'],
							);
						}
					}
                    
                    $sm_metabox_ap_shipping_class_weight_customize = array();
					if ( ! empty( $sm_metabox_ap_shipping_class_weight ) ) {
						foreach ( $sm_metabox_ap_shipping_class_weight as $key => $val ) {
							$ap_fees_shipping_class_weight_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_shipping_class_weight'], 'cpsc' );
							$sm_metabox_ap_shipping_class_weight_customize[ $key ] = array(
								'ap_fees_shipping_class_weight'                 => $ap_fees_shipping_class_weight_values,
								'ap_fees_ap_shipping_class_weight_min_weight'   => $val['ap_fees_ap_shipping_class_weight_min_weight'],
								'ap_fees_ap_shipping_class_weight_max_weight'   => $val['ap_fees_ap_shipping_class_weight_max_weight'],
								'ap_fees_ap_price_shipping_class_weight'        => $val['ap_fees_ap_price_shipping_class_weight'],
							);
						}
					}

					$sm_metabox_ap_shipping_class_subtotal_customize = array();
					if ( ! empty( $sm_metabox_ap_shipping_class_subtotal ) ) {
						foreach ( $sm_metabox_ap_shipping_class_subtotal as $key => $val ) {
							$ap_fees_shipping_class_subtotal_values                  = $this->afrsm_pro_fetch_slug( $val['ap_fees_shipping_class_subtotals'], 'cpsc' );
							$sm_metabox_ap_shipping_class_subtotal_customize[ $key ] = array(
								'ap_fees_shipping_class_subtotals'                => $ap_fees_shipping_class_subtotal_values,
								'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
								'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
								'ap_fees_ap_price_shipping_class_subtotal'        => $val['ap_fees_ap_price_shipping_class_subtotal'],
							);
						}
					}

					$sm_metabox_ap_product_attribute_customize = array();
					if ( ! empty( $sm_metabox_ap_product_attribute ) ) {
						foreach ( $sm_metabox_ap_product_attribute as $key => $val ) {
							$ap_fees_product_attribute_values  = $this->afrsm_pro_fetch_slug( $val['ap_fees_product_attributes'], 'cpsc' );
							$sm_metabox_ap_product_attribute_customize[ $key ] = array(
								'ap_fees_product_attributes'           => $val['ap_fees_product_attributes'],
								'ap_fees_ap_product_attribute_min_qty' => $val['ap_fees_ap_product_attribute_min_qty'],
								'ap_fees_ap_product_attribute_max_qty' => $val['ap_fees_ap_product_attribute_max_qty'],
								'ap_fees_ap_price_product_attribute'   => $val['ap_fees_ap_price_product_attribute'],
							);
						}
					}
					$fees_data[ $request_post_id ] = array(
						'sm_title'                               => $sm_title,
						'fee_settings_unique_shipping_title'     => $fee_settings_unique_shipping_title,
						'sm_cost'                                => $sm_cost,
						'sm_free_shipping_based_on'              => $sm_free_shipping_based_on,
						'is_allow_free_shipping'                 => $is_allow_free_shipping,
						'sm_free_shipping_cost'                  => $sm_free_shipping_cost,
						'sm_free_shipping_cost_before_discount'  => $sm_free_shipping_cost_before_discount,
						'sm_free_shipping_cost_left_notice'      => $sm_free_shipping_cost_left_notice,
						'sm_free_shipping_cost_left_notice_msg'  => $sm_free_shipping_cost_left_notice_msg,
						'sm_free_shipping_coupan_cost'           => $sm_free_shipping_coupan_cost,
						'sm_free_shipping_label'                 => $sm_free_shipping_label,
						'sm_tooltip_type'                        => $sm_tooltip_type,
						'sm_tooltip_desc'                        => $sm_tooltip_desc,
						'sm_start_date'                          => $sm_start_date,
						'sm_end_date'                            => $sm_end_date,
						'sm_start_time'                          => $sm_time_from,
						'sm_end_time'                            => $sm_time_to,
						'sm_select_day_of_week'                  => $sm_select_day_of_week,
						'sm_estimation_delivery'                 => $sm_estimation_delivery,
						'sm_select_taxable'                      => $sm_is_taxable,
						'sm_select_shipping_provider'            => $sm_select_shipping_provider,
						'status'                                 => $sm_status,
						'product_fees_metabox'                   => $sm_metabox_customize,
						'sm_extra_cost'                          => $shipping_class,
						'sm_extra_cost_calc_type'                => $sm_extra_cost_calc_type,
						'sm_fee_chk_qty_price'                   => $getFeesPerQtyFlag,
						'sm_fee_per_qty'                         => $getFeesPerQty,
						'sm_extra_product_cost'                  => $extraProductCost,
						'ap_rule_status'                         => $ap_rule_status,
						'cost_on_product_status'                 => $cost_on_product_status,
						'cost_on_product_weight_status'          => $cost_on_product_weight_status,
						'cost_on_product_subtotal_status'        => $cost_on_product_subtotal_status,
						'cost_on_category_status'                => $cost_on_category_status,
						'cost_on_category_weight_status'         => $cost_on_category_weight_status,
						'cost_on_category_subtotal_status'       => $cost_on_category_subtotal_status,
                        'cost_on_tag_status'                     => $cost_on_tag_status,
                        'cost_on_tag_subtotal_status'            => $cost_on_tag_subtotal_status,
                        'cost_on_tag_weight_status'              => $cost_on_tag_weight_status,
						'cost_on_total_cart_qty_status'          => $cost_on_total_cart_qty_status,
						'cost_on_total_cart_weight_status'       => $cost_on_total_cart_weight_status,
						'cost_on_total_cart_subtotal_status'     => $cost_on_total_cart_subtotal_status,
                        'cost_on_shipping_class_status'          => $cost_on_shipping_class_status,
                        'cost_on_shipping_class_weight_status'   => $cost_on_shipping_class_weight_status,
						'cost_on_shipping_class_subtotal_status' => $cost_on_shipping_class_subtotal_status,
						'cost_on_product_attribute_status'       => $cost_on_product_attribute_status,
						'sm_metabox_ap_product'                  => $sm_metabox_ap_product_customize,
						'sm_metabox_ap_product_subtotal'         => $sm_metabox_ap_product_subtotal_customize,
						'sm_metabox_ap_product_weight'           => $sm_metabox_ap_product_weight_customize,
						'sm_metabox_ap_category'                 => $sm_metabox_ap_category_customize,
						'sm_metabox_ap_category_subtotal'        => $sm_metabox_ap_category_subtotal_customize,
						'sm_metabox_ap_category_weight'          => $sm_metabox_ap_category_weight_customize,
                        'sm_metabox_ap_tag'                      => $sm_metabox_ap_tag_customize,
                        'sm_metabox_ap_tag_subtotal'             => $sm_metabox_ap_tag_subtotal_customize,
						'sm_metabox_ap_tag_weight'               => $sm_metabox_ap_tag_weight_customize,
						'sm_metabox_ap_total_cart_qty'           => $sm_metabox_ap_total_cart_qty_customize,
						'sm_metabox_ap_total_cart_weight'        => $sm_metabox_ap_total_cart_weight_customize,
						'sm_metabox_ap_total_cart_subtotal'      => $sm_metabox_ap_total_cart_subtotal_customize,
                        'sm_metabox_ap_shipping_class'           => $sm_metabox_ap_shipping_class_customize,
                        'sm_metabox_ap_shipping_class_weight'    => $sm_metabox_ap_shipping_class_weight_customize,
						'sm_metabox_ap_shipping_class_subtotal'  => $sm_metabox_ap_shipping_class_subtotal_customize,
						'sm_metabox_ap_product_attribute'        => $sm_metabox_ap_product_attribute_customize,
						'cost_rule_match'                        => $cost_rule_match,
					);
				}
				$get_sort_order = get_option( 'sm_sortable_order_' . $default_lang );
				$main_data      = array(
					'fees_data'      => $fees_data,
					'shipping_order' => $get_sort_order,
				);
			}
			$afrsm_export_action_nonce = filter_input( INPUT_POST, 'afrsm_export_action_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if ( ! wp_verify_nonce( $afrsm_export_action_nonce, 'afrsm_export_save_action_nonce' ) ) {
				return;
			}
			ignore_user_abort( true );
			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=afrsm-settings-export-' . gmdate( 'm-d-Y' ) . '.json' );
			header( "Expires: 0" );
			echo wp_json_encode( $main_data );
			exit;
		}
		if ( ! empty( $import_action ) || 'import_settings' === $import_action ) {
			$afrsm_import_action_nonce = filter_input( INPUT_POST, 'afrsm_import_action_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if ( ! wp_verify_nonce( $afrsm_import_action_nonce, 'afrsm_import_action_nonce' ) ) {
				return;
			}
			$file_import_file_args              = array(
				'import_file' => array(
					'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
					'flags'  => FILTER_FORCE_ARRAY,
				),
			);
			$attached_import_files__arr         = filter_var_array( $_FILES, $file_import_file_args );
			$attached_import_files__arr_explode = explode( '.', $attached_import_files__arr['import_file']['name'] );
			$extension                          = end( $attached_import_files__arr_explode );
			if ( $extension !== 'json' ) {
				wp_die( esc_html__( 'Please upload a valid .json file', 'advanced-flat-rate-shipping-for-woocommerce' ) );
			}
			$import_file = $attached_import_files__arr['import_file']['tmp_name'];
			if ( empty( $import_file ) ) {
				wp_die( esc_html__( 'Please upload a file to import', 'advanced-flat-rate-shipping-for-woocommerce' ) );
			}
			WP_Filesystem();
			global $wp_filesystem;
			$file_data = $wp_filesystem->get_contents( $import_file );
			if ( ! empty( $file_data ) ) {
				$file_data_decode = json_decode( $file_data, true );
				$new_sorting_id   = array();
				if ( ! empty( $file_data_decode['fees_data'] ) ) {
					foreach ( $file_data_decode['fees_data'] as $fees_val ) {
						$fee_post    = array(
							'post_title'  => $fees_val['sm_title'],
							'post_status' => $fees_val['status'],
							'post_type'   => self::afrsm_shipping_post_type,
						);
                        $fount_post = post_exists( $fees_val['sm_title'], '', '', self::afrsm_shipping_post_type );
                        if( $fount_post > 0 && !empty($fount_post) ) {
                            $fee_post['ID'] = $fount_post;
                            $get_post_id = wp_update_post( $fee_post );
                        } else {
                            $get_post_id = wp_insert_post( $fee_post );
                        }
						if ( '' !== $get_post_id && 0 !== $get_post_id ) {
							if ( $get_post_id > 0 ) {
								$new_sorting_id[]     = $get_post_id;
								$sm_metabox_customize = array();
								if ( ! empty( $fees_val['product_fees_metabox'] ) ) {
									foreach ( $fees_val['product_fees_metabox'] as $key => $val ) {
										if ( 'product' === $val['product_fees_conditions_condition']
										     || 'variableproduct' === $val['product_fees_conditions_condition']
										     || 'category' === $val['product_fees_conditions_condition']
										     || 'tag' === $val['product_fees_conditions_condition']
										     || 'zone' === $val['product_fees_conditions_condition'] ) {
											$product_fees_conditions_values = $this->afrsm_pro_fetch_id( $val['product_fees_conditions_values'], $val['product_fees_conditions_condition'] );
											$sm_metabox_customize[ $key ]   = array(
												'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
												'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
												'product_fees_conditions_values'    => $product_fees_conditions_values,
											);
										} else {
											$sm_metabox_customize[ $key ] = array(
												'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
												'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
												'product_fees_conditions_values'    => $val['product_fees_conditions_values'],
											);
										}
									}
								}
								if ( ! empty( $fees_val['sm_extra_cost'] ) ) {
									foreach ( $fees_val['sm_extra_cost'] as $key => $val ) {
										$shipping_class = $this->afrsm_pro_fetch_id( $fees_val['sm_extra_cost'], 'shipping_class' );
									}
								} else {
									$shipping_class = array();
								}

								$sm_metabox_product_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product'] as $key => $val ) {
										$ap_fees_products_values              = $this->afrsm_pro_fetch_id( $val['ap_fees_products'], 'cpp' );
										$sm_metabox_product_customize[ $key ] = array(
											'ap_fees_products'         => $ap_fees_products_values,
											'ap_fees_ap_prd_min_qty'   => $val['ap_fees_ap_prd_min_qty'],
											'ap_fees_ap_prd_max_qty'   => $val['ap_fees_ap_prd_max_qty'],
											'ap_fees_ap_price_product' => $val['ap_fees_ap_price_product'],
										);
									}
								}

								$sm_metabox_ap_product_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product_subtotal'] as $key => $val ) {
										$ap_fees_products_subtotal_values                 = $this->afrsm_pro_fetch_id( $val['ap_fees_product_subtotal'], 'cpp' );
										$sm_metabox_ap_product_subtotal_customize[ $key ] = array(
											'ap_fees_product_subtotal'                 => $ap_fees_products_subtotal_values,
											'ap_fees_ap_product_subtotal_min_subtotal' => $val['ap_fees_ap_product_subtotal_min_subtotal'],
											'ap_fees_ap_product_subtotal_max_subtotal' => $val['ap_fees_ap_product_subtotal_max_subtotal'],
											'ap_fees_ap_price_product_subtotal'        => $val['ap_fees_ap_price_product_subtotal'],
										);
									}
								}

								$sm_metabox_ap_product_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product_weight'] as $key => $val ) {
										$ap_fees_products_weight_values                 = $this->afrsm_pro_fetch_id( $val['ap_fees_product_weight'], 'cpp' );
										$sm_metabox_ap_product_weight_customize[ $key ] = array(
											'ap_fees_product_weight'            => $ap_fees_products_weight_values,
											'ap_fees_ap_product_weight_min_qty' => $val['ap_fees_ap_product_weight_min_qty'],
											'ap_fees_ap_product_weight_max_qty' => $val['ap_fees_ap_product_weight_max_qty'],
											'ap_fees_ap_price_product_weight'   => $val['ap_fees_ap_price_product_weight'],
										);
									}
								}

								$sm_metabox_ap_category_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_category'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_category'] as $key => $val ) {
										$ap_fees_category_values                  = $this->afrsm_pro_fetch_id( $val['ap_fees_categories'], 'cpc' );
										$sm_metabox_ap_category_customize[ $key ] = array(
											'ap_fees_categories'        => $ap_fees_category_values,
											'ap_fees_ap_cat_min_qty'    => $val['ap_fees_ap_cat_min_qty'],
											'ap_fees_ap_cat_max_qty'    => $val['ap_fees_ap_cat_max_qty'],
											'ap_fees_ap_price_category' => $val['ap_fees_ap_price_category'],
										);
									}
								}

								$sm_metabox_ap_category_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_category_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_category_subtotal'] as $key => $val ) {
										$ap_fees_ap_category_subtotal_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_category_subtotal'], 'cpc' );
										$sm_metabox_ap_category_subtotal_customize[ $key ] = array(
											'ap_fees_category_subtotal'                 => $ap_fees_ap_category_subtotal_values,
											'ap_fees_ap_category_subtotal_min_subtotal' => $val['ap_fees_ap_category_subtotal_min_subtotal'],
											'ap_fees_ap_category_subtotal_max_subtotal' => $val['ap_fees_ap_category_subtotal_max_subtotal'],
											'ap_fees_ap_price_category_subtotal'        => $val['ap_fees_ap_price_category_subtotal'],
										);
									}
								}

								$sm_metabox_ap_category_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_category_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_category_weight'] as $key => $val ) {
										$ap_fees_ap_category_weight_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_categories_weight'], 'cpc' );
										$sm_metabox_ap_category_weight_customize[ $key ] = array(
											'ap_fees_categories_weight'          => $ap_fees_ap_category_weight_values,
											'ap_fees_ap_category_weight_min_qty' => $val['ap_fees_ap_category_weight_min_qty'],
											'ap_fees_ap_category_weight_max_qty' => $val['ap_fees_ap_category_weight_max_qty'],
											'ap_fees_ap_price_category_weight'   => $val['ap_fees_ap_price_category_weight'],
										);
									}
								}

                                $sm_metabox_ap_tag_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_tag'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_tag'] as $key => $val ) {
										$ap_fees_tag_values                  = $this->afrsm_pro_fetch_id( $val['ap_fees_tags'], 'cpc' );
										$sm_metabox_ap_tag_customize[ $key ] = array(
											'ap_fees_tags'              => $ap_fees_tag_values,
											'ap_fees_ap_tag_min_qty'    => $val['ap_fees_ap_tag_min_qty'],
											'ap_fees_ap_tag_max_qty'    => $val['ap_fees_ap_tag_max_qty'],
											'ap_fees_ap_price_tag'      => $val['ap_fees_ap_price_tag'],
										);
									}
								}

                                $sm_metabox_ap_tag_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_tag_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_tag_subtotal'] as $key => $val ) {
										$ap_fees_ap_tag_subtotal_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_tag_subtotal'], 'cpc' );
										$sm_metabox_ap_tag_subtotal_customize[ $key ] = array(
											'ap_fees_tag_subtotal'                 => $ap_fees_ap_tag_subtotal_values,
											'ap_fees_ap_tag_subtotal_min_subtotal' => $val['ap_fees_ap_tag_subtotal_min_subtotal'],
											'ap_fees_ap_tag_subtotal_max_subtotal' => $val['ap_fees_ap_tag_subtotal_max_subtotal'],
											'ap_fees_ap_price_tag_subtotal'        => $val['ap_fees_ap_price_tag_subtotal'],
										);
									}
								}

                                $sm_metabox_ap_tag_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_tag_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_tag_weight'] as $key => $val ) {
										$ap_fees_ap_tag_weight_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_tag_weight'], 'cpc' );
										$sm_metabox_ap_tag_weight_customize[ $key ] = array(
											'ap_fees_tag_weight'            => $ap_fees_ap_tag_weight_values,
											'ap_fees_ap_tag_weight_min_qty' => $val['ap_fees_ap_tag_weight_min_qty'],
											'ap_fees_ap_tag_weight_max_qty' => $val['ap_fees_ap_tag_weight_max_qty'],
											'ap_fees_ap_price_tag_weight'   => $val['ap_fees_ap_price_tag_weight'],
										);
									}
								}

								$sm_metabox_ap_total_cart_qty_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_total_cart_qty'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_total_cart_qty'] as $key => $val ) {
										$ap_fees_ap_total_cart_qty_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_total_cart_qty'], '' );
										$sm_metabox_ap_total_cart_qty_customize[ $key ] = array(
											'ap_fees_total_cart_qty'            => $ap_fees_ap_total_cart_qty_values,
											'ap_fees_ap_total_cart_qty_min_qty' => $val['ap_fees_ap_total_cart_qty_min_qty'],
											'ap_fees_ap_total_cart_qty_max_qty' => $val['ap_fees_ap_total_cart_qty_max_qty'],
											'ap_fees_ap_price_total_cart_qty'   => $val['ap_fees_ap_price_total_cart_qty'],
										);
									}
								}

								$sm_metabox_ap_total_cart_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_total_cart_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_total_cart_weight'] as $key => $val ) {
										$ap_fees_ap_total_cart_weight_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_total_cart_weight'], '' );
										$sm_metabox_ap_total_cart_weight_customize[ $key ] = array(
											'ap_fees_total_cart_weight'               => $ap_fees_ap_total_cart_weight_values,
											'ap_fees_ap_total_cart_weight_min_weight' => $val['ap_fees_ap_total_cart_weight_min_weight'],
											'ap_fees_ap_total_cart_weight_max_weight' => $val['ap_fees_ap_total_cart_weight_max_weight'],
											'ap_fees_ap_price_total_cart_weight'      => $val['ap_fees_ap_price_total_cart_weight'],
										);
									}
								}

								$sm_metabox_ap_total_cart_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_total_cart_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_total_cart_subtotal'] as $key => $val ) {
										$ap_fees_ap_total_cart_subtotal_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_total_cart_subtotal'], '' );
										$sm_metabox_ap_total_cart_subtotal_customize[ $key ] = array(
											'ap_fees_total_cart_subtotal'                 => $ap_fees_ap_total_cart_subtotal_values,
											'ap_fees_ap_total_cart_subtotal_min_subtotal' => $val['ap_fees_ap_total_cart_subtotal_min_subtotal'],
											'ap_fees_ap_total_cart_subtotal_max_subtotal' => $val['ap_fees_ap_total_cart_subtotal_max_subtotal'],
											'ap_fees_ap_price_total_cart_subtotal'        => $val['ap_fees_ap_price_total_cart_subtotal'],
										);
									}
								}

                                $sm_metabox_ap_shipping_class_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_shipping_class'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_shipping_class'] as $key => $val ) {
										$ap_fees_shipping_classes_values      = $this->afrsm_pro_fetch_id( $val['ap_fees_shipping_classes'], 'shipping_class' );
										$sm_metabox_ap_shipping_class_customize[ $key ] = array(
											'ap_fees_shipping_classes'          => $ap_fees_shipping_classes_values,
											'ap_fees_ap_shipping_class_min_qty' => $val['ap_fees_ap_shipping_class_min_qty'],
											'ap_fees_ap_shipping_class_max_qty' => $val['ap_fees_ap_shipping_class_max_qty'],
											'ap_fees_ap_price_shipping_class'   => $val['ap_fees_ap_price_shipping_class'],
										);
									}
                                }

                                $sm_metabox_ap_shipping_class_weight_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_shipping_class_weight'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_shipping_class_weight'] as $key => $val ) {
										$ap_fees_ap_shipping_class_weight_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_shipping_class_weight'], 'cpsc' );
										$sm_metabox_ap_shipping_class_weight_customize[ $key ] = array(
											'ap_fees_shipping_class_weight'                => $ap_fees_ap_shipping_class_weight_values,
											'ap_fees_ap_shipping_class_weight_min_weight' => $val['ap_fees_ap_shipping_class_weight_min_weight'],
											'ap_fees_ap_shipping_class_weight_max_weight' => $val['ap_fees_ap_shipping_class_weight_max_weight'],
											'ap_fees_ap_price_shipping_class_weight'        => $val['ap_fees_ap_price_shipping_class_weight'],
										);
									}
								}

								$sm_metabox_ap_shipping_class_subtotal_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_shipping_class_subtotal'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_shipping_class_subtotal'] as $key => $val ) {
										$ap_fees_ap_shipping_class_subtotal_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_shipping_class_subtotals'], 'cpsc' );
										$sm_metabox_ap_shipping_class_subtotal_customize[ $key ] = array(
											'ap_fees_shipping_class_subtotals'                => $ap_fees_ap_shipping_class_subtotal_values,
											'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
											'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
											'ap_fees_ap_price_shipping_class_subtotal'        => $val['ap_fees_ap_price_shipping_class_subtotal'],
										);
									}
								}
								$sm_metabox_ap_product_attribute_customize = array();
								if ( ! empty( $fees_val['sm_metabox_ap_product_attribute'] ) ) {
									foreach ( $fees_val['sm_metabox_ap_product_attribute'] as $key => $val ) {
										$ap_fees_ap_product_attribute_values               = $this->afrsm_pro_fetch_id( $val['ap_fees_product_attributes'], '' );
										$sm_metabox_ap_product_attribute_customize[ $key ] = array(
											'ap_fees_product_attributes'            => $ap_fees_ap_product_attribute_values,
											'ap_fees_ap_product_attribute_min_qty'  => $val['ap_fees_ap_product_attribute_min_qty'],
											'ap_fees_ap_product_attribute_max_qty'  => $val['ap_fees_ap_product_attribute_max_qty'],
											'ap_fees_ap_price_product_attribute'    => $val['ap_fees_ap_price_product_attribute'],
										);
									}
								}
								update_post_meta( $get_post_id, 'fee_settings_unique_shipping_title', $fees_val['fee_settings_unique_shipping_title'] );
								update_post_meta( $get_post_id, 'sm_product_cost', $fees_val['sm_cost'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_based_on', $fees_val['sm_free_shipping_based_on'] );
								update_post_meta( $get_post_id, 'is_allow_free_shipping', $fees_val['is_allow_free_shipping'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_cost', $fees_val['sm_free_shipping_cost'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_cost_before_discount', $fees_val['sm_free_shipping_cost_before_discount'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_cost_left_notice', $fees_val['sm_free_shipping_cost_left_notice'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_cost_left_notice_msg', $fees_val['sm_free_shipping_cost_left_notice_msg'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_coupan_cost', $fees_val['sm_free_shipping_coupan_cost'] );
								update_post_meta( $get_post_id, 'sm_free_shipping_label', $fees_val['sm_free_shipping_label'] );
								update_post_meta( $get_post_id, 'sm_tooltip_type', $fees_val['sm_tooltip_type'] );
								update_post_meta( $get_post_id, 'sm_tooltip_desc', $fees_val['sm_tooltip_desc'] );
								update_post_meta( $get_post_id, 'sm_start_date', $fees_val['sm_start_date'] );
								update_post_meta( $get_post_id, 'sm_end_date', $fees_val['sm_end_date'] );
								update_post_meta( $get_post_id, 'sm_time_from', $fees_val['sm_start_time'] );
								update_post_meta( $get_post_id, 'sm_time_to', $fees_val['sm_end_time'] );
								update_post_meta( $get_post_id, 'sm_select_day_of_week', $fees_val['sm_select_day_of_week'] );
								update_post_meta( $get_post_id, 'sm_estimation_delivery', $fees_val['sm_estimation_delivery'] );
								update_post_meta( $get_post_id, 'sm_select_taxable', $fees_val['sm_select_taxable'] );
								update_post_meta( $get_post_id, 'sm_select_shipping_provider', $fees_val['sm_select_shipping_provider'] );
								update_post_meta( $get_post_id, 'sm_metabox', $sm_metabox_customize );
								update_post_meta( $get_post_id, 'sm_extra_cost', $shipping_class );
								update_post_meta( $get_post_id, 'sm_extra_cost_calculation_type', $fees_val['sm_extra_cost_calc_type'] );
								update_post_meta( $get_post_id, 'sm_fee_chk_qty_price', $fees_val['sm_fee_chk_qty_price'] );
								update_post_meta( $get_post_id, 'sm_fee_per_qty', $fees_val['sm_fee_per_qty'] );
								update_post_meta( $get_post_id, 'sm_extra_product_cost', $fees_val['sm_extra_product_cost'] );
								update_post_meta( $get_post_id, 'ap_rule_status', $fees_val['ap_rule_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_status', $fees_val['cost_on_product_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_weight_status', $fees_val['cost_on_product_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_subtotal_status', $fees_val['cost_on_product_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_category_status', $fees_val['cost_on_category_status'] );
								update_post_meta( $get_post_id, 'cost_on_category_weight_status', $fees_val['cost_on_category_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_category_subtotal_status', $fees_val['cost_on_category_subtotal_status'] );
                                update_post_meta( $get_post_id, 'cost_on_tag_status', $fees_val['cost_on_tag_status'] );
								update_post_meta( $get_post_id, 'cost_on_tag_subtotal_status', $fees_val['cost_on_tag_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_tag_weight_status', $fees_val['cost_on_tag_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_total_cart_qty_status', $fees_val['cost_on_total_cart_qty_status'] );
								update_post_meta( $get_post_id, 'cost_on_total_cart_weight_status', $fees_val['cost_on_total_cart_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_shipping_class_status', $fees_val['cost_on_shipping_class_status'] );
                                update_post_meta( $get_post_id, 'cost_on_total_cart_subtotal_status', $fees_val['cost_on_total_cart_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_shipping_class_weight_status', $fees_val['cost_on_shipping_class_weight_status'] );
								update_post_meta( $get_post_id, 'cost_on_shipping_class_subtotal_status', $fees_val['cost_on_shipping_class_subtotal_status'] );
								update_post_meta( $get_post_id, 'cost_on_product_attribute_status', $fees_val['cost_on_product_attribute_status'] );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product', $sm_metabox_product_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product_subtotal', $sm_metabox_ap_product_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product_weight', $sm_metabox_ap_product_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_category', $sm_metabox_ap_category_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_category_subtotal', $sm_metabox_ap_category_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_category_weight', $sm_metabox_ap_category_weight_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_tag', $sm_metabox_ap_tag_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_tag_subtotal', $sm_metabox_ap_tag_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_tag_weight', $sm_metabox_ap_tag_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_qty', $sm_metabox_ap_total_cart_qty_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_weight', $sm_metabox_ap_total_cart_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_subtotal', $sm_metabox_ap_total_cart_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class', $sm_metabox_ap_shipping_class_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_weight', $sm_metabox_ap_shipping_class_weight_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_subtotal', $sm_metabox_ap_shipping_class_subtotal_customize );
								update_post_meta( $get_post_id, 'sm_metabox_ap_product_attribute', $sm_metabox_ap_product_attribute_customize );
								update_post_meta( $get_post_id, 'cost_rule_match', $fees_val['cost_rule_match'] );
							}
						}
					}
					update_option( 'sm_sortable_order_' . $default_lang, $new_sorting_id );
				}
			}
			wp_safe_redirect( add_query_arg( array(
				'page'   => 'afrsm-pro-import-export',
				'status' => 'success',
			), admin_url( 'admin.php' ) ) );
			exit;
		}
	}
	/**
	 * Export Zone
	 *
	 * @since 3.6.1
	 *
	 */
	public function afrsm_pro_import_export_zone() {
		$get_all_fees_args  = array(
			'post_type'      => self::afrsm_zone_post_type,
			'order'          => 'DESC',
			'posts_per_page' => - 1,
			'orderby'        => 'ID',
		);
		$get_all_fees_query = new WP_Query( $get_all_fees_args );
		$get_all_fees       = $get_all_fees_query->get_posts();
		$get_all_fees_count = $get_all_fees_query->found_posts;
		$fees_data          = array();
		if ( $get_all_fees_count > 0 ) {
			foreach ( $get_all_fees as $fees ) {
				$request_post_id               = $fees->ID;
				$sm_status                     = get_post_status( $request_post_id );
				$sm_title                      = __( get_the_title( $request_post_id ), 'advanced-flat-rate-shipping-for-woocommerce' );
				$location_type                 = get_post_meta( $request_post_id, 'location_type', true );
				$zone_type                     = get_post_meta( $request_post_id, 'zone_type', true );
				$location_code                 = get_post_meta( $request_post_id, 'location_code', true );
				$fees_data[ $request_post_id ] = array(
					'sm_title'      => $sm_title,
					'status'        => $sm_status,
					'location_type' => $location_type,
					'zone_type'     => $zone_type,
					'location_code' => $location_code,
				);
			}
		}
		$export_action = filter_input( INPUT_POST, 'afrsm_zone_export_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$import_action = filter_input( INPUT_POST, 'afrsm_zone_import_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! empty( $export_action ) || 'zone_export_settings' === $export_action ) {
			$afrsm_export_action_nonce = filter_input( INPUT_POST, 'afrsm_zone_export_action_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if ( ! wp_verify_nonce( $afrsm_export_action_nonce, 'afrsm_zone_export_save_action_nonce' ) ) {
				return;
			}
			ignore_user_abort( true );
			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=afrsm-zone-export-' . gmdate( 'm-d-Y' ) . '.json' );
			header( "Expires: 0" );
			echo wp_json_encode( $fees_data );
			exit;
		}
		if ( ! empty( $import_action ) || 'zone_import_settings' === $import_action ) {
			$afrsm_import_action_nonce = filter_input( INPUT_POST, 'afrsm_zone_import_action_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if ( ! wp_verify_nonce( $afrsm_import_action_nonce, 'afrsm_zone_import_action_nonce' ) ) {
				return;
			}
			$file_import_file_args              = array(
				'zone_import_file' => array(
					'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
					'flags'  => FILTER_FORCE_ARRAY,
				),
			);
			$attached_import_files__arr         = filter_var_array( $_FILES, $file_import_file_args );
			$attached_import_files__arr_explode = explode( '.', $attached_import_files__arr['zone_import_file']['name'] );
			$extension                          = end( $attached_import_files__arr_explode );
			if ( $extension !== 'json' ) {
				wp_die( esc_html__( 'Please upload a valid .json file', 'advanced-flat-rate-shipping-for-woocommerce' ) );
			}
			$import_file = $attached_import_files__arr['zone_import_file']['tmp_name'];
			if ( empty( $import_file ) ) {
				wp_die( esc_html__( 'Please upload a file to import', 'advanced-flat-rate-shipping-for-woocommerce' ) );
			}
			WP_Filesystem();
			global $wp_filesystem;
			$fees_data = $wp_filesystem->get_contents( $import_file );
			if ( ! empty( $fees_data ) ) {
				$fees_data_decode = json_decode( $fees_data, true );
				if ( ! empty( $fees_data_decode ) ) {
					foreach ( $fees_data_decode as $fees_val ) {
						if ( ! empty( $fees_val['sm_title'] ) ) {
							$fee_post    = array(
								'post_title'  => $fees_val['sm_title'],
								'post_status' => $fees_val['status'],
								'post_type'   => self::afrsm_zone_post_type,
							);
                            $fount_post = post_exists( $fees_val['sm_title'], '', '', self::afrsm_zone_post_type );
                            if( $fount_post > 0 && !empty($fount_post) ) {
                                $fee_post['ID'] = $fount_post;
                                $get_post_id = wp_update_post( $fee_post );
                            } else {
                                $get_post_id = wp_insert_post( $fee_post );
                            }
							if ( '' !== $get_post_id && 0 !== $get_post_id ) {
								if ( $get_post_id > 0 ) {
									update_post_meta( $get_post_id, 'location_type', $fees_val['location_type'] );
									update_post_meta( $get_post_id, 'zone_type', $fees_val['zone_type'] );
									update_post_meta( $get_post_id, 'location_code', $fees_val['location_code'] );
								}
							}
						}
					}
				}
			}
			wp_safe_redirect( add_query_arg( array(
				'page'   => 'afrsm-pro-import-export',
				'status' => 'success',
			), admin_url( 'admin.php' ) ) );
			exit;
		}
	}
	/**
	 * Plugins URL
	 *
	 * @since    3.6.1
	 */
	public function afrsm_pro_plugins_url( $id, $page, $tab, $action, $nonce ) {
		$query_args = array();
		if ( '' !== $page ) {
			$query_args['page'] = $page;
		}
		if ( '' !== $tab ) {
			$query_args['tab'] = $tab;
		}
		if ( '' !== $action ) {
			$query_args['action'] = $action;
		}
		if ( '' !== $id ) {
			$query_args['id'] = $id;
		}
		if ( '' !== $nonce ) {
			$query_args['_wpnonce'] = wp_create_nonce( 'afrsmnonce' );
		}
		return esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
	}
	/**
	 * Create a menu for plugin.
	 *
	 * @param string $current current page.
	 *
	 * @since    3.6.1
	 */
	public function afrsm_pro_menus( $current = 'afrsm-pro-list' ) {
		$wpfp_menus = array(
			'main_menu' => array(
				'pro_menu'  => array(
					'afrsm-pro-list'          => array(
						'menu_title' => __( 'Manage Rules', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'menu_slug'  => 'afrsm-pro-list',
						'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-list', '', '', '' ),
					),
                    'afrsm-page-general-settings' => array(
                        'menu_title' => __( 'Settings', 'advanced-flat-rate-shipping-for-woocommerce' ),
                        'menu_slug'  => 'afrsm-page-general-settings',
                        'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-page-general-settings', '', '', '' ),
                    ),
                    'afrsm-pro-list-account' => array(
						'menu_title' => __( 'License', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'menu_slug'  => 'afrsm-pro-list-account',
						'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-list-account', '', '', '' ),
					),
                    'afrsm-page-add-ons' => array(
						'menu_title' => __( 'Add-Ons', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'menu_slug'  => 'afrsm-page-add-ons',
						'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-page-add-ons', '', '', '' ),
					),
				),
				'free_menu' => array(
                    'afrsm-pro-dashboard'   => array(
						'menu_title' => __( 'Dashboard', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'menu_slug'  => 'afrsm-pro-dashboard',
						'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-dashboard', '', '', '' ),
					),
					'afrsm-pro-list'        => array(
						'menu_title' => __( 'Manage Rules', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'menu_slug'  => 'afrsm-pro-list',
						'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-list', '', '', '' ),
					),
                    'afrsm-page-general-settings' => array(
                        'menu_title' => __( 'Settings', 'advanced-flat-rate-shipping-for-woocommerce' ),
                        'menu_slug'  => 'afrsm-page-general-settings',
                        'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-page-general-settings', '', '', '' ),
                    ),
                    'afrsm-page-add-ons' => array(
						'menu_title' => __( 'Add-Ons', 'advanced-flat-rate-shipping-for-woocommerce' ),
						'menu_slug'  => 'afrsm-page-add-ons',
						'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-page-add-ons', '', '', '' ),
					),
				),
			),
		);
        //Submenu activation code
        $submenu_keys = array(
            'afrsm-page-general-settings',
            'afrsm-wc-shipping-zones',
            'afrsm-pro-import-export',
            'afrsm-pro-get-started',
            'afrsm-pro-information',
        );
        if ( afrsfw_fs()->is__premium_only() ){
            if( afrsfw_fs()->can_use_premium_code() ) {
            } else {
                array_push($submenu_keys, 'afrsm-pro-list-account' );
            }
        } else {
            array_push($submenu_keys, 'afrsm-pro-list-account' );
        }
        if( in_array( $current, $submenu_keys, true ) ){
            $current = 'afrsm-page-general-settings';
        }
		?>
		<div class="dots-menu-main">
			<nav>
				<ul>
					<?php
					$main_current = $current;
					$sub_current  = $current;
					foreach ( $wpfp_menus['main_menu'] as $main_menu_slug => $main_wpfp_menu ) {
						if ( afrsfw_fs()->is__premium_only() ) {
							if ( afrsfw_fs()->can_use_premium_code() ) {
								if ( 'pro_menu' === $main_menu_slug || 'common_menu' === $main_menu_slug ) {
									foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
										if ( 'afrsm-pro-information' === $main_current ) {
											$main_current = 'afrsm-pro-get-started';
										}
										$class = ( $menu_slug === $main_current ) ? 'active' : '';
										?>
										<li>
											<a class="dotstore_plugin <?php echo esc_attr( $class ); ?>"
											   href="<?php echo esc_url( $wpfp_menu['menu_url'] ); ?>">
												<?php esc_html_e( $wpfp_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
											</a>
											<?php if ( isset( $wpfp_menu['sub_menu'] ) && ! empty( $wpfp_menu['sub_menu'] ) ) { ?>
												<ul class="sub-menu">
													<?php foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
														$sub_class = ( $sub_menu_slug === $sub_current ) ? 'active' : '';
														?>

														<li>
															<a class="dotstore_plugin <?php echo esc_attr( $sub_class ); ?>"
															   href="<?php echo esc_url( $wpfp_sub_menu['menu_url'] ); ?>">
																<?php esc_html_e( $wpfp_sub_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
															</a>
														</li>
													<?php } ?>
												</ul>
											<?php } ?>
										</li>
										<?php
									}
								}
							} else {
								if ( 'free_menu' === $main_menu_slug || 'common_menu' === $main_menu_slug ) {
									foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
										if ( 'afrsm-pro-information' === $main_current ) {
											$main_current = 'afrsm-pro-get-started';
										}
										$class = ( $menu_slug === $main_current ) ? 'active' : '';
										?>
										<li>
											<a class="dotstore_plugin <?php echo esc_attr( $class ); ?>"
											   href="<?php echo esc_url( $wpfp_menu['menu_url'] ); ?>">
												<?php esc_html_e( $wpfp_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
											</a>
											<?php if ( isset( $wpfp_menu['sub_menu'] ) && ! empty( $wpfp_menu['sub_menu'] ) ) { ?>
												<ul class="sub-menu">
													<?php foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
														$sub_class = ( $sub_menu_slug === $sub_current ) ? 'active' : '';
														?>

														<li>
															<a class="dotstore_plugin <?php echo esc_attr( $sub_class ); ?>"
															   href="<?php echo esc_url( $wpfp_sub_menu['menu_url'] ); ?>">
																<?php esc_html_e( $wpfp_sub_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
															</a>
														</li>
													<?php } ?>
												</ul>
											<?php } ?>
										</li>
										<?php
									}
								}
							}
						} else {
							if ( 'free_menu' === $main_menu_slug || 'common_menu' === $main_menu_slug ) {
								foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
									if ( 'afrsm-pro-information' === $main_current ) {
										$main_current = 'afrsm-pro-get-started';
									}
									$class = ( $menu_slug === $main_current ) ? 'active' : '';
									?>
									<li>
										<a class="dotstore_plugin <?php echo esc_attr( $class ); ?>"
										   href="<?php echo esc_url( $wpfp_menu['menu_url'] ); ?>">
											<?php esc_html_e( $wpfp_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
										</a>
										<?php if ( isset( $wpfp_menu['sub_menu'] ) && ! empty( $wpfp_menu['sub_menu'] ) ) { ?>
											<ul class="sub-menu">
												<?php foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
													$sub_class = ( $sub_menu_slug === $sub_current ) ? 'active' : '';
													?>

													<li>
														<a class="dotstore_plugin <?php echo esc_attr( $sub_class ); ?>"
														   href="<?php echo esc_url( $wpfp_sub_menu['menu_url'] ); ?>">
															<?php esc_html_e( $wpfp_sub_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
														</a>
													</li>
												<?php } ?>
											</ul>
										<?php } ?>
									</li>
									<?php
								}
							}
						}
					}
					?>
				</ul>
			</nav>
		</div>
		<?php
	}

    /**
	 * Create a menu for plugin.
	 *
	 * @param string $current current page.
	 *
	 * @since    3.6.1
	 */
	public function afrsm_submenus( $current = 'afrsm-page-general-settings' ) {
        $afrsm_sub_menus = array(
            'afrsm-page-general-settings' => array(
                'menu_title' => __( 'General Settings', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'menu_slug'  => 'afrsm-page-general-settings',
                'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-page-general-settings', '', '', '' ),
            ),
            'afrsm-wc-shipping-zones' => array(
                'menu_title' => __( 'Manage Zones', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'menu_slug'  => 'afrsm-wc-shipping-zones',
                'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-wc-shipping-zones', '', '', '' ),
                'sub_menu'   => array(
                    'afrsm-wc-shipping-zones' => array(
                        'menu_title' => __( 'Add Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
                        'menu_slug'  => 'afrsm-wc-shipping-zones',
                        'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-wc-shipping-zones&add_zone', '', '', '' ),
                    ),
                ),
            ),
            'afrsm-pro-import-export' => array(
                'menu_title' => __( 'Import / Export', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'menu_slug'  => 'afrsm-pro-import-export',
                'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-import-export', '', '', '' ),
            ),
            'afrsm-pro-get-started' => array(
                'menu_title' => __( 'About', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'menu_slug'  => 'afrsm-pro-get-started',
                'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-get-started', '', '', '' ),
            ),
            'afrsm-pro-information' => array(
                'menu_title' => __( 'Quick info', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'menu_slug'  => 'afrsm-pro-information',
                'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-information', '', '', '' ),
            ),
		);
        if ( afrsfw_fs()->is__premium_only() ){
            if( afrsfw_fs()->can_use_premium_code() ) {
            } else {
                $afrsm_sub_menus['afrsm-pro-list-account'] = array(
                    'menu_title' => __( 'Account', 'advanced-flat-rate-shipping-for-woocommerce' ),
                    'menu_slug'  => 'afrsm-pro-list-account',
                    'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-list-account', '', '', '' ),
                );
            } 
        }else {
            $afrsm_sub_menus['afrsm-pro-list-account'] = array(
                'menu_title' => __( 'Account', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'menu_slug'  => 'afrsm-pro-list-account',
                'menu_url'   => $this->afrsm_pro_plugins_url( '', 'afrsm-pro-list-account', '', '', '' ),
            );
        }
        $afrsm_display_submenu = in_array( $current, array_keys($afrsm_sub_menus), true ) ? 'display:inline-block' : 'display:none';
        ?>
        <div class="dotstore-submenu-items" style="<?php echo esc_attr($afrsm_display_submenu); ?>">
            <ul>
            <?php
                foreach( $afrsm_sub_menus as $sub_menu_slug => $sub_menu ){
                $class = ( $sub_menu_slug === $current ) ? 'active' : '';
                ?>
                <li>
                    <a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url($sub_menu['menu_url']); ?>">
                        <?php echo esc_html($sub_menu['menu_title']); ?>
                    </a>
                </li>
                <?php
                }
            ?>
            <li><a href="<?php echo esc_url('https://www.thedotstore.com/plugins/'); ?>" target="_blank"><?php esc_html_e('Shop Plugins', 'advanced-flat-rate-shipping-for-woocommerce'); ?></a></li>
            </ul>
        </div>
        <?php
    }

	/**
	 * Filter for price - currency switcher
	 *
	 * @param float $price Get price which we will convert here.
	 *
	 * @return float $price Return converted price.
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_pro_price_based_on_switcher( $price ) {
		if ( has_filter( 'afrsm_woomc_price' ) ) {
			$price = apply_filters( 'afrsm_woomc_price', $price );
		}
		return $price;
	}

	/**
	 * Filter for price - currency switcher
	 *
	 * @param float $price Get price which we will convert here.
	 *
	 * @return float $price Return converted price.
	 *
	 * @since  3.8
	 *
	 * @author jb
	 */
	public function afrsm_woomc_price_data( $price ) {
		if ( function_exists( 'wmc_get_price' ) ) {
	  		$price = wmc_get_price( $price );
	  	}
	  return $price;
	}

	/**
	 * Display the notice with the amount of price left to free shipping
	 *
	 */
	public function afrsm_free_shipping_cart_notice__premium_only() {
		global $sitepress;
		require_once plugin_dir_path( __DIR__ ) . 'admin/partials/afrsm-init-shipping-methods.php';
       
		// get shipping information in array
		$packages = WC()->shipping->get_packages();

        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $afrsm_object = new AFRSM_Shipping_Method();

        // We have commented this code as remove cart page error of pacakge which is not used in this function. After 2 successful release it will be remove from here.
		if ( ! empty( $packages ) && is_array( $packages ) ) {
			foreach ( $packages as $package ) {
                $matched_shipping_methods = $afrsm_object->afrsm_shipping_match_methods( $package, $sitepress, $default_lang );

                /**
                 * match shipping methods
                 */
                if ( ! empty( $matched_shipping_methods ) && is_array( $matched_shipping_methods ) ) {
                    // ordering issue and highest, smallest, forceall shipping issue code
                    foreach ( $matched_shipping_methods as $main_shipping_method_id_val ) {
                        if ( ! empty( $main_shipping_method_id_val ) || $main_shipping_method_id_val !== 0 ) {
                            if ( ! empty( $sitepress ) ) {
                                $shipping_method_id_val = apply_filters( 'wpml_object_id', $main_shipping_method_id_val, 'wc_afrsm', true, $default_lang );
                            } else {
                                $shipping_method_id_val = $main_shipping_method_id_val;
                            }
                            $is_allow_free_shipping = get_post_meta( $shipping_method_id_val, 'is_allow_free_shipping', true );
                            $free_shipping_cost_left_notice = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_cost_left_notice', true );
                            if( 'on' === $free_shipping_cost_left_notice && 'on' === $is_allow_free_shipping ){
                                $shipping_name = !empty( get_the_title($main_shipping_method_id_val) ) ? get_the_title($main_shipping_method_id_val) : __( 'shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
                                $free_shipping_based_on = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_based_on', true );
                                $free_shipping_costs = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_cost', true );
                                $is_free_shipping_before_discount = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_cost_before_discount', true );
                                $total_cart_value = floatval(WC()->cart->subtotal);
                                $afrsm_admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
                                $total_discount_value    = $afrsm_admin_object->afrsm_pro_remove_currency_symbol( WC()->cart->get_total_discount() );
                                if("min_order_amt" === $free_shipping_based_on){
                                    if( "on" === $is_free_shipping_before_discount ){
                                        $final_total_cart_value = $total_cart_value;
                                    }else{
                                        $final_total_cart_value = ( $total_cart_value - $total_discount_value );
                                    }
                                    if ( $final_total_cart_value < $free_shipping_costs ) {
                                        $free_shipping_cost_left_notice_msg = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_cost_left_notice_msg', true );
                                        if ( isset( $free_shipping_cost_left_notice_msg ) && ! empty( $free_shipping_cost_left_notice_msg ) ) {
                                            $sm_dynamic_price = wc_price( $free_shipping_costs - $final_total_cart_value );
                                            $free_shipping_cost_left_notice_msg = str_replace( "{LEFT_PRICE_VALUE}", $sm_dynamic_price, $free_shipping_cost_left_notice_msg );
                                            $added_text	= $free_shipping_cost_left_notice_msg;
                                        } else {
                                            $added_text = sprintf( 'Get free <strong>%1$s</strong> if you order %2$s more!', $shipping_name, wc_price( $free_shipping_costs - $final_total_cart_value ) );
                                        }
                                        $return_to = wc_get_page_permalink( 'shop' );
                                        $notice = sprintf( '<a href="%s" class="button wc-forward">%s</a><div class="wc-info-notice"> %s</div>', esc_url( $return_to ), 'Continue Shopping', wp_kses_post( $added_text ) );
                                        wc_print_notice( $notice, 'notice' );
                                    }
                                }
                            }	
                        }
                    }
                }
			}
		}
        
	}
	/**
	 * Outputs a small piece of javascript for the beacon.
	 */
	public function afrsm_output_beacon_js(){
		printf(
			'<script type="text/javascript">window.%1$s(\'%2$s\', \'%3$s\')</script>',
			'Beacon',
			'init',
			esc_html('afe1c188-3c3b-4c5f-9dbd-87329301c920')
		);
	}
	/**
	 * Price change by currancy exchange plugin compatible WOOCS Plugin
	 *
	 * @param string $price
	 *
	 * @return string $converted_price
	 * @since  3.9.7
	 *
	 */
	public function afrsm_woocs_convert_price( $price ){
		if ( is_plugin_active( 'woocommerce-currency-switcher/index.php' ) ) {
			$currencies = get_option('woocs', array());
			$cc = get_woocommerce_currency();
			$converted_price = floatval((float) $price * (float) $currencies[$cc]['rate']);
		} else {
			$converted_price = $price;
		}
		
		return $converted_price;
	}

	/**
	 * Action perform after shipment added to DB from Germanized plugin to append our manual data
	 *
	 * @param string $data, $data_store
	 *
	 * @since  4.0
	 *
	 */
	public function afrsm_shipment_object( $data, $data_store ){
		
		global $wpdb;
		
		$order_id = $data->get_order_id();
		$order = wc_get_order($order_id);

		// Iterating through order shipping items
		foreach( $order->get_items( 'shipping' ) as $item ){
			$order_item_name             = $item->get_name();
			$shipping_post = get_page_by_title( $order_item_name, OBJECT, 'wc_afrsm'); // phpcs:ignore
			$shipping_id = ( !empty($shipping_post) && isset($shipping_post->ID) && $shipping_post->ID > 0 ) ? $shipping_post->ID : 0;
			$shipment_provider_name = get_post_meta( $shipping_id, 'sm_select_shipping_provider', true ) ? get_post_meta( $shipping_id, 'sm_select_shipping_provider', true ) : '';

			if( !empty($shipment_provider_name) ){
                // phpcs:disable
				$wpdb->get_var( 
					$wpdb->prepare(
						"UPDATE {$wpdb->gzd_shipments} 
						SET `shipment_shipping_provider` = '%s'
						WHERE shipment_order_id = %d",
						$shipment_provider_name,
						intval($order_id)
					) 
				);
                // phpcs:enable
			}
		}
	}

	public function afrsm_updated_message( $message, $validation_msg ){
		if ( empty( $message ) ) {
			return false;
		}

		if ( 'created' === $message ) {
			$updated_message = esc_html__( "Shipping rule has been created.", 'advanced-flat-rate-shipping-for-woocommerce' );
		} elseif ( 'saved' === $message ) {
			$updated_message = esc_html__( "Shipping rule has been updated.", 'advanced-flat-rate-shipping-for-woocommerce' );
		} elseif ( 'deleted' === $message ) {
			$updated_message = esc_html__( "Shipping rule has been deleted.", 'advanced-flat-rate-shipping-for-woocommerce' );
		} elseif ( 'duplicated' === $message ) {
			$updated_message = esc_html__( "Shipping rule has been duplicated.", 'advanced-flat-rate-shipping-for-woocommerce' );
		} elseif ( 'disabled' === $message ) {
			$updated_message = esc_html__( "Shipping rule has been disabled.", 'advanced-flat-rate-shipping-for-woocommerce' );
		} elseif ( 'enabled' === $message ) {
			$updated_message = esc_html__( "Shipping rule has been enabled.", 'advanced-flat-rate-shipping-for-woocommerce' );
		}
		if ( 'failed' === $message ) {
			$failed_messsage = esc_html__( "There was an error with saving data.", 'advanced-flat-rate-shipping-for-woocommerce' );
		} elseif ( 'nonce_check' === $message ) {
			$failed_messsage = esc_html__( "There was an error with security check.", 'advanced-flat-rate-shipping-for-woocommerce' );
		}
		if ( 'validated' === $message ) {
			$validated_messsage = esc_html( $validation_msg );
		}
		
		if ( ! empty( $updated_message ) ) {
			echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
			return false;
		}
		if ( ! empty( $failed_messsage ) ) {
			echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) );
			return false;
		}
		if ( ! empty( $validated_messsage ) ) {
			echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) );
			return false;
		}
	}

    /**
	 * Check user's have first order or not
	 *
	 * @return boolean $order_check
	 * @since 4.2.0
	 *
	 */
	public function afrsm_check_first_order_for_user( $user_id ) {

		$user_id = !empty($user_id) ? $user_id : get_current_user_id();

        $args = array( 
            'customer' => $user_id,
            'status' => array( 'wc-completed', 'wc-processing' ),
            'limit' => 1,
            'return' => 'ids'
        );

        $customer_orders = wc_get_orders( $args );

		// return "true" when customer has already at least one order (false if not)
	   	return count($customer_orders) > 0 ? false : true; 
	}

    /**
	 * Check order condition for user
	 *
	 * @return boolean $order_check
	 * @since 4.2.0
	 *
	 */
	public function afrsm_check_order_for_user__premium_only( $user_id, $count = false ) {

		$user_id = !empty($user_id) ? $user_id : get_current_user_id();

		$numberposts = (!$count) ? 1 : -1;

        $args = array( 
            'customer' => $user_id,
            'status' => array( 'wc-completed', 'wc-processing' ),
            'limit' => $numberposts,
            'return' => 'ids'
        );
        $customer_orders = wc_get_orders( $args );

		// return "true" when customer has already at least one order (false if not)
		$total = 0;
		if(!$count){
			foreach ( $customer_orders as $customer_order ) {
				$order = wc_get_order( $customer_order );
				$total += $order->get_total();
			}
			return $total; 
		} else {
			return count($customer_orders);
		}
	}

    /**
     * Get and save plugin setup wizard data
     * 
     * @since    4.2.0
     * 
     */
    public function afrsm_plugin_setup_wizard_submit() {
    	check_ajax_referer( 'afrsfw_wizard_nonce', 'nonce' );

    	$survey_list = filter_input( INPUT_GET, 'survey_list', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

    	if ( !empty($survey_list) && 'Select One' !== $survey_list ) {
    		update_option('afrsm_where_hear_about_us', $survey_list);
    	}
		wp_die();
    }

    /**
     * Send setup wizard data to sendinblue
     * 
     * @since    4.2.0
     * 
     */
    public function afrsm_send_wizard_data_after_plugin_activation() {
    	$send_wizard_data = filter_input(INPUT_GET, 'send-wizard-data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if ( isset( $send_wizard_data ) && !empty( $send_wizard_data ) ) {
			if ( !get_option('afrsm_data_submited_in_sendiblue') ) {
				$afrsm_where_hear = get_option('afrsm_where_hear_about_us');
				$get_user = afrsfw_fs()->get_user();
				$data_insert_array = array();
				if ( isset( $get_user ) && !empty( $get_user ) ) {
					$data_insert_array = array(
						'user_email'              => $get_user->email,
						'ACQUISITION_SURVEY_LIST' => $afrsm_where_hear,
					);	
				}
                
				$feedback_api_url = AFRSM_STORE_URL . '/wp-json/dotstore-sendinblue-data/v2/dotstore-sendinblue-data?' . wp_rand();
				$query_url        = $feedback_api_url . '&' . http_build_query( $data_insert_array );
				if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
					$response = vip_safe_wp_remote_get( $query_url, 3, 1, 20 );
				} else {
					$response = wp_remote_get( $query_url ); //phpcs:ignore
				}

				if ( ( !is_wp_error($response)) && (200 === wp_remote_retrieve_response_code( $response ) ) ) {
					update_option('afrsm_data_submited_in_sendiblue', '1');
					delete_option('afrsm_where_hear_about_us');
				}
			}
		}
    }

    /**
     * Get dynamic promotional bar of plugin
     *
     * @param   String  $plugin_slug  slug of the plugin added in the site option
     * @since    4.2.0
     * 
     * @return  null
     */
    public function afrsm_get_promotional_bar( $plugin_slug = '' ) {
        $promotional_bar_upi_url = AFRSM_PROMOTIONAL_BANNER_API_URL . 'wp-json/dpb-promotional-banner/v2/dpb-promotional-banner?' . wp_rand();
        $promotional_banner_request    = wp_remote_get( $promotional_bar_upi_url );  //phpcs:ignore
        if ( empty( $promotional_banner_request->errors ) ) {
            $promotional_banner_request_body = $promotional_banner_request['body'];	
            $promotional_banner_request_body = json_decode( $promotional_banner_request_body, true );
            echo '<div class="dynamicbar_wrapper">';
            
            if ( ! empty( $promotional_banner_request_body ) && is_array( $promotional_banner_request_body ) ) {
                foreach ( $promotional_banner_request_body as $promotional_banner_request_body_data ) {
					$promotional_banner_id        	  	= $promotional_banner_request_body_data['promotional_banner_id'];
                    $promotional_banner_cookie          = $promotional_banner_request_body_data['promotional_banner_cookie'];
                    $promotional_banner_image           = $promotional_banner_request_body_data['promotional_banner_image'];
                    $promotional_banner_description     = $promotional_banner_request_body_data['promotional_banner_description'];
                    $promotional_banner_button_group    = $promotional_banner_request_body_data['promotional_banner_button_group'];
                    $dpb_schedule_campaign_type         = $promotional_banner_request_body_data['dpb_schedule_campaign_type'];
                    $promotional_banner_target_audience = $promotional_banner_request_body_data['promotional_banner_target_audience'];

                    if ( ! empty( $promotional_banner_target_audience ) ) {
                        $plugin_keys = array();
                        if(is_array ($promotional_banner_target_audience)) {
                            foreach($promotional_banner_target_audience as $list) {
                                $plugin_keys[] = $list['value'];
                            }
                        } else {
                            $plugin_keys[] = $promotional_banner_target_audience['value'];
                        }

                        $display_banner_flag = false;
                        if ( in_array ( 'all_customers', $plugin_keys, true ) || in_array ( $plugin_slug, $plugin_keys, true ) ) {
                            $display_banner_flag = true;
                        }
                    }

                    if ( true === $display_banner_flag ) {
                        if ( 'default' === $dpb_schedule_campaign_type ) {
                            $banner_cookie_show         = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag                       = false;
                            if ( empty( $banner_cookie_show ) && empty( $banner_cookie_visible_once ) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes', time() + ( 86400 * 7 ) ); //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' ); //phpcs:ignore
                                $flag = true;
                            }

                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            if ( ! empty( $banner_cookie_show ) || true === $flag ) {
                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = isset( $banner_cookie ) ? $banner_cookie : '';
                                if ( empty( $banner_cookie ) && 'yes' !== $banner_cookie ) { ?>
                            	<div class="dpb-popup <?php echo isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner'; ?>">
                                    <?php
                                    if ( ! empty( $promotional_banner_image ) ) {
                                        ?>
                                        <img src="<?php echo esc_url( $promotional_banner_image ); ?>"/>
                                        <?php
                                    }
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php
                                            echo wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) );
                                            if ( ! empty( $promotional_banner_button_group ) ) {
                                                foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                                    ?>
                                                    <a href="<?php echo esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ); ?>" target="_blank"><?php echo esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ); ?></a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                    	</p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php echo esc_attr( $promotional_banner_id ); ?>" data-popup-name="<?php echo isset( $promotional_banner_cookie ) ? esc_attr( $promotional_banner_cookie ) : 'default-banner'; ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php
                                }
                            }
                        } else {
                            $banner_cookie_show         = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $banner_cookie_visible_once = filter_input( INPUT_COOKIE, 'banner_show_once_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            $flag                       = false;
                            if ( empty( $banner_cookie_show ) && empty( $banner_cookie_visible_once ) ) {
                                setcookie( 'banner_show_' . $promotional_banner_cookie, 'yes'); //phpcs:ignore
                                setcookie( 'banner_show_once_' . $promotional_banner_cookie, 'yes' ); //phpcs:ignore
                                $flag = true;
                            }

                            $banner_cookie_show = filter_input( INPUT_COOKIE, 'banner_show_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                            if ( ! empty( $banner_cookie_show ) || true === $flag ) {

                                $banner_cookie = filter_input( INPUT_COOKIE, 'banner_' . $promotional_banner_cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                                $banner_cookie = isset( $banner_cookie ) ? $banner_cookie : '';
                                if ( empty( $banner_cookie ) && 'yes' !== $banner_cookie ) { ?>
                    			<div class="dpb-popup <?php echo isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner'; ?>">
                                    <?php
                                    if ( ! empty( $promotional_banner_image ) ) {
                                        ?>
                                            <img src="<?php echo esc_url( $promotional_banner_image ); ?>"/>
                                        <?php
                                    }
                                    ?>
                                    <div class="dpb-popup-meta">
                                        <p>
                                            <?php
                                            echo wp_kses_post( str_replace( array( '<p>', '</p>' ), '', $promotional_banner_description ) );
                                            if ( ! empty( $promotional_banner_button_group ) ) {
                                                foreach ( $promotional_banner_button_group as $promotional_banner_button_group_data ) {
                                                    ?>
                                                    <a href="<?php echo esc_url( $promotional_banner_button_group_data['promotional_banner_button_link'] ); ?>" target="_blank"><?php echo esc_html( $promotional_banner_button_group_data['promotional_banner_button_text'] ); ?></a>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <a href="javascript:void(0);" data-bar-id="<?php echo esc_attr( $promotional_banner_id ); ?>" data-popup-name="<?php echo isset( $promotional_banner_cookie ) ? esc_html( $promotional_banner_cookie ) : 'default-banner'; ?>" class="dpbpop-close"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><path id="Icon_material-close" data-name="Icon material-close" d="M17.5,8.507,16.493,7.5,12.5,11.493,8.507,7.5,7.5,8.507,11.493,12.5,7.5,16.493,8.507,17.5,12.5,13.507,16.493,17.5,17.5,16.493,13.507,12.5Z" transform="translate(-7.5 -7.5)" fill="#acacac"/></svg></a>
                                </div>
                                <?php
                                }
                            }
                        }
                    }
                }
            }
            echo '</div>';
        }
    }
}