<?php
/**
 * @package red_instagramFollowersShortcode
*/
/*
Plugin Name: Instagram Followers Shortcode
Plugin URI: http://crayfishseo.com
Description: Instagram Followers Shortcode for Wordpress.
Version: 1.0
Author: Charles Li
Author URI: http://crayfishseo.com
*/
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
add_shortcode('instagramFollowers', 'instagramFollowersShortcode');
 
 function instagramFollowersShortcode($atts){
 	$atts = shortcode_atts(array(
 		'id' => '25025320',
 		'limit' => '20',
 		'width' => '500',
 		'supportAuthor' => 'true'
 	), $atts);
 	extract($atts);
 	$data = "";
 	$data .= "
 			<div class='red_instagramFollowersWidget$suffix'>
            	<div id='instagramFollowers' style='max-width: $width
 			";
 	$data .= "px;'";
 	if( ini_get('allow_url_fopen') && function_exists('openssl_open') ) {
 		$data .= instagramShortcode($id, $limit);
 	}else {
 		$data .= "Check your php.ini and make sure allow_url_fopen & openssl function is set to on.";
 	}
 	$data .= "
 				</div>";
 	if ($supportAuthor == "true") :
 	$data .= "<div style='font-size: 9px; color: #808080; font-weight: normal; font-family: tahoma,verdana,arial,sans-serif; line-height: 1.28; text-align: right; direction: ltr;'><a href='http://www.empirepromos.com/' target='_blank' style='color: #808080;' title='click here'>Promotional Products</a></div>";
 	endif;
 	$data .= "
 			</div>";
 	
 	return $data;
 }
 /**
  * Register style sheet.
  */
 function register_plugin_styles() {
 	wp_register_style( 'instagramFollowersShortcode', plugins_url( 'style.css' , __FILE__ ) );
 	wp_enqueue_style( 'instagramFollowersShortcode' );
 }
 function instagramShortcode($instagramID, $limit){
 	$accessToken = "445731305.f59def8.b478a2249aca4adf955ee0ebbfa6b31a";
 	if(empty($instagramID)) return false;
 	$userInfo = getUserInfo($instagramID, $accessToken);
 	$userInfoData= $userInfo['data'];
 	extract($userInfoData);
 	$followedBy = $counts['followed_by'];
 	$output = "";
 	$output .= "
 	<div class='instagram-border'>
 	<div id='followers'>
 	<div class='followUs'>Follow us on Instagram</div>
 	<div class='floatelement'>
 	<div class='thumb-img'><a href='https://instagram.com/$username' target='_blank' title='$username'><img src='$profile_picture'/></a></div>
 	<div class='right-text'><p class='title'><a href='https://instagram.com/$username' target='_blank' title='$username'>$full_name</a></p>
 	<a href='https://instagram.com/$username' target='_blank' title='$username' class='followButton'>Follow</a></div>
 	<div class='clr'></div>
 	</div>
 	<div class='imagelisting'>
 	<p>$followedBy People following <span><a href='https://instagram.com/$username' target='_blank' title='$username'>$full_name</a></span></p>
 	";
 	$followersInfo = getFollowersInfo($instagramID, $accessToken);
 	$followersInfoData= $followersInfo['data'];
 	
 	$output .= "
 	<ul>";
 	for($i=0;$i<$limit;$i++){
 	$postUserName = $followersInfoData[$i]['username'];
 		$postFullName = $followersInfoData[$i]['full_name'];
 		$postProfilePicture = $followersInfoData[$i]['profile_picture'];
 		$output .= "
 		<li><a href='https://instagram.com/$postUserName' title='$postFullName' target='_blank'><img src='$postProfilePicture'></a></li>";
 	}
 	$output .= "<div class='clr'></div></ul></div>
 	</div>
 	</div>";
 		return $output;
 }
 function getUserInfo($instagramID,$accessToken){
 	$instagramUserInfo = "https://api.instagram.com/v1/users/$instagramID/?access_token=$accessToken";
 	$userInfo = json_decode(file_get_contents($instagramUserInfo),true);
 	//if found any error than return false
 	if(isset($userInfo->error)) return false;
 	else return $userInfo;
 
 }
 function getFollowersInfo($instagramID,$accessToken){
 	$instagramFollowers = "https://api.instagram.com/v1/users/$instagramID/followed-by?access_token=$accessToken";
 	$followersInfo = json_decode(file_get_contents($instagramFollowers),true);
 	//if found any error than return false
 	if(isset($followersInfo->error)) return false;
 	else return $followersInfo;
 
 }