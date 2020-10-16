jQuery(document).ready(function($) {
jQuery( "#vehicletypoe" ).change(function() {
      var term_id = jQuery('#vehicletypoe').val();
            if(term_id !=''){
               $.ajax({
                    url: frontend_ajax_object.ajaxurl,
                    type: 'post',
                    data: {
                        'action':'select_post',
                        'term_id':term_id
                    },
                    success: function( response ) {
                      jQuery('.vehicle_display').show();
                        jQuery('#vehicle').html(response);
                    },
                  });
            }else{
                alert("Select Options");
            }
   
   });

jQuery( "#vehicle" ).change(function() {
      var post_id = jQuery('#vehicle').val();
       $.ajax({
            url: frontend_ajax_object.ajaxurl,
            type: 'post',
            data: {
                'action':'get_vehicle_price',
                'post_id':post_id
            },
            success: function( response ) {
                 jQuery('.vehiclepricedisplay').show();
                 jQuery('#vehicle_price').val(response);
               // jQuery('#vehicle').html(response);
            },
          });
   });


  jQuery( "#bookingrequest" ).on('submit',function(e) {
       e.preventDefault();
      var first_name = jQuery('#first_name').val();
      var last_name = jQuery('#last_name').val();
      var email = jQuery('#email').val();
      var phone = jQuery('#phone').val();
      var vehicle_type = jQuery('#vehicletypoe').val();
      var vehicle = jQuery('#vehicle').val();
      var vehicle_price = jQuery('#vehicle_price').val();
  
       $.ajax({
            url: frontend_ajax_object.ajaxurl,
            type: 'post',
            data: {
                'action':'submitBookingRequest',
                'first_name':first_name,
                'last_name':last_name,
                'email':email,
                'phone':phone,
                'vehicle_type':vehicle_type,
                'vehicle':vehicle,
                'vehicle_price':vehicle_price,
            },
            success: function( response ) {
               //  jQuery('#vehicle_price').val(response);
               // jQuery('#vehicle').html(response);
               jQuery('.message').html(response);
            },
          });
   });
});