<?php
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/models/Rentsyst_Order.php';
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Rentsyst_OrderListTable extends WP_List_Table {

	public static function define_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'id' => __( 'Id', 'rentsyst' ),
			'date_range' => __( 'Date range', 'rentsyst' ),
			'pickup_location' => __( 'Pickup location', 'rentsyst' ),
			'return_location' => __( 'Return location', 'rentsyst' ),
			'client' => __('Client', 'rentsyst'),
			'vehicle' => __('Vehicle', 'rentsyst'),
			'date' => __( 'Date', 'rentsyst' ),
		);

		return $columns;
	}

	public function __construct() {
		parent::__construct( array(
			'singular' => 'id',
			'plural' => 'ids',
			'ajax' => false,
		) );
	}

	public function prepare_items() {
		$per_page = $this->get_items_per_page( 'rentsyst_per_page' );

		$args = array(
			'posts_per_page' => $per_page,
			'orderby' => 'id',
			'order' => 'ASC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page,
		);

		if ( ! empty( $_REQUEST['s'] ) ) {
			$args['s'] = sanitize_text_field($_REQUEST['s']);
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( 'title' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'title';
			} elseif ( 'author' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'author';
			} elseif ( 'date' == $_REQUEST['orderby'] ) {
				$args['orderby'] = 'date';
			}
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) ) {
				$args['order'] = 'ASC';
			} elseif ( 'desc' == strtolower( $_REQUEST['order'] ) ) {
				$args['order'] = 'DESC';
			}
		}

		$this->items = Order::find( $args );

		$total_items = Order::count();
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page,
		) );
	}

	public function get_columns() {
		return get_column_headers( get_current_screen() );
	}

	protected function get_sortable_columns() {
		$columns = array(
			'title' => array( 'title', true ),
			'author' => array( 'author', false ),
			'date' => array( 'date', false ),
		);

		return $columns;
	}

	protected function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'rentsyst' ),
		);

		return $actions;
	}

	protected function column_default( $item, $column_name ) {
		return '';
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item->id
		);
	}

	public function column_id( $item ) {
		$edit_link = add_query_arg(
			array(
				'id' => absint( $item->id ),
				'action' => 'show',
			),
			menu_page_url( 'rentsyst-orders', false )
		);

		$output = sprintf(
			'<a class="row-title" href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( $edit_link ),
			esc_attr( sprintf(
				/* translators: %s: title of contact form */
				__( 'Show &#8220;%s&#8221;', 'rentsyst' ),
				$item->id
			) ),
			esc_html( $item->id )
		);

		$output = sprintf( '<strong>%s</strong>', $output );

		if ( false ) {
				$error_notice = 'Not send';
				$output .= sprintf(
					'<div class="config-error"><span class="icon-in-circle" aria-hidden="true">!</span> %s</div>',
					$error_notice
				);
		}

		return $output;
	}

	protected function handle_row_actions( $item, $column_name, $primary ) {
		if ( $column_name !== $primary ) {
			return '';
		}

		$edit_link = add_query_arg(
			array(
				'id' => absint( $item->id ),
				'action' => 'delete',
			),
			menu_page_url( 'rentsyst-orders', false )
		);

		$actions = array(
			'delete' =>  __( 'Delete', 'rentsyst' ),
		);

		return $this->row_actions( $actions );
	}

	public function column_pickup_location( $item ) {
		return $item->pickup_location;
	}

	public function column_return_location( $item ) {
		return $item->return_location;
	}

	public function column_date_range( $item ) {
		return $item->date_range;
	}

	public function column_client( $item ) {
		return $item->client;
	}

	public function column_date( $item ) {
		$post = get_post( $item->id );

		if ( ! $post ) {
			return;
		}

		$t_time = mysql2date( __( 'Y/m/d g:i:s A', 'rentsyst' ),
			$post->post_date, true );
		$m_time = $post->post_date;
		$time = mysql2date( 'G', $post->post_date )
			- get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 and $time_diff < 24*60*60 ) {
			$h_time = sprintf(
				/* translators: %s: time since the creation of the contact form */
				__( '%s ago', 'rentsyst' ),
				human_time_diff( $time )
			);
		} else {
			$h_time = mysql2date( __( 'Y/m/d', 'rentsyst' ), $m_time );
		}

		return sprintf( '<abbr title="%2$s">%1$s</abbr>',
			esc_html( $h_time ),
			esc_attr( $t_time )
		);
	}
}
