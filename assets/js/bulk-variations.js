(function($) {
	$.extend({
		keys: function(obj) {
			var a = [];
			$.each(obj, function(k) {
				a.push(k)
			});
			return a;
		}
	});


	jQuery(document).on('wc_variation_form', function(e) {

		if ( $('div#matrix_form').length ) {
			var form = e.target;
			
			$(form).addClass('is_bulk');
			
			if (!$(form).data('bound')) {

				var info_row;
				var info_box;
				var info_row_index;
				var width = 0;

				$('.btn-single').click(function() {
					/** Modified displayed -- 03/02/2016 **/
					$('#matrix_form').slideUp('200', function() {
						$('form.variations_form').slideDown('400', function() {
						});
					});
					/****/
				});

				$('.btn-back-to-single').click(function() {
					/** Modified displayed -- 03/02/2016 **/
					$('#matrix_form').slideUp('200', function() {
						$('form.variations_form').slideDown('400', function() {
						});
					});
					/****/
				});

				$('.btn-bulk').click(function() {
					/** Modified displayed -- 03/02/2016 **/
					$('form.variations_form').slideUp('200', function() {
						$('#matrix_form').slideDown('400', function() {
							$('#qty_input_0').focus();
						});
					});
					/****/

				});

				$('.qty_input').focus(function() {

					var $tr = $(this).closest('tr');
					$('td', '#matrix_form_table').removeClass('active');
					$(this).closest('td').addClass('active');
					/** Remove effect click input (no displayed info product) **/
				});



				//Setup the validation
				$("#wholesale_form").validate({
					errorElement: "div",
					wrapper: "div", // a wrapper around the error message
					errorPlacement: function(error, element) {
						offset = element.offset();
						error.insertBefore(element)
						error.addClass('message');  // add a class to the wrapper
						error.css('position', 'absolute');
						error.css('left', offset.left + element.outerWidth());
						error.css('top', offset.top);
					}
				});


				$('.qty_input').each(function(index, element) {

					var manage_stock = $(element).data('manage-stock') == 'yes';
					var stock_max = $(element).data('max');
					var in_stock = $(element).data('instock');
					var backorders = $(element).data('backorders');
					var vmsg = $(element).data('vmsg');

					if (manage_stock && !backorders) {
						$(element).rules('add', {
							max: stock_max,
							messages: {
								max: vmsg
							}
						});
					}

				});

			}
		}
	});


})(jQuery);

jQuery(document).ready(function($) {



});