<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'advanced-flat-rate-shipping-for-woocommerce';
		$this->version     = AFRSM_PRO_PLUGIN_VERSION;
		$this->load_dependencies();
		$this->set_locale();
		$this->init();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter( "{$prefix}plugin_action_links_" . AFRSM_PRO_PLUGIN_BASENAME, array(
			$this,
			'plugin_action_links',
		), 10, 4 );
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_i18n. Defines internationalization functionality.
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin. Defines all hooks for the admin area.
	 * - Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-flat-rate-shipping-for-woocommerce-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advanced-flat-rate-shipping-for-woocommerce-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advanced-flat-rate-shipping-for-woocommerce-public.php';
		
		$this->loader = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 3.0.0
	 */
	public function init() {
		// Initialize shipping method class
		add_action( 'woocommerce_shipping_init', array( $this, 'afrsm_pro_init_shipping_method' ) );
		// Register shipping method
		add_action( 'woocommerce_shipping_methods', array( $this, 'afrsm_pro_register_shipping_method_class' ) );
	}
	/**
	 * Initialize shipping method.
	 *
	 * Configure and add all the shipping methods available.
	 *
	 * @since 3.0.0
	 *
	 * @uses  AFRSM_Shipping_Method class
	 * @uses  AFRSM_Forceall_Shipping_Method
	 */
	public function afrsm_pro_init_shipping_method() {
		require_once plugin_dir_path( __DIR__ ) . '/admin/partials/afrsm-init-shipping-methods.php';
		$this->afrsm_method = new AFRSM_Shipping_Method();
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				require_once plugin_dir_path( __DIR__ ) . '/admin/partials/afrsm-forceall-shipping-method.php';
				$this->afrsm_forceall_method = new AFRSM_Forceall_Shipping_Method();
			}
		}
	}
	/**
	 * Add shipping method.
	 *
	 * Add configured methods to available shipping methods.
	 *
	 * @param array $methods
	 *
	 * @return array $methods
	 * @since 3.0.0
	 *
	 */
	public function afrsm_pro_register_shipping_method_class( $methods ) {
		if ( class_exists( 'AFRSM_Shipping_Method' ) ) {
			$methods[] = 'AFRSM_Shipping_Method';
		}
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				if ( class_exists( 'AFRSM_Forceall_Shipping_Method' ) ) {
					$methods[] = 'AFRSM_Forceall_Shipping_Method';
				}
			}
		}
		$afrsm_object = new AFRSM_Shipping_Method();
		$GLOBALS['afrsmsm'] = $afrsm_object;
		return $methods;
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
        $get_page       = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$page           = !empty($get_page) ? sanitize_text_field($get_page) : '';
		$plugin_admin   = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'afrsm_location_specific_list', $plugin_admin, 'afrsm_location_specific_action' );
		$this->loader->add_action( 'afrsm_product_specific_list', $plugin_admin, 'afrsm_product_specific_action' );
		$this->loader->add_action( 'afrsm_attribute_specific_list', $plugin_admin, 'afrsm_attribute_specific_action' );
		$this->loader->add_action( 'afrsm_user_specific_list', $plugin_admin, 'afrsm_user_specific_action' );
		$this->loader->add_action( 'afrsm_cart_specific_list', $plugin_admin, 'afrsm_cart_specific_action' );
		$this->loader->add_action( 'afrsm_checkout_specific_list', $plugin_admin, 'afrsm_checkout_specific_action' );
		$this->loader->add_action( 'afrsm_conditions_list', $plugin_admin, 'afrsm_conditions_list_action' );
		$this->loader->add_action( 'afrsm_operator_list_prd', $plugin_admin, 'afrsm_operator_list_prd_action' );
		$this->loader->add_action( 'afrsm_operator_crt_list', $plugin_admin, 'afrsm_operator_list_crt_action' );
		$this->loader->add_action( 'afrsm_operator_list', $plugin_admin, 'afrsm_operator_list_action' );
		$this->loader->add_action( 'afrsm_advanced_tab_list', $plugin_admin, 'afrsm_advanced_tab_list_action' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'afrsm_pro_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'afrsm_pro_enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'afrsm_pro_redirect_shipping_function' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'afrsm_pro_dot_store_menu_shipping_method_pro' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'afrsm_pro_welcome_shipping_method_screen_do_activation_redirect' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'afrsm_pro_remove_admin_submenus' );
        $this->loader->add_filter( 'set-screen-option', $plugin_admin, 'afrsm_set_screen_options', 10, 3 );
        $this->loader->add_filter( 'default_hidden_columns', $plugin_admin, 'afrsm_default_hidden_columns', 10, 2 );
		$this->loader->add_filter( 'afrsm_condition_match_rules', $plugin_admin, 'afrsm_pro_condition_match_rules', 10, 2 );
		$this->loader->add_action( 'wp_ajax_afrsm_pro_sm_sort_order', $plugin_admin, 'afrsm_pro_sm_sort_order' );
		$this->loader->add_action( 'wp_ajax_nopriv_afrsm_pro_sm_sort_order', $plugin_admin, 'afrsm_pro_sm_sort_order' );
		$this->loader->add_action( 'wp_ajax_afrsm_pro_save_master_settings', $plugin_admin, 'afrsm_pro_save_master_settings' );
		$this->loader->add_action( 'wp_ajax_afrsm_pro_product_fees_conditions_values_ajax', $plugin_admin, 'afrsm_pro_product_fees_conditions_values_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_afrsm_pro_product_fees_conditions_values_ajax', $plugin_admin, 'afrsm_pro_product_fees_conditions_values_ajax' );
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$this->loader->add_action( 'wp_ajax_afrsm_pro_product_fees_conditions_varible_values_product_ajax__premium_only', $plugin_admin, 'afrsm_pro_product_fees_conditions_varible_values_product_ajax__premium_only' );
                $this->loader->add_action( 'wp_ajax_afrsm_products_list_ajax', $plugin_admin, 'afrsm_products_list_ajax__premium_only' );
			}
		}
		$this->loader->add_action( 'wp_ajax_afrsm_pro_product_fees_conditions_values_product_ajax', $plugin_admin, 'afrsm_pro_product_fees_conditions_values_product_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_afrsm_pro_product_fees_conditions_values_product_ajax', $plugin_admin, 'afrsm_pro_product_fees_conditions_values_product_ajax' );
		$this->loader->add_action( 'wp_ajax_afrsm_pro_wc_multiple_delete_shipping_method', $plugin_admin, 'afrsm_pro_wc_multiple_delete_shipping_method' );
		$this->loader->add_action( 'wp_ajax_nopriv_afrsm_pro_wc_multiple_delete_shipping_method', $plugin_admin, 'afrsm_pro_wc_multiple_delete_shipping_method' );
		if ( ! empty( $page ) && ( false !== strpos( $page, 'afrsm' ) ) ) {
			$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'afrsm_pro_admin_footer_review' );
			$this->loader->add_action( 'admin_footer', $plugin_admin, 'afrsm_output_beacon_js');
		}
		$this->loader->add_action( 'wp_ajax_afrsm_pro_clone_shipping_method', $plugin_admin, 'afrsm_pro_clone_shipping_method' );
		$this->loader->add_action( 'wp_ajax_afrsm_pro_change_status_from_list_section', $plugin_admin, 'afrsm_pro_change_status_from_list_section' );
		$this->loader->add_filter( 'woocommerce_get_sections_shipping', $plugin_admin, 'afrsm_pro_remove_section' );
		if ( ! empty( $page ) && ( false !== strpos( $page, 'afrsm-pro-import-export' ) ) ) {
			$this->loader->add_action( 'admin_init', $plugin_admin, 'afrsm_pro_import_export_shipping_method' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'afrsm_pro_import_export_zone' );
		}
		$this->loader->add_action( 'wp_ajax_afrsm_pro_fetch_shipping_zone', $plugin_admin, 'afrsm_pro_fetch_shipping_zone' );
		$this->loader->add_action( 'wp_ajax_afrsm_pro_change_status_of_advance_pricing_rules', $plugin_admin, 'afrsm_pro_change_status_of_advance_pricing_rules' );
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$this->loader->add_action( 'wp_ajax_afrsm_pro_simple_and_variation_product_list_ajax__premium_only', $plugin_admin, 'afrsm_pro_simple_and_variation_product_list_ajax__premium_only' );
				$this->loader->add_action( 'woocommerce_before_cart', $plugin_admin, 'afrsm_free_shipping_cart_notice__premium_only' );
			}
		}
		$this->loader->add_filter( 'afrsm_woomc_price', $plugin_admin, 'afrsm_woomc_price_data', 10, 1 );
		$this->loader->add_action( 'woocommerce_after_shipment_object_save', $plugin_admin, 'afrsm_shipment_object', 10, 2 );
        $this->loader->add_action( 'wp_ajax_afrsm_plugin_setup_wizard_submit', $plugin_admin, 'afrsm_plugin_setup_wizard_submit' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'afrsm_send_wizard_data_after_plugin_activation' );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Public( $this->get_plugin_name(), $this->get_version() );
		$afrsm_force_customer_to_select_sm = get_option( 'afrsm_force_customer_to_select_sm' );
				
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'afrsm_pro_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'afrsm_pro_enqueue_scripts' );
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'afrsm_pro_wc_locate_template_sm_conditions', 1, 3 );
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$this->loader->add_action( 'woocommerce_checkout_update_order_review', $plugin_public, 'afrsm_pro_woocommerce_checkout_update_order_review__premium_only' );
				/*New Code*/
				$this->loader->add_filter( 'woocommerce_package_rates', $plugin_public, 'afrsm_pro_remove_shipping_method__premium_only', 10, 1 );
			}
		}
		if (isset($afrsm_force_customer_to_select_sm) && 'on' === $afrsm_force_customer_to_select_sm) {
			add_filter( 'woocommerce_shipping_chosen_method', '__return_false', 99);
		} else {
			$this->loader->add_filter( 'woocommerce_shipping_chosen_method', $plugin_public, 'afrsm_set_default_shipping_method', 10, 2 );
		}
		$this->loader->add_filter( 'woocommerce_cart_shipping_method_full_label', $plugin_public, 'afrsm_pro_wc_cart_shipping_method_label_callback', 10, 2 );
		$this->loader->add_action( 'woocommerce_after_shipping_rate', $plugin_public, 'afrsm_add_tooltip_and_subtitle_callback' );
	}
	/**
	 * Return the plugin action links.  This will only be called if the plugin
	 * is active.
	 *
	 * @param array $actions associative array of action names to anchor tags
	 *
	 * @return array associative array of plugin action links
	 * @since 1.0.0
	 */
	public function plugin_action_links( $actions ) {
		$custom_actions = array(
			'configure' => sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array( 'page' => 'afrsm-pro-list' ), admin_url( 'admin.php' ) ) ), __( 'Settings', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
			'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce' ), __( 'Docs', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
			'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://www.thedotstore.com/support' ), __( 'Support', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
		);
		// add the links to the front of the actions list
		return array_merge( $custom_actions, $actions );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
	/**
	 * Allowed html tags used for wp_kses function
	 *
	 * @param array add custom tags
	 *
	 * @return array
	 * @since     1.0.0
	 *
	 */
	public static function afrsm_pro_allowed_html_tags() {
		$allowed_tags = array(
			'a'        => array(
				'href'         => array(),
				'title'        => array(),
				'class'        => array(),
				'target'       => array(),
				'data-tooltip' => array(),
			),
			'ul'       => array( 'class' => array() ),
			'li'       => array( 'class' => array() ),
			'div'      => array( 'class' => array(), 'id' => array() ),
			'select'   => array(
				'rel-id'   => array(),
				'id'       => array(),
				'name'     => array(),
				'class'    => array(),
				'multiple' => array(),
				'style'    => array(),
			),
			'input'    => array(
				'id'         => array(),
				'value'      => array(),
				'name'       => array(),
				'class'      => array(),
				'type'       => array(),
				'data-index' => array(),
			),
			'textarea' => array( 'id' => array(), 'name' => array(), 'class' => array() ),
			'option'   => array( 'id' => array(), 'selected' => array(), 'name' => array(), 'value' => array() ),
			'br'       => array(),
			'p'        => array(),
			'b'        => array( 'style' => array() ),
			'em'       => array(),
			'strong'   => array(),
			'i'        => array( 'class' => array() ),
			'span'     => array( 'class' => array() ),
			'small'    => array( 'class' => array() ),
			'label'    => array( 'class' => array(), 'id' => array(), 'for' => array() ),
		);
		return $allowed_tags;
	}
}