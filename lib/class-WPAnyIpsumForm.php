<?php
/*
Class: WPAnyIpsumGenerator
Author: Pete Nelson (@GunGeekATX)

Handles anyipsum-form shortcode

*/

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumForm')) {

	class WPAnyIpsumForm {

		public function plugins_loaded() {
			add_shortcode('anyipsum-form', array($this, 'shortcode_form') );
		}


		function shortcode_form($atts) {

			$text_domain = 'anyipsum';
			$output = '';

			$ipsum_name = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'name' );
			$start_with = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'start-with' );
			$all_custom = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-all-custom' );
			$custom_and_filler = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'querystring-custom-and-filler' );
			$all_custom_text = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'all-custom-text' );
			$custom_and_filler_text = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'custom-and-filler-text' );
			$button_text = apply_filters( 'anyipsum-setting-get', '', 'anyipsum-settings-general', 'button-text' );

			if (empty($ipsum_name))
				$ipsum_name = 'Lorem';

			if (empty($start_with))
				$start_with = 'Lorem ipsum dolor amet';

			$type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : $all_custom;

			ob_start();
			?>
				<form class="anyipsum-form" action="" method="get">
					<?php if (is_singular() && empty(get_option('permalink_structure'))) { ?>
					<input type="hidden" name="p" value="<?php echo esc_attr(get_the_id()); ?>" />
					<?php } ?>
					<table class="anyipsum-table">
						<tbody>
							<tr class="anyipsum-paragraphs">
								<td class="anyipsum-left-cell"><?php _e('Paragraphs', $text_domain); ?>:</td>
								<td class="anyipsum-right-cell"><input type="text" name="paras" value="5" maxlength="2" /></td>
							</tr>
							<tr class="anyipsum-type">
								<td class="anyipsum-left-cell"><?php _e('Type', $text_domain); ?>:</td>
								<td class="anyipsum-right-cell"><label><input type="radio" name="type" value="<?php echo esc_attr($all_custom); ?>" <?php checked($all_custom, $type); ?> /><?php echo esc_attr( $all_custom_text ) ?></label> <label><input type="radio" name="type" value="<?php echo esc_attr($custom_and_filler); ?>" <?php checked($custom_and_filler, $type); ?> /><?php echo esc_attr($custom_and_filler_text); ?></label></td>
							</tr>
							<tr class="anyipsum-start-with">
								<td class="anyipsum-left-cell"></td>
								<td class="anyipsum-right-cell"><input id="start-with-lorem" type="checkbox" name="start-with-lorem" value="1" checked="checked" /> <label for="start-with-lorem"><?php _e('Start with', $text_domain); ?> '<?php echo esc_attr($start_with); ?>...'</label></td>
							</tr>
							<tr class="anyipsum-submit">
								<td class="anyipsum-left-cell"></td>
								<td class="anyipsum-right-cell"><input type="submit" value="<?php echo esc_attr($button_text); ?>" /></td>
							</tr>
						</tbody>
					</table>
				</form>
			<?php

			$form = ob_get_contents();
			ob_end_clean();

			// for customizing/overriding form
			$form = apply_filters( 'anyipsum-form', $form );


			if (isset($_REQUEST["type"])) {

				$args = apply_filters( 'anyipsum-parse-request-args', array() );

				$paragraphs = apply_filters( 'anyipsum-generate-filler', $args );

				$output = '<div class="anyipsum-output">';
				foreach($paragraphs as $paragraph)
					$output .= '<p>' . $paragraph . '</p>';

				$output .= '</div>';

				// customize/override output
				$output = apply_filters( 'anyipsum-form-output', $output, $paragraphs );

			}

			return do_shortcode( $output . $form );

		}


	}

}

