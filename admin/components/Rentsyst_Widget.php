<?php

namespace rentsyst\admin\components;

use rentsyst\site\Rentsyst_BookingWidget;
use rentsyst\site\Rentsyst_ResourceLoader;
use WP_Widget;

class Rentsyst_Widget extends WP_Widget {

	function __construct() {
		// Запускаем родительский класс
		parent::__construct(
			'rentstyst', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: my_widget
			'Rentsyst booking',
			array('description' => 'Display widget for booking vehicles')
		);

	}

	// Вывод виджета
	function widget( $args, $instance ){
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if( $title )
			echo $args['before_title'] . $title . $args['after_title'];

		require_once WP_RENTSYST_PLUGIN_DIR . '/site/partials/booking.php';

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
		$instance['tabsSwitch']       = sanitize_text_field( $new_instance['tabsSwitch'] );
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
		$tabsSwitch     = isset( $instance['tabsSwitch'] ) ? esc_attr( $instance['tabsSwitch'] ) : $settings['tabsSwitch']['value'];

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
		<p>
			<label for="<?php echo $this->get_field_id( 'tabsSwitch' ); ?>"><?php _e( 'tabsSwitch:' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'tabsSwitch' ); ?>" name="<?php echo $this->get_field_name( 'tabsSwitch' ); ?>">
				<option value="enable" <?php selected( $orientation, 'enable' ); ?>>
					Enable
				</option>
				<option value="disable" <?php selected( $orientation, 'disable' ); ?>>
					Disable
				</option>
			</select>
		</p>

		<?php
	}




	public static function addBlockType()
	{
		add_action('wp_print_scripts', [__CLASS__, 'addActivationScripts']);

		add_action( 'init', [__CLASS__, 'registerBlockTypeGutenberg'] );

		add_action( 'enqueue_block_editor_assets', [__CLASS__, 'gutenberg_block_editor_scripts']);


	}

	public static function gutenberg_block_editor_scripts() {
		$asset_file = include( WP_RENTSYST_PLUGIN_DIR . '/admin/asset.php');

		wp_register_script(
			'rentsyst-block-booking',
			plugins_url( 'rentsyst/admin/js/block-booking.js', WP_RENTSYST_PLUGIN_DIR ),
			$asset_file['dependencies'],
			$asset_file['version']
		);
		wp_register_script(
			'rentsyst-test',
			plugins_url( 'rentsyst/admin/js/test.js', WP_RENTSYST_PLUGIN_DIR ),
			$asset_file['dependencies'],
			$asset_file['version']
		);

	}

	public static function addActivationScripts() {
		$script = <<<JS
window.Rentsyst_Activate = function (setting) {
	if (document.readyState === 'loading') {
	    document.addEventListener("DOMContentLoaded", function() {
	        Rentsyst_Activate_Run(setting);
	    });
	} else {
		 Rentsyst_Activate_Run(setting);
	}
	function Rentsyst_Activate_Run(setting) {
	  RentsystFrame.init(
	  		{
                container: document.getElementById("rentsyst_frame"), 
                settings: setting,
                onResize: function (width, height) {},
                onAutoScroll: function (scrollVal) {}
	  		}
	  	);
	}
};
JS;
		echo "<script>$script</script>";
	}

	public static function registerBlockTypeGutenberg() {

		$settings = ( new Rentsyst_BuilderConstructorSetting )->setSystemSettings()->settings;

		register_block_type( 'rentsyst/rentsyst-booking', array(
			'editor_script' => 'rentsyst-block-booking',
			'example' => [
				'test' => 1,
			],
			'attributes' => [
				'orientation' => [
					'type' => 'string',
					'default' => 'horizontal'
				],
				'tabsSwitch' => [
					'type' => 'string',
					'default' => 'disable'
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
                'marginTop' => [
                    'type' => 'string',
                    'default' => 0,
                ]
			],
			'render_callback' => [__CLASS__, 'renderDynamicContent']
		) );

	}


	public static function renderDynamicContent( $attributes, $test, $instance ) {

		self::gutenberg_block_editor_scripts();
		$settingsDynamic = static::generateSetting($attributes);

		$params = http_build_query([
			'params' => $settingsDynamic
		]);

		$marginTop = $attributes['marginTop'] ?? 0;

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
		$tabsSwitch = ( ! empty( $instance['tabsSwitch'] ) ) ? $instance['tabsSwitch'] : false;

		$settings = [
			'disabledBlocks' => ['header_text'],
			'tabs' => $tabsSwitch === 'enable' ? 1 : !1,
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




}

