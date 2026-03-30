jQuery(function ($) {

	//update code
		//Greeting message[new added feature]
		$(".addf-gr-hide-greeting-message-popup-btn").click(function(){
			$(".addf-greeting-message-popup").hide();
			$(".addf-gr-add-message-result-configuration").text(" ");
			$(".addf-add-greeting-message").prop('disabled', false);
			$(".addf-gift-registry-add-greeting-message-text-field").val("");
	
		});
	
		//greeting message addition popup show 
		$(document).on('click','.addf_gr_add_message_button',function(){
			$('.addf-add-greeting-message').text('Add message');
			$(".addf-greeting-message-popup").show();
			$(".addf_gr_post_id_to_be_edited").val($(this).data('gr_post_id'));
			$(".addf_gr_product_id").val($(this).data('product_id'));
		});
	
		//edit greeting message
		$(document).on('click','.addf_gr_edit_message_button',function(){
			$(".addf-greeting-message-popup").show();
			$(".addf_gr_post_id_to_be_edited").val($(this).data('gr_post_id'));
			$(".addf_gr_product_id").val($(this).data('product_id'));
			$(".addf-gift-registry-add-greeting-message-text-field").val($(this).data("gr_greeting_message"));
			$('.addf-add-greeting-message').text('Update message');
		})
		
		//delete greeting message
		$(document).on('click','.addf_gr_delete_message_button',function (event) {
	
				event.preventDefault();
				var addf_gr_post_id_tobe_updated           = $(this).data('gr_post_id');
				var addf_gr_product_id_tobe_updated        = $(this).data('product_id');
				jQuery.ajax({
					url: my_ajax_object.ajax_url, 
					type: 'POST',
					data: { 
						addify_gift_registry_nonce:my_ajax_object.nonce,
						action : 'addf_gift_registry_delete_a_greeting_message_from_registry', 
						addf_gr_post_id_tobe_updated: addf_gr_post_id_tobe_updated, 
						addf_gr_product_id_tobe_updated: addf_gr_product_id_tobe_updated,
					 },
					success: function(data){
						if('yes' == data['success']){
							$(".addf-gift-registry-add-greeting-message-text-field").val("");
							$('.existing-gift-registry-data-table_replace_data'+addf_gr_post_id_tobe_updated).replaceWith( data['addf_gift_registry_ajax_refresh_product_registry_at_buyer_side'] );	
						}
						else{
							$(".addf-deetion-ajax-error").show();
							$(".addf-deetion-ajax-error").css("color" , "red");
							$(".addf-deetion-ajax-error").css("text-align" , "center");
							$(".addf-deetion-ajax-error").text("Something went wrong please try again later");
						}
						
					}
				});
			});

		// Allow keyboard activation for icon controls used as buttons.
		$(document).on('keydown', '.addf_gr_edit_message_button, .addf_gr_delete_message_button', function (event) {
			if ('Enter' === event.key || ' ' === event.key) {
				event.preventDefault();
				$(this).trigger('click');
			}
		});
	
	
		
		$(".addf-add-greeting-message").prop('disabled', true);
		$(".addf-gift-registry-add-greeting-message-text-field").on('input',function(){
			if($(".addf-gift-registry-add-greeting-message-text-field").val() == ''){
				$(".addf-add-greeting-message").prop('disabled', true);
			}
			else{
				$(".addf-add-greeting-message").prop('disabled', false);
			}
		});
	
		//add greeting message
		$(".addf-gr-add-message-result-configuration").text(" ");
			$(document).on('click','.addf-add-greeting-message',function () {
				$(".addf-add-greeting-message").prop('disabled', true);
	
				event.preventDefault();
				var addf_gr_post_id_tobe_updated           = $(".addf_gr_post_id_to_be_edited").val();
				var addf_gr_product_id_tobe_updated        = $(".addf_gr_product_id").val();
				var addf_gr_greeting_message			   = $(".addf-gift-registry-add-greeting-message-text-field").val();
	
				jQuery.ajax({
					url: my_ajax_object.ajax_url, 
					type: 'POST',
					data: { 
						addify_gift_registry_nonce:my_ajax_object.nonce,
						action : 'addf_gift_registry_add_a_greeting_message_from_registry', 
						addf_gr_post_id_tobe_updated: addf_gr_post_id_tobe_updated, 
						addf_gr_product_id_tobe_updated: addf_gr_product_id_tobe_updated,
						addf_gr_greeting_message: addf_gr_greeting_message },
					success: function(data){
						if('yes' == data['success']){
							$(".addf-gr-add-message-result-configuration").text(" ");
							$(".addf-greeting-message-popup").hide();
							$(".addf-gift-registry-add-greeting-message-text-field").val("");
							$('.existing-gift-registry-data-table_replace_data'+addf_gr_post_id_tobe_updated).replaceWith( data['addf_gift_registry_ajax_refresh_product_registry_at_buyer_side'] );	
	
						}
						else{
							$(".addf-add-greeting-message").prop('disabled', false);
							$(".addf-gr-add-message-result-configuration").css("color","red");
							$(".addf-gr-add-message-result-configuration").text("Something went wrong please try again later");
						}
					}
				});
			});
	
	
			//Gift registry search[new added feature]
		$('#addf-gr-registry-search-select').select2({
			ajax: {
				url: my_ajax_object.ajax_url,
				dataType: 'json',
				type: 'POST',
				delay: 100,
				data: function (params) {
					return {
						q: params.term,
						action: 'addf_gift_registry_search',
						addify_gift_registry_nonce: my_ajax_object.nonce,
					};
				},
				processResults: function (data) {
					var options = [];
					if (data) {
						$.each(
							data,
							function ( index, text ) {
								options.push({ id: text[0], text: `<a class="addf-registry-search-result-link" href="${text[1]}">${text[0]}</a>` });
							}
						);
					}
					return { results: options };
				},
				cache: true
			},
			escapeMarkup: function (markup) {
				return markup;
			},
			multiple: false,
			placeholder: '<i class="fa fa-search"></i>  Search Gift Registry',
			// minimumInputLength: 3
		}).on('select2:select', function (e) {
			// Redirect logic when an option is selected
			var url = $(e.params.data.text).attr('href');
			window.location.href = url;
		});

	// End of update code

	// restricting add to cart
	if ( ( 'rest_user' == $(".addf_gr_rest_user_op").val() ) && ( '1' == $(".addf_gift_registry_seesion_add_To_cart").val() ) ) {
		$(".add_to_cart_button").removeClass("ajax_add_to_cart");
		$(".add_to_cart_button").addClass("addf_gr_restrict_add_to_cart");
		$(".single_add_to_cart_button").addClass("addf_gr_restrict_add_to_cart");
		$(".add_to_cart_button").removeAttr("href");
		$('.single_add_to_cart_button').removeAttr("type").attr("type", "button");
	} else {
		$(".add_to_cart_button").addClass("ajax_add_to_cart");
		$(".add_to_cart_button").removeClass("addf_gr_restrict_add_to_cart");
		$(".single_add_to_cart_button").removeClass("addf_gr_restrict_add_to_cart");
		$('.single_add_to_cart_button').attr("type", "submit");
	}

	$(".a_for_create_registry").click(function(){
		$(".register-new-gift-registry").show();
		$(".a_for_create_registry").hide();
		$(".empty_registry").hide();
		$("#addf-gr-registry-search-container").hide()
	});
	
	$(".a_for_create_registry_calcel").click(function(){
		$(".register-new-gift-registry").hide();
		$(".a_for_create_registry").show();
		$(".empty_registry").hide();
	});

	if ($(".addf_add_to_cart_quantity_console").val() == 0) {
		$(".ajax_add_to_cart").prop("disabled", true);
	}
	$(".addf_add_to_cart_quantity_console").change(function(){
		$(".ajax_add_to_cart"+$(this).data('product_id')).data('quantity' , $(this).val());
		if ($(this).val() == 0) {
			$(".ajax_add_to_cart"+$(this).data('product_id')).prop("disabled", true);
		} else {
			$(".ajax_add_to_cart"+$(this).data('product_id')).attr("disabled", false);
		}
	});
	
	//  for password protected registry
	$(".addf-gift-registry-visibility-private-tr").hide();
	if ($(".addf-gift-registry-visibility-pri").prop("checked") == true) {
		$(".addf-gift-registry-visibility-private-tr").show();
		$(".addf-gift-registry-visibility-private-pass").prop("required" , true);
	}
	$(".addf-gift-registry-visibility-pri").click(function(){
		if ($(this).prop("checked") == true) {
			$(".addf-gift-registry-visibility-private-tr").show();
			$(".addf-gift-registry-visibility-private-pass").prop("required" , true);
		}
	});
	$(".addf-gift-registry-visibility-pub").click(function(){
		if ($(this).prop("checked") == true) {
			$(".addf-gift-registry-visibility-private-tr").hide();
			$(".addf-gift-registry-visibility-private-pass").prop("required" , false);
		}
	});
	

	//  collapse able div
	$(".addf_gr_post_see_less").hide();
	$(".addf_gr_cursor_see_all").click(function(){
		if ( $(".addf_gr_post_see_less"+$(this).data('addf_gr_post_id')).is(':visible') ) {
			$(".addf_gr_post_see_less"+$(this).data('addf_gr_post_id')).hide('1000');
			$(".addf_gr_cursor_see_all"+$(this).data('addf_gr_post_id')).show();
			$(".addf_gr_cursor_see_less"+$(this).data('addf_gr_post_id')).hide();
		} else {
			$(".addf_gr_post_see_less"+$(this).data('addf_gr_post_id')).show('1000');
			$(".addf_gr_cursor_see_all"+$(this).data('addf_gr_post_id')).hide();
			$(".addf_gr_cursor_see_less"+$(this).data('addf_gr_post_id')).show();
		}
	});
	
	$(".addf_gift_registry_add_to_cart_from_product_save_to_directory").prop("disabled" , true);
	$(".addf_gift_registry_add_to_cart_from_product_registry_selected").change(function () {
		$(".addf_gift_registry_add_to_cart_from_product_save_to_directory").prop("disabled" , false);

	});
	$(".addf-add-product-from-registry-single-product").prop("disabled" , true);
	$(".addf-gift-registry-add-single-product-class").change(function () {
		$(".addf-add-product-from-registry-single-product").addClass("loading");
		jQuery.ajax({
			url: my_ajax_object.ajax_url, 
			type: 'POST',
			data: { 
				addify_gift_registry_nonce:my_ajax_object.nonce,
				action : 'addf_gift_registry_add_a_product_attr_selection', 
				product_id: $(this).val(), 
			},
			success: function(data){
				$(".addf-add-product-from-registry-single-product").removeClass("loading");
				if ( 'yes' == data['success']) {
					$(".addf_gr_select_attr_popup").html( data['addf_gift_registry_add_attr'] );
					if ( '' == $(".addf_gr_all_selection_verify").val() ) {
						$(".addf-add-product-from-registry-single-product").prop("disabled" , false );
					} else {
						$(".addf-add-product-from-registry-single-product").prop("disabled" , true );
					}
				} else {
				}
			}
		});
	});
	$(document).on('change' , '.addf_gr_select_attr' , function(){
		addf_gr_all_selection_verify       = $(".addf_gr_all_selection_verify").val();
		addf_gr_all_selection_verify_array = addf_gr_all_selection_verify.split('&');
		var addf_gr_check_sel              = 0;
		var addf_selection_verify_vales    = "";
		for (let index = 0; index < addf_gr_all_selection_verify_array.length; index++) {
			if ( '' == $( "#"+addf_gr_all_selection_verify_array[index]).val() ) {
				addf_gr_check_sel = 1;
			}
		}
		if ( 0 == addf_gr_check_sel ) {
			for (let index = 0; index < addf_gr_all_selection_verify_array.length; index++) {
				if ( '' != addf_gr_all_selection_verify_array[index] ) {
					addf_selection_verify_vales += addf_gr_all_selection_verify_array[index] + "=" + $( "#"+addf_gr_all_selection_verify_array[index]).val() + "&";
				}
			}
			$(".addf_gr_all_selection_verify_vales").val( addf_selection_verify_vales );
			$(".addf-add-product-from-registry-single-product").prop("disabled" , false );
		} else {
			$(".addf-add-product-from-registry-single-product").prop("disabled" , true );
		}
	});

	$(".addf_gift_registry_send_mail").click(function(){
		$(".addf_gift_registry_share_email_cover").show();
		$(".addf_share_mail_gift_registry_btn_submit_post_value").val($(this).data('addf_g_r_post_id'));
		$(".addf_share_mail_gift_registry_btn_submit_post_userid").val($(this).data('user_id'));
	});
	$(".addf_share_mail_gift_registry_btn_submit").click(function(){
		// $(".addf_gift_registry_share_email_cover").hide();
	});
	$(".addf_share_mail_gift_registry_btn_cancel").click(function(){
		$(".addf_gift_registry_share_email_cover").hide();
	});

	//  min date
	var now = new Date(),
	minDate = now.toISOString().substring(0,10);
	$('.addf-gift-registry-event-date').prop('min', minDate);
	//  add to gift registry using btn
	
	$(".addf_gift_registry_add_to_cart_from_product").click(function(){
		$(".addf_gift_registry_add_to_cart_from_product_div_cover").show();
		if ($(".qty").val()) {
			var addf_g_r_id_qty = $(this).data('id')+":"+$(".qty").val();
		} else {
			var addf_g_r_id_qty = $(this).data('id')+":"+"1";
		}
		$(".addf_gift_registry_add_to_cart_from_product_save_to_directory").data('id' ,addf_g_r_id_qty );
	});
	$(".addf_gift_registry_add_to_cart_from_product_div_cover_close_btn").click(function(){
		$(".addf_gift_registry_add_to_cart_from_product_div_cover").hide();
	});
	$(document).on('click','.addf_gift_registry_add_to_cart_from_product_save_to_directory',function () {
		var gift_registry_selected             = $(".addf_gift_registry_add_to_cart_from_product_registry_selected").val();
		var gift_registry_product_combined     = $(".addf_gift_registry_add_to_cart_from_product_save_to_directory").data("id");
		var gift_registry_product_combine      = gift_registry_product_combined.split(":");
		var gift_registry_product_selected     = gift_registry_product_combine[0];
		var gift_registry_product_qty_selected = gift_registry_product_combine[1];
		jQuery.ajax({
			url: my_ajax_object.ajax_url,
			type: 'POST',
			data: { 
				addify_gift_registry_nonce:my_ajax_object.nonce,
				action : 'addf_gift_registry_add_a_product_through_button', 
				gift_registry_selected: gift_registry_selected, 
				addf_gr_attr_for_variation: $(".addf_gr_attr_for_variation").val(), 
				gift_registry_product_qty_selected: gift_registry_product_qty_selected, 
				gift_registry_product_selected: gift_registry_product_selected },
			success: function(data){
				if ( 'yes' == data['success']) {
					$(".addf_gift_registry_add_to_cart_from_product_div_cover").hide();
					$(".addf_gr_product_success_added_to_cart").html(data['message']);
				} else {
					$(".addf_gr_product_success_added_to_cart").html(data['message']);
					$(".addf_gift_registry_add_to_cart_from_product_div_cover").show();
				}
				$("html, body").animate({ scrollTop: 0 }, "slow");
			}
		  });
	});




	$(".addf-deetion-ajax-error").text(" ");
	//  del a product from gift query
	$(document).on('click','.addf-delete-product-from-registry',function (event) {
		event.preventDefault();
		$(".addf-deetion-ajax-error").text(" ");
		var $deleteBtn = $(this);
		var $row = $deleteBtn.closest('tr');
		// Delete id
		var addf_data_recieved = ($deleteBtn.data('id')).split(":");
		var product_id         = addf_data_recieved[0];
		var post_id            = addf_data_recieved[1];
		data                   = "";
		jQuery.ajax({
			url: my_ajax_object.ajax_url, // in backend you should pass the ajax url using this variable
			type: 'POST',
			data: { 
				addify_gift_registry_nonce:my_ajax_object.nonce,
				action : 'addf_gift_registry_delete_a_product_from_registry', 
				post_id: post_id, 
				product_id: product_id },
			success: function(data){
				if ( data['success'] ) {
					$row.css('background','tomato');
					$row.fadeOut(250,function(){
						$(this).remove();
					});
				} else {
					$(".addf-deetion-ajax-error").show();
					$(".addf-deetion-ajax-error").css("color" , "red");
					$(".addf-deetion-ajax-error").css("text-align" , "center");
					$(".addf-deetion-ajax-error").text("Something went wrong please try again later");
				}
			}
		});
	});
	$(document).on('click', '.addf-g-r-bg-cover-hide-btn', function(){
		$(".addf-g-r-bg-cover").hide();
		$("body").removeClass("addf-gr-no-scroll");
		$(".addf-gr-result-configuration").text(" ");
		$(".addf-add-product-from-registry-single-product").prop('disabled', true);
		$(".addf-gift-registry-add-single-product-quantity").val("1"); 
		$(".addf-gift-registry-add-single-product-class").empty(); 
		$('.addf-gift-registry-add-single-product-class').append('<option hidden selected>Select a product</option>');
	});
	// add a new product    wp_ajax_addf_gift_registry_add_a_product_from_registry
	$(document).on('click', '.addf-add-product-from-registry', function(event){
		event.preventDefault();

		$(".addf-g-r-bg-cover").show();
		$("body").addClass("addf-gr-no-scroll");
		$(".addf_gr_post_id_to_be_edited").val($(this).data('id'));

		// $(this).css("opacity", '0.3');
	});


	//  add to cart single product using ajax
	$(".product_type_simple_add_to_cart_bulk_button").prop("disabled",true);
	function addfParseBulkProductIds(rawIds) {
		return String(rawIds || '')
			.split(',')
			.map(function(val){ return $.trim(val); })
			.filter(function(val){ return val !== '' && val !== '0'; });
	}

	$(document).on('click','.add_to_cart_from_addf_g_r_checkbox_bulk',function () {
		var $bulkIdsField = $(".product_type_simple_add_to_cart_bulk_button_all_ids_add_to_cart");
		var ids = addfParseBulkProductIds($bulkIdsField.val());
		var selectedId = String($(this).data('product_id'));

		if ($(this).prop("checked") == true) {
			if ($.inArray(selectedId, ids) === -1) {
				ids.push(selectedId);
			}
		} else {
			ids = $.grep(ids, function(item){ return item !== selectedId; });
		}

		$bulkIdsField.val(ids.join(','));
		$(".product_type_simple_add_to_cart_bulk_button").prop("disabled", ids.length === 0);
	});

	$(document).on('click','.product_type_simple_add_to_cart_bulk_button',function (event) {
		event.preventDefault();
		$(this).addClass('loading');
		$(".product_type_simple_add_to_cart_bulk_button").prop("disabled",true);
		var product_ids                  = $(".product_type_simple_add_to_cart_bulk_button_all_ids_add_to_cart").val();
		var product_ids_array            = addfParseBulkProductIds(product_ids);
		var addf_gr_max_check            = 0;
		var current_quantities = "";

		if (product_ids_array.length === 0) {
			$('.product_type_simple_add_to_cart_bulk_button').removeClass('loading');
			$(".product_type_simple_add_to_cart_bulk_button").prop("disabled",true);
			return;
		}

		for ( var inc = 0; inc < product_ids_array.length; inc++ ) {
			var current_index      = product_ids_array[inc];
			var addf_gr_cur_val    = $(".addf-gr-desire-product" + current_index).val();
			var addf_gr_max_val    = $(".addf-gr-desire-product" + current_index).prop('max');
			if ( 0 < ( addf_gr_cur_val - addf_gr_max_val ) ) {
				++addf_gr_max_check;
				jQuery.ajax({
					url: my_ajax_object.ajax_url, 
					type: 'POST',
					data: {
						addify_gift_registry_nonce:my_ajax_object.nonce,
						action : 'addf_gift_registry_check_qty_single', 
						qty: addf_gr_cur_val, 
					},
					success: function(data){
						if ( data['success'] ) {
							$('.product_type_simple_add_to_cart_bulk_button').removeClass('loading');
							$(".addf_gr_restrict_cart_have_items").html(data['message']);
							$("html, body").animate({ scrollTop: 0 }, "slow");
							$(".product_type_simple_add_to_cart_bulk_button_all_quantities_add_to_cart");
							$(".add_to_cart_from_addf_g_r_checkbox_bulk").prop("checked",false);
							$(".product_type_simple_add_to_cart_bulk_button_all_ids_add_to_cart").val("");
							$(".addf-gr-desire-product_public").val("1");
						}
					}
				});
			} else {
				var current_qty = $(".addf-gr-desire-product" + current_index).val();
				current_quantities = current_quantities ? (current_quantities + "," + current_qty) : current_qty;
				$(".product_type_simple_add_to_cart_bulk_button_all_quantities_add_to_cart").val(current_quantities);
			}
			

		}
			var addf_quantities_for_products = $(".product_type_simple_add_to_cart_bulk_button_all_quantities_add_to_cart").val();
		if ( 0 == addf_gr_max_check ) {
			jQuery.ajax({
				url: my_ajax_object.ajax_url, 
				type: 'POST',
				data: {
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action : 'addf_gift_registry_add_to_cat_bulk_action', 
					product_ids: product_ids, 
					gr_post_id: $(this).data('gr_post_id'), 
					addf_quantities_for_products: addf_quantities_for_products,
				},
				success: function(data){
					$(".addf_gr_restrict_cart_have_items").html(data['message']);
					if ( data['success'] ) {
						$(".product_type_simple_add_to_cart_bulk_button_all_quantities_add_to_cart").val("");
						$(".product_type_simple_add_to_cart_bulk_button").prop("disabled",false);
						$(".add_to_cart_from_addf_g_r_checkbox_bulk").prop("checked",false);
						$('.product_type_simple_add_to_cart_bulk_button').removeClass('loading');
						$(".addf_gr_add_t_cart_succ_msg").show();
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.ajax_add_to_cart_public_addf_g_r').removeClass('loading');
						if('' != my_ajax_object.addf_gr_redirect_page_url){
							window.location.href = my_ajax_object.addf_gr_redirect_page_url;
						}
					} else {
						$(".add_to_cart_from_addf_g_r_checkbox_bulk").prop("checked",false);
						$(".product_type_simple_add_to_cart_bulk_button").prop("disabled",false);
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('.ajax_add_to_cart_public_addf_g_r').removeClass('loading');
						$('.product_type_simple_add_to_cart_bulk_button').removeClass('loading');
						$('.ajax_add_to_cart_public_addf_g_r').removeClass('loading');
					}
					$(".product_type_simple_add_to_cart_bulk_button_all_ids_add_to_cart").val("");
				}
			});
		}
	});

	//  restrict add to cart
	$(".addf_gr_restrict_add_to_cart_info").hide();
	$(".addf_gr_restrict_add_to_cart").click(function(){
		$(".addf_gr_restrict_add_to_cart_info").show();
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});



	$(document).on('click','.ajax_add_to_cart_public_addf_g_r',function (event) {
		event.preventDefault();
		$(this).addClass('loading');
		var product_id           = $(this).data('product_id');
		var quantity_for_product = $(".addf-gr-desire-product"+product_id).val();
		if ( 0 > ($(".addf-gr-desire-product"+product_id).prop('max') ) - ($(".addf-gr-desire-product"+product_id).val()) ) {
			jQuery.ajax({
				url: my_ajax_object.ajax_url, 
				type: 'POST',
				data: {
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action : 'addf_gift_registry_check_qty_single', 
					qty: $(".addf-gr-desire-product"+product_id).val(),
					rule_id 	: $(this).data('gr_post_id'),
				},
				success: function(data){
					if ( data['success'] ) {
						$('.ajax_add_to_cart_public_addf_g_r').removeClass('loading');
						$(".addf_gr_restrict_cart_have_items").html(data['message']);
						$("html, body").animate({ scrollTop: 0 }, "slow");
					}
				}
			});
		} else {
			jQuery.ajax({
				url: my_ajax_object.ajax_url, 
				type: 'POST',
				data: {
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action : 'addf_gift_registry_add_to_cat_single', 
					product_id: product_id, 
					gr_post_id: $(this).data('gr_post_id'),
					quantity_for_product: quantity_for_product,
				},
				success: function(data){
					if ( data['success'] ) {
						$('.ajax_add_to_cart_public_addf_g_r').removeClass('loading');
						$(".addf_gr_restrict_cart_have_items").html(data['message']);
						$("html, body").animate({ scrollTop: 0 }, "slow");
						if('' != my_ajax_object.addf_gr_redirect_page_url){
							window.location.href = my_ajax_object.addf_gr_redirect_page_url;
						}
					} else {
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$(".addf_gr_restrict_cart_have_items").html(data['message']);
						$('.ajax_add_to_cart_public_addf_g_r').removeClass('loading');
					}
				}
			});
		}
	});
	//  for empty registry list 
	$(document).on('click' , '.addf_gr_empty_reg_btn' , function(){
		$(".addf_gr_empty_reg_msg").show();
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	// for privacy policy 
	if ( $(".addf_gr_addf_gr_enable_pp_cb").val() == 'yes' ) {
		$("#gift-registry-submit").prop("disabled" , true );
		if ( $('#addf_gr_agree_pp_cb').prop("checked") == true ) {
			$("#gift-registry-submit").prop("disabled" , false );
		}
		$(document).on('click' , '#addf_gr_agree_pp_cb' , function(){
			if ( $(this).prop("checked") == true ) {
				$("#gift-registry-submit").prop("disabled" , false );
			} else {
				$("#gift-registry-submit").prop("disabled" , true );
			}
		});
	}

	//  for editing registry information
	$(".addf_gr_edit_r").css('cursor', 'pointer');
	$(".addf_gr_edit_registry_new").hide();
	$(".addf_gr_edit_r").click(function(){
		$(".addf_gr_edit_registry").hide();
		$(".addf_gr_edit_registry_new").show();
	});
	// hide update from
	$(".addf_gr_close_edit_btn").click(function(){
		$(".addf_gr_edit_registry").show();
		$(".addf_gr_edit_registry_new").hide();
	});

	$(document).on("click",".addf_gr_delete_r",function(){

		var addf_gr_post_id_tobe_deleted = $("#addf_gr_current_registry_id").data("registry-id");

		jQuery.ajax({
				url: my_ajax_object.ajax_url, 
				type: 'POST',
				data: { 
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action : 'addf_gift_registry_delete_registry', 
					addf_gr_post_id_tobe_deleted: addf_gr_post_id_tobe_deleted, 
				},
				success: function(data){
					if ( 'yes' == data['success']) {
						$(".addf_gr_registry_delete_success_error").html(data['message']);
						$("html, body").animate({ scrollTop: 0 }, "slow");
						setTimeout(function() {
					        window.location.href = data['base_url']+'/addf_gift_registry';
					      }, 1000);
					} else {
						$(".addf_gr_registry_delete_success_error").html(data['message']);
						$("html, body").animate({ scrollTop: 0 }, "slow");
					}
				}
			});

	});

	
	$(".addf-gr-result-configuration").text(" ");
		$(document).on('click','button.addf-add-product-from-registry-single-product',function (event) {
			$(".addf-add-product-from-registry-single-product").prop('disabled', true);

			event.preventDefault();
			var addf_gr_post_id_tobe_updated           = $(".addf_gr_post_id_to_be_edited").val();
			var addf_gr_product_id_tobe_updated        = $(".addf-gift-registry-add-single-product-class").val();
			var addf_gr_product_quantity_to_be_updated = $(".addf-gift-registry-add-single-product-quantity").val();
			jQuery.ajax({
				url: my_ajax_object.ajax_url, 
				type: 'POST',
				data: { 
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action : 'addf_gift_registry_add_a_new_product_from_registry', 
					addf_gr_all_selection_verify_vales: $(".addf_gr_all_selection_verify_vales").val(), 
					addf_gr_post_id_tobe_updated: addf_gr_post_id_tobe_updated, 
					addf_gr_product_id_tobe_updated: addf_gr_product_id_tobe_updated,
					addf_gr_product_quantity_to_be_updated: addf_gr_product_quantity_to_be_updated },
				success: function(data){
					if ( 'yes' == data['success']) {
						$(".addf-add-product-from-registry-single-product").prop('disabled', true);
						$(".addf-gr-result-configuration").text(" ");
						$(".addf-g-r-bg-cover").hide();
						$("body").removeClass("addf-gr-no-scroll");
						$('.existing-gift-registry-data-table_replace_data'+addf_gr_post_id_tobe_updated).replaceWith( data['addf_gift_registry_ajax_refresh_table'] );
						$(".addf-gift-registry-add-single-product-quantity").val("1"); 
						$(".addf-gift-registry-add-single-product-class").empty(); 
						$('.addf-gift-registry-add-single-product-class').append('<option hidden selected>Select a product</option>');
					} else {
						$(".addf-add-product-from-registry-single-product").prop('disabled', false);
						$(".addf-gr-result-configuration").css("color","red");
						$(".addf-gr-result-configuration").text("Something went wrong please try again later");
					}
				}
			});
		});
});


	jQuery(function($){
		$(".addf_gift_registry_cart_frm_btn_hide").show();
		$(".addf_gift_registry_cart_frm_btn_show").hide();
		$(document).on('show_variation' , 'form.variations_form' , function(event, data){
			var variation_id = data.variation_id;
			data_attributes  = data.attributes;
			$(".addf_gift_registry_cart_frm_btn_show").data( 'id' , variation_id );
			jQuery.ajax({
				url: my_ajax_object.ajax_url, 
				type: 'POST',
				data: { 
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action : 'addf_gift_registry_check_is_instock', 
					variation_id: variation_id ,
					data_attributes: data_attributes 
				},
				success: function(data){
					if ( data['success'] ) {
						var addf_gr_var_attr = true;
						if ( null != data['addf_gr_key_send'] ) {
							var addf_gr_key_send     = data['addf_gr_key_send'].split('&');
							var addf_gr_sel_var_attr = "";
							for (let index = 0; index < addf_gr_key_send.length; index++) {
								if ( '' != addf_gr_key_send[index] ) {
									addf_gr_sel_var_attr += "&" + addf_gr_key_send[index] + "=" + $("[name="+addf_gr_key_send[index] +"]").val();
									if ( '' == $("[name="+addf_gr_key_send[index] +"]").val() ) {
										addf_gr_var_attr = false;
									}
								}
							}
						} else {
							var addf_gr_sel_var_attr = "";
						}
						if ( addf_gr_var_attr ) {
							$(".addf_gr_attr_for_variation").val( addf_gr_sel_var_attr );
							$(".addf_gift_registry_cart_frm_btn_show").show();
							$(".addf_gift_registry_cart_frm_btn_hide").hide();
						} else {
							$(".addf_gift_registry_cart_frm_btn_hide").show();
							$(".addf_gift_registry_cart_frm_btn_show").hide();
						}
					} else {
						$(".addf_gift_registry_cart_frm_btn_hide").show();
						$(".addf_gift_registry_cart_frm_btn_show").hide();
					}
				}
			});
		} );
		$(document).on('hide_variation' , 'form.variations_form' , function(){
			   $(".addf_gift_registry_cart_frm_btn_hide").show();
			   $(".addf_gift_registry_cart_frm_btn_show").hide();
		});
	});
	(function ($) {
		'use strict';
		$(function () {
			$('.addf_js_multiproduct_select').select2({
				ajax: {
					dataType: "json",
					url: my_ajax_object.ajax_url,
					delay: 250, 
					data: function (params) {
						return {
							q: params.term, 
							addify_gift_registry_nonce:my_ajax_object.nonce,
							action: 'addf_gift_registry_getproductsearch'
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							// data is the array of arrays, and each of them contains ID and the Label of the option
							$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
								options.push( { id: text[0], text: text[1]  } );
							});
						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3 // the minimum of symbols to input before perform a search
			});
		});

			
			
		//  select product
		$(function () {
			$('.addf-gift-registry-add-single-product-class').select2({
				ajax: {
					url: my_ajax_object.ajax_url, 
					dataType: 'json',
					delay: 250, 
					data: function (params) {
						return {
							q: params.term, 
							addify_gift_registry_nonce:my_ajax_object.nonce,
							action: 'addf_gr_single_Product_and_variation' 
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							$.each( data, function( index, text ) { 
								options.push( { id: text[0], text: text[1]  } );
							});
						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3 
			});
		});

		function search_country_state(country,state) {
			jQuery.ajax(
			{
				url: my_ajax_object.ajax_url,
				type: 'POST',
				data:{
					addify_gift_registry_nonce:my_ajax_object.nonce,
					action: 'addf_gr_search_country_state_ajax',
					addf_country: country,
					addf_state  : state
				},

				success: function (response) {
					jQuery( '.addf_gr_guest_state' ).html( response );
				}
		});
	}
	
	// Trigger the AJAX function on page load if the country value is not empty
	jQuery(document).ready(function () {
		var state_value = $(".addf_state_value").val();
		var initial_country_value = jQuery('.addf_gr_countries_billing_front').val();
		if (initial_country_value !== '') {
			search_country_state(initial_country_value,state_value);
		}
	});
	
	// Trigger the AJAX function on change event
	jQuery(document).on('change', ".addf_gr_countries_billing_front", function () {
		var state_value = $(".addf_state_value").val();
		var country = jQuery(this).val();
		search_country_state(country,state_value);
	});

	})(jQuery);
