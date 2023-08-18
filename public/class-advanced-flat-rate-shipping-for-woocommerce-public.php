<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Public {
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
	private $version;
	private static $admin_object = null;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		self::$admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advanced-flat-rate-shipping-for-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'font-awesome-min', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version );
	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function afrsm_pro_enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
	}
	/**
	 * This function return the template from this plugin, if it exists
	 *
	 * @param string $template
	 * @param string $template_name that is only the filename
	 * @param string $template_path
	 *
	 * @return string
	 * @since    1.0.0
	 *
	 */
	public function afrsm_pro_wc_locate_template_sm_conditions( $template, $template_name, $template_path ) {
		global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}
		$plugin_path = advanced_flat_rate_shipping_for_woocommerce_pro_plugin_path() . '/woocommerce/';
		$template    = locate_template(
			array(
				$template_path . $template_name,
				$template_name,
			)
		);
		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}
		if ( ! $template ) {
			$template = $_template;
		}
		// Return what we found
		return $template;
	}
	/**
	 * This function want to display method based on payment method
	 *
	 * @since   3.5
	 */
	public function afrsm_pro_woocommerce_checkout_update_order_review__premium_only() {
		$payment_method = filter_input( INPUT_POST, 'payment_method', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		WC()->session->set( 'chosen_payment_method', empty( $payment_method ) ? '' : sanitize_text_field( wc_clean( wp_unslash( $payment_method ) ) ) );
		$bool = true;
		if ( WC()->session->get( 'chosen_payment_method' ) ) {
			$bool = false;
		}
		$shipping_package = WC()->cart->get_shipping_packages();
        
        //#74359 package null condition added
		if ( isset( $shipping_package ) && is_array( $shipping_package ) ) {
			foreach ( array_keys( $shipping_package ) as $package_key ) {
				WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
			}	
		}

		WC()->cart->calculate_shipping();
        // Restore shipping method, as well as cost and tax estimate when on the cart page.
        WC()->shipping->reset_shipping();
        //phpcs:ignore
        // WC()->cart->calculate_totals(); //#54543 we have added this but later not used (#74889) this ticket 
	}
	/*
	 * Check shipping and remove shipping
	 *
	 * @since 3.5.2
	 *
	 * @param $rates
	 * @param $package
	 *
	 * @return $rates
	 */
	public function afrsm_pro_remove_shipping_method__premium_only( $rates ) {

		$matched_methods                        = get_option( 'matched_method' );
		$get_what_to_do_method                  = get_option( 'what_to_do_method' );
		$get_what_to_do_method                  = ! empty( $get_what_to_do_method ) ? $get_what_to_do_method : 'allow_customer';
		$currency_symbol                        = get_woocommerce_currency_symbol();
		$combine_default_shipping_with_forceall = get_option( 'combine_default_shipping_with_forceall' );
		$afrsm_hide_other_shipping              = get_option( 'afrsm_hide_other_shipping' );
        
		if ( ! empty( $matched_methods ) ) {

			if ( 'force_all' === $get_what_to_do_method ) {
                
				if ( isset( $combine_default_shipping_with_forceall ) && 'woo_our' === $combine_default_shipping_with_forceall ) {
					if ( ! empty( $rates ) ) {
						$total_package_rate_cost = 0;
						$total_package_rate_tax  = 0;
						$package_rate_label      = '';
						$ik                      = 0;

                        //Apply specific shipping mathod id for exclude from force combine.
						$exclude_shipping_flag = apply_filters( 'exclude_shipping_flag', array() );
						$exclude_shipping_arr  = isset( $exclude_shipping_flag ) && !empty ( $exclude_shipping_flag ) ? $exclude_shipping_flag : array();
						
						foreach ( $rates as $rate_id => $rate ) {
							
							if ( (
								  'free_shipping' === $rate->method_id
							      || 'flat_rate' === $rate->method_id
							      || 'local_pickup' === $rate->method_id
							      || 'advanced_flat_rate_shipping' === $rate->method_id
								)
								&&
								! in_array( $rate->id, $exclude_shipping_arr, true )
								) {
								$price = self::$admin_object->afrsm_pro_price_based_on_switcher( $rate->cost );
								$price = self::$admin_object->afrsm_woocs_convert_price( $rate->cost );
								$package_rate_cost       = floatval( $price );
								$total_package_rate_cost += $rate->cost;
								$tax_label               = '';
								if ( ! empty( $rate->taxes ) ) {
									foreach ( $rate->taxes as $tax_cost ) {
										$package_rate_tax             = floatval( $tax_cost );
										$include_tax                  = wc_prices_include_tax();
										$display_prices_including_tax = WC()->cart->display_prices_including_tax();
										if ( $display_prices_including_tax ) {
											if ( $tax_cost > 0 && ! $include_tax ) {
												$tax_label .= WC()->countries->inc_tax_or_vat();
											}
											$package_rate_cost       += floatval( $package_rate_tax );
											$total_package_rate_tax += floatval( $tax_cost );
										} else {
											if ( $tax_cost > 0 ) {
												$total_package_rate_tax += floatval( $tax_cost );
												$tax_label              .= WC()->countries->ex_tax_or_vat();
											}
										}
									}
								}
								if ( 'forceall' !== $rate->id ) {
									if ( 1 !== $ik ) {
										$package_rate_label .= ', ';
									}
									$package_rate_label .= $rate->label . ' : ' . $currency_symbol . '' . $this->afrswp_fraction_price_format( $package_rate_cost ) . ' ' . $tax_label;
								}
								if ( 'forceall' !== $rate->id ) {
									unset( $rates[ $rate_id ] );
								}
							}
							$ik ++;
						}
						if ( '' !== $package_rate_label ) {
							$taxes                    = array();
							$taxes[1]                 = $total_package_rate_tax;
							$rates['forceall']->label = $package_rate_label;
							$rates['forceall']->cost  = floatval($total_package_rate_cost + $total_package_rate_tax);
							$rates['forceall']->taxes = $taxes;
						}
					}
					return $rates;
				} elseif ( isset( $combine_default_shipping_with_forceall ) && 'all' === $combine_default_shipping_with_forceall ) {
					if ( ! empty( $rates ) ) {
						$total_package_rate_cost = 0;
						$total_package_rate_tax  = 0;
						$package_rate_label      = '';
						$jk                      = 0;

                        //Apply specific shipping mathod id for exclude from force combine
						$exclude_shipping_flag   = apply_filters( 'exclude_shipping_flag', array() );
						$exclude_shipping_arr    = isset( $exclude_shipping_flag ) && !empty ( $exclude_shipping_flag ) ? $exclude_shipping_flag : array();

						foreach ( $rates as $rate_id => $rate ) {
							if( ! in_array( $rate->id, $exclude_shipping_arr, true ) ){
								$price = self::$admin_object->afrsm_pro_price_based_on_switcher( $rate->cost );
								$price = self::$admin_object->afrsm_woocs_convert_price( $rate->cost );
								$package_rate_cost       = floatval( $price );
								$total_package_rate_cost += $rate->cost;
								$tax_label               = '';
								if ( ! empty( $rate->taxes ) ) {
									foreach ( $rate->taxes as $tax_cost ) {
										$package_rate_tax             = floatval( $tax_cost );
										$include_tax                  = wc_prices_include_tax();
										$display_prices_including_tax = WC()->cart->display_prices_including_tax();
										if ( $display_prices_including_tax ) {
											if ( $tax_cost > 0 && ! $include_tax ) {
												$tax_label .= WC()->countries->inc_tax_or_vat();
											}
											$package_rate_cost       += floatval( $package_rate_tax );
											$total_package_rate_tax += floatval( $tax_cost );
										} else {
											if ( $tax_cost > 0 ) {
												$total_package_rate_tax += floatval( $tax_cost );
												$tax_label              .= WC()->countries->ex_tax_or_vat();
											}
										}
									}
								}
								if ( 'forceall' !== $rate->id ) {
									if ( 1 !== $jk ) {
										$package_rate_label .= ', ';
									}
									$package_rate_label .= $rate->label . ' : ' . $currency_symbol . '' . $this->afrswp_fraction_price_format( $package_rate_cost ) . ' ' . $tax_label;
								}
								if ( 'forceall' !== $rate->id ) {
									unset( $rates[ $rate_id ] );
								}
								$jk ++;
							}
						}
						if ( '' !== $package_rate_label ) {
							$taxes                    = array();
							$taxes[1]                 = $total_package_rate_tax;
							$rates['forceall']->label = $package_rate_label;
							$rates['forceall']->cost  = floatval($total_package_rate_cost + $total_package_rate_tax);
							$rates['forceall']->taxes = $taxes;
						}
					}
					return $rates;
				} else {
					if ( ! empty( $rates ) ) {
						$total_package_rate_cost = 0;
						$total_package_rate_tax  = 0;
						$package_rate_label      = '';
						$vk                      = 0;
						foreach ( $rates as $rate_id => $rate ) {
							if ( 'advanced_flat_rate_shipping' === $rate->method_id ) {
								$price = self::$admin_object->afrsm_pro_price_based_on_switcher( $rate->cost );
								$price = self::$admin_object->afrsm_woocs_convert_price( $rate->cost );
								$package_rate_cost       = floatval( $price );
								$total_package_rate_cost += $rate->cost;
								$tax_label               = '';
								if ( ! empty( $rate->taxes ) ) {
									foreach ( $rate->taxes as $tax_cost ) {
										$package_rate_tax             = floatval( $tax_cost );
										$include_tax                  = wc_prices_include_tax();
										$display_prices_including_tax = WC()->cart->display_prices_including_tax();
										if ( $display_prices_including_tax ) {
											if ( $tax_cost > 0 && ! $include_tax ) {
												$tax_label .= WC()->countries->inc_tax_or_vat();
											}
											$package_rate_cost += floatval( $package_rate_tax );
											$total_package_rate_tax += floatval( $tax_cost );
										} else {
											if ( $tax_cost > 0 ) {
												$total_package_rate_tax += floatval( $tax_cost );
												$tax_label              .= WC()->countries->ex_tax_or_vat();
											}
										}
									}
								}
								if ( 'forceall' !== $rate->id ) {
									$vk ++;
									if ( 1 !== $vk ) {
										$package_rate_label .= ', ';
									}
									$package_rate_label .= $rate->label . ' : ' . $currency_symbol . '' . $this->afrswp_fraction_price_format( $package_rate_cost ) . ' ' . $tax_label;
									unset( $rates[ $rate_id ] );
								}
							}
						}
						if ( '' !== $package_rate_label ) {
							$taxes                    = array();
							$taxes[1]                 = $total_package_rate_tax;
							$rates['forceall']->label = $package_rate_label;
							$rates['forceall']->cost  = floatval($total_package_rate_cost + $total_package_rate_tax);
							$rates['forceall']->taxes = $taxes;
						}
					}
				}
				return $rates;
			} elseif ( 'apply_highest' === $get_what_to_do_method ) {
				$check_highest            = array();
				$highest_value_key_result = array();
				if ( ! empty( $rates ) ) {
					foreach ( $rates as $key => $rate ) {
						$check_highest[ $key ] = floatval( $rate->cost );
					}
				}
				if ( ! empty( $check_highest ) ) {
					$highest_value_key_result = array_keys( $check_highest, max( $check_highest ), true );
				}
				if ( array_key_exists( 0, $highest_value_key_result ) ) {
					$highest_value_key = $highest_value_key_result[0];
				} else {
					$highest_value_key = '';
				}
				foreach ( $rates as $rate_id => $rate ) {
                    // Apply filter for exclude shipping from highest shipping list (#86037)
                    if ( $highest_value_key !== $rate_id ) {
						$show_default_ids = apply_filters('show_shipping_in_highest', array());
						if(isset($show_default_ids) && ! empty($show_default_ids) && is_array($show_default_ids)){
							if( ! in_array( $rate_id, $show_default_ids, true ) ) {
								unset( $rates[ $rate_id ] );
							}
						} else {
							unset( $rates[ $rate_id ] );
						}
					}
				}
				return $rates;
			} elseif ( 'apply_smallest' === $get_what_to_do_method ) {
				$check_smallest            = array();
				$smallest_value_key_result = array();
				if ( ! empty( $rates ) ) {
					foreach ( $rates as $key => $rate ) {
						$check_smallest[ $key ] = floatval( $rate->cost );
					}
				}
				if ( ! empty( $check_smallest ) ) {
					$smallest_value_key_result = array_keys( $check_smallest, min( $check_smallest ), true );
				}
				if ( array_key_exists( 0, $smallest_value_key_result ) ) {
					$smallest_value_key = $smallest_value_key_result[0];
				} else {
					$smallest_value_key = '';
				}
				foreach ( $rates as $rate_id => $rate ) {
					if ( $smallest_value_key !== $rate_id ) {
						unset( $rates[ $rate_id ] );
					}
				}
				return $rates;
			} elseif ( 'on' === $afrsm_hide_other_shipping ) {				
				if ( ! empty( $rates ) ) {
					$free_rates = array();
					foreach ( $rates as $key => $rate ) {
						$check_smallest_cost = floatval( $rate->cost );
						if( floatval(0) === $check_smallest_cost){
							$free_rates[$key] = $rate;
							break;
						}
					}
					return ( !empty($free_rates) ? $free_rates : $rates );
				}
			} else {
				if ( ! empty( $rates ) ) {
					foreach ( $rates as $rate_id => $rate ) {
						if ( 'forceall' === $rate_id ) {
							unset( $rates[ $rate_id ] );
						}
					}
				}
				return $rates;
			}
		} else {
			if ( 'on' === $afrsm_hide_other_shipping ) {
				if ( ! empty( $rates ) ) {
					$free_rates = array();
					foreach ( $rates as $key => $rate ) {
						$check_smallest_cost = floatval( $rate->cost );
						if( floatval(0) === $check_smallest_cost){
							$free_rates[$key] = $rate;
							break;
						}
					}
					return ( !empty($free_rates) ? $free_rates : $rates );
				}
			}
		}
	return $rates;
	}
	/**
	 * Price Format
	 *
	 * @param float $price price would be display here
	 *
	 * @return float $price
	 * @since  3.6.1
	 */
	public function afrswp_fraction_price_format( $price ) {
		$args  = array(
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimals'           => wc_get_price_decimals(),
			'price_format'       => get_woocommerce_price_format(),
		);
		$price = floatval( $price );
		$price = number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );
		return $price;
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
	public function afrsm_pro_remove_currency_symbol_public__premium_only( $price ) {
		$wc_currency_symbol = get_woocommerce_currency_symbol();
		$new_price          = str_replace( $wc_currency_symbol, '', $price );
		$new_price2         = (double) preg_replace( '/[^.\d]/', '', $new_price );
		return $new_price2;
	}
	/**
	 * Forceall label for cart
	 *
	 * @param $new_lin_force_all_lable
	 * @param $tool_tip_html
	 * @param $method
	 * @param $forceall_label
	 *
	 * @return array
	 * @since  3.6
	 */
	public function afrsm_pro_forceall_label_for_cart__premium_only( $new_lin_force_all_lable, $tool_tip_html, $method, $forceall_label ) {
		$total_shipping_lable     = '';
		$total_shipping_lable_con = '';

		if ( false !== strpos( $method->label, ', ' ) ) {
			$method_label_explode = explode( ', ', $method->label );
			$check_taxable_array  = array();
			foreach ( $method_label_explode as $key => $label_value ) {
				if ( false !== strpos( $label_value, '(' ) ) {
					$label_value_ex = explode( ' (', $label_value );
					$shipping_id    = '';
					if ( $key >= 1 ) {
						$new_lin_force_all_lable .= '<br>';
					}
					$value = $label_value;
					if ( array_key_exists( '1', $label_value_ex ) ) {
						$shipping_id = trim( $label_value_ex[1] );
					}
					if ( ! empty( $shipping_id ) || '' !== $shipping_id ) {
						if ( false !== strpos( $shipping_id, 'incl' ) ) {
							$check_taxable_array[] = 'inc';
						} else {
							$check_taxable_array[] = 'exc';
						}
					}
					$new_lin_force_all_lable .= $value;
				} else {
					if ( $key >= 1 ) {
						$new_lin_force_all_lable .= '<br>';
					}
					$new_lin_force_all_lable .= $label_value;
				}
			}
		} else {
			$new_lin_force_all_lable .= $method->label;
		}
		$tax_value                = isset( $method->taxes[1] ) ? $method->taxes[1] : 0;
		if ( ! empty( $new_lin_force_all_lable ) ) {
			if ( ! empty( $check_taxable_array ) ) {
				if ( in_array( 'inc', $check_taxable_array, true ) ) {
					$total_shipping_lable_con .= $new_lin_force_all_lable . '<br>(<b>';
					$total_shipping_lable_con .= esc_html__( 'Total Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ) . ': ';
					$total_shipping_lable_con .= wp_kses( wc_price( $method->cost + $tax_value ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
					ob_start();
					?>
					</b>
					<small class="tax_label"><?php echo wp_kses_post( WC()->countries->inc_tax_or_vat() ); ?></small>
					<?php
					$b_small_html = ob_get_contents();
					ob_end_clean();
					$total_shipping_lable_con .= $b_small_html;
					$total_shipping_lable_con .= ')';
					$total_shipping_lable     .= $total_shipping_lable_con;
				} elseif ( in_array( 'ex', $check_taxable_array, true ) ) {
					$total_shipping_lable_con .= $new_lin_force_all_lable . '<br>(<b>';
					$total_shipping_lable_con .= esc_html__( 'Total Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ) . ': ';
					$total_shipping_lable_con .= wp_kses( wc_price( $method->cost ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
					ob_start();
					?>
					</b>
					<small class="tax_label"><?php echo wp_kses_post( WC()->countries->ex_tax_or_vat() ); ?></small>
					<?php
					$b_small_html = ob_get_contents();
					ob_end_clean();
					$total_shipping_lable_con .= $b_small_html;
					$total_shipping_lable_con .= ')';
					$total_shipping_lable     .= $total_shipping_lable_con;
				} else {
					$total_shipping_lable .= $new_lin_force_all_lable . '<br>(<b>' .
					                         esc_html__( 'Total Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ) . ': ' .
					                         wp_kses( wc_price( $method->cost ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) . ')';
				}
			} else {
				$total_shipping_lable .= $new_lin_force_all_lable . '<br>(<b>' .
				                         esc_html__( 'Total Shipping', 'advanced-flat-rate-shipping-for-woocommerce' ) . ': ' .
				                         wp_kses( wc_price( $method->cost + $tax_value ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) . ')';
			}
		}
		if ( ! empty( $forceall_label ) ) {
			ob_start();
			?>
			<div class="forceall-tooltip">
				<a><i class="fa fa-question-circle fa-lg"></i></a>
				<span class="tooltiptext"><?php echo wp_kses( $new_lin_force_all_lable, array( 'br' => array() ) ); ?></span>
			</div>
			<?php
			$tool_tip_html .= ob_get_contents();
			ob_end_clean();
			$method->label = $forceall_label;
		} else {
			$method->label = $new_lin_force_all_lable;
		}
		return array(
			'method_label'         => $method->label,
			'total_shipping_lable' => $total_shipping_lable,
			'tool_tip_html'        => $tool_tip_html,
		);
	}

	/**
	 * Default Shipping method
	 *
	 * @param $method
	 * @param $available_methods
	 *
	 * @return array
	 * @since  3.6
	 */
	public function afrsm_set_default_shipping_method( $method, $available_methods ) {
	    $afrsm_default_shipping_methods = array();
	    if($available_methods){
		    foreach ( $available_methods as $afrsm_method ) :
		    	$get_method_id        = '';
				if ( false !== strpos( $afrsm_method->id, 'advanced_flat_rate_shipping:' ) ) {
					$method_id_explode = explode( ':', $afrsm_method->id );
					$get_method_id     = $method_id_explode[1];
				}
				$sm_is_selected_shipping = get_post_meta( $get_method_id, 'sm_select_selected_shipping', true );
		    	if("yes" === $sm_is_selected_shipping){
		    		$afrsm_default_shipping_methods[] = $afrsm_method->id;
		    	}
		    endforeach;
		}

	    if(empty($afrsm_default_shipping_methods)){
	    	return $method;
	    }else{
	    	foreach ( $afrsm_default_shipping_methods as $afrsm_default_method ) :
		    	if( array_key_exists($afrsm_default_method, $available_methods ) ){
			    	return $afrsm_default_method;
		    	}
		    endforeach;	
	    }
	}

	public function afrsm_pro_wc_cart_shipping_method_label_callback( $label, $method ){

		$get_method_id        = '';
		$method_id = $method->get_id() ? $method->get_id() : '';
		if ( false !== strpos( $method_id, 'advanced_flat_rate_shipping:' ) ) {
			$method_id_explode = explode( ':', $method_id );
			$get_method_id     = end($method_id_explode);
		}
		$sm_estimation_delivery = get_post_meta( $get_method_id, 'sm_estimation_delivery', true );
		$sm_estimation_delivery = ( isset( $sm_estimation_delivery ) && ! empty( $sm_estimation_delivery ) ) ? ( ' (' . $sm_estimation_delivery . ') ' ) : '';

		if( "forceall" === $method_id ){
			$forceall_label = get_option( 'forceall_label' ) ? get_option( 'forceall_label' ) : esc_html__( 'Combine Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
			$method->set_label($forceall_label);
			$label = $method->get_label().": ".wc_price($method->get_cost());
			return $label;
		} else {
			return $label."<span>".$sm_estimation_delivery."</span>";
		}
		
	}

	public function afrsm_add_tooltip_and_subtitle_callback( $method ){

		$tool_tip_html        = '';
		$final_shipping_label = '';
		$get_method_id        = '';
		
		if( "forceall" === $method->id && is_checkout() ){
			$new_lin_force_all_lable = '';
			$forceall_label = get_option( 'forceall_label' ) ? get_option( 'forceall_label' ) : esc_html__( 'Combine Shipping', 'advanced-flat-rate-shipping-for-woocommerce' );
			$get_param_cart       = $this->afrsm_pro_forceall_label_for_cart__premium_only( $new_lin_force_all_lable, $tool_tip_html, $method, $forceall_label );
			if ( is_plugin_active( 'checkout-for-woocommerce/checkout-for-woocommerce.php' ) ) {
				$tool_tip_html = $get_param_cart['tool_tip_html'];
			}
		} else {
			if ( false !== strpos( $method->id, 'advanced_flat_rate_shipping:' ) ) {
				$method_id_explode = explode( ':', $method->id );
				$get_method_id     = $method_id_explode[1];
			}
			
			$sm_tooltip_type        = get_post_meta( $get_method_id, 'sm_tooltip_type', true );
			$sm_tooltip_type        = ( isset( $sm_tooltip_type ) && ! empty( $sm_tooltip_type ) ) ? $sm_tooltip_type : esc_html__( 'tooltip', 'advanced-flat-rate-shipping-for-woocommerce' );
	
			$sm_tooltip_desc        = get_post_meta( $get_method_id, 'sm_tooltip_desc', true );
			$sm_tooltip_desc        = ( isset( $sm_tooltip_desc ) && ! empty( $sm_tooltip_desc ) ) ? $sm_tooltip_desc : '';
			
			$final_shipping_label   .= $sm_tooltip_desc;
			if ( ! empty( $final_shipping_label ) ) {
				if( "tooltip" === $sm_tooltip_type ) {
					$tool_tip_html .= '<div class="extra-flate-tool-tip"><a data-tooltip="' . esc_attr( $final_shipping_label ) . '"><i class="fa fa-question-circle fa-lg"></i></a></div>';
				} else {
					$tool_tip_html .= '<div class="extra-flate-subtitle">' . esc_html( $final_shipping_label ) . '</div>';
				}
			}
		}		

		echo wp_kses( $tool_tip_html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() );
	}

}