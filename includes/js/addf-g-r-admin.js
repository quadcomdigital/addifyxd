jQuery(function ($) {
	//  min date
	var now = new Date(),
	minDate = now.toISOString().substring(0,10);
	// $('.addf-gift-registry-event-date').prop('min', minDate);

	$('#title').prop("required","true");

	//  settings tabs
	$(".addf_gf_gen_tab").css('background-color' , 'rgb(190, 187, 187)');
	$(".addf_gf_gen_tab").css('color' , 'white');
	($(".addf_gr-addf_gift_registry_syntax_mail_editor").closest('tr')).hide();
	($(".addf_gf_social_tab_ops").closest('tr')).hide();
	($(".addf_gf_email_tab_ops").closest('tr')).hide();
	($(".addf_gr_editors").closest('tr')).hide();
	($(".addf_gf_btn_tab_ops").closest('tr')).hide();
	($(".addf_gf_res_msg_ops").closest('tr')).hide();
	// scial btn
	$(".addf_gf_social_tab").click(function(){
		$(".addf_gr_all_btns").css('background-color' , 'white');
		$(".addf_gr_all_btns").css('color' , 'blue');
		$(this).css('background-color' , 'rgb(190, 187, 187)');
		$(this).css('color' , 'white');
		($(".addf_gf_gen_tab_ops").closest('tr')).hide();
		($(".addf_gf_btn_tab_ops").closest('tr')).hide();
		($(".addf_gr-addf_gift_registry_syntax_mail_editor").closest('tr')).hide();
		($(".addf_gf_social_tab_ops").closest('tr')).show();
		($(".addf_gf_res_msg_ops").closest('tr')).hide();
		($(".addf_gf_email_tab_ops").closest('tr')).hide();
		($(".addf_gr_editors").closest('tr')).hide();
		($('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr')).hide();
		($("#wc_settings_tab_gift_registry_search_visibility_page_option").closest('tr')).hide();


	});
	// general btn
	$(".addf_gf_gen_tab").click(function(){
		$(".addf_gr_all_btns").css('background-color' , 'white');
		$(".addf_gr_all_btns").css('color' , 'blue');
		$(this).css('background-color' , 'rgb(190, 187, 187)');
		$(this).css('color' , 'white');
		($(".addf_gr-addf_gift_registry_syntax_mail_editor").closest('tr')).hide();
		($(".addf_gf_social_tab_ops").closest('tr')).hide();
		($(".addf_gf_gen_tab_ops").closest('tr')).show();
		($(".addf_gf_res_msg_ops").closest('tr')).hide();
		($(".addf_gf_btn_tab_ops").closest('tr')).hide();
		($(".addf_gf_email_tab_ops").closest('tr')).hide();
		($(".addf_gr_editors").closest('tr')).hide();
		if ($('#wc_settings_tab_gift_registry_notify_gift_registry_enable_redirect_after_add_to_cart').prop("checked")) {
	    	$('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr').show();
		} else {
		    $('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr').hide();
		}

		if ($('#wc_settings_tab_gift_registry_notify_gift_registry_enable_gift_registry_search').prop("checked")) {
	    	$('#wc_settings_tab_gift_registry_search_visibility_page_option').closest('tr').show();
		} else {
		    $('#wc_settings_tab_gift_registry_search_visibility_page_option').closest('tr').hide();
		}
	});
	// email btn
	$(".addf_gf_email_tab").click(function(){
		$(".addf_gr_all_btns").css('background-color' , 'white');
		$(".addf_gr_all_btns").css('color' , 'blue');
		$(this).css('background-color' , 'rgb(190, 187, 187)');
		$(this).css('color' , 'white');
		($(".addf_gf_social_tab_ops").closest('tr')).hide();
		($(".addf_gf_email_tab_ops").closest('tr')).show();
		($(".addf_gr_editors").closest('tr')).show();
		($(".addf_gf_res_msg_ops").closest('tr')).hide();
		($(".addf_gf_btn_tab_ops").closest('tr')).hide();
		($(".addf_gf_gen_tab_ops").closest('tr')).hide();
		($(".addf_gr-addf_gift_registry_syntax_mail_editor").closest('tr')).show();
		($('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr')).hide();
		($("#wc_settings_tab_gift_registry_search_visibility_page_option").closest('tr')).hide();



	});
	// Button customization btn
	$(".addf_gf_btn_tab").click(function(){
		$(".addf_gr_all_btns").css('background-color' , 'white');
		$(".addf_gr_all_btns").css('color' , 'blue');
		$(this).css('background-color' , 'rgb(190, 187, 187)');
		$(this).css('color' , 'white');
		($(".addf_gf_social_tab_ops").closest('tr')).hide();
		($(".addf_gf_res_msg_ops").closest('tr')).hide();
		($(".addf_gf_email_tab_ops").closest('tr')).hide();
		($(".addf_gr_editors").closest('tr')).hide();
		($(".addf_gf_btn_tab_ops").closest('tr')).show();
		($(".addf_gf_gen_tab_ops").closest('tr')).hide();
		($(".addf_gr-addf_gift_registry_syntax_mail_editor").closest('tr')).hide();
		($('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr')).hide();
		($("#wc_settings_tab_gift_registry_search_visibility_page_option").closest('tr')).hide();



	});
	// Button customization btn
	$(".addf_gf_res_msg").click(function(){
		$(".addf_gr_all_btns").css('background-color' , 'white');
		$(".addf_gr_all_btns").css('color' , 'blue');
		$(this).css('background-color' , 'rgb(190, 187, 187)');
		$(this).css('color' , 'white');
		($(".addf_gf_social_tab_ops").closest('tr')).hide();
		($(".addf_gf_btn_tab_ops").closest('tr')).hide();
		($(".addf_gf_email_tab_ops").closest('tr')).hide();
		($(".addf_gr_editors").closest('tr')).hide();
		($(".addf_gf_res_msg_ops").closest('tr')).show();
		($(".addf_gf_gen_tab_ops").closest('tr')).hide();
		($(".addf_gr-addf_gift_registry_syntax_mail_editor").closest('tr')).hide();
		($('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr')).hide();
		($("#wc_settings_tab_gift_registry_search_visibility_page_option").closest('tr')).hide();



	});

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
	$(".addf-g-r-bg-cover-hide-btn").click(function(){
		$(".addf-g-r-bg-cover").hide();
		$(".addf-gr-result-configuration").text(" ");
		$(".addf-add-product-from-registry-single-product").prop('disabled', true);
		$(".addf-gift-registry-add-single-product-quantity").val("1"); 
		$(".addf-gift-registry-add-single-product-class").empty(); 
		$('.addf-gift-registry-add-single-product-class').append('<option hidden selected>Select a product</option>');

	});
	// add a new product    wp_ajax_addf_gift_registry_add_a_product_from_registry
	$('.addf-add-product-from-registry').click(function(){
		$(".addf-g-r-bg-cover").show();
		$(".addf_gr_post_id_to_be_edited").val($(this).data('id'));
	});
	$(".addf-add-product-from-registry-single-product").prop("disabled" , true);
		$(".addf-gift-registry-add-single-product-class").change(function () {
			$(".addf-add-product-from-registry-single-product").prop("disabled" , false);
		});

	//enable redirect after add to cart
	// Initial check when the page loads[enable redirect after add to cart]
	if ($('#wc_settings_tab_gift_registry_notify_gift_registry_enable_redirect_after_add_to_cart').prop("checked")) {
	    $('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr').show();
	} else {
	    $('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr').hide();
	}

	$('#wc_settings_tab_gift_registry_notify_gift_registry_enable_redirect_after_add_to_cart').on('change', function() {
	    if ($(this).prop("checked")) {
	        $('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr').show();
	    } else {
	        $('#wc_settings_tab_gift_registry_notify_gift_registry_redirect_after_add_to_cart_option').closest('tr').hide();
	    }
	});

	

	//enable gift registry search
	//Initial check when page loads[enable gift registry search]
	if ($('#wc_settings_tab_gift_registry_notify_gift_registry_enable_gift_registry_search').prop("checked")) {
		$('#wc_settings_tab_gift_registry_search_visibility_page_option').closest('tr').show();
	} else {
		$('#wc_settings_tab_gift_registry_search_visibility_page_option').closest('tr').hide();
	}

	$('#wc_settings_tab_gift_registry_notify_gift_registry_enable_gift_registry_search').on('change', function() {
	    if ($(this).prop("checked")) {
	        $('#wc_settings_tab_gift_registry_search_visibility_page_option').closest('tr').show();
	    } else {
	        $('#wc_settings_tab_gift_registry_search_visibility_page_option').closest('tr').hide();
	    }
	});

	


	$(document).on('click','.addf-add-product-from-registry-single-product',function (event) {
		$(".addf-add-product-from-registry-single-product").prop('disabled', true);
		event.preventDefault();
		var addf_gr_post_id_tobe_updated           = $(".addf_gr_post_id_to_be_edited").val();
		var addf_gr_product_id_tobe_updated        = $(".addf-gift-registry-add-single-product-class").val();
		var addf_gr_product_quantity_to_be_updated = $(".addf-gift-registry-add-single-product-quantity").val();
		   
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: {
				addify_gift_registry_nonce:php_var.nonce,
				action : 'addf_gift_registry_add_a_new_product_from_registry', 
				addf_gr_post_id_tobe_updated: addf_gr_post_id_tobe_updated, 
				addf_gr_all_selection_verify_vales: $(".addf_gr_all_selection_verify_vales").val(), 
				addf_gr_product_id_tobe_updated: addf_gr_product_id_tobe_updated,
				addf_gr_product_quantity_to_be_updated: addf_gr_product_quantity_to_be_updated },
			success: function(data){
				if ( 'yes' == data['success']) {
					$(".addf-add-product-from-registry-single-product").prop('disabled', true);
					$(".addf-gr-result-configuration").text(" ");
					$(".addf-g-r-bg-cover").hide();
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



	$(".addf-gift-registry-add-single-product-class").change(function () {
		$(".addf-add-product-from-registry-single-product").addClass("loading");
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: { 
				addify_gift_registry_nonce:php_var.nonce,
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
});
(function ($) {
	'use strict';
	$(function () {
		$('.addf_js_multiproduct_select').select2({
			ajax: {
				dataType: "json",
				url: ajaxurl,
				delay: 250, 
				data: function (params) {
					return {
						q: params.term, 
						addify_gift_registry_nonce:php_var.nonce,
						action: 'addf_gift_registry_getproductsearch' 
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
	$(".addf-deetion-ajax-error").text(" ");
	//  del a product from gift query
	$('.addf-add-product-from-registry').click(function(){
		$(".addf-g-r-bg-cover").show();
		$(".addf_gr_post_id_to_be_edited").val($(this).data('id'));
	});
	$(document).on('click','.addf-delete-product-from-registry',function () {
		$(".addf-deetion-ajax-error").text(" ");
		// Delete id
		var addf_data_recieved = ($(this).data('id')).split(":");
		var product_id         = addf_data_recieved[0];
		var post_id            = addf_data_recieved[1];
		jQuery.ajax({
			url: ajaxurl, 
			type: 'POST',
			data: { 
				addify_gift_registry_nonce:php_var.nonce,
				action : 'addf_gift_registry_delete_a_product_from_registry', 
				post_id: post_id, 
				product_id: product_id },
			success: function(data){
				if ( data['success'] ) {
					$(".addf-deetion-ajax-error").show();
					$("#addf_gift_registry_product_tr").replaceWith( data['addf_gift_registry_ajax_replace_select'] );
					jQuery( '.addf_js_multiproduct_select' ).select2();
				}
			}
		 });
			$(this).closest('tr').css('background','tomato');
			$(this).closest('tr').fadeOut(800,function(){
				($(this).closest('tr')).remove();
			});        
		
	});
	$(function () {
		$('.addf-gift-registry-add-single-product-class').select2({
			ajax: {
				url: ajaxurl, 
				dataType: 'json',
				delay: 250, 
				data: function (params) {
					return {
						q: params.term, 
						addify_gift_registry_nonce:php_var.nonce,
						action: 'addf_gr_single_Product_and_variation' 
					};
				},
				processResults: function( data ) {
					console.log(data)
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
	$( document ).on('change', ".addf_gr_countries_billing_front", function () {
			var country = $(this).val();
			jQuery.ajax(
				{
					url: ajaxurl,
					type: 'POST',
					data:{
						addify_gift_registry_nonce:php_var.nonce,
						action: 'addf_gr_search_country_state_ajax',
						addf_country: country,
					},

					success: function (response) {
						jQuery( '.addf_gr_guest_state' ).html( response );
					}
				}
			);
	}
	);
})(jQuery);
