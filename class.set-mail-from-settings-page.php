<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'constants.php';

if ( ! class_exists( 'Set_Mail_From_Settings_Page' ) ) {
	class Set_Mail_From_Settings_Page {
		private Set_Mail_From_Renderer $renderer;
		private string $email;
		private string $name;

		public function __construct( Set_Mail_From_Renderer $renderer, string $email, string $name ) {
			$this->renderer = $renderer;
			$this->email    = $email;
			$this->name     = $name;
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function admin_menu(): void {
			add_options_page(
				esc_html__( 'Set Mail From', 'set-mail-from' ),
				esc_html__( 'Set Mail From', 'set-mail-from' ),
				'manage_options',
				'set-mail-from',
				array(
					$this,
					'render_admin_options_page'
				)
			);
		}

		public function register_settings(): void {
			register_setting(
				'set-mail-from-option-group',
				'set-mail-from-options',
				array(
					'label'             => esc_html__( 'Set Mail From', 'set-mail-from' ),
					'description'       => esc_html__( 'Settings for the Set Mail From plugin.', 'set-mail-from' ),
					'sanitize_callback' => array( $this, 'sanitize_options' ),
					'show_in_rest'      => true,
					'default'           => array(
						'from-email' => $this->email,
						'from-name'  => $this->name
					),
				)
			);
			add_settings_section(
				'general',
				esc_html__( 'General', 'set-mail-from' ),
				array( $this, 'render_section_general' ),
				'set-mail-from'
			);
			add_settings_field(
				'from-email',
				esc_html__( 'Email', 'set-mail-from' ),
				array( $this, 'render_email_field' ),
				'set-mail-from',
				'general',
				array( 'label_for' => 'from-email' )
			);
			add_settings_field(
				'from-name',
				esc_html__( 'Name', 'set-mail-from' ),
				array( $this, 'render_name_field' ),
				'set-mail-from',
				'general',
				array( 'label_for' => 'from-name' )
			);
		}

		public function sanitize_options( $options ): array {
			$old_options = get_option( 'set-mail-from-options' );
			$has_errors  = false;

			$newEmail = empty( $options['from-email'] ) ? '' : $options['from-email'];
			$newEmail = is_string( $newEmail ) ? $newEmail : '';
			$newEmail = trim( $newEmail );
			$newEmail = sanitize_email( $newEmail );

			$newName = empty( $options['from-name'] ) ? '' : $options['from-name'];
			$newName = is_string( $newName ) ? $newName : '';
			$newName = trim( $newName );
			$newName = sanitize_text_field( $newName );

			if ( $newEmail xor $newName ) {
				add_settings_error(
					'set-mail-from-options',
					'set-mail-from',
					esc_html__(
						'Either both fields must be filled, or both fields must be empty.',
						'set-mail-from'
					)
				);
				$has_errors = true;
			}
			if ( $newEmail && ! is_email( $newEmail ) ) {
				add_settings_error(
					'set-mail-from-options',
					'set-mail-from',
					esc_html__( 'Invalid email address.', 'set-mail-from' )
				);
				$has_errors = true;
			}

			if ( $has_errors ) {
				return $old_options;
			}

			return array(
				'from-email' => $newEmail,
				'from-name'  => $newName
			);
		}

		public function render_admin_options_page(): void {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if (
				isset( $_GET['settings-updated'] )
				&& empty( get_settings_errors( 'set-mail-from' ) )
			) {
				add_settings_error(
					'set-mail-from-options',
					'set-mail-from',
					esc_html__( 'Settings Saved', 'set-mail-from' ),
					'success'
				);
			}

			ob_start();
			settings_fields( 'set-mail-from-option-group' );
			$settings_fields = ob_get_clean();

			ob_start();
			do_settings_sections( 'set-mail-from' );
			$do_settings_sections = ob_get_clean();

			ob_start();
			submit_button( __( 'Save Settings', 'set-mail-from' ) );
			$submit_button = ob_get_clean();

			$this->renderer->render( 'options-page', array(
				'pageTitle'            => esc_html( get_admin_page_title() ),
				'settings_fields'      => $settings_fields,
				'do_settings_sections' => $do_settings_sections,
				'submit_button'        => $submit_button
			) );
		}

		public function render_section_general(): void {
			$description = esc_html__( 'General settings for the Set Mail From plugin.', 'set-mail-from' );
			$notes_title = esc_html__( 'Notes:', 'set-mail-from' );
			$notes_list  = array(
				esc_html__( 'Changing these settings may affect the deliverability of your emails. ', 'set-mail-from' ),
				esc_html__( 'Some email providers may reject emails that do not match the domain of the website. ', 'set-mail-from' ),
				esc_html__( 'Leaving these fields empty will use the default WordPress settings.', 'set-mail-from' ),
			);
			$this->renderer->render(
				'general-section',
				array(
					'description' => $description,
					'notes_title' => $notes_title,
					'notes_list'  => $notes_list
				)
			);
		}

		public function render_email_field(): void {
			$field_value       = esc_attr( $this->email );
			$field_placeholder = esc_html__( "Sender's email", 'set-mail-from' );
			$field_description = esc_html__( 'The email of the sender.', 'set-mail-from' );
			$this->renderer->render(
				'email-input-field',
				array(
					'field_value'       => $field_value,
					'field_placeholder' => $field_placeholder,
					'field_description' => $field_description
				)
			);
		}

		public function render_name_field(): void {
			$field_value       = esc_attr( $this->name );
			$field_placeholder = esc_html__( "Sender's name", 'set-mail-from' );
			$field_description = esc_html__( 'The name of the sender.', 'set-mail-from' );
			$this->renderer->render(
				'name-input-field',
				array(
					'field_value'       => $field_value,
					'field_placeholder' => $field_placeholder,
					'field_description' => $field_description
				)
			);
		}
	}
}
