<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@extend core fields
	***/
	add_filter("um_predefined_fields_hook", 'um_woocommerce_add_field', 100 );
	function um_woocommerce_add_field($fields){

		$fields['woo_total_spent'] = array(
				'title' => __('Total Spent','twodayssss'),
				'metakey' => 'woo_total_spent',
				'type' => 'text',
				'label' => __('Total Spent','twodayssss'),
				'icon' => 'um-faicon-credit-card',
				'edit_forbidden' => 1,
				'show_anyway' => true,
				'custom' => true,
		);

		$fields['woo_order_count'] = array(
				'title' => __('Total Orders','twodayssss'),
				'metakey' => 'woo_order_count',
				'type' => 'text',
				'label' => __('Total Orders','twodayssss'),
				'icon' => 'um-faicon-shopping-cart',
				'edit_forbidden' => 1,
				'show_anyway' => true,
				'custom' => true,
		);
		
		// billing
		$fields['billing_first_name'] = array(
				'title' => __('WC Billing First name','twodayssss'),
				'metakey' => 'billing_first_name',
				'type' => 'text',
				'label' => __('WC Billing First name','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-user'
		);
		
		$fields['billing_last_name'] = array(
				'title' => __('WC Billing Last name','twodayssss'),
				'metakey' => 'billing_last_name',
				'type' => 'text',
				'label' => __('WC Billing Last name','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-user'
		);
		
		$fields['billing_company'] = array(
				'title' => __('WC Billing Company','twodayssss'),
				'metakey' => 'billing_company',
				'type' => 'text',
				'label' => __('WC Billing Company','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-user'
		);
		
		$fields['billing_address_1'] = array(
				'title' => __('WC Billing Address 1','twodayssss'),
				'metakey' => 'billing_address_1',
				'type' => 'text',
				'label' => __('WC Billing Address 1','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['billing_address_2'] = array(
				'title' => __('WC Billing Address 2','twodayssss'),
				'metakey' => 'billing_address_2',
				'type' => 'text',
				'label' => __('WC Billing Address 2','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['billing_city'] = array(
				'title' => __('WC Billing city','twodayssss'),
				'metakey' => 'billing_city',
				'type' => 'text',
				'label' => __('WC Billing city','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['billing_postcode'] = array(
				'title' => __('WC Billing postcode','twodayssss'),
				'metakey' => 'billing_postcode',
				'type' => 'text',
				'label' => __('WC Billing postcode','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);

		$fields['billing_country'] = array(
				'title' => __('WC Billing country','twodayssss'),
				'metakey' => 'billing_country',
				'type' => 'select',
				'label' => __('WC Billing country','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['billing_state'] = array(
				'title' => __('WC Billing state','twodayssss'),
				'metakey' => 'billing_state',
				'type' => 'text',
				'label' => __('WC Billing state','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['billing_phone'] = array(
				'title' => __('WC Billing phone','twodayssss'),
				'metakey' => 'billing_phone',
				'type' => 'text',
				'label' => __('WC Billing phone','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-phone'
		);
		
		$fields['billing_email'] = array(
				'title' => __('WC Billing email','twodayssss'),
				'metakey' => 'billing_email',
				'type' => 'text',
				'label' => __('WC Billing email','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-envelope'
		);
		
		
		// Shipping
		$fields['shipping_first_name'] = array(
				'title' => __('WC Shipping First name','twodayssss'),
				'metakey' => 'shipping_first_name',
				'type' => 'text',
				'label' => __('WC Shipping First name','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-user'
		);
		
		$fields['shipping_last_name'] = array(
				'title' => __('WC Shipping Last name','twodayssss'),
				'metakey' => 'shipping_last_name',
				'type' => 'text',
				'label' => __('WC Shipping Last name','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-user'
		);
		
		$fields['shipping_company'] = array(
				'title' => __('WC Shipping Company','twodayssss'),
				'metakey' => 'shipping_company',
				'type' => 'text',
				'label' => __('WC Shipping Company','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-user'
		);
		
		$fields['shipping_address_1'] = array(
				'title' => __('WC Shipping Address 1','twodayssss'),
				'metakey' => 'shipping_address_1',
				'type' => 'text',
				'label' => __('WC Shipping Address 1','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['shipping_address_2'] = array(
				'title' => __('WC Shipping Address 2','twodayssss'),
				'metakey' => 'shipping_address_2',
				'type' => 'text',
				'label' => __('WC Shipping Address 2','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['shipping_city'] = array(
				'title' => __('WC Shipping city','twodayssss'),
				'metakey' => 'shipping_city',
				'type' => 'text',
				'label' => __('WC Shipping city','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['shipping_postcode'] = array(
				'title' => __('WC Shipping postcode','twodayssss'),
				'metakey' => 'shipping_postcode',
				'type' => 'text',
				'label' => __('WC Shipping postcode','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['shipping_country'] = array(
				'title' => __('WC Shipping country','twodayssss'),
				'metakey' => 'shipping_country',
				'type' => 'select',
				'label' => __('WC Shipping country','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['shipping_state'] = array(
				'title' => __('WC Shipping state','twodayssss'),
				'metakey' => 'shipping_state',
				'type' => 'text',
				'label' => __('WC Shipping state','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-map-marker'
		);
		
		$fields['shipping_phone'] = array(
				'title' => __('WC Shipping phone','twodayssss'),
				'metakey' => 'shipping_phone',
				'type' => 'text',
				'label' => __('WC Shipping phone','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-phone'
		);
		
		$fields['shipping_email'] = array(
				'title' => __('WC Shipping email','twodayssss'),
				'metakey' => 'shipping_email',
				'type' => 'text',
				'label' => __('WC Shipping email','twodayssss'),
				'public'   => 1,
				'editable' => 1,
				'icon' => 'um-faicon-envelope'
		);
		
		return $fields;
		
	}
	
	/***
	***	@show total orders
	***/
	add_filter('um_profile_field_filter_hook__woo_order_count', 'um_profile_field_filter_hook__woo_order_count', 99, 2);
	function um_profile_field_filter_hook__woo_order_count( $value, $data ) {
		$output = '';
		global $wpdb;
		$user_id = um_user('ID');
		$count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*)
			FROM $wpdb->posts as posts 
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id 
			WHERE meta.meta_key = '_customer_user' AND     
				  posts.post_type IN ('" . implode( "','", wc_get_order_types( 'order-count' ) ) . "') AND     
				  posts.post_status IN ('" . implode( "','", array('wc-completed') )  . "') AND     
				  meta_value = %d",
			$user_id
		) );
		
		$count = absint($count);
		if ( $count == 1 ) {
			$output = sprintf(__('%s order','twodayssss'), ($count) );
		} else {
			$output = sprintf(__('%s orders','twodayssss'), ($count) );
		}
		
		return $output;
	}
	
	/***
	***	@show total spent
	***/
	add_filter('um_profile_field_filter_hook__woo_total_spent', 'um_profile_field_filter_hook__woo_total_spent', 99, 2);
	function um_profile_field_filter_hook__woo_total_spent( $value, $data ) {
		$output = '';
		
		$output = get_woocommerce_currency_symbol() . number_format( wc_get_customer_total_spent( um_user('ID') ) );
		
		return $output;
	}

	/***
	 *** Save country to WC fields in register
	 ***/
    add_filter( 'um_before_save_filter_submitted', 'um_woocommerce_before_save_filter_submitted', 10, 2 );
    function um_woocommerce_before_save_filter_submitted( $submitted, $args ) {
        if ( $submitted['billing_country'] || $submitted['shipping_country'] ) {
	        $countries = UM()->builtin()->get( 'countries' );

	        if ( $submitted['billing_country'] && strlen( $submitted['billing_country'] ) != 2 ) {
		        $submitted['billing_country'] = array_search( $submitted['billing_country'], $countries );
	        }
	        if ( $submitted['shipping_country'] && strlen( $submitted['shipping_country'] ) != 2  ) {
		        $submitted['shipping_country'] = array_search( $submitted['shipping_country'], $countries );
	        }
        }

        return $submitted;
    }

	/***
	 *** Change country to WC fields in profile
	 ***/
	add_filter( 'um_user_pre_updating_profile_array', 'um_woocommerce_user_pre_updating_profile', 10, 1 );
	function um_woocommerce_user_pre_updating_profile( $to_update ) {
		if ( $to_update['billing_country'] || $to_update['shipping_country'] ) {
			$countries = UM()->builtin()->get( 'countries' );

			if ( $to_update['billing_country'] && strlen( $to_update['billing_country'] ) != 2 ) {
				$to_update['billing_country'] = array_search( $to_update['billing_country'], $countries );
			}
			if ( $to_update['shipping_country'] && strlen( $to_update['shipping_country'] ) != 2 ) {
				$to_update['shipping_country'] = array_search( $to_update['shipping_country'], $countries );
			}
		}

		return $to_update;
	}

	/***
	 *** Enable options pair to WC field country
	 ***/
	add_filter( 'um_select_options_pair', 'um_woocommerce_select_options_pair', 10, 2 );
	function um_woocommerce_select_options_pair( $empty = null, $data ) {
		if ( $data['metakey'] == 'billing_country' || $data['metakey'] == 'shipping_country' ) {
			return true;
		}

		return null;
	}

	/***
	 *** Show full WC country in profile
	 ***/
	add_filter( 'um_view_field_value_select', 'um_woocommerce_view_field', 10, 2 );
	function um_woocommerce_view_field( $res, $data ) {
		if ( strlen( $res ) == 2 && ( $data['metakey'] == 'billing_country' || $data['metakey'] == 'shipping_country' ) ) {
			$countries = UM()->builtin()->get( 'countries' );
			$res = $countries[ $res ];
		}

		return $res;
	}