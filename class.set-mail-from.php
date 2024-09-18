<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

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
					"from-email" => null,
					"from-name"  => null
				)
			);

			self::$renderer = new Set_Mail_From_Renderer( SET_MAIL_FROM_PATH . '/views/mustache' );
		}
	}
}

if ( class_exists( 'Set_Mail_From' ) ) {
	new Set_Mail_From();
}
