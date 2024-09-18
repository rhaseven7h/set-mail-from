<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

if ( ! defined( 'SET_MAIL_FROM_VERSION' ) ) {
	define( 'SET_MAIL_FROM_VERSION', '1.0' );
}

if ( ! defined( 'SET_MAIL_FROM_PATH' ) ) {
	define( 'SET_MAIL_FROM_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SET_MAIL_FROM_URL' ) ) {
	define( 'SET_MAIL_FROM_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'SET_MAIL_FROM_PLUGIN_REL_PATH' ) ) {
	define( 'SET_MAIL_FROM_PLUGIN_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
}
