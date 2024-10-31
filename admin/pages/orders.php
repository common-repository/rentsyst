<?php
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_OrderListTable.php';
$service->add_my_setting();

$list_table = new Rentsyst_OrderListTable();
$list_table->prepare_items();

?>
<div class="wrap rentsyst-page-wrapper" id="rentsyst-list-table">

	<h1 class="wp-heading-inline">
		<?php echo esc_html( __( 'Orders', 'rentsyst' ) ); ?>
	</h1>


	<hr class="wp-header-end">


	<form method="get" action="">
		<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
		<?php $list_table->search_box( __( 'Find', 'rentsyst' ), 'rentsyst-order_search' ); ?>
		<?php $list_table->display(); ?>
	</form>
</div>