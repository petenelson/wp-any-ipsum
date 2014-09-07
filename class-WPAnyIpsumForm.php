<?php

if (!defined( 'ABSPATH' )) exit('restricted access');

if (!class_exists('WPAnyIpsumForm')) {

	class WPAnyIpsumForm {


		public function plugins_loaded() {
			add_shortcode('anyipsum-form', array($this, 'shortcode_form') );
		}


		function shortcode_form($atts) {

			$text_domain = 'anyipsum';
			$output = '';

			$ipsum_name = apply_filters( 'anyipsum-setting-get', array(), 'anyipsum-settings-general', 'name' );
			$start_with = apply_filters( 'anyipsum-setting-get', array(), 'anyipsum-settings-general', 'start-with' );

			if (empty($ipsum_name))
				$ipsum_name = 'Lorem';

			if (empty($start_with))
				$start_with = 'Lorem ipsum dolor amet';

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
								<td class="anyipsum-right-cell"><label><input type="radio" name="type" value="all-custom" checked="checked" /><?php echo _e('All', $text_domain); ?> <?php echo esc_attr( $ipsum_name ) ?></label> <label><input type="radio" name="type" value="custom-and-filler" /><?php echo esc_attr( $ipsum_name ) ?> <?php _e('and Filler', $text_domain); ?></label></td>
							</tr>
							<tr class="anyipsum-start-with">
								<td class="anyipsum-left-cell"></td>
								<td class="anyipsum-right-cell"><input id="start-with-lorem" type="checkbox" name="start-with-lorem" value="1" checked="checked" /> <label for="start-with-lorem"><?php _e('Start with', $text_domain); ?> '<?php echo esc_attr($start_with); ?>...'</label></td>
							</tr>
							<tr class="anyipsum-submit">
								<td class="anyipsum-left-cell"></td>
								<td class="anyipsum-right-cell"><input type="submit" value="Give me <?php echo strtolower( esc_attr($ipsum_name) ); ?>" /></td>
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

				$args = array();

				$args['type'] = filter_var($_REQUEST["type"], FILTER_SANITIZE_STRING);

				$number_of_paragraphs = 5;
				if (isset($_REQUEST["paras"]))
					$number_of_paragraphs = intval($_REQUEST["paras"]);

				if ($number_of_paragraphs < 1)
					$number_of_paragraphs = 1;

				if ($number_of_paragraphs > 100)
					$number_of_paragraphs = 100;

				$args['number-of-paragraphs'] = $number_of_paragraphs;
				$args['start-with-lorem'] = !empty($_REQUEST["start-with-lorem"]) && '1' === $_REQUEST["start-with-lorem"];

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

