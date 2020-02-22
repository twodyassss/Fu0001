<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* woo-commmercerce meta boxes for um-switcher
*/
class Um_Switcher_Meta_Boxes{
	
	function __construct(){
		add_filter( 'product_type_selector' , array( $this, 'um_switcher' ) );	
		add_action( 'woocommerce_product_options_general_product_data', array($this, 'wc_custom_add_custom_fields' ));
		add_action( 'woocommerce_process_product_meta', array( $this, 'wc_custom_save_custom_fields' ));
	}

	public function um_switcher($product_types){
		$product_types['um_switcher'] = __( 'UM-Switcher Product', 'twodayssss' );
		return $product_types;
	}
	
	public function wc_custom_add_custom_fields() {
		global $post,$wpdb,$woocommerce;   
	?>
	<div id='UmWoTD_options'><?php 
		if ( wc_tax_enabled() ) {				
			echo '<div class="options_group tax">';				
				woocommerce_wp_select( array(
					'id'      => '_tax_status',
					'label'   => __( 'Tax status', 'woocommerce' ),
					'options' => array(
						'taxable' 	=> __( 'Taxable', 'woocommerce' ),
						'shipping' 	=> __( 'Shipping only', 'woocommerce' ),
						'none' 		=> _x( 'None', 'Tax status', 'woocommerce' )
					)
				) );
				$tax_classes         = WC_Tax::get_tax_classes();
				$classes_options     = array();
				$classes_options[''] = __( 'Standard', 'woocommerce' );

				if ( ! empty( $tax_classes ) ) {
					foreach ( $tax_classes as $class ) {
						$classes_options[ sanitize_title( $class ) ] = esc_html( $class );
					}
				}
				woocommerce_wp_select( array(
					'id'          => '_tax_class',
					'label'       => __( 'Tax class', 'woocommerce' ),
					'options'     => $classes_options			
					
				) );
				do_action( 'woocommerce_product_options_tax' );
			echo '</div>';
		} ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#UmWoTD_subscription_type").change(function () {
            if ($(this).val() == "1") {
                $("#um_switcher_product").show();
            } else {
                $("#um_switcher_product").hide();
            }
			
        });
		
		$("#UmWoTD_subscription_type").change(function () {
            if ($(this).val() == "2") {
                $("#um_switcher_product_end_date").show();
            } else {
                $("#um_switcher_product_end_date").hide();
            }
        });

    });
</script>

   <script type="text/javascript">
$(document).ready(function(){
    $('#purpose').on('change', function() {
      if ( this.value == 'send')
      {
        $("#reminder_1").show();
      }
      else
      {
        $("#reminder_1").hide();
      }
    });
	
	 $('#reminder_sel').on('change', function() {
      if ( this.value == 'send')
      {
        $("#reminder_2").show();
      }
      else
      {
        $("#reminder_2").hide();
      }
    });
});

</script>


		<div class='options_group custom-field-section'>
			<p class="form-field UmWoTD_day_field">
			<label>Subscription Start & End Type</label>
				<?php $UmWoTD_subscription_type = get_post_meta( $post->ID, 'UmWoTD_subscription_type', true ); ?>
				<select name="UmWoTD_subscription_type" id="UmWoTD_subscription_type">
					<option value="0">Select Subscription Type</option>
					<option value="1" <?php if($UmWoTD_subscription_type == 1) echo " selected"; ?>>Number of Days</option>
                    <option value="2"<?php if($UmWoTD_subscription_type == 2) echo " selected"; ?>>End Date</option>
				</select>
			</p>
                    
			<!--######################### PRODUCT 1 #########################-->
            <div id='um_switcher_product' style="display: <?php if($UmWoTD_subscription_type == 1) echo "block"; else echo "none"; ?>">        
			<?php
			woocommerce_wp_text_input( array(
				'id'			=> 'UmWoTD_days',
				'label'			=> __( 'Set Days', 'twodayssss' ),								
				'type' 			=> 'number',
			) );
			
				woocommerce_wp_text_input( array(
				'id'			=> 'UmWoTD_hours',
				'label'			=> __( 'Set Hours', 'twodayssss' ),				
				'type' 			=> 'number',
			) );
			woocommerce_wp_text_input( array(
				'id'			=> 'UmWoTD_mins',
				'label'			=> __( 'Set Minuts', 'twodayssss' ),				
				'type' 			=> 'number',
			) );	
			?>
            </div>
            <!--##################################################-->
            
            
			<!--######################### PRODUCT 2 #########################-->
            <div id='um_switcher_product_end_date' style="display:<?php if($UmWoTD_subscription_type == 2) echo "block"; else echo "none"; ?>">
			<?php
			woocommerce_wp_text_input( array( 
			'id' => 'subscription_type_start_date', 
			'label' => __( 'Start date', 'woocommerce' ), 
			'placeholder' => 'From... YYYY-MM-DD',  
			'class' => 'w date-picker',
 			) );
			?>
			<?php
			woocommerce_wp_text_input( array( 
			'id' => 'subscription_type_end_date', 
			'label' => __( 'End date', 'woocommerce' ), 
			'placeholder' => 'To... YYYY-MM-DD',  
			'class' => 'w date-picker',
 			) );
			?>            
            </div>
			<!--##################################################--> 
		</div>
		<div class='options_group role-section'>            
			<p class="form-field UmWoTD_day_field">
			<label><?php _e('Select User Roles','twodayssss');?></label>
				<?php $selectbefore_val = get_post_meta( $post->ID, 'selectbefore', true ); ?>
				<select name="selectbefore" id="selectbefore">
					<option><?php _e( 'Select user role', 'twodayssss' );?></option>
					<?php 
					//foreach( UM()->roles()->role_data( um_user( 'role' ) ) as $key => $value ) 
					foreach( UM()->roles()->get_roles() as $key => $value )
					{ ?>
					<option value="<?php echo $key; ?>" <?php if($selectbefore_val == $key){ ?>selected<?php } ?>><?php echo $value; ?>
					</option>
					<?php } ?>
				</select>
			</p>
		</div>
		<div class='options_group role-section'>
			<p class="form-field UmWoTD_day_field ">
			<label><?php _e('When Experiod','twodayssss');?></label>
			<?php $selectafter_val = get_post_meta( $post->ID, 'selectafter', true ); ?>
			<select name="selectafter" id="selectafter">
			<option><?php _e( 'Select user role', 'twodayssss' );?></option>
			<?php
			//foreach( UM()->roles()->role_data( um_user( 'role' ) ) as $key => $value ) 
			foreach( UM()->roles()->get_roles() as $key => $value )
			{ ?>
				<option value="<?php echo $key; ?>" <?php if($selectafter_val == $key){ ?>selected<?php } ?>><?php echo $value; ?></option>
			<?php } ?>
			</select>
			</p>
		</div>
        
		<div class='options_group role-section'>
			<p class="form-field UmWoTD_day_field ">
			<label><?php _e('Redirect Page After Checkout','twodayssss'); ?></label>
			<?php $redirect_after_checkout = get_post_meta( $post->ID, 'redirect_after_checkout', true ); ?>
            <input type="url" name="redirect_after_checkout" id="redirect_after_checkout" class="short" value="<?php echo $redirect_after_checkout; ?>" placeholder="ex: http://yoursite.com/thank-you-page" />
			</p>
		</div>  
        
		<div class="options_group reminder-section" >
		<p class="form-field note">
			<label for="note"><?php _e('Note:','twodayssss');?></label>
				<?php _e("* You can use the following shortcodes inside your emails.<br/>[site_name][new_line][user][days][hours]","umwotd"); ?>
				<?php _e("<br/>** Reminder's option is recommended to select one in the option.","umwotd"); ?>
		</p>			
			<p class="form-field UmWoTD_rem1_field">
			<label><?php _e('Send Reminder 1','twodayssss'); ?></label>
			<?php $rem1 = get_post_meta( $post->ID, 'rem1', true ); ?>	
			
			<select name="rem1" id='purpose'>
				<option><?php _e('-select one-','twodayssss');?></option>
				<option value="send" <?php if($rem1 == "send"){ ?>selected = "selected" <?php } ?>><?php _e('Send a Reminder','twodayssss'); ?></option>
				<option value="dontsend" <?php if($rem1 == "dontsend"){?>selected = "selected"<?php } ?>><?php _e('Don’t Send a Reminder','twodayssss');?></option>
			</select>
			</p>
			<div class="rem1" style='display:none;' id='reminder_1'>
			<p class="form-field reminder_day1_field ">
				<label for="reminder_day1"><?php _e('Before Experation','twodayssss');?></label>
				<?php $reminder_day1 = get_post_meta( $post->ID, 'reminder_day1', true ); ?>
				<input type="number" min="0" placeholder="Days" value="<?php echo $reminder_day1;?>" id="reminder_day1" name="reminder_day1" style="" class="short">
				<?php $reminder_hours1 = get_post_meta( $post->ID, 'reminder_hours1', true ); ?>
				<input type="number" min="0" placeholder="Hours" value="<?php echo $reminder_hours1;?>" id="reminder_hours1" name="reminder_hours1" style="" class="short">
				<?php $reminder_min1 = get_post_meta( $post->ID, 'reminder_min1', true ); ?>
				<input type="number" min="0" placeholder="Minutes" value="<?php echo $reminder_min1;?>" id="reminder_min1" name="reminder_min1" style="" class="short">				
			</p>
			<p class="form-field reminder_subject1_field ">	
			<?php $reminder_subject1 = get_post_meta( $post->ID, 'reminder_subject1', true ); ?>
				<input type="text" placeholder="" value="<?php if($reminder_subject1 != ""){echo $reminder_subject1;}else{ 
					_e("Your Subscription to [site_name] is about to expiry","umwotd");}?>" id="reminder_subject1" name="reminder_subject1" style="" class="short">
				
			</p>			
			
			<p class="form-field reminder_msg1_field ">	
			<?php $reminder_msg1 = get_post_meta( $post->ID, 'reminder_msg1', true ); ?>			
				<textarea value="" id="reminder_msg1" rows="15" cols="65" name="reminder_msg1" style="" class="short"><?php if($reminder_msg1 != ""){_e($reminder_msg1,'twodayssss');}else{$tmpmsg1="Hello [user],\n[new_line]\nWe send you a reminder message that your subscription to [site_name] will expiry in just [days] and we do not reactivate expired subscriptions.[new_line]If you wish to continue using our services, please visit our website. Simply log in with your username and password and extend your subscription.\n\n[new_line]\nRegards,[site_name]";_e($tmpmsg1,'twodayssss');}?></textarea>
				
			</p>
			<?php
				$redirect_after_checkout = get_post_meta( $post->ID, 'redirect_after_checkout', true );
				$reminder_day1 = get_post_meta( $post->ID, 'reminder_day1', true );
		        $reminder_day2 = get_post_meta( $post->ID, 'reminder_day2', true );
		        $reminder_hours1 = get_post_meta( $post->ID, 'reminder_hours1', true );
		        $reminder_hours2 = get_post_meta( $post->ID, 'reminder_hours2', true );
		        $reminder_min1 = get_post_meta( $post->ID, 'reminder_min1', true );
		        $reminder_min2 = get_post_meta( $post->ID, 'reminder_min2', true );
		        $reminder_subject1 = get_post_meta( $post->ID, 'reminder_subject1', true );
		        $reminder_subject2 = get_post_meta( $post->ID, 'reminder_subject2', true );
		        $reminder_msg1 = get_post_meta( $post->ID, 'reminder_msg1', true );
		        $reminder_msg2 = get_post_meta( $post->ID, 'reminder_msg2', true );
		        $current_user = get_user_by('id', get_current_user_id());
		        $user_name = $current_user->display_name;
		     	$email = $current_user->user_email;
		        $siteurl = get_site_url();   
		        $sitename = um_get_option('mail_from');  
		        $mail_from_addr = um_get_option('mail_from_addr'); 	        
       
		        if($reminder_day1 == " " || $reminder_day1 == 0){
		            $setday1 = "Days";            
		        }
		        if($reminder_day1 == 1){
		            $setday1 = "Day";
		        }    
		        if($reminder_day1 > 1){
		            $setday1 = "Days";
		        }
		        if($reminder_hours1 == " " || $reminder_hours1 == 0){
		            $sethour1 = "Hours";            
		        }
		        if($reminder_hours1 == 1){
		            $sethour1 = "Hour";
		        }    
		        if($reminder_hours1 > 1){
		            $sethour1 = "Hours";
		        }
		        if($reminder_min1 == " " || $reminder_min1 == 0){
		            $setmin1 = "Minutes";            
		        }
		        if($reminder_min1 == 1){
		            $setmin1 = "Minute";
		        }    
		        if($reminder_min1 > 1){
		            $setmin1 = "Minutes";
		        }
		        if($reminder_day2 == " " || $reminder_day2 == 0){
		            $setday2 = "Days";            
		        }
		        if($reminder_day2 == 1){
		            $setday2 = "Day";
		        }    
		        if($reminder_day2 > 1){
		            $setday2 = "Days";
		        }
		        if($reminder_hours2 == " " || $reminder_hours2 == 0){
		            $sethour2 = "Hours";
		            
		        }
		        if($reminder_hours2 == 1){
		            $sethour2 = "Hour";
		        }    
		        if($reminder_hours2 > 1){
		            $sethour2 = "Hours";
		        }
		        if($reminder_min2 == " " || $reminder_min2 == 0){
		            $setmin2 = "Minutes";
		            
		        }
		        if($reminder_min2 == 1){
		            $setmin2 = "Minute";
		        }    
		        if($reminder_min2 > 1){
		            $setmin2 = "Minutes";
		        }

				$replacements1 = array("[user]" => $user_name,"[new_line]" => "<br/><br/>","[site_name]" => $sitename,"[days]"=>$reminder_day1.'-'.$setday1, "[hours]"=>$reminder_hours1.'-'.$sethour1.' '.$reminder_min1.'-'.$setmin1);
		        $replacements2 = array("[user]" => $user_name,"[new_line]" => "<br/><br/>","[site_name]" => $sitename,"[days]"=>$reminder_day2.'-'.$setday2, "[hours]"=>$reminder_hours2.'-'.$sethour2.' '.$reminder_min2.'-'.$setmin2);
		        $replacesub1 = array("[site_name]" => $sitename);
		        $replacesub2 = array("[site_name]" => $sitename);
		        $resultmsg1 = strtr($reminder_msg1, $replacements1);
		        $resultmsg2 = strtr($reminder_msg2, $replacements2);
		        $resultsub1 = strtr($reminder_subject1, $replacesub1);
		        $resultsub2 = strtr($reminder_subject2, $replacesub2);
				?>
			<p class="form-field">
				<input type="button" value="Mail Preview" id="pre1">	
			</p>
			
			<p class="form-field test_mail1" style="display: none;">	
				<?php _e("From: ","umwotd");?> <?php echo $mail_from_addr; echo "<br/>"; ?>
				<?php _e("To: ","umwotd");?> <?php echo $email; echo "<br/>"; ?>		
				<?php _e("Subject: ","umwotd");?><?php echo $resultsub1; echo "<br/>"; ?>	
				<?php _e("Message: ","umwotd");?><?php echo $resultmsg1; ?>		
			</p>

			</div>
			
			<p class="form-field UmWoTD_rem2_field ">
				<label><?php _e('Send Reminder 2','twodayssss');?></label>
				<?php $rem2 = get_post_meta( $post->ID, 'rem2', true ); ?>
				<select name="rem2" id='reminder_sel'>
					<option><?php _e('-select one-','twodayssss');?></option>
					<option value="send" <?php if($rem2 == "send"){ ?> selected ="selected" <?php } ?>><?php _e('Send a Reminder','twodayssss');?></option>
					<option value="dontsend" <?php if($rem2 == "dontsend"){?> selected ="selected" <?php } ?>><?php _e('Don’t Send a Reminder','twodayssss');?></option>
				</select>
			</p>
			<div class="rem2" style='display:none;' id='reminder_2'>
			<p class="form-field reminder_day2_field ">
				<label for="reminder_day2"><?php _e('Before Experation','twodayssss');?></label>
				<?php $reminder_day2 = get_post_meta( $post->ID, 'reminder_day2', true ); ?>
				<input type="number" min="0" placeholder="Days" value="<?php echo $reminder_day2 ;?>" id="reminder_day2" name="reminder_day2" style="" class="short">
				<?php $reminder_hours2 = get_post_meta( $post->ID, 'reminder_hours2', true ); ?>
				<input type="number" min="0" placeholder="Hours" value='<?php echo $reminder_hours2;?>' id="reminder_hours2" name="reminder_hours2" style="" class="short">
				<?php $reminder_min2 = get_post_meta( $post->ID, 'reminder_min2', true ); ?>
				<input type="number" min="0" placeholder="Minutes" value="<?php echo $reminder_min2;?>" id="reminder_min2" name="reminder_min2" style="" class="short">
				
			</p>
			<p class="form-field reminder_subject2_field ">
			<?php $reminder_subject2 = get_post_meta( $post->ID, 'reminder_subject2', true );?>
				<input type="text" placeholder="" value="<?php if($reminder_subject2 != ""){echo $reminder_subject2;}else{ 
					_e("Your Subscription to [site_name] is about to expiry","umwotd");}?>" id="reminder_subject2" name="reminder_subject2" style="" class="short">
				
			</p>
			<p class="form-field reminder_msg2_field ">
			<?php $reminder_msg2 = get_post_meta( $post->ID, 'reminder_msg2', true ); ?>
				<textarea value="" id="reminder_msg2" name="reminder_msg2" style="" class="short"><?php	if($reminder_msg2 != ""){_e($reminder_msg2, 'twodayssss');}else{$tmpmsg2="Hello [user],\n[new_line]\nWe send you a reminder message that your subscription to [site_name] is expired and we do not reactivate expired subscriptions.[new_line]If you wish to continue using our services, please visit our website.Simply log in with your username and password and renew your subscription.\n\n[new_line]\nRegards,[site_name]";_e($tmpmsg2,'twodayssss');}?></textarea>
				
			</p>
			<p class="form-field">
				<input type="button" value="Mail Preview" id="pre2">	
			</p>
			
			<p class="form-field test_mail2" style="display: none;">	
				<?php _e("From: ","umwotd");?><?php echo $mail_from_addr; echo "<br/>"; ?>
				<?php _e("To: ","umwotd");?><?php echo $email; echo "<br/>"; ?>	
				<?php _e("Subject: ","umwotd");?><?php echo $resultsub2; echo "<br/>"; ?>		
				<?php _e("Message: ","umwotd");?><?php echo $resultmsg2; ?>	

			</p>	
			</div>		
		</div>	    
	</div>	

    
	<?php  
 
 	}
 
 	public function wc_custom_save_custom_fields( $post_id ) { 
	    //global $post,$ultimatemember,$wpdb,$product;
		global $post,$wpdb,$product;
           $product_type = $_POST['product-type'];
	    $user_id = get_current_user_id();

	    if ( isset( $_POST['_tax_status'] ) ) {
			update_post_meta( $post_id, '_tax_status', wc_clean( $_POST['_tax_status'] ) );
		}
		if ( isset( $_POST['_tax_class'] ) ) {
			update_post_meta( $post_id, '_tax_class', wc_clean( $_POST['_tax_class'] ) );
		}

		//new
		$UmWoTD_subscription_type =  $_POST['UmWoTD_subscription_type'];
		$subscription_type_start_date =  $_POST['subscription_type_start_date'];
		$subscription_type_end_date =  $_POST['subscription_type_end_date'];	
        update_post_meta( $post_id, 'UmWoTD_subscription_type', esc_attr( $UmWoTD_subscription_type ) );
		update_post_meta( $post_id, 'subscription_type_start_date', esc_attr( $subscription_type_start_date ) );	
		update_post_meta( $post_id, 'subscription_type_end_date', esc_attr( $subscription_type_end_date ) );		
		//end new
		
		$UmWoTD_days =  $_POST['UmWoTD_days'];
		$UmWoTD_hours =  $_POST['UmWoTD_hours'];
		$UmWoTD_mins =  $_POST['UmWoTD_mins'];
		$selectbefore =  $_POST['selectbefore'];
		$selectafter =  $_POST['selectafter'];	
        update_post_meta( $post_id, 'product_type', esc_attr( $product_type ) );
		update_post_meta( $post_id, 'UmWoTD_days', esc_attr( $UmWoTD_days ) );	
		update_post_meta( $post_id, 'UmWoTD_hours', esc_attr( $UmWoTD_hours ) );		
		update_post_meta( $post_id, 'UmWoTD_mins', esc_attr( $UmWoTD_mins ) );
		update_post_meta( $post_id, 'selectbefore', esc_attr( $selectbefore ) );
		update_post_meta( $post_id, 'selectafter', esc_attr( $selectafter ) );
		update_post_meta( $post_id, 'UmWoTD_cron', esc_attr( $UmWoTD_cron ) );
		update_post_meta( $user_id, 'UmWoTD_cron', esc_attr( $UmWoTD_cron ) );
		

	 	$rem1 =  $_POST['rem1'];	 
		$redirect_after_checkout = $_POST['redirect_after_checkout'];	
	 	$reminder_day1 =  $_POST['reminder_day1'];	
		$reminder_hours1 =  $_POST['reminder_hours1'];	
		$reminder_min1 =  $_POST['reminder_min1'];
		$reminder_subject1 =  $_POST['reminder_subject1'];	
		$reminder_msg1 =  $_POST['reminder_msg1'];

		update_post_meta( $post_id, 'redirect_after_checkout', esc_attr( $redirect_after_checkout ) );
		
		update_post_meta( $post_id, 'rem1', esc_attr( $rem1 ) );		
		if( $reminder_day1 == "" ) {
			$reminder_day1 = 0;
			update_post_meta( $post_id, 'reminder_day1', esc_attr( $reminder_day1 ) );
		}else{
			update_post_meta( $post_id, 'reminder_day1', esc_attr( $reminder_day1 ) );
		}

		if( $reminder_hours1 == "" ) {
			$reminder_hours1 = 0;
			update_post_meta( $post_id, 'reminder_hours1', esc_attr( $reminder_hours1 ) );
		}else{
			update_post_meta( $post_id, 'reminder_hours1', esc_attr( $reminder_hours1 ) );
		}

		if( $reminder_min1 == "" ) {
			$reminder_min1 = 0;
			update_post_meta( $post_id, 'reminder_min1', esc_attr( $reminder_min1 ) );
		}else{
			update_post_meta( $post_id, 'reminder_min1', esc_attr( $reminder_min1 ) );
		}	
		update_post_meta( $post_id, 'reminder_subject1', esc_attr( $reminder_subject1 ) );		
		update_post_meta( $post_id, 'reminder_msg1', esc_attr( $reminder_msg1) );
		

		$rem2 =  $_POST['rem2'];	
		$reminder_day2 =  $_POST['reminder_day2'];	
		$reminder_hours2 =  $_POST['reminder_hours2'];	
		$reminder_min2 =  $_POST['reminder_min2'];	
		$reminder_subject2 =  $_POST['reminder_subject2'];	
		$reminder_msg2 =  $_POST['reminder_msg2'];
		
		update_post_meta( $post_id, 'rem2', esc_attr( $rem2 ) );		
		if( $reminder_day2 == "" ) {
			$reminder_day2 = 0;
			update_post_meta( $post_id, 'reminder_day2', esc_attr( $reminder_day2 ) );
		}else{
			update_post_meta( $post_id, 'reminder_day2', esc_attr( $reminder_day2 ) );
		}

		if( $reminder_hours2 == "" ) {
			$reminder_hours2 = 0;
			update_post_meta( $post_id, 'reminder_hours2', esc_attr( $reminder_hours2 ) );
		}else{
			update_post_meta( $post_id, 'reminder_hours2', esc_attr( $reminder_hours2 ) );
		}
		
		if( $reminder_min2 == "" ) {
			$reminder_min2 = 0;
			update_post_meta( $post_id, 'reminder_min2', esc_attr( $reminder_min2 ) );
		}else{
			update_post_meta( $post_id, 'reminder_min2', esc_attr( $reminder_min2 ) );
		}		
		update_post_meta( $post_id, 'reminder_subject2', esc_attr( $reminder_subject2 ) );
		update_post_meta( $post_id, 'reminder_msg2', esc_attr( $reminder_msg2 ) );
		

		
	}
}
new Um_Switcher_Meta_Boxes();