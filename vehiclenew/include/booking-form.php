<?php 
/* 
 * Booking Request form
*/
global $wpdb;
?>
<form class="bookingrequest" id="bookingrequest" method="post">
  <div class="form-group">
    <label for="first-name">First Name</label>
    <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" required="required">
  </div>
  <div class="form-group">
    <label for="last-name">First Name</label>
    <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" required="required">
  </div>

  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required="required">
  </div>

   <div class="form-group">
    <label for="phone">Phone Number</label>
    <input type="tel" class="form-control" id="phone" placeholder="Phone" name="phone" required="required">
  </div>

   <div class="form-group">
    <label for="Vehicle-Type">Vehicle Type</label>
     <select class="form-control" id="vehicletypoe" name="vehicle_type">
	      <option>Select Vehicle Type</option>
	       <?php
	         $vehicle_type = get_terms( 'vehicle_type', array(
				    'orderby'    => 'parent',
				) ); 
	         foreach ($vehicle_type as $key => $type) {
	            ?> 
	            <option value="<?php echo $type->term_id; ?>"><?php echo $type->name; ?></option>
	            <?php 
	         }
	       ?>
    </select>
  </div>

  <div class="form-group vehicle_display"  style="display: none;">
    <label for="vehicle">Vehicle</label>
    <select class="form-control" id="vehicle" name="vehicle">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </div>

  <div class="form-group vehiclepricedisplay"  style="display: none;">
	    <label for="vehicle_price">Vehicle Price</label>
	    <input type="text" class="form-control" id="vehicle_price" placeholder="Vehicle Price" name="vehicle_price" required="required">
  </div>
  
  <div class="form-group">
    <label for="message">Message</label>
    <textarea class="form-control" id="message" rows="3" required="required"></textarea>
  </div>
  <input type="submit" value="submit" name="submit">
</form>
<div class="message"></div>