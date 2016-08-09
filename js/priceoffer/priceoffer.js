// Product View Custom Javascripts, Mon Dec 12 14:40:50 +0100 2014

// Copyright (c) 2014-2015 Ephraim Babekov
// dailydeals.js is freely distributable under the terms of an MIT-style license.


jQuery(function($){	 

	$('.how-it-works a').click (function (e) {
		e.preventDefault();
		$('#offer_form_container').fadeOut('fast', function(){
			$('#how-it-works-container').fadeIn('fast');
		});
	})
	
	$('.go-back-to-offer').click (function (e) {
		$('#how-it-works-container').fadeOut('fast', function(){
			$('#offer_form_container').fadeIn('fast');
		});
	})
	
	function calOffer () {
		var qty = $('#qtyOffer').val();
				
		if (!qty || isNaN(qty)) {
			$('#qtyOffer').val('1');
			qty=1;
		}
		originalPrice = originalPrice*qty;
		var offerPrice = $('#priceOffer').val();
		if (!offerPrice || isNaN(offerPrice)) {
			offerPrice=0;
			$('#priceOffer').val('0')
		}	
		if (offerPrice>0) {
			var newPrice = parseFloat(qty*offerPrice).toFixed(2);
			$('#offer-calc div:first-child h4').html(qty+' X $'+addCommas(parseFloat(offerPrice).toFixed(2))+' = $'+addCommas(newPrice));
			$('#offer-calc div:last-child div').html(addCommas(parseFloat(100-(newPrice/originalPrice)*100).toFixed(0))+'%');
			$('#offer-calc').show();
		}
	}
	
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 
	
	function isValidPostalCode(postalCode) {
		
			postalCodeRegex = /^([0-9]{5})(?:[-\s]*([0-9]{4}))?$/;
			var ret = postalCodeRegex.test(postalCode);
			if (!ret) {
				postalCodeRegex = /^([A-Z][0-9][A-Z])\s*([0-9][A-Z][0-9])$/;
				ret = postalCodeRegex.test(postalCode);
			} else if (!ret) {
				postalCodeRegex = /^(?:[A-Z0-9]+([- ]?[A-Z0-9]+)*)?$/;
				ret = postalCodeRegex.test(postalCode);
			}
			
			return ret;
	}
	
	function outline (id,placeholder,focus) {
		id.css('outline','1px solid red');
		id.delay(5000).animate({outline: "0"}, 500);
		id.attr('placeholder',placeholder);
		id.val('');
		if (focus) id.focus();
	}
	
	function addCommas(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
	
	$('#zipcodeOffer').blur (function () {
		if (!isValidPostalCode ($(this).val())) {
			outline ($(this),'Please enter a valid zip code');
		}
	})
	
	$('.submitOffer').click ( function () {
		
		if (!isValidPostalCode ($('#zipcodeOffer').val())) {
			outline ($('#zipcodeOffer'),'Please enter a valid zip code',focus);
			return false
		} else if (!validateEmail($('#emailOffer').val())) {
			outline ($('#emailOffer'),'Please enter a valid email address',focus);
			return false
		}
		
		$.fancybox.showLoading()
		$.post(ajaxPath+"dailydeals/ajax/offer", 
			{offerForm: $("#form").serialize(),discount:$('#offer-calc div:last-child div').text()}, 
			function(data){
				$.fancybox.close();
				//alert (data);
				$('#offer_form_container').fadeOut('fast', function(){
					$('.msgConfirmation').fadeIn('fast');
				});
				
				resizeFancybox (600,300);
				$.fancybox.hideLoading()
				$.fancybox.reposition();
				$('#form').find("input[type=text], textarea").val("");
				$('#offer-calc').hide();
			});
	})
	
	function resizeFancybox (newWidth, newHeight) {
		$('.fancybox-inner').css('width',newWidth+'px');
		$('.fancybox-skin').css('width',newWidth+'px');
		$.fancybox.current.width=newWidth+'px';
		if (newHeight) {
			$('.fancybox-wrap').css('height',newHeight+'px');
			$('.fancybox-skin').css('height',newHeight+'px');
			$.fancybox.current.height=newHeight+'px';
		}
	}
	
	$('#emailOffer').blur ( function () {
		if (!validateEmail($(this).val())) {
			outline ($(this),'Please enter a valid email address');
		}
		
	})
	
	$('#priceOffer, #qtyOffer').blur (function () {
		calOffer();
	})
	
	$('.inquiry_submit').click ( function () {
		
		if (!$('#inquiry_name').val()) {
			outline ($('#inquiry_name'),'Please enter a valid zip code',focus);
			return false
		} else if (!validateEmail($('#inquiry_email').val())) {
			outline ($('#inquiry_email'),'Please enter a valid email address',focus);
			return false
		}
		
		$.fancybox.showLoading()
		$.post(ajaxPath+"dailydeals/ajax/inquiry", 
			{inquiryForm: $("#inquiry_form").serialize()}, 
			function(data){	
				alert (data);
				//$.fancybox.close();
				$('#inquiry_form').fadeOut('fast', function(){
					$('.msgConfirmation').fadeIn('fast');
				});
				
				resizeFancybox (600,300);
				$.fancybox.hideLoading()
				$.fancybox.reposition();
				$('#form').find("input[type=text], textarea").val("");
			});
	})
	
	$('.notification a, .btn-outofstock').click (function (e) {
		e.preventDefault();
		$.fancybox.showLoading();
		var product_id;
		
		if ($(this).parents("li").length) {
			product_name=$(this).parents("li").find(".product-name a").attr('title');
			product_id=$(this).parents("li").find("#product_id").val();
			$('#inquiry_form').show();
			$("#inquiry_inquiry").text(product_name);
			$("#inquiry_product").val(product_name);
		} else {
			product_id=$("#inquiry_product_id").val();
		}
		
		$("#inquiry_product_id").val(product_id);
		
		$('.msgConfirmation').hide();
		$.fancybox({
			afterClose: function () {
				$('#form').find("input[type=text], textarea").val("");
			},
			helpers : {
				overlay : {
						css : {
							'background' : 'rgba(0, 0, 0, 0.30)'
						}
					},
		        title: {
						type: 'inside',
						position: 'top'
					}
				},
				'content' : $(".notify_me").show(),
				overflow	: 'visible',
				fitToView	: true,
				width		: '700',
				height		: '530',
				padding		: 0,
				autoSize	: false,
				closeClick	: false,
				openMethod	: 'zoomIn',
				closeBtn	: true
				
		});
		$.fancybox.hideLoading();
		
		
	})
	
	$('.price_offer').click (function (e) {
		if (is_onMobile()) {
			window.open(
				  'https://chronostore.com/make-an-offer?prod='+$("input[name='product_id']").val(),
				  '_blank' // <- This is what makes it open in a new window.
				); 
			return
		}
		$('#offer_form_container').show();
		$('.msgConfirmation').hide();
		$('#how-it-works-container').hide()
		$.fancybox({
			afterClose: function () {
				$('#form').find("input[type=text], textarea").val("");
				$('#offer-calc').hide();
			},
			padding	: 0,
			helpers : {
				overlay : {
						css : {
							'background' : 'rgba(0, 0, 0, 0.30)'
						}
					}
				},
				'content' : $(".offer_container").show(),
				overflow	: 'visible',
				fitToView	: true,
				width		: '800',
				height		: '535',
				autoSize	: false,
				closeClick	: false,
				openMethod	: 'zoomIn',
				closeBtn	: true
				
		});
		$('#priceOffer').focus();

	});
	
	/*
	$('.add-to-box').mouseover( function() {
		if (!$('.add-to-box > div.new').length) {
			$('.add-to-box').prepend( "<div class='new' style='position: absolute; top:0; left:0; width: 100%; height: 100%'></div>" );
		
			$('.add-to-box').css({'position': 'relative', 'opacity': '0.3'});
		}
	})
	
	$('.add-to-box').on ('mouseout', '.add-to-box > div.new', function() {
		$('.add-to-box > div.new').remove();
		$('.add-to-box').css('opacity', '1');
	})*/

});