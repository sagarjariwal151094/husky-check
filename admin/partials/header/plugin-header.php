<?php
	// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	global $afrsfw_fs;
	
    $plugin_slug = '';
    if ( $afrsfw_fs->is__premium_only() ) {
        if ( $afrsfw_fs->can_use_premium_code() ) {
            $plugin_slug = 'pro_flat_rate';
        } else {
            $plugin_slug = 'basic_flat_rate';
        }
    } else {
        $plugin_slug = 'basic_flat_rate';
    }

	$afrsm_admin_object = new Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin( '', '' );

?>
<div class="wrap">
    <div id="dotsstoremain" class="afrsm-section">
        <div class="all-pad">
            <?php $afrsm_admin_object->afrsm_get_promotional_bar( $plugin_slug ); ?>
            <hr class="wp-header-end" />
            <header class="dots-header">
                <div class="dots-plugin-details">
                    <div class="dots-header-left">
                        <div class="dots-logo-main">
                            <img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/advance-flat-rate.png' ); ?>">
                        </div>
                        <div class="plugin-name">
                            <div class="title"><?php echo esc_html( AFRSM_PRO_PLUGIN_NAME ); ?></div>
                        </div>
                        <span class="version-label"><?php echo esc_html( AFRSM_VERSION_LABEL ); ?></span>
                        <span class="version-number"><?php echo esc_html( AFRSM_PRO_PLUGIN_VERSION ); ?></span>
                    </div>
                    <div class="dots-header-right">
                        <div class="button-dots">
                            <a target="_blank" href="<?php echo esc_url('http://www.thedotstore.com/support/'); ?>">
                                <?php esc_html_e('Support', 'advanced-flat-rate-shipping-for-woocommerce') ?>
                            </a>
                        </div>
                        <div class="button-dots">
                            <a target="_blank" href="<?php echo esc_url('https://www.thedotstore.com/feature-requests/'); ?>">
                                <?php esc_html_e('Suggest', 'advanced-flat-rate-shipping-for-woocommerce') ?>
                            </a>
                        </div>
                        <div class="button-dots <?php echo $afrsfw_fs->is__premium_only() && $afrsfw_fs->can_use_premium_code() ? '' : 'last-link-button'; ?>">
                            <a target="_blank" href="<?php echo esc_url('https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce'); ?>">
                                <?php esc_html_e('Help', 'advanced-flat-rate-shipping-for-woocommerce') ?>
                            </a>
                        </div>
                        <?php
                        if ( afrsfw_fs()->is__premium_only() ) {
                            if ( afrsfw_fs()->can_use_premium_code() ) { ?>
                                <div class="button-dots">
                                    <a target="_blank" href="<?php echo esc_url('https://www.thedotstore.com/thedotstore-account/'); ?>">
                                        <?php esc_html_e('My Account', 'advanced-flat-rate-shipping-for-woocommerce') ?>
                                    </a>
                                </div>
                                <?php
                            }else{ ?>
                                <div class="button-dots">
                                    <a target="_blank" class="dots-upgrade-btn" href="<?php echo esc_url($afrsfw_fs->get_upgrade_url()); ?>">
                                        <?php esc_html_e('Upgrade', 'advanced-flat-rate-shipping-for-woocommerce') ?>
                                    </a>
                                </div> 
                            <?php 
                            } 
                        }else{ ?>
                            <div class="button-dots">
                                <a target="_blank" class="dots-upgrade-btn" href="<?php echo esc_url($afrsfw_fs->get_upgrade_url()); ?>">
                                    <?php esc_html_e('Upgrade', 'advanced-flat-rate-shipping-for-woocommerce') ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <?php
                    $current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $afrsm_admin_object->afrsm_pro_menus( $current_page );
                ?>
            </header>
            <div class="dots-settings-inner-main">
                <div class="dots-settings-left-side">
                    <?php $afrsm_admin_object->afrsm_submenus( $current_page );?>