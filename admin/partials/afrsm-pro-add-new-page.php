<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
$afrsm_admin_object     = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
$afrsm_object           = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro( '', '' );
$get_action             = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
$allowed_tooltip_html   = wp_kses_allowed_html( 'post' )['span'];
/*
 * edit all posted data method define in class-advanced-flat-rate-shipping-for-woocommerce-admin
 */
if ( isset( $get_action ) && 'edit' === $get_action ) {

	$get_id          		= filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

	$get_wpnonce         	= filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$get_retrieved_nonce 	= isset( $get_wpnonce ) ? sanitize_text_field( wp_unslash( $get_wpnonce ) ) : '';

	$get_duplicate_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$get_duplicate_nonce 	= isset( $get_duplicate_wpnonce ) ? sanitize_text_field( wp_unslash( $get_duplicate_wpnonce ) ) : '';

	if ( ! wp_verify_nonce( $get_retrieved_nonce, 'edit_' . $get_id ) && ! wp_verify_nonce( $get_duplicate_nonce, 'edit_' . $get_id ) ) {
		die( 'Failed security check' );
	}
	$get_post_id     = isset( $get_id ) ? sanitize_text_field( wp_unslash( $get_id ) ) : '';
	$sm_status       = get_post_status( $get_post_id );
	$sm_title        = __( get_the_title( $get_post_id ), 'advanced-flat-rate-shipping-for-woocommerce' );
	$sm_cost         = get_post_meta( $get_post_id, 'sm_product_cost', true );
	$is_allow_free_shipping    = get_post_meta( $get_post_id, 'is_allow_free_shipping', true );
	$sm_free_shipping_based_on = get_post_meta( $get_post_id, 'sm_free_shipping_based_on', true );
	$sm_free_shipping_cost  = get_post_meta( $get_post_id, 'sm_free_shipping_cost', true );
	$is_free_shipping_exclude_prod  = get_post_meta( $get_post_id, 'is_free_shipping_exclude_prod', true );
	$sm_free_shipping_coupan_cost = get_post_meta( $get_post_id, 'sm_free_shipping_coupan_cost', true );
	$sm_free_shipping_label = get_post_meta( $get_post_id, 'sm_free_shipping_label', true );
	$sm_tooltip_type = get_post_meta( $get_post_id, 'sm_tooltip_type', true );
	$sm_tooltip_desc = get_post_meta( $get_post_id, 'sm_tooltip_desc', true );
	$sm_is_log_in_user = get_post_meta( $get_post_id, 'sm_select_log_in_user', true );
    $sm_first_order_for_user = get_post_meta( $get_post_id, 'sm_select_first_order_for_user', true );
	$sm_is_selected_shipping = get_post_meta( $get_post_id, 'sm_select_selected_shipping', true );
	$sm_select_shipping_provider = get_post_meta( $get_post_id, 'sm_select_shipping_provider', true );

	$sm_is_taxable   = get_post_meta( $get_post_id, 'sm_select_taxable', true );
	$sm_metabox      = get_post_meta( $get_post_id, 'sm_metabox', true );
	if ( is_serialized( $sm_metabox ) ) {
		$sm_metabox = maybe_unserialize( $sm_metabox );
	} else {
		$sm_metabox = $sm_metabox;
	}
	$sm_extra_cost = get_post_meta( $get_post_id, 'sm_extra_cost', true );
	if ( is_serialized( $sm_extra_cost ) ) {
		$sm_extra_cost = maybe_unserialize( $sm_extra_cost );
	} else {
		$sm_extra_cost = $sm_extra_cost;
	}
	$sm_extra_cost_calc_type = get_post_meta( $get_post_id, 'sm_extra_cost_calculation_type', true );
	$ap_rule_status  = get_post_meta( $get_post_id, 'ap_rule_status', true );

	$cost_on_total_cart_weight_status       = get_post_meta( $get_post_id, 'cost_on_total_cart_weight_status', true );
	$cost_on_total_cart_subtotal_status     = get_post_meta( $get_post_id, 'cost_on_total_cart_subtotal_status', true );
	$cost_rule_match = get_post_meta( $get_post_id, 'cost_rule_match', true );
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
		$general_rule_match                         = 'all';
		$cost_on_total_cart_weight_rule_match       = 'any';
		$cost_on_total_cart_subtotal_rule_match     = 'any';
	}
	$sm_metabox_ap_total_cart_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_weight', true );
	if ( is_serialized( $sm_metabox_ap_total_cart_weight ) ) {
		$sm_metabox_ap_total_cart_weight = maybe_unserialize( $sm_metabox_ap_total_cart_weight );
	} else {
		$sm_metabox_ap_total_cart_weight = $sm_metabox_ap_total_cart_weight;
	}
	$sm_metabox_ap_total_cart_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_subtotal', true );
	if ( is_serialized( $sm_metabox_ap_total_cart_subtotal ) ) {
		$sm_metabox_ap_total_cart_subtotal = maybe_unserialize( $sm_metabox_ap_total_cart_subtotal );
	} else {
		$sm_metabox_ap_total_cart_subtotal = $sm_metabox_ap_total_cart_subtotal;
	}
	if ( afrsfw_fs()->is__premium_only() ) {
		if ( afrsfw_fs()->can_use_premium_code() ) {
			
			$fee_settings_unique_shipping_title = get_post_meta( $get_post_id, 'fee_settings_unique_shipping_title', true );
			$getFeesPerQtyFlag                  = get_post_meta( $get_post_id, 'sm_fee_chk_qty_price', true );
			$getFeesPerQty                      = get_post_meta( $get_post_id, 'sm_fee_per_qty', true );
			$extraProductCost                   = get_post_meta( $get_post_id, 'sm_extra_product_cost', true );
			$sm_estimation_delivery             = get_post_meta( $get_post_id, 'sm_estimation_delivery', true );
			$sm_start_date                      = get_post_meta( $get_post_id, 'sm_start_date', true );
			$sm_end_date                        = get_post_meta( $get_post_id, 'sm_end_date', true );
			$sm_time_from                       = get_post_meta( $get_post_id, 'sm_time_from', true );
			$sm_time_to                         = get_post_meta( $get_post_id, 'sm_time_to', true );
			$sm_select_day_of_week              = get_post_meta( $get_post_id, 'sm_select_day_of_week', true );
			$sm_free_shipping_based_on_product  = get_post_meta( $get_post_id, 'sm_free_shipping_based_on_product', true );
			$sm_free_shipping_exclude_product   = get_post_meta( $get_post_id, 'sm_free_shipping_exclude_product', true );
			if ( is_serialized( $sm_select_day_of_week ) ) {
				$sm_select_day_of_week = maybe_unserialize( $sm_select_day_of_week );
			} else {
				$sm_select_day_of_week = $sm_select_day_of_week;
			}
			if ( is_serialized( $sm_free_shipping_based_on_product ) ) {
				$sm_free_shipping_based_on_product = maybe_unserialize( $sm_free_shipping_based_on_product );
			} else {
				$sm_free_shipping_based_on_product = $sm_free_shipping_based_on_product;
			}
            if ( is_serialized( $sm_free_shipping_exclude_product ) ) {
				$sm_free_shipping_exclude_product = maybe_unserialize( $sm_free_shipping_exclude_product );
			} else {
				$sm_free_shipping_exclude_product = $sm_free_shipping_exclude_product;
			}
			/*Advance rule status*/
			$cost_on_product_status                 = get_post_meta( $get_post_id, 'cost_on_product_status', true );
			$cost_on_product_weight_status          = get_post_meta( $get_post_id, 'cost_on_product_weight_status', true );
			$cost_on_product_subtotal_status        = get_post_meta( $get_post_id, 'cost_on_product_subtotal_status', true );
			$cost_on_category_status                = get_post_meta( $get_post_id, 'cost_on_category_status', true );
			$cost_on_category_weight_status         = get_post_meta( $get_post_id, 'cost_on_category_weight_status', true );
			$cost_on_category_subtotal_status       = get_post_meta( $get_post_id, 'cost_on_category_subtotal_status', true );
			$cost_on_tag_status                     = get_post_meta( $get_post_id, 'cost_on_tag_status', true );
            $cost_on_tag_subtotal_status            = get_post_meta( $get_post_id, 'cost_on_tag_subtotal_status', true );
            $cost_on_tag_weight_status              = get_post_meta( $get_post_id, 'cost_on_tag_weight_status', true );
			$cost_on_total_cart_qty_status          = get_post_meta( $get_post_id, 'cost_on_total_cart_qty_status', true );
            $cost_on_shipping_class_status          = get_post_meta( $get_post_id, 'cost_on_shipping_class_status', true );
			$cost_on_shipping_class_weight_status   = get_post_meta( $get_post_id, 'cost_on_shipping_class_weight_status', true );
			$cost_on_shipping_class_subtotal_status = get_post_meta( $get_post_id, 'cost_on_shipping_class_subtotal_status', true );
			$cost_on_product_attribute_status       = get_post_meta( $get_post_id, 'cost_on_product_attribute_status', true );
			/*Advance rule status*/
			//APM variable initialize on edit action
			$sm_free_shipping_cost_before_discount  = get_post_meta( $get_post_id, 'sm_free_shipping_cost_before_discount', true );
			$sm_free_shipping_cost_left_notice      = get_post_meta( $get_post_id, 'sm_free_shipping_cost_left_notice', true );
			$sm_free_shipping_cost_left_notice_msg      = get_post_meta( $get_post_id, 'sm_free_shipping_cost_left_notice_msg', true );

			$is_allow_custom_weight_base  = get_post_meta( $get_post_id, 'is_allow_custom_weight_base', true );
			$sm_custom_weight_base_cost  = get_post_meta( $get_post_id, 'sm_custom_weight_base_cost', true );
			$sm_custom_weight_base_per_each  = get_post_meta( $get_post_id, 'sm_custom_weight_base_per_each', true );
			$sm_custom_weight_base_over  = get_post_meta( $get_post_id, 'sm_custom_weight_base_over', true );
			$is_allow_custom_qty_base  = get_post_meta( $get_post_id, 'is_allow_custom_qty_base', true );
			$sm_custom_qty_base_cost  = get_post_meta( $get_post_id, 'sm_custom_qty_base_cost', true );
			$sm_custom_qty_base_per_each  = get_post_meta( $get_post_id, 'sm_custom_qty_base_per_each', true );
			$sm_custom_qty_base_over  = get_post_meta( $get_post_id, 'sm_custom_qty_base_over', true );

			$sm_metabox_ap_product = get_post_meta( $get_post_id, 'sm_metabox_ap_product', true );
			if ( is_serialized( $sm_metabox_ap_product ) ) {
				$sm_metabox_ap_product = maybe_unserialize( $sm_metabox_ap_product );
			} else {
				$sm_metabox_ap_product = $sm_metabox_ap_product;
			}

			$sm_metabox_ap_product_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_product_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_product_subtotal ) ) {
				$sm_metabox_ap_product_subtotal = maybe_unserialize( $sm_metabox_ap_product_subtotal );
			} else {
				$sm_metabox_ap_product_subtotal = $sm_metabox_ap_product_subtotal;
			}

			$sm_metabox_ap_product_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_product_weight', true );
			if ( is_serialized( $sm_metabox_ap_product_weight ) ) {
				$sm_metabox_ap_product_weight = maybe_unserialize( $sm_metabox_ap_product_weight );
			} else {
				$sm_metabox_ap_product_weight = $sm_metabox_ap_product_weight;
			}

			$sm_metabox_ap_category = get_post_meta( $get_post_id, 'sm_metabox_ap_category', true );
			if ( is_serialized( $sm_metabox_ap_category ) ) {
				$sm_metabox_ap_category = maybe_unserialize( $sm_metabox_ap_category );
			} else {
				$sm_metabox_ap_category = $sm_metabox_ap_category;
			}

			$sm_metabox_ap_category_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_category_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_category_subtotal ) ) {
				$sm_metabox_ap_category_subtotal = maybe_unserialize( $sm_metabox_ap_category_subtotal );
			} else {
				$sm_metabox_ap_category_subtotal = $sm_metabox_ap_category_subtotal;
			}

			$sm_metabox_ap_category_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_category_weight', true );
			if ( is_serialized( $sm_metabox_ap_category_weight ) ) {
				$sm_metabox_ap_category_weight = maybe_unserialize( $sm_metabox_ap_category_weight );
			} else {
				$sm_metabox_ap_category_weight = $sm_metabox_ap_category_weight;
			}

            $sm_metabox_ap_tag = get_post_meta( $get_post_id, 'sm_metabox_ap_tag', true );
			if ( is_serialized( $sm_metabox_ap_tag ) ) {
				$sm_metabox_ap_tag = maybe_unserialize( $sm_metabox_ap_tag );
			} else {
				$sm_metabox_ap_tag = $sm_metabox_ap_tag;
			}

            $sm_metabox_ap_tag_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_tag_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_tag_subtotal ) ) {
				$sm_metabox_ap_tag_subtotal = maybe_unserialize( $sm_metabox_ap_tag_subtotal );
			} else {
				$sm_metabox_ap_tag_subtotal = $sm_metabox_ap_tag_subtotal;
			}

			$sm_metabox_ap_tag_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_tag_weight', true );
			if ( is_serialized( $sm_metabox_ap_tag_weight ) ) {
				$sm_metabox_ap_tag_weight = maybe_unserialize( $sm_metabox_ap_tag_weight );
			} else {
				$sm_metabox_ap_tag_weight = $sm_metabox_ap_tag_weight;
			}

			$sm_metabox_ap_total_cart_qty = get_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_qty', true );
			if ( is_serialized( $sm_metabox_ap_total_cart_qty ) ) {
				$sm_metabox_ap_total_cart_qty = maybe_unserialize( $sm_metabox_ap_total_cart_qty );
			} else {
				$sm_metabox_ap_total_cart_qty = $sm_metabox_ap_total_cart_qty;
			}
			
            $sm_metabox_ap_shipping_class = get_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class', true );
			if ( is_serialized( $sm_metabox_ap_shipping_class ) ) {
				$sm_metabox_ap_shipping_class = maybe_unserialize( $sm_metabox_ap_shipping_class );
			} else {
				$sm_metabox_ap_shipping_class = $sm_metabox_ap_shipping_class;
			}

            $sm_metabox_ap_shipping_class_weight = get_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_weight', true );
			if ( is_serialized( $sm_metabox_ap_shipping_class_weight ) ) {
				$sm_metabox_ap_shipping_class_weight = maybe_unserialize( $sm_metabox_ap_shipping_class_weight );
			} else {
				$sm_metabox_ap_shipping_class_weight = $sm_metabox_ap_shipping_class_weight;
			}

			$sm_metabox_ap_shipping_class_subtotal = get_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_shipping_class_subtotal ) ) {
				$sm_metabox_ap_shipping_class_subtotal = maybe_unserialize( $sm_metabox_ap_shipping_class_subtotal );
			} else {
				$sm_metabox_ap_shipping_class_subtotal = $sm_metabox_ap_shipping_class_subtotal;
			}

            $sm_metabox_ap_product_attribute = get_post_meta( $get_post_id, 'sm_metabox_ap_product_attribute', true );
			if ( is_serialized( $sm_metabox_ap_product_attribute ) ) {
				$sm_metabox_ap_product_attribute = maybe_unserialize( $sm_metabox_ap_product_attribute );
			} else {
				$sm_metabox_ap_product_attribute = $sm_metabox_ap_product_attribute;
			}
			
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
				$general_rule_match                         = 'all';
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
				$cost_on_shipping_class_rule_match          = 'any';
                $cost_on_shipping_class_weight_rule_match   = 'any';
				$cost_on_shipping_class_subtotal_rule_match = 'any';
				$cost_on_product_attribute_rule_match       = 'any';
			}
		}
	}
} else {
	$get_post_id             = '';
	$sm_status               = '';
	$sm_title                = '';
	$sm_cost                 = '';
	$sm_free_shipping_based_on = '';
	$is_allow_free_shipping  = ''; 
	$sm_free_shipping_cost   = '';
    $is_free_shipping_exclude_prod = '';
	$sm_free_shipping_coupan_cost = '';
	$sm_free_shipping_label  = '';
	$sm_tooltip_type         = '';
	$sm_tooltip_desc         = '';
	$sm_is_log_in_user       = '';
    $sm_first_order_for_user = 'no';
	$sm_is_selected_shipping = '';
	$sm_is_taxable           = '';
	$sm_select_shipping_provider = '';
	$sm_metabox              = array();
	$sm_extra_cost           = array();
	$sm_extra_cost_calc_type = '';
	$ap_rule_status          = '';
	$general_rule_match      = 'all';
	$cost_on_total_cart_weight_status   = '';
	$cost_on_total_cart_subtotal_status = '';
	$cost_on_total_cart_weight_rule_match   = 'any';
	$cost_on_total_cart_subtotal_rule_match = 'any';
	$sm_metabox_ap_total_cart_weight            = array();
	$sm_metabox_ap_total_cart_subtotal          = array();
	if ( afrsfw_fs()->is__premium_only() ) {
		if ( afrsfw_fs()->can_use_premium_code() ) {
			$fee_settings_unique_shipping_title         = '';
			$getFeesPerQtyFlag                          = '';
			$getFeesPerQty                              = '';
			$extraProductCost                           = '';
			$sm_estimation_delivery                     = '';
			$sm_start_date                              = '';
			$sm_end_date                                = '';
			$cost_on_product_status                     = '';
			$cost_on_category_status                    = '';
			$cost_on_tag_status                         = '';
			$cost_on_total_cart_qty_status              = '';
			$cost_on_product_weight_status              = '';
			$cost_on_category_weight_status             = '';
            $cost_on_tag_weight_status                  = '';
            $cost_on_shipping_class_status              = '';
            $cost_on_shipping_class_weight_status       = '';
			$cost_on_shipping_class_subtotal_status     = '';
			$cost_on_product_attribute_status           = '';
			$sm_free_shipping_cost_before_discount      = '';
			$sm_free_shipping_cost_left_notice 			= '';
			$sm_free_shipping_cost_left_notice_msg		= '';
			$is_allow_custom_weight_base 				= "";
			$sm_custom_weight_base_cost 				= "";
			$sm_custom_weight_base_per_each 			= "";
			$sm_custom_weight_base_over 				= "";
			$is_allow_custom_qty_base 					= "";
			$sm_custom_qty_base_cost 					= "";
			$sm_custom_qty_base_per_each 				= "";
			$sm_custom_qty_base_over 					= "";
			$sm_metabox_ap_product                      = array();
			$sm_metabox_ap_category                     = array();
			$sm_metabox_ap_tag                          = array();
			$sm_metabox_ap_total_cart_qty               = array();
			$sm_metabox_ap_product_weight               = array();
			$sm_metabox_ap_category_weight              = array();
            $sm_metabox_ap_tag_weight                   = array();
            $sm_metabox_ap_shipping_class               = array();
            $sm_metabox_ap_shipping_class_weight        = array();
			$sm_metabox_ap_shipping_class_subtotal      = array();
            $sm_metabox_ap_product_attribute            = array();
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
            $cost_on_shipping_class_rule_match          = 'any';
            $cost_on_shipping_class_weight_rule_match   = 'any';
			$cost_on_shipping_class_subtotal_rule_match = 'any';
            $cost_on_product_attribute_rule_match       = 'any';
		}
	}
}
$sm_status       = ( ( ! empty( $sm_status ) && 'publish' === $sm_status ) || empty( $sm_status ) ) ? 'checked' : '';
$sm_title        = ! empty( $sm_title ) ? esc_attr( stripslashes( $sm_title ) ) : '';
$sm_cost         = ( '' !== $sm_cost ) ? esc_attr( stripslashes( $sm_cost ) ) : '';
$sm_free_shipping_based_on  = ( '' !== $sm_free_shipping_based_on ) ? esc_attr( stripslashes( $sm_free_shipping_based_on ) ) : '';
$is_allow_free_shipping  = ( '' !== $is_allow_free_shipping ) ? esc_attr( stripslashes( $is_allow_free_shipping ) ) : '';
$sm_free_shipping_label = ( '' !== $sm_free_shipping_label ) ? esc_attr( stripslashes( $sm_free_shipping_label ) ) : '';
$sm_tooltip_type = ! empty( $sm_tooltip_type ) ? $sm_tooltip_type : '';
$sm_tooltip_desc = ! empty( $sm_tooltip_desc ) ? $sm_tooltip_desc : '';
$ap_rule_status  = ( ! empty( $ap_rule_status ) && 'on' === $ap_rule_status && "" !== $ap_rule_status ) ? 'checked' : '';
$cost_on_total_cart_weight_status       = ( ! empty( $cost_on_total_cart_weight_status ) && 'on' === $cost_on_total_cart_weight_status && "" !== $cost_on_total_cart_weight_status ) ? 'checked' : '';
$cost_on_total_cart_subtotal_status     = ( ! empty( $cost_on_total_cart_subtotal_status ) && 'on' === $cost_on_total_cart_subtotal_status && "" !== $cost_on_total_cart_subtotal_status ) ? 'checked' : '';
if ( afrsfw_fs()->is__premium_only() ) {
	if ( afrsfw_fs()->can_use_premium_code() ) {
		$cost_on_product_status                 = ( ! empty( $cost_on_product_status ) && 'on' === $cost_on_product_status && "" !== $cost_on_product_status ) ? 'checked' : '';
		$cost_on_product_weight_status          = ( ! empty( $cost_on_product_weight_status ) && 'on' === $cost_on_product_weight_status && "" !== $cost_on_product_weight_status ) ? 'checked' : '';
		$cost_on_product_subtotal_status        = ( ! empty( $cost_on_product_subtotal_status ) && 'on' === $cost_on_product_subtotal_status && "" !== $cost_on_product_subtotal_status ) ? 'checked' : '';
		$cost_on_category_status                = ( ! empty( $cost_on_category_status ) && 'on' === $cost_on_category_status && "" !== $cost_on_category_status ) ? 'checked' : '';
		$cost_on_category_weight_status         = ( ! empty( $cost_on_category_weight_status ) && 'on' === $cost_on_category_weight_status && "" !== $cost_on_category_weight_status ) ? 'checked' : '';
		$cost_on_category_subtotal_status       = ( ! empty( $cost_on_category_subtotal_status ) && 'on' === $cost_on_category_subtotal_status && "" !== $cost_on_category_subtotal_status ) ? 'checked' : '';
        $cost_on_tag_status                     = ( ! empty( $cost_on_tag_status ) && 'on' === $cost_on_tag_status && "" !== $cost_on_tag_status ) ? 'checked' : '';
        $cost_on_tag_subtotal_status            = ( ! empty( $cost_on_tag_subtotal_status ) && 'on' === $cost_on_tag_subtotal_status && "" !== $cost_on_tag_subtotal_status ) ? 'checked' : '';
		$cost_on_tag_weight_status              = ( ! empty( $cost_on_tag_weight_status ) && 'on' === $cost_on_tag_weight_status && "" !== $cost_on_tag_weight_status ) ? 'checked' : '';
		$cost_on_total_cart_qty_status          = ( ! empty( $cost_on_total_cart_qty_status ) && 'on' === $cost_on_total_cart_qty_status && "" !== $cost_on_total_cart_qty_status ) ? 'checked' : '';
        $cost_on_shipping_class_status          = ( ! empty( $cost_on_shipping_class_status ) && 'on' === $cost_on_shipping_class_status && "" !== $cost_on_shipping_class_status ) ? 'checked' : '';
		$cost_on_shipping_class_weight_status   = ( ! empty( $cost_on_shipping_class_weight_status ) && 'on' === $cost_on_shipping_class_weight_status && "" !== $cost_on_shipping_class_weight_status ) ? 'checked' : '';
		$cost_on_shipping_class_subtotal_status = ( ! empty( $cost_on_shipping_class_subtotal_status ) && 'on' === $cost_on_shipping_class_subtotal_status && "" !== $cost_on_shipping_class_subtotal_status ) ? 'checked' : '';
		$cost_on_product_attribute_status       = ( ! empty( $cost_on_product_attribute_status ) && 'on' === $cost_on_product_attribute_status && "" !== $cost_on_product_attribute_status ) ? 'checked' : '';
		$sm_estimation_delivery                 = ! empty( $sm_estimation_delivery ) ? esc_attr( stripslashes( $sm_estimation_delivery ) ) : '';
		$sm_start_date                          = ! empty( $sm_start_date ) ? esc_attr( stripslashes( $sm_start_date ) ) : '';
		$sm_end_date                            = ! empty( $sm_end_date ) ? esc_attr( stripslashes( $sm_end_date ) ) : '';
		$sm_time_from                           = ! empty( $sm_time_from ) ? esc_attr( stripslashes( $sm_time_from ) ) : '';
		$sm_time_to                             = ! empty( $sm_time_to ) ? esc_attr( stripslashes( $sm_time_to ) ) : '';
		$sm_select_day_of_week                  = ! empty( $sm_select_day_of_week ) ? $sm_select_day_of_week : '';
		$sm_free_shipping_based_on_product      = ! empty( $sm_free_shipping_based_on_product ) ? $sm_free_shipping_based_on_product : array();
		$sm_free_shipping_exclude_product       = ! empty( $sm_free_shipping_exclude_product ) ? $sm_free_shipping_exclude_product : array();
		if ( empty( $fee_settings_unique_shipping_title ) && ! empty( $sm_title ) ) {
			$fee_settings_unique_shipping_title = $sm_title;
		}
	}
}
$submit_text = __( 'Save changes', 'advanced-flat-rate-shipping-for-woocommerce' );
?>
<?php // Shipping Rules Condition   ?>
	<div class="text-condtion-is" style="display:none;">
		<select class="text-condition">
			<option value="is_equal_to"><?php esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			<option value="less_equal_to"><?php esc_html_e( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			<option value="less_then"><?php esc_html_e( 'Less than ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			<option value="greater_equal_to"><?php esc_html_e( 'Greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			<option value="greater_then"><?php esc_html_e( 'Greater than ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			<option value="not_in"><?php esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
		</select>
		<select class="select-condition">
			<option value="is_equal_to"><?php esc_html_e( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
			<option value="not_in"><?php esc_html_e( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
		</select>
	</div>
	<div class="default-country-box" style="display:none;">
		<?php echo wp_kses( $afrsm_admin_object->afrsm_pro_get_country_list(), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ); ?>
	</div>

	<div class="afrsm-section-left">
		<div class="afrsm-main-table res-cl">
			<h2><?php esc_html_e( 'Shipping Method Configuration', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
			<form method="POST" name="feefrm" action="">
				<?php wp_nonce_field( 'afrsm_pro_save_action', 'afrsm_pro_conditions_save' ); ?>
				<input type="hidden" name="post_type" value="wc_afrsm">
				<input type="hidden" name="fee_post_id" value="<?php echo esc_attr( $get_post_id ) ?>">
				<table class="form-table table-outer shipping-method-table afrsm-table-tooltip">
					<tbody>
					<?php
					do_action( 'afrsm_status_field_before', $get_post_id );
					?>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label>
                                <?php esc_html_e( 'Status', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'This method will be visible to customers only if it is enabled.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp">
							<label class="switch">
								<input type="checkbox" name="sm_status" value="on" <?php echo esc_attr( $sm_status ); ?> />
								<div class="slider round"></div>
							</label>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_status_field_after', $get_post_id );
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							do_action( 'afrsm_ust_field_before', $get_post_id );
							?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="fee_settings_unique_shipping_title">
                                        <?php esc_html_e( 'Shipping Title', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <span class="required-star">*</span>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'This will display only for admin purpose', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
									</label>
								</th>
								<td class="forminp">
									<input type="text" name="fee_settings_unique_shipping_title" class="text-class" id="fee_settings_unique_shipping_title" value="<?php echo esc_attr( $fee_settings_unique_shipping_title ); ?>" required="1" placeholder="<?php echo esc_attr( 'Enter shipping title', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" />
								</td>
							</tr>
							<?php
							do_action( 'afrsm_ust_field_after', $get_post_id );
						}
					}
					do_action( 'afrsm_sname_field_before', $get_post_id );
					?>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="fee_settings_product_fee_title">
                                <?php esc_html_e( 'Shipping Method Name', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
								<span class="required-star">*</span>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'This name will be visible to the customer at the time of checkout. This should convey the purpose of the charges you are applying to the order.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
							</label>
						</th>
						<td class="forminp">
							<input type="text" name="fee_settings_product_fee_title" class="text-class" id="fee_settings_product_fee_title" value="<?php echo esc_attr( $sm_title ); ?>" required="1" placeholder="<?php echo esc_attr( 'Enter shipping method name', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" />
						</td>
					</tr>
					<?php
					do_action( 'afrsm_sname_field_after', $get_post_id );
					do_action( 'afrsm_scharge_field_before', $get_post_id );
					?>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="sm_product_cost">
                                <?php esc_html_e( 'Shipping Charge', 'advanced-flat-rate-shipping-for-woocommerce' ); ?> (<?php echo esc_html(get_woocommerce_currency_symbol()); ?>)
								<span class="required-star">*</span>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'You can add a fixed/percentage fee based on the selection above.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
							</label>
						</th>

						<td class="forminp">
							<div class="product_cost_left_div">
								<input type="text" name="sm_product_cost" required="1" class="text-class" id="sm_product_cost" value="<?php echo esc_attr( $sm_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
							</div>
							<?php
							if ( afrsfw_fs()->is__premium_only() ) {
								if ( afrsfw_fs()->can_use_premium_code() ) {
									?>
									<div class="product_cost_right_div">
										<div class="applyperqty-boxone">
                                            <div class="applyperqty-box">
                                                <label for="fee_chk_qty_price">
                                                    <?php esc_html_e( 'Apply Per Additional Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Apply this fee per quantity of products.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                </label>
                                                <input type="checkbox" name="sm_fee_chk_qty_price" id="fee_chk_qty_price" class="chk_qty_price_class" value="on" <?php checked( $getFeesPerQtyFlag, 'on' ); ?>>
                                                <?php
                                                $html = sprintf( '<p class="note"><b style="color: red;">%s</b>%s</p>',
                                                    esc_html__( 'Note : ', 'advanced-flat-rate-shipping-for-woocommerce' ),
                                                    esc_html__( 'If you active this option then Advance Pricing Rule will be disable and not working.', 'advanced-flat-rate-shipping-for-woocommerce' )
                                                );
                                                echo wp_kses_post( $html );
                                                ?>
                                            </div>
										</div>
										<div class="applyperqty-boxtwo">
                                            <div class="applyperqty-box">
                                                <label for="price_cartqty_based">
                                                    <?php esc_html_e( 'Calculate Quantity Based On', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                                    <?php
                                                    echo wp_kses( wc_help_tip( esc_html__( 'Cart based will apply to the total product\'s quantity in the cart and Product based will apply to the specific product\'s quantity in the cart.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); 
                                                    ?>
                                                </label>
                                                <select name="sm_fee_per_qty" id="price_cartqty_based" class="chk_qty_price_class afrsm_select" >
                                                    <?php
                                                    $afrsm_apq_array = $afrsm_admin_object->afrsm_apq_type_action();
                                                    foreach ( $afrsm_apq_array as $key => $value ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $getFeesPerQty, $key ); ?>><?php echo esc_html( $value ); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
										</div>
										<div class="applyperqty-boxthree">
                                            <div class="applyperqty-box">
                                                <label for="extra_product_cost">
                                                    <?php esc_html_e( 'Fee per Additional Quantity&nbsp;(' . get_woocommerce_currency_symbol() . ') ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                                    <span class="required-star">*</span>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can add shipping here to be charged for each additional quantity.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                </label>
                                                <input type="number" name="sm_extra_product_cost" class="number-class" id="extra_product_cost" required step="0.01" min="0" value="<?php echo isset( $extraProductCost ) ? esc_attr( $extraProductCost ) : ''; ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                                            </div>
										</div>
									</div>
									<?php
								}
							}
							?>
                            <div class="description afrsm_dynamic_rules_tooltips">
                                <p><?php echo wp_kses( __( 'When customer select this shipping method the amount will be added to the cart subtotal. You can enter fixed amount or make it dynamic using below parameters:', 'advanced-flat-rate-shipping-for-woocommerce' ), array() );?></p>
                                <div class="afrsm_dynamic_rules_content">
                                    <?php
                                    echo sprintf( wp_kses( __( 
                                        '<p>&nbsp;&nbsp;&nbsp;<span>[qty]</span> - total number of items in cart<br/>
                                        &nbsp;&nbsp;&nbsp;<span>[cost]</span> - cost of items<br/>
                                        &nbsp;&nbsp;&nbsp;<span>[fee percent=10 min_fee=20]</span> - Percentage based fee<br/>
                                        &nbsp;&nbsp;&nbsp;<span>[fee percent=10 max_fee=20]</span> - Percentage based fee<br/><br/>
                                        Below are some examples:<br/>
                                        &nbsp;&nbsp;&nbsp;<strong>i.</strong> 10.00 -> To add flat 10.00 shipping charge.<br/>
                                        &nbsp;&nbsp;&nbsp;<strong>ii.</strong> 10.00 * <span>[qty]</span> - To charge 10.00 per quantity in the cart. It will be 50.00 if the cart has 5 quantity.<br/>
                                        &nbsp;&nbsp;&nbsp;<strong>iii.</strong> <span>[fee percent=10 min_fee=20]</span> - This means charge 10 percent of cart subtotal, minimum 20 charge will be applicable.<br/>
                                        &nbsp;&nbsp;&nbsp;<strong>iv.</strong> <span>[fee percent=10 max_fee=20]</span> - This means charge 10 percent of cart subtotal greater than max_fee then maximum 20 charge will be applicable.<br/><br/>
                                        <span class="dashicons dashicons-info-outline"></span>
                                        <a href="https://docs.thedotstore.com/article/101-shipping-fee-configuration-form" target="_blank">View Documentation</a><br/>'
                                        , 'advanced-flat-rate-shipping-for-woocommerce' )
                                    , array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );

                                    ?>
                                </div>
                            </div>
                            <a href="javascript:void(0);" id="afrsm_chk_advanced_settings" class="afrsm_chk_advanced_settings"><?php esc_html_e( 'Advance settings', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_scharge_field_after', $get_post_id );
                    if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							do_action( 'afrsm_etd_field_before', $get_post_id );
							?>
							<tr valign="top" class="afrsm_advanced_setting_section">
								<th class="titledesc" scope="row">
									<label for="sm_estimation_delivery">
                                        <?php esc_html_e( 'Estimated Delivery Time', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'With this feature, you can specify approximately days or time to deliver the order to the customers. It will increase your conversion ratio. (Not for forceall shipping method)', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="text" name="sm_estimation_delivery" class="text-class" id="sm_estimation_delivery" placeholder="<?php echo esc_attr( 'e.g. (2-5 days)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>" value="<?php echo esc_attr( $sm_estimation_delivery ); ?>">
								</td>
							</tr>
							<?php
							do_action( 'afrsm_etd_field_after', $get_post_id );
							do_action( 'afrsm_start_date_field_before', $get_post_id );
							?>
							<tr valign="top" class="afrsm_advanced_setting_section">
								<th class="titledesc" scope="row">
									<label for="sm_start_date">
                                        <?php esc_html_e( 'Start Date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php
										$html = sprintf( wp_kses( __( 
											'Select start date on which date shipping method will enable on the website.
											&nbsp;&nbsp;&nbsp;<span class="dashicons dashicons-info-outline"></span>
											<a href="https://docs.thedotstore.com/article/300-how-to-add-start-date-and-end-data" target="_blank">View Documentation</a><br/>'
											, 'advanced-flat-rate-shipping-for-woocommerce' )
										, array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );
										echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                        ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="text" name="sm_start_date" class="text-class" id="sm_start_date" value="<?php echo esc_attr( $sm_start_date ); ?>" placeholder="<?php echo esc_attr( 'Select start date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
								</td>
							</tr>
							<?php
							do_action( 'afrsm_start_date_field_after', $get_post_id );
							do_action( 'afrsm_end_date_field_before', $get_post_id );
							?>
							<tr valign="top" class="afrsm_advanced_setting_section">
								<th class="titledesc" scope="row">
									<label for="sm_end_date">
                                        <?php esc_html_e( 'End Date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php
										$html = sprintf( wp_kses( __( 
											'Select end date on which date shipping method will disable on the website.
											&nbsp;&nbsp;&nbsp;<span class="dashicons dashicons-info-outline"></span>
											<a href="https://docs.thedotstore.com/article/300-how-to-add-start-date-and-end-data" target="_blank">View Documentation</a><br/>'
											, 'advanced-flat-rate-shipping-for-woocommerce' )
										, array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );
										echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                        ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="text" name="sm_end_date" class="text-class" id="sm_end_date" value="<?php echo esc_attr( $sm_end_date ); ?>" placeholder="<?php echo esc_attr( 'Select end date', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
								</td>
							</tr>
							<?php
							do_action( 'afrsm_end_date_field_after', $get_post_id );
							do_action( 'afrsm_dow_field_before', $get_post_id );
							?>
							<tr valign="top" class="afrsm_advanced_setting_section">
								<th class="titledesc" scope="row">
									<label for="sm_select_day_of_week">
                                        <?php esc_html_e( 'Days of the Week', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php
										$html = sprintf( '%s<a href=%s target="_blank">%s</a>',
											esc_html__( 'Select days on which day shipping method will enable on the website. This rule match with current day which is set by wordpress', 'advanced-flat-rate-shipping-for-woocommerce' ),
											esc_url( admin_url( 'options-general.php' ) ),
											esc_html__( 'Timezone', 'advanced-flat-rate-shipping-for-woocommerce' )
										);
                                        echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                        ?>
                                    </label>
								</th>
								<td class="forminp">
									<?php
									$select_day_week_array = array(
										'sun' => esc_html__( 'Sunday', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'mon' => esc_html__( 'Monday', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'tue' => esc_html__( 'Tuesday', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'wed' => esc_html__( 'Wednesday', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'thu' => esc_html__( 'Thursday', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'fri' => esc_html__( 'Friday', 'advanced-flat-rate-shipping-for-woocommerce' ),
										'sat' => esc_html__( 'Saturday', 'advanced-flat-rate-shipping-for-woocommerce' ),
									);
									?>
									<select name="sm_select_day_of_week[]" id="sm_select_day_of_week" class="sm_select_day_of_week afrsm_select" multiple="multiple" placeholder='<?php echo esc_attr( 'Select days of the week', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>'>
										<?php
										foreach ( $select_day_week_array as $value => $name ) {
											?>
											<option value="<?php echo esc_attr( $value ); ?>" <?php echo ! empty( $sm_select_day_of_week ) && in_array( $value, $sm_select_day_of_week, true ) ? 'selected="selected"' : '' ?>><?php echo esc_html( $name ); ?></option>
											<?php
										}
										?>
									</select>
								</td>
							</tr>
							<?php
							do_action( 'afrsm_dow_field_after', $get_post_id );
							do_action( 'afrsm_time_field_before', $get_post_id );
							?>
							<tr valign="top" class="afrsm_advanced_setting_section">
								<th class="titledesc" scope="row">
									<label>
                                        <?php esc_html_e( 'Time', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php
										$html = sprintf( '%s<a href=%s target="_blank">%s</a>',
											esc_html__( 'Select time on which time shipping method will enable on the website. This rule match with current time which is set by wordpress', 'advanced-flat-rate-shipping-for-woocommerce' ),
											esc_url( admin_url( 'options-general.php' ) ),
											esc_html__( 'Timezone', 'advanced-flat-rate-shipping-for-woocommerce' )
										);
                                        echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                        ?>
                                    </label>
								</th>
								<td class="forminp">
									<!-- <?php esc_html_e( 'From:', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span> -->
									<input type="text" name="sm_time_from" class="text-class afrsm_time_input" id="sm_time_from" value="<?php echo esc_attr( $sm_time_from ); ?>" placeholder='<?php echo esc_attr( 'Select start time', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>' autocomplete="off" />
									<span class="sm_time_to"><?php esc_html_e( '-', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
									<input type="text" name="sm_time_to" class="text-class afrsm_time_input" id="sm_time_to" value="<?php echo esc_attr( $sm_time_to ); ?>" placeholder='<?php echo esc_attr( 'Select end time', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>' autocomplete="off" />
                                    <a href="javascript:void(0)" class="sm_reset_time"><span class="dashicons dashicons-update"></span></a>
								</td>
							</tr>
							<?php
							do_action( 'afrsm_time_field_after', $get_post_id );
						}
					}
                    do_action( 'afrsm_is_log_in_user_before', $get_post_id );
					?>
					<tr valign="top" class="afrsm_advanced_setting_section">
						<th class="titledesc" scope="row">
							<label for="sm_select_log_in_user">
                                <?php esc_html_e( 'Enable for logged in users?', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Display shipping method only for logged in users. Default: No', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp afrsm-radio-section">
                            <label>
                                <input name="sm_select_log_in_user" class="sm_select_log_in_user" type="radio" value="yes" <?php checked( $sm_is_log_in_user, 'yes' ); ?>>
                                <?php esc_html_e( 'Yes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
                            <label>
                                <input name="sm_select_log_in_user" class="sm_select_log_in_user" type="radio" value="no" <?php empty($sm_is_log_in_user) ? checked( $sm_is_log_in_user, '' ) : checked( $sm_is_log_in_user, 'no' ); ?>>
                                <?php esc_html_e( 'No', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_is_log_in_user_after', $get_post_id );
                    do_action( 'afrsm_first_order_for_user_before', $get_post_id );
					?>
					<tr valign="top" class="afrsm_advanced_setting_section">
						<th class="titledesc" scope="row">
							<label for="sm_select_first_order_for_user">
                                <?php esc_html_e( 'Enable for first order', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Only apply when user will place first order. Default: No', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp afrsm-radio-section">
                            <label>
                                <input name="sm_select_first_order_for_user" class="sm_select_first_order_for_user" type="radio" value="yes" <?php checked( $sm_first_order_for_user, 'yes' ); ?>>
                                <?php esc_html_e( 'Yes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
                            <label>
                                <input name="sm_select_first_order_for_user" class="sm_select_first_order_for_user" type="radio" value="no" <?php empty($sm_first_order_for_user) ? checked( $sm_first_order_for_user, '' ) : checked( $sm_first_order_for_user, 'no' ); ?>>
                                <?php esc_html_e( 'No', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_first_order_for_user_after', $get_post_id );
					do_action( 'afrsm_free_shipping_status_before', $get_post_id );
					?>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="sm_free_shipping_cost">
                                <?php esc_html_e( 'Allow Free Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php
								$html = sprintf( wp_kses( __( 
									'Enable or disable free shipping. Default: False.
									&nbsp;&nbsp;&nbsp;<span class="dashicons dashicons-info-outline"></span>
									<a href="https://docs.thedotstore.com/article/403-advanced-free-shipping-rules" target="_blank">View Documentation</a><br/>'
									, 'advanced-flat-rate-shipping-for-woocommerce' )
								, array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );
                                echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                ?>
                            </label>
						</th>
						<td class="forminp">
							<input type="checkbox" name="is_allow_free_shipping" id="is_allow_free_shipping" class="is_allow_free_shipping" value="on" <?php checked( $is_allow_free_shipping, 'on' ); ?> />
						</td>
					</tr>
					<?php
					do_action( 'afrsm_free_shipping_status_after', $get_post_id );
					do_action( 'afrsm_free_shipping_based_on_before', $get_post_id );
					?>
					<tr valign="top" class="free_shipping_section free_shipping_section_top_css">
						<th class="titledesc" scope="row">
							<label for="sm_free_shipping_based_on">
                                <?php esc_html_e( 'Free Shipping based on', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Allow free shipping based on order amount, coupon amount or products .', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp">
							<select name="sm_free_shipping_based_on" id="sm_free_shipping_based_on" class="afrsm_select_log_in_user">
								<option value="min_order_amt" <?php echo isset( $sm_free_shipping_based_on ) && 'min_order_amt' === $sm_free_shipping_based_on ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Minimum Order Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								<option value="min_coupan_amt" <?php echo isset( $sm_free_shipping_based_on ) && 'min_coupan_amt' === $sm_free_shipping_based_on ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Free Shipping on Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								<?php
								if ( afrsfw_fs()->is__premium_only() ) {
									if ( afrsfw_fs()->can_use_premium_code() ) { ?>
										<option value="min_simple_product" <?php echo isset( $sm_free_shipping_based_on ) && 'min_simple_product' === $sm_free_shipping_based_on ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Free Shipping on Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option> 
										<?php	
									}else{ ?>
										<option value="in_pro" disabled><?php esc_html_e( 'Free Shipping on Product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<?php
									}
								}else{ ?>
									<option value="in_pro" disabled><?php esc_html_e( 'Free Shipping on Product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									<?php
								} ?>
							</select>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_free_shipping_based_on_after', $get_post_id );
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							do_action( 'afrsm_free_shipping_product_before', $get_post_id );
							?>
							<tr valign="top" class="free_shipping_section free_shipping_simple_prod">
								<th class="titledesc" scope="row">
									<label for="sm_free_shipping_label">
                                        <?php esc_html_e( 'Free Shipping - Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select products which you want to apply for free shipping. Free shipping rule active when selected product in cart.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
									<select name="sm_free_shipping_based_on_product[]" id="sm_free_shipping_based_on_product" class=" multiselect2 product_fees_conditions_values_product sm_free_shipping_based_on_product" multiple="multiple">
										<?php
										$free_shipping_selected = $sm_free_shipping_based_on_product;
										$free_shipping_selected_count = 900;

										$get_all_products = new WP_Query( array(
											'post_type'      => 'product',
											'post_status'    => 'publish',
											'posts_per_page' => $free_shipping_selected_count,
											'post__in'       => $free_shipping_selected,
										) );

										if ( isset( $get_all_products->posts ) && ! empty( $get_all_products->posts ) ) {
											$html_for_free_shipping_product = '';
											foreach ( $get_all_products->posts as $get_all_product ) {
												
												$new_product_id = $get_all_product->ID;
												
												if(is_array( $free_shipping_selected ) && ! empty( $free_shipping_selected )) {
													$free_shipping_selected = array_map( 'intval', $free_shipping_selected );
												}

												$selectedVal = is_array( $free_shipping_selected ) && ! empty( $free_shipping_selected ) && in_array( $new_product_id, $free_shipping_selected, true ) ? 'selected=selected' : '';

												if ( '' !== $selectedVal ) {
													$html_for_free_shipping_product .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
												}
											}
											echo wp_kses( $html_for_free_shipping_product, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
										}
										?>
									</select>
								</td>
							</tr>
							<?php
							do_action( 'afrsm_free_shipping_product_after', $get_post_id );
						}
					}
					do_action( 'afrsm_free_shipping_label_before', $get_post_id );
					?>
					<tr valign="top" class="free_shipping_section">
						<th class="titledesc" scope="row">
							<label for="sm_free_shipping_label">
                                <?php esc_html_e( 'Free Shipping - Label', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'This name will be visible to the customer at the time of checkout when free shipping is available. For example "Free Shipping", "Free Rate" etc', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
							<?php
								if ( afrsfw_fs()->is__premium_only() ) {
									if ( afrsfw_fs()->can_use_premium_code() ) { 	
									}else{ ?>
										<span class="afrsm-new-feture"><?php esc_html_e( '[In Pro]', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
									<?php
									}
								}else{ ?>
										<span class="afrsm-new-feture"><?php esc_html_e( '[In Pro]', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
									<?php
								} 
							?>
						</th>
						<td class="forminp">
							<?php
							if ( afrsfw_fs()->is__premium_only() ) {
								if ( afrsfw_fs()->can_use_premium_code() ) {
									$input_disabled = "";
									$free_shipping_placeholder = "Enter Free Shipping Label";
								}else{
									$input_disabled = "disabled";
									$free_shipping_placeholder = "Free Shipping";
								}
							}else{
								$input_disabled = "disabled";
								$free_shipping_placeholder = "Free Shipping";
							}
							?>
							<input type="text" name="sm_free_shipping_label" class="text-class" id="sm_free_shipping_label" value="<?php echo esc_attr( $sm_free_shipping_label ); ?>" placeholder="<?php echo esc_attr( $free_shipping_placeholder ); ?>" <?php echo esc_attr( $input_disabled ); ?>>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_free_shipping_label_after', $get_post_id );
					do_action( 'afrsm_free_shipping_order_amount_before', $get_post_id );
					?>
					<tr valign="top" class="free_shipping_section free_shipping_amt">
						<th class="titledesc" scope="row">
							<label for="sm_free_shipping_cost">
                                <?php esc_html_e( 'Free Shipping Order - Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Maximum free shipping order amount', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp">
							<input type="text" name="sm_free_shipping_cost" class="text-class" id="sm_free_shipping_cost" value="<?php echo esc_attr( $sm_free_shipping_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                            <?php 
                            if ( afrsfw_fs()->is__premium_only() ) {
						        if ( afrsfw_fs()->can_use_premium_code() ) { ?>
                                    <div class="afrsm_exclude_product_from_free">
                                        <input type="checkbox" name="is_free_shipping_exclude_prod" id="is_free_shipping_exclude_prod" class="is_free_shipping_exclude_prod" value="on" <?php checked( $is_free_shipping_exclude_prod, 'on' ); ?>>
                                        <label for="is_free_shipping_exclude_prod"><?php esc_html_e( 'Exclude Special Products', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
                                        <div class="afrsm_exclude_product">
                                            <select name="sm_free_shipping_exclude_product[]" id="sm_free_shipping_exclude_product" class="afrsm_product_dropdown  sm_free_shipping_exclude_product" multiple="multiple">
                                                <?php
                                                $free_shipping_selected = $sm_free_shipping_exclude_product;

                                                $get_all_products = new WP_Query( array(
                                                    'post_type'      => array( 'product', 'product_variation' ),
                                                    'post_status'    => 'publish',
                                                    'post__in'       => $sm_free_shipping_exclude_product,
                                                ) );

                                                if ( isset( $get_all_products->posts ) && ! empty( $get_all_products->posts ) ) {
                                                    $html_for_free_shipping_product = '';
                                                    foreach ( $get_all_products->posts as $get_all_product ) {
                                                        
                                                        $new_product_id = $get_all_product->ID;
                                                        
                                                        if(is_array( $sm_free_shipping_exclude_product ) && ! empty( $sm_free_shipping_exclude_product )) {
                                                            $sm_free_shipping_exclude_product = array_map( 'intval', $sm_free_shipping_exclude_product );
                                                        }

                                                        $selectedVal = is_array( $sm_free_shipping_exclude_product ) && ! empty( $sm_free_shipping_exclude_product ) && in_array( $new_product_id, $sm_free_shipping_exclude_product, true ) ? 'selected=selected' : '';

                                                        if ( '' !== $selectedVal ) {
                                                            $html_for_free_shipping_product .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                                                        }
                                                    }
                                                    echo wp_kses( $html_for_free_shipping_product, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php 
                                }
                            } ?>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_free_shipping_order_amount_after', $get_post_id );
					?>

					<?php
					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) { 
							do_action( 'afrsm_free_shipping_before_coupon_before', $get_post_id ); ?>
							<tr valign="top" class="free_shipping_section free_shipping_amt">
								<th class="titledesc" scope="row">
									<label for="sm_free_shipping_cost_before_discount">
                                        <?php esc_html_e( 'Apply before coupon discount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Apply free shipping amount before coupon code. Default: False.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="sm_free_shipping_cost_before_discount" id="sm_free_shipping_cost_before_discount" class="sm_free_shipping_cost_before_discount" value="on" <?php checked( $sm_free_shipping_cost_before_discount, 'on' ); ?> />
								</td>
							</tr>
							<?php
							do_action( 'afrsm_free_shipping_before_coupon_after', $get_post_id );
							do_action( 'afrsm_free_shipping_price_left_notice_before', $get_post_id );
							?>
							<tr valign="top" class="free_shipping_section free_shipping_amt free_shipping_section_bottom_css">
								<th class="titledesc" scope="row">
									<label for="sm_free_shipping_cost_left_notice">
                                        <?php esc_html_e( 'Display notice with left amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Display the notice with the amount of price left to free shipping', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="sm_free_shipping_cost_left_notice" id="sm_free_shipping_cost_left_notice" class="sm_free_shipping_cost_left_notice" value="on" <?php checked( $sm_free_shipping_cost_left_notice, 'on' ); ?>>
								</td>
							</tr>
						<?php
							do_action( 'afrsm_free_shipping_price_left_notice_after', $get_post_id );
							do_action( 'afrsm_free_shipping_price_left_notice_msg_before', $get_post_id );
							?>
							<tr valign="top" class="free_shipping_section free_shipping_amt free_shipping_amt_msg">
								<th class="titledesc" scope="row">
									<label for="sm_free_shipping_cost_left_notice_msg">
                                        <?php esc_html_e( 'Change the notice message', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Change the notice message. Use {LEFT_PRICE_VALUE} to get a dynamic price value.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
									<textarea name="sm_free_shipping_cost_left_notice_msg" rows="3" cols="70" id="sm_free_shipping_cost_left_notice_msg" maxlength="100" placeholder="<?php echo esc_attr( 'Get free shipping if you order {LEFT_PRICE_VALUE} more!', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"><?php echo wp_kses_post( $sm_free_shipping_cost_left_notice_msg ); ?></textarea>
								</td>
							</tr>
							<?php
							do_action( 'afrsm_free_shipping_price_left_notice_msg_after', $get_post_id );
						}
					}	
					?>
					<?php
					do_action( 'afrsm_free_shipping_coupon_amount_before', $get_post_id ); 
					?>
					<tr valign="top" class="free_shipping_section free_shipping_coupon free_shipping_section_bottom_css">
						<th class="titledesc" scope="row">
							<label for="sm_free_shipping_coupan_cost">
                                <?php esc_html_e( 'Free Shipping Coupon - Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Maximum free shipping coupon amount', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp">
							<input type="text" name="sm_free_shipping_coupan_cost" class="text-class" id="sm_free_shipping_coupan_cost" value="<?php echo esc_attr( $sm_free_shipping_coupan_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
						</td>
					</tr>
					<?php
					do_action( 'afrsm_free_shipping_coupon_amount_after', $get_post_id ); 

					if ( afrsfw_fs()->is__premium_only() ) {
						if ( afrsfw_fs()->can_use_premium_code() ) {
							do_action( 'afrsm_free_shipping_each_weight_before', $get_post_id ); ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label>
                                        <?php esc_html_e( 'Each Weight Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php
										$html = sprintf( wp_kses( __( 
											'Enable or disable weight base shipping. <strong>Default: False</strong><br/>
											Apply additional rule per weight in cart page.<br/>
											<strong>Ex:</strong> 10 amount charges per 2 KG over 10KG<br/>
											In this rule Per each 2KG add charges of the amount of 10. But it will be apply over the 10KG in cart.
											&nbsp;&nbsp;&nbsp;
											<span class="dashicons dashicons-info-outline"></span>
											<a href="https://docs.thedotstore.com/article/401-advanced-weight-based-shipping-rules" target="_blank">View Documentation</a>
											<br/>'
											, 'advanced-flat-rate-shipping-for-woocommerce' )
										, array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );
										echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                        ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="is_allow_custom_weight_base" id="is_allow_custom_weight_base" class="is_allow_custom_weight_base" value="on" <?php checked( $is_allow_custom_weight_base, 'on' ); ?>>
								</td>
							</tr>
							<tr valign="top" class="depend_of_custom_weight_base">
								<th class="titledesc" scope="row">
                                    <label for="sm_custom_weight_base_cost">
                                        <?php esc_html_e( 'Weight - Rate', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Maximum free shipping coupan amount', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
                                    <input type="text" name="sm_custom_weight_base_cost" class="text-class" id="sm_custom_weight_base_cost" value="<?php echo esc_attr( $sm_custom_weight_base_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="depend_of_custom_weight_base">
                                <th class="titledesc" scope="row">
                                    <label for="sm_custom_weight_base_cost">
                                        <?php esc_html_e( 'Weight - Per each', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Weight per each', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
                                </th>
								<td class="forminp">
                                    <input type="text" name="sm_custom_weight_base_per_each" class="text-class" id="sm_custom_weight_base_per_each" value="<?php echo esc_attr( $sm_custom_weight_base_per_each ); ?>" placeholder="<?php echo esc_attr( 'kg', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="depend_of_custom_weight_base">
                                <th class="titledesc" scope="row">
                                    <label for="sm_custom_weight_base_cost">
                                        <?php esc_html_e( 'Weight - Over', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Weight over', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
                                </th>
                                <td class="forminp">
                                    <input type="text" name="sm_custom_weight_base_over" class="text-class" id="sm_custom_weight_base_over" value="<?php echo esc_attr( $sm_custom_weight_base_over ); ?>" placeholder="<?php echo esc_attr( 'kg', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
								</td>
							</tr>
							<?php
							do_action( 'afrsm_free_shipping_each_weight_after', $get_post_id ); 
							do_action( 'afrsm_free_shipping_each_qty_before', $get_post_id ); ?>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label>
                                        <?php esc_html_e( 'Each Quantity Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php
										$html = sprintf( wp_kses( __( 
											'Enable or disable each quantity base shipping. <strong>Default: False</strong><br/>
											Apply additional rule per quantity in cart page.<br/>
											<strong>Ex:</strong> 10 amount charges per 2 QTY over 10 QTY<br/>
											In this rule Per each 2 QTY add charges of the amount of 10. But it will be apply over the 10 QTY in cart.
											&nbsp;&nbsp;&nbsp;
											<span class="dashicons dashicons-info-outline"></span>
											<a href="https://docs.thedotstore.com/article/402-advanced-quantity-based-shipping-rules" target="_blank">View Documentation</a>
											<br/>'
											, 'advanced-flat-rate-shipping-for-woocommerce' )
										, array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );
										echo wp_kses( wc_help_tip( $html ), array( 'span' => $allowed_tooltip_html ) ); 
                                        ?>
                                    </label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="is_allow_custom_qty_base" id="is_allow_custom_qty_base" class="is_allow_custom_qty_base" value="on" <?php checked( $is_allow_custom_qty_base, 'on' ); ?>>
								</td>
							</tr>
							<tr valign="top" class="depend_of_custom_qty_base">
								<th class="titledesc" scope="row">
                                    <label for="sm_custom_qty_base_cost">
                                        <?php esc_html_e( 'Quantity - Rate', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Quantity amount', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
								<td class="forminp">
                                    <input type="text" name="sm_custom_qty_base_cost" class="text-class" id="sm_custom_qty_base_cost" value="<?php echo esc_attr( $sm_custom_qty_base_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="depend_of_custom_qty_base">
                                <th class="titledesc" scope="row">
                                    <label for="sm_custom_qty_base_per_each">
                                        <?php esc_html_e( 'Quantity - Per each', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Quantity per each', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
                                </th>
                                <td class="forminp">
                                    <input type="text" name="sm_custom_qty_base_per_each" class="text-class" id="sm_custom_qty_base_per_each" value="<?php echo esc_attr( $sm_custom_qty_base_per_each ); ?>" placeholder="<?php echo esc_attr( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="depend_of_custom_qty_base">
                                <th class="titledesc" scope="row">
                                    <label for="sm_custom_qty_base_over">
                                        <?php esc_html_e( 'Quantity - Over', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Quantity over', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                    </label>
								</th>
                                <td class="forminp">
                                    <input type="text" name="sm_custom_qty_base_over" class="text-class" id="sm_custom_qty_base_over" value="<?php echo esc_attr( $sm_custom_qty_base_over ); ?>" placeholder="<?php echo esc_attr( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
                                </td>
							</tr>
							<?php
							do_action( 'afrsm_free_shipping_each_qty_after', $get_post_id );
						}
					}
					do_action( 'afrsm_tooltip_field_before', $get_post_id );
					?>
					<tr valign="top" id="tooltip_section">
						<th class="titledesc" scope="row">
							<label for="sm_tooltip_type">
                                <?php esc_html_e( 'Tooltip type', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set which type of details you want to show on frontside. Default: Tooltip', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp">
							<select name="sm_tooltip_type" id="sm_tooltip_type" class="afrsm_tooltip_type">
								<option value="tooltip" <?php selected( $sm_tooltip_type, "tooltip" ); ?>><?php esc_html_e( 'Tooltip', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
								<option value="subtitle" <?php selected( $sm_tooltip_type, "subtitle" ); ?>><?php esc_html_e( 'Subtitle', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="sm_tooltip_desc">
                                <?php esc_html_e( 'Tooltip Description', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php
                                if ( afrsfw_fs()->is__premium_only() ) {
                                    if ( afrsfw_fs()->can_use_premium_code() ) {
                                        echo wp_kses( wc_help_tip( esc_html__( 'Not for forceall shipping method and not for dropdown shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); 
                                    } else {
                                        echo wp_kses( wc_help_tip( esc_html__( 'Not for dropdown shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) );
                                    }
                                } else {
                                    echo wp_kses( wc_help_tip( esc_html__( 'Not for dropdown shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) );
                                }
                                ?>
                            </label>
						</th>
						<td class="forminp">
                            <textarea name="sm_tooltip_desc" rows="3" cols="70" id="sm_tooltip_desc" maxlength="100" placeholder="<?php echo esc_attr( 'Enter tooltip description (Max. 100 characters)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"><?php echo wp_kses_post( $sm_tooltip_desc ); ?></textarea>
							<p class="tooltip_error error_msg" style="display:none;">
								<?php esc_html_e( 'Please enter 100 characters only!', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
							</p>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_tooltip_field_after', $get_post_id );
					do_action( 'afrsm_default_shipping_before', $get_post_id );
					?>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="sm_select_selected_shipping">
                                <?php esc_html_e( 'Default selected shipping?', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set default selected shipping method on cart. Default: No', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp afrsm-radio-section">
                            <label>
                                <input name="sm_select_selected_shipping" class="sm_select_selected_shipping" type="radio" value="yes" <?php checked( $sm_is_selected_shipping, 'yes' ); ?>>
                                <?php esc_html_e( 'Yes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
                            <label>
                                <input name="sm_select_selected_shipping" class="sm_select_selected_shipping" type="radio" value="no" <?php empty($sm_is_selected_shipping) ? checked( $sm_is_selected_shipping, '' ) : checked( $sm_is_selected_shipping, 'no' ); ?>>
                                <?php esc_html_e( 'No', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_default_shipping_after', $get_post_id );
					do_action( 'afrsm_is_amount_taxable_field_before', $get_post_id ); 
					?>
					<tr valign="top">
						<th class="titledesc" scope="row">
							<label for="sm_select_taxable">
                                <?php esc_html_e( 'Is Amount Taxable?', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Apply Tax. Default: No', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            </label>
						</th>
						<td class="forminp afrsm-radio-section">
                            <label>
                                <input name="sm_select_taxable" class="sm_select_taxable" type="radio" value="yes" <?php checked( $sm_is_taxable, 'yes' ); ?>>
                                <?php esc_html_e( 'Yes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
                            <label>
                                <input name="sm_select_taxable" class="sm_select_taxable" type="radio" value="no" <?php empty($sm_is_taxable) ? checked( $sm_is_taxable, '' ) : checked( $sm_is_taxable, 'no' ); ?>>
                                <?php esc_html_e( 'No', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                            </label>
						</td>
					</tr>
					<?php
					do_action( 'afrsm_is_amount_taxable_field_after', $get_post_id );
					if( is_plugin_active('woocommerce-germanized/woocommerce-germanized.php') ) {
						do_action( 'afrsm_shipping_provider_before', $get_post_id ); 
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="sm_select_shipping_provider">
                                    <?php esc_html_e( 'Shipping Provider', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Shipping provider select for order attahch with this fee as Germenized plugin does', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
							</th>
							<td class="forminp">
								<select name="sm_select_shipping_provider" id="sm_select_shipping_provider" class="afrsm_select_shipping_provider">
									<?php foreach( wc_gzd_get_shipping_provider_select() as $provider_k => $provider_v ) { ?>
										<option value="<?php echo esc_attr($provider_k); ?>" <?php selected( $sm_select_shipping_provider, $provider_k ); ?>><?php echo esc_html( $provider_v ); ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<?php 
						do_action( 'afrsm_shipping_provider_after', $get_post_id ); 
					} ?>
					</tbody>
				</table>
				<?php
				$all_shipping_classes = WC()->shipping->get_shipping_classes();
				if ( ! empty( $all_shipping_classes ) ) {
					?>
					<div class="shipping-sub-section element-shadow">
						<h2><?php esc_html_e( 'Additional Shipping Charges Based on Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
                        <table class="form-table table-outer shipping-method-table">
                            <tbody>
                            <tr valign="top">
                                <th class="forminp" colspan="2">
                                    <?php
                                    $html = sprintf( '%s<a href=%s>%s</a>.',
                                        esc_html__( 'These costs can optionally be added based on the ', 'advanced-flat-rate-shipping-for-woocommerce' ),
                                        esc_url( add_query_arg( array(
                                            'page'    => 'wc-settings',
                                            'tab'     => 'shipping',
                                            'section' => 'classes',
                                        ), admin_url( 'admin.php' ) ) ),
                                        esc_html__( 'product shipping class', 'advanced-flat-rate-shipping-for-woocommerce' )
                                    );
                                    echo wp_kses_post( $html );
                                    ?>
                                </th>
                            </tr>
                            <?php
                            foreach ( $all_shipping_classes as $key => $shipping_class ) {
                                $shipping_extra_cost = isset( $sm_extra_cost["$shipping_class->term_id"] ) && ( '' !== $sm_extra_cost["$shipping_class->term_id"] ) ? $sm_extra_cost["$shipping_class->term_id"] : "";
                                ?>
                                <tr valign="top">
                                    <th class="titledesc" scope="row">
                                        <label for="extra_cost_<?php echo esc_attr( $shipping_class->term_id ); ?>">
                                            <?php
                                            echo sprintf( esc_html__( '"%s" shipping class cost', 'advanced-flat-rate-shipping-for-woocommerce' ), esc_html( $shipping_class->name ) );
                                            ?>
                                        </label>
                                    </th>
                                    <td class="forminp">
                                        <input type="text" name="sm_extra_cost[<?php echo esc_attr( $shipping_class->term_id ); ?>]" class="text-class" id="extra_cost_<?php echo esc_attr( $shipping_class->term_id ); ?>" value="<?php echo esc_attr( $shipping_extra_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr valign="top">
                                <th class="titledesc" scope="row">
                                    <label for="sm_extra_cost_calculation_type"><?php esc_html_e( 'Calculation type', 'advanced-flat-rate-shipping-for-woocommerce' ) ?></label>
                                </th>
                                <td class="forminp">
                                    <select name="sm_extra_cost_calculation_type" id="sm_extra_cost_calculation_type">
                                        <option value="per_class" <?php selected( $sm_extra_cost_calc_type, 'per_class' ); ?>>
                                            <?php esc_html_e( 'Per class: Charge shipping for each shipping class individually', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        </option>
                                        <option value="per_order" <?php selected( $sm_extra_cost_calc_type, 'per_order' ); ?>>
                                            <?php esc_html_e( 'Per order: Charge shipping for the most expensive shipping class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
					</div>
				<?php } ?>
				<div class="shipping-method-rules">
					<div class="sub-title sub-section">
						<h2><?php esc_html_e( 'Shipping Method Rules', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
						<div class="tap">
							<a id="shipping-add-field" class="button" href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
						</div>
						<?php
						if ( afrsfw_fs()->is__premium_only() ) {
							if ( afrsfw_fs()->can_use_premium_code() ) {
								?>
								<div class="advance_rule_condition_match_type">
									<p class="switch_in_pricing_rules_description_left">
										<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
									<select name="cost_rule_match[general_rule_match]" id="general_rule_match"
									        class="arcmt_select">
										<option value="any" <?php selected( $general_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										<option value="all" <?php selected( $general_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
									</select>
									<p class="switch_in_pricing_rules_description">
										<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
									</p>
								</div>
								<?php
							}
						}
						?>
						<div class="noramal_shipping_rule_condition_help">
							<a href="<?php echo esc_url('https://docs.thedotstore.com/article/102-shipping-rules-or-conditions'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
							<span class="dashicons dashicons-info-outline"></span>
						</div>
					</div>
					<div class="tap">
						<table id="tbl-shipping-method" class="tbl_product_fee table-outer tap-cas form-table shipping-method-table">
							<tbody>
								<?php
								$attribute_taxonomies_name = wc_get_attribute_taxonomy_names();
								if ( isset( $sm_metabox ) && ! empty( $sm_metabox ) ) {
									$i = 2;
									foreach ( $sm_metabox as $key => $productfees ) {
										$fees_conditions = isset( $productfees['product_fees_conditions_condition'] ) ? $productfees['product_fees_conditions_condition'] : '';
										$condition_is    = isset( $productfees['product_fees_conditions_is'] ) ? $productfees['product_fees_conditions_is'] : '';
										$condtion_value  = isset( $productfees['product_fees_conditions_values'] ) ? $productfees['product_fees_conditions_values'] : array();
										?>
										<tr id="row_<?php echo esc_attr( $i ); ?>" valign="top">
											<td class="titledesc th_product_fees_conditions_condition" scope="row">
												<select rel-id="<?php echo esc_attr( $i ); ?>"
														id="product_fees_conditions_condition_<?php echo esc_attr( $i ); ?>"
														name="fees[product_fees_conditions_condition][]"
														id="product_fees_conditions_condition"
														class="product_fees_conditions_condition">
													<?php
													/**
													 * Added dynamic function for condition list action.
													 *
													 * @since  3.8
													 *
													 * @author jb
													 */
													$condition_spe = $afrsm_admin_object->afrsm_conditions_list_action();
													foreach ( $condition_spe as $optg_key => $opt_data ) {
														?>
														<optgroup label="<?php echo esc_attr( $optg_key ); ?>">
															<?php
															foreach ( $opt_data as $opt_key => $opt_value ) {
																?>
																<option value="<?php echo esc_attr( $opt_key ); ?>" <?php echo ( $opt_key === $fees_conditions ) ? 'selected' : '' ?> <?php echo ( false !== strpos($opt_key, 'in_pro') ) ? 'disabled' : '' ?>><?php echo esc_html( $opt_value ); ?></option>
																<?php
															}
															?>

														</optgroup>
														<?php
													}
													?>
												</select>
											</td>
											<td class="select_condition_for_in_notin">
												<?php
												/**
												 * Added dynamic function for operator list action.
												 *
												 * @since  3.8
												 *
												 * @author jb
												 */
												$opr_spe = $afrsm_admin_object->afrsm_operator_list_action( $fees_conditions );
												?>
												<select name="fees[product_fees_conditions_is][]"
														class="product_fees_conditions_is_<?php echo esc_attr( $i ); ?>">
													<?php
													foreach ( $opr_spe as $opr_key => $opr_value ) {
														?>
														<option value="<?php echo esc_attr( $opr_key ); ?>" <?php echo ( $opr_key === $condition_is ) ? 'selected' : '' ?>><?php echo esc_html( $opr_value ); ?></option>
														<?php
													}
													?>
												</select>
											</td>
											<td class="condition-value" id="column_<?php echo esc_attr( $i ); ?>" <?php if( $i <= 2 ) { echo 'colspan="2"'; } ?>>
												<?php
												$html = '';
												if ( afrsfw_fs()->is__premium_only() ) {
													if ( afrsfw_fs()->can_use_premium_code() ) {
														if ( 'country' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_country_list( $i, $condtion_value );
														} elseif ( 'state' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_states_list( $i, $condtion_value );
														} elseif ( 'city' === $fees_conditions ) {
															$html .= '<textarea name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']">' . wp_kses_post( $condtion_value ) . '</textarea>';
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'Make sure enter each city name in one line.', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/article/358-how-to-add-city-based-shipping-rules' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
															
																
														} elseif ( 'postcode' === $fees_conditions ) {
															$html .= '<textarea name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']">' . wp_kses_post( $condtion_value ) . '</textarea>';
														} elseif ( 'zone' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_zones_list( $i, $condtion_value );
														} elseif ( 'product' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_product_list( $i, $condtion_value );
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'Please make sure that when you add rules in
																		Advanced Pricing > Cost per Product Section It contains in above selected product list,
																		otherwise it may be not apply proper shipping charges. For more detail please view
																		our documentation at ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														} elseif ( 'variableproduct' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_varible_product_list__premium_only( $i, $condtion_value, 'edit' );
														} elseif ( 'category' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_category_list( $i, $condtion_value );
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'Please make sure that when you add rules in
																		Advanced Pricing > Cost per Category Section It contains in above selected category list,
																		otherwise it may be not apply proper shipping charges. For more detail please view
																		our documentation at ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														} elseif ( 'tag' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_tag_list( $i, $condtion_value );
														} elseif ( 'sku' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_sku_list__premium_only( $i, $condtion_value );
														} elseif ( 'product_qty' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values qty-class" class = "product_fees_conditions_values qty-class" value = "' . esc_attr( $condtion_value ) . '">';
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'This rule will only work if you have selected any one Product Specific option. ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/article/104-product-specific-shipping-rule/' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														} elseif ( in_array( $fees_conditions, $attribute_taxonomies_name, true ) ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_att_term_list__premium_only( $i, $fees_conditions, $condtion_value );
														} elseif ( 'user' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_user_list( $i, $condtion_value );
														} elseif ( 'user_role' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_user_role_list__premium_only( $i, $condtion_value );
														} elseif ( 'cart_total' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'cart_totalafter' === $fees_conditions ) {
															$html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id="product_fees_conditions_values" class="product_fees_conditions_values price-class" value="' . esc_attr( $condtion_value ) . '">';
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'After discount. In this case, subtotal amount is $25 – $10(Discount price) = $15, without discount $25 ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/article/381-how-to-add-shipping-method-based-on-after-discount-rule' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														} elseif ( 'cart_productspecific' === $fees_conditions ) {
															$html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id="product_fees_conditions_values" class="product_fees_conditions_values price-class" value="' . esc_attr( $condtion_value ) . '">';
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'This rule will only work if you have selected any one Product Specific option. ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/article/104-product-specific-shipping-rule/' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														} elseif ( 'last_spent_order' === $fees_conditions ) {
															$html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id="product_fees_conditions_values" class="product_fees_conditions_values price-class" value="' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'quantity' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values qty-class" class = "product_fees_conditions_values qty-class" value = "' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'width' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values measure-class" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'height' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values measure-class" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'length' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values measure-class" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'volume' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values measure-class" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
														} elseif ( 'weight' === $fees_conditions ) {
															$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values weight-class" class = "product_fees_conditions_values" value = "' . esc_attr( $condtion_value ) . '">';
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'Please make sure that when you add rules in
																		Advanced Pricing > Cost per weight Section It contains in above entered weight,
																		otherwise it may be not apply proper shipping charges. For more detail please view
																		our documentation at ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( 'https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce' ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														} elseif ( 'coupon' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_coupon_list__premium_only( $i, $condtion_value );
														} elseif ( 'shipping_class' === $fees_conditions ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_advance_flat_rate_class__premium_only( $i, $condtion_value );
														} elseif ( $fees_conditions === 'payment_method' ) {
															$html .= $afrsm_admin_object->afrsm_pro_get_payment__premium_only( $i, $condtion_value );
															$html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>.
																		</p>',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'This rule will work for Force All Shipping Method in master setting ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_url( add_query_arg( array( 'page' => 'afrsm-start-page' ), admin_url( 'admin.php' ) ) ),
																esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' )
															) );
														}
													} else {
                                                        if ( 'country' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_country_list( $i, $condtion_value );
                                                        } elseif ( 'state' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_states_list( $i, $condtion_value );
                                                        } elseif ( 'postcode' === $fees_conditions ) {
                                                            $html .= '<textarea name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']">' . wp_kses_post( $condtion_value ) . '</textarea>';
                                                        } elseif ( 'zone' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_zones_list( $i, $condtion_value );	
                                                        } elseif ( 'product' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_product_list( $i, $condtion_value );
                                                        } elseif ( 'category' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_category_list( $i, $condtion_value );
                                                        } elseif ( 'tag' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_tag_list( $i, $condtion_value );
                                                        } elseif ( 'user' === $fees_conditions ) {
                                                            $html .= $afrsm_admin_object->afrsm_pro_get_user_list( $i, $condtion_value );
                                                        } elseif ( 'cart_total' === $fees_conditions ) {
                                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . esc_attr( $condtion_value ) . '">';
                                                        } elseif ( 'quantity' === $fees_conditions ) {
                                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . esc_attr( $condtion_value ) . '">';
                                                        } elseif ( 'width' === $fees_conditions ) {
                                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
                                                        } elseif ( 'height' === $fees_conditions ) {
                                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
                                                        } elseif ( 'length' === $fees_conditions ) {
                                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
                                                        } elseif ( 'volume' === $fees_conditions ) {
                                                            $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
                                                        }
                                                    }
												} else {
													if ( 'country' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_country_list( $i, $condtion_value );
													} elseif ( 'state' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_states_list( $i, $condtion_value );
													} elseif ( 'postcode' === $fees_conditions ) {
														$html .= '<textarea name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']">' . wp_kses_post( $condtion_value ) . '</textarea>';
													} elseif ( 'zone' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_zones_list( $i, $condtion_value );	
													} elseif ( 'product' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_product_list( $i, $condtion_value );
													} elseif ( 'category' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_category_list( $i, $condtion_value );
													} elseif ( 'tag' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_tag_list( $i, $condtion_value );
													} elseif ( 'user' === $fees_conditions ) {
														$html .= $afrsm_admin_object->afrsm_pro_get_user_list( $i, $condtion_value );
													} elseif ( 'cart_total' === $fees_conditions ) {
														$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . esc_attr( $condtion_value ) . '">';
													} elseif ( 'quantity' === $fees_conditions ) {
														$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . esc_attr( $condtion_value ) . '">';
													} elseif ( 'width' === $fees_conditions ) {
														$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
													} elseif ( 'height' === $fees_conditions ) {
														$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
													} elseif ( 'length' === $fees_conditions ) {
														$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
													} elseif ( 'volume' === $fees_conditions ) {
														$html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . esc_attr( $i ) . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values measure-class" value = "' . esc_attr( $condtion_value ) . '">';
													}
												}
												echo wp_kses(
													apply_filters( 'afrsm_pro_product_fees_conditions_values_edit_ft', $html, $i, $fees_conditions, $condtion_value ),
													Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
												);
												?>
												<input type="hidden"
													name="condition_key[value_<?php echo esc_attr( $i ); ?>]"
													value="">
											</td>
											<?php if( $i > 2 ) { ?>
											<td>
                                                <?php 
                                                if ( afrsfw_fs()->is__premium_only() ) {
													if ( afrsfw_fs()->can_use_premium_code() ) { ?>
                                                        <a id="fee-clone-field" rel-id="<?php echo esc_attr( $i ); ?>"	class="clone-row" href="javascript:void(0);" title="Clone">
                                                            <i class="fa fa-clone"></i>
                                                        </a>
                                                        <?php 
                                                    }
                                                } ?>
												<a id="fee-delete-field" rel-id="<?php echo esc_attr( $i ); ?>"	class="delete-row" href="javascript:void(0);" title="Delete">
													<i class="dashicons dashicons-trash"></i>
												</a>
											</td>
											<?php } ?>
										</tr>
										<?php
										$i ++;
									}
									?>
									<?php
								} else {
									$i = 1;
									?>
									<tr id="row_1" valign="top">
										<td class="titledesc th_product_fees_conditions_condition" scope="row">
											<select rel-id="1" id="product_fees_conditions_condition_1"
													name="fees[product_fees_conditions_condition][]"
													id="product_fees_conditions_condition"
													class="product_fees_conditions_condition">
												<?php
												/**
												 * Added dynamic function for condition list action.
												 *
												 * @since  3.8
												 *
												 * @author jb
												 */
												$condition_spe = $afrsm_admin_object->afrsm_conditions_list_action();
												foreach ( $condition_spe as $optg_key => $opt_data ) {
													?>
													<optgroup label="<?php echo esc_attr( $optg_key ); ?>">
														<?php
														foreach ( $opt_data as $opt_key => $opt_value ) {
															?>
															<option value="<?php echo esc_attr( $opt_key ); ?>" <?php echo ( false !== strpos($opt_key, 'in_pro') ) ? 'disabled' : '' ?>><?php echo esc_html( $opt_value ); ?></option>
															<?php
														}
														?>
													</optgroup>
													<?php
												}
												?>
											</select>
										</td>
										<td class="select_condition_for_in_notin">
											<select name="fees[product_fees_conditions_is][]"
													class="product_fees_conditions_is product_fees_conditions_is_1">
												<?php
												/**
												 * Added dynamic function for operator list action.
												 *
												 * @since  3.8
												 *
												 * @author jb
												 */
												$opr_spe = $afrsm_admin_object->afrsm_operator_list_action( 'country' );
												foreach ( $opr_spe as $opr_key => $opr_value ) {
													?>
													<option value="<?php echo esc_attr( $opr_key ); ?>"><?php echo esc_html( $opr_value ); ?></option>
													<?php
												}
												?>
											</select>
										</td>
										<td id="column_1" class="condition-value" colspan="2">
											<?php
											echo wp_kses( $afrsm_admin_object->afrsm_pro_get_country_list( 1 ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
											?>
											<input type="hidden" name="condition_key[value_1][]" value="">
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<input type="hidden" name="total_row" id="total_row" value="<?php echo esc_attr( $i ); ?>">
					</div>
				</div>

				<?php // Advanced Pricing Section start  ?>
				<div id="apm_wrap" class="adv-pricing-rules element-shadow">
					<div class="ap_title sub-section">
						<h2><?php esc_html_e( 'Advanced Shipping Price Rules', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
						<label class="switch">
							<input type="checkbox" name="ap_rule_status" value="on" <?php echo esc_attr( $ap_rule_status ); ?>>
							<div class="slider round"></div>
						</label>
                        <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled this Advanced Pricing button only than below all rule\'s will go for apply to shipping method.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
					</div>

					<div class="pricing_rules">
						<div class="pricing_rules_tab">
							<ul class="tabs">
								<?php
								/**
								 * Added dynamic function for tab list action.
								 *
								 * @since  3.8
								 *
								 * @author jb
								 */
								$tab_array = $afrsm_admin_object->afrsm_advanced_tab_list_action();
								if ( ! empty( $tab_array ) ) {
									foreach ( $tab_array as $data_tab => $tab_title ) {
										if ( afrsfw_fs()->is__premium_only() ) {
											if ( afrsfw_fs()->can_use_premium_code() ) {
												if ( "tab-1" === $data_tab ) {
													$class = " current";
												} else {
													$class = "";
												}
											}else{
												if ( "tab-11" === $data_tab ) {
													$class = " current";
												} else {
													$class = "";
												}
											}
										}else{
                                            if ( "tab-11" === $data_tab ) {
												$class = " current";
											} else {
												$class = "";
											}
										}
										
										?>
										<li class="tab-link<?php echo esc_attr( $class ); ?>"
											data-tab="<?php echo esc_attr( $data_tab ); ?>">
											<?php echo esc_html( $tab_title ); ?>
										</li>
										<?php
									}
								}
								?>
							</ul>
						</div>

						<div class="pricing_rules_tab_content">
							<?php
							if ( afrsfw_fs()->is__premium_only() ) {
								if ( afrsfw_fs()->can_use_premium_code() ) { ?>
									<?php
									do_action( 'afrsm_ap_product_container_before', $get_post_id );
									?>
                                    <!-- Advanced Pricing Product start here -->
									<div class="ap_product_container advance_pricing_rule_box tab-content current" id="tab-1" data-title="<?php echo esc_attr( 'Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_product_list"></div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="product"
														data-qow="qty"
														data-filedtype="select"
														data-filedtitle2="prd"
														data-filedcategory="product_list"
														data-relatedtype="not_list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox" name="cost_on_product_status"
																	value="on" <?php echo esc_attr( $cost_on_product_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_product_rule_match]"
															id="cost_on_product_rule_match" class="arcmt_select">
														<option value="any" <?php selected( $cost_on_product_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_product_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/115-advanced-shipping-price-rules-shipping-cost-on-product'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_product_method" class="tbl_product_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_product_fees_conditions_condition" scope="row">
														<span><?php esc_html_e( 'Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the shipping amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product quantity per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product quantity per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();

												if ( ! empty( $sm_metabox_ap_product ) && is_array( $sm_metabox_ap_product ) ):
													foreach ( $sm_metabox_ap_product as $app_arr ) {
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $app_arr ) || '' !== $app_arr ) {
															if ( ( '' !== $app_arr['ap_fees_products'] && '' !== $app_arr['ap_fees_ap_price_product'] ) && ( '' !== $app_arr['ap_fees_ap_prd_min_qty'] || '' !== $app_arr['ap_fees_ap_prd_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $app_arr;
															}
														}
													}
												endif;

												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_product = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_products    = isset( $productfees['ap_fees_products'] ) ? $productfees['ap_fees_products'] : '';
														$ap_fees_ap_min_qty       = isset( $productfees['ap_fees_ap_prd_min_qty'] ) ? $productfees['ap_fees_ap_prd_min_qty'] : '';
														$ap_fees_ap_max_qty       = isset( $productfees['ap_fees_ap_prd_max_qty'] ) ? $productfees['ap_fees_ap_prd_max_qty'] : '';
														$ap_fees_ap_price_product = isset( $productfees['ap_fees_ap_price_product'] ) ? $productfees['ap_fees_ap_price_product'] : '';
														?>
														<tr id="ap_product_row_<?php echo esc_attr( $cnt_product ); ?>"
															valign="top" class="ap_product_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_product ); ?>"
																		id="ap_product_fees_conditions_condition_<?php echo esc_attr( $cnt_product ); ?>"
																		name="fees[ap_product_fees_conditions_condition][<?php echo esc_attr( $cnt_product ); ?>][]"
																		id="ap_product_fees_conditions_condition"
																		class="ap_list ap_product product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_product_options( $cnt_product, $fees_ap_fees_products ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_prd_min_qty][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_prd_min_qty[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_min_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_prd_max_qty][]"
																		class="text-class qty-class qty-class max-val-class"
																		id="ap_fees_ap_prd_max_qty[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_max_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_product][]"
																		class="price-val-class text-class number-field"
																		id="ap_fees_ap_price_product[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_product ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_product, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
                                                                <a id="ap-product-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-product-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_product ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_product ++;
													}
													?>
													<?php
												} else {
													$cnt_product = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_product" id="total_row_product"
													value="<?php echo esc_attr( $cnt_product ); ?>">
										</div>
									</div>
                                    <!-- Advanced Pricing Product end here -->
									<?php
									do_action( 'afrsm_ap_product_container_after', $get_post_id );

									do_action( 'afrsm_ap_product_subtotal_container_before', $get_post_id );
									?>
									<!-- Advanced Pricing Product Subtotal start here -->
									<div class="ap_product_subtotal_container advance_pricing_rule_box tab-content" id="tab-2" data-title="<?php esc_attr_e( 'Cost on Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_cart_subtotal">
													<option value="product_subtotal"><?php esc_html_e( 'Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="product_subtotal"
														data-qow="subtotal"
														data-filedtype="select"
														data-filedtitle2="product_subtotal"
														data-filedcategory="product_list"
														data-relatedtype="not_list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_product_subtotal_status"
																	value="on" <?php echo esc_attr( $cost_on_product_subtotal_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_product_subtotal_rule_match]"
															id="cost_on_product_subtotal_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_product_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_product_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/161-advanced-shipping-price-rules-shipping-cost-based-on-product-subtotal'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_product_subtotal_method" class="tbl_product_subtotal table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Product Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart subtotal per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
														<span><?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart subtotal per row before the shipping amount is
														applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_product_subtotal = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_product_subtotal ) && is_array( $sm_metabox_ap_product_subtotal ) ):
													foreach ( $sm_metabox_ap_product_subtotal as $apcat_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apcat_arr ) || $apcat_arr !== '' ) {
															if (
																( $apcat_arr['ap_fees_product_subtotal'] !== '' && $apcat_arr['ap_fees_ap_price_product_subtotal'] !== '' ) &&
																( $apcat_arr['ap_fees_ap_product_subtotal_min_subtotal'] !== '' || $apcat_arr['ap_fees_ap_product_subtotal_max_subtotal'] !== '' )
															) {
																$filled_product_subtotal[] = $apcat_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_product_subtotal ) && ! empty( $filled_product_subtotal ) ) {
													$cnt_product_subtotal = 2;
													foreach ( $filled_product_subtotal as $key => $productfees ) {
														$fees_ap_fees_product_subtotal            = isset( $productfees['ap_fees_product_subtotal'] ) ? $productfees['ap_fees_product_subtotal'] : '';
														$ap_fees_ap_product_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_product_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_product_subtotal_min_subtotal'] : '';
														$ap_fees_ap_product_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_product_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_product_subtotal_max_subtotal'] : '';
														$ap_fees_ap_price_product_subtotal        = isset( $productfees['ap_fees_ap_price_product_subtotal'] ) ? $productfees['ap_fees_ap_price_product_subtotal'] : '';
														?>
														<tr id="ap_product_subtotal_row_<?php echo esc_attr( $cnt_product_subtotal ); ?>"
															valign="top" class="ap_product_subtotal_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_product_subtotal ); ?>"
																		id="ap_product_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_product_subtotal ); ?>"
																		name="fees[ap_product_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_product_subtotal ); ?>][]"
																		class="ap_list ap_product product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_product_options( $cnt_product_subtotal, $fees_ap_fees_product_subtotal ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_product_subtotal_min_subtotal][]"
																		class="text-class price-class min-val-class subtotal-class"
																		id="ap_fees_ap_product_subtotal_min_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		step="0.01"
																		value="<?php echo esc_attr( $ap_fees_ap_product_subtotal_min_subtotal ); ?>"
																		min="0.0">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_product_subtotal_max_subtotal][]"
																		class="text-class price-class max-val-class subtotal-class"
																		id="ap_fees_ap_product_subtotal_max_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		step="0.01"
																		value="<?php echo esc_attr( $ap_fees_ap_product_subtotal_max_subtotal ); ?>"
																		min="0.0">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_product_subtotal][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_product_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_product_subtotal ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_product_subtotal, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
                                                                <a id="ap-product-subtotal-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-product-subtotal-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_product_subtotal ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_product_subtotal ++;
													}
													?>
													<?php
												} else {
													$cnt_product_subtotal = 1;
												} ?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_product_subtotal"
													id="total_row_product_subtotal"
													value="<?php echo esc_attr( $cnt_product_subtotal ); ?>">
											<!-- Advanced Pricing Category Section end here -->
										</div>
									</div>
									<!-- Advanced Pricing Product Subtotal end here -->
									<?php
									do_action( 'afrsm_ap_product_subtotal_container_after', $get_post_id );

									do_action( 'afrsm_ap_product_weight_container_before', $get_post_id );
									?>
                                    <!-- Advanced Pricing Product Weight start here -->
									<div class="ap_product_weight_container advance_pricing_rule_box tab-content" id="tab-3" data-title="<?php esc_attr_e( 'Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_product_weight_list"></div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Product Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="product_weight"
														data-qow="weight"
														data-filedtype="select"
														data-filedtitle2="product_weight"
														data-filedcategory="product_list"
														data-relatedtype="not_list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_product_weight_status"
																	value="on" <?php echo esc_attr( $cost_on_product_weight_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_product_weight_rule_match]"
															id="cost_on_product_weight_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_product_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_product_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/119-advanced-shipping-price-rules-shipping-cost-on-product-weight'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_product_weight_method" class="tbl_product_weight_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_product_weight_fees_conditions_condition" scope="row">
														<span><?php esc_html_e( 'Product', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the shipping amount to when the min/max weight match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_product_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product weight per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product weight per row before the shipping amount is applied. Leave empty then will set with infinte', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_weight_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$product_weight_filled_arr = array();
												if ( ! empty( $sm_metabox_ap_product_weight ) && is_array( $sm_metabox_ap_product_weight ) ):
													foreach ( $sm_metabox_ap_product_weight as $app_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $app_arr ) || '' !== $app_arr ) {
															if ( ( '' !== $app_arr['ap_fees_product_weight'] && '' !== $app_arr['ap_fees_ap_price_product_weight'] ) && ( '' !== $app_arr['ap_fees_ap_product_weight_min_qty'] || '' !== $app_arr['ap_fees_ap_product_weight_max_qty'] ) ) {
																//if condition match than fill in array
																$product_weight_filled_arr[] = $app_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $product_weight_filled_arr ) && ! empty( $product_weight_filled_arr ) ) {
													$cnt_product_weight = 2;
													foreach ( $product_weight_filled_arr as $key => $product_weight_fees ) {
														$fees_ap_fees_product_weight     = isset( $product_weight_fees['ap_fees_product_weight'] ) ? $product_weight_fees['ap_fees_product_weight'] : '';
														$ap_fees_product_weight_min_qty  = isset( $product_weight_fees['ap_fees_ap_product_weight_min_qty'] ) ? $product_weight_fees['ap_fees_ap_product_weight_min_qty'] : '';
														$ap_fees_product_weight_max_qty  = isset( $product_weight_fees['ap_fees_ap_product_weight_max_qty'] ) ? $product_weight_fees['ap_fees_ap_product_weight_max_qty'] : '';
														$ap_fees_ap_price_product_weight = isset( $product_weight_fees['ap_fees_ap_price_product_weight'] ) ? $product_weight_fees['ap_fees_ap_price_product_weight'] : '';
														?>
														<tr id="ap_product_weight_row_<?php echo esc_attr( $cnt_product_weight ); ?>"
															valign="top" class="ap_product_weight_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_product_weight ); ?>"
																		id="ap_product_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_product_weight ); ?>"
																		name="fees[ap_product_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_product_weight ); ?>][]"
																		id="ap_product_weight_fees_conditions_condition"
																		class="ap_list ap_product_weight product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_product_options( $cnt_product_weight, $fees_ap_fees_product_weight ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_product_weight_min_weight][]"
																		class="text-class weight-class min-val-class"
																		id="ap_fees_ap_product_weight_min_weight[]"
																		placeholder="<?php echo esc_attr( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_product_weight_min_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_product_weight_max_weight][]"
																		class="text-class weight-class max-val-class"
																		id="ap_fees_ap_product_weight_max_weight[]"
																		placeholder="<?php echo esc_attr( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_product_weight_max_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_product_weight][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_product_weight[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_product_weight ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_product_weight, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
                                                                <a id="ap-product-weight-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-product-weight-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_product_weight ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_product_weight ++;
													}
													?>
													<?php
												} else {
													$cnt_product_weight = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_product_weight"
													id="total_row_product_weight"
													value="<?php echo esc_attr( $cnt_product_weight ); ?>">
										</div>
									</div>
                                    <!-- Advanced Pricing Product Weight end here -->
									<?php
									do_action( 'afrsm_ap_product_weight_container_after', $get_post_id );

									do_action( 'afrsm_ap_category_container_before', $get_post_id );
									?>
									<!-- Advanced Pricing Category start here -->
									<div class="ap_category_container advance_pricing_rule_box tab-content" id="tab-4" data-title="<?php esc_attr_e( 'Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_category_list">
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_category_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="category"
														data-qow="qty"
														data-filedtype="select"
														data-filedtitle2="cat"
														data-filedcategory="category_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox" name="cost_on_category_status"
																	value="on" <?php echo esc_attr( $cost_on_category_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_category_rule_match]"
															id="cost_on_category_rule_match" class="arcmt_select">
														<option value="any" <?php selected( $cost_on_category_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_category_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/117-advanced-shipping-price-rules-shipping-cost-on-category'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_category_method" class="tbl_category_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_category_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a category to apply the shipping amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_category_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum category quantity per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_category_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum category quantity per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_category_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_category ) && is_array( $sm_metabox_ap_category ) ):
													foreach ( $sm_metabox_ap_category as $apcat_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
															if ( ( '' !== $apcat_arr['ap_fees_categories'] && '' !== $apcat_arr['ap_fees_ap_price_category'] ) &&
																	( '' !== $apcat_arr['ap_fees_ap_cat_min_qty'] || '' !== $apcat_arr['ap_fees_ap_cat_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $apcat_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_category = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_categories   = isset( $productfees['ap_fees_categories'] ) ? $productfees['ap_fees_categories'] : '';
														$ap_fees_ap_cat_min_qty    = isset( $productfees['ap_fees_ap_cat_min_qty'] ) ? $productfees['ap_fees_ap_cat_min_qty'] : '';
														$ap_fees_ap_cat_max_qty    = isset( $productfees['ap_fees_ap_cat_max_qty'] ) ? $productfees['ap_fees_ap_cat_max_qty'] : '';
														$ap_fees_ap_price_category = isset( $productfees['ap_fees_ap_price_category'] ) ? $productfees['ap_fees_ap_price_category'] : '';
														?>
														<tr id="ap_category_row_<?php echo esc_attr( $cnt_category ); ?>"
															valign="top"
															class="ap_category_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_category ); ?>"
																		id="ap_category_fees_conditions_condition_<?php echo esc_attr( $cnt_category ); ?>"
																		name="fees[ap_category_fees_conditions_condition][<?php echo esc_attr( $cnt_category ); ?>][]"
																		id="ap_category_fees_conditions_condition"
																		class="ap_not_list ap_category product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_category_options__premium_only( $fees_ap_fees_categories, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_cat_min_qty][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_cat_min_qty[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_cat_min_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_cat_max_qty][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_cat_max_qty[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_cat_max_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_category][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_category[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_category ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_category, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
                                                                <a id="ap-category-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-category-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_category ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_category ++;
													}
													?>
													<?php
												} else {
													$cnt_category = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_category" id="total_row_category"
													value="<?php echo esc_attr( $cnt_category ); ?>">
											<!-- Advanced Pricing Category Section end here -->
										</div>
									</div>
                                    <!-- Advanced Pricing Category end here -->
									<?php
									do_action( 'afrsm_ap_category_container_after', $get_post_id );
                                    
									do_action( 'afrsm_ap_category_subtotal_container_before', $get_post_id );
									?>
									<!-- Advanced Pricing Category Subtotal start here -->
									<div class="ap_category_subtotal_container advance_pricing_rule_box tab-content" id="tab-5" data-title="<?php esc_attr_e( 'Cost on Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_cart_subtotal">
													<option value="category_subtotal"><?php esc_html_e( 'Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="category_subtotal"
														data-qow="subtotal"
														data-filedtype="select"
														data-filedtitle2="category_subtotal"
														data-filedcategory="category_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_category_subtotal_status"
																	value="on" <?php echo esc_attr( $cost_on_category_subtotal_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_category_subtotal_rule_match]"
															id="cost_on_category_subtotal_rule_match" class="arcmt_select">
														<option value="any" <?php selected( $cost_on_category_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_category_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/163-advanced-shipping-price-rules-shipping-cost-on-category-subtotal'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_category_subtotal_method" class="tbl_category_subtotal table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Category Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart subtotal per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart subtotal per row before the shipping amount is
														applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_category_subtotal = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_category_subtotal ) && is_array( $sm_metabox_ap_category_subtotal ) ):
													foreach ( $sm_metabox_ap_category_subtotal as $apcat_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apcat_arr ) || $apcat_arr !== '' ) {
															if (
																( $apcat_arr['ap_fees_category_subtotal'] !== '' && $apcat_arr['ap_fees_ap_price_category_subtotal'] !== '' ) &&
																( $apcat_arr['ap_fees_ap_category_subtotal_min_subtotal'] !== '' || $apcat_arr['ap_fees_ap_category_subtotal_max_subtotal'] !== '' )
															) {
																$filled_category_subtotal[] = $apcat_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_category_subtotal ) && ! empty( $filled_category_subtotal ) ) {
													$cnt_category_subtotal = 2;
													foreach ( $filled_category_subtotal as $key => $productfees ) {
														$fees_ap_fees_category_subtotal            = isset( $productfees['ap_fees_category_subtotal'] ) ? $productfees['ap_fees_category_subtotal'] : '';
														$ap_fees_ap_category_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_category_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_category_subtotal_min_subtotal'] : '';
														$ap_fees_ap_category_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_category_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_category_subtotal_max_subtotal'] : '';
														$ap_fees_ap_price_category_subtotal        = isset( $productfees['ap_fees_ap_price_category_subtotal'] ) ? $productfees['ap_fees_ap_price_category_subtotal'] : '';
														?>
														<tr id="ap_category_subtotal_row_<?php echo esc_attr( $cnt_category_subtotal ); ?>"
															valign="top" class="ap_category_subtotal_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_category_subtotal ); ?>"
																		id="ap_category_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_category_subtotal ); ?>"
																		name="fees[ap_category_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_category_subtotal ); ?>][]"
																		id="ap_category_subtotal_fees_conditions_condition"
																		class="ap_not_list ap_category_subtotal product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_category_options__premium_only( $fees_ap_fees_category_subtotal, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_category_subtotal_min_subtotal][]"
																		class="text-class price-class min-val-class subtotal-class"
																		id="ap_fees_ap_category_subtotal_min_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		step="0.01"
																		value="<?php echo esc_attr( $ap_fees_ap_category_subtotal_min_subtotal ); ?>"
																		min="0.0">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_category_subtotal_max_subtotal][]"
																		class="text-class price-class max-val-class subtotal-class"
																		id="ap_fees_ap_category_subtotal_max_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		step="0.01"
																		value="<?php echo esc_attr( $ap_fees_ap_category_subtotal_max_subtotal ); ?>"
																		min="0.0">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_category_subtotal][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_category_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_category_subtotal ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_category_subtotal, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
                                                                <a id="ap-category-subtotal-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-category-subtotal-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_category_subtotal ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_category_subtotal ++;
													}
													?>
													<?php
												} else {
													$cnt_category_subtotal = 1;
												} ?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_category_subtotal"
													id="total_row_category_subtotal"
													value="<?php echo esc_attr( $cnt_category_subtotal ); ?>">
											<!-- Advanced Pricing Category Section end here -->

										</div>
									</div>
									<!-- Advanced Pricing Category Subtotal  end here -->
									<?php
									do_action( 'afrsm_ap_category_subtotal_container_after', $get_post_id );

									do_action( 'afrsm_ap_category_weight_container_before', $get_post_id );
                                    ?>
									<!-- Advanced Pricing Category Weight start here -->
									<div class="ap_category_weight_container advance_pricing_rule_box tab-content" id="tab-6" data-title="<?php esc_attr_e( 'Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_category_weight_list">
													<option value=""><?php esc_html_e( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_category_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Category Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="category_weight"
														data-qow="weight"
														data-filedtype="select"
														data-filedtitle2="category_weight"
														data-filedcategory="category_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_category_weight_status"
																	value="on" <?php echo esc_attr( $cost_on_category_weight_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_category_weight_rule_match]"
															id="cost_on_category_weight_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_category_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_category_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/120-advanced-shipping-price-rules-shipping-cost-on-category-weight'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_category_weight_method" class="tbl_category_weight_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_category_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Category', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a category to apply the shipping amount to when the min/max weight match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_category_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum category weight per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_category_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum category weight per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_category_weight_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr_cat_weight = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_category_weight ) && is_array( $sm_metabox_ap_category_weight ) ):
													foreach ( $sm_metabox_ap_category_weight as $apcat_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
															if ( ( '' !== $apcat_arr['ap_fees_categories_weight'] && '' !== $apcat_arr['ap_fees_categories_weight'] ) &&
																	( '' !== $apcat_arr['ap_fees_ap_category_weight_min_qty'] || '' !== $apcat_arr['ap_fees_ap_category_weight_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr_cat_weight[] = $apcat_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr_cat_weight ) && ! empty( $filled_arr_cat_weight ) ) {
													$cnt_category_weight = 2;
													foreach ( $filled_arr_cat_weight as $key => $productfees ) {
														$fees_ap_fees_categories_weight     = isset( $productfees['ap_fees_categories_weight'] ) ? $productfees['ap_fees_categories_weight'] : '';
														$ap_fees_ap_category_weight_min_qty = isset( $productfees['ap_fees_ap_category_weight_min_qty'] ) ? $productfees['ap_fees_ap_category_weight_min_qty'] : '';
														$ap_fees_ap_category_weight_max_qty = isset( $productfees['ap_fees_ap_category_weight_max_qty'] ) ? $productfees['ap_fees_ap_category_weight_max_qty'] : '';
														$ap_fees_ap_price_category_weight   = isset( $productfees['ap_fees_ap_price_category_weight'] ) ? $productfees['ap_fees_ap_price_category_weight'] : '';
														?>
														<tr id="ap_category_weight_row_<?php echo esc_attr( $cnt_category_weight ); ?>"
															valign="top" class="ap_category_weight_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_category_weight ); ?>"
																		id="ap_category_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_category_weight ); ?>"
																		name="fees[ap_category_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_category_weight ); ?>][]"
																		id="ap_category_weight_fees_conditions_condition"
																		class="ap_not_list ap_category_weight product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_category_options__premium_only( $fees_ap_fees_categories_weight, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_category_weight_min_weight][]"
																		class="text-class weight-class min-val-class"
																		id="ap_fees_ap_category_weight_min_weight[]"
																		placeholder="<?php echo esc_attr( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_category_weight_min_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_category_weight_max_weight][]"
																		class="text-class weight-class max-val-class"
																		id="ap_fees_ap_category_weight_max_weight[]"
																		placeholder="<?php echo esc_attr( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_category_weight_max_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_category_weight][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_category_weight[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_category_weight ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_category_weight, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
                                                                <a id="ap-category-weight-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-category-weight-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_category_weight ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_category_weight ++;
													}
													?>
													<?php
												} else {
													$cnt_category_weight = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_category_weight"
													id="total_row_category_weight"
													value="<?php echo esc_attr( $cnt_category_weight ); ?>">
											<!-- Advanced Pricing Category Section end here -->
										</div>
									</div>
                                    <!-- Advanced Pricing Category Weight end here -->
									<?php
									do_action( 'afrsm_ap_category_weight_container_after', $get_post_id );

                                    do_action( 'afrsm_ap_tag_container_before', $get_post_id );
									?>
									<!-- Advanced Pricing Tag start here -->
									<div class="ap_tag_container advance_pricing_rule_box tab-content" id="tab-7" data-title="<?php esc_attr_e( 'Cost on Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_tag_list">
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_tag_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="tag"
														data-qow="qty"
														data-filedtype="select"
														data-filedtitle2="tag"
														data-filedcategory="tag_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox" name="cost_on_tag_status"
																	value="on" <?php echo esc_attr( $cost_on_tag_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_tag_rule_match]"
															id="cost_on_tag_rule_match" class="arcmt_select">
														<option value="any" <?php selected( $cost_on_tag_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_tag_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/117-advanced-shipping-price-rules-shipping-cost-on-tag'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_tag_method" class="tbl_tag_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_tag_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a tag to apply the shipping amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_tag_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum tag quantity per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_tag_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum tag quantity per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_tag_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();
												//check if tag AP rules exist
												if ( ! empty( $sm_metabox_ap_tag ) && is_array( $sm_metabox_ap_tag ) ):
													foreach ( $sm_metabox_ap_tag as $aptag_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $aptag_arr ) || '' !== $aptag_arr ) {
															if ( ( '' !== $aptag_arr['ap_fees_tags'] && '' !== $aptag_arr['ap_fees_ap_price_tag'] ) &&
																	( '' !== $aptag_arr['ap_fees_ap_tag_min_qty'] || '' !== $aptag_arr['ap_fees_ap_tag_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $aptag_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_tag = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_tags   = isset( $productfees['ap_fees_tags'] ) ? $productfees['ap_fees_tags'] : '';
														$ap_fees_ap_tag_min_qty    = isset( $productfees['ap_fees_ap_tag_min_qty'] ) ? $productfees['ap_fees_ap_tag_min_qty'] : '';
														$ap_fees_ap_tag_max_qty    = isset( $productfees['ap_fees_ap_tag_max_qty'] ) ? $productfees['ap_fees_ap_tag_max_qty'] : '';
														$ap_fees_ap_price_tag = isset( $productfees['ap_fees_ap_price_tag'] ) ? $productfees['ap_fees_ap_price_tag'] : '';
														?>
														<tr id="ap_tag_row_<?php echo esc_attr( $cnt_tag ); ?>"
															valign="top"
															class="ap_tag_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_tag ); ?>"
																		id="ap_tag_fees_conditions_condition_<?php echo esc_attr( $cnt_tag ); ?>"
																		name="fees[ap_tag_fees_conditions_condition][<?php echo esc_attr( $cnt_tag ); ?>][]"
																		id="ap_tag_fees_conditions_condition"
																		class="ap_not_list ap_tag product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_tag_options__premium_only( $fees_ap_fees_tags, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_tag_min_qty][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_tag_min_qty[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_tag_min_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_tag_max_qty][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_tag_max_qty[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_tag_max_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_tag][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_tag[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_tag ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_tag, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag ); ?> condition-value">
                                                                <a id="ap-tag-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-tag-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_tag ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_tag ++;
													}
													?>
													<?php
												} else {
													$cnt_tag = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_tag" id="total_row_tag" value="<?php echo esc_attr( $cnt_tag ); ?>">
										</div>
									</div>
									<!-- Advanced Pricing Tag end here -->
									<?php
									do_action( 'afrsm_ap_tag_container_after', $get_post_id );

                                    do_action( 'afrsm_ap_tag_subtotal_container_before', $get_post_id );
									?>
									<!-- Advanced Pricing Tag Subtotal start here -->
									<div class="ap_tag_subtotal_container advance_pricing_rule_box tab-content" id="tab-8" data-title="<?php esc_attr_e( 'Cost on Tag Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_tag_list">
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_tag_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Tag Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="tag_subtotal"
														data-qow="subtotal"
														data-filedtype="select"
														data-filedtitle2="tag_subtotal"
														data-filedcategory="tag_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox" name="cost_on_tag_subtotal_status"
																	value="on" <?php echo esc_attr( $cost_on_tag_subtotal_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_tag_subtotal_rule_match]"
															id="cost_on_tag_subtotal_rule_match" class="arcmt_select">
														<option value="any" <?php selected( $cost_on_tag_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_tag_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/117-advanced-shipping-price-rules-shipping-cost-on-tag-subtotal'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_tag_subtotal_method" class="tbl_tag_subtotal table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_tag_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a tag to apply the shipping amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_tag_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum tag subtotal per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_tag_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum tag subtotal per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_tag_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_tag_subtotal = array();
												//check if tag AP rules exist
												if ( ! empty( $sm_metabox_ap_tag_subtotal ) && is_array( $sm_metabox_ap_tag_subtotal ) ):
													foreach ( $sm_metabox_ap_tag_subtotal as $aptag_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $aptag_arr ) || '' !== $aptag_arr ) {
															if ( ( '' !== $aptag_arr['ap_fees_tag_subtotal'] && '' !== $aptag_arr['ap_fees_ap_price_tag_subtotal'] ) &&
																	( '' !== $aptag_arr['ap_fees_ap_tag_subtotal_min_subtotal'] || '' !== $aptag_arr['ap_fees_ap_tag_subtotal_max_subtotal'] ) ) {
																//if condition match than fill in array
																$filled_tag_subtotal[] = $aptag_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_tag_subtotal ) && ! empty( $filled_tag_subtotal ) ) {
													$cnt_tag_subtotal = 2;
													foreach ( $filled_tag_subtotal as $key => $productfees ) {
														$fees_ap_fees_tag_subtotal   = isset( $productfees['ap_fees_tag_subtotal'] ) ? $productfees['ap_fees_tag_subtotal'] : '';
														$ap_fees_ap_tag_subtotal_min_subtotal    = isset( $productfees['ap_fees_ap_tag_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_tag_subtotal_min_subtotal'] : '';
														$ap_fees_ap_tag_subtotal_max_subtotal    = isset( $productfees['ap_fees_ap_tag_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_tag_subtotal_max_subtotal'] : '';
														$ap_fees_ap_price_tag_subtotal = isset( $productfees['ap_fees_ap_price_tag_subtotal'] ) ? $productfees['ap_fees_ap_price_tag_subtotal'] : '';
														?>
														<tr id="ap_tag_subtotal_row_<?php echo esc_attr( $cnt_tag_subtotal ); ?>"
															valign="top"
															class="ap_tag_subtotal_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_tag_subtotal ); ?>"
																		id="ap_tag_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_tag_subtotal ); ?>"
																		name="fees[ap_tag_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_tag_subtotal ); ?>][]"
																		id="ap_tag_subtotal_fees_conditions_condition"
																		class="ap_not_list ap_tag_subtotal product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_tag_options__premium_only( $fees_ap_fees_tag_subtotal, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_tag_subtotal_min_subtotal][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_tag_subtotal_min_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_tag_subtotal_min_subtotal ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_tag_subtotal_max_subtotal][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_tag_subtotal_max_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_tag_subtotal_max_subtotal ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_subtotal ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_tag_subtotal][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_tag_subtotal[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_tag_subtotal ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_tag_subtotal, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_subtotal ); ?> condition-value">
                                                                <a id="ap-tag-subtotal-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-tag-subtotal-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_tag_subtotal ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_tag_subtotal ++;
													}
													?>
													<?php
												} else {
													$cnt_tag_subtotal = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_tag_subtotal" id="total_row_tag_subtotal" value="<?php echo esc_attr( $cnt_tag_subtotal ); ?>">
										</div>
									</div>
									<!-- Advanced Pricing Tag Subtotal end here -->
									<?php
									do_action( 'afrsm_ap_tag_subtotal_container_after', $get_post_id );

                                    do_action( 'afrsm_ap_tag_weight_container_before', $get_post_id );
                                    ?>
									<!-- Advanced Pricing Tag Weight start here -->
									<div class="ap_tag_weight_container advance_pricing_rule_box tab-content" id="tab-9" data-title="<?php esc_attr_e( 'Cost on Tag Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_tag_weight_list">
													<option value=""><?php esc_html_e( 'Select Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_tag_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Tag Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="tag_weight"
														data-qow="weight"
														data-filedtype="select"
														data-filedtitle2="tag_weight"
														data-filedcategory="tag_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_tag_weight_status"
																	value="on" <?php echo esc_attr( $cost_on_tag_weight_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_tag_weight_rule_match]"
															id="cost_on_tag_weight_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_tag_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_tag_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/120-advanced-shipping-price-rules-shipping-cost-on-category-weight'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_tag_weight_method" class="tbl_tag_weight_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_tag_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Tag', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a tag to apply the shipping amount to when the min/max weight match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_tag_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum tag weight per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_tag_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum tag weight per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_tag_weight_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr_cat_weight = array();
												//check if Tag AP rules exist
												if ( ! empty( $sm_metabox_ap_tag_weight ) && is_array( $sm_metabox_ap_tag_weight ) ):
													foreach ( $sm_metabox_ap_tag_weight as $apcat_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
															if ( ( '' !== $apcat_arr['ap_fees_tag_weight'] && '' !== $apcat_arr['ap_fees_tag_weight'] ) &&
																	( '' !== $apcat_arr['ap_fees_ap_tag_weight_min_qty'] || '' !== $apcat_arr['ap_fees_ap_tag_weight_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr_cat_weight[] = $apcat_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr_cat_weight ) && ! empty( $filled_arr_cat_weight ) ) {
													$cnt_tag_weight = 2;
													foreach ( $filled_arr_cat_weight as $key => $productfees ) {
														$fees_ap_fees_tag_weight        = isset( $productfees['ap_fees_tag_weight'] ) ? $productfees['ap_fees_tag_weight'] : '';
														$ap_fees_ap_tag_weight_min_qty  = isset( $productfees['ap_fees_ap_tag_weight_min_qty'] ) ? $productfees['ap_fees_ap_tag_weight_min_qty'] : '';
														$ap_fees_ap_tag_weight_max_qty  = isset( $productfees['ap_fees_ap_tag_weight_max_qty'] ) ? $productfees['ap_fees_ap_tag_weight_max_qty'] : '';
														$ap_fees_ap_price_tag_weight    = isset( $productfees['ap_fees_ap_price_tag_weight'] ) ? $productfees['ap_fees_ap_price_tag_weight'] : '';
														?>
														<tr id="ap_tag_weight_row_<?php echo esc_attr( $cnt_tag_weight ); ?>"
															valign="top" class="ap_tag_weight_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_tag_weight ); ?>"
																		id="ap_tag_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_tag_weight ); ?>"
																		name="fees[ap_tag_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_tag_weight ); ?>][]"
																		id="ap_tag_weight_fees_conditions_condition"
																		class="ap_not_list ap_tag_weight product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_tag_options__premium_only( $fees_ap_fees_tag_weight, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_tag_weight_min_weight][]"
																		class="text-class weight-class min-val-class"
																		id="ap_fees_ap_tag_weight_min_weight[]"
																		placeholder="<?php echo esc_attr( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_tag_weight_min_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_tag_weight_max_weight][]"
																		class="text-class weight-class max-val-class"
																		id="ap_fees_ap_tag_weight_max_weight[]"
																		placeholder="<?php echo esc_attr( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_tag_weight_max_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_tag_weight][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_tag_weight[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_tag_weight ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_tag_weight, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_tag_weight ); ?> condition-value">
                                                                <a id="ap-tag-weight-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-tag-weight-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_tag_weight ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_tag_weight ++;
													}
													?>
													<?php
												} else {
													$cnt_tag_weight = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_tag_weight"
													id="total_row_tag_weight"
													value="<?php echo esc_attr( $cnt_tag_weight ); ?>">
											<!-- Advanced Pricing Category Section end here -->
										</div>
									</div>
                                    <!-- Advanced Pricing Tag Weight end here -->
									<?php
									do_action( 'afrsm_ap_category_weight_container_after', $get_post_id );

									do_action( 'afrsm_ap_total_cart_container_before', $get_post_id );
									// Advanced Pricing Total QTY start here
									?>
									<div class="ap_total_cart_container advance_pricing_rule_box tab-content" id="tab-10" data-title="<?php esc_attr_e( 'Cost on Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_cart_qty">
													<option value="total_cart_qty"><?php esc_html_e( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="total_cart_qty"
														data-qow="qty"
														data-filedtype="label"
														data-filedtitle2="total_cart_qty"
														data-filedcategory=""
														data-relatedtype=""
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_total_cart_qty_status"
																	value="on" <?php echo esc_attr( $cost_on_total_cart_qty_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_total_cart_qty_rule_match]"
															id="cost_on_total_cart_qty_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_total_cart_qty_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_total_cart_qty_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/118-advanced-shipping-price-rules-shipping-cost-on-total-cart-qty'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_total_cart_qty_method" class="tbl_total_cart_qty table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Total Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart quantity per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart quantity per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_total_cart_qty = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_total_cart_qty ) && is_array( $sm_metabox_ap_total_cart_qty ) ):
													foreach ( $sm_metabox_ap_total_cart_qty as $apcat_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
															if ( ( '' !== $apcat_arr['ap_fees_total_cart_qty'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_qty'] ) &&
																	( '' !== $apcat_arr['ap_fees_ap_total_cart_qty_min_qty'] || '' !== $apcat_arr['ap_fees_ap_total_cart_qty_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_total_cart_qty[] = $apcat_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_total_cart_qty ) && ! empty( $filled_total_cart_qty ) ) {
													$cnt_total_cart_qty = 2;
													foreach ( $filled_total_cart_qty as $key => $productfees ) {
														$ap_fees_ap_total_cart_qty_min_qty = isset( $productfees['ap_fees_ap_total_cart_qty_min_qty'] ) ? $productfees['ap_fees_ap_total_cart_qty_min_qty'] : '';
														$ap_fees_ap_total_cart_qty_max_qty = isset( $productfees['ap_fees_ap_total_cart_qty_max_qty'] ) ? $productfees['ap_fees_ap_total_cart_qty_max_qty'] : '';
														$ap_fees_ap_price_total_cart_qty   = isset( $productfees['ap_fees_ap_price_total_cart_qty'] ) ? $productfees['ap_fees_ap_price_total_cart_qty'] : '';
														?>
														<tr id="ap_total_cart_qty_row_<?php echo esc_attr( $cnt_total_cart_qty ); ?>"
															valign="top" class="ap_total_cart_qty_row_tr">
															<td class="titledesc" scope="row">
																<label><?php echo esc_html_e( 'Cart Qty', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
																<input type="hidden"
																		name="fees[ap_total_cart_qty_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_qty ); ?>][]"
																		id="ap_total_cart_qty_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_total_cart_qty_min_qty][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_total_cart_qty_min_qty[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_total_cart_qty_min_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_total_cart_qty_max_qty][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_total_cart_qty_max_qty[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_total_cart_qty_max_qty ); ?>">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_total_cart_qty][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_total_cart_qty[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_qty ); ?>"
																		min="1">
																<?php
																$first_char = substr( $ap_fees_ap_price_total_cart_qty, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
                                                                <a id="ap-total-cart-qty-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-total-cart-qty-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_total_cart_qty ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_total_cart_qty ++;
													}
													?>
													<?php
												} else {
													$cnt_total_cart_qty = 1;
													?>
												<?php }
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_total_cart_qty"
													id="total_row_total_cart_qty"
													value="<?php echo esc_attr( $cnt_total_cart_qty ); ?>">
											<!-- Advanced Pricing Category Section end here -->
										</div>
									</div>
									<?php
                                    // Advanced Pricing Total QTY end here
									do_action( 'afrsm_ap_total_cart_container_after', $get_post_id );
									?>
								<?php
								}
							} ?>	
							<?php
							if ( afrsfw_fs()->is__premium_only() ) {
								if ( afrsfw_fs()->can_use_premium_code() ) { 
									$current_class_free = "";
								}else{
									$current_class_free = "current";
								}
							}else{
								$current_class_free = "current";
							}
							do_action( 'afrsm_ap_total_cart_weight_container_before', $get_post_id );
							// Advanced Pricing Total Cart Weight start here
							?>
							<div class="ap_total_cart_weight_container advance_pricing_rule_box tab-content <?php echo esc_attr( $current_class_free ); ?>" id="tab-11" data-title="<?php esc_attr_e( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
								<div class="tap-class">
									<div class="predefined_elements">
										<div id="all_cart_weight">
											<option value="total_cart_weight"><?php esc_html_e( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										</div>
									</div>
									<div class="sub-title">
										<h2 class="ap-title"><?php esc_html_e( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
										<div class="tap">
											<a id="ap-add-field"
												data-filedtitle="total_cart_weight"
												data-qow="weight"
												data-filedtype="label"
												data-filedtitle2="total_cart_weight"
												data-filedcategory=""
												data-relatedtype=""
												class="button"
												href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
											<div class="switch_status_div">
												<label class="switch switch_in_pricing_rules">
													<input type="checkbox"
															name="cost_on_total_cart_weight_status"
															value="on" <?php echo esc_attr( $cost_on_total_cart_weight_status ); ?>>
													<div class="slider round"></div>
												</label>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
											</div>
										</div>
										<div class="advance_rule_condition_match_type">
											<p class="switch_in_pricing_rules_description_left">
												<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
											</p>
											<select name="cost_rule_match[cost_on_total_cart_weight_rule_match]"
													id="cost_on_total_cart_weight_rule_match"
													class="arcmt_select">
												<option value="any" <?php selected( $cost_on_total_cart_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
												<option value="all" <?php selected( $cost_on_total_cart_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
											</select>
											<p class="switch_in_pricing_rules_description">
												<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
											</p>
										</div>
										<div class="advance_rule_condition_help">
											<span class="dashicons dashicons-info-outline"></span>
											<a href="<?php echo esc_url('https://docs.thedotstore.com/article/122-advanced-shipping-price-rules-shipping-cost-on-total-cart-weight'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
										</div>
									</div>
									<table id="tbl_ap_total_cart_weight_method" class="tbl_total_cart_weight table-outer tap-cas form-table advance-shipping-method-table">
										<tbody>
										<tr class="heading">
											<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row">
                                                <span><?php esc_html_e( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
											</th>
											<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row">
                                                <span><?php esc_html_e( 'Min Weight ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart weight per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                            </th>
											<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row">
                                                <span><?php esc_html_e( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart weight per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                            </th>
											<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row" colspan="2">
                                                <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
											</th>
										</tr>
										<?php
										//check advanced pricing value fill proper or unset if not
										$filled_total_cart_weight = array();
										//check if category AP rules exist
										if ( ! empty( $sm_metabox_ap_total_cart_weight ) && is_array( $sm_metabox_ap_total_cart_weight ) ):
											foreach ( $sm_metabox_ap_total_cart_weight as $apcat_arr ):
												//check that if required field fill or not once save the APR,  if match than fill in array
												if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
													if ( ( '' !== $apcat_arr['ap_fees_total_cart_weight'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_weight'] ) &&
															( '' !== $apcat_arr['ap_fees_ap_total_cart_weight_min_weight'] || '' !== $apcat_arr['ap_fees_ap_total_cart_weight_max_weight'] ) ) {
														//if condition match than fill in array
														$filled_total_cart_weight[] = $apcat_arr;
													}
												}
											endforeach;
										endif;
										//check APR exist
										if ( isset( $filled_total_cart_weight ) && ! empty( $filled_total_cart_weight ) ) {
											$cnt_total_cart_weight = 2;
											foreach ( $filled_total_cart_weight as $key => $productfees ) {
												$ap_fees_ap_total_cart_weight_min_weight = isset( $productfees['ap_fees_ap_total_cart_weight_min_weight'] ) ? $productfees['ap_fees_ap_total_cart_weight_min_weight'] : '';
												$ap_fees_ap_total_cart_weight_max_weight = isset( $productfees['ap_fees_ap_total_cart_weight_max_weight'] ) ? $productfees['ap_fees_ap_total_cart_weight_max_weight'] : '';
												$ap_fees_ap_price_total_cart_weight      = isset( $productfees['ap_fees_ap_price_total_cart_weight'] ) ? $productfees['ap_fees_ap_price_total_cart_weight'] : '';
												?>
												<tr id="ap_total_cart_weight_row_<?php echo esc_attr( $cnt_total_cart_weight ); ?>"
													valign="top" class="ap_total_cart_weight_row_tr">
													<td class="titledesc" scope="row">
														<label><?php echo esc_html_e( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
														<input type="hidden"
																name="fees[ap_total_cart_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_weight ); ?>][]"
																id="ap_total_cart_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_weight ); ?>">
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
														<input type="text"
																name="fees[ap_fees_ap_total_cart_weight_min_weight][]"
																class="text-class weight-class min-val-class"
																id="ap_fees_ap_total_cart_weight_min_weight[]"
																placeholder="<?php echo esc_attr( 'Min weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																value="<?php echo esc_attr( $ap_fees_ap_total_cart_weight_min_weight ); ?>">
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
														<input type="text"
																name="fees[ap_fees_ap_total_cart_weight_max_weight][]"
																class="text-class weight-class max-val-class"
																id="ap_fees_ap_total_cart_weight_max_weight[]"
																placeholder="<?php echo esc_attr( 'Max weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																value="<?php echo esc_attr( $ap_fees_ap_total_cart_weight_max_weight ); ?>">
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
														<input type="text"
																name="fees[ap_fees_ap_price_total_cart_weight][]"
																class="text-class number-field price-val-class"
																id="ap_fees_ap_price_total_cart_weight[]"
																placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_weight ); ?>">
														<?php
														$first_char = substr( $ap_fees_ap_price_total_cart_weight, 0, 1 );
														if ( '-' === $first_char ) {
															$html = sprintf(
																'<p><b style="color: red;">%s</b>%s',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
															);
															echo wp_kses_post( $html );
														}
														?>
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
                                                        <a id="ap-total-cart-weight-clone-field" 
                                                            rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                            title="Clone" 
                                                            class="ap-clone-row" 
                                                            href="javascript:;">
                                                            <i class="fa fa-clone"></i>
                                                        </a>
														<a id="ap-total-cart-weight-delete-field"
															rel-id="<?php echo esc_attr( $cnt_total_cart_weight ); ?>"
															title="Delete" class="delete-row"
															href="javascript:;">
															<i class="dashicons dashicons-trash"></i>
														</a>
													</td>
												</tr>
												<?php
												$cnt_total_cart_weight ++;
											}
											?>
											<?php
										} else {
											$cnt_total_cart_weight = 1;
										}
										?>
										</tbody>
									</table>
									<input type="hidden" name="total_row_total_cart_weight"
											id="total_row_total_cart_weight"
											value="<?php echo esc_attr( $cnt_total_cart_weight ); ?>">
									<!-- Advanced Pricing Category Section end here -->
								</div>
							</div>
							<?php
							//Advanced Pricing Total Cart Weight end here
							do_action( 'afrsm_ap_total_cart_weight_container_after', $get_post_id );

							do_action( 'afrsm_ap_total_cart_subtotal_container_before', $get_post_id );
							?>
							<!-- Advanced Pricing Total Cart Subtotal start here -->
							<div class="ap_total_cart_subtotal_container advance_pricing_rule_box tab-content" id="tab-12" data-title="<?php esc_attr_e( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
								<div class="tap-class">
									<div class="predefined_elements">
										<div id="all_cart_subtotal">
											<option value="total_cart_subtotal"><?php esc_html_e( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
										</div>
									</div>
									<div class="sub-title">
										<h2 class="ap-title"><?php esc_html_e( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
										<div class="tap">
											<a id="ap-add-field"
												data-filedtitle="total_cart_subtotal"
												data-qow="subtotal"
												data-filedtype="label"
												data-filedtitle2="total_cart_subtotal"
												data-filedcategory=""
												data-relatedtype=""
												class="button"
												href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
											<div class="switch_status_div">
												<label class="switch switch_in_pricing_rules">
													<input type="checkbox"
															name="cost_on_total_cart_subtotal_status"
															value="on" <?php echo esc_attr( $cost_on_total_cart_subtotal_status ); ?>>
													<div class="slider round"></div>
												</label>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
											</div>
										</div>
										<div class="advance_rule_condition_match_type">
											<p class="switch_in_pricing_rules_description_left">
												<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
											</p>
											<select name="cost_rule_match[cost_on_total_cart_subtotal_rule_match]"
													id="cost_on_total_cart_subtotal_rule_match"
													class="arcmt_select">
												<option value="any" <?php selected( $cost_on_total_cart_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
												<option value="all" <?php selected( $cost_on_total_cart_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
											</select>
											<p class="switch_in_pricing_rules_description">
												<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
											</p>
										</div>
									</div>
									<table id="tbl_ap_total_cart_subtotal_method" class="tbl_total_cart_subtotal table-outer tap-cas form-table advance-shipping-method-table">
										<tbody>
										<tr class="heading">
											<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row">
                                                <span><?php esc_html_e( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
											</th>
											<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row">
                                                <span><?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart subtotal per row before the shipping amount is
												applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                            </th>
											<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row">
                                                <span><?php esc_html_e( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart subtotal per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                            </th>
											<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
											</th>
										</tr>
										<?php
										//check advanced pricing value fill proper or unset if not
										$filled_total_cart_subtotal = array();
										//check if category AP rules exist
										if ( ! empty( $sm_metabox_ap_total_cart_subtotal ) && is_array( $sm_metabox_ap_total_cart_subtotal ) ):
											foreach ( $sm_metabox_ap_total_cart_subtotal as $apcat_arr ):
												//check that if required field fill or not once save the APR,  if match than fill in array
												if ( ! empty( $apcat_arr ) || $apcat_arr !== '' ) {
													if (
														( $apcat_arr['ap_fees_total_cart_subtotal'] !== '' && $apcat_arr['ap_fees_ap_price_total_cart_subtotal'] !== '' ) &&
														( $apcat_arr['ap_fees_ap_total_cart_subtotal_min_subtotal'] !== '' || $apcat_arr['ap_fees_ap_total_cart_subtotal_max_subtotal'] !== '' )
													) {
														$filled_total_cart_subtotal[] = $apcat_arr;
													}
												}
											endforeach;
										endif;
										//check APR exist
										if ( isset( $filled_total_cart_subtotal ) && ! empty( $filled_total_cart_subtotal ) ) {
											$cnt_total_cart_subtotal = 2;
											foreach ( $filled_total_cart_subtotal as $key => $productfees ) {
												$ap_fees_ap_total_cart_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_total_cart_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_total_cart_subtotal_min_subtotal'] : '';
												$ap_fees_ap_total_cart_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_total_cart_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_total_cart_subtotal_max_subtotal'] : '';
												$ap_fees_ap_price_total_cart_subtotal        = isset( $productfees['ap_fees_ap_price_total_cart_subtotal'] ) ? $productfees['ap_fees_ap_price_total_cart_subtotal'] : '';
												?>
												<tr id="ap_total_cart_subtotal_row_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>"
													valign="top" class="ap_total_cart_subtotal_row_tr">
													<td class="titledesc" scope="row">
														<label><?php echo esc_html_e( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></label>
														<input type="hidden"
																name="fees[ap_total_cart_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>][]"
																id="ap_total_cart_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>">
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
														<input type="number"
																name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]"
																class="text-class price-class min-val-class subtotal-class"
																id="ap_fees_ap_total_cart_subtotal_min_subtotal[]"
																placeholder="<?php echo esc_attr( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																step="0.01"
																value="<?php echo esc_attr( $ap_fees_ap_total_cart_subtotal_min_subtotal ); ?>"
																min="0.0">
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
														<input type="number"
																name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]"
																class="text-class price-class max-val-class subtotal-class"
																id="ap_fees_ap_total_cart_subtotal_max_subtotal[]"
																placeholder="<?php echo esc_attr( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																step="0.01"
																value="<?php echo esc_attr( $ap_fees_ap_total_cart_subtotal_max_subtotal ); ?>"
																min="0.0">
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
														<input type="text"
																name="fees[ap_fees_ap_price_total_cart_subtotal][]"
																class="text-class number-field price-val-class"
																id="ap_fees_ap_price_total_cart_subtotal[]"
																placeholder="<?php echo esc_attr( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_subtotal ); ?>">
														<?php
														$first_char = substr( $ap_fees_ap_price_total_cart_subtotal, 0, 1 );
														if ( '-' === $first_char ) {
															$html = sprintf(
																'<p><b style="color: red;">%s</b>%s',
																esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
															);
															echo wp_kses_post( $html );
														}
														?>
													</td>
													<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
                                                        <a id="ap-total-cart-subtotal-clone-field" 
                                                            rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                            title="Clone" 
                                                            class="ap-clone-row" 
                                                            href="javascript:;">
                                                            <i class="fa fa-clone"></i>
                                                        </a>
														<a id="ap-total-cart-subtotal-delete-field"
															rel-id="<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>"
															title="Delete" class="delete-row"
															href="javascript:;">
															<i class="dashicons dashicons-trash"></i>
														</a>
													</td>
												</tr>
												<?php
												$cnt_total_cart_subtotal ++;
											}
											?>
											<?php
										} else {
											$cnt_total_cart_subtotal = 1;
										} ?>
										</tbody>
									</table>
									<input type="hidden" name="total_row_total_cart_subtotal"
											id="total_row_total_cart_subtotal"
											value="<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>">
									<!-- Advanced Pricing Category Section end here -->

								</div>
							</div>
							<!-- Advanced Pricing Total Cart Subtotal end here -->
							<?php
							do_action( 'afrsm_ap_total_cart_subtotal_container_after', $get_post_id ); 
                            
							if ( afrsfw_fs()->is__premium_only() ) {
								if ( afrsfw_fs()->can_use_premium_code() ) { ?>
									<?php 
                                    
                                    do_action( 'afrsm_ap_shipping_class_container_before', $get_post_id ); ?>
                                    <!-- Advanced Pricing Shipping Class start here -->
									<div class="ap_shipping_class_container advance_pricing_rule_box tab-content" id="tab-13" data-title="<?php esc_attr_e( 'Cost on Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_shipping_class_list">
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_class_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="shipping_class"
														data-qow="qty"
														data-filedtype="select"
														data-filedtitle2="shipping_class"
														data-filedcategory="shipping_class_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox" name="cost_on_shipping_class_status"
																	value="on" <?php echo esc_attr( $cost_on_shipping_class_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_shipping_class_rule_match]"
															id="cost_on_shipping_class_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_shipping_class_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_shipping_class_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/162-advanced-shipping-price-rules-shipping-cost-on-shipping-class-subtotal'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_shipping_class_method" class="tbl_shipping_class_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_shipping_class_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a shipping class to apply the shipping amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_shipping_class_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum shipping class cart quantity per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_shipping_class_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum shipping class cart quantity per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_shipping_class_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart quantity.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_shipping_class ) && is_array( $sm_metabox_ap_shipping_class ) ):
													foreach ( $sm_metabox_ap_shipping_class as $apscs_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apscs_arr ) || '' !== $apscs_arr ) {
															if ( ( '' !== $apscs_arr['ap_fees_shipping_classes'] && '' !== $apscs_arr['ap_fees_ap_price_shipping_class'] ) &&
																	( '' !== $apscs_arr['ap_fees_ap_shipping_class_min_qty'] || '' !== $apscs_arr['ap_fees_ap_shipping_class_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $apscs_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_shipping_class = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_shipping_classes          = isset( $productfees['ap_fees_shipping_classes'] ) ? $productfees['ap_fees_shipping_classes'] : '';
														$ap_fees_ap_shipping_class_min_qty 		= isset( $productfees['ap_fees_ap_shipping_class_min_qty'] ) ? $productfees['ap_fees_ap_shipping_class_min_qty'] : '';
														$ap_fees_ap_shipping_class_max_qty 		= isset( $productfees['ap_fees_ap_shipping_class_max_qty'] ) ? $productfees['ap_fees_ap_shipping_class_max_qty'] : '';
														$ap_fees_ap_price_shipping_class        = isset( $productfees['ap_fees_ap_price_shipping_class'] ) ? $productfees['ap_fees_ap_price_shipping_class'] : '';
														?>
														<tr id="ap_shipping_class_row_<?php echo esc_attr( $cnt_shipping_class ); ?>"
															valign="top"
															class="ap_shipping_class_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_shipping_class ); ?>"
																		id="ap_shipping_class_fees_conditions_condition_<?php echo esc_attr( $cnt_shipping_class ); ?>"
																		name="fees[ap_shipping_class_fees_conditions_condition][<?php echo esc_attr( $cnt_shipping_class ); ?>][]"
																		id="ap_shipping_class_fees_conditions_condition"
																		class="ap_shipping_class product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_class_options__premium_only( $fees_ap_fees_shipping_classes, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_shipping_class_min_qty][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_shipping_class_min_qty[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_shipping_class_min_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_shipping_class_max_qty][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_shipping_class_max_qty[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_shipping_class_max_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_shipping_class][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_shipping_class[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_shipping_class ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_shipping_class, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class ); ?> condition-value">
                                                                <a id="ap-shipping-class-subtotal-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-shipping-class-subtotal-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_shipping_class ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_shipping_class ++;
													}
													?>
													<?php
												} else {
													$cnt_shipping_class = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_shipping_class"
													id="total_row_shipping_class"
													value="<?php echo esc_attr( $cnt_shipping_class ); ?>">
										</div>
									</div>
                                    <!-- Advanced Pricing Shipping Class end here -->
									<?php 
                                    do_action( 'afrsm_ap_shipping_class_container_after', $get_post_id );


                                    do_action( 'afrsm_ap_shipping_class_weight_container_before', $get_post_id ); ?>
                                    <!-- Advanced Pricing Shipping Class Weight start here -->
									<div class="ap_shipping_class_weight_container advance_pricing_rule_box tab-content" id="tab-14" data-title="<?php esc_attr_e( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_shipping_class_list">
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_class_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Shipping Class Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="shipping_class_weight"
														data-qow="weight"
														data-filedtype="select"
														data-filedtitle2="shipping_class_weight"
														data-filedcategory="shipping_class_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_shipping_class_weight_status"
																	value="on" <?php echo esc_attr( $cost_on_shipping_class_weight_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_shipping_class_weight_rule_match]"
															id="cost_on_shipping_class_weight_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_shipping_class_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_shipping_class_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/162-advanced-shipping-price-rules-shipping-cost-on-shipping-class-subtotal'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_shipping_class_weight_method" class="tbl_shipping_class_weight_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_shipping_class_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a shipping class to apply the shipping amount to when the min/max weight match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_shipping_class_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum shipping class cart weight per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_shipping_class_weight_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum shipping class cart weight per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_shipping_class_weight_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart weight.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_shipping_class_weight ) && is_array( $sm_metabox_ap_shipping_class_weight ) ):
													foreach ( $sm_metabox_ap_shipping_class_weight as $apscs_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apscs_arr ) || '' !== $apscs_arr ) {
															if ( ( '' !== $apscs_arr['ap_fees_shipping_class_weight'] && '' !== $apscs_arr['ap_fees_ap_price_shipping_class_weight'] ) &&
																	( '' !== $apscs_arr['ap_fees_ap_shipping_class_weight_min_weight'] || '' !== $apscs_arr['ap_fees_ap_shipping_class_weight_max_weight'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $apscs_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_shipping_class_weight = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_shipping_class_weight             = isset( $productfees['ap_fees_shipping_class_weight'] ) ? $productfees['ap_fees_shipping_class_weight'] : '';
														$ap_fees_ap_shipping_class_weight_min_weight    = isset( $productfees['ap_fees_ap_shipping_class_weight_min_weight'] ) ? $productfees['ap_fees_ap_shipping_class_weight_min_weight'] : '';
														$ap_fees_ap_shipping_class_weight_max_weight    = isset( $productfees['ap_fees_ap_shipping_class_weight_max_weight'] ) ? $productfees['ap_fees_ap_shipping_class_weight_max_weight'] : '';
														$ap_fees_ap_price_shipping_class_weight         = isset( $productfees['ap_fees_ap_price_shipping_class_weight'] ) ? $productfees['ap_fees_ap_price_shipping_class_weight'] : '';
														?>
														<tr id="ap_shipping_class_weight_row_<?php echo esc_attr( $cnt_shipping_class_weight ); ?>"
															valign="top"
															class="ap_shipping_class_weight_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_shipping_class_weight ); ?>"
																		id="ap_shipping_class_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_shipping_class_weight ); ?>"
																		name="fees[ap_shipping_class_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_shipping_class_weight ); ?>][]"
																		id="ap_shipping_class_weight_fees_conditions_condition"
																		class="ap_shipping_class_weight product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_class_options__premium_only( $fees_ap_fees_shipping_class_weight, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_weight ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_shipping_class_weight_min_weight][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_shipping_class_weight_min_weight[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_shipping_class_weight_min_weight ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_weight ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_shipping_class_weight_max_weight][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_shipping_class_weight_max_weight[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_shipping_class_weight_max_weight ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_weight ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_shipping_class_weight][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_shipping_class_weight[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_shipping_class_weight ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_shipping_class_weight, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart weight it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart weight then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_weight ); ?> condition-value">
                                                                <a id="ap-shipping-class-weight-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-shipping-class-weight-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_shipping_class_weight ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_shipping_class_weight ++;
													}
													?>
													<?php
												} else {
													$cnt_shipping_class_weight = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_shipping_class_weight"
													id="total_row_shipping_class_weight"
													value="<?php echo esc_attr( $cnt_shipping_class_weight ); ?>">
											<!-- Advanced Pricing Shipping Class Weight Section end here -->
										</div>
									</div>
                                    <!-- Advanced Pricing Shipping Class Weight end here -->
									<?php 
                                    do_action( 'afrsm_ap_shipping_class_weight_container_after', $get_post_id );


                                    do_action( 'afrsm_ap_shipping_class_subtotal_container_before', $get_post_id ); ?>
                                    <!-- Advanced Pricing Shipping Class Subtotal start here -->
									<div class="ap_shipping_class_subtotal_container advance_pricing_rule_box tab-content" id="tab-15" data-title="<?php esc_attr_e( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_shipping_class_list">
													<?php
													echo wp_kses( $afrsm_admin_object->afrsm_pro_get_class_options__premium_only( '', $json = true ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
													?>
												</div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Shipping Class Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="shipping_class_subtotal"
														data-qow="subtotal"
														data-filedtype="select"
														data-filedtitle2="shipping_class_subtotal"
														data-filedcategory="shipping_class_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox"
																	name="cost_on_shipping_class_subtotal_status"
																	value="on" <?php echo esc_attr( $cost_on_shipping_class_subtotal_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_shipping_class_subtotal_rule_match]"
															id="cost_on_shipping_class_subtotal_rule_match"
															class="arcmt_select">
														<option value="any" <?php selected( $cost_on_shipping_class_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_shipping_class_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/162-advanced-shipping-price-rules-shipping-cost-on-shipping-class-subtotal'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_shipping_class_subtotal_method" class="tbl_shipping_class_subtotal_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a shipping class to apply the shipping amount to when the min/max subtotal match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum shipping class cart subtotal per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Subtotal ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum shipping class cart subtotal per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();
												//check if category AP rules exist
												if ( ! empty( $sm_metabox_ap_shipping_class_subtotal ) && is_array( $sm_metabox_ap_shipping_class_subtotal ) ):
													foreach ( $sm_metabox_ap_shipping_class_subtotal as $apscs_arr ):
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $apscs_arr ) || '' !== $apscs_arr ) {
															if ( ( '' !== $apscs_arr['ap_fees_shipping_class_subtotals'] && '' !== $apscs_arr['ap_fees_ap_price_shipping_class_subtotal'] ) &&
																	( '' !== $apscs_arr['ap_fees_ap_shipping_class_subtotal_min_subtotal'] || '' !== $apscs_arr['ap_fees_ap_shipping_class_subtotal_max_subtotal'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $apscs_arr;
															}
														}
													endforeach;
												endif;
												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_shipping_class_subtotal = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_shipping_class_subtotals           = isset( $productfees['ap_fees_shipping_class_subtotals'] ) ? $productfees['ap_fees_shipping_class_subtotals'] : '';
														$ap_fees_ap_shipping_class_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_shipping_class_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_shipping_class_subtotal_min_subtotal'] : '';
														$ap_fees_ap_shipping_class_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_shipping_class_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_shipping_class_subtotal_max_subtotal'] : '';
														$ap_fees_ap_price_shipping_class_subtotal        = isset( $productfees['ap_fees_ap_price_shipping_class_subtotal'] ) ? $productfees['ap_fees_ap_price_shipping_class_subtotal'] : '';
														?>
														<tr id="ap_shipping_class_subtotal_row_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
															valign="top"
															class="ap_shipping_class_subtotal_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
																		id="ap_shipping_class_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
																		name="fees[ap_shipping_class_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>][]"
																		id="ap_shipping_class_subtotal_fees_conditions_condition"
																		class="ap_shipping_class_subtotal product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_pro_get_class_options__premium_only( $fees_ap_fees_shipping_class_subtotals, $json = false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_shipping_class_subtotal_min_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_shipping_class_subtotal_min_subtotal ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]"
																		class="text-class qty-class max-val-class"
																		id="ap_fees_ap_shipping_class_subtotal_max_subtotal[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_shipping_class_subtotal_max_subtotal ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_shipping_class_subtotal][]"
																		class="text-class number-field price-val-class"
																		id="ap_fees_ap_price_shipping_class_subtotal[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_shipping_class_subtotal ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_shipping_class_subtotal, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
                                                                <a id="ap-shipping-class-subtotal-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-shipping-class-subtotal-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_shipping_class_subtotal ++;
													}
													?>
													<?php
												} else {
													$cnt_shipping_class_subtotal = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_shipping_class_subtotal"
													id="total_row_shipping_class_subtotal"
													value="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>">
											<!-- Advanced Pricing Category Section end here -->
										</div>
									</div>
                                    <!-- Advanced Pricing Shipping Class Subtotal end here -->
									<?php 
                                    do_action( 'afrsm_ap_shipping_class_subtotal_container_after', $get_post_id );

                                    do_action( 'afrsm_ap_product_attribute_container_before', $get_post_id );
									?>
                                    <!-- Advanced Pricing Product Attributes start here -->
									<div class="ap_product_attribute_container advance_pricing_rule_box tab-content" id="tab-16" data-title="<?php echo esc_attr( 'Cost on Product Attributes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>">
										<div class="tap-class">
											<div class="predefined_elements">
												<div id="all_product_attribute_list">
                                                <?php
                                                    echo wp_kses( $afrsm_admin_object->afrsm_get_product_attribute_options__premium_only( '', true ), $afrsm_object::afrsm_pro_allowed_html_tags() );
                                                ?>
                                                </div>
											</div>
											<div class="sub-title">
												<h2 class="ap-title"><?php esc_html_e( 'Cost on Product Attributes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
												<div class="tap">
													<a id="ap-add-field"
														data-filedtitle="product_attribute"
														data-qow="qty"
														data-filedtype="select"
														data-filedtitle2="product_attribute"
														data-filedcategory="product_attribute_list"
														data-relatedtype="list"
														class="button"
														href="javascript:;"><?php esc_html_e( '+ Add Rule', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
													<div class="switch_status_div">
														<label class="switch switch_in_pricing_rules">
															<input type="checkbox" name="cost_on_product_attribute_status" value="on" <?php echo esc_attr( $cost_on_product_attribute_status ); ?>>
															<div class="slider round"></div>
														</label>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( AFRSM_PRO_PERTICULAR_FEE_AMOUNT_NOTICE, 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</div>
												</div>
												<div class="advance_rule_condition_match_type">
													<p class="switch_in_pricing_rules_description_left">
														<?php esc_html_e( 'below', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
													<select name="cost_rule_match[cost_on_product_attribute_rule_match]" id="cost_on_product_attribute_rule_match" class="arcmt_select">
														<option value="any" <?php selected( $cost_on_product_attribute_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
														<option value="all" <?php selected( $cost_on_product_attribute_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
													</select>
													<p class="switch_in_pricing_rules_description">
														<?php esc_html_e( 'rule match', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>
													</p>
												</div>
												<div class="advance_rule_condition_help">
													<span class="dashicons dashicons-info-outline"></span>
													<a href="<?php echo esc_url('https://docs.thedotstore.com/article/115-advanced-shipping-price-rules-shipping-cost-on-product_attribute'); ?>" target="_blank"><?php esc_html_e( 'View Documentation', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></a>
												</div>
											</div>
											<table id="tbl_ap_product_attribute_method" class="tbl_product_attribute_fee table-outer tap-cas form-table advance-shipping-method-table">
												<tbody>
												<tr class="heading">
													<th class="titledesc th_product_attribute_fees_conditions_condition" scope="row">
														<span><?php esc_html_e( 'Product Attributes', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the shipping amount to when the min/max quantity match.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_product_attribute_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Min Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product quantity per row before the shipping amount is applied.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_attribute_fees_conditions_condition" scope="row">
                                                        <span><?php esc_html_e( 'Max Quantity ', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product quantity per row before the shipping amount is applied. Leave empty then will set with infinite', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </th>
													<th class="titledesc th_product_attribute_fees_conditions_condition" scope="row" colspan="2">
                                                        <span><?php esc_html_e( 'Shipping Amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on cart subtotal.', 'advanced-flat-rate-shipping-for-woocommerce' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
												</tr>
												<?php
												//check advanced pricing value fill proper or unset if not
												$filled_arr = array();

												if ( ! empty( $sm_metabox_ap_product_attribute ) && is_array( $sm_metabox_ap_product_attribute ) ):
													foreach ( $sm_metabox_ap_product_attribute as $app_arr ) {
														//check that if required field fill or not once save the APR,  if match than fill in array
														if ( ! empty( $app_arr ) || '' !== $app_arr ) {
															if ( ( '' !== $app_arr['ap_fees_product_attributes'] && '' !== $app_arr['ap_fees_ap_price_product_attribute'] ) && ( '' !== $app_arr['ap_fees_ap_product_attribute_min_qty'] || '' !== $app_arr['ap_fees_ap_product_attribute_max_qty'] ) ) {
																//if condition match than fill in array
																$filled_arr[] = $app_arr;
															}
														}
													}
												endif;

												//check APR exist
												if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
													$cnt_product_attribute = 2;
													foreach ( $filled_arr as $key => $productfees ) {
														$fees_ap_fees_product_attributes       = isset( $productfees['ap_fees_product_attributes'] ) ? $productfees['ap_fees_product_attributes'] : '';
														$ap_fees_ap_product_attribute_min_qty  = isset( $productfees['ap_fees_ap_product_attribute_min_qty'] ) ? $productfees['ap_fees_ap_product_attribute_min_qty'] : '';
														$ap_fees_ap_product_attribute_max_qty  = isset( $productfees['ap_fees_ap_product_attribute_max_qty'] ) ? $productfees['ap_fees_ap_product_attribute_max_qty'] : '';
														$ap_fees_ap_price_product_attribute    = isset( $productfees['ap_fees_ap_price_product_attribute'] ) ? $productfees['ap_fees_ap_price_product_attribute'] : '';
														?>
														<tr id="ap_product_row_<?php echo esc_attr( $cnt_product_attribute ); ?>"
															valign="top" class="ap_product_attribute_row_tr">
															<td class="titledesc" scope="row">
																<select rel-id="<?php echo esc_attr( $cnt_product_attribute ); ?>"
																		id="ap_product_attribute_fees_conditions_condition_<?php echo esc_attr( $cnt_product_attribute ); ?>"
																		name="fees[ap_product_attribute_fees_conditions_condition][<?php echo esc_attr( $cnt_product_attribute ); ?>][]"
																		id="ap_product_attribute_fees_conditions_condition"
																		class="ap_not_list ap_product product_fees_conditions_values multiselect2 afrsm_select"
																		multiple="multiple">
																	<?php
																	echo wp_kses( $afrsm_admin_object->afrsm_get_product_attribute_options__premium_only( $fees_ap_fees_product_attributes, false ), $afrsm_object::afrsm_pro_allowed_html_tags() );
																	?>
																</select>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_attribute ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_product_attribute_min_qty][]"
																		class="text-class qty-class min-val-class"
																		id="ap_fees_ap_product_attribute_min_qty[]"
																		placeholder="<?php echo esc_attr( 'Min quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_product_attribute_min_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_attribute ); ?> condition-value">
																<input type="number"
																		name="fees[ap_fees_ap_product_attribute_max_qty][]"
																		class="text-class qty-class qty-class max-val-class"
																		id="ap_fees_ap_product_attribute_max_qty[]"
																		placeholder="<?php echo esc_attr( 'Max quantity', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_product_attribute_max_qty ); ?>"
																		min="1">
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_attribute ); ?> condition-value">
																<input type="text"
																		name="fees[ap_fees_ap_price_product_attribute][]"
																		class="price-val-class text-class number-field"
																		id="ap_fees_ap_price_product_attribute[]"
																		placeholder="<?php echo esc_attr( 'amount', 'advanced-flat-rate-shipping-for-woocommerce' ); ?>"
																		value="<?php echo esc_attr( $ap_fees_ap_price_product_attribute ); ?>">
																<?php
																$first_char = substr( $ap_fees_ap_price_product_attribute, 0, 1 );
																if ( '-' === $first_char ) {
																	$html = sprintf(
																		'<p><b style="color: red;">%s</b>%s',
																		esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
																		esc_html__( 'If entered shipping amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered shipping amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'advanced-flat-rate-shipping-for-woocommerce' )
																	);
																	echo wp_kses_post( $html );
																}
																?>
															</td>
															<td class="column_<?php echo esc_attr( $cnt_product_attribute ); ?> condition-value">
                                                                <a id="ap-product-attribute-clone-field" 
                                                                    rel-id="<?php echo esc_attr( $cnt_product ); ?>" 
                                                                    title="Clone" 
                                                                    class="ap-clone-row" 
                                                                    href="javascript:;">
                                                                    <i class="fa fa-clone"></i>
                                                                </a>
																<a id="ap-product-attribute-delete-field"
																	rel-id="<?php echo esc_attr( $cnt_product_attribute ); ?>"
																	title="Delete" class="delete-row"
																	href="javascript:;">
																	<i class="dashicons dashicons-trash"></i>
																</a>
															</td>
														</tr>
														<?php
														$cnt_product_attribute ++;
													}
													?>
													<?php
												} else {
													$cnt_product_attribute = 1;
												}
												?>
												</tbody>
											</table>
											<input type="hidden" name="total_row_product_attribute" id="total_row_product_attribute" value="<?php echo esc_attr( $cnt_product_attribute ); ?>">
										</div>
									</div>
                                    <!-- Advanced Pricing Product Attributes end here -->
									<?php
									do_action( 'afrsm_ap_product_attribute_container_after', $get_post_id );
								}
							} ?>		
						</div>
					</div>
				</div>
				<?php // Advanced Pricing Section end ?>
						
				<p class="submit">
					<input type="submit" name="submitFee" class="button button-primary" value="<?php echo esc_attr( $submit_text ); ?>">
				</p>
			</form>
		</div>

	</div>
<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-footer.php' );