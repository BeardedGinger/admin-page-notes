<?php
/**
 * Represents the view for the admin addition of a page note
 */

$screen = get_current_screen();

$value = get_post_meta( $post->ID, 'gb_admin_note', true );

?>
<label for="admin_add_note"><?php _e( 'Add a note for editors to see when editing this', 'gb-page-notes' ); ?> <?php echo $screen->post_type; ?></label><br><br>

<textarea id="admin_add_note" name="admin_add_note" class="widefat" ><?php echo html_entity_decode( $value ); ?></textarea>