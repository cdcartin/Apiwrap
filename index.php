<?
/**
 * API Wrapper
 *
 * A simple wrapper for the Facebook PHP SDK that returns the details for a specific public page
 *
 * This package requires the Facebook SDK v5 located here:
 *
 * https://developers.facebook.com/docs/reference/php
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2015 - 2016, Daniel Cartin - Fastbook
 * Copyright (c) Facebook - Facebook SDK
 * 
 * @package	Apiwrap
 * @author	Daniel Cartin
 * @license	http://opensource.org/licenses/MIT	MIT License
 */

// Load the Apiwrap class
require "src/class.Apiwrap.php";

/**
 *	 Create Apiwrap instance
 *	
 *	 When creating a new Apiwrap object, send a Facebook ID as a parameter
 */

$facebook = new Apiwrap('WaterSignal');?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Find us on Facebook</title>
<link rel="stylesheet" href="css/960/reset.css" />
<link rel="stylesheet" href="css/960/960_24_col.css" />
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="topbanner">
	<div class="container_24">
		<div class="grid_8">
			<h1><?=$facebook->name?></h1>
		</div>
		<div class="grid_16">
			<p></p>
		</div>
	</div>
</div>
<div class="container_24">	
	<div class="clear"></div>
	<div class="grid_6">
		<div class="profileimgWrapper">
			<img class="profileimg" src="<?=$facebook->profile_pic?>" />
		</div>
		<div class="findUs">
			<img src="img/FB_FindUsOnFacebook-144.png"/>
		</div>
	</div>
	<div class="grid_18">	
	<?foreach($facebook->feed as $value){?>
		<div class="post grid_16 alpha omega">
			<div class="postTitle">
				<img src="<?=$value['thumb']?>" />
				<h5><?=$facebook->name?></h5>
				<small><?=$value['stamp']?></small>
			</div>
			<div class="postMessage">
				<p><?echo $value['message']?></p>
			</div>
		</div>		
	<?}?>
	</div>
</div>
</body>
</html>
