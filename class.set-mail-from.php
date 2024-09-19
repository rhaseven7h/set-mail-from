<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

require_once 'constants.php';
require_once 'class.set-mail-from-renderer.php';
require_once 'class.set-mail-from-styles.php';
require_once 'class.set-mail-from-settings-page.php';

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

			$senderEmail = self::$options['from-email'];
			$senderName  = self::$options['from-name'];
			if ( ! empty( $senderEmail ) && ! empty( $senderName ) ) {
				add_filter( 'wp_mail_from',
					function ()
					use ( $senderEmail ) {
						return $senderEmail;
					}
				);
				add_filter( 'wp_mail_from_name',
					function ()
					use ( $senderName ) {
						return $senderName;
					}
				);
			}
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

if ( class_exists( 'Set_Mail_From' ) ) {
	register_activation_hook( __FILE__, array( 'Set_Mail_From', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Set_Mail_From', 'deactivate' ) );
	register_uninstall_hook( __FILE__, array( 'Set_Mail_From', 'uninstall' ) );
	new Set_Mail_From();
}
