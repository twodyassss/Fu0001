<?php
class WC_Product_Um_Switcher extends WC_Product {
	public function __construct( $product ) {
		$this->product_type = 'um_switcher';
		parent::__construct( $product );		
	}	
}