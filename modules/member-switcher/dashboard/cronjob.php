<?php
	if ( ! defined( 'ABSPATH' ) )
	exit;
	
		global $wpdb;
    	$prefix = $wpdb->prefix;
	   $result = $wpdb->get_results("SELECT * FROM ".$prefix."um_switcher_settings");
	   foreach($result as $row) {
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
?>  
<div class="main-content" style="margin-left: -20px;">
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
               <img class="navbar-brand" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'dashboard/img/um_logo.png'; ?>">
            </div>
            <div id="navbar3" class="navbar-collapse collapse" style="margin-top: 7px;">
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=UM-Switcher"><span class="glyphicon glyphicon-signal" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_settings"><span class="glyphicon glyphicon-calendar" style="color: #000; font-size: 30px;"></span></a></li>
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
<div id="myCarousel" class="carousel slide" data-ride="carousel"  style="margin-bottom: 15px;">
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
               <h1>SETTINGS</h1>
				<p>Here you can controll some settings.</p>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid marketing">

<div class="row row-eq-height" style="margin-right: -5px;
    margin-left: -5px;">
    <div class="col-sm-6">
        <h2 style="margin-top: -10px;">Server Settings</h2>
        <p>You need a cronjob for an accurate switch between user roles and for sending out the email renew notifications. Use the below path for creating your cronjob.</p>
		<p><strong>Cronjob Path:</strong> <?php echo admin_url()."admin-ajax.php?action=umswticher_cronjob"; ?></p>
    </div>
    <div class="col-sm-6">  
	<h2 style="margin-top: -10px;">UMS Product buttons</h2>
		<p>A product displays a button with the texts’ ‘Add to cart’ by default. A UMS subscription changes this to for example: Sign Up Now. You can customize the button texts below. In case you don’t see a difference, some themes overwrite button text and you will need to contact the theme developer.</p>	
Add to cart button Text<input style="width: 250px; display: initial; margin-left: 10px;" type="text" class="form-control" id="add_to_cart_button_txt_1" value="<?php echo $add_to_cart_button_txt_1; ?>" /><br /><br />
<!--Place order button Text<input style="width: 250px; display: initial; margin-left: 10px;" type="text" class="form-control" id="add_to_cart_button_txt_2" value="<?php echo $add_to_cart_button_txt_2; ?>" /><br /><br />-->
<input type="hidden" id="add_to_cart_button_txt_2" value="<?php echo $add_to_cart_button_txt_2; ?>" />
    </div>
</div>




<input type="button" name="email_template" class="btn btn-info pull-right" onclick="javascript: save_add_to_cart_button_txt(); " value="SAVE SETTINGS" style="margin-top: 15px; background-color: #000; border-radius: 0; color: #fff; padding: 10px 12px; border-color: #000;" /><br />
<div id="save_add_to_cart_button_txt_loader" class="pull-right" style="margin-right: 10px;
    margin-top: 5px;"></div>


<div style="margin-top: 70px;">
   <?php include 'footer.php';?>   
</div>