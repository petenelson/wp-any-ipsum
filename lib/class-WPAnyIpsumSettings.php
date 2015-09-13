<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'WPAnyIpsumSettings' ) ) {

	class WPAnyIpsumSettings {

		private $settings_page    = 'anyipsum-settings';
		private $settings_key_general  = 'anyipsum-settings-general';
		private $settings_key_filler  = 'anyipsum-settings-custom-filler';
		private $settings_key_api   = 'anyipsum-settings-api';
		private $settings_key_oembed  = 'anyipsum-settings-oembed';
		private $settings_key_help   = 'anyipsum-settings-help';
		private $plugin_settings_tabs  = array();


		public function plugins_loaded() {
			// admin menus
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_notices', array( $this, 'activation_admin_notice' ) );

			add_filter( 'anyipsum-setting-is-enabled', array( $this, 'setting_is_enabled' ), 10, 3 );
			add_filter( 'anyipsum-setting-get', array( $this, 'setting_get' ), 10, 3 );
		}


		public function activation_hook() {

			// create default settings
			add_option( $this->settings_key_general, array(
					'name' => 'Bacon',
					'start-with' => 'Bacon ipsum dolor amet',
					'querystring-all-custom' => 'all-custom',
					'querystring-custom-and-filler' => 'custom-and-filler',
					'button-text' => 'Give Me Bacon',
					'all-custom-text' => 'All Meat',
					'custom-and-filler-text' => 'Meat and Filler',
				), '', $autoload = 'no' );

			add_option( $this->settings_key_api, array(
					'api-enabled' => '0',
					'api-endpoint' => 'ipsum-api',
				), '', $autoload = 'no' );

			add_option( $this->settings_key_oembed, array(
					'oembed-enabled' => '0',
					'oembed-endpoint' => 'ipsum-oembed',
				), '', $autoload = 'no' );

			$custom = '';
			$filler = '';

			if ( class_exists( 'WPAnyIpsumGenerator' ) ) {
				$WPAnyIpsumGenerator = new WPAnyIpsumGenerator();
				$custom = implode( "\n", $WPAnyIpsumGenerator->default_custom() );
				$filler = implode( "\n", $WPAnyIpsumGenerator->default_filler() );
			}

			add_option( $this->settings_key_filler, array( 'custom-words' => $custom, 'filler-words' => $filler ), '', $autoload = 'no' );

			// add an option so we can show the activated admin notice
			add_option( 'anyipsum-plugin-activated', '1' );

		}


		public function activation_admin_notice() {
			if ( '1' === get_option( 'anyipsum-plugin-activated' ) ) { ?>
					<div class="updated">
						<p><?php
				echo sprintf( __( '<strong>Any Ipsum activated!</strong> Please visit the <a href="%s">Any Ipsum Settings</a> page to customize your ipsum generator.', 'any-ispum' ), admin_url( 'options-general.php?page=anyipsum-settings' ) );
				?></p>
					</div>
				<?php
				delete_option( 'anyipsum-plugin-activated' );
			}
		}


		public function deactivation_hook() {
			// placeholder in case we need deactivation code
		}


		public function admin_init() {
			$this->register_general_settings();
			$this->register_filler_settings();
			$this->register_api_settings();
			$this->register_oembed_settings();
			$this->register_help_tab();
		}


		private function register_general_settings() {
			$key = $this->settings_key_general;
			$this->plugin_settings_tabs[$key] = __( 'General', 'any-ipsum' );

			register_setting( $key, $key );

			$section = 'general';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'name', __( 'Your Ipsum Name', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'name', 'size' => 20, 'maxlength' => 50, 'after' => 'Example: Bacon, Hipster, Cupcake, etc' ) );

			add_settings_field( 'all-custom-text', __( 'All Custom Text', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'all-custom-text', 'size' => 20, 'maxlength' => 50, 'after' => 'Example: All Meat, Hipster neat' ) );

			add_settings_field( 'custom-and-filler-text', __( 'Custom and Filler Text', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'custom-and-filler-text', 'size' => 20, 'maxlength' => 50, 'after' => 'Example: Meat and Filler, Hipster with a shot of Latin' ) );

			add_settings_field( 'start-with', __( 'Start With Text', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'start-with', 'size' => 50, 'maxlength' => 50, 'after' => 'Example: Bacon ipsum dolor sit amet' ) );

			add_settings_field( 'button-text', __( 'Button Text', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'button-text', 'size' => 50, 'maxlength' => 50, 'after' => 'Example: Give me bacon, Beer me!, etc.' ) );

			add_settings_field( 'querystring-all-custom', __( 'Querystring for All Custom', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'querystring-all-custom', 'size' => 50, 'maxlength' => 50, 'after' => 'In case you want something different (like all-meat, hipster-centric, etc.)' ) );

			add_settings_field( 'querystring-custom-and-filler', __( 'Querystring for Custom and Filler', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'querystring-custom-and-filler', 'size' => 50, 'maxlength' => 50, 'after' => 'In case you want something different (like meat-and-filler, hipster-latin, etc.)' ) );
		}


		private function register_filler_settings() {
			$key = $this->settings_key_filler;
			$this->plugin_settings_tabs[$key] = __( 'Custom and Filler', 'any-ipsum' );

			register_setting( $key, $key );

			$section = 'custom-and-filler';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'custom-words', __( 'Custom Words', 'any-ipsum' ), array( $this, 'settings_textarea' ), $key, $section,
				array( 'key' => $key, 'name' => 'custom-words', 'rows' => 10, 'cols' => 40, 'after' => 'One word, phrase, or sentence per line' ) );

			add_settings_field( 'filler-words', __( 'Filler Words', 'any-ipsum' ), array( $this, 'settings_textarea' ), $key, $section,
				array( 'key' => $key, 'name' => 'filler-words', 'rows' => 10, 'cols' => 40, 'after' => 'One word/phrase per line' ) );

			add_settings_field( 'sentence-mode', __( 'Sentence Mode', 'any-ipsum' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => 'sentence-mode', 'after' =>  __( 'The custom words above are sentences instead of words.  Disables automatic punctuation.' ) ) );
		}


		private function register_api_settings() {
			$this->register_api_oembed_settings( $this->settings_key_api, 'API', 'api' );
		}


		private function register_oembed_settings() {
			$this->register_api_oembed_settings( $this->settings_key_oembed, 'oEmbed', 'oembed' );
		}


		private function register_api_oembed_settings( $key, $name, $section ) {
			$this->plugin_settings_tabs[$key] = __( $name, 'any-ipsum' );

			register_setting( $key, $key );

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( $section .'-enabled', __( 'Enabled', 'any-ipsum' ), array( $this, 'settings_yes_no' ), $key, $section,
				array( 'key' => $key, 'name' => $section .'-enabled' ) );

			$permalink_structure = get_option( 'permalink_structure' );
			$permalink_warning = empty( $permalink_structure ) ? ' ' . __( '(please enable any non-default Permalink structure)', 'any-ipsum' ) : '';

			$after = $this->get_api_oembed_after( $section );

			add_settings_field( $section .'-endpoint', __( $name .' Page Name', 'any-ipsum' ), array( $this, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => $section .'-endpoint', 'size' => 20, 'maxlength' => 50, 'after' => $after . $permalink_warning ) );
		}


		private function get_api_oembed_after( $section ) {
			switch ( $section ) {
				case 'oembed':
					$after = __( 'Example: oembed, ipsum-oembed', 'any-ipsum' );
					break;
				case 'api':
					$after = __( 'Example: api, ipsum-api, etc', 'any-ipsum');
					break;
				default:
					$after = '';
					break;
			}
			return $after;
		}


		private function register_help_tab() {
			$key = $this->settings_key_help;
			$this->plugin_settings_tabs[$key] =  __( 'Help', 'any-ipsum' );

			register_setting( $key, $key );

			$section = 'help';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

		}


		public function setting_is_enabled( $enabled, $key, $setting ) {
			return '1' === $this->setting_get( '0', $key, $setting );
		}


		public function setting_get( $value, $key, $setting ) {

			$args = wp_parse_args( get_option( $key ),
				array(
					$setting => $value,
				)
			);

			return $args[$setting];
		}


		public function settings_input( $args ) {

			$args = wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'maxlength' => 50,
					'size' => 30,
					'after' => '',
				)
			);


			$name = $args['name'];
			$key = $args['key'];
			$size = $args['size'];
			$maxlength = $args['maxlength'];

			$option = get_option( $key );
			$value = isset( $option[$name] ) ? esc_attr( $option[$name] ) : '';

			echo "<div><input id='{$name}' name='{$key}[{$name}]'  type='text' value='" . $value . "' size='{$size}' maxlength='{$maxlength}' /></div>";
			if ( !empty( $args['after'] ) )
				echo '<div>' . __( $args['after'], 'any-ipsum' ) . '</div>';

		}


		public function settings_textarea( $args ) {

			$args = wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'rows' => 10,
					'cols' => 40,
					'after' => '',
				)
			);


			$name = $args['name'];
			$key = $args['key'];
			$rows = $args['rows'];
			$cols = $args['cols'];

			$option = get_option( $key );
			$value = isset( $option[$name] ) ? esc_attr( $option[$name] ) : '';

			echo "<div><textarea id='{$name}' name='{$key}[{$name}]' rows='{$rows}' cols='{$cols}'>" . $value . "</textarea></div>";
			if ( !empty( $args['after'] ) )
				echo '<div>' . $args['after'] . '</div>';

		}


		public function settings_yes_no( $args ) {

			$args = wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'after' => '',
				)
			);

			$name = $args['name'];
			$key = $args['key'];

			$option = get_option( $key );
			$value = isset( $option[$name] ) ? esc_attr( $option[$name] ) : '';

			if ( empty( $value ) )
				$value = '0';

			echo '<div>';
			echo "<label><input id='{$name}_1' name='{$key}[{$name}]'  type='radio' value='1' " . ( '1' === $value ? " checked=\"checked\"" : "" ) . "/>" . __( 'Yes', 'any-ipsum' ) . "</label> ";
			echo "<label><input id='{$name}_0' name='{$key}[{$name}]'  type='radio' value='0' " . ( '0' === $value ? " checked=\"checked\"" : "" ) . "/>" . __( 'No', 'any-ipsum' ) . "</label> ";
			echo '</div>';

			if ( !empty( $args['after'] ) )
				echo '<div>' . __( $args['after'], 'any-ipsum' ) . '</div>';
		}


		public function admin_menu() {
			add_options_page( __( 'Any Ipsum Settings', 'any-ipsum' ), __( 'Any Ipsum', 'any-ipsum' ), 'manage_options', $this->settings_page, array( $this, 'options_page' ), 30 );
		}


		public function options_page() {

			$tab = $this->current_tab(); ?>
			<div class="wrap">
				<?php $this->plugin_options_tabs(); ?>
				<form method="post" action="options.php" class="options-form">
					<?php settings_fields( $tab ); ?>
					<?php do_settings_sections( $tab ); ?>
					<?php
						if ( $this->settings_key_help !== $tab ) {
							submit_button( __( 'Save Settings', 'any-ipsum' ), 'primary', 'submit', true );
						}
					?>
				</form>
			</div>
			<?php

			$settings_updated = filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING );
			if ( ! empty( $settings_updated ) ) {
				flush_rewrite_rules( );
			}

		}


		private function current_tab() {
			$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			return empty( $current_tab ) ? $this->settings_key_general : $current_tab;
		}


		private function plugin_options_tabs() {
			$current_tab = $this->current_tab();
			echo '<h2>' . __( 'Any Ipsum Settings', 'any-ipsum' ) . '</h2><h2 class="nav-tab-wrapper">';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->settings_page . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}


		public function section_header( $args ) {

			switch ( $args['id'] ) {
			case 'help';
				include_once 'admin-help.php';
				break;
			case 'api':
				$output = __( 'Allows for API access to your custom ipsum, that returns JSON, HTML, or plain text.', 'any-ipsum' );
				$endpoint = $this->setting_get( '', $this->settings_key_api, 'api-endpoint' );
				if ( !empty( $endpoint ) ) {
					$output .= '<br/>' . __( 'Example', 'any-ipsum' ) . ': ';
					$url = add_query_arg( array(
						'type' => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-all-custom' ),
						'start-with-lorem' => 1,
					), site_url( $endpoint . '/' ) );

					$output .= '<a target="_blank" href="' . $url . '">' . $url . '</a>';
				}
				break;
			}

			if ( !empty( $output ) ) {
				echo '<p class="settings-section-header">' . $output . '</p>';
			}

		}


	}

}
