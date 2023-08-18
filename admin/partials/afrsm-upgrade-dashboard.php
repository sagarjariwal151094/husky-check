<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @since   3.9.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
global $wcpffc_fs;
?>
	<div class="wcpfc-section-left">
		<div class="dotstore-upgrade-dashboard">
			<div class="premium-benefits-section">
				<h2><?php esc_html_e( 'Go Premium to Increase Profitability', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></h2>
				<p><?php esc_html_e( 'Three Benefits for Upgrading to Premium', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<span><?php esc_html_e('01', 'advanced-flat-rate-shipping-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Optimize Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Easily configure advanced  automated shipping.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('02', 'advanced-flat-rate-shipping-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Maximize Revenue', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Configure dynamic shipping charges based on specific user orders.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('03', 'advanced-flat-rate-shipping-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Faster Support', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Get direct access to our dedicated support team to answer questions and optimize plugin settings.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
					</div>
				</div>
			</div>
			<div class="premium-benefits-section unlock-premium-features">
				<p><span><?php esc_html_e( 'Unlock Premium Features', 'advanced-flat-rate-shipping-for-woocommerce' ); ?></span></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<h3><?php esc_html_e('Advanced Shipping Rules', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-cogs"></i></span>
						<p><?php esc_html_e('Easily set up advanced shipping rules based on various parameters such as product, category, tag, subtotal, quantity, and more.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-1.png' ); ?>" alt="<?php echo esc_attr('Advanced Shipping Rules', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Our powerful tool empowers online store owners to create unlimited advanced shipping rules that match their business needs perfectly. Set rules based on item quantity, product weight, categories, user roles, shipping class, and more.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
                                                    <li><?php esc_html_e('Reward loyal customers by providing free shipping on all orders for users with specific roles, enhancing their shopping satisfaction.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('For instance, add $20 shipping charges for orders with subtotals ranging from $101 to $150, according to the rule.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Flexible Tiered Shipping Solutions', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-location-arrow"></i></span>
						<p><?php esc_html_e('Our flat rate shipping formulas allow you to set shipping fees effortlessly, using various dynamic variables. ', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-2.png' ); ?>" alt="<?php echo esc_attr('Flexible Tiered Shipping Solutions', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Our cutting-edge plugin empowers you to effortlessly set up multiple shipping charges based on various ranges such as products, categories, shipping class, weight, total sale, and more.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('lets you offer special shipping rates to vendors in India. If a vendor (seller) adds \'product 1\' or \'product 2\' to their cart and their last order was worth at least 100.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Customers who pay using cash on delivery have a flat shipping fee of $5.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Personalized Shipping for Every User', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-user"></i></span>
						<p><?php esc_html_e('Set up user-based shipping costs to capitalize on demand from specific users or user groups.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-3.png' ); ?>" alt="<?php echo esc_attr('Personalized Shipping for Every User', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Our user-based shipping feature allows customized shipping options for different user roles or groups. Provide a tailored shipping experience that delights your customers and boosts loyalty.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('E.g, we can select user role from plugin setting like "Vendor", So once user login with vendor role then this shipping will apply.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('User-specific shipping charges', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Force All Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-compress"></i></span>
						<p><?php esc_html_e('Leverage user demand with the option to merge bulk orders of multiple products into one, non-optional shipping cost.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-4.png' ); ?>" alt="<?php echo esc_attr('Force All Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php echo sprintf( esc_html__('When multiple shipping methods are enabled, and If the store owner wants to charge the sum of all applicable shipping method charges, then this feature is very useful.', 'advanced-flat-rate-shipping-for-woocommerce'), 2 ); ?></p>
												<ul>
													<li><?php esc_html_e('Merge all shipping methods to one shipping method', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('This option will also provide to show highest or lowest availble shipping method', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Merge WooCommerce shipping method with us using this option', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Time-Bound Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-clock-o"></i></span>
						<p><?php esc_html_e('Effortlessly configure shipping for special days like festivals, nights, holidays, and more. Provide timely deliveries for a seamless customer experience.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-5.png' ); ?>" alt="<?php echo esc_attr('Time-Bound Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Timely Deliveries, Every Occasion. Our time-based shipping method lets you configure shipping options for festivals, nights, holidays, and more, ensuring a seamless customer experience.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('We can display shipping methods for deliveries from 25-12-2023 to 26-12-2023, between 3 PM to 6 PM.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('With our day-wise shipping feature, schedule deliveries based on specific days or weekdays. ', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Zone-Based Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-globe"></i></span>
						<p><?php esc_html_e('Benefit from assigning zone-based shipping costs defined by postcode, city, state, and more.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-6.png' ); ?>" alt="<?php echo esc_attr('Zone-Based Shipping', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('It allowing you to set customized shipping costs based on postcodes, cities, states, and more. Provide a seamless shopping experience with shipping fees specific to each customer\'s location. ', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Apply the designated shipping method for seamless delivery within European countries.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Our shipping solution allows you to ship to specific postcodes, cities, countries, and states from the list.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
                    <div class="feature-box">
						<h3><?php esc_html_e('Customize Free Shipping Rules', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-percent"></i></span>
						<p><?php esc_html_e('Effortlessly set conditions for free shipping based on order value or specific products, and coupon usage.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-7.png' ); ?>" alt="<?php echo esc_attr('Customize Free Shipping Rules', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Unlock the power to offer free shipping based on order amount, specific products, and coupon usage.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('E.g., Set free shipping for orders with a subtotal greater than $60. Exclude "Product 2" from the subtotal calculation.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('E.g., Customers can enjoy free shipping on their orders by using a coupon. To qualify, the coupon total must be greater than $50.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
                    <div class="feature-box">
						<h3><?php esc_html_e('Weight-Based Shipping Charges', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-balance-scale"></i></span>
						<p><?php esc_html_e('Our feature allows you to set shipping charges based on product weight. Provide accurate and fair shipping charges.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-8.png' ); ?>" alt="<?php echo esc_attr('Weight-Based Shipping Charges', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Our feature calculates shipping costs based on product weight, ensuring fair and reliable rates for customers.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('E.g., orders weighing 5 kgs or less have a flat shipping fee of $10. For each additional 2 kgs, $5 is added to the total shipping charge.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('E.g., 14 kgs order would have a shipping fee of $32.5, Calculation is like $10(base charge) + 14kgs of product weight in cart(where only add adition charge on 9kgs(14kgs - 5kgs )) which is calculate as $22.5', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
                    <div class="feature-box">
						<h3><?php esc_html_e('Import/Export Shipping Data', 'advanced-flat-rate-shipping-for-woocommerce'); ?></h3>
						<span><i class="fa fa-exchange"></i></span>
						<p><?php esc_html_e('Our feature allows seamless import and export of shipping methods and zones', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'advanced-flat-rate-shipping-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url( AFRSM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-img-9.png' ); ?>" alt="<?php echo esc_attr('Import/Export Shipping Data', 'advanced-flat-rate-shipping-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
                                                <p><?php esc_html_e('Our feature calculates shipping costs based on product weight, ensuring fair and reliable rates for customers. Enhance their satisfaction with a streamlined and transparent shipping strategy tailored to their order weight.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></p>
												<ul>
                                                    <li><?php esc_html_e('Streamline shipping setup with Import-Export. Effortlessly transfer shipping methods and optimize your logistics with ease.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
													<li><?php esc_html_e('Simplify zone setup with Import-Export. Seamlessly transfer shipping zones for efficient logistics management.', 'advanced-flat-rate-shipping-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="upgrade-to-premium-btn">
				<a href="<?php echo esc_url('https://www.thedotstore.com/flat-rate-shipping-plugin-for-woocommerce/') ?>" target="_blank" class="button button-primary"><?php esc_html_e('Upgrade to Premium', 'advanced-flat-rate-shipping-for-woocommerce'); ?><svg id="Group_52548" data-name="Group 52548" xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 27.263 24.368"><path id="Path_199491" data-name="Path 199491" d="M333.833,428.628a1.091,1.091,0,0,1-1.092,1.092H316.758a1.092,1.092,0,1,1,0-2.183h15.984a1.091,1.091,0,0,1,1.091,1.092Z" transform="translate(-311.117 -405.352)" fill="#fff"></path><path id="Path_199492" data-name="Path 199492" d="M312.276,284.423h0a1.089,1.089,0,0,0-1.213-.056l-6.684,4.047-4.341-7.668a1.093,1.093,0,0,0-1.9,0l-4.341,7.668-6.684-4.047a1.091,1.091,0,0,0-1.623,1.2l3.366,13.365a1.091,1.091,0,0,0,1.058.825h18.349a1.09,1.09,0,0,0,1.058-.825l3.365-13.365A1.088,1.088,0,0,0,312.276,284.423Zm-4.864,13.151H290.764l-2.509-9.964,5.373,3.253a1.092,1.092,0,0,0,1.515-.4l3.944-6.969,3.945,6.968a1.092,1.092,0,0,0,1.515.4l5.373-3.253Z" transform="translate(-285.455 -280.192)" fill="#fff"></path></svg></a>
			</div>
		</div>
	</div>
	</div>
</div>
</div>
</div>
<?php 
