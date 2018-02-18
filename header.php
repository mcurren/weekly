<?php 
require_once( dirname(__FILE__) . '/functions.php' ); ?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Weekly Bounty - <?= $page_title ?></title>
	<meta name="description" content="<?= $page_lead ?>">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<style type="text/css">
		.navbar-brand img {
      transition: transform 0.23s;
		}
		.navbar-brand:hover img {
			transform: rotate(180deg);
		}
		.card-header .date__month {
			font-size: 0.4em;
			line-height: 1;
		}
		.card-header .date__day {
			font-size: 0.7em;
			line-height: 1;
			font-weight: 300;
		}
	</style>
</head>
<body>

<header id="masthead" class="">
	<nav class="navbar navbar-expand-md navbar-dark bg-info box-shadow">
	  <a class="navbar-brand" href="/">
	  	<img src="hourglass.svg" width="33" height="30" class="d-inline-block align-top mr-0" alt="harvest horn of plenty"> 
	  	Weekly Bounty
	  </a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>

	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<?php 
				foreach ($nav_items as $title => $slug) {
					$add_class = ( $page_slug == $slug ) ? ' active' : '';
					echo '<li class="nav-item"><a class="nav-link' . $add_class . '" href="' . $slug . '">' . $title . '</a></li>';
				} ?>
			</ul>
		</div>
	</nav>
</header>

<main>
