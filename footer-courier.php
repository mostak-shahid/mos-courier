<?php 
global $page_list, $page_slug;
?>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
			<div class="p-3">
				<h5>Title</h5>
				<p>Sidebar content</p>
			</div>
		</aside>
		<!-- /.control-sidebar -->

		<!-- Main Footer -->
		<footer class="main-footer">
			<!-- To the right -->
			<!-- <div class="float-right d-none d-sm-inline">
				Anything you want
			</div> -->
			<!-- Default to the left -->
			Copyright &copy; 2014-<?php echo date("Y") ?> All rights reserved. Developped by <a target="_blank" href="https://www.mdmostakshahid.me">Md. Mostak Shahid</a>.
		</footer>
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->

	<!-- jQuery -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- DataTables -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/dataTables.bootstrap4.js"></script>

	<!-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script> -->

	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/dataTables.buttons.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/buttons.bootstrap4.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/jszip.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/pdfmake.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/vfs_fonts.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/buttons.html5.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/buttons.print.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/externals/buttons.colVis.min.js"></script>

	<!-- ChartJS -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/chart.js/Chart.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/moment/moment.min.js"></script>
	<!-- date-range-picker -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/daterangepicker/daterangepicker.js"></script>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<!-- Select2 -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/select2/select2.full.min.js"></script>
	<!-- Bootstrap Imageupload -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/bootstrap-imageupload/dist/js/bootstrap-imageupload.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/dist/js/adminlte.min.js"></script>

<script type='text/javascript'>
/* <![CDATA[ */
// var ajax_obj = {"ajax_url":"http:\/\/tcourier.aiscript.net\/wp-admin\/admin-ajax.php"};
var ajax_obj = {"ajax_url":"<?php echo admin_url('admin-ajax.php') ?>"};
/* ]]> */
</script>

	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>js/mos-courier-ajax.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>js/mos-courier.js"></script>
	<script>
		
jQuery(document).ready(function($){	
	var $imageupload = $('.imageupload');
	$imageupload.imageupload();
	// $('.img-upload').imgUpload();
	
	$('.select2').select2();
    /*$('#example1').DataTable( {
        // dom: 'Bfrtip',
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            'copy', 'csv', 'excel', 'pdf'//, 'print'
        ],
	    "columnDefs": [ {
	      "targets"  : 'no-sort',
	      "orderable": false,
	    }]
    });*/

    // Setup - add a text input to each footer cell
    $('#example1 tfoot th').each( function () {
    	var data_search = $(this).data('search');
    	if (data_search != 'no-search'){
	        var title = $(this).text();
	        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	    }
    } );
 
    // DataTable
    // var table = $('#example').DataTable();

    var table = $('#example1').DataTable( {
        // dom: 'Bfrtip',
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            'copy', 'csv', 'excel', 'pdf'/*, 'print'*/
        ],
	    "columnDefs": [ {
	      "targets"  : 'no-sort',
	      "orderable": false,
	    }],
	    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
    });
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change clear', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    $('#user-table').DataTable( {
        // dom: 'Bfrtip',
        order: [[ 11, 'desc' ]],
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
	    columnDefs: [ {
	      "targets"  : 'no-sort',
	      "orderable": false,
	    }]
    });
	$('#example2').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"info": true,
		"autoWidth": false
	});	
	$('#order-table').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        "order": [[ 1, "desc" ]],
		"autoWidth": false,
        'ajax': {
            'url': '<?php echo plugin_dir_url(__FILE__) . 'order-manage-tabledat.php' ?>',
        },
        'columns': [
            {data:'post_id'},
            {data:'ID'},
            {data:'cn'},
            {data:'booking'},
            {data:'delivery_status'},
            {data:'brand'},
            {data:'receiver'},
            {data:'action'},
        ],
	    columnDefs: [{
			"targets"  : 'no-sort',
			"orderable": false,
	    }]
    });
    //Date range as a button
    $('.pie-range-btn').daterangepicker({
    	ranges   : {
    		'Today'       : [moment(), moment()],
    		'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
    		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
    		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    	},
    	startDate: moment().subtract(29, 'days'),
    	endDate  : moment()
    },
    function (start, end) {
    	// $('#start-pie').val(start.format('YYYY/MM/DD'));
    	$('.pie-range-btn span').html(start.format('MMMM D, YYYY'));

    	if (start.format('MMMM D, YYYY') != end.format('MMMM D, YYYY')){
    		// $('#end-pie').val(end.format('YYYY/MM/DD'));
    		$('.pie-range-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    	}

        $.ajax({
            url: ajax_obj.ajax_url, // or example_ajax_obj.ajaxurl if using on frontend
            type:"POST",
            dataType:"json",
            data: {
                'action': 'pie_data',
                'start_date' : start.format('YYYY/MM/DD'),
                'end_date' : end.format('YYYY/MM/DD'),
            },
            success: function(result){
                console.log(result);
                total_post = parseInt(result.total_post);
                $('.pieTitle span').html(total_post);

                total_delivered = parseInt(result.total_delivered);
                total_returned = parseInt(result.total_returned);
                total_pending = parseInt(result.total_pending);
                total_received = parseInt(result.total_received);
                total_hold = parseInt(result.total_hold);
                total_way = parseInt(result.total_way);
                total_pdelivered = parseInt(result.total_pdelivered);


                pieData = {
                	labels: [ 
                		'Total Delivery',
						'Total returned',  
						'Total pending', 
						'Total received', 
						'Total hold', 
						'Total way', 
						'Total pdelivered', 
                	],
                	datasets: [
	                	{
	                		data: [total_delivered,total_returned,total_pending,total_hold,total_way,total_pdelivered],
	                		backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#aeaeae'],
	                	}
                	]
                }
                // var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
                // var pieOptions     = {
                // 	maintainAspectRatio : false,
                // 	responsive : true,
                // }
			    //Create pie or douhnut chart
			    // You can switch between pie and douhnut using the method below.
			    pieChart.destroy();
			    pieChart = new Chart(pieChartCanvas, {
			    	type: 'pie',
			    	data: pieData,
			    	options: pieOptions      
			    })
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });	    	

    })

	if ($('#firechart').val() == 1){
		var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
		var pieOptions     = {
			maintainAspectRatio : true,
			responsive : true,
		}
	    //Create pie or douhnut chart
	    // You can switch between pie and douhnut using the method below.
	    var pieChart = new Chart(pieChartCanvas, {
	    	type: 'pie',
	    	data: pieData,
	    	options: pieOptions      
	    })

	    //-------------
	    //- BAR CHART -
	    //-------------
	    var ytdChartCanvas = $('#ytdChart').get(0).getContext('2d')
	    var ytdChartData = jQuery.extend(true, {}, ytdareaChartData)
	    var temp0 = ytdareaChartData.datasets[0]
	    var temp1 = ytdareaChartData.datasets[1]
	    ytdChartData.datasets[0] = temp1
	    ytdChartData.datasets[1] = temp0

	    var ytdChartOptions = {
	    	responsive              : true,
	    	maintainAspectRatio     : false,
	    	datasetFill             : false
	    }

	    var ytdChart = new Chart(ytdChartCanvas, {
	    	type: 'bar', 
	    	data: ytdChartData,
	    	options: ytdChartOptions
	    })

	    var dsrChartCanvas = $('#dsrChart').get(0).getContext('2d')
	    var dsrChartData = jQuery.extend(true, {}, dsrareaChartData)
	    var temp0 = dsrareaChartData.datasets[0]
	    var temp1 = dsrareaChartData.datasets[1]
	    dsrChartData.datasets[0] = temp1
	    dsrChartData.datasets[1] = temp0

	    var dsrChartOptions = {
	    	responsive              : true,
	    	maintainAspectRatio     : false,
	    	datasetFill             : false
	    }

	    var dsrChart = new Chart(dsrChartCanvas, {
	    	type: 'bar', 
	    	data: dsrChartData,
	    	options: dsrChartOptions
	    })
	}
});

	</script>
</body>
</html>
