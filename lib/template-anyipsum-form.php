<?php


?>

<form class="anyipsum-form" action="" method="get">
	<?php if ( is_singular() && empty( $permalink_structure ) ) { ?>
	<input type="hidden" name="p" value="<?php echo esc_attr( get_the_id() ); ?>" />
	<?php } ?>
	<table class="anyipsum-table">
		<tbody>
			<tr class="anyipsum-paragraphs">
				<td class="anyipsum-left-cell"><?php _e( 'Paragraphs', 'any-ipsum' ); ?>:</td>
				<td class="anyipsum-right-cell"><input type="text" name="paras" value="5" maxlength="2" /></td>
			</tr>
			<tr class="anyipsum-type">
				<td class="anyipsum-left-cell"><?php _e( 'Type', 'any-ipsum' ); ?>:</td>
				<td class="anyipsum-right-cell"><label><input type="radio" name="type" value="<?php echo esc_attr( $all_custom ); ?>" <?php checked( $all_custom, $type ); ?> /><?php echo esc_attr( $all_custom_text ) ?></label> <label><input type="radio" name="type" value="<?php echo esc_attr( $custom_and_filler ); ?>" <?php checked( $custom_and_filler, $type ); ?> /><?php echo esc_attr( $custom_filler_text ); ?></label></td>
			</tr>
			<tr class="anyipsum-start-with">
				<td class="anyipsum-left-cell"></td>
				<td class="anyipsum-right-cell"><input id="start-with-lorem" type="checkbox" name="start-with-lorem" value="1" checked="checked" /> <label for="start-with-lorem"><?php _e( 'Start with', 'any-ipsum' ); ?> '<?php echo esc_attr( $start_with ); ?>...'</label></td>
			</tr>
			<tr class="anyipsum-submit">
				<td class="anyipsum-left-cell"></td>
				<td class="anyipsum-right-cell"><input type="submit" value="<?php echo esc_attr( $button_text ); ?>" /></td>
			</tr>
		</tbody>
	</table>
</form>
