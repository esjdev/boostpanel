<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Error</title>
	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:500" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Titillium+Web:700,900" rel="stylesheet">

	<style type="text/css">
		* {
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
		}

		body {
			padding: 0;
			margin: 0;
		}

		#notfound {
			position: relative;
			height: 100vh;
		}

		#notfound .notfound {
			position: absolute;
			left: 50%;
			top: 50%;
			-webkit-transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
		}

		.notfound {
			max-width: 767px;
			width: 100%;
			line-height: 1.4;
			padding: 0px 15px;
		}

		.notfound .notfound-404 {
			position: relative;
			height: 150px;
			line-height: 150px;
			margin-bottom: 25px;
		}

		.notfound .notfound-404 h1 {
			font-family: 'Titillium Web', sans-serif;
			font-size: 186px;
			font-weight: bold;
			margin: 0px;
			text-transform: uppercase;
			background-size: cover;
			background-position: center;
		}

		.notfound h2 {
			font-family: 'Titillium Web', sans-serif;
			font-size: 26px;
			font-weight: 700;
			margin: 0;
		}

		.notfound p {
			font-family: 'Montserrat', sans-serif;
			font-size: 14px;
			font-weight: 500;
			margin-bottom: 0px;
			text-transform: uppercase;
		}

		.notfound a {
			font-family: 'Titillium Web', sans-serif;
			display: inline-block;
			text-transform: uppercase;
			color: #fff;
			text-decoration: none;
			border: none;
			background: #5c91fe;
			padding: 10px 40px;
			font-size: 14px;
			font-weight: 700;
			border-radius: 1px;
			margin-top: 15px;
			-webkit-transition: 0.2s all;
			transition: 0.2s all;
		}

		.notfound a:hover {
			opacity: 0.8;
		}

		@media only screen and (max-width: 767px) {
			.notfound .notfound-404 {
				height: 110px;
				line-height: 110px;
			}

			.notfound .notfound-404 h1 {
				font-size: 120px;
			}
		}
	</style>
</head>
<body>
<div id="notfound">
	<div class="notfound">
		<div class="notfound-404">
			<h1>Error</h1>
		</div>
		<h2><?php echo $heading; ?></h2>
		<p><?php echo $message; ?></p>
		<a href="/">Go To Homepage</a>
	</div>
</div>
</body>
</html>
