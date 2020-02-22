jQuery( document ).ready( function() {
	jQuery("#UmWoTD_options").find(".tax").css('display','block');
	jQuery('#product-type').on('change', function() {
	  if( this.value == "um_switcher" ){
	  	jQuery(".general_options").addClass("active");
        jQuery("#shipping_product_data").css('display','none');
		jQuery(".shipping_options").removeClass("active");
		jQuery("#general_product_data").css('display','block');             
	  }
	});
	jQuery("#UmWoTD_options").css('display','none');	
	jQuery(".general_options").show();
	var pro_type = jQuery('#product-type').val();
	if(pro_type == "um_switcher"){	
			jQuery("#shipping_product_data").css('display','none');			
			jQuery('#product-type option:contains("UM-Switcher Product")').prop('selected',true);
			jQuery(".general_options").show();			 
			jQuery(".pricing").css('display','block');
			jQuery(".general_options").addClass("active");
			jQuery(".shipping_options").removeClass("active");
			jQuery("#general_product_data").css('display','block');
			jQuery("#UmWoTD_options").css('display','block');
			
	}
	jQuery('#product-type').change(function(){
		var pro = jQuery('#product-type').val();
		if(pro != "um_switcher"){
			jQuery("#UmWoTD_options").css('display','none');
		}
		if(pro == "um_switcher"){
			jQuery("#shipping_product_data").css('display','none');	
			jQuery(".general_options").show();
			jQuery(".pricing").css('display','block');
			jQuery("#UmWoTD_options").css('display','block');			
		}
	});
});