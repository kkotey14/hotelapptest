<?php
// Copy this to config.php (do not commit config.php)
// Fill with your own local values or use environment variables.

define('DB_HOST', 'localhost');
define('DB_NAME', 'hotelapp');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '/hotelapp');

// Branding (optional)
if (!defined('HOTEL_NAME'))  define('HOTEL_NAME', 'The Riverside');
if (!defined('HOTEL_TAGLINE')) define('HOTEL_TAGLINE', 'Boutique stays by the river.');
$HOTEL_AMENITIES = [
  ['icon'=>'🛏️','title'=>'Plush Bedding','text'=>'Down duvets, premium linens'],
  ['icon'=>'📶','title'=>'Fast Wi-Fi','text'=>'Reliable, hotel-wide'],
  ['icon'=>'☕','title'=>'Coffee & Tea','text'=>'In-room Nespresso'],
];
