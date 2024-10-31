<?php
namespace rentsyst\admin\components;
use Exception;
use rentsyst\includes\UrlConfig;

class Rentsyst_Api
{
	public $baseUrl;

	public static $instance;

	const TOKEN_OPTIONS_NAME = 'rentsyst_access_token';
	public $accessToken = '';

	private function __construct()
	{
		if(get_option( 'rentsyst_auth' )) {
			$this->baseUrl = get_option( 'rentsyst_enable_design_v2' ) ? UrlConfig::getApiUrlV2() : UrlConfig::getApiUrl();
			$this->setAccessToken();
		}
	}

	public static function getInstance()
	{
		if(!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function refreshInstance()
	{
		self::$instance = false;
	}

	public function getToken()
	{
		return $this->accessToken;
	}

	public function getLocations()
	{
		$service_url = $this->getConfig()['locations'];
		$locations = $this->doRequestWithAccess($service_url, [], null);
		return $locations;
	}

	public function getCompanySettings()
	{
		$service_url = $this->getConfig()['companySettings'];
		$company_settings = $this->doRequestWithAccess($service_url);
		return $company_settings;
	}

	public function getLocation($id)
	{
		$service_url = $this->getConfig()['location'];
		$location = $this->doRequestWithAccess($service_url, ['id' => $id]);
		return $location;
	}

	public function getAllVehicles()
	{
		$service_url = $this->getConfig()['vehicles'];
		$vehicles = $this->doRequestWithAccess($service_url);
		return $vehicles;
	}

	/**
	 * @param $name
	 * @param $phone
	 * @param null $vehicle_id
	 * @param null $location
	 *
	 * @return boolean
	 */
	public function addContact($name, $phone, $vehicle_id = null, $location = null)
	{
		$data = [
			'name' => $name,
			'phone' => $phone,
			'vehicle_id' => $vehicle_id,
			'location' => $location,
			'notes' => $location,
		];
		$service_url = $this->getConfig()['createContact'];
		$response = $this->doRequestWithAccess($service_url, $data, true);
		return isset($response->status) && $response->status === 'success';
	}

	private function doRequestWithAccess($route, $queryParams = [], $is_post = null)
	{
		$accessToken = get_option(self::TOKEN_OPTIONS_NAME)->access_token;
		$this->addLanguageParam($queryParams);
		if($is_post) {
			$route = $this->buildGetRequest($route, ['accessToken' => $accessToken]);
		} else {
			$queryParams['accessToken'] = $accessToken;
		}

		return $this->doRequest($route, $queryParams, $is_post);
	}

	private function addLanguageParam(&$queryParams)
	{
		if($language = Rentsyst_Language::getLanguageCode()) {
			$queryParams['lang'] = $language;
		}
	}

	private function doRequest($route, $queryParams = [], $is_post = null){
		$url =  $this->baseUrl . $route;
		if($is_post) {
			$response = wp_remote_post( $url, [
				'timeout'     => 20,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => [],
				'body'        => $queryParams,
				'cookies'     => array()
			]);
		} else {
			$response = wp_remote_get($this->buildGetRequest($url, $queryParams), [
				'timeout'     => 20,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => [],
				'body'        => '',
				'cookies'     => array()
			]);
		}
		if(!is_array($response) || !isset($response['body'])) {
			throw new \Exception($this->baseUrl . ' temporarily not available');
		}
		$result = json_decode($response['body']);
		if(isset($result->status) && in_array($result->status, [400,401,404])) {
			delete_option(self::TOKEN_OPTIONS_NAME);
			throw new \Exception('Rentsyst.com temporarily not available');
		}
		return $result;
	}

	public function buildGetRequest($baseUrl, $queryParams)
	{
		return $baseUrl . '?' . http_build_query($queryParams);
	}

	public function getConfig($id = null)
	{
		$config = [
			'tokenUrl' => '/oauth2/token',
			'bookOrder' => '/booking/search',
			'disableDates' => '/booking/busy-dates',
			'createOrder' => '/order/create',
			'updateOrder' => '/order/update',
			'updateCoupon' => '/order/coupon',
			'paymentDiscount' => '/order/payment-discount',
			'getOrder' => '/order/info',
			'orderConfirm' => '/order/confirm',
			'orderCancel' => '/order/cancel',
			'vehicles' => '/vehicle/index',
			'companySettings' => '/company/settings',
			'createContact' => '/contact/create',
			'getPass' => '/contact/send-pass',
			'getDriver' => '/contact',
			'updateTime' => '/order/add-time',
			'uploadImage' => '/file/upload',
			'getWorkTimes' => '/company/locations/',
			'grantTypeAccessToken' => 'client_credentials',
			'client_id' => get_option( 'rentsyst_auth' )->client_id ?? null,
			'client_secret' => get_option( 'rentsyst_auth' )->client_secret ?? null,
		];
		return $config;
	}

	public function setAccessToken()
	{
		$accessInfo = get_option(self::TOKEN_OPTIONS_NAME);

			if (!$accessInfo || $this->isAccessTokenExpired( $accessInfo->expires_at ) || (isset($accessInfo->status) && $accessInfo->status === 400)) {
				$this->refreshAccessToken();
			} else {
				$this->accessToken = $accessInfo->access_token;
			}
	}

	public function refreshAccessToken()
	{
		try {
			$curl_post_data = array(
				'grant_type'    => $this->getConfig()['grantTypeAccessToken'],
				'client_id'     => $this->getConfig()['client_id'],
				'client_secret' => $this->getConfig()['client_secret']
			);

			$result = $this->doRequest( $this->getConfig()['tokenUrl'], $curl_post_data, true );

			if ( $result ) {
				$this->accessToken = $result->access_token;
				$this->saveAccessTokenInfo( $result );
			}
		} catch (Exception $exception) {
			return 1; // TODO Add notification about connection
		}
	}

	private function saveAccessTokenInfo( $result )
	{
		$result->expires_at = $result->expires_in + time();
		update_option(self::TOKEN_OPTIONS_NAME, $result);
	}

	private function isAccessTokenExpired($expires_at)
	{
		return (($expires_at - time()) < 0) ? true : false;
	}
}


