<?php
/**
 * Announcement bar with seamless marquee.
 *
 * @package Atelier_B2B
 */

defined( 'ABSPATH' ) || exit;
$c = atelier_content( 'announcement' );
?>
<div class="announcement" role="region" aria-label="<?php esc_attr_e( 'Announcement', 'atelier-b2b' ); ?>">
	<?php atelier_marquee( $c['items'], array( 'class' => 'marquee--sm marquee--pause', 'speed' => $c['speed'], 'repeat' => 3 ) ); ?>
</div>
