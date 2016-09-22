<?php
/*
Class: WPAnyIpsumGenerator
Author: Pete Nelson (@GunGeekATX)

Handles anyipsum-form shortcode

*/

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( !class_exists( 'WPAnyIpsumForm' ) ) {

	class WPAnyIpsumForm {

		public function plugins_loaded() {
			add_shortcode( 'anyipsum-form', array( $this, 'shortcode_form' ) );
		}


		function shortcode_form( $atts, $content ) {

			// generate form template
			$form = $this->form_template( $content );

			// for customizing/overriding form
			$form = apply_filters( 'anyipsum-form', $form );

			$output = $this->add_generated_output();

			return do_shortcode( $output . $form );

		}


		private function add_generated_output() {

			$output = '';
			$type = WPAnyIpsumCore::get_request( 'type' );

			if ( ! empty( $type ) ) {

				$args = apply_filters( 'anyipsum-parse-request-args', $_SERVER['QUERY_STRING'] );

				$paragraphs = apply_filters( 'anyipsum-generate-filler', $args );

				// Allow filtering of the generated filler.
				$paragraphs = apply_filters( 'anyipsum-generated-filler', $paragraphs );

				$output = '<div class="anyipsum-output">';
				foreach ( $paragraphs as $paragraph ) {
					$output .= '<p>' . $paragraph . '</p>';
				}

				$output .= '</div>';

				// customize/override output
				$output = apply_filters( 'anyipsum-form-output', $output, $paragraphs );

				// send notification for anything else that's hooked in
				$args['source'] = 'web';
				$args['format'] = 'html';
				$args['output'] = $output;

				do_action( 'anyipsum-filler-generated', $args );

			}

			return $output;

		}


		private function form_template( $content ) {

			$settings = $this->get_settings();
			$type = WPAnyIpsumCore::get_request( 'type' , $settings['all_custom'] );

			if ( empty( $settings['ipsum_name'] ) ) {
				$settings['ipsum_name'] = 'Lorem';
			}

			if ( empty( $settings['start_with'] ) ) {
				$settings['start_with'] = 'Lorem ipsum dolor amet';
			}

			return $this->form_template_html( $content, $type, $settings );

		}


		private function form_template_html( $content, $type, $settings ) {

			$permalink_structure = get_option( 'permalink_structure' );

			ob_start();

			if ( ! empty( $content ) && ! empty( $type ) ) { ?>
				<div class="anyipsum-form-header"><?php echo do_shortcode( $content ); ?></div>
			<?php } ?>

				<form class="anyipsum-form" action="" method="get">
					<?php if ( is_singular() && empty( $permalink_structure ) ) { ?>
					<input type="hidden" name="p" value="<?php echo esc_attr( get_the_id() ); ?>" />
					<?php } ?>
					<table class="anyipsum-table">
						<tbody>
							<tr class="anyipsum-paragraphs"><td class="anyipsum-left-cell"><?php _e( 'Paragraphs', 'any-ipsum' ); ?>:</td><td class="anyipsum-right-cell"><input type="text" name="paras" value="5" maxlength="2" /></td></tr>
							<tr class="anyipsum-type">
								<td class="anyipsum-left-cell"><?php _e( 'Type', 'any-ipsum' ); ?>:</td>
								<td class="anyipsum-right-cell">
									<input id="any-ipsum-all-custom" type="radio" name="type" value="<?php echo esc_attr( $settings['all_custom'] ); ?>" <?php checked( $settings['all_custom'], $type ); ?> />
									<label for="any-ipsum-all-custom"><?php echo esc_attr( $settings['all_custom_text'] ) ?></label>
									<input id="any-ipsum-custom-and-filler" type="radio" name="type" value="<?php echo esc_attr( $settings['custom_and_filler'] ); ?>" <?php checked( $settings['custom_and_filler'], $type ); ?> />
									<label for="any-ipsum-custom-and-filler"><?php echo esc_attr( $settings['custom_filler_text'] ); ?></label>
								</td>
							</tr>
							<tr class="anyipsum-start-with">
								<td class="anyipsum-left-cell"></td>
								<td class="anyipsum-right-cell">
									<input id="start-with-lorem" type="checkbox" name="start-with-lorem" value="1" checked="checked" />
									<label for="start-with-lorem"><?php _e( 'Start with', 'any-ipsum' ); ?> '<?php echo esc_attr( $settings['start_with'] ); ?>...'</label>
								</td>
							</tr>
							<?php do_action( 'anyipsum-after-starts-with-row', $content, $type, $settings ); ?>
							<tr class="anyipsum-submit"><td class="anyipsum-left-cell"></td><td class="anyipsum-right-cell"><input type="submit" value="<?php echo esc_attr( $settings['button_text'] ); ?>" /></td></tr>
						</tbody>
					</table>
				</form>
			<?php

			$form = ob_get_contents();
			ob_end_clean();
			return $form;
		}


		private function get_settings() {
			$settings = array(
				'custom_filler_text'   => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'custom-and-filler-text' ),
				'custom_and_filler'    => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' ),
				'all_custom_text'      => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'all-custom-text' ),
				'all_custom'           => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-all-custom' ),
				'button_text'          => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'button-text' ),
				'start_with'           => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'start-with' ),
				'ipsum_name'           => apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'name' ),
			);
			return $settings;
		}


	}

}
