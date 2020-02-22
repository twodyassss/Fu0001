<?php
	/**
	* Dashboard um-switcher
	*/
	if ( ! defined( 'ABSPATH' ) )
	exit;

	global $wpdb,$post,$woocommerce,$ultimatemember;
      $prefix = $wpdb->prefix;    
      $base_prefix = $wpdb->base_prefix;
			
   if(@$_REQUEST["yr"] <> "") $def_yr = @$_REQUEST["yr"];
   else $def_yr = date("Y");
   
  ########### get graph data ##################
   $month = array();
   	
   $month[0] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-01-01' AND '".$def_yr."-01-31'");
   if($month[0] == "" || !isset($month[0])) $month[0] = 0;
   
   $month[1] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-02-01' AND '".$def_yr."-02-31'");
   if($month[1] == "" || !isset($month[1])) $month[1] = 0;
   
   $month[2] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-03-01' AND '".$def_yr."-03-31'");
   if($month[2] == "" || !isset($month[2])) $month[2] = 0;
   
   $month[3] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-04-01' AND '".$def_yr."-04-31'");
   if($month[3] == "" || !isset($month[3])) $month[3] = 0;
   
   $month[4] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-05-01' AND '".$def_yr."-05-31'");
   if($month[4] == "" || !isset($month[4])) $month[4] = 0;
   
   $month[5] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-06-01' AND '".$def_yr."-06-31'");
   if($month[5] == "" || !isset($month[5])) $month[5] = 0;
   
   $month[6] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-07-01' AND '".$def_yr."-07-31'");
   if($month[6] == "" || !isset($month[6])) $month[6] = 0;
   
   $month[7] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-08-01' AND '".$def_yr."-08-31'");
   if($month[7] == "" || !isset($month[7])) $month[7] = 0;
   
   $month[8] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-09-01' AND '".$def_yr."-09-31'");
   if($month[8] == "" || !isset($month[8])) $month[8] = 0;
   
   $month[9] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-10-01' AND '".$def_yr."-10-31'");
   if($month[9] == "" || !isset($month[9])) $month[9] = 0;
   
   $month[10] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-11-01' AND '".$def_yr."-11-31'");
   if($month[10] == "" || !isset($month[10])) $month[10] = 0;
   
   $month[11] = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered BETWEEN '".$def_yr."-12-01' AND '".$def_yr."-12-31'");
   if($month[11] == "" || !isset($month[11])) $month[11] = 0;
   
   $min_ = min($month);
   $max_ = max($month);
   ####################################### 
   
   
   ######### generate year dropdown options ##########
   $opt = '';
   $current_year = date("Y");
   for($i = 2015; $i <= $current_year; $i++)
   {
   	if($i == $def_yr)
   		$opt .= "<option value='".$i."' selected>".$i."</option>";
   	else
   		$opt .= "<option value='".$i."'>".$i."</option>";
   }
   ###################################################
   
   
   ########## generate new members data #####
   $dt1 = new DateTime();
   $current_date_next = $dt1->format("Y-m-d");
   
   $dt2 = new DateTime("-1 month");
   $current_date = $dt2->format("Y-m-d");
  
   //$sql = "SELECT ".$prefix."um_switcher.user_id, ".$prefix."um_switcher.product_id, ".$prefix."um_switcher.order_id FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."users.user_registered = '".$current_date_next."' OR ".$prefix."users.user_registered BETWEEN '".$current_date."' AND '".$current_date_next."' LIMIT 4";
   $sql = "SELECT ".$prefix."um_switcher.user_id, ".$prefix."um_switcher.product_id, ".$prefix."um_switcher.order_id FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id ORDER BY ".$prefix."users.user_registered DESC LIMIT 4";
  //echo $sql;
  $res = $wpdb->get_results($sql);
   ##########################################
   
   
   ########## Total and Active Subscriptions #####
  global $post,$ultimatemember,$wpdb,$woocommerce;
     $prefix = $wpdb->prefix;    
      $base_prefix = $wpdb->base_prefix;
      $all = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
      $subscribed = $wpdb->get_var("SELECT COUNT(*) FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.selectbefore = ".$prefix."um_switcher.user_status");
   ###############
   
   
   ############### System Status  ################
   //get Woocommerce version
   $woocommerce_version = $wpdb->get_results("SELECT option_value FROM ".$prefix."options WHERE option_name='woocommerce_version' LIMIT 1");
   foreach($woocommerce_version as $row) $woocommerce_version_data = $row->option_value;	
   //end
   ###############################################
   
   
   ############# get total sales #######################
   $total_sales = 0;
   $result = $wpdb->get_results("SELECT product_id FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");
   foreach($result as $row) $total_sales = $total_sales + get_post_meta( $row->product_id, '_regular_price', true);
   #####################################################
   
   
   ############# get total sales last week #############
   $total_sales_last_week = 0;
   $d1 = new DateTime();
   $current_date_next = $d1->format("Y-m-d");
   
   $d2 = new DateTime("-1 week");
   $current_date = $d2->format("Y-m-d");
   $result = $wpdb->get_results("SELECT product_id FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id WHERE ".$prefix."um_switcher.current_time BETWEEN '".$current_date."' AND '".$current_date_next."'");
   foreach($result as $row) $total_sales_last_week = $total_sales_last_week + get_post_meta( $row->product_id, '_regular_price', true);
   #####################################################	
   
   
   ############## get online members #############
	function display_logged_in_users()
	{
		// get the user activity the list
		$logged_in_users = get_transient('online_status');
		if ( !empty( $logged_in_users ) ) 
		{
			return count($logged_in_users);
			//foreach ( $logged_in_users as $key => $value) 
			//{
				//$user = get_user_by( 'id', $key );
				//echo '<br/> Looged in user name is ' . $user->display_name;
			//}
		} 
		else return 0;
	}   
   ###############################################


   ############### get current db version ############
    $conf_file = $_SERVER['DOCUMENT_ROOT']."/dbversion";
	$db_current_version = @file_get_contents($conf_file);
   ###############################################

   
?>
<div class="main-content" style="margin-left: -20px;">
<script type="text/javascript">
   window.onload = function () {
   	var chart = new CanvasJS.Chart("chartContainer", {
   	
   		axisX: {
   			interval: 12
   		},
   		dataPointWidth: 60,
   		data: [{
   			type: "column",
   			indexLabelLineThickness: 2,
   			dataPoints: [
   				  { x: 01, y: <?php echo $month[0]; if($month[0] == 0 || $month[0] == $min_) { echo ', indexLabel: "Jan"'; } elseif($month[0] == $max_) { echo ', indexLabel: "Jan"'; } else { echo ', indexLabel: "Jan"'; } ?> },
   				  { x: 02, y: <?php echo $month[1]; if($month[1] == 0 || $month[1] == $min_) { echo ', indexLabel: "Feb"'; } elseif($month[1] == $max_) { echo ', indexLabel: "Feb"'; } else { echo ', indexLabel: "Feb"'; } ?> },
   				  { x: 03, y: <?php echo $month[2]; if($month[2] == 0 || $month[2] == $min_) { echo ', indexLabel: "Mar"'; } elseif($month[2] == $max_) { echo ', indexLabel: "Mar"'; } else { echo ', indexLabel: "Mar"'; } ?> },
   				  { x: 04, y: <?php echo $month[3]; if($month[3] == 0 || $month[3] == $min_) { echo ', indexLabel: "Apr"'; } elseif($month[3] == $max_) { echo ', indexLabel: "Apr"'; } else { echo ', indexLabel: "Apr"'; } ?> },
   				  { x: 05, y: <?php echo $month[4]; if($month[4] == 0 || $month[4] == $min_) { echo ', indexLabel: "May"'; } elseif($month[4] == $max_) { echo ', indexLabel: "May"'; } else { echo ', indexLabel: "May"'; } ?> },
   				  { x: 06, y: <?php echo $month[5]; if($month[5] == 0 || $month[5] == $min_) { echo ', indexLabel: "Jun"'; } elseif($month[5] == $max_) { echo ', indexLabel: "Jun"'; } else { echo ', indexLabel: "Jun"'; } ?> },
   				  { x: 07, y: <?php echo $month[6]; if($month[6] == 0 || $month[6] == $min_) { echo ', indexLabel: "Jul"'; } elseif($month[6] == $max_) { echo ', indexLabel: "Jul"'; } else { echo ', indexLabel: "Jul"'; } ?> },
   				  { x: 08, y: <?php echo $month[7]; if($month[7] == 0 || $month[7] == $min_) { echo ', indexLabel: "Aug"'; } elseif($month[7] == $max_) { echo ', indexLabel: "Aug"'; } else { echo ', indexLabel: "Aug"'; } ?> },
   				  { x: 09, y: <?php echo $month[8]; if($month[8] == 0 || $month[8] == $min_) { echo ', indexLabel: "Sept"'; } elseif($month[8] == $max_) { echo ', indexLabel: "Sept"'; } else { echo ', indexLabel: "Sept"'; } ?> },
   				  { x: 10, y: <?php echo $month[9]; if($month[9] == 0 || $month[9] == $min_) { echo ', indexLabel: "Oct"'; } elseif($month[9] == $max_) { echo ', indexLabel: "Oct"'; } else { echo ', indexLabel: "Oct"'; } ?> },
   				  { x: 11, y: <?php echo $month[10]; if($month[10] == 0 || $month[10] == $min_) { echo ', indexLabel: "Nov"'; } elseif($month[10] == $max_) { echo ', indexLabel: "Nov"'; } else { echo ', indexLabel: "Nov"'; } ?> },
   				  { x: 12, y: <?php echo $month[11]; if($month[11] == 0 || $month[11] == $min_) { echo ', indexLabel: "Dec"'; } elseif($month[11] == $max_) { echo ', indexLabel: "Dec"'; } else { echo ', indexLabel: "Dec"'; } ?> }						  
   			]
   		}]
   	});
   	chart.render();
   }
</script>
<div class="navbar-wrapper">
   <div class="container-fluid">
      <nav class="navbar navbar-inverse navbar-static-top" style="margin-left: -20px;">
         <div class="container">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar3">
               <span class="sr-only"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
               <img class="navbar-brand" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/um_logo.png'; ?>">
            </div>
            <div id="navbar3" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right" style="margin-top: 7px">
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=UM-Switcher"><span class="glyphicon glyphicon-signal" style="color: #000; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_settings"><span class="glyphicon glyphicon-calendar" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_faq"><span class="glyphicon glyphicon-question-sign" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_email"><span class="glyphicon glyphicon-envelope" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_support"><span class="glyphicon glyphicon-cog" style="color: #bac9d1; font-size: 30px;"></span></a></li>
               </ul>
            </div>
            <!--/.nav-collapse -->
         </div>
         <!--/.container-fluid -->
      </nav>
   </div>
</div>
<div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-bottom: -10px;">
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
               <h1>UM DASHBOARD</h1>
               <p>Provides a summary of real-time statistics</p>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid marketing" style="padding: 25px;">
<div class="row">
   <div class="col-6 col-sm-3">
      <h4>Total Sales</h4>
      <div class="um-content switcher">
         <p class="pull-right"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/icon-up.png'; ?>" style="width:20px; margin-right:5px; margin-top: -5px;"><span style="font-size: 130%;"><?php echo get_woocommerce_currency_symbol();  ?> <?php echo $total_sales; ?></span></p>
         <hr class="content-divider">
      </div>
   </div>
   <div class="col-6 col-sm-3">
      <h4>Last 7 Days Sales</h4>
      <div class="um-content switcher">
         <p class="pull-right"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/icon-up.png'; ?>" style="width:20px; margin-right:5px; margin-top: -5px;"><span style="font-size: 130%;"><?php echo get_woocommerce_currency_symbol();  ?> <?php echo $total_sales_last_week; ?></span></p>
         <hr class="content-divider">
      </div>
   </div>
   <div class="col-6 col-sm-3">
      <h4>Total Subscriptions</h4>
      <div class="um-content switcher">
         <p class="pull-right"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/icon-up.png'; ?>" style="width:20px; margin-right:5px; margin-top: -5px;"><span style="font-size: 130%;"><?php _e($all,'twodayssss'); ?></span></span></p>
         <hr class="content-divider">
      </div>
   </div>
   <div class="col-6 col-sm-3">
      <h4>Active Subscriptions</h4>
      <div class="um-content switcher">
         <p class="pull-right"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/icon-up.png'; ?>" style="width:20px; margin-right:5px; margin-top: -5px;"><span style="font-size: 130%;"><?php _e($subscribed.' ','twodayssss'); ?></span></p>
         <hr class="content-divider">
      </div>
   </div>
</div>
<div class="row row-eq-height" style="margin-top: 10px;">
   <div class="col-md-9 push-md-3">
      <h4>Member Status</h4>
      <div class="pull-right" style="margin-top: -40px;">
         <select id="year_end" name="year_end" onchange="javascript: window.location='?page=UM-Switcher&yr='+this.value; " /> 
         <?php echo $opt; ?>
         </select> 
      </div>
      <div id="chartContainer" style="height: 321px; width: 100%;"></div>
      <hr class="content-divider" style="margin-top: 0px;">
   </div>
   <div class="col-md-3 pull-md-9">
      <h4>New members</h4>
	  <div class="new-members" style="margin-bottom: 30px;">
      <?php
         foreach($res as $row)
         { 	
         	$udata = get_userdata($row->user_id);
         	$registered = date("F j, Y, g:i a", strtotime($udata->user_registered));
         	$user_display_name = $udata->display_name;
			$sql = "SELECT ".$prefix."woocommerce_order_items.order_item_name FROM ".$base_prefix."woocommerce_order_items WHERE ".$base_prefix."woocommerce_order_items.order_id = '".$row->order_id."' LIMIT 1";
			
			$res2 = $wpdb->get_results($sql);
			foreach($res2 as $row2) $product_name = $row2->order_item_name; 
         ?>
        <div class="group" style="margin-bottom: 20px;">
            <div class="left" style="width: 20%;">
                <a href="<?php echo um_user_profile_url(); ?>" target="_blank"><?php echo get_avatar($row->user_id,80); ?></a>   
            </div>
            <div class="right" style="width: 75%;">
                <p style="margin: 0 0 0px;"><strong><?php echo $user_display_name; ?></strong></p>
                <p style="margin: 5px 0 5px; font-size: 10px;"><i><?php echo $registered; ?></i></p>
                <p style="margin: 0 0 5px; font-size: 10px; margin-top: -7px;"><i>purchased umswitcher <?php echo $product_name; ?></i></p>
            </div>
        </div>
       <?php } ?>
	 </div>
	<hr class="content-divider">
   </div>
</div>
<div class="row row-eq-height" style="margin-top: 10px;">
<div class="col-6 col-sm-4">
      <h4>System Status</h4>
      <div class="um-content">
         <div class="group">
			<p><i class="fa fa-circle um-green" aria-hidden="true"></i>UM-Switcher 2.5
			</p>
			<p>
			<?php
			if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				echo '<i class="fa fa-circle um-green" aria-hidden="true"></i>','Woocommerce ';
				echo $woocommerce_version_data;
				
			}
			else {

			echo '<i class="fa fa-circle um-red" aria-hidden="true"></i>Plugin not available';
			}
			?>
			</p>
			<p>
			<?php
			if( is_plugin_active( 'ultimate-member/ultimate-member.php' ) ) {
			echo '<i class="fa fa-circle um-green" aria-hidden="true"></i>Ultimate member ';
			echo ultimatemember_version;
			}
			else {
			echo '<i class="fa fa-circle um-red" aria-hidden="true"></i>Plugin not available';
			}
			?>
			</p>
			<p> 
			<?php 
				if (@$settings[4] <> "" && @$settings[5] <> " ") echo '<i class="fa fa-circle um-green" aria-hidden="true"></i>Multi site';
				else echo '<i class="fa fa-circle um-red" aria-hidden="true"></i>Multi site';
			?> 
			</p>
			<p>
			<?php      
            	$timezone = get_option('timezone_string');
				$timelen=  strlen($timezone);
				if($timelen>0)
				{
					echo '<i class="fa fa-circle um-green" aria-hidden="true"></i>Time zone: ';
					_e($timezone,'twodayssss');
					date_default_timezone_set($timezone);
				} 
				else {
					echo '<i class="fa fa-circle um-red" aria-hidden="true"></i>Time zone: ';
					_e("UTC+/- is not supported, Please select your city instead, see (Settings/General/Timezone)",'twodayssss');
				}
			?>
			</p>
			<p> 
				<i class="fa fa-circle um-green" aria-hidden="true"></i>Um-switcher Database Version <?php echo $db_current_version; ?>
			</p>
            
         </div>
         <hr class="content-divider" style="margin-top: 10px;">
      </div>
   </div>
	<div class="col-6 col-sm-4">
      <h4>Online Members</h4>
      <div class="um-content switcher" style="margin-top: 90px;">
         <p class="pull-right um-mobile" style="margin-top: 65px;"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/icon-up.png'; ?>" style="width:20px; margin-right:5px; margin-top: -5px;"><span style="font-size: 130%;"><?php //echo get_user_count(); ?><?php echo display_logged_in_users(); ?></span></p>
         <hr class="content-divider">
      </div>
	</div>
	<div class="col-6 col-sm-4">
      <h4>Total Members</h4>
      <div class="um-content switcher" style="margin-top: 90px;">
         <p class="pull-right um-mobile" style="margin-top: 65px;"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/icon-up.png'; ?>" style="width:20px; margin-right:5px; margin-top: -5px;"><span style="font-size: 130%;"><?php
            $result = count_users();
            echo '', $result['total_users'], '';?></span></p>
         <hr class="content-divider">
      </div>
	</div>   
</div>
<?php include 'footer.php';?>