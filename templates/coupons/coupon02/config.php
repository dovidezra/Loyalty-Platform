<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Template configuration
	|--------------------------------------------------------------------------
	|
	| These variables are overriden by settings and can be used like $coupon->navbar_text.
	|
	*/

  // When creating a new item, this is used for the name like "Coupon 1"
	"name_prefix" => "Coupon",

	"navbar_text" => "Special Offer",
	"navbar_text_font" => "'Bungee Inline', cursive",
  "navbar_background_color" => "#3F51B5",
  "navbar_text_color" => "#ffffff",
  "navbar_text_size" => 20,

	"coupon_title_text" => "Save $5",
	"coupon_title_font" => "'Bungee Inline', cursive",
	"coupon_title_color" => "#000000",
	"coupon_title_size" => 32,
 
	"coupon_description_text" => "Including sale items.",
	"coupon_description_font" => "'Open Sans', sans-serif",
	"coupon_description_color" => "#000000",
	"coupon_description_size" => 22,

  "header_image_full_width" => false,
  "header_image1" => url('templates/assets/images/coupons/headers/special-offer.png'),

  "button_text" => "Redeem",
  "button_text_font" => "'Open Sans', sans-serif",
  "button_background_color" => "#4caf50",
  "button_background_color_hover" => "#36973a",
  "button_text_color" => "#ffffff",
  "button_text_size" => 26,

  "border_color" => "#3F51B5",
  "qr_color" => "#3F51B5",

  "coupon_background_color" => "#ffffff",
  "coupon_background_image" => url('templates/assets/images/coupons/backgrounds/swirl_pattern.png'),
  "coupon_background_image_repeat" => true,

  "background_color" => "#000000",
  "background_image" => url('templates/assets/images/coupons/backgrounds/dark_wood.png'),
  "background_image_repeat" => true,
];