<?php

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumSettings')) {

	class WPAnyIpsumSettings {

		var $text_domain = 'anyipsum';
		var $settings_page = 'anyipsum-settings';
		var $settings_key_general = 'anyipsum-settings-general';
		var $settings_key_filler = 'anyipsum-settings-custom-filler';
		var $settings_key_api = 'anyipsum-settings-api';
		var $plugin_settings_tabs = array();


		public function plugins_loaded() {
			// admin menus
			add_action('admin_init', array($this, 'admin_init'));
			add_action('admin_menu', array($this, 'admin_menu'));

			add_filter( 'anyipsum-setting-is-enabled', array($this, 'setting_is_enabled'), 10, 3);
			add_filter( 'anyipsum-setting-get', array($this, 'setting_get'), 10, 3);

		}


		function admin_init() {
			$this->register_general_settings();
			$this->register_filler_settings();
			$this->register_api_settings();
		}


		function register_general_settings() {
			$key = $this->settings_key_general;
			$this->plugin_settings_tabs[$key] = 'General';

			register_setting( $key, $key );

			$section = 'general';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'name', 'Your Ipsum Name', array( $this, 'settings_input' ), $key, $section,
				array('key' => $key, 'name' => 'name', 'size' => 20, 'maxlength' => 50, 'after' => 'Example: Bacon, Hipster, Cupcake, etc'));

			add_settings_field( 'start-with', 'Start With Text', array( $this, 'settings_input' ), $key, $section,
				array('key' => $key, 'name' => 'start-with', 'size' => 50, 'maxlength' => 50, 'after' => 'Example: Bacon ipsum dolor sit amet'));

		}


		function register_filler_settings() {
			$key = $this->settings_key_filler;
			$this->plugin_settings_tabs[$key] = 'Custom and Filler';

			register_setting( $key, $key );

			$section = 'custom-and-filler';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'custom-words', 'Custom Words', array( $this, 'settings_textarea' ), $key, $section,
				array('key' => $key, 'name' => 'custom-words', 'rows' => 10, 'cols' => 40, 'after' => 'One word/phrase per line'));

			add_settings_field( 'filler-words', 'Filler Words', array( $this, 'settings_textarea' ), $key, $section,
				array('key' => $key, 'name' => 'filler-words', 'rows' => 10, 'cols' => 40, 'after' => 'One word/phrase per line'));

		}


		function register_api_settings() {
			$key = $this->settings_key_api;
			$this->plugin_settings_tabs[$key] = 'API';

			register_setting( $key, $key );

			$section = 'api';

			add_settings_section( $section, '', array( $this, 'section_header' ), $key );

			add_settings_field( 'api-enabled', 'Enabled', array( $this, 'settings_yes_no' ), $key, $section,
				array('key' => $key, 'name' => 'api-enabled'));

			$permalink_warning = empty(get_option( 'permalink_structure' )) ? ' (please anable any non-default Permalink structure)' : '';

			add_settings_field( 'api-endpoint', 'Endpoint Page Name', array( $this, 'settings_input' ), $key, $section,
				array('key' => $key, 'name' => 'api-endpoint', 'size' => 20, 'maxlength' => 50, 'after' => 'Example: api, ipsum-api, etc' . $permalink_warning));

		}


		function setting_is_enabled($enabled, $key, $setting) {
			return '1' === $this->setting_get('0', $key, $setting);
		}


		function setting_get($value, $key, $setting) {

			$args = wp_parse_args( get_option($key),
				array(
					$setting => $value,
				)
			);

			return $args[$setting];
		}


		function settings_input($args) {

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

			$option = get_option($key);
			$value = isset($option[$name]) ? esc_attr($option[$name]) : '';

			echo "<div><input id='{$name}' name='{$key}[{$name}]'  type='text' value='" . $value . "' size='{$size}' maxlength='{$maxlength}' /></div>";
			if (!empty($args['after']))
				echo '<div>' . $args['after'] . '</div>';

		}


		function settings_textarea($args) {

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

			$option = get_option($key);
			$value = isset($option[$name]) ? esc_attr($option[$name]) : '';

			echo "<div><textarea id='{$name}' name='{$key}[{$name}]' rows='{$rows}' cols='{$cols}'>" . $value . "</textarea></div>";
			if (!empty($args['after']))
				echo '<div>' . $args['after'] . '</div>';

		}


		function settings_yes_no($args) {

			$args = wp_parse_args( $args,
				array(
					'name' => '',
					'key' => '',
					'after' => '',
				)
			);

			$name = $args['name'];
			$key = $args['key'];

			$option = get_option($key);
			$value = isset($option[$name]) ? esc_attr($option[$name]) : '';

			if (empty($value))
				$value = '0';

			echo '<div>';
			echo "<label><input id='{$name}_1' name='{$key}[{$name}]'  type='radio' value='1' " . ('1' === $value ? " checked=\"checked\"" : "") . "/>Yes</label> ";
			echo "<label><input id='{$name}_0' name='{$key}[{$name}]'  type='radio' value='0' " . ('0' === $value ? " checked=\"checked\"" : "") . "/>No</label> ";
			echo '</div>';

			if (!empty($args['after']))
				echo '<div>' . $args['after'] . '</div>';
		}


		function admin_menu() {
			add_options_page( 'Any Ipsum Settings', 'Any Ipsum', 'manage_options', $this->settings_page, array($this, 'options_page' ), 30);
		}


		function options_page() {

			$tab = !empty( $_GET['tab'] ) ? $_GET['tab'] : $this->settings_key_general;
			?>
			<div class="wrap">
				<?php $this->plugin_options_tabs(); ?>
				<form method="post" action="options.php" class="options-form">
					<?php settings_fields( $tab ); ?>
					<?php do_settings_sections( $tab ); ?>
					<?php submit_button('Save Settings', 'primary', 'submit', true); ?>
				</form>
			</div>
			<?php
		}


		function plugin_options_tabs() {
			$current_tab = !empty( $_GET['tab'] ) ? $_GET['tab'] : $this->settings_key_general;
			echo '<h2>Any Ipsum Settings</h2><h2 class="nav-tab-wrapper">';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->settings_page . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}


		function section_header($args) {



			switch ($args['id']) {
				case 'api':
					$output = 'Allows for a JSON API to your custom ipsum.';
					$endpoint = $this->setting_get( '', $this->settings_key_api, 'api-endpoint' );
					if (!empty($endpoint)) {
						$output .= '<br/>' . _('Example', $this->text_domain) . ': ';
						$url = home_url( $endpoint ) . '?type=all-custom&amp;paras=3&amp;start-with-lorem=1';
						$output .= '<a target="_blank" href="' . $url . '">' . $url . '</a>';
					}
					break;
			}

			if (!empty($output))
				echo '<p class="settings-section-header">' . $output . '</p>';

		}


	}

}

