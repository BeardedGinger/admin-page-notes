<?php
/**
 * Represents the view for the admin addition of a page note
 */
 
$value = get_post_meta( $post->ID, 'gb_admin_note', true );

echo '<p>' .  html_entity_decode( $value ) . '</p>';

?>