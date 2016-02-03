<?php
/** Remove column variable -- 03/02/2016 **/
global $woocommerce, $wc_bulk_variations, $post;

//Build the matrix data
if ( WC_Bulk_Variations_Compatibility::is_wc_version_gte_2_4() ) {
	$matrix_data = woocommerce_bulk_variations_create_matrix_v24( $post->ID );
} else {
	$matrix_data = woocommerce_bulk_variations_create_matrix( $post->ID );
}

$matrix = $matrix_data['matrix'];
$matrix_rows = $matrix_data['matrix_rows'];
$row_attribute = $matrix_data['row_attribute'];

//Set up some locals
$row_index = 0;
$cell_index = 0;
$info_boxes = array();
?>

<?php do_action( 'woocommerce_bv_before_add_to_cart_form' ); ?>


<div id="matrix_form">

	<form id="wholesale_form" action="" class="bulk_variations_form cart matrix" method="post" enctype='multipart/form-data'>
		<table id="matrix_form_table">
			<thead>
			</thead>
			<tbody>
				<?php foreach ( $matrix as $row => $field_data ) : ?>
					<tr class="<?php echo $row_index % 2 == 0 ? '' : 'alt'; ?>" data-index="<?php echo $row_index; ?>">
						<td class="row-label"><?php echo woocommerce_bulk_variations_get_title( $row_attribute, $matrix_rows[$row_index] ); ?></td>

							<?php $variation = new WC_Product_Variation( $field_data['variation_id'] ); ?>

							<?php
							$managing_stock = $variation->manage_stock;

							$vmsg = $variation->get_stock_quantity() ? sprintf(__('Only %s available', 'wc_bulk_variations'), $variation->get_stock_quantity()) : sprintf(__('Currently unavailable', 'wc_bulk_variations'));
							?>

							<td>
								<?php if ( $field_data ) : ?>
									<input 
										data-manage-stock="<?php echo $managing_stock; ?>"
										data-purchasable="<?php echo $variation->is_purchasable() ? '1' : '0'; ?>"
										data-instock="<?php echo $variation->is_in_stock() ? '1' : '0'; ?>"
										data-backorders="<?php echo $variation->backorders_allowed() ? '1' : '0'; ?>"
										data-max="<?php echo $variation->get_stock_quantity(); ?>"
										data-price="<?php echo $variation->get_price(); ?>"
										data-vmsg="<?php echo $vmsg; ?>"
										title="<?php echo esc_attr( woocommerce_bulk_variations_get_price( $variation->get_price() ) ); ?>"
										id="qty_input_<?php echo $cell_index; ?>"
										data-column="<?php echo $column_index; ?>" 
										class="number qty_input" 
										type="text" 
										name="order_info[<?php echo $cell_index; ?>][quantity]" 
										/>
										<?php if ( $wc_bulk_variations->get_setting( 'show_prices_in_grid', true ) ) : ?>
										<p><?php echo $field_data['price_html']; ?></p>
									<?php endif; ?>

									<?php $info_boxes['qty_input_' . $cell_index . '_info'] = array($row_attribute => $row, 'variation_data' => $field_data, 'variation' => $variation); ?>

									<input type="hidden" name="order_info[<?php echo $cell_index; ?>][variation_id]" value="<?php echo $field_data['variation_id']; ?>" />
									<input type="hidden" name="order_info[<?php echo $cell_index; ?>][variation_data]" value="<?php echo $key; ?>" />
									<input type="hidden" name="order_info[<?php echo $cell_index; ?>][variation_data][attribute_<?php echo $row_attribute; ?>]" value="<?php echo $row; ?>" />
								<?php else : ?>

								<?php endif; ?>
							</td>
							<?php
							$cell_index++;
							?>
						<?php if ( $wc_bulk_variations->get_setting( 'use_quantity_selectors', false ) ) : ?>
							<td width= "32px">
								<div class="quantity buttons_added">
									<input type="hidden" name="order_info[<?php echo $cell_index; ?>][quantity]" value="1" />
									<input type="button" value="-"  class="minus" rel="<?php echo $row_index; ?>">
									<input type="button" value="+"  class="plus" rel="<?php echo $row_index; ?>">
								</div>
							</td>
						<?php endif; ?>
					</tr>

					<?php $row_index++; ?>


				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<?php do_action( 'woocommerce_bv_before_add_to_cart_button' ); ?>
						<button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters( 'single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), 'variable' ); ?></button>
						<?php do_action( 'woocommerce_bv_after_add_to_cart_button' ); ?>
					</td>
				</tr>
			</tfoot>
		</table>

		<div>
			<input type="hidden" name="add-variations-to-cart" value="true" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
		</div>
	</form>

	<input class="button btn-back-to-single" type="button" value="<?php _e( '<-- Singular Order Form', 'wc_bulk_variations' ); ?>" />
	<div id="matrix_form_info_holder" style="display:none;" >
		<?php foreach ( $info_boxes as $key => $field_data ) : ?>

			<?php $variation = $field_data['variation']; ?>

			<div id="<?php echo $key; ?>" class="qty_input_info" >
				<div class="images">
					<?php echo $variation->get_image(); ?>
				</div>
				<div class="summary">
					<p itemprop="name" class="product_title entry-title"><?php echo $variation->get_title(); ?></p>
					<?php echo $variation->get_price_html(); ?>
					<ul>
						<li><?php echo WC_Bulk_Variations_Compatibility::wc_attribute_label( $row_attribute ); ?>: <?php echo woocommerce_bulk_variations_get_title( $row_attribute, $field_data[$row_attribute] ); ?></li>

						<?php if ( $variation->sku ) : ?>
							<li><?php echo $field_data['variation_data']['sku']; ?></li>
						<?php endif; ?>

					</ul>


					<?php echo $field_data['variation_data']['availability_html'] ? $field_data['variation_data']['availability_html'] : '<p class="stock">&nbsp;</p>'; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<?php do_action( 'woocommerce_bv_after_add_to_cart_form' ); ?> 
