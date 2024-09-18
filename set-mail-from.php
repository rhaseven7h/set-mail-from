<?php

/**
 * Plugin Name: Set Mail From
 * Plugin URI: https://rhaseven7h.com/wordpress-set-mail-from-plugin/
 * Description: A simple plugin for WordPress to change the “Mail From” email and name.
 * Version: 1.0.0
 * Author: Gabriel Medina
 * Author URI: http://rhaseven7h.com/author/gmedina
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: set-mail-from
 * Domain Path: /languages
 * Requires at least: 5.6
 */

/**
 * Set Mail From is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Set Mail From is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MV Slider. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
 */

error_log( "HERE! 1" );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

require_once plugin_dir_path( __FILE__ ) . 'class.set-mail-from.php';
