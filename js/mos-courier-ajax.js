jQuery(document).ready(function($) {

    $('.track-form').submit(function(e){
        e.preventDefault();
        var form_data = $(this).serialize();
        console.log(form_data);
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'order_tracking',
                'form_data' : form_data,
            },
            success: function(result){
                // console.log(result);
                $('.track-output').html(result);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });

    $('#action_order_form').submit(function(e){
        var data = $('#order_table_action').val();
        if (data == 'Delete'){
            e.preventDefault();
            $('#modal-danger').modal('show');
        }
    });
    $('#post-delete').on("click", function(){        
        var form_data = $('#action_order_form').serialize();
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'delete_post',
                'form_data' : form_data,
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    // console.log('#order-row-'+result[i].id);
                    $('#order-row-'+result[i].id).remove();;
                }
                $('#modal-danger').modal('hide');
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
    $( '#report_step_two' ).submit(function(e){        
        e.preventDefault();
        var form_two_data = $(this).serialize();
        var form_one_data = $('#report_step_one').serialize();
        // console.log(form_one_data);
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'generate_report',
                'form_two_data' : form_two_data,
                'form_one_data' : form_one_data,
            },
            success: function(result){
                console.log(result);
                for (i = 0; i < result.length; i++) {
                   $("#bill_pay_cn_no, #merchant").val('');
                   $('<tr><th>'+result[i].id+'</th><td>'+result[i].title+'</td><td>'+result[i].product_price+'</td><td>'+result[i].paid_amount+'</td><td>'+result[i].delivery_charge+'</td><td>'+result[i].delivery_date+'</td><td>'+result[i].tpayment+'</td><td><input type="number" min="0" class="form-control" id="order_'+result[i].id+'" name="order['+result[i].id+']" value="0"></td></tr>').prependTo("#bill_pay_form_result");

                }
                // alert(' First user '+user_id[0]+' Second User '+ user_id[1]+' First start_time '+start_time[0]+' Second start_time '+ start_time[1] );

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
    $( '#report_step_one' ).submit(function(e){        
        e.preventDefault();
        var formdata = $(this).serialize();
        // console.log(formdata);
        var res = formdata.split("&");
        // console.log(res);
        if (res.length>1) {
            $(this).hide();
            $('#final-report').show();
            for (var i = res.length - 1; i >= 0; i--) {
                // console.log(res[i].replace("=1", ""));
                if (res[i] == "output_cl_no=1"){
                    $('<input type="hidden" name="output_teble_cl_no" value="1">').prependTo("#report_step_two");
                }

                if (res[i] == "input_cl_no=1"){
                    $('<div class="form-group col-lg-3"><label for="cl_no">CL NO</label><input name="cl_no" id="cl_no" type="text" class="form-control" placeholder="CL NO"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_order_id=1"){
                    $('<div class="form-group col-lg-3"><label for="order_id">Merchant Order ID</label><input name="order_id" id="order_id" type="text" class="form-control" placeholder="Merchant Order ID"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_merchant_name=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_merchant_name">Merchant Name</label><select id="_mos_courier_merchant_name" name="_mos_courier_merchant_name" class="form-control merchant-select" multiple></select></div>').prependTo("#report_step_two > .form-row");
                    get_all_merchants();                    
                    $('.merchant-select').select2();
                }
                if (res[i] == "input_merchant_address=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_merchant_address">Merchant Address</label><input name="_mos_courier_merchant_address" id="_mos_courier_merchant_address" type="text" class="form-control" placeholder="Merchant Address"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_merchant_number=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_merchant_number">Merchant Phone</label><input name="_mos_courier_merchant_number" id="_mos_courier_merchant_number" type="tel" class="form-control" placeholder="Merchant Phone"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_booking_date=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_booking_date_from">Booking From</label><input name="_mos_courier_booking_date_from" id="_mos_courier_booking_date_from" type="date" class="form-control" placeholder="Booking From"></div><div class="form-group col-lg-3"><label for="_mos_courier_booking_date_to">Booking To</label><input name="_mos_courier_booking_date_to" id="_mos_courier_booking_date_to" type="date" class="form-control" placeholder="Booking To"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_product_name=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_product_name">Product Name</label><input name="_mos_courier_product_name" id="_mos_courier_product_name" type="text" class="form-control" placeholder="Product Name"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_product_price=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_product_price_from">Product Price From</label><input name="_mos_courier_product_price_from" id="_mos_courier_product_price_from" type="number" class="form-control" placeholder="Product Price From"></div><div class="form-group col-lg-3"><label for="_mos_courier_product_price_to">Product Price to</label><input name="_mos_courier_product_price_to" id="_mos_courier_product_price_to" type="number" class="form-control" placeholder="Product Price To"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_product_quantity=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_product_quantity_from">Product Quantity From</label><input name="_mos_courier_product_quantity_from" id="_mos_courier_product_quantity_from" type="number" class="form-control" placeholder="Product Quantity From"></div><div class="form-group col-lg-3"><label for="_mos_courier_product_quantity_to">Product Quantity to</label><input name="_mos_courier_product_quantity_to" id="_mos_courier_product_quantity_to" type="number" class="form-control" placeholder="Product Quantity To"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_receiver_name=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_receiver_name">Receiver Name</label><input name="_mos_courier_receiver_name" id="_mos_courier_receiver_name" type="text" class="form-control" placeholder="Receiver Name"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_receiver_address=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_receiver_address">Receiver Address</label><input name="_mos_courier_receiver_address" id="_mos_courier_receiver_address" type="text" class="form-control" placeholder="Receiver Address"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_receiver_number=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_receiver_number">Receiver Number</label><input name="_mos_courier_receiver_number" id="_mos_courier_receiver_number" type="text" class="form-control" placeholder="Receiver Number"></div>').prependTo("#report_step_two > .form-row");
                } 
                if (res[i] == "input_total_weight=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_total_weight_from">Total Weight From</label><input name="_mos_courier_total_weight_from" id="_mos_courier_total_weight_from" type="number" class="form-control" placeholder="Total Weight From"></div><div class="form-group col-lg-3"><label for="_mos_courier_total_weight_to">Total Weight to</label><input name="_mos_courier_total_weight_to" id="_mos_courier_total_weight_to" type="number" class="form-control" placeholder="Total Weight To"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_packaging_type=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_packaging_type">Packaging Type</label><select name="_mos_courier_packaging_type" id="_mos_courier_packaging_type" class="custom-select packaging-select" multiple></select></div>').prependTo("#report_step_two > .form-row");
                    get_all_packaging();
                    $('.packaging-select').select2();
                } 
                if (res[i] == "input_delivery_charge=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_delivery_charge_from">Delivery Charge From</label><input name="_mos_courier_delivery_charge_from" id="_mos_courier_delivery_charge_from" type="number" class="form-control" placeholder="Delivery Charge From"></div><div class="form-group col-lg-3"><label for="_mos_courier_delivery_charge_to">Delivery Charge to</label><input name="_mos_courier_delivery_charge_to" id="_mos_courier_delivery_charge_to" type="number" class="form-control" placeholder="Delivery Charge To"></div>').prependTo("#report_step_two > .form-row");
                }
                if (res[i] == "input_paid_amount=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_delivery_charge_from">Customer payment From</label><input name="_mos_courier_delivery_charge_from" id="_mos_courier_delivery_charge_from" type="number" class="form-control" placeholder="Customer payment From"></div><div class="form-group col-lg-3"><label for="_mos_courier_delivery_charge_to">Customer payment to</label><input name="_mos_courier_delivery_charge_to" id="_mos_courier_delivery_charge_to" type="number" class="form-control" placeholder="Customer payment To"></div>').prependTo("#report_step_two > .form-row");
                }  
                if (res[i] == "input_payment_date=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_payment_date_from">Payment From</label><input name="_mos_courier_payment_date_from" id="_mos_courier_payment_date_from" type="date" class="form-control" placeholder="Payment From"></div><div class="form-group col-lg-3"><label for="_mos_courier_payment_date_to">Payment To</label><input name="_mos_courier_payment_date_to" id="_mos_courier_payment_date_to" type="date" class="form-control" placeholder="Payment To"></div>').prependTo("#report_step_two > .form-row");
                } 
                if (res[i] == "input_delivery_zone=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_delivery_zone">Delivery Zone</label><select name="_mos_courier_delivery_zone" id="_mos_courier_delivery_zone" class="custom-select zone-select" multiple></select></div>').prependTo("#report_step_two > .form-row");
                    get_all_zone();
                    $('.zone-select').select2();
                }   
                if (res[i] == "input_delivery_man=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_delivery_man">Delivery Man</label><select name="_mos_courier_delivery_man" id="_mos_courier_delivery_man" class="custom-select man-select" multiple></select></div>').prependTo("#report_step_two > .form-row");
                    get_all_delivery_man();
                    $('.man-select').select2();
                } 
                if (res[i] == "input_delivery_date=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_delivery_date_from">Delivery From</label><input name="_mos_courier_delivery_date_from" id="_mos_courier_delivery_date_from" type="date" class="form-control" placeholder="Delivery From"></div><div class="form-group col-lg-3"><label for="_mos_courier_delivery_date_to">Delivery To</label><input name="_mos_courier_delivery_date_to" id="_mos_courier_delivery_date_to" type="date" class="form-control" placeholder="Delivery To"></div>').prependTo("#report_step_two > .form-row");
                } 
                if (res[i] == "input_delivery_status=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_delivery_status">Delivery Status</label><select id="_mos_courier_delivery_status" name="_mos_courier_delivery_status" class="form-control delivery-status-select" multiple></select></div>').prependTo("#report_step_two > .form-row");
                    get_all_delivery_status();                    
                    $('.delivery-status-select').select2();
                }
                if (res[i] == "input_payment_status=1"){
                    $('<div class="form-group col-lg-3"><label for="_mos_courier_payment_status">Payment Status</label><select id="_mos_courier_payment_status" name="_mos_courier_payment_status" class="form-control payment-status-select" multiple></select></div>').prependTo("#report_step_two > .form-row");
                    get_all_payment_status();                    
                    $('.payment-status-select').select2();
                }      
            }
        }
        /*$.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'bill_pay_details',
                'formdata' : formdata
            },
            success: function(result){
                console.log(result);

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });*/
        
    });
    var old_bill_pay_ids = [];
    $('#bill_pay_form').submit(function(e){
        e.preventDefault();
        var formdata = $(this).serialize();
        //console.log(formdata);
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'bill_pay_details',
                'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                $("#bill_pay_cn_no, #merchant").val('');
                for (i = 0; i < result.length; i++) {
                    if (old_bill_pay_ids.indexOf(result[i].id) == -1 ) {
                        $('<tr><th>'+result[i].id+'</th><td>'+result[i].title+'</td><td>'+result[i].product_price+'</td><td>'+result[i].paid_amount+'</td><td>'+result[i].delivery_charge+'</td><td>'+result[i].delivery_date+'</td><td>'+result[i].tpayment+'</td><td><input type="number" class="form-control payable-amount" id="order_'+result[i].id+'" name="order['+result[i].id+']" value="'+result[i].payable+'"></td><td><input type="radio" name="method['+result[i].id+']" id="method_'+result[i].id+'_1" value="" checked><label class="form-check-label" for="method_'+result[i].id+'_1">Default</label><input type="radio" name="method['+result[i].id+']" id="method_'+result[i].id+'_2" value="Bkash"><label class="form-check-label" for="method_'+result[i].id+'_2">Bkash</label><input type="radio" name="method['+result[i].id+']" id="method_'+result[i].id+'_3" value="Bank"><label class="form-check-label" for="method_'+result[i].id+'_3">Bank</label><input type="radio" name="method['+result[i].id+']" id="method_'+result[i].id+'_4" value="Cash"><label class="form-check-label" for="method_'+result[i].id+'_4">Cash</label></td><td><input class="form-control" id="note['+result[i].id+']" name="note['+result[i].id+']"></td></tr>').prependTo("#bill_pay_form_result");           
                        index = old_bill_pay_ids.length;
                        old_bill_pay_ids[index] = result[i].id;
                    }

                }
                $('#bill_pay_form').hide();

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
    var old_check_in_ids = [];
    $('#check_in_form').submit(function(e){
        e.preventDefault();
        var formdata = $("#check_in_form").serialize();
        // console.log(formdata);
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'check_in_oreder_details',
                'formdata' : formdata
            },
            success: function(result){
                console.log(result);
                $("#check_in_cn_no, #delivery_man").val('');
                for (i = 0; i < result.length; i++) { 
                    if (old_check_in_ids.indexOf(result[i].id) == -1 ) {
                        $('<tr><th>'+result[i].merchantid+'</th><td>'+result[i].title+'</td><td>'+result[i].product_price+'</td><td><input type="number" min="0" max="'+result[i].product_price+'" class="form-control payable-amount" id="order_'+result[i].id+'" name="order['+result[i].id+']" value="'+result[i].product_price+'"></td><td><input type="number" min="0" max="'+result[i].product_price+'" class="form-control commission" id="order_commission_'+result[i].id+'" name="order_commission['+result[i].id+']"></td><td><input type="text" class="form-control" id="order_remark_'+result[i].id+'" name="order_remark['+result[i].id+']"></td><td><div class="btn-group btn-group-toggle" data-toggle="buttons"><label class="btn btn-outline-success active" title="Delivered"><input type="radio" name="order_delivery_status['+result[i].id+']" autocomplete="off" checked value="delivered"><i class="fa fa-sign-out"></i></label><label class="btn btn-outline-info pdelivery" title="Pertial Delivery"><input type="radio" name="order_delivery_status['+result[i].id+']" autocomplete="off" value="pdelivered"><i class="fa fa-pie-chart"></i></label><label class="btn btn-outline-warning hold" title="Hold"><input type="radio" name="order_delivery_status['+result[i].id+']" autocomplete="off" value="hold"><i class="fa fa-hand-stop-o"></i></label><label class="btn btn-outline-danger returned" title="Returned the order"><input type="radio" name="order_delivery_status['+result[i].id+']" autocomplete="off" value="returned"><i class="fa fa-sign-in"></i></label></div></td></tr>').prependTo('#check_in_form_result');                        
                        index = old_check_in_ids.length;
                        old_check_in_ids[index] = result[i].id;
                    }
                }
                console.log(old_check_in_ids);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
    var old_check_out_ids = [];
    $('#check_out_form').submit(function(e){
        e.preventDefault();
        var formdata = $("#check_out_form").serialize();
        console.log(formdata);
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'check_out_oreder_details',
                'formdata' : formdata
            },
            success: function(result){
                console.log(result);
                //var obj = JSON.parse(result);
                $("#check_out_cn_no, #check_out_zone, #check_out_cn_multi_no").val('');
                for (i = 0; i < result.length; i++) {
                    if (old_check_out_ids.indexOf(result[i].id) == -1 ) {
                        $('<tr><th><input type="checkbox" name="orders[]" id="order_'+result[i].id+'" class="administrator" value="'+result[i].id+'" checked></th><td>'+result[i].title+'</td><td>'+result[i].receiver_address+'</td><td>'+result[i].delivery_zone+'</td></tr>').prependTo("#check_out_form_result");         
                        index = old_check_out_ids.length;
                        old_check_out_ids[index] = result[i].id;
                    }
                }
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
	$('#_mos_courier_merchant_name').change(function(){
        var merchant_id = $(this).val();
        if (merchant_id){
    		var total_weight = $('#_mos_courier_total_weight').val();
            var total_charge = 0;
            //alert(merchant_id);
    	    $.ajax({
    	        url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
    	        type:"POST",
    	        dataType:"json",
    	        data: {
    	            'action': 'get_merchant_details',
    	            'merchant_id' : merchant_id
    	        },
    	        success:function(data) {
    	            // This outputs the result of the ajax request
    	            // console.log(data);
                    total_charge = parseInt(data.delivery_charge);
                    if (total_weight > 1){
                        var additional_weight = total_weight - 1;
                        total_charge = total_charge + additional_weight * parseInt(data.additional_charge);
                    }
    	            $('#_mos_courier_merchant_address').val(data.address);
                    $('#_mos_courier_merchant_number').val(data.phone);
                 //    $('#_mos_courier_delivery_charge').val(total_charge);
                 //    $('.dc').val(data.delivery_charge);
    	            // $('.ac').val(data.additional_charge);
    	        },
    	        error: function(errorThrown){
    	            console.log(errorThrown);
    	        }
    	    });
        } 
	});
	/*$('#_mos_courier_total_weight').on('change', function(){
        var total_weight = $(this).val();
        var delivery_charge = $('.dc').val();
        var additional_charge = $('.ac').val();

        total_charge = parseInt(delivery_charge);
        if (total_weight > 1){
            var additional_weight = total_weight - 1;
            total_charge = total_charge + additional_weight * parseInt(additional_charge);
        }
        $('#_mos_courier_delivery_charge').val(total_charge);
    }); */  
    $("button.multi-files").on("click", function(e){
        e.preventDefault();
        var imageUploader = wp.media({
            'title'     : 'Upload Files',
            'button'    : {
                'text'  : 'Set the files'
            },
            'multiple'  : true
        });
        //console.log(imageUploader);
        
        imageUploader.open();
        var button = $(this);
        imageUploader.on("select", function(){
            var image = imageUploader.state().get("selection").first().toJSON();
            var images = imageUploader.state().get("selection").toJSON();
            var image_link = '';
            for (var i = images.length - 1; i >= 0; i--) {
                image = images[i];
                //image_link += image.id + ',';
                image_link += image.url + ',';
            }
            //image_link = image.url;
            image_link = image_link.slice(0, -1);
            button.prev('input').val(image_link);
            //button.parent().siblings('div.map-img-wrapper').find('img').attr('src', image_link);
            console.log(images);
        });
    });
    $("button.upload-image").on("click", function(e){
        e.preventDefault();
        var imageUploader = wp.media({
            'title'     : 'Upload Image',
            'button'    : {
                'text'  : 'Set the image'
            },
            'multiple'  : false
        });
        //console.log(imageUploader);
        
        imageUploader.open();
        var button = $(this);
        imageUploader.on("select", function(){
            var image = imageUploader.state().get("selection").first().toJSON();
            image_link = image.url;
            button.prev('input').val(image_link);
            //button.parent().siblings('div.map-img-wrapper').find('img').attr('src', image_link);
            console.log(images);
        });
    });
    function get_all_merchants(){
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'get_all_merchants',
                //'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    $('<option value="'+result[i].id+'">'+result[i].title+'</option>').appendTo("#_mos_courier_merchant_name");
                    // result[i].id
                    // result[i].title
                }

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }
    function get_all_packaging(){
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'get_all_packaging',
                //'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    $('<option value="'+result[i].id+'">'+result[i].title+'</option>').appendTo("#_mos_courier_packaging_type");
                    // result[i].id
                    // result[i].title
                }

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }
    function get_all_zone(){
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'get_all_zone',
                //'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    $('<option value="'+result[i].id+'">'+result[i].title+'</option>').appendTo("#_mos_courier_delivery_zone");
                    // result[i].id
                    // result[i].title
                }

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }
    function get_all_delivery_man(){
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'get_all_delivery_man',
                //'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    $('<option value="'+result[i].id+'">'+result[i].title+'</option>').appendTo("#_mos_courier_delivery_man");
                    // result[i].id
                    // result[i].title
                }

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }
    function get_all_delivery_status(){
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'get_all_delivery_status',
                //'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    $('<option value="'+result[i].id+'">'+result[i].title+'</option>').appendTo("#_mos_courier_delivery_status");
                    // result[i].id
                    // result[i].title
                }

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }
    function get_all_payment_status(){
        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'get_all_payment_status',
                //'formdata' : formdata
            },
            success: function(result){
                // console.log(result);
                for (i = 0; i < result.length; i++) {
                    $('<option value="'+result[i].id+'">'+result[i].title+'</option>').appendTo("#_mos_courier_payment_status");
                    // result[i].id
                    // result[i].title
                }

            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }
}); 