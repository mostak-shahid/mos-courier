jQuery(document).ready(function($){	
	$('.transactionModal').click(function(){
		var title = $(this).data('title');		
		var type = $(this).data('type');		
		var amount = $(this).data('amount');		
		var description = $(this).data('description');
		var id = $(this).data('id');
		if (id) {
			$('#transactionModalLabel').html('Edit Transaction');
		} else {
			$('#transactionModalLabel').html('Add Transaction');			
		}		
		$('#title').val(title);
		if (type) $('#type'+type).prop('checked', true);
		else $('#typecashout').prop('checked', true);
		$('#amount').val(amount);
		$('#description').val(description);
		$('#id').val(id);
		$('#transactionModal').modal('show');	
	});	
	$('#user_role').change(function(){
		var user_role = $(this).val();
		if (user_role == 'Regular' || user_role == 'Corporate'){
			$('#brand_name, #payment, #delivery_charge, #additional_charge').attr("required",true);
		} else{
			$('#brand_name, #payment, #delivery_charge, #additional_charge').removeAttr("required");
		}
	});
	
	$('#payment').change(function(){		
		var payment = $(this).val();
		if (payment == 'Bank'){
			$('#payacc, #bank_name, #account_holder').attr("required",true);
		} else if (payment == 'BKash'){
			$('#payacc').attr("required",true);			
			$('#bank_name, #account_holder').removeAttr("required");			
		} else{
			$('#payacc, #bank_name, #account_holder').removeAttr("required");
		}
	});
	$("#_mos_courier_delivery_status").change(function() {
	    if(this.checked) {
	    	// alert(0);
	        $('#_mos_courier_remarks').attr('required', true);
	    } else {
	        $('#_mos_courier_remarks').removeAttr('required');	    	
	    } 
	});
	$('.btn-calculate').on("click", function(){ 
		var sum = 0;
		var add = 0;
		var m_com = 0;
		$('table').find('.payable-amount').each(function( number ) {
			var text = $(this).val();
			var numeric = text.match(/^[0-9 -]/gmi);
			if (numeric){
				sum = sum + parseInt(text);
			}
		});
		$('table').find('.commission').each(function( number ) {
			var text = $(this).val();
			var numeric = text.match(/^[0-9 -]/gmi);
			if (numeric){
				add = add + parseInt(text);
			}
		});
		$('table').find('.merchant_commission').each(function( number ) {
			var text = $(this).val();
			var numeric = text.match(/^[0-9 -]/gmi);
			if (numeric){
				m_com = m_com + parseInt(text);
			}
		});
		var result = sum - add + m_com;
		$('.calculated-value').html(result);
	});

	$("#checkAll").click(function(){
		if(this.checked) {
			var newValue = '';
			var newValuePos = '';
		    // $('input:checkbox').not(this).prop('checked', this.checked);
		    $('.order-selector').each(function(){
		    	// alert($(this).val());
		    	$(this).prop('checked', true);
				var href = $('.order-print-btn').attr('href');
				var poshref = $('.order-pos-print-btn').attr('href');
		    	newValue = href + $(this).val() + ',';
		    	newValuePos = poshref + $(this).val() + ',';
			    $('.order-print-btn').attr('href',newValue);
			    $('.order-pos-print-btn').attr('href',newValuePos);
			});
		} else {
			$('.order-selector').each(function(){
				$(this).prop('checked', false);
				var href = $('.order-print-btn').attr('href');
				var poshref = $('.order-pos-print-btn').attr('href');				
		    	newValue = href.replace($(this).val() + ',', '');
		    	newValuePos = poshref.replace($(this).val() + ',', '');
			    $('.order-print-btn').attr('href',newValue);
			    $('.order-pos-print-btn').attr('href',newValuePos);
			});
		}
	});

	var user_role = $('#user_role').val();	
	user_role_fields(user_role);
	$('#user_role').on('change', function(){
		user_role = $(this).val();
		user_role_fields(user_role)
	});

	var payment = $('#payment').val();	
	payment_fields(payment);
	$('#payment').on('change', function(){
		payment = $(this).val();
		payment_fields(payment)
	});
	$('.btn-add-charge').on('click', function(){
		var old_id = parseInt($(this).val());
		var id = old_id + 1;
		var this_elem =  $('.last-row').clone().insertBefore('.last-row');
		this_elem.removeAttr('class');
		this_elem.find('.zone-name').attr('name','mos_courier_options['+old_id+'][zone-name]');
		this_elem.find('.area-name').attr('name','mos_courier_options['+old_id+'][area-name]');
		this_elem.find('.regular').attr('name','mos_courier_options['+old_id+'][regular]');
		this_elem.find('.extra').attr('name','mos_courier_options['+old_id+'][extra]');
		this_elem.find('.urgent').attr('name','mos_courier_options['+old_id+'][urgent]');
		$(this).val(id);
	});
	$('#_mos_courier_delivery_zone,#_mos_courier_urgent_delivery,#_mos_courier_total_weight').change(function(){
		set_delivery_charge();
	});

	/*$(".order-selector").change(function() {
	    if(this.checked) {
	        alert($(this).val());
	    }
	});*/
	$(document).on('click', '.order-selector', function() { 
		var newValue = '';
		var href = $('.order-print-btn').attr('href');
		var poshref = $('.order-pos-print-btn').attr('href');
	    if(this.checked) {
	    	newValue = href + $(this).val() + ',';
	    	newValuePos = poshref + $(this).val() + ',';
	    } else {
	    	newValue = href.replace($(this).val() + ',', '');
	    	newValuePos = poshref.replace($(this).val() + ',', '');
	    }
	    $('.order-print-btn').attr('href',newValue);
	    $('.order-pos-print-btn').attr('href',newValuePos);
	    // console.log(newValue);
	});
	function set_delivery_charge(){
		var total_charge = 0;
		var rcharge = parseInt($('#_mos_courier_delivery_zone').find(':selected').data('rcharge'));
		var acharge = parseInt($('#_mos_courier_delivery_zone').find(':selected').data('acharge'));
		var ucharge = parseInt($('#_mos_courier_delivery_zone').find(':selected').data('ucharge'));
		var urgent_delivery = $('#_mos_courier_urgent_delivery').find(':selected').val();
		var total_weight= parseInt($('#_mos_courier_total_weight').val());
		// console.log(rcharge);
		// console.log(acharge);
		// console.log(ucharge);
		// console.log(urgent_delivery);
		// console.log(total_weight);
		if (urgent_delivery == 'yes'){
			total_charge = ucharge + (total_weight - 1) * acharge;
		} else {
			total_charge = rcharge + (total_weight - 1) * acharge;			
		} 
		// console.log(total_charge);
		$('#_mos_courier_delivery_charge').val(total_charge);
	}
	function user_role_fields(user_role){		
		if (user_role != 'Regular' && user_role != 'Corporate'){
			$("#brand_name, #payment, #payacc, #delivery_charge, #additional_charge").closest(".col-lg-6").hide();
		} else {
			$("#brand_name, #payment, #delivery_charge, #additional_charge").closest(".col-lg-6").show();
		}

	}
	function payment_fields(payment){		
		if (!payment || payment == 'Cash'){
			$("#payment").closest(".col-lg-6").siblings().hide();
		} else if (payment == 'BKash'){
			$("#payment").closest(".col-lg-6").siblings().hide();
			$("#payment").closest(".col-lg-6").next().show();
		} else {
			$("#payment").closest(".col-lg-6").siblings().show();
		}

	}

});
function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
// Disable form submissions if there are invalid fields
(function() {
	'use strict';
	window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
    	form.addEventListener('submit', function(event) {
    		if (form.checkValidity() === false) {
    			event.preventDefault();
    			event.stopPropagation();
    		}
    		form.classList.add('was-validated');
    	}, false);
    });
}, false);
})();