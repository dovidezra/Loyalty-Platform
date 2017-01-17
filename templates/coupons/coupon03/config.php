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

	"navbar_text" => "Food Coupon",
	"navbar_text_font" => "'Lobster', cursive",
  "navbar_background_color" => "#49c5b6",
  "navbar_text_color" => "#ffffff",
  "navbar_text_size" => 28,

	"coupon_title_text" => "2-for-1 Dinner",
	"coupon_title_font" => "'Lobster', cursive",
	"coupon_title_color" => "#000000",
	"coupon_title_size" => 32,
 
	"coupon_description_text" => "For two persons.",
	"coupon_description_font" => "'Open Sans', sans-serif",
	"coupon_description_color" => "#000000",
	"coupon_description_size" => 22,

  "header_image_full_width" => false,
  "header_image1" => url('templates/assets/images/coupons/headers/discount-50.png'),

  "button_text" => "Redeem",
  "button_text_font" => "'Open Sans', sans-serif",
  "button_background_color" => "#4caf50",
  "button_background_color_hover" => "#36973a",
  "button_text_color" => "#ffffff",
  "button_text_size" => 26,

  "border_color" => "#000000",
  "qr_color" => "#115c53",

  "coupon_background_color" => "#85c8c5",
  "coupon_background_image" => url('templates/assets/images/coupons/backgrounds/restaurant_icons.png'),
  "coupon_background_image_repeat" => true,

  "background_color" => "#ffffff",
  "background_image" => "",
  "background_image_repeat" => true,
];