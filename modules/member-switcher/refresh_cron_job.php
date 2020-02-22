<?php 
	if ( ! defined( 'ABSPATH' ) )
	exit;
	//var_dump(UM()->roles()); 
    //global $post,$ultimatemember,$wpdb,$woocommerce,$product;
	global $post,$wpdb,$woocommerce,$product;
	//$ultimatemember = $GLOBALS['ultimatemember'];
	//$GLOBALS['ultimatemember'] = UM();
	
    $prefix = $wpdb->prefix;    
    $base_prefix = $wpdb->base_prefix;
    $table_woo = ""; 
    $result=$wpdb->get_results("SELECT * FROM ".$base_prefix."users JOIN ".$prefix."um_switcher ON ".$base_prefix."users.ID = ".$prefix."um_switcher.user_id");  
   
    foreach($result as $row){       
      
        date_default_timezone_set('Europe/London');
        $loop['expire_time'] = $row->expire_time; 
        $loop['today_time'] = @$row->today_time; 
        $loop['selectbefore'] = $row->selectbefore; 
        $loop['selectafter'] = $row->selectafter;
        $loop['product_id'] = $row->product_id;
		$loop['UmWoTD_subscription_type'] = $row->UmWoTD_subscription_type;
		$loop['subscription_type_end_date'] = $row->subscription_type_end_date;

		
        $user_id = $loop['user_id'] = $row->user_id;
        $product_id = $loop['product_id'];
        $orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
            'numberposts' => -1, // -1 for all orders
            'meta_key'    => '_customer_user',
            'meta_value'  => $user_id,
            'post_type'   => 'shop_order',
            'post_status' => 'processing'
        ) ) );

        $status = array();
		$status['value']="";
        foreach ($orders as $value) {
            $status['value'] =  $value->post_status;
        }

        $order_status = $status['value'];   
       
        date_default_timezone_set('Europe/London');   
        $todays_date =date("Y-m-d H:i:s");
		
		if($loop['UmWoTD_subscription_type'] == 2) {
			$exp_date = $loop['subscription_type_end_date'];      
		} else {
			$exp_date = $loop['expire_time'];      
		}
       
        $date1=date_create($todays_date);
        $date2=date_create($exp_date);   
        
        $diff=date_diff($date1,$date2);

        $diff->format("%R%a days and %R%h hours and %R%i Minutes");
        $remday = $diff->format("%R%a");
        $remhour = $diff->format("%R%h");
        $remmin = $diff->format("%R%i");
        
        $reminder_day1 = get_post_meta( $product_id, 'reminder_day1', true );
        $reminder_day2 = get_post_meta( $product_id, 'reminder_day2', true );
        $reminder_hours1 = get_post_meta( $product_id, 'reminder_hours1', true );
        $reminder_hours2 = get_post_meta( $product_id, 'reminder_hours2', true );
        $reminder_min1 = get_post_meta( $product_id, 'reminder_min1', true );
        $reminder_min2 = get_post_meta( $product_id, 'reminder_min2', true );

        $reminder_subject1 = get_post_meta( $product_id, 'reminder_subject1', true );
        $reminder_subject2 = get_post_meta( $product_id, 'reminder_subject2', true );
        $reminder_msg1 = get_post_meta( $product_id, 'reminder_msg1', true );
        $reminder_msg2 = get_post_meta( $product_id, 'reminder_msg2', true );
		
		$UmWoTD_subscription_type = get_post_meta( $product_id, 'UmWoTD_subscription_type', true );
		//$subscription_type_end_date = get_post_meta( $product_id, 'subscription_type_end_date', true );
		
        $rem1 = get_post_meta( $product_id, 'rem1', true );
        $rem2 = get_post_meta( $product_id, 'rem2', true );    
        $current_user = get_user_by('id', $loop['user_id']);
        $user_name = $current_user->display_name;      
        $email = $current_user->user_email;

        $siteurl = get_site_url();       
        $email_template = $wpdb->get_results("SELECT * FROM ".$prefix."email_template");      
        $emailvar = array();
        foreach($email_template as $emailrow){
          $emailvar['from_name'] = $emailrow->from_name;  
          $emailvar['from_address'] = $emailrow->from_address;
          $emailvar['header_image'] = $emailrow->header_image;
          $emailvar['footer_text'] = $emailrow->footer_text;
          $emailvar['base_color'] = $emailrow->base_color;
          $emailvar['bg_color'] = $emailrow->bg_color;
          $emailvar['body_bg_color'] = $emailrow->body_bg_color;
          $emailvar['body_txt_color'] = $emailrow->body_txt_color;
        $emailvar['checkboxemail'] = $emailrow->checkboxemail;
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

        $replacements1 = array("[user]" => $user_name,"[new_line]" => "<br/><br/>","[site_name]" => $emailvar['from_name'],"[days]"=>$reminder_day1.'-'.$setday1.' '.$reminder_hours1.'-'.$sethour1.' '.$reminder_min1.'-'.$setmin1);

        $replacements2 = array("[user]" => $user_name,"[new_line]" => "<br/><br/>","[site_name]" => $emailvar['from_name'],"[days]"=>$reminder_day2.'-'.$setday2.' '.$reminder_hours2.'-'.$sethour2.' '.$reminder_min2.'-'.$setmin2);
        $replacesub1 = array("[site_name]" => $emailvar['from_name']);
        $replacesub2 = array("[site_name]" => $emailvar['from_name']);

        $resultmsg1 = strtr($reminder_msg1, $replacements1);
        $resultmsg2 = strtr($reminder_msg2, $replacements2);
        $resultsub1 = strtr($reminder_subject1, $replacesub1);
        $resultsub2 = strtr($reminder_subject2, $replacesub2);

        if($rem1 == "send"){ 
           //if($remday <= $reminder_day1 && $remhour <= $reminder_hours1 && $remmin <= $reminder_min1 && $UmWoTD_subscription_type == 1){ 
		   if($remday <= $reminder_day1 && $remhour <= $reminder_hours1 && $remmin <= $reminder_min1){ 
                $table_woo = $wpdb->prefix.'um_switcher';           
                $to = $email;     
                $subject = $resultsub1;    
                $imagesrc = '<img src="'.$emailvar['header_image'].'" style="width:650px;height:350px;">';
$message1 = "";

if($emailvar['checkboxemail'] == 'on'){
   $message1 = $resultmsg1;   
}
if($emailvar['checkboxemail'] == 'off'){
   $message1 = '<html><style>
		/*
        @media (min-height:1050px) and (max-height:2500px){#body{  height: 100% !important;}} 
        @media (min-width:1400px) and (max-width:2500px){#body{  height: 100% !important;}}
		*/
        #body{  height: 100% !important; width:80%;} 
		</style>
            <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
                <div id="body" style="background-color:'.$emailvar['base_color'].';width:100%;-webkit-text-size-adjust:none !important;margin:0;height:auto;padding:0px;">';

$message1 .= "<table border='0' cellpadding='0' cellspacing='0' width='680' id='template_container' style='-webkit-box-shadow:none !important;box-shadow:none !important;-webkit-border-radius:6px !important;border-radius:6px !important;background-color:".$emailvar['base_color'].";border:none;width: 80% !important;text-align:center;margin:0 auto;padding:70px 0px;'>";

$message1 .=  "<tr><td align='center' valign='top'>";

$message1 .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_header' style='background-color: ".$emailvar['bg_color'].";color: #f1f1f1;-webkit-border-top-left-radius:6px !important;-webkit-border-top-right-radius:6px !important;border-top-left-radius:6px !important;border-top-right-radius:6px !important;border-bottom: 0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle;'>
                <tr>
                    <td>
                        <h1 style='color: #000000;margin:0;padding: 28px 24px;display:block;font-family:Arial;font-size: 30px;font-weight:bold;text-align:center;line-height: 150%;' id='logo'>
                        <a style='color: #000000;text-decoration: none;'' href='$siteurl' title=".$emailvar['from_name']."'>$imagesrc</a>
                        </h1>
                    </td>
                </tr>
            </table>";

$message1 .= "</td></tr>";

$message1 .= "<tr>
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

$message1 .= "<tr>
                <td align='center' valign='top'>
                    <table border='0' cellpadding='10' cellspacing='0' width='100%' id='template_footer' style='border-top:1px solid #E2E2E2;background: #ffffff;-webkit-border-radius:0px 0px 6px 6px;-o-border-radius:0px 0px 6px 6px;-moz-border-radius:0px 0px 6px 6px;border-radius:0px 0px 6px 6px;'>
                            <tr>
                                <td valign='top'>
                                    <table border='0' cellpadding='10' cellspacing='0' width='100%'>
                                        <tr>
                                            <td colspan='2' valign='middle' id='credit' style='border:0;color:#000000;font-family: Arial;font-size: 10px;line-height:125%;text-align:left;'>                                
                                                ".$emailvar['footer_text']."
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
         
                $sender =  $emailvar['from_address'];      
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";        
                $headers .= 'From: '.$emailvar['from_name'].' <'.$emailvar['from_address'].'>' . "\r\n";
                //$headers .= 'Bcc: '.$emailvar['from_address'].'' . "\r\n";             
                
                $flag1 = $wpdb->get_results( "SELECT * FROM ".$prefix."um_switcher WHERE user_id = $user_id");
                                   
                foreach($flag1 as $rowf){   
                    $loop1['flag1']=$rowf->flag1;
                    $loop1['product_id']=$rowf->product_id;               
                }  
                if($loop1['flag1'] == "0"){
                    $table_woo = $wpdb->prefix.'um_switcher';
                   if(wp_mail($to, $subject, $message1, $headers)){
                    $wpdb->update($table_woo, array('flag1'=>1),array( 'product_id' =>$loop1['product_id'],'user_id'=>$user_id), 
                                        array('%d'), array( '%d','%d' ) );
                   
                   }
                }

               
            }
        }
        if($rem2 == "send"){
            //if($remday <= $reminder_day2 && $remhour <= $reminder_hours2 && $remmin <= $reminder_min2 && $UmWoTD_subscription_type == 1){  
			if($remday <= $reminder_day2 && $remhour <= $reminder_hours2 && $remmin <= $reminder_min2){  
                $to = $email;     
                $subject = $resultsub2;    
                /*new code for email template start*/               
                $imagesrc = '<img src="'.$emailvar['header_image'].'" style="width:650px;height:350px;">';
$message2 = "";
if($emailvar['checkboxemail'] == 'on'){
   $message2 = $resultmsg2;   
}
if($emailvar['checkboxemail'] == 'off'){
$message2 = "";
$message2 = '<html><style>
		/*
        @media (min-height:1050px) and (max-height:2500px){#body{  height: 100% !important;}} 
        @media (min-width:1400px) and (max-width:2500px){#body{  height: 100% !important;}}
		*/
        #body{  height: 100% !important; width:80%;} 
        </style>
		
            <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
                <div id="body" style="background-color:'.$emailvar['base_color'].';width:100%;-webkit-text-size-adjust:none !important;margin:0;height:auto;padding:0px;">';

$message2 .= "<table border='0' cellpadding='0' cellspacing='0' width='680' id='template_container' style='-webkit-box-shadow:none !important;box-shadow:none !important;-webkit-border-radius:6px !important;border-radius:6px !important;background-color:".$emailvar['base_color'].";border:none;width:80% !important;text-align:center;margin:0 auto;padding:70px 0px;'>";

$message2 .=  "<tr><td align='center' valign='top'>";

$message2 .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' id='template_header' style='background-color: ".$emailvar['bg_color'].";color: #f1f1f1;-webkit-border-top-left-radius:6px !important;-webkit-border-top-right-radius:6px !important;border-top-left-radius:6px !important;border-top-right-radius:6px !important;border-bottom: 0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle;'>
                <tr>
                    <td>
                        <h1 style='color: #000000;margin:0;padding: 28px 24px;display:block;font-family:Arial;font-size: 30px;font-weight:bold;text-align:center;line-height: 150%;' id='logo'>
                        <a style='color: #000000;text-decoration: none;'' href='$siteurl' title=".$emailvar['from_name']."'>$imagesrc</a>
                        </h1>
                    </td>
                </tr>
            </table>";

$message2 .= "</td></tr>";

$message2 .= "<tr>
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

$message2 .= "<tr>
                <td align='center' valign='top'>
                    <table border='0' cellpadding='10' cellspacing='0' width='100%' id='template_footer' style='border-top:1px solid #E2E2E2;background: #ffffff;-webkit-border-radius:0px 0px 6px 6px;-o-border-radius:0px 0px 6px 6px;-moz-border-radius:0px 0px 6px 6px;border-radius:0px 0px 6px 6px;'>
                            <tr>
                                <td valign='top'>
                                    <table border='0' cellpadding='10' cellspacing='0' width='100%'>
                                        <tr>
                                            <td colspan='2' valign='middle' id='credit' style='border:0;color:#000000;font-family: Arial;font-size: 10px;line-height:125%;text-align:left;'>                                
                                                ".$emailvar['footer_text']."
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
                $sender =  $emailvar['from_address'];     

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";        
                $headers .= 'From: '.$emailvar['from_name'].' <'.$emailvar['from_address'].'>' . "\r\n";
                //$headers .= 'Bcc: '.$emailvar['from_address'].'' . "\r\n";  

                $flag2 = $wpdb->get_results( "SELECT * FROM ".$prefix."um_switcher WHERE user_id = $user_id");
                $loop1 = array(); 
                /*$user_id = get_current_user_id(); */      
                foreach($flag2 as $rowf){   
                    $loop1['flag2']=$rowf->flag2;
                    $loop1['product_id']=$rowf->product_id;
                    $loop1['user_id']=$rowf->user_id;               
                }  
                if($loop1['flag2'] == "0"){ 
                    $table_woo = $wpdb->prefix.'um_switcher';
                    if(wp_mail($to, $subject, $message2, $headers)){
                    $wpdb->update($table_woo, array('flag2'=>1),array( 'product_id' =>$loop1['product_id'],'user_id'=>$user_id), array('%d'), array( '%d','%d' ) );
                   
                   }
                }           
               
                          
            }
        }

        $final_day = $diff->format("%R%a");
        $final_hour = $diff->format("%R%h");
        $final_mins = $diff->format("%R%i");

        if($final_day <= 0 && $final_hour <= 0 && $final_mins <= 0){  
            um_fetch_user($user_id ); 
            //$loop['selectafter'];     
			wp_update_user( array( 'ID' => $user_id, 'role' =>$loop['selectafter'] ) );
			//$ultimatemember->user->set_role($loop['selectafter']);
			//UM()->roles()->set_role( $user_id, $loop['selectafter'] );
            
            if(isset($loop1['product_id'])){
              $wpdb->update($table_woo, array('user_status'=>$loop['selectafter']),array( 'product_id' =>$loop1['product_id'],'user_id'=>$user_id), array('%s'), array( '%d','%d' ) );    
            }  
			
        }
		else{
			//um_bronze-member
            if($order_status == 'wc-pending' || $order_status == 'wc-on-hold' || $order_status == 'wc-processing' || $order_status == 'wc-failed' || $order_status == 'wc-refunded' || $order_status == 'wc-cancelled')
			{
				um_fetch_user( $user_id );
				wp_update_user( array( 'ID' => $user_id, 'role' =>$loop['selectafter'] ) );
            }
			else
			{
                um_fetch_user( $user_id );
				wp_update_user( array( 'ID' => $user_id, 'role' =>$loop['selectbefore'] ) ); //looks like not working..
				if(isset($loop1['product_id'])){
				  $wpdb->update($table_woo, array('user_status'=>$loop['selectbefore']),array( 'product_id' =>$loop1['product_id'],'user_id'=>$user_id), array('%s'), array( '%d','%d' ) );    
				} 				
            }
		}
		$counter++;
    }

?>