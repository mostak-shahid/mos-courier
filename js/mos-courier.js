jQuery(document).ready(function($){		
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
		$('table').find('.payable-amount').each(function( number ) {
			var text = $(this).val();
			var numeric = text.match(/^[0-9 -]/gmi);
			if (numeric){
				sum = sum + parseInt(text);
			}
		});
		$('.calculated-value').html(sum);
	});

	$("#checkAll").click(function(){
	    $('input:checkbox').not(this).prop('checked', this.checked);
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