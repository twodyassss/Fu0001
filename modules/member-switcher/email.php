<div class="main-content" style="margin-left: -20px;">
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
			<img class="navbar-brand" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'um-switcher/dashboard/img/um_logo.png'; ?>">
		</div>
		<div id="navbar3" class="navbar-collapse collapse" style="margin-top: 7px;">
			 <ul class="nav navbar-nav navbar-right">
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=UM-Switcher"><span class="glyphicon glyphicon-signal" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_settings"><span class="glyphicon glyphicon-calendar" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_faq"><span class="glyphicon glyphicon-question-sign" style="color: #bac9d1; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_email"><span class="glyphicon glyphicon-envelope" style="color: #000; font-size: 30px;"></span></a></li>
                  <li><a href="<?php $url = admin_url(); ?>admin.php?page=um_switcher_support"><span class="	glyphicon glyphicon-cog" style="color: #bac9d1; font-size: 30px;"></span></a></li>
               </ul>
		</div>
      <!--/.nav-collapse -->
		</div>
    <!--/.container-fluid -->
	</nav>
 </div>
</div>
<div id="myCarousel" class="carousel slide" data-ride="carousel"  style="margin-bottom: 30px;">
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
              <h1>EMAIL TEMPLATE SETTINGS</h1>
              <p>Customize your email template</p>
            </div>
          </div>
     </div>   
</div>
<div class="container-fluid marketing" style="padding: 25px; padding-top: 0px;">

 <?php
if ( ! defined( 'ABSPATH' ) )
	exit;
 
global $post,$ultimatemember,$wpdb,$woocommerce,$_FILES,$_POST;
$table_woo = $wpdb->prefix.'email_template';
$siteurl = get_site_url();
/*Remove image start*/
if(isset($_POST['removeimage'])){
	$imagedelete = $_POST['hiddenrimage'];
	$delete_image = "UPDATE $table_woo SET header_image = '' WHERE header_image ='$imagedelete' ";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($delete_image);	
}
/*remove image end*/
$checkboxvalue = "";
$image_url = "";
$from_name 		= "";
$from_address 	= "";
$footer_text	= "";	
$base_color 	= "";
$bg_color 		= "";
$body_bg_color 	= "";
$body_txt_color = "";
if(isset($_POST['email_template'])) {	
	$image_url = $_POST['imagemedia'];
	$from_name 		= $_POST['from_name'];
	$from_address 	= $_POST['from_address'];
	$footer_text	= $_POST['footer_text'];	
	$base_color 	= $_POST['base_color'];
	$bg_color 		= $_POST['bg_color'];
	$body_bg_color 	= $_POST['body_bg_color'];
	$body_txt_color = $_POST['body_txt_color'];
	if(isset($_POST['umcheckmail'])){
		$checkboxvalue = $_POST['umcheckmail'];
	}	
	
	if($checkboxvalue == 'yes'){
		$checkboxemail = "on";
	}else{
		$checkboxemail = "off";
	}	
	
    $wpdb->get_results( 'SELECT * FROM '.$table_woo );
	$row = $wpdb->num_rows;

	if($row == 0){
		$insert_email = "INSERT INTO `$table_woo`(from_name,from_address,header_image,footer_text,base_color,bg_color,body_bg_color,body_txt_color,checkboxemail) VALUES ('".$from_name."','".$from_address."','".$image_url."','".$footer_text."','".$base_color."','".$bg_color."','".$body_bg_color."','".$body_txt_color."','".$checkboxemail."')";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($insert_email);
	}
    if($row > 0){    	
    	$update_email = "UPDATE `$table_woo` SET from_name='$from_name',from_address='$from_address',header_image='$image_url',footer_text='$footer_text',base_color='$base_color',bg_color='$bg_color',body_bg_color='$body_bg_color',body_txt_color='$body_txt_color',checkboxemail='$checkboxemail'";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($update_email);	

    }
    	
		echo '<div class="updated notice"><p>' . esc_html__( 'Settings saved.', 'twodayssss' ) . '</p></div>';
	}
		

	$result = $wpdb->get_results( 'SELECT * FROM '.$table_woo );

		$var = array();
		
	foreach ($result as $row) {

		$var['from_name'] 		= $row->from_name;		
		$var['from_address'] 	= $row->from_address;		
		$var['header_image']   = $row->header_image;		
		$var['footer_text']	= $row->footer_text;		
		$var['base_color'] 	= $row->base_color;
		$var['bg_color'] 		= $row->bg_color;
		$var['body_bg_color'] 	= $row->body_bg_color;
		$var['body_txt_color'] = $row->body_txt_color;
		$var['checkboxemail'] = $row->checkboxemail;		

	}
	if(isset($_POST['send_test_mail'])) {     
		
		$from_namet 		= $_POST['from_name'];
		$from_addresst 	= $_POST['from_address'];
		$footer_textt	= $var['footer_text'];		
		$base_colort	= $_POST['base_color'];
		$bg_colort 		= $_POST['bg_color'];
		$body_bg_colort 	= $_POST['body_bg_color'];
		$body_txt_colort = $_POST['body_txt_color'];
		$test_from_mail = $_POST['test_from_mail'];
		$imagetest = $_POST['imagemedia'];

        $to = $test_from_mail;     
        $subject = 'test mail';    
        $imagesrc = '<img src="'.$imagetest.'" style="width:100%;height:auto;">';
        
        if($var['checkboxemail'] == 'on') {
        	$message = " <p>Hello lorem,<br/></p>
                  <p>Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.<br/></p>
                   <p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>";
        }
        if($var['checkboxemail'] == 'off'){

        $message = '<html><style>
        @media (min-height:1050px) and (max-height:2500px){#body{  height: 100% !important;}} 
        @media (min-width:1400px) and (max-width:2500px){#body{  height: 100% !important;}}</style>
            <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
                <div id="body" style="background-color:'.$base_colort.';width:100%;-webkit-text-size-adjust:none !important;margin:0;padding:0px 0;height:auto;">';

		$message .= "<table border='0' cellpadding='0' cellspacing='0' width='680' id='template_container' style='-webkit-box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;box-shadow:none !important;-webkit-border-radius:6px !important;border-radius:6px !important;background-color:".$base_colort.";border:none;text-align:center;margin:0 auto;padding:70px  0;height:auto;'>";

		$message .=  "<tr><td align='center' valign='top'>";

		$message .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_header' style='background-color: ".$bg_colort.";color: #f1f1f1;-webkit-border-top-left-radius:6px !important;-webkit-border-top-right-radius:6px !important;border-top-left-radius:6px !important;border-top-right-radius:6px !important;border-bottom: 0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle;'>
                <tr>
                    <td>
                        <h1 style='color: #000000;margin:0;padding: 28px 24px;display:block;font-family:Arial;font-size: 30px;font-weight:bold;text-align:center;line-height: 150%;' id='logo'>
                        <a style='color: #000000;text-decoration: none;'' href='#' title='".$from_namet."'>$imagesrc</a>
                        </h1>
                    </td>
                </tr>
            </table>";

		$message .= "</td></tr>";

		$message .= "<tr>
                <td align='center' valign='top'>
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_body'>
                        <tr>
                            <td valign='top' style='background-color: ".$body_bg_colort.";' id='mailtpl_body_bg'>
                                <table border='0' cellpadding='20' cellspacing='0' width='100%'>
                                    <tr>
                                        <td valign='top'>
                                            <div style='color:".$body_txt_colort.";font-family:Arial;font-size: 14px;line-height:150%;text-align:left;' id='mailtpl_body'>
                                                <p>Hello lorem,<br/></p>
                                                <p>Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.<br/></p>
                                                <p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>                                                
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

		$message.= "<tr>
                <td align='center' valign='top'>
                    <table border='0' cellpadding='10' cellspacing='0' width='100%' id='template_footer' style='border-top:1px solid #E2E2E2;background: #ffffff;-webkit-border-radius:0px 0px 6px 6px;-o-border-radius:0px 0px 6px 6px;-moz-border-radius:0px 0px 6px 6px;border-radius:0px 0px 6px 6px;height:auto;'>
                            <tr>
                                <td valign='top'>
                                    <table border='0' cellpadding='10' cellspacing='0' width='100%'>
                                        <tr>
                                            <td colspan='2' valign='middle' id='credit' style='border:0;color:#000000;font-family: Arial;font-size: 10px;line-height:125%;text-align:left;'>".$footer_textt."
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
            }           
				   
                $sender =  rtrim($from_addresst);      
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";        
                $headers .= 'From:'.rtrim($from_namet).' <'.rtrim($from_addresst).'>'."\r\n";
                //$headers .= 'Bcc: '.rtrim($from_addresst).'' . "\r\n";           
             	
              if(wp_mail($to, $subject, $message, $headers)){
              	_e("<div class='updated notice'><p><b>Test mail send successfully</b></p></div>","umwotd");
              }
}
?>

<form class="form-horizontal" action="#" method="post" name="umwotd_email_form" id="mainform" enctype="multipart/form-data">
    <div class="row email-equal-height" style="margin-top: 15px;">
        <div class="col-sm-6">
            <h2>Email Template</h2>
            <p>Enter your email and email sender name</p>
            <label>From</label>
            <input type="text" class="form-control" value="<?php echo $var['from_name'];?>" id="from_name" placeholder="" name="from_name">
            </br>
            <label>Email Sender</label>
            <input type="email" class="form-control" value="<?php echo $var['from_address'];?>" id="from_address" placeholder="" multiple="multiple" name="from_address">
            </br>
        </div>
        <div class="col-sm-6">
            <h2>Header Image</h2>
            <p>Upload your header image, advice 650x350px</p>
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" id="image-url" name="imagemedia" class="form-control" value="<?php echo $var['header_image'];?>" placeholder="">
                        <span class="input-group-btn">
                        <button id="upload-button" class="btn btn-secondary" type="button" value="Upload">Upload</button>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <td class="forminp forminp-text">
                        <?php $image_name = basename($var['header_image']).PHP_EOL; ?>
                        <?php 
                            if($var['header_image'] == ""){
                            	$image_name = "um-switcher.jpg";
                            	$var['header_image'] = get_site_url().'/wp-content/plugins/um-switcher/assets/images/'.$image_name;
                            }
                            ?>					
                        <div class="fileUpload btn btn-primary" style="display: none;">
                            <span class=""></span>
                            <input id="uploadBtn" type="hidden" class="upload" name="image"/>
                        </div>
                        <div class="imgtext">						
                            <img class='plugin_hdr_img' src="<?php echo $var['header_image'];?>" style="width:100%;" height="auto">
                        </div>
                        <div class="imgtext">
                            <input type="hidden" name="hiddenrimage" value="<?php echo $var['header_image'];?>">
                        </div>
                        <input type="hidden" name="hidden_image" value="<?php echo $image_name; ?>">
                    </td>
                </div>
            </div>
            </br>
        </div>
    </div>
    <div class="row email-equal-height" style="margin-top: 10px;">
        <div class="col-sm-6">
            <h2>Footer Details</h2>
            <p>Enter your copyright etc.</p>
            <textarea class="form-control" name="footer_text" id="footer_text" style="width:300px; height: 75px;" class="" placeholder='<?php _e("UM-Switcher-Email Template powered by Themedutch","umwotd");?>'><?php echo $var['footer_text'];?></textarea>
            </br>
        </div>
        <div class="col-sm-6">
            <h2>Colors</h2>
            <p>Customize your colors</p>
            <div class="col-sm-6" style="border: 0px;">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-6" style="border:0px; text-align: left;" for="email">Background color</label>
                        <?php if($var['base_color'] == ""){
                            $var['base_color']='#f7f7f7';
                            }?>					    
                        <span class="colorpickpreview" style="background: <?php echo $var['base_color'];?>"></span>	
                        <input class="colorpick" type="text" name="base_color" value="<?php echo $var['base_color'];?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" style="border:0px; text-align: left;" for="email">Border color</label>
                        <?php if($var['bg_color'] == ""){
                            $var['bg_color']='#ffffff';
                            }?>				
                        <span class="colorpickpreview" style="background: <?php echo $var['bg_color'];?>"></span>
                        <input class="colorpick" type="text" name="bg_color" value="<?php echo $var['bg_color'];?>"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" style="border: 0px;">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-6" style="border:0px; text-align: left;" for="email">Body Background color</label>
                        <?php if($var['body_bg_color'] == ""){
                            $var['body_bg_color']='#ffffff';
                            }?>			
                        <span class="colorpickpreview" style="background: <?php echo $var['body_bg_color'];?>"></span>
                        <input class="colorpick" type="text" name="body_bg_color" value="<?php echo $var['body_bg_color'];?>"/>	
                    </div>
                    <div class="form-group" style="margin-top: -15px;">
                        <label class="control-label col-sm-6" style="border:0px; text-align: left;" for="email">Body text</label>
                        <?php if($var['body_txt_color'] == ""){
                            $var['body_txt_color']='#000000';
                            }?>					
                        <span class="colorpickpreview" style="background: <?php echo $var['body_txt_color'];?>"></span>	
                        <input class="colorpick" type="text" name="body_txt_color" value="<?php echo $var['body_txt_color'];?>"/>	
                    </div>
                </div>
            </div>
            </br>
        </div>
    </div>
    <div class="row email-equal-height" style="margin-top: 10px;">
        <div class="col-sm-6">
            <h2>Activate Um Template</h2>
            <p>Do you use a third party plugin for your email template?</p>
            <div class="checkboxemail" style="padding:0px;width:100%">
                <fieldset id="um_options-checkmail_email_on" class="redux-field-container redux-field redux-container-switch" data-id="checkmail_email_on" data-type="switch">
                    <div class="switch-options">
                        <?php if($var['checkboxemail'] == 'on'){ ?>
                        <label class="cb-enable selected" data-id="checkmail_email_on">
                        <span>Yes</span>
                        </label>
                        <label class="cb-disable" data-id="checkmail_email_on">
                        <span>No</span>
                        </label>
                        <?php }else{?>
                        <label class="cb-enable" data-id="checkmail_email_on">
                        <span>Yes</span>
                        </label>
                        <label class="cb-disable selected" data-id="checkmail_email_on">
                        <span>No</span>
                        </label>
                        <?php } ?>
                    </div>
                </fieldset>
                <input name="umcheckmail" style="display:none;" id="checkboxemail" value="yes" placeholder="" type="checkbox" <?php if(isset($var['checkboxemail']) == 'on'){?> checked="checked" <?php } ?>>							
            </div>
        </div>
        <div class="col-sm-6" style="padding-bottom: 25px;">
            <h2>Send Email</h2>
            <p>Check your email template to see how it looks</p>
            <div class="input-group">
                <?php $touser = get_option( 'admin_email' ); ?>
                <input type="text" name="test_from_mail" class="form-control" value="" placeholder="your@email.com">
                <span class="input-group-btn">
                <button class="btn btn-secondary" type="submit" value="Send" name="send_test_mail">Send</button>
                </span>
            </div>
        </div>
    </div>
    <input type="submit" name="email_template" class="btn btn-info pull-right" value="SAVE SETTINGS" style="margin-top: 15px; background-color: #000; border-radius: 0; color: #fff; padding: 10px 12px; border-color: #000;">
    <hr>
</form>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#removeimage").click(function(){
			jQuery(".plugin_hdr_img").hide();

		});
		jQuery(".cb-disable").click(function(){
			jQuery(this).addClass('selected');
			jQuery(".cb-enable").removeClass('selected');
			jQuery("#checkboxemail").val("no");
			jQuery("#checkboxemail").removeAttr('checked');
		});
		jQuery(".cb-enable").click(function(){
			jQuery(this).addClass('selected');
			jQuery(".cb-disable").removeClass('selected');
			jQuery("#checkboxemail").val("yes");
			jQuery("#checkboxemail").attr('checked','checked');
		});

	});
	document.getElementById("uploadBtn").onchange = function () {
    document.getElementById("uploadFile").value = this.value;
};
(function( $ ) {
	// Add Color Picker to all inputs that have 'color-field' class
	$(function() {
	$('.colorpick').wpColorPicker();
	});
})( jQuery );
</script>
<script type="text/javascript">
	jQuery(document).ready(function(){

  var mediaUploader;

  jQuery('#upload-button').click(function(e) {
    e.preventDefault();
    // If the uploader object has already been created, reopen the dialog
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    // Extend the wp.media object
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });

    // When a file is selected, grab the URL and set it as the text field's value
    mediaUploader.on('select', function() {
      attachment = mediaUploader.state().get('selection').first().toJSON();
      jQuery('#image-url').val(attachment.url);
    });
    // Open the uploader dialog
    mediaUploader.open();
  });

});
</script>
<div style="margin-top: 55px;">
  <?php include 'dashboard/footer.php';?>   
</div>