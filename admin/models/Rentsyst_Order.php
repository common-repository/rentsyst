<?php

class Rentsyst_Order
{
	/**
	 * @var int
	 */
	private static $found_items;
	private $api;

	public $id;
	public $pickup_location;
	public $return_location;
	public $date_range;
	public $date;
	public $vehicle;
	public $insurance;
	public $payment_method;
	public $client;
	public $full_info;
	public $status;

	const STATUS_SEND = 1;
	const STATUS_NOT_SEND = 0;

	public function __construct(Rentsyst_Api $api)
	{
		$this->api = $api;
	}

	public static function find( $args = '' )
	{
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC',
		);

		$args = wp_parse_args( $args, $defaults );

		$whereQuery = '';
		if(isset($args['s']) && $args['s']) {
			$whereQuery .= "WHERE CONCAT(pickup_location, return_location) LIKE \"%{$args['s']}%\"";
		}

		$tableName = self::getTableName();

		$sqlQuery = "SELECT * FROM $tableName $whereQuery ORDER BY {$args['orderby']} {$args['order']} LIMIT {$args['offset']}, {$args['posts_per_page']}";
		$posts = self::getDb()->get_results($sqlQuery);

		self::$found_items = self::getDb()->get_row("SELECT COUNT(*) as row_count FROM $tableName $whereQuery")->row_count;


		return $posts;
	}

	public static function count()
	{
		return self::$found_items;
	}

	public static function delete( $ids )
	{
		if(is_numeric($ids)) {
			$ids[] = $ids;
		}
		$ids = implode( ',', array_map( 'absint', $ids ) );
		$tableName = self::getTableName();
		return self::getDb()->query( "DELETE FROM $tableName WHERE ID IN($ids)" );
	}

	public function uploadFromRequest($request)
	{
		$this->pickup_location = $request['pickup_location'];
		$this->return_location = $request['return_location'];
		$this->date_range = $request['date'];
		$this->date = date('Y/m/d g:i:s A');
		$this->vehicle = $request['vehicle_id'];
		$this->insurance = $request['insurance'];
		$this->payment_method = $request['payment_method'];
		$driver = isset($request['driver'][0]) ? $request['driver'][0] : null;
		if($driver) {
			$this->client = json_encode($driver);
		}
		$this->client = $request['driver'];
		$this->full_info = serialize($request);
	}

	public function transformIdToTitle()
	{
		$location = $this->api->getLocation($this->pickup_location);
		if(isset($location->name)) {
			$this->pickup_location = $location->name;
		}
		$location = $this->api->getLocation($this->return_location);
		if(isset($location->name)) {
			$this->return_location = $location->name;
		}
	}

	public static function getTableName()
	{
		return self::getDb()->base_prefix . 'rentsyst_order';
	}

	public static function getDb()
	{
		global $wpdb;
		return $wpdb;
	}

	public function saveToDb()
	{
		$this->id = self::getDb()->insert($this->getTableName(), [
			'pickup_location' => $this->pickup_location,
			'return_location' => $this->return_location,
			'date_range' => $this->date_range,
			'date' => gmdate('Y-m-d H:i:s'),
			'vehicle' => $this->vehicle,
			'insurance' => $this->insurance,
			'payment_method' => $this->payment_method,
			'client' => $this->client,
			'full_info' => $this->full_info,
		]);
	}

	public static function getStatusList()
	{
		return [
			self::STATUS_SEND => 'Send',
			self::STATUS_NOT_SEND => 'Not send',
		];
	}

}