jQuery(document).ready(function($) {
    $(window).load(function(){
      $('.mos-courier-wrapper .tab-con').hide();
      $('.mos-courier-wrapper .tab-con.active').show();
    });

    $('.mos-courier-wrapper .tab-nav > a').click(function(event) {
      event.preventDefault();
      var id = $(this).data('id');

      set_mos_courier_cookie('courier_active_tab',id,1);
      $('#mos-courier-'+id).addClass('active').show();
      $('#mos-courier-'+id).siblings('div').removeClass('active').hide();

      $(this).closest('.tab-nav').addClass('active');
      $(this).closest('.tab-nav').siblings().removeClass('active');
    });
});