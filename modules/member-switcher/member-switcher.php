<?php
/**
 * Member Name: Member-Switcher
 */

error_reporting(0);
error_reporting(E_ERROR | E_PARSE);

if ( ! defined( 'ABSPATH' ) )
	exit;

register_activation_hook( __FILE__, 'umwotd_install' );
	function umwotd_install() {			

	}

if(  !class_exists( 'UM' ) ){

	add_action( 'admin_notices', 'my_error_notice1' );
	function my_error_notice1() {
    ?>
    <div class="error notice">
        <p><?php _e( 'Please activate <b>Ultimate Member Plugin</b> for UM Switcher Plugin', 'twodayssss' ); ?></p>
    </div>
    <?php
	}	
	return false;

}
if( !is_plugin_active( 'woocommerce/woocommerce.php' )){

	add_action( 'admin_notices', 'my_error_notice' );
	function my_error_notice() {
    ?>
    <div class="error notice">
        <p><?php _e( 'Please activate <b>WooCommerce Plugin</b> for UM Switcher Plugin', 'twodayssss' ); ?></p>
    </div>
    <?php
	}	
	return false;
}


add_shortcode( 'UMSWTICHER-USER-SUBSCRIPTION', 'umswitcher_user_subscription_expiration' );
add_shortcode( 'UMSWTICHER-PROFILE-SUBSCRIPTION', 'umswitcher_profile_subscription_expiration' );

class Um_Switcher{

	public $post;
	public $wpdb;
	public $woocommerce;
	/**
	 * Plugin data from get_plugins()
	 *
	 * @since 1.0
	 * @var object
	 */
	public $plugin_data;

	/**
	 * Includes to load
	 *
	 * @since 1.0
	 * @var array
	 */

	public $includes;
	/**
	 * Plugin Action and Filter Hooks
	 *
	 * @since 1.0.0
	 * @return null
	 */
	public function __construct(){
		global $wpdb,$post,$woocommerce;	
		add_action( 'plugins_loaded' , array($this, 'um_switcher_define_constants'), 1);
		add_action( 'plugins_loaded', array( $this, 'umwotd_support_multilanguages' ) );
		add_action( 'plugins_loaded' , array($this, 'umwotd_set_includes'), 1);
		add_action( 'plugins_loaded' , array($this, 'umwotd_load_includes'), 1);
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles_and_scripts' ) );
		add_action( 'admin_footer', array($this, 'um_switcher_custom_js' ));	
		add_action( 'woocommerce_um_switcher_add_to_cart', array($this, 'umwotd_add_to_cart'),30);
		add_action( 'woocommerce_thankyou',array($this,'insert_into_database'));		
		
		add_action( 'wp_ajax_umswticher_cronjob', array( $this, 'umswticher_cronjob' ) );
		add_action( 'wp_ajax_nopriv_umswticher_cronjob', array( $this, 'umswticher_cronjob' ) );
		
		add_action( 'wp_ajax_umswticher_settings', array( $this, 'umswticher_settings' ) );
		add_action( 'wp_ajax_nopriv_umswticher_settings', array( $this, 'umswticher_settings' ) );		
			
		$table_name1 = $wpdb->prefix . 'um_switcher';	
		$table_name2 = $wpdb->prefix . 'email_template';
		$table_name3 = $wpdb->prefix . 'um_switcher_settings';
		$table_name4 = $wpdb->prefix . 'um_switcher_history';
		
	
		### history loader ###
		if( @ $_REQUEST["um_switcher__load_history"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$ret_data = '<p class="hist">Historie</p>';
			$dbvh = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."um_switcher_history WHERE ums_id = '".$_REQUEST["um_switcher__id"]."' LIMIT 4");      
			foreach($dbvh as $dbvh_row)
			{
				switch($dbvh_row->action)
				{
					case "REMINDER 1":
						$class_icon = "glyphicon glyphicon-envelope";
						$data_old = "Reminder 1";
						$data_new = "Reminder 1 New";
						break;
					case "REMINDER 2":
						$class_icon = "glyphicon glyphicon-envelope";
						$data_old = "Reminder 2";
						$data_new = "Reminder 2 New";
						break;
					case "END-DATE":
						$class_icon = "glyphicon glyphicon-user";
						$data_old = "Not active Member";
						$data_new = "Active Member";
						break;
					case "ENDATE-BASED-START":
						$class_icon = "glyphicon glyphicon-calendar";
						$data_old = "Date old";
						$data_new = "Date new";
						break;
					case "ENDATE-BASED-END":
						$class_icon = "glyphicon glyphicon-calendar";
						$data_old = "Date old";
						$data_new = "Date new";
						break;
				}
				$ret_data = $ret_data.'
				   <div class="row">
						<div class="col-sm-4">'.$dbvh_row->date_time.'</div>
						<div class="col-sm-4"><span class="'.$class_icon.'" style="color: red; margin-right: 3px"></span>'.$data_old.'</br>
						<span class="ums-hist-date">'.$dbvh_row->old_date.'</span>
						</div>
						<div class="col-sm-4"><span class="'.$class_icon.'" style="color: green; margin-right: 3px"></span>'.$data_new.'</br>
						<span class="ums-hist-date">'.$dbvh_row->new_date.'</span>
						</div>
					</div>';
			}	
			echo $ret_data;		
			die();
		}
		#############################	

	
		### save history notes ###
		if( @ $_REQUEST["um_switcher__history_notes"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
	    	$prefix = $wpdb->prefix;
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET `notes`='".$_REQUEST["um_switcher__history_notes"]."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
			if($result) echo 1;
			else echo 0; die();
		}
		#############################
		
		
		### save start date changes ###
		if( @ $_REQUEST["um_switcher__start_date"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$new_date = date("Y-m-d H:i:s", strtotime($_REQUEST["um_switcher__start_date"]));
	    	$prefix = $wpdb->prefix;
			
			//save history
			$date_today = date("Y-m-d")." ".date("H:i:s"); 
			$dbv = $wpdb->get_results("SELECT expire_time FROM ".$prefix."um_switcher WHERE id = '".$_REQUEST["um_switcher__id"]."' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  $history_current_time = $dbv_row->expire_time;
			}
			$wpdb->get_results("INSERT INTO ".$prefix."um_switcher_history SET ums_id = '".$_REQUEST["um_switcher__id"]."', action='END-DATE', old_date='$history_current_time', new_date='$new_date', date_time='$date_today'");      
			//end
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET `current_time`='".$new_date."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
			if($result) echo 1;
			else echo 0; die();
		}
		#############################
		
		### save end date changes ###
		if( @ $_REQUEST["um_switcher__end_date"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$new_date = date("Y-m-d H:i:s", strtotime($_REQUEST["um_switcher__end_date"]));
	    	$prefix = $wpdb->prefix;
			
			//save history
			$date_today = date("Y-m-d")." ".date("H:i:s"); 
			$dbv = $wpdb->get_results("SELECT expire_time FROM ".$prefix."um_switcher WHERE id = '".$_REQUEST["um_switcher__id"]."' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  $history_current_time = $dbv_row->expire_time;
			}
			$wpdb->get_results("INSERT INTO ".$prefix."um_switcher_history SET ums_id = '".$_REQUEST["um_switcher__id"]."', action='END-DATE', old_date='$history_current_time', new_date='$new_date', date_time='$date_today'");      
			//end
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET expire_time='".$new_date."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
			if($result) echo 1;
			else echo 0; die();
		}
		#############################
		
		
		### based on end date #########
		### save start date changes ###
		if( @ $_REQUEST["um_switcher__based_end_date__start_date"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$new_date = date("Y-m-d H:i:s", strtotime($_REQUEST["um_switcher__based_end_date__start_date"]));
	    	$prefix = $wpdb->prefix;
			
			//save history
			$date_today = date("Y-m-d")." ".date("H:i:s"); 
			$dbv = $wpdb->get_results("SELECT subscription_type_start_date FROM ".$prefix."um_switcher WHERE id = '".$_REQUEST["um_switcher__id"]."' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  $history_current_time = $dbv_row->subscription_type_start_date;
			}
			$wpdb->get_results("INSERT INTO ".$prefix."um_switcher_history SET ums_id = '".$_REQUEST["um_switcher__id"]."', action='ENDATE-BASED-START', old_date='$history_current_time', new_date='$new_date', date_time='$date_today'");      
			//end
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET `subscription_type_start_date`='".$new_date."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
			if($result) echo 1;
			else echo 0; die();
		}
		#############################
		
		### save end date changes ###
		if( @ $_REQUEST["um_switcher__based_end_date__end_date"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$new_date = date("Y-m-d H:i:s", strtotime($_REQUEST["um_switcher__based_end_date__end_date"]));
	    	$prefix = $wpdb->prefix;
			
			//save history
			$date_today = date("Y-m-d")." ".date("H:i:s"); 
			$dbv = $wpdb->get_results("SELECT subscription_type_end_date FROM ".$prefix."um_switcher WHERE id = '".$_REQUEST["um_switcher__id"]."' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  $history_current_time = $dbv_row->subscription_type_end_date;
			}
			$wpdb->get_results("INSERT INTO ".$prefix."um_switcher_history SET ums_id = '".$_REQUEST["um_switcher__id"]."', action='ENDATE-BASED-END', old_date='$history_current_time', new_date='$new_date', date_time='$date_today'");      
			//end
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET subscription_type_end_date='".$new_date."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
			if($result) echo 1;
			else echo 0; die();
		}
		#############################		
		
		### save manually subscription role ###
		if( @ $_REQUEST["um_switcher__subscription"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			
	    	$prefix = $wpdb->prefix;
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET `selectbefore`='".$_REQUEST["um_switcher__subscription"]."', user_status='".$_REQUEST["um_switcher__subscription"]."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
	
			um_fetch_user( $_REQUEST["um_switcher__user_id"] );
			wp_update_user( array( 'ID' => $_REQUEST["um_switcher__user_id"], 'role' =>$_REQUEST["um_switcher__subscription"] ) );
			
			if($result) echo 1;
			else echo 0; die();
		}
		#############################		
		
		### save manually subscription reminder 1 ###
		if( @ $_REQUEST["um_switcher__reminder1_changes"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$new_date = date("Y-m-d H:i:s", strtotime($_REQUEST["um_switcher__reminder1_changes"]));
	    	$prefix = $wpdb->prefix;
			
			//save history
			$day1 = date("d", strtotime($_REQUEST["um_switcher__reminder1_changes"])); 
			$hours1 = date("H", strtotime($_REQUEST["um_switcher__reminder1_changes"]));
			$min1 = date("i", strtotime($_REQUEST["um_switcher__reminder1_changes"]));
		
			$date_today = date("Y-m-d")." ".date("H:i:s"); 
			$dbv = $wpdb->get_results("SELECT * FROM ".$prefix."um_switcher WHERE id = '".$_REQUEST["um_switcher__id"]."' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  	$history_current_time = $dbv_row->expire_time;
				$new_date = date('Y-m-d H:i:s',strtotime('-'.$day1.' days -'.$hours1.' hours -'.$min1.' minutes',strtotime($history_current_time)));
				$history_current_time = date('Y-m-d H:i:s',strtotime('-'.$dbv_row->day1.' days -'.$dbv_row->hours1.' hours -'.$dbv_row->min1.' minutes',strtotime($history_current_time)));			  
			}
			$wpdb->get_results("INSERT INTO ".$prefix."um_switcher_history SET ums_id = '".$_REQUEST["um_switcher__id"]."', action='REMINDER 1', old_date='$history_current_time', new_date='$new_date', date_time='$date_today'");      
			//end
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET day1='".$day1."', hours1='".$hours1."', min1='".$min1."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
				
			if($result) echo 1;
			else echo 0; die();
		}
		#############################	
		
		### save manually subscription reminder 2 ###
		if( @ $_REQUEST["um_switcher__reminder2_changes"] <> "" && $_REQUEST["um_switcher__id"] <> "")
		{
			$new_date = date("Y-m-d H:i:s", strtotime($_REQUEST["um_switcher__reminder2_changes"]));
	    	$prefix = $wpdb->prefix;
			
			//save history
			$day2 = date("d", strtotime($_REQUEST["um_switcher__reminder2_changes"])); 
			$hours2 = date("H", strtotime($_REQUEST["um_switcher__reminder2_changes"]));
			$min2 = date("i", strtotime($_REQUEST["um_switcher__reminder2_changes"]));
		
			$date_today = date("Y-m-d")." ".date("H:i:s"); 
			$dbv = $wpdb->get_results("SELECT * FROM ".$prefix."um_switcher WHERE id = '".$_REQUEST["um_switcher__id"]."' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  	$history_current_time = $dbv_row->expire_time;
				$new_date = date('Y-m-d H:i:s',strtotime('-'.$day2.' days -'.$hours2.' hours -'.$min2.' minutes',strtotime($history_current_time)));
				$history_current_time = date('Y-m-d H:i:s',strtotime('-'.$dbv_row->day2.' days -'.$dbv_row->hours2.' hours -'.$dbv_row->min2.' minutes',strtotime($history_current_time)));			  
			}
			$wpdb->get_results("INSERT INTO ".$prefix."um_switcher_history SET ums_id = '".$_REQUEST["um_switcher__id"]."', action='REMINDER 2', old_date='$history_current_time', new_date='$new_date', date_time='$date_today'");      
			//end
			
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET day2='".$day2."', hours2='".$hours2."', min2='".$min2."' WHERE id = '".$_REQUEST["um_switcher__id"]."'");
				
			if($result) echo 1;
			else echo 0; die();
		}
		#############################		
		
		$is_upgrade = 0;
		
		if($wpdb->get_var("show tables like '$table_name4'") != $table_name4){
			$sql = "CREATE TABLE " . $table_name4 . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`ums_id` mediumint(9) NOT NULL,
			`action` varchar(50) NOT NULL,
			`old_date` datetime NOT NULL,
			`new_date` datetime NOT NULL,
			`date_time` datetime NOT NULL,
			UNIQUE KEY id (id)
			);"; 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$is_upgrade = 1;
			@dbDelta($sql);
		}
		
		if($wpdb->get_var("show tables like '$table_name3'") != $table_name3){
			$sql = "CREATE TABLE " . $table_name3 . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`type` varchar(50) NOT NULL,
			`type_value` varchar(100) NOT NULL,
			UNIQUE KEY id (id)
			);"; 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$sql_insert1 = "INSERT INTO ".$table_name3." (type,type_value) VALUES('add_to_cart_button_txt_1', 'ADD')"; 
			$sql_insert2 = "INSERT INTO ".$table_name3." (type,type_value) VALUES('add_to_cart_button_txt_2', 'VIEW')"; 
			$sql_insert3 = "INSERT INTO ".$table_name3." (type,type_value) VALUES('db_version', '2.0')"; 
			$is_upgrade = 1;
			@dbDelta($sql);
			@dbDelta($sql_insert1);
			@dbDelta($sql_insert2);
			@dbDelta($sql_insert3);
		}	
		
		
		if($wpdb->get_var("show tables like '$table_name1'") != $table_name1){
			$sql = "CREATE TABLE " . $table_name1 . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`user_id` int(11) NOT NULL,
			`product_id` int(11) NOT NULL,
			`order_id` int(11) NOT NULL,
			`current_time` datetime NOT NULL,
			`UmWoTD_subscription_type` int(1) NOT NULL DEFAULT '1',
			`subscription_type_start_date` date NOT NULL,
			`subscription_type_end_date` date NOT NULL,
			`days` int(11) NOT NULL,
			`hours` int(11) NOT NULL,
			`mins` int(11) NOT NULL,
			`rem1` varchar(10) NOT NULL,
			`rem2` varchar(10) NOT NULL,	
			`day1` int(11) NOT NULL,	
			`day2` int(11) NOT NULL,
			`hours1` int(11) NOT NULL,
			`hours2` int(11) NOT NULL,
			`min1` int(11) NOT NULL,
			`min2` int(11) NOT NULL,
			`rem_sub1` varchar(500) NOT NULL,
			`rem_sub2` varchar(500) NOT NULL,	
			`rem_msg1` varchar(500) NOT NULL,
			`rem_msg2` varchar(500) NOT NULL,
			`selectbefore` varchar(200) NOT NULL,
			`selectafter` varchar(200) NOT NULL,
			`expire_time` datetime NOT NULL,
			`flag1` int(1) NOT NULL,
			`flag2` int(1) NOT NULL,
			`user_status` varchar(200) NOT NULL,
			`site_name` varchar(200) NOT NULL,
			`wc_order_status` varchar(200) NOT NULL,
			`notes` TEXT NOT NULL,
			UNIQUE KEY id (id)
			);"; 
			if($is_upgrade == 0) { require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); }
			$is_upgrade = 1;
			@dbDelta($sql);

		}
		else
		{

		   ############### get db version ############
		    $db_version_data = "";
			$db_settings = array();
			$data = @file_get_contents($db_file);
			$db_settings = @explode("\n", $data);
			$db_version_data = @$db_settings[0]; 
			$dbv = $wpdb->get_results("SELECT type_value FROM ".$table_name3." WHERE type='db_version' LIMIT 1");      
			foreach($dbv as $dbv_row)
			{
			  $db_version_data = $dbv_row->type_value;  			
			}
		   ###############################################			
			
			if($db_version_data <> 2.1 && $db_version_data <> 2.2)
			{
				$sql = "UPDATE ".$table_name3." SET type_value='2.1' WHERE type='db_version'"; 
				@$wpdb->get_var($sql);
				
				$sql = "ALTER TABLE " . $table_name1 . " ADD COLUMN 
				UmWoTD_subscription_type int(1) NOT NULL"; 
				@$wpdb->get_var($sql);
				
				$sql = "ALTER TABLE " . $table_name1 . " ADD COLUMN 
				subscription_type_start_date date NOT NULL"; 
				@$wpdb->get_var($sql);
				
				$sql = "ALTER TABLE " . $table_name1 . " ADD COLUMN 
				subscription_type_end_date date NOT NULL"; 
				@$wpdb->get_var($sql);

				$sql = "ALTER TABLE " . $table_name1 . " CHANGE `UmWoTD_subscription_type` `UmWoTD_subscription_type` INT(1) NOT NULL DEFAULT '1'"; 
				@$wpdb->get_var($sql);
				
				$sql = "ALTER TABLE " . $table_name1 . " ADD COLUMN 
				wc_order_status varchar(200) NOT NULL"; 
				@$wpdb->get_var($sql);			
			}
			
			if($db_version_data <> 2.2)
			{
				$sql = "UPDATE ".$table_name3." SET type_value='2.2' WHERE type='db_version'"; 
				@$wpdb->get_var($sql);
				
				$sql = "ALTER TABLE " . $table_name1 . " ADD COLUMN 
				notes TEXT NOT NULL"; 
				@$wpdb->get_var($sql);
			}			
		}


		if($wpdb->get_var("show tables like '$table_name2'") != $table_name2){
			$sql = "CREATE TABLE " . $table_name2 . " (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`from_name` varchar(50) NOT NULL,
			`from_address` varchar(100) NOT NULL,	
			`header_image` varchar(500) NOT NULL,	
			`footer_text` varchar(1000) NOT NULL,
			`base_color` varchar(15) NOT NULL,
			`bg_color` varchar(15) NOT NULL,		
            `body_bg_color` varchar(15) NOT NULL,	
			`body_txt_color` varchar(15) NOT NULL,	
            `checkboxemail` varchar(15) NOT NULL,
			UNIQUE KEY id (id)
			);"; 
			$imageurl = get_site_url().'/wp-content/plugins/um-switcher/assets/images/um-switcher.jpg';
			$sql_insert = "INSERT INTO ".$table_name2." (id,from_name,from_address,header_image,footer_text,base_color,bg_color,body_bg_color,body_txt_color,checkboxemail) VALUES('1','UM SWITCHER - DEVELOPMENT SITE','hello@umswitcher.com','$imageurl','HERE COMES YOUR FOOTER MESSAGE','#f7f7f7','#ffffff','#ffffff','#000000','off')"; 
			
			if($is_upgrade == 0) { require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); }
			@dbDelta($sql);
			@dbDelta($sql_insert);
		}
		
	}
	
	

	/**
	 * Umswticher settings - Add to Cart Button
	 *
	 * 1.0.0
	 */
	public function umswticher_settings()
	{
		global $wpdb;
    	$prefix = $wpdb->prefix;
		$ret = "No changes has been made!";
		if($_REQUEST["add_to_cart_button_txt_1"] <> "")
		{
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher_settings SET type_value='".$_REQUEST["add_to_cart_button_txt_1"]."' WHERE type = 'add_to_cart_button_txt_1'");
			$ret = "Successfully saved!";
		}
		if($_REQUEST["add_to_cart_button_txt_2"] <> "")
		{
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher_settings SET type_value='".$_REQUEST["add_to_cart_button_txt_2"]."' WHERE type = 'add_to_cart_button_txt_2'");
			$ret = "Successfully saved!";
		}
	}
	
	/**
	 * Cron Job
	 *
	 * @since 1.0.0
	 */
	public function umswticher_cronjob()
	{
		global $wpdb,$post,$woocommerce;	
    	$prefix = $wpdb->prefix;
		$table_woo = ""; 
		
	   $result = $wpdb->get_results("SELECT * FROM ".$prefix."users JOIN ".$prefix."um_switcher ON ".$prefix."users.ID = ".$prefix."um_switcher.user_id");
	   $result1 = $wpdb->get_results("SELECT * from ".$prefix."options WHERE option_id = 1");
	   
	   $var = array();
	   foreach($result1 as $row1)
	   { 
	   		$var['base_url'] = $row1->option_value; 
	   }
	   
	   foreach($result as $row)
	   { 
				if($row->UmWoTD_subscription_type == 2) {
					$exp_date = $row->subscription_type_end_date;  
				} else {
					$exp_date = $row->expire_time;  
				}	   
			   
			   date_default_timezone_set('Europe/London');
			   $todays_date = date("Y-m-d H:i:s");      
			   $date1=date_create($todays_date);
			   $date2=date_create($exp_date);  
			   
			   $diff=date_diff($date1,$date2);
			  
			   $diff->format("%R%a days and %R%h hours and %R%i Minutes");      
			   $remday = $diff->format("%R%a"); 
			   $remhour = $diff->format("%R%h");
			   $remmin = $diff->format("%R%i");
			   //$redirect_after_checkout = $row->redirect_after_checkout;
			   $reminder_day1 = $row->day1;
			   $reminder_day2 = $row->day2;
			   $reminder_hours1 = $row->hours1;
			   $reminder_hours2 = $row->hours2;
			   $reminder_min1 = $row->min1;
			   $reminder_min2 = $row->min2;
			   $rem1 = $row->rem1;
			   $rem2 = $row->rem2;
			  
			   /** HERE YOU CAN USE YOUR CUSTOM EMAIL ADDRESS, BY DEFAULT THE SYSTEM WILL USE THE ADMIN EMAIL SEE: SETTINGS/GENERAL. 
			   REPLACING: get_option( 'admin_email' FOR YOUR CUSTOM EMAIL.*/   
			   $user_email = $row->user_email;
			   $user_name = $row->display_name;
			   $host = gethostname();  
	   
			   $sub1 = $row->rem_sub1;
			   $sub2 = $row->rem_sub2;
			   $msg1 = $row->rem_msg1;
			   $msg2 = $row->rem_msg2;
			   $emailvar['from_name'] = "";
			   $emailvar['from_address'] = "";
			   $emailvar['header_image'] = "";
			   $emailvar['footer_text'] = "";
			   $emailvar['base_color'] = "";
			   $emailvar['bg_color'] = "";
			   $emailvar['body_bg_color'] = "";
			   $emailvar['body_txt_color'] = "";
				 
				 
			   $email_template = $wpdb->get_results("SELECT * FROM ".$prefix."email_template");
			   $emailvar = array();
			   foreach($email_template as $emailrow)
			   { 
					 $emailvar['from_name'] = $emailrow->from_name;  
					 $emailvar['from_address'] = $emailrow->from_address;
					 $emailvar['header_image'] = $emailrow->header_image;
					 $emailvar['footer_text'] = $emailrow->footer_text;
					 $emailvar['base_color'] = $emailrow->base_color;
					 $emailvar['bg_color'] = $emailrow->bg_color;
					 $emailvar['body_bg_color'] = $emailrow->body_bg_color;
					 $emailvar['body_txt_color'] = $emailrow->body_txt_color;					
			   }
			   
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
			   $replacements1 = array("[user]" => $user_name,"[new_line]"=>"<br/><br/>","[site_name]" => $emailvar['from_name'],"[days]"=>$reminder_day1.'-'.$setday1.' '.$reminder_hours1.'-'.$sethour1.' '.$reminder_min1.'-'.$setmin1);        
			   $replacements2 = array("[user]" => $user_name,"[new_line]"=>"<br/><br/>","[site_name]" => $emailvar['from_name'],"[days]"=>$reminder_day2.'-'.$setday2.' '.$reminder_hours2.'-'.$sethour2.' '.$reminder_min2.'-'.$setmin2);
			   $replacesub1 = array("[site_name]" => $emailvar['from_name']);
			   $replacesub2 = array("[site_name]" => $emailvar['from_name']);
	   
			   $resultmsg1 = strtr($msg1, $replacements1);        
			   $resultmsg2 = strtr($msg2, $replacements2);
			   $resultsub1 = strtr($sub1, $replacesub1);
			   $resultsub2 = strtr($sub2, $replacesub2);
			   
	   
			   /** HERE IS THE REMINDER EMAIL.*/
			   if($rem1 == "send")
			   {    
				  
				  //if($remday <= $reminder_day1 && $remhour <= $reminder_hours1 && $remmin <= $reminder_min1 && $UmWoTD_subscription_type == 1)
				  if($remday <= $reminder_day1 && $remhour <= $reminder_hours1 && $remmin <= $reminder_min1)
				  {
					   $to = $user_email;
					   $subject = $resultsub1;  
					   /*new code for email template start*/
	   
					   $imagesrc = '<img src="'.$emailvar['header_image'].'" width="650" height="350">';
	   
					   $message = '<html><style>
							   @media (min-height:1050px) and (max-height:2500px){#body{  height: 100% !important;}} 
							   @media (min-width:1400px) and (max-width:2500px){#body{  height: 100% !important;}}</style>
								   <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
									   <div id="body" style="background-color:'.$emailvar['base_color'].';width:100%;-webkit-text-size-adjust:none !important;margin:0;padding:0px;height:auto;">';
					   
					   $message .= "<table border='0' cellpadding='0' cellspacing='0' width='680' id='template_container' style='-webkit-box-shadow:none !important;box-shadow:none !important;-webkit-border-radius:6px !important;border-radius:6px !important;background-color:".$emailvar['base_color'].";border:none;text-align:center;margin:0 auto;padding:70px 0px;'>";
					   
					   $message .=  "<tr><td align='center' valign='top'>";
					   
					   $message .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_header' style='background-color: ".$emailvar['bg_color'].";color: #f1f1f1;-webkit-border-top-left-radius:6px !important;-webkit-border-top-right-radius:6px !important;border-top-left-radius:6px !important;border-top-right-radius:6px !important;border-bottom: 0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle;'>
									   <tr>
										   <td>
											   <h1 style='color: #000000;margin:0;padding: 28px 24px;display:block;font-family:Arial;font-size: 30px;font-weight:bold;text-align:center;line-height: 150%;' id='logo'>
											   <a style='color: #000000;text-decoration: none;'' href='https://theme-dutch.com/presents/profiler' title=".$emailvar['from_name']."'>$imagesrc</a>
											   </h1>
										   </td>
									   </tr>
								   </table>";
					   
					   $message .= "</td></tr>";
					   
					   $message .= "<tr>
									   <td align='center' valign='top'>
										   <table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_body'>
											   <tr>
												   <td valign='top' style='background-color: ".$emailvar['body_bg_color'].";' id='mailtpl_body_bg'>
													   <table border='0' cellpadding='20' cellspacing='0' width='100%'>
														   <tr>
															   <td valign='top'>
																   <div style='color:".$emailvar['body_txt_color'].";font-family:Arial;font-size: 14px;line-height:150%;text-align:left;' id='mailtpl_body'>
																	   <p>$resultmsg1</p>
																   </div>
															   </td>
														   </tr>
													   </table>
												   </td>
											   </tr>
										   </table>
									   </td>
								   </tr>
								   ";
					   
					   $message .= "<tr>
									   <td align='center' valign='top'>
										   <table border='0' cellpadding='10' cellspacing='0' width='100%' id='template_footer' style='border-top:1px solid #E2E2E2;background: #ffffff;-webkit-border-radius:0px 0px 6px 6px;-o-border-radius:0px 0px 6px 6px;-moz-border-radius:0px 0px 6px 6px;border-radius:0px 0px 6px 6px;'>
												   <tr>
													   <td valign='top'>
														   <table border='0' cellpadding='10' cellspacing='0' width='100%'>
															   <tr>
																   <td colspan='2' valign='middle' id='credit' style='border:0;color:#000000;font-family: Arial;font-size: 10px;line-height:125%;text-align:left;'>                                
																	   ".$emailvar['footer_text']."
																	   <p>THIS EMAIL IS FROM CRON.PHP 1</p>
																   </td>
															   </tr>
														   </table>
													   </td>
												   </tr>
										   </table>
									   </td>
								   </tr>
								   </table>
								   </div></body></html>"; 
	   
	   
					   /*new code for email template end*/
					   $headers = "MIME-Version: 1.0" . "\r\n";
					   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";        
						$headers .= 'From: '.$emailvar['from_name'].'<'.$emailvar['from_address'].'>' . "\r\n";
					  // $headers .= 'Bcc: '.$emailvar['from_address'].'' . "\r\n";  
						
					   if($row->flag1 == 0)
					   {                  
						   mail($to,$subject,$message,$headers);
						   $wpdb->get_results('UPDATE '.$prefix.'um_switcher SET flag1 = 1 WHERE product_id = '.$row->product_id.' AND user_id = '.$row->user_id);
						   echo "Mail For Reminder1 Send Successfully<br />";    
						   //echo "Mail For Reminder1 Send Successfully for user" . $row->user_email;    
					   }   
					   if($row->flag1 == 1)
					   {                  
						   echo "Mail was already send for Reminder1<br />";    
						   //echo "Mail was already send for Reminder1 with user" . $row->user_email;    
					   }                
							 
				   }        
			   }
			   if($rem2 == "send")
			   {
				   //if($remday <= $reminder_day2 && $remhour <= $reminder_hours2 && $remmin <= $reminder_min2 && $UmWoTD_subscription_type == 1)
					if($remday <= $reminder_day2 && $remhour <= $reminder_hours2 && $remmin <= $reminder_min2)				   
				   {  
					   $to = $user_email;
					   $subject = $resultsub2;           
						 /*new code for email template start*/
	   
					   $imagesrc = '<img src="'.$emailvar['header_image'].'" width="650" height="350">';
	   
					   $message = '<html><style>
							   @media (min-height:1050px) and (max-height:2500px){#body{  height: 100% !important;}} 
							   @media (min-width:1400px) and (max-width:2500px){#body{  height: 100% !important;}}</style>
								   <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
									   <div id="body" style="background-color:'.$emailvar['base_color'].';width:100%;-webkit-text-size-adjust:none !important;margin:0;padding:0px;height:auto;">';
					   
					   $message .= "<table border='0' cellpadding='0' cellspacing='0' width='680' id='template_container' style='-webkit-box-shadow:none !important;box-shadow:none !important;-webkit-border-radius:6px !important;border-radius:6px !important;background-color:".$emailvar['base_color'].";border:none;text-align:center;margin:0 auto;padding:70px 0px;'>";
					   
					   $message .=  "<tr><td align='center' valign='top'>";
					   
					   $message .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_header' style='background-color: ".$emailvar['bg_color'].";color: #f1f1f1;-webkit-border-top-left-radius:6px !important;-webkit-border-top-right-radius:6px !important;border-top-left-radius:6px !important;border-top-right-radius:6px !important;border-bottom: 0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle;'>
									   <tr>
										   <td>
											   <h1 style='color: #000000;margin:0;padding: 28px 24px;display:block;font-family:Arial;font-size: 30px;font-weight:bold;text-align:center;line-height: 150%;' id='logo'>
											   <a style='color: #000000;text-decoration: none;'' href='https://theme-dutch.com/presents/profiler' title=".$emailvar['from_name']."'>$imagesrc</a>
											   </h1>
										   </td>
									   </tr>
								   </table>";
					   
					   $message .= "</td></tr>";
					   
					   $message .= "<tr>
									   <td align='center' valign='top'>
										   <table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_body'>
											   <tr>
												   <td valign='top' style='background-color: ".$emailvar['body_bg_color'].";' id='mailtpl_body_bg'>
													   <table border='0' cellpadding='20' cellspacing='0' width='100%'>
														   <tr>
															   <td valign='top'>
																   <div style='color:".$emailvar['body_txt_color'].";font-family:Arial;font-size: 14px;line-height:150%;text-align:left;' id='mailtpl_body'>
																	   <p>$resultmsg2</p>
																   </div>
															   </td>
														   </tr>
													   </table>
												   </td>
											   </tr>
										   </table>
									   </td>
								   </tr>
								   ";
					   
					   $message .= "<tr>
									   <td align='center' valign='top'>
										   <table border='0' cellpadding='10' cellspacing='0' width='100%' id='template_footer' style='border-top:1px solid #E2E2E2;background: #ffffff;-webkit-border-radius:0px 0px 6px 6px;-o-border-radius:0px 0px 6px 6px;-moz-border-radius:0px 0px 6px 6px;border-radius:0px 0px 6px 6px;'>
												   <tr>
													   <td valign='top'>
														   <table border='0' cellpadding='10' cellspacing='0' width='100%'>
															   <tr>
																   <td colspan='2' valign='middle' id='credit' style='border:0;color:#000000;font-family: Arial;font-size: 10px;line-height:125%;text-align:left;'>                                
																	   ".$emailvar['footer_text']."
																	   <p>THIS EMAIL IS FROM CRON.PHP 2</p>
																   </td>
															   </tr>
														   </table>
													   </td>
												   </tr>
										   </table>
									   </td>
								   </tr>
								   </table>
								   </div></body></html>"; 
					
					   /*new code for email template end*/     
					   $headers = "MIME-Version: 1.0" . "\r\n";
					   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";        
						$headers .= 'From: '.$emailvar['from_name'].'<'.$emailvar['from_address'].'>' . "\r\n";
					  // $headers .= 'Bcc: '.$emailvar['from_address'].'' . "\r\n";
					   if($row->flag2 == 0){   
						   mail($to,$subject,$message,$headers);
						   $wpdb->get_results('UPDATE '.$prefix.'um_switcher SET flag2 = 1 WHERE product_id = '.$row->product_id.' AND user_id = '.$row->user_id);
						   
						   //echo "Mail For Reminder2 Send Successfully for user" . $row->user_email;
						   echo "Mail For Reminder2 Send Successfully<br />";
					   } 
					   if($row->flag2 == 1){                  
						   echo "Mail was already send for Reminder2<br />";
						   //echo "Mail was already send for Reminder2 with user" . $row->user_email;    
					   }             
				   }        
			   }
			   $final_day = $diff->format("%R%a");
			   $final_hour = $diff->format("%R%h");
			   $final_mins = $diff->format("%R%i");
	   
			if($final_day <= 0 && $final_hour <= 0 && $final_mins <= 0)
			{
				//new updates
				um_fetch_user($row->user_id); 
				wp_update_user( array( 'ID' => $row->user_id, 'role' =>$row->selectafter ) );
				//end
			
				$updaterole = $wpdb->get_results('UPDATE `'.$prefix.'usermeta` SET meta_value = "'.$row->selectafter.'" WHERE user_id = "'.$row->user_id.'" AND meta_key = "role"');
				$updatestatus = $wpdb->get_results('UPDATE `'.$prefix.'um_switcher` SET user_status = "'.$row->selectafter.'" WHERE user_id = "'.$row->user_id.'" AND product_id = "'.$row->product_id.'"');
			} 
			else 
	   		{
				//new updates
				$product_id = $row->product_id;
				$orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
					'numberposts' => -1, // -1 for all orders
					'meta_key'    => '_customer_user',
					'meta_value'  => $row->user_id,
					'post_type'   => 'shop_order',
					'post_status' => 'processing'
				) ) );
		
				$status = array();
				$status['value']="";
				foreach ($orders as $value) {
					$status['value'] =  $value->post_status;
				}
				$order_status = $status['value']; 		
				
				if($order_status == 'wc-pending' || $order_status == 'wc-on-hold' || $order_status == 'wc-processing' || $order_status == 'wc-failed' || $order_status == 'wc-refunded' || $order_status == 'wc-cancelled')
				{
					um_fetch_user( $row->user_id );
					wp_update_user( array( 'ID' => $row->user_id, 'role' =>$row->selectafter ) );
				}
				else
				{
					um_fetch_user($row->user_id); 
					wp_update_user( array( 'ID' => $row->user_id, 'role' =>$row->selectbefore ) );
					
					$updaterole = $wpdb->get_results('UPDATE `'.$prefix.'usermeta` SET meta_value = "'.$row->selectbefore.'" WHERE user_id = "'.$row->user_id.'" AND meta_key = "role"');
					$updatestatus = $wpdb->get_results('UPDATE `'.$prefix.'um_switcher` SET user_status = "'.$row->selectbefore.'" WHERE user_id = "'.$row->user_id.'" AND product_id = "'.$row->product_id.'"');
				}
				//end
			}
	   }
	}


	 
	/**
	 * Support languages
	 *
	 * @since 1.0.0
	 * @return null
	 */
	public function umwotd_support_multilanguages() {
		load_plugin_textdomain('twodayssss', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}
	/**
	 * Plugin constant define
	 *
	 * @since 1.0.0
	 * @return null
	 */
	public function um_switcher_define_constants(){
		define( 'UMWOTD_VERSION', $this->plugin_data['Version'] );// plugin version
		define( 'UMWOTD_FILE', __FILE__ );	// plugin's main file path
		define( 'UMWOTD_DIR', dirname( plugin_basename( UMWOTD_FILE ) ) );// plugin's directory
		define( 'UMWOTD_PATH',untrailingslashit( plugin_dir_path( UMWOTD_FILE ) ) );// plugin's directory path
		define( 'UMWOTD_URL',untrailingslashit( plugin_dir_url( UMWOTD_FILE ) ) );// plugin's directory URL
		define( 'UMWOTD_INC_DIR','includes' );	// includes directory
		define( 'UMWOTD_ASSETS_DIR','assets' );		// assets directory
		define( 'UMWOTD_LANG_DIR','languages' );	// languages directory
		define( 'UMWOTD_ROOT_URL',untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'UMWOTD_PACKAGE_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
	}


	public function umwotd_set_includes(){

		$this->includes = apply_filters('um_switcher' , array(
			'admin' => array(
				UMWOTD_INC_DIR . '/admin/class-um-switcher-meta-boxes.php',
			),
			'frontends' => array(
				UMWOTD_INC_DIR . '/class-umwotd-product-um_switcher.php'
				
			)
		));

	}
	public function umwotd_load_includes() {

		$includes = $this->includes;

		foreach ( $includes as $condition => $files ) {
			$do_includes = false;
			switch( $condition ) {
				case 'admin':
					if ( is_admin() ) {
						$do_includes = true;
					}
					break;
				case 'frontend':
					if ( ! is_admin() ) {
						$do_includes = true;
					}
					break;
				default:
					$do_includes = true;
					break;
			}

			if ( $do_includes ) {
				foreach ( $files as $file ) {
					require_once trailingslashit( UMWOTD_PATH ) . $file;
				}
			}
		}
	}

	public function admin_styles_and_scripts() {
		global $post, $woocommerce, $wp_scripts;
		
		wp_register_style('subs-datepicker', UMWOTD_ROOT_URL. '/assets/css/subs-datepicker.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('subs-datepicker');
			
		wp_register_style('umsub_style', UMWOTD_ROOT_URL. '/assets/css/umsub_style.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('umsub_style');
		wp_register_script('umsub_js', UMWOTD_ROOT_URL. '/assets/js/umsub_js.js',array('jquery'), '', true);
		wp_enqueue_script('umsub_js'); 
        wp_register_script('datepicker_js', UMWOTD_ROOT_URL. '/assets/js/datepicker.js',array('jquery'), '', true);
		wp_enqueue_script('datepicker_js'); 
		
		wp_register_script('custom', UMWOTD_ROOT_URL. '/assets/js/custom.js',array('jquery'), '', true);
		wp_enqueue_script('custom'); 
		
		wp_register_script( 'ums_calendar4_js', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js', array( 'jquery' ) );
		wp_enqueue_script( 'ums_calendar4_js' );          
			
		wp_register_script( 'ums_calendar5_js', '//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js', array( 'jquery' ) );
		wp_enqueue_script( 'ums_calendar5_js' );                           		
			
	}

	public function um_switcher_custom_js() {?>
		
	<?php
	}	
	
	public function umwotd_add_to_cart(){
		wc_get_template( 'single-product/add-to-cart/um_switcher.php',$args = array(), $template_path = '', UMWOTD_PACKAGE_TEMPLATE_PATH);
	}
	
	public function insert_into_database($order_id){
		
	    global $wpdb,$post,$woocommerce;
	     $prefix = $wpdb->prefix;
		$order = new WC_Order( $order_id );	
		$order_date = $order->get_date_created();
		date_default_timezone_set('Europe/London');
		$current_time = date("Y-m-d H:i:s");
		$order_id = $order->get_id();
		$items = $order->get_items();		
		$product_id=array();
		
		foreach ( $items as $item ) { 
		   	$product_id['id'] = $item['product_id'];  
		}    

		$UmWoTD_subscription_type = esc_html(get_post_meta($product_id['id'], 'UmWoTD_subscription_type',true)); //new
		if($UmWoTD_subscription_type == 0 || $UmWoTD_subscription_type == "") $UmWoTD_subscription_type = 1;
		
		$subscription_type_start_date = esc_html(get_post_meta($product_id['id'], 'subscription_type_start_date',true)); //new
		$subscription_type_end_date = esc_html(get_post_meta($product_id['id'], 'subscription_type_end_date',true)); //new
		
		$product_type =  esc_html(get_post_meta($product_id['id'], 'product_type',true));
		$selectbefore =  esc_html( get_post_meta($product_id['id'], 'selectbefore',true) );
		$selectafter =  esc_html( get_post_meta($product_id['id'], 'selectafter',true) );
		$UmWoTD_days =  esc_html( get_post_meta($product_id['id'], 'UmWoTD_days',true) );
		$UmWoTD_hours =  esc_html( get_post_meta($product_id['id'], 'UmWoTD_hours',true) );
		$UmWoTD_mins =  esc_html( get_post_meta($product_id['id'], 'UmWoTD_mins',true) );
		$rem1 = esc_html( get_post_meta($product_id['id'], 'rem1',true) );
		$rem2 = esc_html( get_post_meta($product_id['id'], 'rem2',true) );
		$redirect_after_checkout =  esc_html( get_post_meta($product_id['id'], 'redirect_after_checkout',true) );
		$reminder_day1 =  esc_html( get_post_meta($product_id['id'], 'reminder_day1',true) );
		$reminder_day2 =  esc_html( get_post_meta($product_id['id'], 'reminder_day2',true) );
		$reminder_min1 =  esc_html( get_post_meta($product_id['id'], 'reminder_min1',true) );
		$reminder_min2 =  esc_html( get_post_meta($product_id['id'], 'reminder_min2',true) );
		$reminder_hours1 =  esc_html( get_post_meta($product_id['id'], 'reminder_hours1',true) );
		$reminder_hours2 =  esc_html( get_post_meta($product_id['id'], 'reminder_hours2',true) );
		$reminder_subject1 =  esc_html( get_post_meta($product_id['id'], 'reminder_subject1',true) );
		$reminder_subject2 =  esc_html( get_post_meta($product_id['id'], 'reminder_subject2',true) );
		$reminder_msg1 =  esc_html( get_post_meta($product_id['id'], 'reminder_msg1',true) );
		$reminder_msg2 =  esc_html( get_post_meta($product_id['id'], 'reminder_msg2',true) );
		$user_id = get_current_user_id();

		$result = $wpdb->get_results( "SELECT * FROM ".$prefix."um_switcher WHERE user_id = $user_id");
		$loop = array();
		$loop['user_id']="";	
		$loop['expire_time'] = "";
		$loop['days'] = "";
		$loop['hours'] = "";
		$loop['mins'] = "";
		$loop['product_id']="";
		$loop['user_status']="";
		$loop['selectafter']="";
		$loop['selectbefore']="";
		foreach($result as $row){ 	
			$loop['user_id']=$row->user_id;	
			$loop['expire_time'] = $row->expire_time;
			$loop['current_time'] = $row->current_time;
			$loop['subscription_type_start_date'] = $row->subscription_type_start_date; //new
			$loop['subscription_type_end_date'] = $row->subscription_type_end_date; //new
			$loop['days'] = $row->days;
			$loop['hours'] = $row->hours;
			$loop['mins'] = $row->mins;
			$loop['product_id']=$row->product_id;
			$loop['user_status']=$row->user_status;
			$loop['selectafter']=$row->selectafter;
			$loop['selectbefore']=$row->selectbefore;	
		}

		if(isset($UmWoTD_days)){
		@	$days = $loop['days']+$UmWoTD_days;
		}else{
			$days=0;
		}
		if(isset($UmWoTD_hours)){
		@	$hours = $loop['hours']+$UmWoTD_hours;
		}else{
			$hours=0;
		}
		if(isset($UmWoTD_mins)){
		@	$mins = $loop['mins']+$UmWoTD_mins;	
		}else{
			$mins=0;
		}		

		date_default_timezone_set('Europe/London');			
		$exp_date = date('Y-m-d H:i:s',date(strtotime("+".$days ."days +".$hours." hours +".$mins." minutes"/*, strtotime($order_date)*/)));
		
		if( ($loop['user_id'] != $user_id) && ($product_type == 'um_switcher') ){
			$user_id = get_current_user_id();
			$table_woo = $wpdb->prefix.'um_switcher';
			$sitename = um_get_option('mail_from');
			$wpdb->insert($table_woo,
				  array('user_id' => get_current_user_id(),
				  		'product_id'=>$product_id['id'],
				  		'order_id'=>$order_id,
						'current_time'=>$current_time,
						'UmWoTD_subscription_type' => $UmWoTD_subscription_type, //new
						'subscription_type_start_date' => $subscription_type_start_date, //new
						'subscription_type_end_date' => $subscription_type_end_date, //new
						'days' => $UmWoTD_days,
						'hours' => $UmWoTD_hours,
						'mins' => $UmWoTD_mins,
						'rem1'=>$rem1,
						'rem2'=>$rem2,
						//'redirect_after_checkout' => $redirect_after_checkout,
						'day1' => $reminder_day1,
						'day2' => $reminder_day2,
						'hours1' => $reminder_hours1,
						'hours2' => $reminder_hours2,
						'min1' => $reminder_min1,
						'min2' => $reminder_min2,
						'rem_sub1' => $reminder_subject1,
						'rem_sub2' => $reminder_subject2,
						'rem_msg1'=>$reminder_msg1,
						'rem_msg2'=>$reminder_msg2,
						'selectbefore'=>$selectbefore,
						'selectafter'=>$selectafter,
						'expire_time' => $exp_date,
						'flag1'=>'0',
						'flag2'=>'0',
						'user_status'=>$selectbefore,
						'site_name'=>$sitename),

				  array('%d','%d','%d','%s','%s','%s','%s','%d','%d','%d','%s','%s','%d','%d','%d','%d','%d','%d','%s','%s','%s','%s','%s','%s','%s','%d','%d','%s','%s'));		

			############################################

			um_fetch_user( get_current_user_id() );
			if($order->get_payment_method() == "bacs" || $order->get_payment_method() == "cod" || $order->get_payment_method() == "cheque") 
			{
					$order->update_status('on-hold');
					$user = new WP_User($user_id);
					$user->set_role(get_option('default_role'));
					@ wp_update_user( array( 'ID' => $user_id, 'role' => $default_role ) );


			}
			else 

			{
				//$ultimatemember->user->set_role($selectbefore);
				$order->update_status('completed');
				wp_update_user( array( 'ID' => $user_id, 'role' => $selectbefore ) );
	 			
				
			}
			############################################

		}
		if(($loop['user_id'] == $user_id) && ($product_type == 'um_switcher' || $product_type == 'um_switcher2')) {
		$table_woo = $wpdb->prefix.'um_switcher';	
			$UmWoTD_days =  esc_html( get_post_meta($product_id['id'], 'UmWoTD_days',true) );
			$UmWoTD_hours =  esc_html( get_post_meta($product_id['id'], 'UmWoTD_hours',true) );
			$UmWoTD_mins =  esc_html( get_post_meta($product_id['id'], 'UmWoTD_mins',true) );		
			 
			if($UmWoTD_days == ""){
				$UmWoTD_days=0;
			}
			if($UmWoTD_hours == ""){
				$UmWoTD_hours=0;
			}
			if($UmWoTD_mins == ""){
				$UmWoTD_mins=0;
			}			

		
		date_default_timezone_set('Europe/London');

		if($loop['user_status'] == $loop['selectafter']){
			$sitename = um_get_option('mail_from');
			$daysaf = $UmWoTD_days;
			$hoursaf = $UmWoTD_hours;		
			$minsaf = $UmWoTD_mins;	
			
			date_default_timezone_set('Europe/London');
			$currentstart = date('Y-m-d H:i:s')/*$loop['expire_time']*/;
			$exp_dateaf = date('Y-m-d H:i:s',strtotime('+'.$daysaf.' days +'.$hoursaf.' hours +'.$minsaf.' minutes',strtotime($currentstart)));
			
			$wpdb->update($table_woo, 
			array(
				'product_id'=>$product_id['id'],
				'order_id'=>$order_id,	
				'current_time'=>$currentstart,	
				'UmWoTD_subscription_type' => $UmWoTD_subscription_type, //new
				'subscription_type_start_date' => $subscription_type_start_date, //new
				'subscription_type_end_date' => $subscription_type_end_date, //new				
				'days'=>$daysaf,
				'hours'=>$hoursaf,
				'mins'=>$minsaf,
				'rem1'=>$rem1,
				'rem2'=>$rem2,
				//'redirect_after_checkout'=>$redirect_after_checkout,
				'day1'=>$reminder_day1,
				'day2'=>$reminder_day2,
				'hours1'=>$reminder_hours1,
				'hours2'=>$reminder_hours2,
				'min1'=>$reminder_min1,				
				'min2'=>$reminder_min2,
				'rem_sub1'=>$reminder_subject1,
				'rem_sub2'=>$reminder_subject2,
				'rem_msg1'=>$reminder_msg1,
				'rem_msg2'=>$reminder_msg2,
				'selectbefore'=>$selectbefore,
				'selectafter'=>$selectafter,
				'expire_time' => $exp_dateaf,	
				'flag1' => '0',
				'flag2' => '0',
				'user_status'=>$selectbefore,
				'site_name'=>$sitename), 
			array( 'product_id' =>$loop['product_id'],'user_id'=>$user_id), 
			array( 				
				'%d','%d','%s','%s','%s','%s','%d','%d','%d','%s','%s','%d','%d','%d','%d','%d','%d','%s','%s','%s','%s','%s','%s','%s','%d','%d','%s','%s'
			), 
			array( '%d','%d' ) 
		);
			
		}

		if($loop['user_status'] == $loop['selectbefore']){
			$sitename = um_get_option('mail_from');
			$daysbe = $loop['days']+$UmWoTD_days;
			$hoursbe = $loop['hours']+$UmWoTD_hours;		
			$minsbe = $loop['mins']+$UmWoTD_mins;
			
			date_default_timezone_set('Europe/London');						
			$currentstart = $loop['expire_time']/*date('Y-m-d H:i:s')*/;			
			$datetime1 = date("Y-m-d H:i:s");
			$datetime2 = date('Y-m-d H:i:s', strtotime($currentstart));	
			
			if($datetime1 > $datetime2){
				$loop['current_time'];
				$currentstart = $datetime1;
				$currentupdate = $loop['current_time'];
				$exp_datebe = date('Y-m-d H:i:s',strtotime('+'.$daysbe.' days +'.$hoursbe.' hours +'.$minsbe.' minutes',strtotime($currentstart)));	
			}
			
			if($datetime1 < $datetime2){
				
				$currentupdate = $loop['current_time'];
				$currentstart = $loop['expire_time']/*date('Y-m-d H:i:s')*/;
				$exp_datebe = date('Y-m-d H:i:s',strtotime('+'.$UmWoTD_days.' days +'.$UmWoTD_hours.' hours +'.$UmWoTD_mins.' minutes',strtotime($currentstart)));	
			}			
			
			$wpdb->update($table_woo, 
			array(
				'product_id'=>$product_id['id'],	
				'order_id'=>$order_id,	
				'current_time'=>$currentupdate,	
				'UmWoTD_subscription_type' => $UmWoTD_subscription_type, //new
				'subscription_type_start_date' => $subscription_type_start_date, //new
				'subscription_type_end_date' => $subscription_type_end_date, //new					
				'days'=>$daysbe,
				'hours'=>$hoursbe,
				'mins'=>$minsbe,
				'rem1'=>$rem1,
				'rem2'=>$rem2,
				//'redirect_after_checkout'=>$redirect_after_checkout,
				'day1'=>$reminder_day1,
				'day2'=>$reminder_day2,
				'hours1'=>$reminder_hours1,
				'hours2'=>$reminder_hours2,
				'min1'=>$reminder_min1,				
				'min2'=>$reminder_min2,
				'rem_sub1'=>$reminder_subject1,
				'rem_sub2'=>$reminder_subject2,
				'rem_msg1'=>$reminder_msg1,
				'rem_msg2'=>$reminder_msg2,
				'selectbefore'=>$selectbefore,
				'selectafter'=>$selectafter,
				'expire_time' => $exp_datebe,	
				'flag1' => '0',
				'flag2' => '0',
				'user_status'=>$selectbefore,
				'site_name'=>$sitename), 
			array( 'product_id' =>$loop['product_id'],'user_id'=>$user_id), 
			array( 				
				'%d','%d','%s','%s','%s','%s','%d','%d','%d','%s','%s','%d','%d','%d','%d','%d','%d','%s','%s','%s','%s','%s','%s','%s','%d','%d','%s','%s'
			), 
			array( '%d','%d' ) 
		);
		}
		
		
			um_fetch_user( get_current_user_id() );
			if($order->get_payment_method() == "bacs" || $order->get_payment_method() == "cod" || $order->get_payment_method() == "cheque") 
			{
					$order->update_status('on-hold');
					$user = new WP_User($user_id);
					$user->set_role(get_option('default_role'));
					@ wp_update_user( array( 'ID' => $user_id, 'role' => $default_role ) );

			}
			else 
			{
				$order->update_status('completed');
				wp_update_user( array( 'ID' => $user_id, 'role' => $selectbefore ) );
			}


		}
		  	
	}	
}

function my_media_lib_uploader_enqueue() {
	
    wp_enqueue_media(); 
 }

  add_action('admin_enqueue_scripts', 'my_media_lib_uploader_enqueue');

	add_action('admin_menu', 'sep_menuexample_create_menu' );
	function sep_menuexample_create_menu() {	
			add_menu_page( 'UM-Switcher', 'UM-Switcher', 'administrator', 'UM-Switcher','demo','dashicons-groups', '42.78578' );	
		add_submenu_page( 'UM-Switcher', 'UM-Switcher Overview', 'Subscriptions','administrator', 'umsubscriptions_overview', 'umsubscriptions_overview_page');
	    add_submenu_page( 'UM-Switcher', 'UM-Switcher Settings', 'Settings','administrator', 'um_switcher_settings', 'umswitcher_settings_page');
		add_submenu_page( 'UM-Switcher', 'UM-Switcher Faq', 'FAQ','administrator', 'um_switcher_faq', 'umswitcher_faq_page');
		add_submenu_page( 'UM-Switcher', 'UM-Switcher Email', 'Email','administrator', 'um_switcher_email', 'umswitcher_email_page');
		add_submenu_page( 'UM-Switcher', 'UM-Switcher Support', 'Support','administrator', 'um_switcher_support', 'umswitcher_support_page');
		
	}
	
	function demo(){
		
			wp_register_style('carousel_style', UMWOTD_ROOT_URL. '/assets/css/carousel.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('carousel_style');
			wp_register_style('bootstrap.min_style', UMWOTD_ROOT_URL. '/assets/css/bootstrap.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('bootstrap.min_style');
			wp_register_style('font-awesome.min_style', UMWOTD_ROOT_URL. '/assets/css/font-awesome.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('font-awesome.min_style');
			wp_register_script('jquery.min', UMWOTD_ROOT_URL. '/assets/js/jquery.min.js',array('jquery'), '', true);
            wp_enqueue_script('jquery.min'); 
			wp_register_script('canvasjs', UMWOTD_ROOT_URL. '/assets/js/canvasjs.min.js',array('jquery'), '', true);
            wp_enqueue_script('canvasjs'); 

		require_once ABSPATH . 'wp-content/plugins/um-switcher/dashboard/dashboard.php';	
	}

	function umswitcher_email_page() {	
		
		  	wp_register_style('carousel_style', UMWOTD_ROOT_URL. '/assets/css/carousel.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('carousel_style');
			wp_register_style('bootstrap.min_style', UMWOTD_ROOT_URL. '/assets/css/bootstrap.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('bootstrap.min_style');
			wp_register_style('font-awesome.min_style', UMWOTD_ROOT_URL. '/assets/css/font-awesome.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('font-awesome.min_style');
			wp_register_script('jquery.min', UMWOTD_ROOT_URL. '/assets/js/jquery.min.js',array('jquery'), '', true);
            wp_enqueue_script('jquery.min'); 
			
		require_once ABSPATH . 'wp-content/plugins/um-switcher/email.php'; 
	}

	function umswitcher_settings_page() {

			wp_register_style('carousel_style', UMWOTD_ROOT_URL. '/assets/css/carousel.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('carousel_style');
			wp_register_style('bootstrap.min_style', UMWOTD_ROOT_URL. '/assets/css/bootstrap.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('bootstrap.min_style');
			wp_register_style('font-awesome.min_style', UMWOTD_ROOT_URL. '/assets/css/font-awesome.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('font-awesome.min_style');
			wp_register_script('jquery.min', UMWOTD_ROOT_URL. '/assets/js/jquery.min.js',array('jquery'), '', true);
            wp_enqueue_script('jquery.min'); 
			
		require_once ABSPATH . 'wp-content/plugins/um-switcher/dashboard/cronjob.php'; 

	}

	function umswitcher_faq_page() {

			wp_register_style('carousel_style', UMWOTD_ROOT_URL. '/assets/css/carousel.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('carousel_style');
			wp_register_style('bootstrap.min_style', UMWOTD_ROOT_URL. '/assets/css/bootstrap.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('bootstrap.min_style');
			wp_register_style('font-awesome.min_style', UMWOTD_ROOT_URL. '/assets/css/font-awesome.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('font-awesome.min_style');
			wp_register_script('jquery.min', UMWOTD_ROOT_URL. '/assets/js/jquery.min.js',array('jquery'), '', true);
            wp_enqueue_script('jquery.min'); 

		require_once ABSPATH . 'wp-content/plugins/um-switcher/dashboard/faq.php'; 

	}

	function umswitcher_support_page() {

			wp_register_style('carousel_style', UMWOTD_ROOT_URL. '/assets/css/carousel.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('carousel_style');
			wp_register_style('bootstrap.min_style', UMWOTD_ROOT_URL. '/assets/css/bootstrap.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('bootstrap.min_style');
			wp_register_style('font-awesome.min_style', UMWOTD_ROOT_URL. '/assets/css/font-awesome.min.css', array(), $ver = false, $media = 'all');
            wp_enqueue_style('font-awesome.min_style');
			wp_register_script('jquery.min', UMWOTD_ROOT_URL. '/assets/js/jquery.min.js',array('jquery'), '', true);
            wp_enqueue_script('jquery.min'); 

		require_once ABSPATH . 'wp-content/plugins/um-switcher/dashboard/support.php'; 
	}
	
	new Um_Switcher();


function wpse_80236_Colorpicker(){
	wp_enqueue_style( 'wp-color-picker');
	//
	wp_enqueue_script( 'wp-color-picker');
}
add_action('admin_enqueue_scripts', 'wpse_80236_Colorpicker');

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_Table_Example_List_Table extends WP_List_Table
{
	
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'person',
            'plural' => 'persons',
        ));
    }
   
    function column_image($item){    	
    	return get_avatar( $item['user_id'],40);
    }
   
    function column_subscription($item){
		//fix old database
		//global $wpdb;
    	//$prefix = $wpdb->prefix;
		//$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET selectbefore='um_bronze-member', user_status='um_bronze-member' WHERE user_status='subscriber'");
		$selectafter = $item['selectafter'];
		$selectbefore = $item['selectbefore'];
		if($item['wc_order_status'] == "ORDER REMOVED") 
		{
			$is_deleted = '<br /><span class="label label-danger">ORDER REMOVED</span>';
			if($item['user_status'] == $selectbefore ){
				return '<p style="color:#20C543">'.$item['user_status'].$is_deleted.'</p>';
			}else{
				return '<p style="color:#FF0000">'.$item['user_status'].$is_deleted.'</p>';
			}
		}
		else
		{
			
			$clr = "";
			if($item['user_status'] == $selectbefore ) $clr = '';
			else $clr = "disabled";
			
				$opt = "";
				foreach( UM()->roles()->get_roles() as $key => $value )
				{
					$opt .= '<option value="'.$key.'"';
					if($item['user_status'] == $key){ $opt .= 'selected'; } 
					$opt .= '>'.$value.'</option>';
				}
				return '
				<select '.$clr.' name="subscription_'.$item['id'].'" id="subscription_'.$item['id'].'" onchange="javascript: save_subscription__subscription_changes('.$item['id'].', '.$item['user_id'].'); ">
					'.$opt.'  
				</select>
				<div style="display:inline-block;" id="subscription_loader_'.$item['id'].'"></div>
				';
		}
    } 
	

    function column_UmWoTD_subscription_type($item){
		if(@$item['UmWoTD_subscription_type'] == 1)
			return "Based on Number of Days";
		else
			return "Based on Date End";
    }   

	
    function column_startdate($item)
	{
		$selectafter = $item['selectafter'];
		$selectbefore = $item['selectbefore'];
			
		if(@$item['UmWoTD_subscription_type'] == 1)
		{
			$timezone = get_option('timezone_string');
			$timelen=  strlen($timezone);
			if($timelen>0){		   		
				date_default_timezone_set($timezone);
			}else{
				_e("bbb  Set your Timezone",'twodayssss');
				return false;
			}				
			$date = new DateTime($item['current_time'], new DateTimeZone('Europe/London'));
			$date->setTimezone(new DateTimeZone($timezone));			
			
			$clr = "";
			if($item['user_status'] == $selectbefore ) $clr = 'color:#20C543; ';
			else $clr = "";
			
			return '
				<div class=\'input-group date\' id=\'start_datetimepicker_'.$item['id'].'\'>
					<input class="form-control" type="text" autocomplete="off" placeholder="" style="'.$clr.'width:120%;" name="start_date_'.$item['id'].'" id="start_date_'.$item['id'].'"  value="'.$date->format('m/d/Y g:i a').'" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
				<div class="manual-update">
					<button type="button" class="btn btn-default btn-sm button-update" onclick="javascript: save_start_date_changes('.$item['id'].'); ">
					 Save
					</button>
					<div style="display:inline-block;" id="start_loader_'.$item['id'].'"></div>
				</div>
				<script type="text/javascript">
                	$(\'#start_datetimepicker_'.$item['id'].'\').datetimepicker();							
				</script>
				';				
		}
		else
		{
			if($item['subscription_type_start_date'] == "0000-00-00")
				return '';
			else
			{
				$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);
				if($timelen>0){		   		
					date_default_timezone_set($timezone);
				}else{
					_e("bbb  Set your Timezone",'twodayssss');
					return false;
				}				
				$date = new DateTime($item['subscription_type_start_date'], new DateTimeZone('Europe/London'));
				$date->setTimezone(new DateTimeZone($timezone));			
				
				$clr = "";
				if($item['user_status'] == $selectbefore ) $clr = 'color:#20C543; ';
				else $clr = "";
	
				return '
					<div class=\'input-group date\' id=\'start_datetimepicker_'.$item['id'].'\'>
						<input class="form-control" type="text" autocomplete="off" placeholder="" style="'.$clr.'width:120%;" name="based_end_date__start_date_'.$item['id'].'" id="based_end_date__start_date_'.$item['id'].'"  value="'.$date->format('m/d/Y g:i a').'" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<div class="manual-update">
						<button type="button" class="btn btn-default btn-sm button-update" onclick="javascript: save_based_end_date__start_date_changes('.$item['id'].'); ">
						 Save
						</button>
						<div style="display:inline-block;" id="start_loader_'.$item['id'].'"></div>
					</div>
					<script type="text/javascript">
						$(\'#start_datetimepicker_'.$item['id'].'\').datetimepicker();							
					</script>
					';				
			}
		}
    } 
    function column_enddate($item)
	{
		$selectafter = $item['selectafter'];
		$selectbefore = $item['selectbefore'];

		if(@$item['UmWoTD_subscription_type'] == 1)
		{
			$timezone = get_option('timezone_string');
			$timelen=  strlen($timezone);
			if($timelen>0){		   		
				date_default_timezone_set($timezone);
			}else{
				_e("Set your Timezone",'twodayssss');
				return false;
			}					
			$date = new DateTime($item['expire_time'], new DateTimeZone('Europe/London'));
			$date->setTimezone(new DateTimeZone($timezone));			
			
			if($item['user_status'] == $selectbefore ) $clr = "";
			else $clr = "color:#FF0000; ";
			
			return '
				<div class=\'input-group date\' id=\'datetimepicker_'.$item['id'].'\'>
					<input class="form-control" type="text" autocomplete="off" placeholder="" style="'.$clr.'width:120%;" name="end_date_'.$item['id'].'" id="end_date_'.$item['id'].'"  value="'.$date->format('m/d/Y g:i a').'" />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
				<div class="manual-update">
					<button type="button" class="btn btn-default btn-sm button-update" onclick="javascript: save_end_date_changes('.$item['id'].'); ">
					 Save
					</button>
					<div style="display:inline-block;" id="loader_'.$item['id'].'"></div>
				</div>
				<script type="text/javascript">
                	$(\'#datetimepicker_'.$item['id'].'\').datetimepicker();							
				</script>
				';				
		}
		else
		{
			if($item['subscription_type_end_date'] == "0000-00-00")
				return '';
			else
			{
				$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);
				if($timelen>0){		   		
					date_default_timezone_set($timezone);
				}else{
					_e("Set your Timezone",'twodayssss');
					return false;
				}					
				$date = new DateTime($item['subscription_type_end_date'], new DateTimeZone('Europe/London'));
				$date->setTimezone(new DateTimeZone($timezone));					
			
				if($item['user_status'] == $selectbefore ) $clr = "";
				else $clr = "color:#FF0000; ";			
				
				return '
					<div class=\'input-group date\' id=\'datetimepicker_'.$item['id'].'\'>
						<input class="form-control" type="text" autocomplete="off" placeholder="" style="'.$clr.'width:120%;" name="based_end_date__end_date_'.$item['id'].'" id="based_end_date__end_date_'.$item['id'].'" value="'.$date->format('m/d/Y g:i a').'" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<div class="manual-update">
						<button type="button" class="btn btn-default btn-sm button-update" onclick="javascript: save_based_end_date__end_date_changes('.$item['id'].'); ">
						 Save
						</button>
						<div style="display:inline-block;" id="loader_'.$item['id'].'"></div>
					</div>
					<script type="text/javascript">
						$(\'#datetimepicker_'.$item['id'].'\').datetimepicker();							
					</script>
					'; 
			}	
		}		
    } 
	
	
    function column_rem1($item){
    	$day1 = $item['day1'];		
		$hours1 = $item['hours1'];		
		$min1 = $item['min1'];		
    	$reminderdate1 =  date('Y-m-d H:i:s',strtotime('-'.$day1.' days -'.$hours1.' hours -'.$min1.' minutes',strtotime($item['expire_time'])));
		if($item['rem1'] == "dontsend") { 
		    return "<p>No Email Reminder</p>";  			
		}
		else
		{		
			if($item['flag1'] == "1") { 
				$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);
				if($timelen>0){		   		
					date_default_timezone_set($timezone);
	
				}else{
					_e("Set your Timezone",'twodayssss');
					return false;
				}					
				$date = new DateTime($reminderdate1, new DateTimeZone('Europe/London'));
				$date->setTimezone(new DateTimeZone($timezone));  
				$clr = "color:#20C543; ";	
				$returned_data = $date->format('m/d/Y g:i a');
				$returned_stat_color = "<span id='green'>&#9679;</span>";
			}else{
				$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);
				if($timelen>0){		   		
					date_default_timezone_set($timezone);
	
				}else{
					_e("Set your Timezone",'twodayssss');
					return false;
				}					
				$date = new DateTime($reminderdate1, new DateTimeZone('Europe/London'));
				$date->setTimezone(new DateTimeZone($timezone));
				$clr = "color:#FF0000; ";
				$returned_data = $date->format('m/d/Y g:i a');
				$returned_stat_color = "<span id='red'>&#9679;</span>";
			}


				return '
					<div class=\'input-group date\' id=\'reminder1_datetimepicker_'.$item['id'].'\'>
						<input class="form-control" type="text" autocomplete="off" placeholder="" style="'.$clr.'width:120%;" name="reminder1_changes_'.$item['id'].'" id="reminder1_changes_'.$item['id'].'" value="'.$returned_data.'" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-envelope"></span>
						</span>
					</div>
					<div class="manual-update">
						<button type="button" class="btn btn-default btn-sm button-update" onclick="javascript: save_reminder1__reminder_changes('.$item['id'].'); ">
						 Save
						</button>
						<div style="display:inline-block;" id="reminder1_changes_loader_'.$item['id'].'"></div>
						<!--<div style="display:inline-block;">'.$returned_stat_color.'</div>-->
					</div>
					<script type="text/javascript">
						$(\'#reminder1_datetimepicker_'.$item['id'].'\').datetimepicker();							
					</script>
					';



		}
    } 
    function column_rem2($item){
    	$day2 = $item['day2'];		
		$hours2 = $item['hours2'];		
		$min2 = $item['min2'];	
		
   		$reminderdate2 = date('Y-m-d H:i:s',strtotime('-'.$day2.' days -'.$hours2.' hours -'.$min2.' minutes',strtotime($item['expire_time'])));
		
		if($item['rem2'] == "dontsend") { 
		    return "<p>No Email Reminder</p>";  			
		}
		else
		{
			if($item['flag2'] == "1") { 
				$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);
				if($timelen>0){		   		
					date_default_timezone_set($timezone);
				}else{
					_e("Set your Timezone",'twodayssss');
					return false;
				}					
				$date = new DateTime($reminderdate2, new DateTimeZone('Europe/London'));
				$date->setTimezone(new DateTimeZone($timezone));
				
				
				
				//if(@$item['UmWoTD_subscription_type'] == 2)
				//{
				//	$clr = "color:#20C543; ";	
				//	$returned_data = $reminderdate2_end_date;
				//	$returned_stat_color = "<span id='green'>&#9679;</span>";					
				//}
				//else
				//{
					$clr = "color:#20C543; ";	
					$returned_data = $date->format('m/d/Y g:i a');
					$returned_stat_color = "<span id='green'>&#9679;</span>";						
				//}				
			}else{
				$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);

				if($timelen>0){		   		
					date_default_timezone_set($timezone);
	
				}else{
					_e("Set your Timezone",'twodayssss');
					return false;
				}					
				$date = new DateTime($reminderdate2, new DateTimeZone('Europe/London'));
				$date->setTimezone(new DateTimeZone($timezone)); 
				//if(@$item['UmWoTD_subscription_type'] == 2)
				//{
					//$clr = "color:#FF0000; ";	
					//$returned_data = $reminderdate2_end_date;
					//$returned_stat_color = "<span id='red'>&#9679;</span>";					
				//}
				//else
				//{
					$clr = "color:#FF0000; ";	
					$returned_data = $date->format('m/d/Y g:i a');
					$returned_stat_color = "<span id='red'>&#9679;</span>";	
				//}				
			}
			
				return '
					<div class=\'input-group date\' id=\'reminder2_datetimepicker_'.$item['id'].'\'>
						<input class="form-control" type="text" autocomplete="off" placeholder="" style="'.$clr.'width:120%;" name="reminder2_changes_'.$item['id'].'" id="reminder2_changes_'.$item['id'].'" value="'.$returned_data.'" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-envelope"></span>
						</span>
					</div>
					<div class="manual-update">
						<button type="button" class="btn btn-default btn-sm button-update" onclick="javascript: save_reminder2__reminder_changes('.$item['id'].'); ">
						 Save
						</button>
						<div style="display:inline-block;" id="reminder2_changes_loader_'.$item['id'].'"></div>
						<!--<div style="display:inline-block;">'.$returned_stat_color.'</div>-->
					</div>
					<script type="text/javascript">
						$(\'#reminder2_datetimepicker_'.$item['id'].'\').datetimepicker();							
					</script>
					';			
		}
    } 
	###
	function column_client($item){
        $currency = get_woocommerce_currency_symbol();
    	$price = get_post_meta( $item['product_id'], '_regular_price', true);
		$order_link ='post.php?post='.$item['order_id'].'&action=edit';
    	return $item['user_nicename'].'</br>'.'<a href="'.$order_link.'">#'.$item['order_id'].'</a>'.'&nbsp;&nbsp;&nbsp;'.$currency.$price;
    }   
	###
  /* function column_order($item){
    	$order_link ='post.php?post='.$item['order_id'].'&action=edit';
   	return "<p><a href='$order_link'>#".$item['order_id']."</a></p>";
    } 
    function column_total($item){
   	$currency = get_woocommerce_currency_symbol();
   	$price = get_post_meta( $item['product_id'], '_regular_price', true);
   	return $currency.$price;
    }   */
    
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
	
	###### Roy Historie ###############
   function column_historie($item)
    {
        return sprintf(
    '<div class="ums-hist dropdown">
    <button class="umswitcher_button btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" onclick="load_history_loader('.$item['id'].');">
    <span class="caret"></span></button>
    <div class="dropdown-menu">
	<div class="row email-equal-height">
    
	<div class="col-sm-6" id="load_history_container_'.$item['id'].'"></div>
	
    <div class="col-sm-6">
		<p class="hist">Notes</p>
      <div class="input-group">
    <textarea class="form-control custom-control" style="resize:none" rows="8" cols="100" id="um_switcher__history_notes_'.$item['id'].'">'.$item['notes'].'</textarea>
    <button type="button" class="btn btn-default btn-sm button-update" onclick="save_history_notes('.$item['id'].');">
					 Save
	</button>
	<div id="save_history_notes_loader_'.$item['id'].'"></div>
	</div> 
	
    </div>
    </div>
    </div>
	</div>
	',
            $item['id']
        );
    }
   	###### Roy Historie ###############
	
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'image' => __('Image', 'twodayssss'),
            'client' => __('Client', 'twodayssss'),
			'UmWoTD_subscription_type' => __('Product Type', 'twodayssss'),
            'subscription' => __('User role', 'twodayssss'),
            'startdate' => __('Start Date', 'twodayssss'),
            'enddate' => __('End Date', 'twodayssss'),
            'rem1' => __('Reminder 1', 'twodayssss'),
            'rem2' => __('Reminder 2', 'twodayssss'),
           // 'order' => __('Order', 'twodayssss'),
           // 'total'=>__('Total','twodayssss'),
			 'historie' => ''
        );
        return $columns;
    } 
	
   ###################### BULK ACTIONS #################################
    function get_bulk_actions()
    { 
		$actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }  
	###################### END BULK ACTIONS #################################
  	
    function prepare_items()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;   
        $base_prefix = $wpdb->base_prefix;    
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();      
        $this->_column_headers = array($columns, $hidden, $sortable);        
        $this->process_bulk_action();        
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0; 
		$sub_query = "";
		if($_REQUEST["show"] <> "")
		{
			if($_REQUEST["show"] == "a")
			{
				$sub_query = " AND ".$prefix."um_switcher.selectbefore = ".$prefix."um_switcher.user_status";
				$sub_query2 = " WHERE ".$prefix."um_switcher.selectbefore = ".$prefix."um_switcher.user_status";
			}
			if($_REQUEST["show"] == "na")
			{
				$sub_query = " AND ".$prefix."um_switcher.selectafter = ".$prefix."um_switcher.user_status";
				$sub_query2 = " WHERE ".$prefix."um_switcher.selectafter = ".$prefix."um_switcher.user_status";
			}
		}
        if(isset($_REQUEST['search'])){
        	$s = $_REQUEST['s'];
        	$total_items = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id  WHERE ".$base_prefix."users.user_login LIKE '%$s%'".$sub_query);
        	$per_page = 1;
        	
        	$this->items = $wpdb->get_results("SELECT * FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$base_prefix."users.user_login LIKE '%$s%'".$sub_query." ORDER BY ".$prefix."um_switcher.id DESC LIMIT $per_page OFFSET $paged",ARRAY_A);
        	$this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        )); 
        }else{
	    	$total_items = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
	    	if(isset($_REQUEST['um_page'])){
	    		update_option( 'um_page_option_all', $_REQUEST['um_per_page'] );
          		$um_per_page = $_REQUEST['um_per_page'];
          		$per_page = $um_per_page;
          	}else if(get_option('um_page_option_all')>0){ 
				$per_page = get_option('um_page_option_all');
          	}else{
          		$per_page = 10;
          	}
	    	$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id ".$sub_query2." ORDER BY ".$prefix."um_switcher.id DESC LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
	    	$this->set_pagination_args(array(
	            'total_items' => $total_items, // total items defined above
	            'per_page' => $per_page, // per page constant defined at top of method
	            'total_pages' => ceil($total_items / $per_page) // calculate pages count
	        ));
    	}
    	if( isset($_REQUEST['filter']) && $_REQUEST['filter'] == 'subscribed'){
    		$total_items = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectafter = ".$prefix."um_switcher.user_status");
	    	if(isset($_REQUEST['um_page'])){
	    		update_option( 'um_page_option_all', $_REQUEST['um_per_page'] );
          		$um_per_page = $_REQUEST['um_per_page'];
          		$per_page = $um_per_page;
          	}else if(get_option('um_page_option_all')>0){ 
				$per_page = get_option('um_page_option_all');
          	}else{
          		$per_page = 10;
          	}	    		
	    	$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectafter = ".$prefix."um_switcher.user_status ORDER BY ".$prefix."um_switcher.id DESC LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
	    	$this->set_pagination_args(array(
	            'total_items' => $total_items, // total items defined above
	            'per_page' => $per_page, // per page constant defined at top of method
	            'total_pages' => ceil($total_items / $per_page) // calculate pages count
	        ));
    		
    	}
        
    }
}


function umsubscriptions_overview_page()
{  
	wp_register_style('carousel_style', UMWOTD_ROOT_URL. '/assets/css/carousel.css', array(), $ver = false, $media = 'all');
	wp_enqueue_style('carousel_style');
	wp_register_style('bootstrap.min_style', UMWOTD_ROOT_URL. '/assets/css/bootstrap.min.css', array(), $ver = false, $media = 'all');
	wp_enqueue_style('bootstrap.min_style');
	wp_register_style('font-awesome.min_style', UMWOTD_ROOT_URL. '/assets/css/font-awesome.min.css', array(), $ver = false, $media = 'all');
	wp_enqueue_style('font-awesome.min_style');
	wp_register_script('jquery.min', UMWOTD_ROOT_URL. '/assets/js/jquery.min.js',array('jquery'), '', true);
	wp_enqueue_script('jquery.min'); 
			    	
	require_once ABSPATH . 'wp-content/plugins/um-switcher/refresh_cron_job.php';
	?>
	<script type='text/javascript'>
			jQuery( document ).ready( function() {	
				setTimeout(showpanel, 600000);
				function showpanel() {     
	  				//jQuery( "#publish" ).trigger( "click" );
	  				 window.location.reload();
	 			}		
	 	});
	</script>  
	<script type='text/javascript'>
	function toggleColor() {
  var myButtonClasses = document.getElementById("btn1").classList;
  if (myButtonClasses.contains("blue")) {
    $(' .blue ').addClass('ums-hide');
  }
  
  
if( $('.blue ').hasClass('ums-hide') === true ) 
{
 $('.ums-hide-content ').addClass('ums-ready-hide');
}

  
  
}
	</script>
	<?php
    global $post,$wpdb,$woocommerce;
    $prefix = $wpdb->prefix;   
    $base_prefix = $wpdb->base_prefix; 
    $_GET['s'] = "";
    $table = new Custom_Table_Example_List_Table();
    $table->prepare_items();

    $message = '';
	#################################### BULK Delete action ####################################
    if ('delete' === $table->current_action()) {

    	foreach ($_REQUEST['id'] as $value) {
    		$res = $wpdb->get_results("DELETE FROM ".$prefix."um_switcher WHERE id = $value");	
    	}
		wp_redirect(get_site_url()."/wp-admin/admin.php?page=umsubscriptions_overview");
    }
	#################################### END BULK Delete action ####################################
    ?>
	
<div class="main-content dashboard" style="margin-left: -20px;">
 <div class="navbar-wrapper">
   <div class="container-fluid">
       <nav class="navbar navbar-inverse navbar-static-top" style="margin-left: -20px;">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar3">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
          <img class="navbar-brand" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'um-switcher/dashboard/img/um_logo.png'; ?>">
      </div>
			<div id="navbar3" class="navbar-collapse collapse" style="margin-top: 7px;">
				 <ul class="nav navbar-nav navbar-right">
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=UM-Switcher"><span class="glyphicon glyphicon-signal" style="color: #000; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_settings"><span class="glyphicon glyphicon-calendar" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_faq"><span class="glyphicon glyphicon-question-sign" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_email"><span class="glyphicon glyphicon-envelope" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_support"><span class="	glyphicon glyphicon-cog" style="color: #bac9d1; font-size: 30px;"></span></a></li>
               </ul>
			</div>
      <!--/.nav-collapse -->
			</div>
    <!--/.container-fluid -->
		</nav>
   </div>
 </div>
 <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-bottom: -5px;">
    <div class="carousel-inner" role="listbox">
        <div class="item active" style="background-color: #000;height: 500px">
          <div class="container">
            <div class="carousel-caption" style="position: absolute;
    right: 15%;
    bottom: 80px;
    left: 15%;
    z-index: 10;
    padding-top: 20px;
    padding-bottom: 60px;
    color: #fff;
    text-align: center;
    text-shadow: 0 1px 2px rgba(0,0,0,.6)">
              <h1>UM SUBSCRIPTIONS</h1>
              <p>provides a summary of real-time statistics</p>
        
            </div>
          </div>
        </div>
    </div>
 </div>
<div class="container-fluid marketing">	
<div class="wrap" style="margin: 10px 1px 0 1px;">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e($message,'twodayssss');?></h2>
    <form id="adv-settings_um" class="ums-perpage" method="post">	
		<fieldset class="screen-options">			
			<!--<label for="plugins_per_page">Number of items per page:</label>-->
			<input step="1" min="1" max="100" class="screen-per-page" name="um_per_page" id="plugins_per_page" maxlength="3" value="<?php echo get_option('um_page_option_all'); ?>" type="number">
			<p class="submit"><input name="um_page" id="screen-options-apply" class="button button-primary" value="Apply" type="submit"></p>
		</fieldset>  		
	</form>
	
    <ul class="subsubsub" style="padding-left: 10px;">		
 <?php
    $all = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
    if($all == ""){ $all = 0; }
    $subscribed = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectafter = ".$prefix."um_switcher.user_status");
    if($subscribed == "" ){ $subscribed = 0; }
    ?>
		<li class="all"><strong>TOTAL | <span style="color:#7bc942"><a href="<?php $url = admin_url(); ?>admin.php?page=umsubscriptions_overview" style="color: green"><?php _e($all,'twodayssss'); ?></a></span><span class="count"></span></strong></li> 
		<?php
   ########## Total and Active Subscriptions #####
  global $post,$ultimatemember,$wpdb,$woocommerce;
     $prefix = $wpdb->prefix;    
      $base_prefix = $wpdb->base_prefix;
      //$all = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
      $subscribed = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectbefore = ".$prefix."um_switcher.user_status");
   ###############
?>	
	<li><strong style="margin-left: 12px;"> ACTIVE | <span style="color:#7bc942"><a href="<?php $url = admin_url(); ?>admin.php?page=umsubscriptions_overview&show=a" style="color: green"><?php _e($subscribed,'twodayssss'); ?></a></span></strong></li>
		 <?php
    //$all = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
    //if($all == ""){ $all = 0; }
    $subscribed = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectafter = ".$prefix."um_switcher.user_status");
    if($subscribed == "" ){ $subscribed = 0; }
    ?>
		<li class="subscribed"><strong style="margin-left: 12px;">NOT ACTIVE | <span style="color: red"><a href="<?php $url = admin_url(); ?>admin.php?page=umsubscriptions_overview&show=na" style="color: red"><?php _e($subscribed,'twodayssss'); ?></a></span><span class="count"></span></strong></li>
	</ul>
    <form id="persons-table" class="ums-table" method="GET">
		<div class="umswitcher-search">
            <p class="search-box" style="text-align:right">
            <label class="screen-reader-text" for="post-search-input">Search Pages:</label>
            <input id="post-search-input" name="s" value="<?php if(isset($_GET['s'])){echo $_GET['s'];} ?>" type="search">
            <input id="search-submit" class="button" value="SEARCH" name="search" type="submit"></p>
            <input type="hidden" name="page" value="<?php _e($_REQUEST['page'],'twodayssss'); ?>" />
		</div>
        <br clear="all" /><br clear="all" />
		<div class="main_section">
        <?php $table->display() ?>
		</div>
    </form>
	<div class="reminder_text" style="margin-top: 10px;padding: 10px; background-color: #fff;">
	<div id="element1" style="margin-left: 16px;"><p>No email reminder</div> 
	<div id="element2">            
		This means there is no email reminder setup at the product.</div> 
	<div id="element1"><p><span id="red">&#9679;</span>Date + Time in Red</div> 
	<div id="element2">Means the email reminder is not send yet but will be at this date and time. And then it will turn green.</div> 
	<div id="element1"><p><span id="green">&#9679;</span>Date + Time in Green</div> 
	<div id="element2">Means the email reminder is already send out.</div> 
    </div>
        <?php include 'dashboard/footer.php';?>
    </div>
		<script type="text/javascript">    
(function($) {
	 $(function () {
     $(".umswitcher_button").click(function (e) {
      e.preventDefault();
      $(".dropdown-menu").not($(this).toggleClass('active').next('.dropdown-menu').fadeToggle("slow")).fadeOut('fast');
     });
    }); 
}
)(jQuery);

document.onreadystatechange = function () {
  var state = document.readyState
  if (state == 'interactive') {
       document.getElementById('ums-loading').style.visibility="hidden";
  } else if (state == 'complete') {
      setTimeout(function(){
         document.getElementById('interactive');
         document.getElementById('load').style.visibility="hidden";
         document.getElementById('ums-loading').style.visibility="visible";
      },1000);
  }
}
</script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php }



/*dashboard widget */
function umswitcher_add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'umswitcher_dashboard_widget', // Widget slug.
		'UM-Switcher Status', // Title.
		'umswitcher_dashboard_widget_function' // Display function.
	);	
}
add_action( 'wp_dashboard_setup', 'umswitcher_add_dashboard_widgets' );

function umswitcher_dashboard_widget_function() {
	global $post,$wpdb,$woocommerce;
    $prefix = $wpdb->prefix;    
    $base_prefix = $wpdb->base_prefix;
    $all = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
    $subscribed = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectbefore = ".$prefix."um_switcher.user_status");
   ?>
     <ul class="wc_status_list um">	   		
		<li class="all">			
		 	<strong><?php _e($subscribed.' Active','twodayssss'); ?></strong><span><?php _e(' Subscriptions','twodayssss');?></span>
		</li>
		<li class="subscriber">
			<strong><?php _e($all.' Total','twodayssss'); ?></strong><?php _e(' Subscriptions','twodayssss');?>
		</li>		
	</ul>  
	
   <?php 
}


function umswitcher_profile_subscription_expiration() 
{
	    global $wpdb,$post,$woocommerce;
	    $prefix = $wpdb->prefix;	
		
		$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."**";
		$url = str_replace("/**","",$url);
		$url = str_replace("**","",$url);
		$username = @end(explode('/',$url));
		$result = $wpdb->get_results("SELECT um.expire_time, um.current_time, um.subscription_type_start_date, um.subscription_type_end_date FROM ".$prefix."um_switcher um, ".$prefix."users u WHERE u.user_login = '".$username."' AND um.user_id = u.ID LIMIT 1");
		$expire_time = 'No active subscription.';
		foreach($result as $row)
		{ 	
			$expire_time = date("l jS \of F Y h:i:s A", strtotime($row->expire_time));
			$current_time = $row->current_time;
			$subscription_type_start_date = $row->subscription_type_start_date; //new
			$subscription_type_end_date = $row->subscription_type_end_date; //new
		}

		return $expire_time;	
}

function umswitcher_user_subscription_expiration() 
{
	    global $wpdb,$post,$woocommerce;
	     $prefix = $wpdb->prefix;
		 
		$expire_time = "";
		$user_id = get_current_user_id();

		$result = $wpdb->get_results("SELECT * FROM ".$prefix."um_switcher WHERE user_id = ".$user_id." LIMIT 1");
		foreach($result as $row)
		{ 	
			$expire_time = date("l jS \of F Y h:i:s A", strtotime($row->expire_time));
			$current_time = $row->current_time;
			$subscription_type_start_date = $row->subscription_type_start_date; //new
			$subscription_type_end_date = $row->subscription_type_end_date; //new
		}
		return $expire_time;
}

function um_user_subscription_expiration( $fields ) 
{
	 $fields['um_switcher'] = array(
	  'title'    => __( 'UM Expiry date'),
	  'metakey' => 'um_switcher',
	  'required' => 0,
	  'public'   => 0,
	  'editable' => 0,
	  'type'     => 'text',
	  'icon'     => 'um-icon-ios-time-outline',
	  'default'     => '<div id=umswitcher_profile_subscription></div> ',
	  'label'   => 'Subscription end date '
 );
 return $fields;
}
add_filter( "um_predefined_fields_hook", 'um_user_subscription_expiration',  10, 1 );

function umswitcher_profile_subscription_expiration_footer() 
{
?>
	<script type="text/javascript">
	window.onload = function() { jQuery(document).ready(function(){ document.getElementById("umswitcher_profile_subscription").innerHTML = "<?php echo umswitcher_profile_subscription_expiration(); ?>"; }); }	
	</script>
<?php
}
add_action( 'wp_footer', 'umswitcher_profile_subscription_expiration_footer' ); 
	
function set_logged_user()
{
   ############## get online members #############
	$user = wp_get_current_user();
	if ( $user->exists()) 
	{
		// get the user activity the list
		$logged_in_users = get_transient('online_status');
		
		// get current user ID
		$user = wp_get_current_user();
		
		// check if the current user needs to update his online status;
		// he does if he doesn't exist in the list
		$no_need_to_update = isset($logged_in_users[$user->ID])
		
		// and if his "last activity" was less than let's say ...1 minutes ago
		&& $logged_in_users[$user->ID] >  (time() - (1 * 60));
		
		// update the list if needed
		if(!$no_need_to_update){
		  $logged_in_users[$user->ID] = time();
		  set_transient('online_status', $logged_in_users, (2*60)); // 2 mins
		}
	}
	############
}


######################################
if(@ $_REQUEST["new_role"] <> "")
{
	function update_user_roles() 
	{
		global $wpdb;
		$prefix = $wpdb->prefix;	
	
		$name = $_REQUEST['users'];
		foreach ($name as $v){ 
			$result = $wpdb->get_results("UPDATE ".$prefix."um_switcher SET user_status = '".$_REQUEST["new_role"]."', selectafter = '".$_REQUEST["new_role"]."' WHERE user_id='".$v."'");	
		}
	}
	update_user_roles();
}
######################################

############ Public ############
function ums_public_file() {
	wp_register_style('ums_custom_style', UMWOTD_ROOT_URL. '/assets/css/ums_custom_style.css', array(), $ver = false, $media = 'all');
	wp_enqueue_style('ums_custom_style');
}
add_action( 'wp_enqueue_scripts', 'ums_public_file' );
############ Public ############
 
function get_button_text($type)
{
	global $wpdb;
	
	$prefix = $wpdb->prefix;
	$result = $wpdb->get_results("SELECT * FROM ".$prefix."um_switcher_settings");
	foreach($result as $row) 
	{
		switch($row->type)
		{
			case "add_to_cart_button_txt_1":
		   		$add_to_cart_button_txt_1 = $row->type_value;	
				break;
			case "add_to_cart_button_txt_2":	
				$add_to_cart_button_txt_2 = $row->type_value;		
				break;
		}
	}	
	if($type == "ADD")
		return $add_to_cart_button_txt_1;	
	if($type == "VIEW")				
		return $add_to_cart_button_txt_2;		
}



#### WC: add to cart buttons ###
function um_generate_button_for_add_to_cart()
{
	global $product;
	@$product_type = $product->product_type;
	if($product_type == 'um_switcher')
		return '<a href="//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'?add-to-cart='.$product->get_id().'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$product->get_id().'" data-product_sku="" rel="nofollow">'.get_button_text('ADD').'</a>';
	else
		return '<a href="//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'?add-to-cart='.$product->get_id().'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$product->get_id().'" data-product_sku="" rel="nofollow">'.$product->single_add_to_cart_text().'</a>';
}
function custom_woocommerce_product_add_to_cart_text( $button_text ) {
	global $product;
	@$product_type = $product->product_type;
	switch ( $product_type ) {
		case 'um_switcher':
			$_SESSION["product_type_selected"] = "um_switcher";
			return get_button_text('ADD');
			break;
		default:
			$_SESSION["product_type_selected"] = "";
			return $product->single_add_to_cart_text();
			break;			
	}
}
add_filter( 'woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text' );
add_filter( 'woocommerce_loop_add_to_cart_link', 'um_generate_button_for_add_to_cart' );
### END #########################



add_action( 'wp', 'set_logged_user' );

add_action('before_delete_post', function($id) {
    global $wpdb, $post_type;

    if($post_type !== 'shop_order') {
        return;
    }

    $order = new WC_Order($id);
	$user_id = $order->get_user_id();

	$user = new WP_User($user_id);
	$def_role = get_option('default_role');
	$user->set_role($def_role);

	//start - update umswticher subscription to default role
	$prefix = $wpdb->prefix;
	$result = $wpdb->get_results( "DELETE FROM ".$prefix."um_switcher WHERE order_id = '$id'");
	//end	

}, 10, 1);

function add_order_removed_notes_in_umswticher ($post_id) 
{
    $post_type = get_post_type( $post_id );
    $post_status = get_post_status( $post_id );
    if( $post_type == 'shop_order' ) {
			
			global $wpdb, $post_type;
		
			if($post_type !== 'shop_order') {
				return;
			}
		
			$order = new WC_Order($post_id);
			$user_id = $order->get_user_id();
		
			$user = new WP_User($user_id);
			$def_role = get_option('default_role');
			$user->set_role($def_role);
		
			//start - update umswticher subscription to default role
			$prefix = $wpdb->prefix;
			$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET user_status='$def_role', wc_order_status='ORDER REMOVED' WHERE order_id = '$post_id'");
			//end
    }
}
add_action('wp_trash_post', 'add_order_removed_notes_in_umswticher');

function remove_deleted_notes_from_umswticher( $post ) {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$result = $wpdb->get_results( "UPDATE ".$prefix."um_switcher SET wc_order_status='' WHERE order_id = '$post'");
}
add_action( 'untrash_post', 'remove_deleted_notes_from_umswticher');



function wl8OrderPlacedTriggerSomething($order_id)
{
	if($_SESSION["product_type_selected"] = "um_switcher") 
	{
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		foreach ( $items as $item ) {
			$product_id = $item['product_id'];
		}
		$red = get_post_meta( $product_id, 'redirect_after_checkout', true );
		if($red <> "") 
		{
			echo '<script>
			function load_redirection()
			{
				window.location = "'.get_post_meta( $product_id, 'redirect_after_checkout', true ).'";
			}
			setTimeout(load_redirection, 6000);
			</script>';
			//exit();				
		}
	} 
}
$hook_to = 'woocommerce_thankyou';
$what_to_hook = 'wl8OrderPlacedTriggerSomething';
$prioriy = 111;
$num_of_arg = 1;    
add_action($hook_to, $what_to_hook, $prioriy, $num_of_arg);
?>