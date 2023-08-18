<?php
	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
	
    $shipping_method_format    			= get_option( 'md_woocommerce_shipping_method_format' );
    $afrsm_force_customer_to_select_sm  = get_option( 'afrsm_force_customer_to_select_sm' );
    if ( afrsfw_fs()->is__premium_only() ) {
        if ( afrsfw_fs()->can_use_premium_code() ) {
            $what_to_do_method 						= get_option( 'what_to_do_method' );
            $afrsm_hide_other_shipping 				= get_option( 'afrsm_hide_other_shipping' );
            $combine_default_shipping_with_forceall = get_option( 'combine_default_shipping_with_forceall' );
            $forceall_label                         = get_option( 'forceall_label' );
            
        }
    }
?>
<div class="afrsm-section-left">
    <div class="afrsm-main-table res-cl element-shadow">
        <h2><?php esc_html_e( 'General Settings', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
        <table class="table-mastersettings table-outer" cellpadding="0" cellspacing="0">
            <tbody>
                <?php
                    if ( afrsfw_fs()->is__premium_only() ) {
                        if ( afrsfw_fs()->can_use_premium_code() ) {
                            ?>
                            <tr class="mastersettings-raw">
                                <td class="table-whattodo"><?php esc_html_e( 'Show type of shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                                <td>
                                    <select name="what_to_do_method" id="what_to_do_method">
                                        <option value="allow_customer"<?php echo ( isset( $what_to_do_method ) && 'allow_customer' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Allow customer to choose', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                        <option value="apply_highest"<?php echo ( isset( $what_to_do_method ) && 'apply_highest' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Apply Highest', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                        <option value="apply_smallest"<?php echo ( isset( $what_to_do_method ) && 'apply_smallest' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Apply Smallest', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                        <option value="force_all"<?php echo ( isset( $what_to_do_method ) && 'force_all' === $what_to_do_method ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Force all shipping methods', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                <tr valign="top" id="display_mode">
                    <td class="table-whattodo"><?php esc_html_e( 'Shipping Display Mode', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                    <td>
                        <select name="shipping_display_mode" id="shipping_display_mode">
                            <option value="radio_button_mode"<?php echo ( isset( $shipping_method_format ) && 'radio_button_mode' === $shipping_method_format ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Radio buttons', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                            <option value="dropdown_mode"<?php echo ( isset( $shipping_method_format ) && 'dropdown_mode' === $shipping_method_format ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Dropdown', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                        </select>
                    </td>
                </tr>
                <?php
                    if ( afrsfw_fs()->is__premium_only() ) {
                        if ( afrsfw_fs()->can_use_premium_code() ) {
                            ?>
                            <tr valign="top" id="combine_default_shipping_with_forceall_td">
                                <td class="table-whattodo"><?php esc_html_e( 'Display shipping method', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                                <td>
                                    <select name="combine_default_shipping_with_forceall"
                                            id="combine_default_shipping_with_forceall">
                                        <option value="woo_our"<?php echo ( isset( $combine_default_shipping_with_forceall ) && 'woo_our' === $combine_default_shipping_with_forceall ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Combine both shipping method. (Default WooCommerce and Our plugin\'s shipping method.)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                        <option value="our"<?php echo ( isset( $combine_default_shipping_with_forceall ) && 'our' === $combine_default_shipping_with_forceall ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Separate shipping method (only combine our plugin\'s shipping method.)', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                        <option value="all"<?php echo ( isset( $combine_default_shipping_with_forceall ) && 'all' === $combine_default_shipping_with_forceall ) ? ' selected=selected' : ''; ?>><?php esc_html_e( 'Combine all shipping', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" id="forceall_text">
                                <td class="table-whattodo"><?php esc_html_e( 'Forceall Label', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></td>
                                <td>
                                    <input type="text" name="forceall_label" id="forceall_label_id"
                                        value="<?php echo esc_attr( $forceall_label ); ?>"/>
                                </td>
                            </tr>
                            <tr valign="top" id="afrsm_hide_other_shipping">
                                <td class="table-whattodo"><?php esc_html_e( 'Hide other shipping method when free shipping is available', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><span class="afrsm-new-feture-master"></td>

                                <td>
                                    <input type="checkbox" name="afrsm_hide_other_shipping"
                                    id="afrsm_hide_other_shipping"
                                    class="afrsm_hide_other_shipping"
                                    value="on" <?php checked( $afrsm_hide_other_shipping, 'on' ); ?>>
                                </td>
                            </tr>
                            <?php
                            
                        }
                    }
                ?>
                <tr valign="top" id="afrsm_force_customer_to_select_sm">
                    <td class="table-whattodo"><?php esc_html_e( 'Want to force customers to select a shipping method?', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><span class="afrsm-new-feture-master"></td>

                    <td>
                        <input type="checkbox" name="afrsm_force_customer_to_select_sm"
                        id="afrsm_force_customer_to_select_sm"
                        class="afrsm_force_customer_to_select_sm"
                        value="on" <?php checked( $afrsm_force_customer_to_select_sm, 'on' ); ?>>
                    </td>
                </tr>
                <tr valign="top" id="afrsm_count_per_page">
                    <td class="table-whattodo"><?php esc_html_e( 'Number of shipping methods per page', 'advanced-flat-rate-shipping-for-woocommerce' ); ?><span class="afrsm-new-feture-master"></td>
                    <td>
                        <?php
                            $html = sprintf( '<p class="note"><b style="color: red;">%s</b>%s</p>',
                                esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
                                esc_html__( 'This setting has been moved to "Screen Options" on listing page.', 'advanced-flat-rate-shipping-for-woocommerce' )
                            );
                            echo wp_kses_post( $html );
                        ?>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <span class="button button-primary button-large" id="save_master_settings" name="save_master_settings"><?php esc_html_e( 'Save Master Settings', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-footer.php' ); ?>