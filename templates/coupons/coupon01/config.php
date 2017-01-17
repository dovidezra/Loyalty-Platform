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

	"navbar_text" => "My Business",
	"navbar_text_font" => "'Open Sans', sans-serif",
  "navbar_background_color" => "#e91e63",
  "navbar_text_color" => "#ffffff",
  "navbar_text_size" => 20,

	"coupon_title_text" => "Limited Time Offer",
	"coupon_title_font" => "'Open Sans', sans-serif",
	"coupon_title_color" => "#000000",
	"coupon_title_size" => 22,
 
	"coupon_description_text" => "Take 20% off your next purchase!",
	"coupon_description_font" => "'Open Sans', sans-serif",
	"coupon_description_color" => "#000000",
	"coupon_description_size" => 16,

  "header_image_full_width" => false,
  "header_image1" => url('templates/assets/images/coupons/headers/sale.png'),

  "button_text" => "Redeem",
  "button_text_font" => "'Open Sans', sans-serif",
  "button_background_color" => "#4caf50",
  "button_background_color_hover" => "#36973a",
  "button_text_color" => "#ffffff",
  "button_text_size" => 26,

  "border_color" => "#222222",
  "qr_color" => "#000000",

  "coupon_background_color" => "#ffffff",
  "coupon_background_image" => url('templates/assets/images/coupons/backgrounds/wov.png'),
  "coupon_background_image_repeat" => true,

  "background_color" => "#ffffff",
  "background_image" => url('templates/assets/images/coupons/backgrounds/tileable_wood_texture.png'),
  "background_image_repeat" => true,
];