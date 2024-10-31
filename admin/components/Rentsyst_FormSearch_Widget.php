<?php

namespace rentsyst\admin\components;

use rentsyst\includes\Rentsyst_PluginSettings;
use rentsyst\site\Rentsyst_BookingWidget;
use rentsyst\site\Rentsyst_ResourceLoader;
use WP_Widget;

class Rentsyst_FormSearch_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'rentsyst_formsearch_widget',
			'description'                 => __( 'Booking form from Rentsyst' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'rentsyst_formsearch', __( 'Vehicle search form (Rentsyst)' ), $widget_ops );
//		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the booking form.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {

	    if(Rentsyst_BookingWidget::$is_display) {
	        return false;
        }

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : false;


		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		?>
		<?php echo $args['before_widget']; ?>
		<?php
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$settings = self::generateSetting($instance);

		echo Rentsyst_BookingWidget::widget($settings);

		self::addHandlerForSearchButton();
		?>

		<?php
		echo $args['after_widget'];
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = sanitize_text_field( $new_instance['title'] );
		$instance['orientation'] = $new_instance['orientation'];
		$instance['colorMain']       = sanitize_text_field( $new_instance['colorMain'] );
		$instance['colorSecond']       = sanitize_text_field( $new_instance['colorSecond'] );
		$instance['textColor']       = sanitize_text_field( $new_instance['textColor'] );
		$instance['bgColor']       = sanitize_text_field( $new_instance['bgColor'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

	    if(!isset( $instance['bgColor'] )) {
		    $settings = ( new Rentsyst_BuilderConstructorSetting )->setSystemSettings()->settings;
	    }

		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$orientation    = isset( $instance['orientation'] ) ? $instance['orientation'] : 'vertical';
		$colorMain     = isset( $instance['colorMain'] ) ? esc_attr( $instance['colorMain'] ) : $settings['colorMain']['value'];
		$colorSecond     = isset( $instance['colorSecond'] ) ? esc_attr( $instance['colorSecond'] ) : $settings['colorSecond']['value'];
		$textColor     = isset( $instance['textColor'] ) ? esc_attr( $instance['textColor'] ) : $settings['textColor']['value'];
		$bgColor     = isset( $instance['bgColor'] ) ? esc_attr( $instance['bgColor'] ) : $settings['bgColor']['value'];

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'colorMain' ); ?>"><?php _e( 'Color mode main:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'colorMain' ); ?>" name="<?php echo $this->get_field_name( 'colorMain' ); ?>" type="color" value="<?php echo $colorMain; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'colorSecond' ); ?>"><?php _e( 'Color mode secondary:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'colorSecond' ); ?>" name="<?php echo $this->get_field_name( 'colorSecond' ); ?>" type="color" value="<?php echo $colorSecond; ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id( 'textColor' ); ?>"><?php _e( 'Text color:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'textColor' ); ?>" name="<?php echo $this->get_field_name( 'textColor' ); ?>" type="color" value="<?php echo $textColor; ?>" />
        </p>

		<p>
            <label for="<?php echo $this->get_field_id( 'bgColor' ); ?>"><?php _e( 'Background color:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'bgColor' ); ?>" name="<?php echo $this->get_field_name( 'bgColor' ); ?>" type="color" value="<?php echo $bgColor; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'orientation' ); ?>"><?php _e( 'Orientation:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'orientation' ); ?>" name="<?php echo $this->get_field_name( 'orientation' ); ?>">
                    <option value="vertical" <?php selected( $orientation, 'vertical' ); ?>>
                        Vertical
                    </option>
                    <option value="horizontal" <?php selected( $orientation, 'horizontal' ); ?>>
                        Horizontal
                    </option>
            </select>
        </p>

		<?php
	}

	public static function addBlockType()
	{
		add_action( 'init', [__CLASS__, 'registerBlockTypeGutenberg'] );

		add_action( 'enqueue_block_editor_assets', [__CLASS__, 'gutenberg_block_editor_scripts']);


	}

	public static function gutenberg_block_editor_scripts() {
		$asset_file = include( WP_RENTSYST_PLUGIN_DIR . '/admin/asset.php');

		wp_register_script(
			'gutenberg-examples-dynamic',
			plugins_url( 'rentsyst/admin/js/block-search.js', WP_RENTSYST_PLUGIN_DIR ),
			$asset_file['dependencies'],
			$asset_file['version']
		);


	}


	public static function registerBlockTypeGutenberg() {

		$settings = ( new Rentsyst_BuilderConstructorSetting )->setSystemSettings()->settings;

		register_block_type( 'rentsyst/rentsyst-form-search', array(
			'editor_script' => 'gutenberg-examples-dynamic',
			'attributes' => [
				'orientation' => [
					'type' => 'string',
					'default' => 'horizontal'
				],
				'colorMain' => [
					'type' => 'string',
					'default' => $settings['colorMain']['value']
				],
				'colorSecond' => [
					'type' => 'string',
					'default' => $settings['colorSecond']['value']
				],
				'textColor' => [
					'type' => 'string',
					'default' => $settings['textColor']['value']
				],
				'bgColor' => [
					'type' => 'string',
					'default' => $settings['bgColor']['value']
				],
			],
			'render_callback' => [__CLASS__, 'renderDynamicContent']
		) );

	}


	public static function renderDynamicContent( $attributes ) {

	    self::gutenberg_block_editor_scripts();
		self::addHandlerForSearchButton();
		$settingsDynamic = static::generateSetting($attributes);

		$marginTop = $attributes['marginTop'] ?? 0;

		$params = http_build_query([
			'params' => $settingsDynamic
		]);

		if(isset($_GET['context']) && $_GET['context'] === 'edit') {
			return '<div style="position: relative; z-index: 10; margin-top: ' . $marginTop . 'px" class="rentsyst-booking-widget-wrapper"><iframe id="rentsyst_iframe" src="/wp-admin/?rentsyst-iframe=true&' . $params . '" scrolling="no" style="width: 100%; height: 651px; overflow: hidden; border: none;"></iframe></div>';
		}

		return '<div style="position: relative; z-index: 10; margin-top: ' . $marginTop . 'px" class="rentsyst-booking-widget-wrapper">' . Rentsyst_BookingWidget::widget($settingsDynamic) . '</div>';
	}

	private static function generateSetting( array $instance )
	{
		$orientation = isset( $instance['orientation'] ) ? $instance['orientation'] : 'vertical';
		$colorMain = ( ! empty( $instance['colorMain'] ) ) ? $instance['colorMain'] : false;
		$colorSecond = ( ! empty( $instance['colorSecond'] ) ) ? $instance['colorSecond'] : false;
		$textColor = ( ! empty( $instance['textColor'] ) ) ? $instance['textColor'] : false;
		$bgColor = ( ! empty( $instance['bgColor'] ) ) ? $instance['bgColor'] : false;

		$settings = [
			'disabledBlocks' => ['header_text'],
			'tabs' => false,
			'steps' => [
				0 => [
					'view' => $orientation
				]
			],
		];

		if($colorMain) {
			$settings['colorMain'] = [
				'custom' => true,
				'value' => $colorMain
			];
		}

		if($colorSecond) {
			$settings['colorSecond'] = [
				'custom' => true,
				'value' => $colorSecond
			];
		}

		if($textColor) {
			$settings['textColor'] = [
				'custom' => true,
				'value' => $textColor
			];
		}

		if($bgColor) {
			$settings['bgColor'] = [
				'custom' => true,
				'value' => $bgColor
			];
		}
		return $settings;
	}

	private static function addHandlerForSearchButton()
	{
		if ( $page_id = get_option( Rentsyst_PluginSettings::BOOKING_PAGE_ID ) ) {
            $page_id = Rentsyst_Language::getConnectedPagesId($page_id)[Rentsyst_Language::getLanguageCode()] ?? $page_id;
			$booking_page_url = get_permalink( $page_id );
			$script = <<<JS
            document.addEventListener("rentsyst:search", function (event) {
				let rsBokingPageUrl = "$booking_page_url";
                    let params = new URLSearchParams(event.detail.order).toString();
                    params += '&' + 'rentsyst-book-by-search-params=1';
                     if(rsBokingPageUrl.indexOf('?') === -1) {
                        window.location.href = rsBokingPageUrl + '?' + params;
                    } else {
                        window.location.href = rsBokingPageUrl + '&' + params;
                    }
                    return false;
            });
                
JS;
			wp_register_script( Rentsyst_ResourceLoader::PREFIX . 'sear_form', '' );
			wp_enqueue_script( Rentsyst_ResourceLoader::PREFIX . 'sear_form' );
			wp_add_inline_script(Rentsyst_ResourceLoader::PREFIX . 'sear_form', $script);
		}
	}


}

