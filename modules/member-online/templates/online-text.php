<?php if ( ! defined( 'ABSPATH' ) ) exit;

$class = $is_online ? 'online' : 'offline';
$title = $is_online ? __( 'online', 'twodayssss' ) : __( 'offline', 'twodayssss' ); ?>

<span class="um-online-status <?php echo esc_attr( $class ) ?>">
	<?php echo $title ?>
</span>