<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

error_log( "Set Mail From plugin start ..." );

require_once 'constants.php';

if ( ! class_exists( 'Set_Mail_From' ) ) {
	class Set_Mail_From {
		public static mixed $options;
		private static Set_Mail_From_Renderer $renderer;

		public function __construct() {
			load_plugin_textdomain(
				'set-mail-from',
				false,
				SET_MAIL_FROM_PLUGIN_REL_PATH . '/languages'
			);

			self::$options = get_option(
				'set-mail-from-options',
				array(
					"from-email" => "",
					"from-name"  => ""
				)
			);

			self::$renderer = new Set_Mail_From_Renderer( SET_MAIL_FROM_PATH . '/views/mustache' );

			new Set_Mail_From_Styles();

			new Set_Mail_From_Settings_Page(
				self::$renderer,
				self::$options['from-email'],
				self::$options['from-name']
			);
		}

		public static function activate(): void {
			flush_rewrite_rules();
			update_option( 'rewrite_rules', '' );
		}

		public static function deactivate(): void {
			update_option( 'rewrite_rules', '' );
			flush_rewrite_rules();
		}

		public static function uninstall(): void {
			delete_option( 'set-mail-from-options' );
		}
	}
}

error_log( "Set Mail From plugin loading ..." );

if ( class_exists( 'Set_Mail_From' ) ) {
	register_activation_hook( __FILE__, array( 'Set_Mail_From', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Set_Mail_From', 'deactivate' ) );
	register_uninstall_hook( __FILE__, array( 'Set_Mail_From', 'uninstall' ) );
	new Set_Mail_From();
}

error_log( "Set Mail From plugin loaded" );
