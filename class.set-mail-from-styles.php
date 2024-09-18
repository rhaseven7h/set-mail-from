<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access allowed' );
}

require_once 'constants.php';

if ( ! class_exists( 'Set_Mail_From_Styles' ) ) {
	class Set_Mail_From_Styles {
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		public function enqueue_scripts(): void {
			wp_enqueue_style(
				'set-mail-from-styles',
				SET_MAIL_FROM_URL . '/assets/styles/styles.dist.css',
				array(),
				SET_MAIL_FROM_VERSION
			);
		}
	}
}
