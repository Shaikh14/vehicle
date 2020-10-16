<?php
/*
 * Registrer all the action for forms and booking
*/

function booking_form(){
 include_once 'booking-form.php';
}
add_shortcode('bookingForm','booking_form');

require_once(WP_DASHBOARD_PLUGIN_DIR . '/include/wc-list-vehicle-booking.php');

function theme_enqueue_scripts() {
    /**
     * frontend ajax requests.
     */
    wp_enqueue_script( 'frontend-ajax',   plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery'), null, true );
    wp_localize_script( 'frontend-ajax', 'frontend_ajax_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        )
    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );

/*
 * Select vehicle based on the vehicle type
*/

function selectVehicle(){
  global $wpdb;
    if(isset($_POST['term_id'])){
       $args = array(
              'post_type' => 'vehicle',
              'posts_per_page' => '999',
              'post_status' => 'publish',
               'tax_query' => array(
						        array(
						            'taxonomy' => 'vehicle_type',
						            'field'    => 'id',
						            'terms'    => $_POST['term_id']
						        )
						    )
              );
       $result = new WP_Query( $args );
        if($result->have_posts()){
        	echo '<option>-- Select Vehicle --</option>';
        	while ($result->have_posts()) {
        		$result->the_post();
        		echo '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
        	}
        }
        wp_reset_postdata();
	} 
      
  die;
}
add_action('wp_ajax_nopriv_select_post', 'selectVehicle');
add_action('wp_ajax_select_post', 'selectVehicle');

/*
 * Get the price by the vehicle select
*/

function getVehiclePrice(){
  global $wpdb;
    if(isset($_POST['post_id'])){
      $post   = get_post( $_POST['post_id'] );
        echo $price =  get_post_meta( $_POST['post_id'], 'vehicle_price', true );
	} 
      
  die;
}
add_action('wp_ajax_nopriv_get_vehicle_price', 'getVehiclePrice');
add_action('wp_ajax_get_vehicle_price', 'getVehiclePrice');


/*
 * Submit the frontend form 
*/
function submitBookingRequest(){
  global $wpdb;
    if(isset($_POST)){
    	 $first_name = $_POST['first_name'];
    	 $last_name = $_POST['last_name'];
    	 $email = $_POST['email'];
    	 $phone = $_POST['phone'];
    	 $vehicle_type = $_POST['vehicle_type'];
    	 $vehicle = $_POST['vehicle'];
    	 $vehicle_price = $_POST['vehicle_price'];
         
         $result =  $wpdb->insert('wp_vehicle_booking', array(
						          'first_name' => $first_name,
						          'last_name' => $last_name,
						          'email' => $email,
			                'phone' => $phone,
			                'vehicle_type' => $vehicle_type,
			                'vehicle' => $vehicle,
			                'vehicle_price' => $vehicle_price,
                      'status' => 0,
			              ));
         if($result == true){
         	echo "<h2>Booking Request Submitted Sucessfully.</h2>";
           /*Email to admin*/
            $to = get_option( 'admin_email' );
            $subject = 'Booking Request Submitted';
            $body = 'Hello Admin,
                   '.$first_name.', requests for booking with following details.
                   Vehicle Type :'.$vehicle_type.',
                   Vehicle : '.$vehicle.',
                   Vehicle Price: '.$vehicle_price.'

                   Thank You!!
            ';
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
            wp_mail( $to, $subject, $body, $headers);

           /*Email to user*/

            $to = $email;
            $subject = 'Booking Request Submitted Sucessfully.';
            $body = 'Hello '.$first_name.' '.$last_name.',
                   Your requests Submitted successfully, and status is pending.

                   Vehicle Type :'.$vehicle_type.',
                   Vehicle : '.$vehicle.',
                   Vehicle Price: '.$vehicle_price.'

                   Thank You!!
                   Team Dev
            ';
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
            wp_mail( $to, $subject, $body, $headers);
         }
	} 
      
  die;
}
add_action('wp_ajax_nopriv_submitBookingRequest', 'submitBookingRequest');
add_action('wp_ajax_submitBookingRequest', 'submitBookingRequest');