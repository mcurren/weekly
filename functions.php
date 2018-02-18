<?php 
// require composer
require_once( dirname(__FILE__) . '/vendor/autoload.php' );

// load required config variables
$dotenv = new Dotenv\Dotenv(__DIR__, 'config.env');
$dotenv->load();
$dotenv->required('LOGIN_EMAIL')->notEmpty();
$dotenv->required('LOGIN_PASSWORD')->notEmpty();
$dotenv->required('LOGIN_SLUG')->notEmpty();
$dotenv->required('USE_SSL')->notEmpty();
$dotenv->required('USER_ID')->isInteger();
$dotenv->required('DAILY_TARGET')->isInteger();
$dotenv->required('TIME_ZONE')->notEmpty();

$login_email = getenv('LOGIN_EMAIL');
$login_password = getenv('LOGIN_PASSWORD');
$login_slug = getenv('LOGIN_SLUG');
$use_ssl = getenv('USE_SSL');
$user_id = getenv('USER_ID');
$daily_target = getenv('DAILY_TARGET');
$time_zone = getenv('TIME_ZONE');
$dashboard_url = "https://$login_slug.harvestapp.com/";


/**
 * HaPi - PHP wrapper library for the Harvest API
 * @link https://github.com/mdbitz/HaPi
 */

// require the Harvest API core class 
require_once( dirname(__FILE__) . '/lib/harvest/HarvestAPI.php' );

// register the class auto loader 
spl_autoload_register( array('HarvestAPI', 'autoload') );

// instantiate the api object 
$api = new HarvestAPI();
$api->setUser( $login_email );
$api->setPassword( $login_password );
$api->setAccount( $login_slug );
$api->setSSL( $use_ssl );

// retry mode for objects returned with 503 status code
$api->setRetryMode( HarvestAPI::RETRY );


/**
 * Header variables
 */

// meta description
$page_lead = ($page_lead) ? $page_lead : 'Stay on track with your billable hours reporting using the Harvest API.';

// nav menu items  $page_title => $page_slug
$nav_items = array(
	'This Week' => '/', 
	'Last Week' => '/last-week.php',
	'Today' => '/today.php', 
); 
?>