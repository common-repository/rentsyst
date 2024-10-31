<?php
namespace rentsyst\includes;
use rentsyst\admin\components\Rentsyst_View;

class Rentsyst_CatalogFilter
{

	const FILTERS_OPTIONS_ID = 'filter_options_id';
	const FILTER_PRICE_RANGE = 'filter_price_range';

	public static $filters = [];

	public function __construct()
	{
		$this->setDefaultSort();
		$this->configurate( $_GET['rentsyst'] ?? []);
		$this->setFilters();
	}

	public static function getParams()
	{
		$instance = new self();
		return $instance::$filters;
	}

	public static function isEnabled()
	{
		return Rentsyst_PluginSettings::getSettings()[Rentsyst_PluginSettings::CATALOG_FILTER_ENABLE];
	}

	public static function getAccentColor()
	{
		return Rentsyst_PluginSettings::getSettings()[Rentsyst_PluginSettings::CATALOG_FILTER_ACCENT_COLOR];
	}

	public static function getFixedPosition()
	{
		return Rentsyst_PluginSettings::getSettings()[Rentsyst_PluginSettings::CATALOG_FILTER_ENABLE_FIX];
	}

	public static function getPosition()
	{
		return self::isEnabled() ? Rentsyst_PluginSettings::getSettings()[Rentsyst_PluginSettings::CATALOG_FILTER_POSITION] : false;
	}

	private function setDefaultSort()
	{

		set_query_var( 'orderby', 'meta_value_num' );
		set_query_var( 'meta_key', 'price' );
		set_query_var( 'order', 'ASC' );

	}

	private function setFilters()
	{
		$args = array_merge(['relation' => 'AND'], self::$filters);
		set_query_var('meta_query', $args);
		if(!is_admin()) {
			set_query_var( 'post_status', 'publish' );
		}
	}

	public function configurate( $filters )
	{
		$allOptions = self::getOptions();
		foreach ($filters as $filter_name => $options) {
			if($filter_name === 'options') {
				foreach ($options as $option => $value) {
					self::$filters[] = [
						'key' => $filter_name,
						'value' => $option,
					];
				}
			} elseif(isset($allOptions[$filter_name])) {
				self::$filters[] = [
					'key' => $filter_name,
					'value' => array_keys($options),
				];

			} elseif ($filter_name === 'price') {
				$borders = explode('-',$options);
				self::$filters[] = [
					'key' => 'min_price',
					'value' => $borders,
					'compare' => 'BETWEEN',
					'type' => "DECIMAL"
				];
			}
		}
	}

	public static function display($forSidebar = false)
	{
		$filters = self::getPrepareOptions();
		$rangePrice = self::getRangePrices();
		$accentColor = self::getAccentColor();
		$filterNames = self::getFilterNames();
		wp_enqueue_style(  'rentsyst_catalog', WP_RENTSYST_PLUGIN_URL . '/site/css/catalog.css', array(), RENTSYST_VERSION, 'all' );
		$fixPosition = $forSidebar ? false : self::getFixedPosition();
		$view             = new Rentsyst_View();
		$view->fullPath = WP_RENTSYST_PLUGIN_DIR . '/site/partials/filter.php';
		return $view->render(null, [
			'filters' => $filters,
			'rangePrice' => $rangePrice,
			'accentColor' => $accentColor,
			'filterNames' => $filterNames,
			'fixPosition' => $fixPosition
		]);
	}

	public static function saveOptions( array $filters )
	{
		update_option(self::FILTERS_OPTIONS_ID, $filters);
	}

	public static function getOptions()
	{
		return apply_filters('rentsyst_catalog_render_filters', get_option(self::FILTERS_OPTIONS_ID));
	}

	public static function getPrepareOptions()
	{
		return self::prepareOptions(self::getOptions());
	}

	private static function prepareOptions($filters)
	{
		if(!$filters) {
			return  [];
		}

		foreach ($filters as $filter_name => $options) {
			$filters[ $filter_name ] = self::applySettingsOptions( $filter_name, $options );
		}

		return $filters;
	}

	private static function applySettingsOptions( $filter_name, $options )
	{
		if(isset(self::settingsOptions()[$filter_name]['sort'])) {
			$settings = self::settingsOptions()[$filter_name];
		} else {
			$settings = self::settingsOptions()['default'];
		}
		$options = $settings['sort']($options);

		if(isset(self::settingsOptions()[$filter_name]['title'])) {
			$settings = self::settingsOptions()[$filter_name];
		} else {
			$settings = self::settingsOptions()['default'];
		}
		$newOptions = [];
		foreach ($options as $key => $title) {
			$newOptions[$title] = $settings['title']($title);
		}
		return $newOptions;
	}

	public static function settingsOptions()
	{
		return [
			'default' => [
				'sort' => function($options) {
					arsort($options);
					return $options;
				},
				'title' => function($title) {
					return $title;
				}
			],
			'color' => [
				'title' => function($title) {
					return '<span style="background-color: #' . $title . '" class="rentsyst-color-badge"></span>';
				}
			],
		];
	}

	public static function saveRangePrices( array $prices )
	{
		update_option(self::FILTER_PRICE_RANGE, [
			'min' => (int) min($prices),
			'max' => (int) max($prices) + 1,
		]);
	}

	public static function getRangePrices()
	{
		$result = get_option(self::FILTER_PRICE_RANGE);
		if(isset($_GET['rentsyst']['price'])){
			$setRange = explode('-', sanitize_text_field($_GET['rentsyst']['price']));
		}
		$result['start'] = $setRange[0] ?? $result['min'];
		$result['end'] = $setRange[1] ?? $result['max'];
		return $result;
	}

	private static function getFilterNames()
	{
		return [
			'year' => __('Year', 'rentsyst'),
			'number_seats' => __('Number of seats', 'rentsyst'),
			'number_doors' => __('Number of doors', 'rentsyst'),
			'large_bags' => __('Large bags', 'rentsyst'),
			'small_bags' => __('Small bags', 'rentsyst'),
			'brand' => __('Brand', 'rentsyst'),
			'group' => __('Group', 'rentsyst'),
			'color' => __('Color', 'rentsyst'),
			'body_type' => __('Body type', 'rentsyst'),
			'fuel' => __('Fuel', 'rentsyst'),
			'transmission' => __('Transmission', 'rentsyst'),
			'options' => __('Options', 'rentsyst'),
		];
	}
}
