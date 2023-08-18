<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class AFRSM_Shipping_Method.
 *
 * WooCommerce Advanced flat rate shipping method class.
 */
if ( class_exists( 'AFRSM_Shipping_Method' ) ) {
	return; // Stop if the class already exists
}
class AFRSM_Shipping_Method extends WC_Shipping_Method {
	private static $admin_object = null;
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		$get_id                = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
		$post_title            = isset( $get_id ) ? get_the_title( $get_id ) : '';
		$shipping_method_id    = isset( $get_id ) && ! empty( $get_id ) ? $get_id : 'advanced_flat_rate_shipping';
		$shipping_method_title = ! empty( $post_title ) ? $post_title : esc_html__( 'Advanced Flat Rate Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
		$this->id              = $shipping_method_id;
		$this->title           = __( 'Advanced Flat Rate Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
		$this->method_title    = __( $shipping_method_title, 'advanced-flat-rate-shipping-for-woocommerce' );
		$this->afrsm_shipping_init();
		// Save settings
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
	}
	/**
	 * Init
	 *
	 * @since 3.0.0
	 */
	function afrsm_shipping_init() {
		$this->afrsm_shipping_init_form_fields();
		$this->init_settings();
	}
	/**
	 * Init form fields.
	 *
	 * @since 3.0.0
	 */
	public function afrsm_shipping_init_form_fields() {
		$this->form_fields = array(
			'advanced_flat_rate_shipping_table' => array(
				'type' => 'advanced_flat_rate_shipping_table',
			),
		);
	}
	/**
	 * List all shipping method
	 *
	 * @since 3.0.0
	 */
	public function afrsm_shipping_generate_advanced_flat_rate_shipping_table_html() {
		ob_start();
		require plugin_dir_path( __FILE__ ) . 'afrsm-pro-list-page.php';
		return ob_get_clean();
	}
	/**
	 * Calculate shipping.
	 *
	 * @param array $package List containing all products for this method.
	 *
	 * @return bool false if $matched_shipping_methods is false then it will return false
	 * @since 3.0.0
	 *
	 * @uses  get_default_language()
	 * @uses  afrsm_match_methods()
	 * @uses  WC_Cart::get_cart()
	 * @uses  afrsm_allow_customer()
	 * @uses  afrsm_forceall()
	 * @uses  afrsm_fees_per_qty_on_ap_rules_off()
	 * @uses  afrsm_cart_subtotal_before_discount_cost()
	 * @uses  afrsm_cart_subtotal_after_discount_cost()
	 * @uses  afrsm_evaluate_cost()
	 * @uses  afrsm_get_package_item_qty()
	 * @uses  afrsm_find_shipping_classes()
	 * @uses  get_term_by()
	 * @uses  WC_Shipping_Method::add_rate()
	 *
	 */
	public function calculate_shipping( $package = array() ) {
		global $sitepress;
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				global $woocommerce_wpml;
			}
		}
		
		$default_lang             = self::$admin_object->afrsm_pro_get_default_langugae_with_sitpress();
		$matched_shipping_methods = $this->afrsm_shipping_match_methods( $package, $sitepress, $default_lang );
		
		if ( false === $matched_shipping_methods || ! is_array( $matched_shipping_methods ) || empty( $matched_shipping_methods ) ) {
			$Custom_shipping = apply_filters( 'no_afrsm_availble', array() );
			if ( ! empty( $Custom_shipping ) && is_array( $Custom_shipping ) ) {
				$this->add_rate( $Custom_shipping );
				return false;
			} else {
				return false;
			}
		}
		$cart_array = self::$admin_object->afrsm_pro_get_cart();

        // Compatibility with Autoship Cloud powered by QPilot (#45167)
        if( function_exists('autoship_cart_has_valid_autoship_items') ){
            if( autoship_cart_has_valid_autoship_items() ){
                return;
            }
        }
		
		if ( afrsfw_fs()->is__premium_only() ) {
			if ( afrsfw_fs()->can_use_premium_code() ) {
				$get_what_to_do_method = get_option( 'what_to_do_method' );
				$get_what_to_do_method = ! empty( $get_what_to_do_method ) ? $get_what_to_do_method : 'allow_customer';
				/**
				 * Allow customer to choose shipping
				 */
				if ( 'allow_customer' === $get_what_to_do_method || 'apply_smallest' === $get_what_to_do_method || 'apply_highest' === $get_what_to_do_method ) {
					$matched_shipping_methods = $this->afrsm_shipping_allow_customer__premium_only( $matched_shipping_methods, $default_lang );
				}
				/**
				 * Apply for force all shipping rate
				 */
				if ( 'force_all' === $get_what_to_do_method ) {
					$matched_shipping_methods = $this->afrsm_shipping_forceall__premium_only( $cart_array, $matched_shipping_methods, $sitepress, $default_lang );
				}
			}
		} else {
			$getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
			$sort_order   = array();
			if ( ! empty( $getSortOrder ) ) {
				foreach ( $getSortOrder as $getSortOrder_id ) {
					settype( $getSortOrder_id, 'integer' );
					if ( in_array( $getSortOrder_id, $matched_shipping_methods, true ) ) {
						$sort_order[] = $getSortOrder_id;
					}
				}
				unset( $matched_shipping_methods );
				$matched_shipping_methods = $sort_order;
			}
		}
		/**
		 * match shipping methods
		 */
		if ( ! empty( $matched_shipping_methods ) ) {
			// ordering issue and highest, smallest, forceall shipping issue code
			foreach ( $matched_shipping_methods as $main_shipping_method_id_val ) {
				if ( ! empty( $main_shipping_method_id_val ) || $main_shipping_method_id_val !== 0 ) {
					if ( ! empty( $sitepress ) ) {
						$shipping_method_id_val = apply_filters( 'wpml_object_id', $main_shipping_method_id_val, 'wc_afrsm', true, $default_lang );
					} else {
						$shipping_method_id_val = $main_shipping_method_id_val;
					}
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							$get_condition_array = get_post_meta( $shipping_method_id_val, 'sm_metabox', true );
						}
					}
					$shipping_title = get_the_title( $shipping_method_id_val );
					$shipping_rate  = array(
						'id'    => 'advanced_flat_rate_shipping' . ':' . $shipping_method_id_val,
						'label' => __( $shipping_title, 'advanced-flat-rate-shipping-for-woocommerce' ),
						'cost'  => 0,
					);
					$cart_based_qty = '0';
					if ( ! empty( $cart_array ) ) {
						$cart_product_ids_arr = array();
						foreach ( $cart_array as $value ) {
							if ( ! empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ) {
								$product_id_lan = $value['variation_id'];
							} else {
								$product_id_lan = $value['product_id'];
							}
							$cart_product_ids_arr[] = $product_id_lan; 
							$_product      = wc_get_product( $product_id_lan );
							$check_virtual = self::$admin_object->afrsm_check_product_type_for_front( $_product, $value );
							if ( true === $check_virtual ) {
								$cart_based_qty += $value['quantity'];
							}
						}
					}
					// Calculate the costs
					$has_costs = false; // True when a cost is set. False if all costs are blank strings.
					$costs     = get_post_meta( $shipping_method_id_val, 'sm_product_cost', true );
					$cost_args = array(
						'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
						'cost' => $package['contents_cost'],
					);
					$costs     = $this->afrsm_shipping_evaluate_cost( $costs, $cost_args );
					$ap_rule_status  = get_post_meta( $shipping_method_id_val, 'ap_rule_status', true );
					$cost_on_total_cart_weight_status  = get_post_meta( $shipping_method_id_val, 'cost_on_total_cart_weight_status', true );
					$cost_on_total_cart_subtotal_status = get_post_meta( $shipping_method_id_val, 'cost_on_total_cart_subtotal_status', true );
					if ( 'on' === $ap_rule_status ) {
						$cost_rule_match = get_post_meta( $shipping_method_id_val, 'cost_rule_match', true );
						if ( ! empty( $cost_rule_match ) ) {
							if ( is_serialized( $cost_rule_match ) ) {
								$cost_rule_match = maybe_unserialize( $cost_rule_match );
							} else {
								$cost_rule_match = $cost_rule_match;
							}
							if ( array_key_exists( 'cost_on_total_cart_weight_rule_match', $cost_rule_match ) ) {
								$cost_on_total_cart_weight_rule_match = $cost_rule_match['cost_on_total_cart_weight_rule_match'];
							} else {
								$cost_on_total_cart_weight_rule_match = 'any';
							}
							if ( array_key_exists( 'cost_on_total_cart_subtotal_rule_match', $cost_rule_match ) ) {
								$cost_on_total_cart_subtotal_rule_match = $cost_rule_match['cost_on_total_cart_subtotal_rule_match'];
							} else {
								$cost_on_total_cart_subtotal_rule_match = 'any';
							}
						} else {
							$cost_on_total_cart_weight_rule_match       = 'any';
							$cost_on_total_cart_subtotal_rule_match     = 'any';
						}
						$get_condition_array_ap_total_cart_weight       = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_total_cart_weight', true );
						$get_condition_array_ap_total_cart_subtotal     = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_total_cart_subtotal', true );
					}else{
						$get_condition_array_ap_total_cart_weight       = '';
						$get_condition_array_ap_total_cart_subtotal     = '';
						$cost_on_total_cart_weight_rule_match           = 'any';
						$cost_on_total_cart_subtotal_rule_match         = 'any';
					}
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							$getFeesPerQtyFlag                      = get_post_meta( $shipping_method_id_val, 'sm_fee_chk_qty_price', true );
							$getFeesPerQty                          = get_post_meta( $shipping_method_id_val, 'sm_fee_per_qty', true );
							$extraProductCostOriginal               = get_post_meta( $shipping_method_id_val, 'sm_extra_product_cost', true );
							$cost_on_product_status                 = get_post_meta( $shipping_method_id_val, 'cost_on_product_status', true );
							$cost_on_category_status                = get_post_meta( $shipping_method_id_val, 'cost_on_category_status', true );
							$cost_on_tag_status                     = get_post_meta( $shipping_method_id_val, 'cost_on_tag_status', true );
							$cost_on_total_cart_qty_status          = get_post_meta( $shipping_method_id_val, 'cost_on_total_cart_qty_status', true );
							$cost_on_product_weight_status          = get_post_meta( $shipping_method_id_val, 'cost_on_product_weight_status', true );
							$cost_on_category_weight_status         = get_post_meta( $shipping_method_id_val, 'cost_on_category_weight_status', true );
							$cost_on_tag_weight_status              = get_post_meta( $shipping_method_id_val, 'cost_on_tag_weight_status', true );
							$cost_on_shipping_class_weight_status   = get_post_meta( $shipping_method_id_val, 'cost_on_shipping_class_weight_status', true );
							$cost_on_product_subtotal_status        = get_post_meta( $shipping_method_id_val, 'cost_on_product_subtotal_status', true );
							$cost_on_category_subtotal_status       = get_post_meta( $shipping_method_id_val, 'cost_on_category_subtotal_status', true );
                            $cost_on_tag_subtotal_status            = get_post_meta( $shipping_method_id_val, 'cost_on_tag_subtotal_status', true );
							$cost_on_shipping_class_status          = get_post_meta( $shipping_method_id_val, 'cost_on_shipping_class_status', true );
							$cost_on_shipping_class_subtotal_status = get_post_meta( $shipping_method_id_val, 'cost_on_shipping_class_subtotal_status', true );
							$cost_on_product_attribute_status       = get_post_meta( $shipping_method_id_val, 'cost_on_product_attribute_status', true );
							//we can check that if advanced pricing enabled only than it will go for further process
							if ( 'on' === $ap_rule_status ) {
								$cost_rule_match = get_post_meta( $shipping_method_id_val, 'cost_rule_match', true );
								if ( ! empty( $cost_rule_match ) ) {
									if ( is_serialized( $cost_rule_match ) ) {
										$cost_rule_match = maybe_unserialize( $cost_rule_match );
									} else {
										$cost_rule_match = $cost_rule_match;
									}
									if ( array_key_exists( 'cost_on_product_rule_match', $cost_rule_match ) ) {
										$cost_on_product_rule_match = $cost_rule_match['cost_on_product_rule_match'];
									} else {
										$cost_on_product_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_product_weight_rule_match', $cost_rule_match ) ) {
										$cost_on_product_weight_rule_match = $cost_rule_match['cost_on_product_weight_rule_match'];
									} else {
										$cost_on_product_weight_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_product_subtotal_rule_match', $cost_rule_match ) ) {
										$cost_on_product_subtotal_rule_match = $cost_rule_match['cost_on_product_subtotal_rule_match'];
									} else {
										$cost_on_product_subtotal_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_category_rule_match', $cost_rule_match ) ) {
										$cost_on_category_rule_match = $cost_rule_match['cost_on_category_rule_match'];
									} else {
										$cost_on_category_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_category_weight_rule_match', $cost_rule_match ) ) {
										$cost_on_category_weight_rule_match = $cost_rule_match['cost_on_category_weight_rule_match'];
									} else {
										$cost_on_category_weight_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_category_subtotal_rule_match', $cost_rule_match ) ) {
										$cost_on_category_subtotal_rule_match = $cost_rule_match['cost_on_category_subtotal_rule_match'];
									} else {
										$cost_on_category_subtotal_rule_match = 'any';
									}
                                    if ( array_key_exists( 'cost_on_tag_rule_match', $cost_rule_match ) ) {
										$cost_on_tag_rule_match = $cost_rule_match['cost_on_tag_rule_match'];
									} else {
										$cost_on_tag_rule_match = 'any';
									}
                                    if ( array_key_exists( 'cost_on_tag_subtotal_rule_match', $cost_rule_match ) ) {
										$cost_on_tag_subtotal_rule_match = $cost_rule_match['cost_on_tag_subtotal_rule_match'];
									} else {
										$cost_on_tag_subtotal_rule_match = 'any';
									}
                                    if ( array_key_exists( 'cost_on_tag_weight_rule_match', $cost_rule_match ) ) {
										$cost_on_tag_weight_rule_match = $cost_rule_match['cost_on_tag_weight_rule_match'];
									} else {
										$cost_on_tag_weight_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_total_cart_qty_rule_match', $cost_rule_match ) ) {
										$cost_on_total_cart_qty_rule_match = $cost_rule_match['cost_on_total_cart_qty_rule_match'];
									} else {
										$cost_on_total_cart_qty_rule_match = 'any';
									}
                                    if ( array_key_exists( 'cost_on_shipping_class_rule_match', $cost_rule_match ) ) {
										$cost_on_shipping_class_rule_match = $cost_rule_match['cost_on_shipping_class_rule_match'];
									} else {
										$cost_on_shipping_class_rule_match = 'any';
									}
                                    if ( array_key_exists( 'cost_on_shipping_class_weight_rule_match', $cost_rule_match ) ) {
										$cost_on_shipping_class_weight_rule_match = $cost_rule_match['cost_on_shipping_class_weight_rule_match'];
									} else {
										$cost_on_shipping_class_weight_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_shipping_class_subtotal_rule_match', $cost_rule_match ) ) {
										$cost_on_shipping_class_subtotal_rule_match = $cost_rule_match['cost_on_shipping_class_subtotal_rule_match'];
									} else {
										$cost_on_shipping_class_subtotal_rule_match = 'any';
									}
									if ( array_key_exists( 'cost_on_product_attribute_rule_match', $cost_rule_match ) ) {
										$cost_on_product_attribute_rule_match = $cost_rule_match['cost_on_product_attribute_rule_match'];
									} else {
										$cost_on_product_attribute_rule_match = 'any';
									}
								} else {
									$cost_on_product_rule_match                 = 'any';
									$cost_on_product_weight_rule_match          = 'any';
									$cost_on_product_subtotal_rule_match        = 'any';
									$cost_on_category_rule_match                = 'any';
									$cost_on_category_weight_rule_match         = 'any';
									$cost_on_category_subtotal_rule_match       = 'any';
                                    $cost_on_tag_rule_match                     = 'any';
                                    $cost_on_tag_subtotal_rule_match            = 'any';
                                    $cost_on_tag_weight_rule_match              = 'any';
									$cost_on_total_cart_qty_rule_match          = 'any';
									$cost_on_total_cart_weight_rule_match       = 'any';
									$cost_on_total_cart_subtotal_rule_match     = 'any';
                                    $cost_on_shipping_class_rule_match          = 'any';
                                    $cost_on_shipping_class_weight_rule_match   = 'any';
									$cost_on_shipping_class_subtotal_rule_match = 'any';
                                    $cost_on_product_attribute_rule_match       = 'any';
								}
								$get_condition_array_ap_product                 = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product', true );
								$get_condition_array_ap_category                = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_category', true );
								$get_condition_array_ap_tag                     = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_tag', true );
								$get_condition_array_ap_total_cart_qty          = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_total_cart_qty', true );
								$get_condition_array_ap_product_weight          = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product_weight', true );
								$get_condition_array_ap_category_weight         = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_category_weight', true );
								$get_condition_array_ap_tag_weight              = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_tag_weight', true );
								$get_condition_array_ap_product_subtotal        = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product_subtotal', true );
								$get_condition_array_ap_category_subtotal       = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_category_subtotal', true );
								$get_condition_array_ap_tag_subtotal            = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_tag_subtotal', true );
								$get_condition_array_ap_shipping_class          = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_shipping_class', true );
                                $get_condition_array_ap_shipping_class_weight   = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_shipping_class_weight', true );
								$get_condition_array_ap_shipping_class_subtotal = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_shipping_class_subtotal', true );
								$get_condition_array_ap_product_attribute       = get_post_meta( $shipping_method_id_val, 'sm_metabox_ap_product_attribute', true );
							} else {
								//if no methods created than set null to all variables
								$get_condition_array_ap_product                 = '';
								$get_condition_array_ap_category                = '';
                                $get_condition_array_ap_tag                     = '';
								$get_condition_array_ap_total_cart_qty          = '';
								$get_condition_array_ap_product_weight          = '';
								$get_condition_array_ap_category_weight         = '';
								$get_condition_array_ap_total_cart_weight       = '';
								$get_condition_array_ap_total_cart_subtotal     = '';
								$get_condition_array_ap_product_subtotal        = '';
								$get_condition_array_ap_category_subtotal       = '';
                                $get_condition_array_ap_tag_subtotal            = '';
                                $get_condition_array_ap_tag_weight              = '';
                                $get_condition_array_ap_shipping_class          = '';
                                $get_condition_array_ap_shipping_class_weight   = '';
								$get_condition_array_ap_shipping_class_subtotal = '';
                                $get_condition_array_ap_product_attribute       = '';
								$cost_on_product_rule_match                     = 'any';
								$cost_on_product_weight_rule_match              = 'any';
								$cost_on_product_subtotal_rule_match            = 'any';
								$cost_on_category_subtotal_rule_match           = 'any';
								$cost_on_category_rule_match                    = 'any';
                                $cost_on_tag_rule_match                         = 'any';
                                $cost_on_tag_subtotal_rule_match                = 'any';
								$cost_on_category_weight_rule_match             = 'any';
                                $cost_on_tag_weight_rule_match                  = 'any';
								$cost_on_total_cart_qty_rule_match              = 'any';
								$cost_on_total_cart_weight_rule_match           = 'any';
								$cost_on_total_cart_subtotal_rule_match         = 'any';
                                $cost_on_shipping_class_rule_match              = 'any';
                                $cost_on_shipping_class_weight_rule_match       = 'any';
								$cost_on_shipping_class_subtotal_rule_match     = 'any';
                                $cost_on_product_attribute_rule_match           = 'any';
							}
							if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
								$extraProductCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $extraProductCostOriginal );
							} else {
								$extraProductCost = $extraProductCostOriginal;
							}
						}
					}
					$cost = $costs;
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							$products_based_qty    = 0;
							$before_discount_cost  = array();
							$after_discount_cost   = array();
							$product_specific_cost = array();
							//add new condition for apply per quantity only apply if advanced pricing rule disabled
							if ( 'on' === $getFeesPerQtyFlag && 'on' !== $ap_rule_status ) {
								$products_based_qty = self::$admin_object->afrsm_shipping_fees_get_per_product_qty__premium_only( $shipping_method_id_val, $cart_array, $products_based_qty, $sitepress, $default_lang );
								if ( 'qty_cart_based' === $getFeesPerQty ) {
									$cost = $costs + ( ( $cart_based_qty - 1 ) * $extraProductCost );
								} else if ( 'qty_product_based' === $getFeesPerQty ) {
									if ( 0 !== $products_based_qty ) {
										$extraProductCost = $this->afrsm_price_format( $extraProductCost );
										$cost             = $costs + ( ( $products_based_qty - 1 ) * $extraProductCost );
									}
								}
								// Per Qty Condition end
							} else {
								$cost = $costs;
								if ( ! empty( $get_condition_array ) ) {
									$cart_total_array      		= array();
									$cart_totalafter_array 		= array();
									$cart_productspecific_array = array();
									foreach ( $get_condition_array as $key => $value ) {
										if ( array_search( 'cart_total', $value, true ) ) {
											$before_discount_cost['before'] = $this->afrsm_shipping_cart_subtotal_before_discount_cost__premium_only( $cart_total_array, $value, $key, $package );
										}
										if ( array_search( 'cart_totalafter', $value, true ) ) {
											$after_discount_cost['after'] = $this->afrsm_shipping_cart_subtotal_after_discount_cost__premium_only( $cart_totalafter_array, $value, $key, $package );
										}
										if ( array_search( 'cart_productspecific', $value, true ) ) {
											$product_specific_cost['productspecific'] = $this->afrsm_shipping_cart_subtotal_cart_productspecific_cost__premium_only( $cart_productspecific_array, $value, $key, $package, $get_condition_array );
										}
									}
								}
							}
						}
					}
					$sm_taxable                     = get_post_meta( $shipping_method_id_val, 'sm_select_taxable', true );
					$sm_extra_cost_calculation_type = get_post_meta( $shipping_method_id_val, 'sm_extra_cost_calculation_type', true );
					if ( '' !== $cost ) {
						$has_costs = true;
						if ( afrsfw_fs()->is__premium_only() ) {
							if ( afrsfw_fs()->can_use_premium_code() ) {
								if ( ! empty( $before_discount_cost ) ) {
									if ( array_key_exists( 'before', $before_discount_cost ) ) {
										$shipping_rate['cost'] = $this->afrsm_shipping_evaluate_cost( $cost, $before_discount_cost['before'] );
									}
								} else if ( ! empty( $after_discount_cost ) ) {
									if ( array_key_exists( 'after', $after_discount_cost ) ) {
										$shipping_rate['cost'] = $this->afrsm_shipping_evaluate_cost( $cost, $after_discount_cost['after'] );
									}
								} else if( ! empty( $product_specific_cost) ) {
									if ( array_key_exists( 'productspecific', $product_specific_cost ) ) {
										$shipping_rate['cost'] = $this->afrsm_shipping_evaluate_cost( $cost, $product_specific_cost['productspecific'] );
									}
								} else {
									$cost_args             = array(
										'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
										'cost' => $package['contents_cost'],
									);
									$shipping_rate['cost'] = $this->afrsm_shipping_evaluate_cost( $cost, $cost_args );
								}
							}
						} else {
							$cost_args             = array(
								'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
								'cost' => $package['contents_cost'],
							);
							$shipping_rate['cost'] = $this->afrsm_shipping_evaluate_cost( $cost, $cost_args );
						}
					}
					// Add shipping class costs
					$found_shipping_classes = $this->afrsm_shipping_find_shipping_classes( $package );
					$highest_class_cost     = 0;
					if ( ! empty( $found_shipping_classes ) ) {
						foreach ( $found_shipping_classes as $shipping_class => $products ) {
							$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
							$shipping_extra_id   = '';
							if ( false !== $shipping_class_term ) {
								if ( ! empty( $sitepress ) ) {
									$shipping_extra_id = apply_filters( 'wpml_object_id', $shipping_class_term->term_id, 'product_shipping_class', true, $default_lang );
								} else {
									$shipping_extra_id = $shipping_class_term->term_id;
								}
							}
							$sm_extra_cost     = get_post_meta( $shipping_method_id_val, 'sm_extra_cost', true );
							$class_cost_string = isset( $sm_extra_cost[ $shipping_extra_id ] ) && ! empty( $sm_extra_cost[ $shipping_extra_id ] ) ? $sm_extra_cost[ $shipping_extra_id ] : '';
							if ( '' === $class_cost_string ) {
								continue;
							}
							$has_costs  = true;
							$class_cost = $this->afrsm_shipping_evaluate_cost( $class_cost_string, array(
								'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
								'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
							) );
							if ( 'per_class' === $sm_extra_cost_calculation_type ) {
								$shipping_rate['cost'] += $class_cost;
							} else {
								$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
							}
						}
						if ( 'per_order' === $sm_extra_cost_calculation_type && $highest_class_cost ) {
							$shipping_rate['cost'] += $highest_class_cost;
						}
					}
					// apply for tax
					if ( 'no' === $sm_taxable ) {
						$shipping_rate['taxes'] = false;
					} else {
						$shipping_rate['taxes'] = '';
					}
					$match_advance_rule = array();
					if ( 'on' === $cost_on_total_cart_weight_status ) {
						$match_advance_rule['hfbotcw'] = $this->afrsm_shipping_advance_pricing_rules_total_cart_weight( $get_condition_array_ap_total_cart_weight, $cart_array, $cost_on_total_cart_weight_rule_match );
					}
					if ( 'on' === $cost_on_total_cart_subtotal_status ) {
						$match_advance_rule['hfbotcs'] = $this->afrsm_shipping_advance_pricing_rules_total_cart_subtotal( $get_condition_array_ap_total_cart_subtotal, $cart_array, $cost_on_total_cart_subtotal_rule_match );
					}
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							/* Apply Advanced Pricing rules Cost start here */
							
							if ( 'on' === $cost_on_product_status ) {
								$match_advance_rule['hfbopq'] = $this->afrsm_shipping_advance_pricing_rules_product_per_qty__premium_only( $get_condition_array_ap_product, $cart_array, $sitepress, $default_lang, $cost_on_product_rule_match );
							}
							if ( 'on' === $cost_on_product_subtotal_status ) {
								$match_advance_rule['hfbops'] = $this->afrsm_shipping_advance_pricing_rules_product_subtotal__premium_only( $get_condition_array_ap_product_subtotal, $cart_array, $cost_on_product_subtotal_rule_match, $sitepress, $default_lang );
							}
							if ( 'on' === $cost_on_product_weight_status ) {
								$match_advance_rule['hfbopw'] = $this->afrsm_shipping_advance_pricing_rules_product_per_weight__premium_only( $get_condition_array_ap_product_weight, $cart_array, $sitepress, $default_lang, $cost_on_product_weight_rule_match );
							}
							if ( 'on' === $cost_on_category_status ) {
								$match_advance_rule['hfbocq'] = $this->afrsm_shipping_advance_pricing_rules_category_per_qty__premium_only( $get_condition_array_ap_category, $cart_array, $sitepress, $default_lang, $cost_on_category_rule_match );
							}
							if ( 'on' === $cost_on_category_subtotal_status ) {
								$match_advance_rule['hfbocs'] = $this->afrsm_shipping_advance_pricing_rules_category_subtotal__premium_only( $get_condition_array_ap_category_subtotal, $cart_array, $cost_on_category_subtotal_rule_match, $sitepress, $default_lang );
							}
							if ( 'on' === $cost_on_category_weight_status ) {
								$match_advance_rule['hfbocw'] = $this->afrsm_shipping_advance_pricing_rules_category_per_weight__premium_only( $get_condition_array_ap_category_weight, $cart_array, $sitepress, $default_lang, $cost_on_category_weight_rule_match );
							}
                            if ( 'on' === $cost_on_tag_status ) {
								$match_advance_rule['hfbotq'] = $this->afrsm_shipping_advance_pricing_rules_tag_per_qty__premium_only( $get_condition_array_ap_tag, $cart_array, $sitepress, $default_lang, $cost_on_tag_rule_match );
							}
                            if ( 'on' === $cost_on_tag_subtotal_status ) {
								$match_advance_rule['hfbots'] = $this->afrsm_shipping_advance_pricing_rules_tag_subtotal__premium_only( $get_condition_array_ap_tag_subtotal, $cart_array, $cost_on_tag_subtotal_rule_match, $sitepress, $default_lang );
							}
                            if ( 'on' === $cost_on_tag_weight_status ) {
								$match_advance_rule['hfbotw'] = $this->afrsm_shipping_advance_pricing_rules_tag_per_weight__premium_only( $get_condition_array_ap_tag_weight, $cart_array, $sitepress, $default_lang, $cost_on_tag_weight_rule_match );
							}
							if ( 'on' === $cost_on_total_cart_qty_status ) {
								$match_advance_rule['hfbotcq'] = $this->afrsm_shipping_advance_pricing_rules_total_cart_qty__premium_only( $get_condition_array_ap_total_cart_qty, $cart_array, $cost_on_total_cart_qty_rule_match );
							}
                            if ( 'on' === $cost_on_shipping_class_status ) {
								$match_advance_rule['hfbscq'] = $this->afrsm_shipping_advance_pricing_rules_shipping_class_per_qty__premium_only( $get_condition_array_ap_shipping_class, $cart_array, $cost_on_shipping_class_rule_match, $sitepress, $default_lang );
							}
                            if ( 'on' === $cost_on_shipping_class_weight_status ) {
                                $match_advance_rule['hfbotw'] = $this->afrsm_shipping_advance_pricing_rules_shipping_class_weight__premium_only( $get_condition_array_ap_shipping_class_weight, $cart_array, $sitepress, $default_lang, $cost_on_shipping_class_weight_rule_match );
                            }
							if ( 'on' === $cost_on_shipping_class_subtotal_status ) {
								$match_advance_rule['hfbscs'] = $this->afrsm_shipping_advance_pricing_rules_shipping_class_subtotal__premium_only( $get_condition_array_ap_shipping_class_subtotal, $cart_array, $cost_on_shipping_class_subtotal_rule_match, $sitepress, $default_lang );
							}
                            if ( 'on' === $cost_on_product_attribute_status ) {
								$match_advance_rule['hfbopaq'] = $this->afrsm_shipping_advance_pricing_rules_product_attribute_per_qty__premium_only( $get_condition_array_ap_product_attribute, $cart_array, $cost_on_product_attribute_rule_match, $sitepress, $default_lang );
							}
						}
					}
					/**
					 * Filter for advanced matching URL.
					 *
					 * @since  3.8
					 *
					 * @author jb
					 */
					$match_advance_rule[] = apply_filters( 'afrsm_pro_match_advance_pricing_rules', $shipping_method_id_val, $cart_array, $sitepress, $default_lang );
					$advance_shipping_rate = 0;
					if ( isset( $match_advance_rule ) && ! empty( $match_advance_rule ) && is_array( $match_advance_rule ) ) {
						foreach ( $match_advance_rule as $val ) {
							if( is_array($val) || is_object($val) ){
								if ( '' !== $val['flag'] && 'yes' === $val['flag'] ) {
									$advance_shipping_rate += $val['total_amount'];
								}
							}
						}
					}
					$advance_shipping_rate = $this->afrsm_price_format( $advance_shipping_rate );
					$shipping_rate['cost'] += $advance_shipping_rate;

					$shipping_rate['cost'] = $this->afrsm_price_format( $shipping_rate['cost'] );
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							/*** allow each weight rule ***/
							$is_allow_custom_weight_base = get_post_meta( $shipping_method_id_val, 'is_allow_custom_weight_base', true );
							if("on" === $is_allow_custom_weight_base){

								$total_cart_weights = WC()->cart->get_cart_contents_weight();

								$sm_custom_weight_base_cost = get_post_meta( $shipping_method_id_val, 'sm_custom_weight_base_cost', true );
								$sm_custom_weight_base_per_each = get_post_meta( $shipping_method_id_val, 'sm_custom_weight_base_per_each', true );
								$sm_custom_weight_base_over = get_post_meta( $shipping_method_id_val, 'sm_custom_weight_base_over', true );
								$sm_custom_weight_base_cost_shipping = 0;
								if( ($total_cart_weights > 0) && ($total_cart_weights >= $sm_custom_weight_base_per_each) ){
									if( '' !== $sm_custom_weight_base_over ){
										if( $total_cart_weights >= $sm_custom_weight_base_over ){
											$total_cart_weights = ($total_cart_weights - $sm_custom_weight_base_over);
											$sm_custom_weight_base_cost_part = (float)( $total_cart_weights / $sm_custom_weight_base_per_each );
											$sm_custom_weight_base_cost_shipping = floatval( $sm_custom_weight_base_cost * $sm_custom_weight_base_cost_part );
										}
									}else{
										$sm_custom_weight_base_cost_part = (float)( $total_cart_weights / $sm_custom_weight_base_per_each );
										$sm_custom_weight_base_cost_shipping = floatval( $sm_custom_weight_base_cost * $sm_custom_weight_base_cost_part );
									}
									$shipping_rate['cost'] += $sm_custom_weight_base_cost_shipping;
								}

							}
							
							/*** allow each quantity rule ***/
							$is_allow_custom_qty_base = get_post_meta( $shipping_method_id_val, 'is_allow_custom_qty_base', true );
							if("on" === $is_allow_custom_qty_base){

								$total_cart_qty = WC()->cart->get_cart_contents_count();

								$sm_custom_qty_base_cost = get_post_meta( $shipping_method_id_val, 'sm_custom_qty_base_cost', true );
								$sm_custom_qty_base_per_each = get_post_meta( $shipping_method_id_val, 'sm_custom_qty_base_per_each', true );
								$sm_custom_qty_base_over = get_post_meta( $shipping_method_id_val, 'sm_custom_qty_base_over', true );
								$sm_custom_qty_base_cost_shipping = 0;
								if( ($total_cart_qty > 0) && ($total_cart_qty >= $sm_custom_qty_base_per_each) ){
									if( '' !== $sm_custom_qty_base_over ){
										if( $total_cart_qty >= $sm_custom_qty_base_over ){
											$total_cart_qty = ($total_cart_qty - $sm_custom_qty_base_over);
											$sm_custom_qty_base_cost_part = (float)( $total_cart_qty / $sm_custom_qty_base_per_each );
											$sm_custom_qty_base_cost_shipping = floatval( $sm_custom_qty_base_cost * $sm_custom_qty_base_cost_part );
										}
									}else{
										$sm_custom_qty_base_cost_part = (float)( $total_cart_qty / $sm_custom_qty_base_per_each );
										$sm_custom_qty_base_cost_shipping = floatval( $sm_custom_qty_base_cost * $sm_custom_qty_base_cost_part );
									}
									$shipping_rate['cost'] += $sm_custom_qty_base_cost_shipping;
								}

							}
						}
					}
					
					/*** allow free shipping Start ***/
					$free_shipping_status = get_post_meta( $shipping_method_id_val, 'is_allow_free_shipping', true );
					if("on" === $free_shipping_status){
						$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
						$free_shipping_based_on = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_based_on', true );
						$free_shipping_costs = $admin_object->afrsm_woocs_convert_price( get_post_meta( $shipping_method_id_val, 'sm_free_shipping_cost', true ) );
						$free_shipping_coupan_cost = $admin_object->afrsm_woocs_convert_price( get_post_meta( $shipping_method_id_val, 'sm_free_shipping_coupan_cost', true ) );
						$free_shipping_label = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_label', true );
						$free_shipping_based_on_product = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_based_on_product', true );
						$sm_free_shipping_exclude_product = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_exclude_product', true );
                        $is_free_shipping_exclude_prod = get_post_meta( $shipping_method_id_val, 'is_free_shipping_exclude_prod', true );
                        
						$total_cart_value = WC()->cart->subtotal;

						$total_discount_value    = $admin_object->afrsm_pro_remove_currency_symbol( WC()->cart->get_total_discount() );
						
						if ( afrsfw_fs()->is__premium_only() ) {
							if ( afrsfw_fs()->can_use_premium_code() ) {
								$is_free_shipping_before_discount = get_post_meta( $shipping_method_id_val, 'sm_free_shipping_cost_before_discount', true );
							}
						}
						
						if("min_order_amt" === $free_shipping_based_on){
							if ( afrsfw_fs()->is__premium_only() ) {
								if ( afrsfw_fs()->can_use_premium_code() ) {
									if( "on" === $is_free_shipping_before_discount ){
										$final_total_cart_value = $total_cart_value;
									}else{
										$final_total_cart_value = ( $total_cart_value - $total_discount_value );
									}
								}else{
									$final_total_cart_value = ( $total_cart_value - $total_discount_value );
								}
							}else{
								$final_total_cart_value = ( $total_cart_value - $total_discount_value );
							}
                            if( "on" === $is_free_shipping_exclude_prod){
                                $exlude_product_subtotal = $this->afrsm_cart_exclude_product_subtotal( $sm_free_shipping_exclude_product );
                                if( $exlude_product_subtotal <= $final_total_cart_value ) {
                                    $final_total_cart_value = $final_total_cart_value - $exlude_product_subtotal;
                                }
                            }
							if( ("" !== $free_shipping_costs) && ($final_total_cart_value >= $free_shipping_costs) ){
								$has_costs = true;
								$shipping_rate['cost'] = "";
								$shipping_rate['label'] = ! empty( $free_shipping_label ) ? $free_shipping_label : $shipping_rate['label'];
								$this->add_rate( $shipping_rate );
							}
						}						
						if("min_coupan_amt" === $free_shipping_based_on){
							if( ("" !== $free_shipping_coupan_cost) && ($total_discount_value > $free_shipping_coupan_cost) ){
								$has_costs = true;
								$shipping_rate['cost'] = "";
								$shipping_rate['label'] = ! empty( $free_shipping_label ) ? $free_shipping_label : $shipping_rate['label'];
								$this->add_rate( $shipping_rate );	
							}
						}
						if("min_simple_product" === $free_shipping_based_on){

							if ( ! empty( $cart_array ) ) {
								if ( ! empty( $free_shipping_based_on_product ) ) {
									foreach ( $free_shipping_based_on_product as $free_p_id ) {
										settype( $free_p_id, 'integer' );
										if ( in_array( $free_p_id, $cart_product_ids_arr, true ) ) {
											$has_costs = true;
											$shipping_rate['cost'] = "";
											$shipping_rate['label'] = ! empty( $free_shipping_label ) ? $free_shipping_label : $shipping_rate['label'];
											$this->add_rate( $shipping_rate );
											break;
										}
									}
								}
							}
						}

					}	
					/*** allow free shipping End ***/

					if ( $has_costs ) {
						if ( afrsfw_fs()->is__premium_only() ) {
							if ( afrsfw_fs()->can_use_premium_code() ) {
								if ( 'force_all' === $get_what_to_do_method ) {
									$force_all_shipping_rate_pass_rate = array(
										'id'    => 'forceall',
										'label' => __( 'Forceall', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'cost'  => 0,
										'taxes' => 0,
									);
									$this->add_rate( $force_all_shipping_rate_pass_rate ); //apply rate in cart
								}
							}
						}
						if ( $shipping_rate['cost'] < 0 ) {
							//customize label of shipping method
							$shipping_rate['label'] = $shipping_rate['label'];
						}
						$this->add_rate( $shipping_rate ); //apply rate in cart
					}
					do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $shipping_rate, $package );
				}
			}
		}
	}
	/**
	 * Display all shipping method which will selectable
	 *
	 * @param array $matched_shipping_methods
	 *
	 * @return array $matched_shipping_methods
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_allow_customer__premium_only( $matched_shipping_methods, $default_lang ) {
		if ( ! empty( $matched_shipping_methods ) ) {
			$getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
			$sort_order   = array();
			if ( ! empty( $getSortOrder ) ) {
				foreach ( $getSortOrder as $getSortOrder_id ) {
					settype( $getSortOrder_id, 'integer' );
					if ( in_array( $getSortOrder_id, $matched_shipping_methods, true ) ) {
						$sort_order[] = $getSortOrder_id;
					}
				}
				unset( $matched_shipping_methods );
				$matched_shipping_methods = $sort_order;
			}
		}
		return $matched_shipping_methods;
	}
	/**
	 * Combine all shipping method in one shipping method with forceall key
	 *
	 * @param array $cart_array
	 * @param array $matched_shipping_methods
	 *
	 * @return array $matched_shipping_methods
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_forceall__premium_only( $cart_array, $matched_shipping_methods, $sitepress, $default_lang ) {
		if ( ! empty( $matched_shipping_methods ) ) {
			$costs_array = array();
			foreach ( $matched_shipping_methods as $main_shipping_method_id_val ) {
				if ( ! empty( $sitepress ) ) {
					$shipping_method_id = apply_filters( 'wpml_object_id', $main_shipping_method_id_val, 'wc_afrsm', true, $default_lang );
				} else {
					$shipping_method_id = $main_shipping_method_id_val;
				}
				$cart_based_qty   = '0';
				$cart_based_price = '0';
				$args             = array();
				if ( ! empty( $cart_array ) ) {
					foreach ( $cart_array as $value ) {
						$cart_based_qty   += intval( $value['quantity'] );
						$cart_based_price += $value['line_subtotal'];
						$args['qty']      = $cart_based_qty;
						$args['cost']     = $cart_based_price;
					}
				}
				// Calculate the costs
				$costs                              = get_post_meta( $shipping_method_id, 'sm_product_cost', true );
				$costs_array[ $shipping_method_id ] = $costs;
			}
			$forceall     = array();
			$total_costs  = 0;
			$i            = 0;
			$k_with_comma = array();

			foreach ( $costs_array as $k => $v ) {
				$new_total_costs    = $this->afrsm_shipping_evaluate_cost( $v, $args );
				$total_costs        = $total_costs + $new_total_costs;
				$forceall[ $i ]     = $k;
				$k_with_comma[ $i ] = $k;
				$i ++;
			}
			$forceall['forceall']     = 0;
			$matched_shipping_methods = $forceall;
		}
		return $matched_shipping_methods;
	}
	/**
	 * Get Product category from cart
	 *
	 * @param array  $cat_id_list
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $cat_id_list_origin
	 *
	 * @since 3.6
	 *
	 */
	public function afrsm_get_prd_category_from_cart__premium_only( $cat_id_list, $sitepress, $default_lang ) {
		$cat_id_list_origin = array();
		if ( isset( $cat_id_list ) && ! empty( $cat_id_list ) ) {
			foreach ( $cat_id_list as $cat_id ) {
				if ( ! empty( $sitepress ) ) {
					$cat_id_list_origin[] = (int) apply_filters( 'wpml_object_id', $cat_id, 'product_cat', true, $default_lang );
				} else {
					$cat_id_list_origin[] = (int) $cat_id;
				}
			}
		}
		return $cat_id_list_origin;
	}
    /**
	 * Get Product tag from cart
	 *
	 * @param array  $tag_id_list
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $tag_id_list_origin
	 *
	 * @since 3.6
	 *
	 */
	public function afrsm_get_prd_tag_from_cart__premium_only( $tag_id_list, $sitepress, $default_lang ) {
		$tag_id_list_origin = array();
		if ( isset( $tag_id_list ) && ! empty( $tag_id_list ) ) {
			foreach ( $tag_id_list as $tag_id ) {
				if ( ! empty( $sitepress ) ) {
					$tag_id_list_origin[] = (int) apply_filters( 'wpml_object_id', $tag_id, 'product_tag', true, $default_lang );
				} else {
					$tag_id_list_origin[] = (int) $tag_id;
				}
			}
		}
		return $tag_id_list_origin;
	}
	/**
	 * Get specific subtotal for product and category
	 *
	 * @return float $subtotal
	 *
	 * @since    3.6
	 */
	public function afrsm_pro_get_specific_subtotal__premium_only( $line_total, $line_tax ) {
		$get_customer            = WC()->cart->get_customer();
		$get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
		$tax_display_cart        = WC()->cart->get_tax_price_display_mode();//tax_display_cart;
		$tax_enable              = wc_tax_enabled();
		$cart_subtotal           = 0;
		if ( true === $tax_enable ) {
			if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
				$cart_subtotal += $line_total + $line_tax;
			} else {
				$cart_subtotal += $line_total;
			}
		} else {
			$cart_subtotal += $line_total;
		}
		return $cart_subtotal;
	}
	/**
	 * Count qty for Product, Category and Shipping Class
	 *
	 * @param array  $ap_selected_id
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $type
	 * @param string $qws
	 *
	 * @return int $total
	 *
	 * @since 3.6
	 *
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 * @uses  wp_get_post_terms()
	 * @uses  afrsm_get_prd_category_from_cart__premium_only()
	 *
	 */
	public function afrsm_get_count_qty__premium_only( $ap_selected_id, $woo_cart_array, $sitepress, $default_lang, $type, $qws ) {
		$total_qws = 0;
		if ( 'shipping_class' !== $type && 'product_attribute' !== $type ) {
            if ( ! empty( $ap_selected_id ) || '0' !== $ap_selected_id ) {
                $ap_selected_id = array_map( 'intval', $ap_selected_id );
			}
		}
		foreach ( $woo_cart_array as $woo_cart_item ) {
			$main_product_id_lan = $woo_cart_item['product_id'];
			if ( ! empty( $woo_cart_item['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) {
				$product_id_lan = $woo_cart_item['variation_id'];
			} else {
				$product_id_lan = $woo_cart_item['product_id'];
			}
			$_product = wc_get_product( $product_id_lan );
			if( $_product instanceof WC_Product ) {
				if ( ! ( $_product->is_virtual( 'yes' ) ) || $_product->is_type( 'bundle' ) ) {
					if ( ! empty( $sitepress ) ) {
						$product_id_lan = intval( apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang ) );
					} else {
						$product_id_lan = intval( $product_id_lan );
					}
					if ( 'product' === $type ) {
						if ( in_array( $product_id_lan, $ap_selected_id, true ) ) {
							if ( 'qty' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from qty count
								$total_qws += intval( $woo_cart_item['quantity'] );
							}
							if ( 'weight' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from weight
								$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
							}
							if ( 'subtotal' === $qws ) {
								if ( ! empty( $woo_cart_item['line_tax'] ) ) {
									$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
								}
								$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
							}
						}
					}
					if ( 'category' === $type ) {
						$cat_id_list        = wp_get_post_terms( $main_product_id_lan, 'product_cat', array( 'fields' => 'ids' ) );
						$cat_id_list_origin = $this->afrsm_get_prd_category_from_cart__premium_only( $cat_id_list, $sitepress, $default_lang );
						if ( ! empty( $cat_id_list_origin ) && is_array( $cat_id_list_origin ) ) {
							//new code
							$match_category = false;
							foreach ( $ap_selected_id as $ap_fees_categories_key_val ) {
								//replace line
								if ( in_array( $ap_fees_categories_key_val, $cat_id_list_origin, true ) && false === $match_category ) {
									if ( 'qty' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from qty count
										$total_qws += intval( $woo_cart_item['quantity'] );
									}
									if ( 'weight' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from weight
										$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
									}
									if ( 'subtotal' === $qws ) {
										if ( ! empty( $woo_cart_item['line_tax'] ) ) {
											$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
										}
										$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
									}
									//new code
									$match_category = true;
								}
							}
						}
					}
                    if ( 'tag' === $type ) {
						$tag_id_list        = wp_get_post_terms( $main_product_id_lan, 'product_tag', array( 'fields' => 'ids' ) );
						$tag_id_list_origin = $this->afrsm_get_prd_tag_from_cart__premium_only( $tag_id_list, $sitepress, $default_lang );
						if ( ! empty( $tag_id_list_origin ) && is_array( $tag_id_list_origin ) ) {
							//new code
							$match_category = false;
							foreach ( $ap_selected_id as $ap_fees_categories_key_val ) {
								//replace line
								if ( in_array( $ap_fees_categories_key_val, $tag_id_list_origin, true ) && false === $match_category ) {
									if ( 'qty' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from qty count
										$total_qws += intval( $woo_cart_item['quantity'] );
									}
									if ( 'weight' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from weight
										$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
									}
									if ( 'subtotal' === $qws ) {
										if ( ! empty( $woo_cart_item['line_tax'] ) ) {
											$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
										}
										$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
									}
									//new code
									$match_category = true;
								}
							}
						}
					}
					if ( 'shipping_class' === $type ) {
						$prd_shipping_class = $_product->get_shipping_class();
						if ( in_array( $prd_shipping_class, $ap_selected_id, true ) ) {
							if ( 'qty' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from qty count
								$total_qws += intval( $woo_cart_item['quantity'] );
							}
							if ( 'weight' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from weight
								$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
							}
							if ( 'subtotal' === $qws ) {
								if ( ! empty( $woo_cart_item['line_tax'] ) ) {
									$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
								}
								$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
							}
						}
					}
                    if ( 'product_attribute' === $type ){
                        $variation_cart_products_array = array();
                        if ( $_product->is_type( 'variation' ) ) {
                            $variation               = new WC_Product_Variation( $product_id_lan );
                            $variation_cart_products = $variation->get_variation_attributes();
                            foreach($variation_cart_products as $variation_cart_product) {
                                $variation_cart_products_array[] = $variation_cart_product;
                            }
                        } else if( $_product->is_type( 'simple' ) ) {
                            foreach( $_product->get_attributes() as $sa_val ){
                                foreach( $sa_val['options'] as $sa_option ){
                                    $sa_data = get_term_by('id', $sa_option, $sa_val['name']);
                                    $variation_cart_products_array[] = $sa_data->slug;
                                }
                            }
                        }
                        if( !empty( array_intersect($ap_selected_id,$variation_cart_products_array) ) ){
                            if ( 'qty' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from qty count
								$total_qws += intval( $woo_cart_item['quantity'] );
							}
							if ( 'weight' === $qws && !isset($woo_cart_item['wooco_parent_key']) ) { //For composite product skip from weight
								$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
							}
							if ( 'subtotal' === $qws ) {
								if ( ! empty( $woo_cart_item['line_tax'] ) ) {
									$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
								}
								$total_qws += $this->afrsm_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
							}
                        }
                    }
				}
			}
		}
		return apply_filters( 'afrsm_get_count_qty__premium_only_ft', $total_qws, $ap_selected_id, $woo_cart_array, $sitepress, $default_lang, $type, $qws );
	}
	/**
	 * Count for total cart
	 *
	 * @param array  $woo_cart_array
	 * @param string $type
	 * @param string $qws
	 *
	 * @return int $total
	 *
	 * @since 3.6
	 *
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 * @uses  wp_get_post_terms()
	 *
	 */
	public function afrsm_get_count_total_cart( $woo_cart_array, $qws ) {
		$total_qws = 0;
		foreach ( $woo_cart_array as $woo_cart_item ) {
			if ( ! empty( $woo_cart_item['variation_id'] ) && 0 !== $woo_cart_item['variation_id'] ) {
				$product_id_lan = $woo_cart_item['variation_id'];
			} else {
				$product_id_lan = $woo_cart_item['product_id'];
			}
			if ( 'qty' === $qws ) {
				if ( ! ( $woo_cart_item['data']->is_virtual() ) ) {
					$total_qws += $woo_cart_item['quantity'];
				}
			}
			if ( 'weight' === $qws ) {
				$_product = wc_get_product( $product_id_lan );
				if( $_product instanceof WC_Product ) {
					if ( ! ( $_product->is_virtual( 'yes' ) ) || $_product->is_type( 'bundle' ) ) {
						$total_qws += (int) $woo_cart_item['quantity'] * (float) $_product->get_weight();
					}
				}
			}
			if ( 'subtotal' === $qws ) {
				$total_qws = self::$admin_object->afrsm_pro_get_cart_subtotal();
			}
		}
		return apply_filters( 'afrsm_get_count_total_cart_ft', $total_qws, $woo_cart_array, $qws );
	}
	/**
	 * Check Min and max qty, weight and subtotal
	 *
	 * @param int|float $min
	 * @param int|float $max
	 * @param float     $price
	 * @param string    $qws
	 *
	 * @return array
	 *
	 * @since 3.4
	 *
	 */
	public function afrsm_check_min_max_qws( $min, $max, $price, $qws ) {
		$min_val = $min;
		if ( '' === $max || '0' === $max ) {
			$max_val = 2000000000;
		} else {
			$max_val = $max;
		}
		$price_val = $price;
		if ( 'qty' === $qws ) {
			settype( $min_val, 'integer' );
			settype( $max_val, 'integer' );
		} else {
			settype( $min_val, 'float' );
			settype( $max_val, 'float' );
		}
		return array(
			'min'   => $min_val,
			'max'   => $max_val,
			'price' => $price_val,
		);
	}
	/**
	 * Add shipping rate
	 *
	 * @param int|float $min
	 * @param int|float $max
	 * @param float     $price
	 * @param int|float $count_total
	 * @param float     $get_cart_total
	 * @param float     $shipping_rate_cost
	 *
	 * @return float $shipping_rate_cost
	 *
	 * @since 3.4
	 *
	 */
	public function afrsm_check_percantage_price( $price, $get_cart_total ) {
		if ( ! empty( $price ) ) {
			$is_percent = substr( $price, - 1 );
			if ( '%' === $is_percent ) {
				$percent = substr( $price, 0, - 1 );
				$percent = number_format( $percent, 2, '.', '' );
				if ( ! empty( $percent ) ) {
					$percent_total = ( $percent / 100 ) * $get_cart_total;
					$price         = $percent_total;
				}
			} else {
				$price = $this->afrsm_price_format( $price );
			}
		}
		return $price;
	}
	/**
	 * Find unique id based on given array
	 *
	 * @param array  $is_passed
	 * @param string $has_fee_checked
	 * @param string $has_fee_based
	 * @param string $advance_inside_rule_match
	 *
	 * @return array
	 * @since    3.6
	 *
	 */
	public function afrsm_pro_check_all_passed_advance_rule( $is_passed, $has_fee_checked, $has_fee_based, $advance_inside_rule_match ) {
		$get_cart_total = WC()->cart->get_cart_contents_total();
		$main_is_passed = 'no';
		$flag           = array();
		$sum_ammount    = 0;
		if ( ! empty( $is_passed ) ) {
			foreach ( $is_passed as $main_is_passed ) {
				foreach ( $main_is_passed[ $has_fee_checked ] as $key => $is_passed_value ) {
					if ( 'yes' === $is_passed_value ) {
						foreach ( $main_is_passed[ $has_fee_based ] as $hfb_key => $hfb_is_passed_value ) {
							if ( $hfb_key === $key ) {
								$final_price = $this->afrsm_check_percantage_price( $hfb_is_passed_value, $get_cart_total );
								$sum_ammount += $final_price;
							}
						}
						$flag[ $key ] = true;
					} else {
						$flag[ $key ] = false;
					}
				}
			}
			if ( 'any' === $advance_inside_rule_match ) {
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
		return array(
			'flag'         => $main_is_passed,
			'total_amount' => $sum_ammount,
		);
	}
	/**
	 * Cgeck rule passed or not
	 *
	 * @param string    $key
	 * @param string    $min
	 * @param string    $max
	 * @param string    $hbc
	 * @param string    $hbp
	 * @param float     $price
	 * @param int|float $total_qws
	 * @param string    $qws
	 *
	 * @return array
	 * @since    3.6
	 *
	 */
	public function afrsm_check_passed_rule( $key, $min, $max, $hbc, $hbp, $price, $total_qws ) {
		$is_passed_from_here_prd = array();
		if ( ( $min <= $total_qws ) && ( $total_qws <= $max ) ) {
			$is_passed_from_here_prd[ $hbc ][ $key ] = 'yes';
			$is_passed_from_here_prd[ $hbp ][ $key ] = $price;
		} else {
			$is_passed_from_here_prd[ $hbc ][ $key ] = 'no';
			$is_passed_from_here_prd[ $hbp ][ $key ] = $price;
		}
		return $is_passed_from_here_prd;
	}
	/**
	 * Cost for product per qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_product_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_product_per_qty__premium_only( $get_condition_array_ap_product, $woo_cart_array, $sitepress, $default_lang, $cost_on_product_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_prd = array();
			if ( ! empty( $get_condition_array_ap_product ) || '' !== $get_condition_array_ap_product ) {
				foreach ( $get_condition_array_ap_product as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_products'] ) || '' !== $get_condition['ap_fees_products'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_products'],
							$woo_cart_array,
							$sitepress,
							$default_lang,
							'product',
							'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_prd_min_qty'],
							$get_condition['ap_fees_ap_prd_max_qty'],
							$get_condition['ap_fees_ap_price_product'],
							'qty'
						);
						$is_passed_from_here_prd[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_cost_per_prd_qty',
							'has_fee_based_on_cost_per_prd_price',
							$get_condition['ap_fees_ap_price_product'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_prd,
				'has_fee_based_on_cost_per_prd_qty',
				'has_fee_based_on_cost_per_prd_price',
				$cost_on_product_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for category per qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_category_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  WC_Product::is_type()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_category_per_qty__premium_only( $get_condition_array_ap_category, $woo_cart_array, $sitepress, $default_lang, $cost_on_category_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_category ) || '' !== $get_condition_array_ap_category ) {
				foreach ( $get_condition_array_ap_category as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_categories'] ) || '' !== $get_condition['ap_fees_categories'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_categories'], $woo_cart_array, $sitepress, $default_lang, 'category', 'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_cat_min_qty'], $get_condition['ap_fees_ap_cat_max_qty'], $get_condition['ap_fees_ap_price_category'], 'qty'
						);
						$is_passed_from_here_cat[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_category',
							'has_fee_based_on_cost_per_cat_price',
							$get_condition['ap_fees_ap_price_category'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_cat, 'has_fee_based_on_per_category', 'has_fee_based_on_cost_per_cat_price', $cost_on_category_rule_match
			);
			return $main_is_passed;
		}
	}
    /**
	 * Cost for tag per qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_tag
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_tag_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  WC_Product::is_type()
	 *
	 */
    public function afrsm_shipping_advance_pricing_rules_tag_per_qty__premium_only( $get_condition_array_ap_tag, $woo_cart_array, $sitepress, $default_lang, $cost_on_tag_rule_match ){
        if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tag = array();
			if ( ! empty( $get_condition_array_ap_tag ) || '' !== $get_condition_array_ap_tag ) {
				foreach ( $get_condition_array_ap_tag as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_tags'] ) || '' !== $get_condition['ap_fees_tags'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_tags'], $woo_cart_array, $sitepress, $default_lang, 'tag', 'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_tag_min_qty'], $get_condition['ap_fees_ap_tag_max_qty'], $get_condition['ap_fees_ap_price_tag'], 'qty'
						);
						$is_passed_from_here_tag[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_tag',
							'has_fee_based_on_cost_per_tag_price',
							$get_condition['ap_fees_ap_price_tag'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_tag, 'has_fee_based_on_per_tag', 'has_fee_based_on_cost_per_tag_price', $cost_on_tag_rule_match
			);
			return $main_is_passed;
		}
    }
	/**
	 * Cost for total cart qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_qty
	 * @param array  $woo_cart_array
	 * @param string $cost_on_total_cart_qty_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_total_cart_qty__premium_only( $get_condition_array_ap_total_cart_qty, $woo_cart_array, $cost_on_total_cart_qty_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcq = array();
			if ( ! empty( $get_condition_array_ap_total_cart_qty ) || '' !== $get_condition_array_ap_total_cart_qty ) {
				foreach ( $get_condition_array_ap_total_cart_qty as $key => $get_condition ) {
					$total_qws                 = $this->afrsm_get_count_total_cart( $woo_cart_array, 'qty' );
					$get_min_max               = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_total_cart_qty_min_qty'], $get_condition['ap_fees_ap_total_cart_qty_max_qty'], $get_condition['ap_fees_ap_price_total_cart_qty'], 'qty'
					);
					$is_passed_from_here_tcq[] = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_tcq',
						'has_fee_based_on_tcq_price',
						$get_condition['ap_fees_ap_price_total_cart_qty'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_tcq, 'has_fee_based_on_tcq', 'has_fee_based_on_tcq_price', $cost_on_total_cart_qty_rule_match
			);
			return $main_is_passed;
		}
	}
    /**
	 * Cost for Shipping class quantity in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_shipping_class
	 * @param array  $woo_cart_array
	 * @param string $cost_on_shipping_class_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_shipping_class_per_qty__premium_only( $get_condition_array_ap_shipping_class, $woo_cart_array, $cost_on_shipping_class_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_shipping_class = array();
			if ( ! empty( $get_condition_array_ap_shipping_class ) || '' !== $get_condition_array_ap_shipping_class ) {
				foreach ( $get_condition_array_ap_shipping_class as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_shipping_classes'] ) || '' !== $get_condition['ap_fees_shipping_classes'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_shipping_classes'], $woo_cart_array, $sitepress, $default_lang, 'shipping_class', 'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_shipping_class_min_qty'], $get_condition['ap_fees_ap_shipping_class_max_qty'], $get_condition['ap_fees_ap_price_shipping_class'], 'qty'
						);
						$is_passed_from_here_shipping_class[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_shipping_class',
							'has_fee_based_on_cost_per_shipping_class_price',
							$get_condition['ap_fees_ap_price_shipping_class'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_shipping_class, 'has_fee_based_on_per_shipping_class', 'has_fee_based_on_cost_per_shipping_class_price', $cost_on_shipping_class_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for product per weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product_weight
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_product_weight_rule_match
	 *
	 * @return array $main_is_passed
	 *
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_product_per_weight__premium_only( $get_condition_array_ap_product_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_product_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_prd = array();
			if ( ! empty( $get_condition_array_ap_product_weight ) || '' !== $get_condition_array_ap_product_weight ) {
				foreach ( $get_condition_array_ap_product_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_product_weight'] ) || '' !== $get_condition['ap_fees_product_weight'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_product_weight'], $woo_cart_array, $sitepress, $default_lang, 'product', 'weight'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_product_weight_min_qty'], $get_condition['ap_fees_ap_product_weight_max_qty'], $get_condition['ap_fees_ap_price_product_weight'], 'weight'
						);
						$is_passed_from_here_prd[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_cost_ppw',
							'has_fee_based_on_cost_ppw_price',
							$get_condition['ap_fees_ap_price_product_weight'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_prd, 'has_fee_based_on_cost_ppw', 'has_fee_based_on_cost_ppw_price', $cost_on_product_weight_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for category per weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category_weight
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_category_weight_rule_match
	 *
	 * @return array $main_is_passed
	 *
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_category_per_weight__premium_only( $get_condition_array_ap_category_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_category_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_category_weight ) || '' !== $get_condition_array_ap_category_weight ) {
				foreach ( $get_condition_array_ap_category_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_categories_weight'] ) || '' !== $get_condition['ap_fees_categories_weight'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_categories_weight'], $woo_cart_array, $sitepress, $default_lang, 'category', 'weight'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_category_weight_min_qty'], $get_condition['ap_fees_ap_category_weight_max_qty'], $get_condition['ap_fees_ap_price_category_weight'], 'weight'
						);
						$is_passed_from_here_cat[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_cw',
							'has_fee_based_on_cost_per_cw',
							$get_condition['ap_fees_ap_price_category_weight'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_cat, 'has_fee_based_on_per_cw', 'has_fee_based_on_cost_per_cw', $cost_on_category_weight_rule_match
			);
			return $main_is_passed;
		}
	}
    /**
	 * Cost for tag per weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_tag_weight
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_category_weight_rule_match
	 *
	 * @return array $main_is_passed
	 *
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_tag_per_weight__premium_only( $get_condition_array_ap_tag_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_tag_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_tag_weight ) || '' !== $get_condition_array_ap_tag_weight ) {
				foreach ( $get_condition_array_ap_tag_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_tag_weight'] ) || '' !== $get_condition['ap_fees_tag_weight'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_tag_weight'], $woo_cart_array, $sitepress, $default_lang, 'tag', 'weight'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_tag_weight_min_qty'], $get_condition['ap_fees_ap_tag_weight_max_qty'], $get_condition['ap_fees_ap_price_tag_weight'], 'weight'
						);
						$is_passed_from_here_cat[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_cw',
							'has_fee_based_on_cost_per_cw',
							$get_condition['ap_fees_ap_price_tag_weight'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_cat, 'has_fee_based_on_per_cw', 'has_fee_based_on_cost_per_cw', $cost_on_tag_weight_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for total cart weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_weight
	 * @param array  $woo_cart_array
	 * @param string $cost_on_total_cart_weight_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_total_cart_weight( $get_condition_array_ap_total_cart_weight, $woo_cart_array, $cost_on_total_cart_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcw = array();
			if ( ! empty( $get_condition_array_ap_total_cart_weight ) || '' !== $get_condition_array_ap_total_cart_weight ) {
				foreach ( $get_condition_array_ap_total_cart_weight as $key => $get_condition ) {
					$total_qws                 = $this->afrsm_get_count_total_cart( $woo_cart_array, 'weight' );
					$get_min_max               = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_total_cart_weight_min_weight'], $get_condition['ap_fees_ap_total_cart_weight_max_weight'], $get_condition['ap_fees_ap_price_total_cart_weight'], 'weight'
					);
					$is_passed_from_here_tcw[] = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_tcw',
						'has_fee_based_on_tcw_price',
						$get_condition['ap_fees_ap_price_total_cart_weight'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_tcw, 'has_fee_based_on_tcw', 'has_fee_based_on_tcw_price', $cost_on_total_cart_weight_rule_match
			);
			return $main_is_passed;
		}
	}
    /**
	 * Cost for shipping class per weight in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_shipping_class_weight
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_category_weight_rule_match
	 *
	 * @return array $main_is_passed
	 *
	 * @since 4.2.0
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_shipping_class_weight__premium_only( $get_condition_array_ap_shipping_class_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_shipping_class_weight_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_shipping_class_weight ) || '' !== $get_condition_array_ap_shipping_class_weight ) {
				foreach ( $get_condition_array_ap_shipping_class_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_shipping_class_weight'] ) || '' !== $get_condition['ap_fees_shipping_class_weight'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_shipping_class_weight'], $woo_cart_array, $sitepress, $default_lang, 'shipping_class', 'weight'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_shipping_class_weight_min_weight'], $get_condition['ap_fees_ap_shipping_class_weight_max_weight'], $get_condition['ap_fees_ap_price_shipping_class_weight'], 'weight'
						);
						$is_passed_from_here_cat[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_per_scw',
							'has_fee_based_on_cost_per_scw',
							$get_condition['ap_fees_ap_price_shipping_class_weight'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_cat, 'has_fee_based_on_per_scw', 'has_fee_based_on_cost_per_scw', $cost_on_shipping_class_weight_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for total cart subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_subtotal
	 * @param array  $woo_cart_array
	 * @param string $cost_on_total_cart_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_total_cart_subtotal( $get_condition_array_ap_total_cart_subtotal, $woo_cart_array, $cost_on_total_cart_subtotal_rule_match ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcw = array();
			if ( ! empty( $get_condition_array_ap_total_cart_subtotal ) || '' !== $get_condition_array_ap_total_cart_subtotal ) {
				foreach ( $get_condition_array_ap_total_cart_subtotal as $key => $get_condition ) {
					$get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'] );
					$get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'] );
					$total_qws                                                    = $this->afrsm_get_count_total_cart( $woo_cart_array, 'subtotal' );
					$get_min_max                                                  = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'], $get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_total_cart_subtotal'], 'weight'
					);
					$is_passed_from_here_tcw[]                                    = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_tcs',
						'has_fee_based_on_tcs_price',
						$get_condition['ap_fees_ap_price_total_cart_subtotal'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_tcw, 'has_fee_based_on_tcs', 'has_fee_based_on_tcs_price', $cost_on_total_cart_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for Product subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product_subtotal
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_product_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_product_subtotal__premium_only( $get_condition_array_ap_product_subtotal, $woo_cart_array, $cost_on_product_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_ps = array();
			if ( ! empty( $get_condition_array_ap_product_subtotal ) || '' !== $get_condition_array_ap_product_subtotal ) {
				foreach ( $get_condition_array_ap_product_subtotal as $key => $get_condition ) {
					$get_condition['ap_fees_ap_product_subtotal_min_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_product_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_product_subtotal_max_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_product_subtotal_max_subtotal'] );
					$get_condition['ap_fees_ap_product_subtotal_min_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_product_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_product_subtotal_max_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_product_subtotal_max_subtotal'] );
					$total_qws                                                 = $this->afrsm_get_count_qty__premium_only(
						$get_condition['ap_fees_product_subtotal'], $woo_cart_array, $sitepress, $default_lang, 'product', 'subtotal'
					);
					$get_min_max                                               = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_product_subtotal_min_subtotal'], $get_condition['ap_fees_ap_product_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_product_subtotal'], 'subtotal'
					);
					$is_passed_from_here_ps[]                                  = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_ps',
						'has_fee_based_on_ps_price',
						$get_condition['ap_fees_ap_price_product_subtotal'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_ps, 'has_fee_based_on_ps', 'has_fee_based_on_ps_price', $cost_on_product_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for Category subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category_subtotal
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_category_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_category_subtotal__premium_only( $get_condition_array_ap_category_subtotal, $woo_cart_array, $cost_on_category_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cs = array();
			if ( ! empty( $get_condition_array_ap_category_subtotal ) || '' !== $get_condition_array_ap_category_subtotal ) {
				foreach ( $get_condition_array_ap_category_subtotal as $key => $get_condition ) {
					$get_condition['ap_fees_ap_category_subtotal_min_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_category_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_category_subtotal_max_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_category_subtotal_max_subtotal'] );
					$get_condition['ap_fees_ap_category_subtotal_min_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_category_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_category_subtotal_max_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_category_subtotal_max_subtotal'] );
					$total_qws                                                  = $this->afrsm_get_count_qty__premium_only(
						$get_condition['ap_fees_category_subtotal'], $woo_cart_array, $sitepress, $default_lang, 'category', 'subtotal'
					);
					$get_min_max                                                = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_category_subtotal_min_subtotal'], $get_condition['ap_fees_ap_category_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_category_subtotal'], 'subtotal'
					);
					$is_passed_from_here_cs[]                                   = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_cs',
						'has_fee_based_on_cs_price',
						$get_condition['ap_fees_ap_price_category_subtotal'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_cs, 'has_fee_based_on_cs', 'has_fee_based_on_cs_price', $cost_on_category_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
    /**
	 * Cost for Category subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_tag_subtotal
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_tag_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_tag_subtotal__premium_only( $get_condition_array_ap_tag_subtotal, $woo_cart_array, $cost_on_tag_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_ts = array();
			if ( ! empty( $get_condition_array_ap_tag_subtotal ) || '' !== $get_condition_array_ap_tag_subtotal ) {
				foreach ( $get_condition_array_ap_tag_subtotal as $key => $get_condition ) {
					$get_condition['ap_fees_ap_tag_subtotal_min_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_tag_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_tag_subtotal_max_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_tag_subtotal_max_subtotal'] );
					$get_condition['ap_fees_ap_tag_subtotal_min_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_tag_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_tag_subtotal_max_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_tag_subtotal_max_subtotal'] );
					$total_qws                                                  = $this->afrsm_get_count_qty__premium_only(
						$get_condition['ap_fees_tag_subtotal'], $woo_cart_array, $sitepress, $default_lang, 'tag', 'subtotal'
					);
					$get_min_max                                                = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_tag_subtotal_min_subtotal'], $get_condition['ap_fees_ap_tag_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_tag_subtotal'], 'subtotal'
					);
					$is_passed_from_here_ts[]                                   = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_ts',
						'has_fee_based_on_ts_price',
						$get_condition['ap_fees_ap_price_tag_subtotal'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_ts, 'has_fee_based_on_ts', 'has_fee_based_on_ts_price', $cost_on_tag_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}
	/**
	 * Cost for Shipping class subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_shipping_class_subtotal
	 * @param array  $woo_cart_array
	 * @param string $cost_on_shipping_class_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 * @uses  WC_Cart::get_cart_contents_total()
	 * @uses  wp_get_post_terms()
	 * @uses  wc_get_product()
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_shipping_class_subtotal__premium_only( $get_condition_array_ap_shipping_class_subtotal, $woo_cart_array, $cost_on_shipping_class_subtotal_rule_match, $sitepress, $default_lang ) {
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_scs = array();
			if ( ! empty( $get_condition_array_ap_shipping_class_subtotal ) || '' !== $get_condition_array_ap_shipping_class_subtotal ) {
				foreach ( $get_condition_array_ap_shipping_class_subtotal as $key => $get_condition ) {
					$get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'] );
					$get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'] );
					$get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'] = self::$admin_object->afrsm_woocs_convert_price( $get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'] );
					$total_qws                                                        = $this->afrsm_get_count_qty__premium_only($get_condition['ap_fees_shipping_class_subtotals'], $woo_cart_array, $sitepress, $default_lang, 'shipping_class', apply_filters('ap_shipping_class_default_behave', 'subtotal'));
					$get_min_max                                                      = $this->afrsm_check_min_max_qws(
						$get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'], $get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_shipping_class_subtotal'], 'subtotal'
					);
					$is_passed_from_here_scs[]                                        = $this->afrsm_check_passed_rule(
						$key,
						$get_min_max['min'],
						$get_min_max['max'],
						'has_fee_based_on_scs',
						'has_fee_based_on_scs_price',
						$get_condition['ap_fees_ap_price_shipping_class_subtotal'],
						$total_qws
					);
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_scs, 'has_fee_based_on_scs', 'has_fee_based_on_scs_price', $cost_on_shipping_class_subtotal_rule_match
			);
			return $main_is_passed;
		}
	}

    /**
	 * Cost for product attribute per qty in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_product_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_advance_pricing_rules_product_attribute_per_qty__premium_only( $get_condition_array_ap_product_attribute, $cart_array, $cost_on_product_attribute_rule_match, $sitepress, $default_lang ) {

		if ( ! empty( $cart_array ) ) {
			$is_passed_from_here_prd_attr = array();
			if ( ! empty( $get_condition_array_ap_product_attribute ) || '' !== $get_condition_array_ap_product_attribute ) {
				foreach ( $get_condition_array_ap_product_attribute as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_product_attributes'] ) || '' !== $get_condition['ap_fees_product_attributes'] ) {
						$total_qws                 = $this->afrsm_get_count_qty__premium_only(
							$get_condition['ap_fees_product_attributes'],
							$cart_array,
							$sitepress,
							$default_lang,
							'product_attribute',
							'qty'
						);
						$get_min_max               = $this->afrsm_check_min_max_qws(
							$get_condition['ap_fees_ap_product_attribute_min_qty'],
							$get_condition['ap_fees_ap_product_attribute_max_qty'],
							$get_condition['ap_fees_ap_price_product_attribute'],
							'qty'
						);
						$is_passed_from_here_prd_attr[] = $this->afrsm_check_passed_rule(
							$key,
							$get_min_max['min'],
							$get_min_max['max'],
							'has_fee_based_on_cost_per_prd_attr_qty',
							'has_fee_based_on_cost_per_prd_attr_price',
							$get_condition['ap_fees_ap_price_product_attribute'],
							$total_qws
						);
					}
				}
			}
			$main_is_passed = $this->afrsm_pro_check_all_passed_advance_rule(
				$is_passed_from_here_prd_attr,
				'has_fee_based_on_cost_per_prd_attr_qty',
				'has_fee_based_on_cost_per_prd_attr_price',
				$cost_on_product_attribute_rule_match
			);
			return $main_is_passed;
		}
	}

	/**
	 * Cost for cart subtotal before any discount
	 *
	 * @param array        $cart_total_array
	 * @param array        $value
	 * @param int          $key
	 * @param array|object $package
	 *
	 * @return array $before_discount_cost
	 * @uses  afrsm_shipping_get_package_item_qty()
	 *
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_cart_subtotal_before_discount_cost__premium_only( $cart_total_array, $value, $key, $package ) {
		global $woocommerce_wpml;
		$cart_total_array[ $key ] = $value;
		$total                    = WC()->cart->subtotal;
		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
		} else {
			$new_total = $total;
		}
		$before_discount_cost                    = array();
		$value['product_fees_conditions_values'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $value['product_fees_conditions_values'] );
		$value['product_fees_conditions_values'] = self::$admin_object->afrsm_woocs_convert_price( $value['product_fees_conditions_values'] );
		if ( 'is_equal_to' === $value['product_fees_conditions_is'] ||
		     'less_equal_to' === $value['product_fees_conditions_is'] ||
		     'less_then' === $value['product_fees_conditions_is'] ||
		     'greater_equal_to' === $value['product_fees_conditions_is'] ||
		     'greater_then' === $value['product_fees_conditions_is'] ||
		     'not_in' === $value['product_fees_conditions_is'] ) {
			if ( ! empty( $value['product_fees_conditions_values'] ) ) {
				if ( $value['product_fees_conditions_values'] === $new_total ||
				     $value['product_fees_conditions_values'] >= $new_total ||
				     $value['product_fees_conditions_values'] > $new_total ||
				     $value['product_fees_conditions_values'] <= $new_total ||
				     $value['product_fees_conditions_values'] < $new_total ||
				     $new_total === $value['product_fees_conditions_values'] ) {
					$cost_args = array(
						'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
						'cost' => $new_total,
					);
					return $before_discount_cost['before'] = $cost_args;
				}
			}
		}
	}
	/**
	 * Cost for cart subtotal after any discount
	 *
	 * @param array        $cart_totalafter_array
	 * @param array        $value
	 * @param int          $key
	 * @param array|object $package
	 *
	 * @return array $after_discount_cost
	 * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin class
	 * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin::afrsm_shipping_pro_remove_currency_symbol()
	 * @uses  afrsm_shipping_get_package_item_qty()
	 *
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_cart_subtotal_after_discount_cost__premium_only( $cart_totalafter_array, $value, $key, $package ) {
		global $woocommerce_wpml;
		$cart_totalafter_array[ $key ] = $value;
		$admin_object                  = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
		$totalprice                    = $admin_object->afrsm_pro_remove_currency_symbol( WC()->cart->get_cart_subtotal() );
		$totaldisc                     = $admin_object->afrsm_pro_remove_currency_symbol( WC()->cart->get_total_discount() );
		$after_discount_cost           = array();
		if ( '' !== $totaldisc || '0' !== $totaldisc ) {
			$resultprice = $totalprice - $totaldisc;
			if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
				$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
			} else {
				$new_resultprice = $resultprice;
			}
			$value['product_fees_conditions_values'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $value['product_fees_conditions_values'] );
			$value['product_fees_conditions_values'] = self::$admin_object->afrsm_woocs_convert_price( $value['product_fees_conditions_values'] );
			if ( 'is_equal_to' === $value['product_fees_conditions_is'] ||
			     'less_equal_to' === $value['product_fees_conditions_is'] ||
			     'less_then' === $value['product_fees_conditions_is'] ||
			     'greater_equal_to' === $value['product_fees_conditions_is'] ||
			     'greater_then' === $value['product_fees_conditions_is'] ||
			     'not_in' === $value['product_fees_conditions_is'] ) {
				if ( $value['product_fees_conditions_values'] === $new_resultprice ||
				     $value['product_fees_conditions_values'] >= $new_resultprice ||
				     $value['product_fees_conditions_values'] > $new_resultprice ||
				     $value['product_fees_conditions_values'] <= $new_resultprice ||
				     $value['product_fees_conditions_values'] < $new_resultprice ||
				     $new_resultprice === $value['product_fees_conditions_values'] ) {
					$cost_args = array(
						'qty'  => $this->afrsm_shipping_get_package_item_qty( $package ),
						'cost' => wp_unslash( $new_resultprice ),
					);
					return $after_discount_cost['after'] = $cost_args;
				}
			}
		}
	}
	/**
	 * Cost for cart subtotal on specific product
	 *
	 * @param array        $cart_productspecific_array
	 * @param array        $value
	 * @param int          $key
	 * @param array|object $package
	 *
	 * @return array $product_specific_cost
	 * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin class
	 * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin::afrsm_shipping_pro_remove_currency_symbol()
	 * @uses  afrsm_shipping_get_package_item_qty()
	 *
	 * @since 3.4
	 *
	 */
	public function afrsm_shipping_cart_subtotal_cart_productspecific_cost__premium_only( $cart_productspecific_array, $value, $key, $package, $get_condition_array ) {
		global $woocommerce_wpml;
		$totalprice = 0;
		$totalqty = 0;
		$new_resultprice = 0;
		$cart_productspecific_array[ $key ] = $value;
		// Loop over $cart items
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			foreach($get_condition_array as $pvalue){
				$product_id =  !empty($cart_item['variation_id']) &&  (0 !== $cart_item['variation_id']) ? intval($cart_item['variation_id']) : intval($cart_item['product_id']);
				$parent_product_id = intval($cart_item['product_id']);
				$product = wc_get_product( $parent_product_id );
				$qty = $cart_item['quantity'];

				if( array_search( 'product', $pvalue, true ) || array_search( 'variableproduct', $pvalue, true ) ){
                    array_map( 'intval', $pvalue['product_fees_conditions_values'] );
					if( in_array( $product_id, $pvalue['product_fees_conditions_values'], true ) ){		
						$product_subtotal = self::$admin_object->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
						$totalprice += $product_subtotal;
					}
				} else if( array_search( 'sku', $pvalue, true ) ) {
					$product_sku = $product->get_sku();
					if( in_array( $product_sku, $pvalue['product_fees_conditions_values'], true ) ){		
						$product_subtotal = self::$admin_object->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
						$totalprice += $product_subtotal;
					}
				} else if( array_search( 'category', $pvalue, true ) ){
					$category_list = $product->get_category_ids();
					$common_category = ( is_array($category_list) && !empty($category_list) ) ? array_intersect($category_list, $pvalue['product_fees_conditions_values']) : array();
					if( is_array($common_category) && !empty($common_category) ){
						$product_subtotal = self::$admin_object->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
						$totalprice += $product_subtotal;
					}
				} else if( array_search( 'tag', $pvalue, true ) ){
					$tag_list = $product->get_tag_ids();
					$common_tag = ( is_array($tag_list) && !empty($tag_list) ) ? array_intersect($tag_list, $pvalue['product_fees_conditions_values']) : array();
					if( is_array($common_tag) && !empty($common_tag) ){
						$product_subtotal = self::$admin_object->afrsm_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $qty ) );
						$totalprice += $product_subtotal;
					}
				}
			}
		}
		
		$product_specific_cost           = array();
		if ( '' !== $totalprice || '0' < $totalprice ) {
			if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
				$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $totalprice );
			} else {
				$new_resultprice = $totalprice;
			}
			$value['product_fees_conditions_values'] = self::$admin_object->afrsm_pro_price_based_on_switcher( $value['product_fees_conditions_values'] );
			$value['product_fees_conditions_values'] = self::$admin_object->afrsm_woocs_convert_price( $value['product_fees_conditions_values'] );
			if ( 'is_equal_to' === $value['product_fees_conditions_is'] ||
			     'less_equal_to' === $value['product_fees_conditions_is'] ||
			     'less_then' === $value['product_fees_conditions_is'] ||
			     'greater_equal_to' === $value['product_fees_conditions_is'] ||
			     'greater_then' === $value['product_fees_conditions_is'] ||
			     'not_in' === $value['product_fees_conditions_is'] ) {
				if ( $value['product_fees_conditions_values'] === $new_resultprice ||
				     $value['product_fees_conditions_values'] >= $new_resultprice ||
				     $value['product_fees_conditions_values'] > $new_resultprice ||
				     $value['product_fees_conditions_values'] <= $new_resultprice ||
				     $value['product_fees_conditions_values'] < $new_resultprice ||
				     $new_resultprice === $value['product_fees_conditions_values'] ) {
					$cost_args = array(
						'qty'  => $totalqty,
						'cost' => wp_unslash( $new_resultprice ),
					);
					return $product_specific_cost['productspecific'] = $cost_args;
				}
			}
		}
	}
	/**
	 * Match methods.
	 *
	 * Check all created AFRSM shipping methods have a matching condition group.
	 *
	 * @param array|object $package List of shipping package data.
	 * @param string       $sitepress
	 * @param string       $default_lang
	 *
	 * @return array $matched_methods   List of all matched shipping methods.
	 *
	 * @uses  afrsm_shipping_match_conditions()
	 *
	 * @since 3.0.0
	 *
	 * @uses  get_posts()
	 */
	public function afrsm_shipping_match_methods( $package, $sitepress, $default_lang ) {

		$matched_methods  = array();
		$sm_args          = array(
			'post_type'        	=> 'wc_afrsm',
			'posts_per_page'   	=> - 1,
			'orderby'          	=> 'menu_order',
			'order'            	=> 'ASC',
			'suppress_filters' 	=> false,
			'post_status'	   	=> 'publish',
			'fields'		   	=> 'ids'
		);
		$get_all_shipping = new WP_Query( $sm_args );
		if ( !empty($get_all_shipping->posts) ) {
            foreach ( $get_all_shipping->posts as $sid ) {
				if ( ! empty( $sitepress ) ) {
					$sm_post_id = apply_filters( 'wpml_object_id', $sid, 'wc_afrsm', true, $default_lang );
				} else {
					$sm_post_id = $sid;
				}
				if ( ! empty( $sitepress ) ) {
					if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
						$language_information = apply_filters( 'wpml_post_language_details', null, $sm_post_id );
					} else {
						$language_information = wpml_get_language_information( $sm_post_id );
					}
					$post_id_language_code = $language_information['language_code'];
				} else {
					$post_id_language_code = self::$admin_object->afrsm_pro_get_default_langugae_with_sitpress();
				}
				if ( $post_id_language_code === $default_lang ) {
					$is_match = $this->afrsm_shipping_match_conditions( $sm_post_id, $package );
					// Add to matched methods array
					if ( true === $is_match ) {
						$matched_methods[] = $sm_post_id;
					}
				}
			}
		}
		// reset custom query
		wp_reset_query();
		update_option( 'matched_method', $matched_methods );
		return $matched_methods;
	}
	
	/**
	 * Match conditions.
	 *
	 * Check if conditions match, if all conditions in one condition group
	 * matches it will return TRUE and the shipping method will display.
	 *
	 * @param array $sm_post_data
	 * @param array $package List of shipping package data.
	 *
	 * @return BOOL TRUE if all the conditions in one of the condition groups matches true.
	 * @since 1.0.0
	 *
	 */
	public function afrsm_shipping_match_conditions( $sm_post_data, $package = array() ) {

		if ( empty( $sm_post_data ) ) {
			return false;
		}

		if ( ! empty( $sm_post_data ) ) {
			$final_condition_flag = apply_filters( 'afrsm_condition_match_rules', $sm_post_data, $package );	
			if ( $final_condition_flag ) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Get items in package.
	 *
	 * @param array|object $package
	 *
	 * @return int $total_quantity
	 * @since 1.0.0
	 *
	 */
	public function afrsm_shipping_get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}
	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param string $shipping_cost_sum
	 * @param array  $args
	 *
	 * @return string $shipping_cost_sum if shipping cost is empty then it will return 0
	 * @since 1.0.0
	 *
	 * @uses  wc_get_price_decimal_separator()
	 * @uses  WC_Eval_Math_Extra::evaluate()
	 *
	 */
	protected function afrsm_shipping_evaluate_cost( $shipping_cost_sum, $args = array() ) {
		include_once( plugin_dir_path( __FILE__ ) . 'class-wc-extra-flat-eval-math.php' );
		// Allow 3rd parties to process shipping cost arguments
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $shipping_cost_sum, $this );
		$locale         = localeconv();
		$decimals       = array(
			wc_get_price_decimal_separator(),
			$locale['decimal_point'],
			$locale['mon_decimal_point'],
		);
		$this->fee_cost = $args['cost'];
		// Expand shortcodes
		add_shortcode( 'fee', array( $this, 'fee' ) );
		$shipping_cost_sum = do_shortcode( str_replace( array( '[qty]', '[cost]' ), array(
			$args['qty'],
			$args['cost'],
		), $shipping_cost_sum ) );
		remove_shortcode( 'fee', array( $this, 'fee' ) );
		// Remove whitespace from string
		$shipping_cost_sum = preg_replace( '/\s+/', '', $shipping_cost_sum );
		// Remove locale from string
		$shipping_cost_sum = str_replace( $decimals, '.', $shipping_cost_sum );
		// Trim invalid start/end characters
		$shipping_cost_sum = rtrim( ltrim( $shipping_cost_sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
		// Do the math
		return $shipping_cost_sum ? WC_Eval_Math_Extra::evaluate( $shipping_cost_sum ) : 0;
	}
	/**
	 * Finds and returns shipping classes and the products with said class.
	 *
	 * @param array|object $package
	 *
	 * @return array $found_shipping_classes
	 * @since 1.0.0
	 *
	 */
	public function afrsm_shipping_find_shipping_classes( $package ) {
		$found_shipping_classes = array();
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();
				if ( ! empty( $found_class ) ) {
					if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
						$found_shipping_classes[ $found_class ] = array();
					}
					$found_shipping_classes[ $found_class ][ $item_id ] = $values;
				}
			}
		}
		return $found_shipping_classes;
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
	 * @uses   trigger_error()
	 *
	 */
	public function afrsm_shipping_fee_array_column( array $input, $columnKey, $indexKey = null ) {
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
	 * Work out fee ( shortcode ).
	 *
	 * @param array $atts
	 *
	 * @return string $calculated_fee
	 * @since 1.0.0
	 *
	 * @uses  afrsm_shipping_string_sanitize
	 *
	 */
	public function fee( $atts ) {
		$atts            = shortcode_atts( array( 'percent' => '', 'min_fee' => '', 'max_fee' => '' ), $atts );
		$atts['percent'] = $this->afrsm_shipping_string_sanitize( $atts['percent'] );
		$atts['min_fee'] = $this->afrsm_shipping_string_sanitize( $atts['min_fee'] );
		$atts['max_fee'] = $this->afrsm_shipping_string_sanitize( $atts['max_fee'] );
		$calculated_fee  = 0;
		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}
		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}
		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}
		return $calculated_fee;
	}
	/**
	 * Sanitize string
	 *
	 * @param mixed $string
	 *
	 * @return string $result
	 * @since 1.0.0
	 *
	 */
	public function afrsm_shipping_string_sanitize( $string ) {
		$result = preg_replace( "/[^ A-Za-z0-9_=.*()+\-\[\]\/]+/", '', html_entity_decode( $string, ENT_QUOTES ) );
		return $result;
	}
	/**
	 * Price format
	 *
	 * @param string $price
	 *
	 * @return string $price
	 * @since  1.3.3
	 *
	 */
	public function afrsm_price_format( $price ) {
		$price = floatval( $price );
		return $price;
	}
    /**
	 * Price format
	 *
	 * @param array $exlude_products
	 *
	 * @return string $exlude_product_subtotal
	 * @since  4.1.3
	 *
	 */
	public function afrsm_cart_exclude_product_subtotal( $exlude_products ) {
		
        $exlude_product_subtotal = 0;
        $exlude_products = array_map( 'intval', $exlude_products );

        if( !empty($exlude_products) ) {
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $product_id = $cart_item[ 'variation_id' ] ? $cart_item[ 'variation_id' ] : $cart_item[ 'product_id' ];
                if( in_array( $product_id, $exlude_products, true ) ) {
                    if( !empty( $cart_item['line_subtotal'] ) ){
                        $exlude_product_subtotal += $cart_item['line_subtotal'];
                    }
                }
            }
        }

        return $exlude_product_subtotal;
	}
}
