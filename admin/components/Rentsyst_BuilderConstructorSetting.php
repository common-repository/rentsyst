<?php

namespace rentsyst\admin\components;

use Exception;
use rentsyst\includes\RS_Company;

class Rentsyst_BuilderConstructorSetting
{

	public $settings;
	public $api;

	public function __construct()
	{
		$this->setDefaultSettings();
		$this->api = Rentsyst_Api::getInstance();
	}

	public function getAdminSettings()
	{
		$this->setAdminUrls();
		$this->setSystemSettings();
		$this->setToken();
		return $this->settings;
	}

	public function getPublicSettings()
	{
		$this->setPublicUrls();
		$this->setSystemSettings();
		if(!$this->setToken()) {
			return false;
		}
		return $this->settings;
	}

	public function setSystemSettings()
	{
		$oldSettings = get_option('rentsyst_design_settings');

		if($oldSettings) {
			$this->settings = array_replace_recursive($this->settings, $oldSettings);
		}

		$filters = RS_Company::getInstance()->getFilters();
		if($filters) {
			$savedFilters = $this->settings['filters']['aside'];
			foreach ($filters as $key => $filter) {
				$filters[$key] = $savedFilters[$key];
				$filters[$key]['values'] = [];
				foreach ($filter as $id => $option) {
					$filters[$key]['values'][$id] = $savedFilters[$key]['values'][$id] ?? $option;
				}
			}
			$this->settings['filters']['aside'] = $filters;
		}

		try {
			$locations = RS_Company::getInstance()->getLocations();
			if($locations) {
				$this->settings['locations'] = $locations;
			}
			$payments = RS_Company::getInstance()->getPayments();
			if($payments) {
				$this->settings['payment_method'] = $payments;
			}
			$rangeDriverAge = RS_Company::getInstance()->getDriverAgeRange();
			if($rangeDriverAge && $rangeDriverAge->from) {
				$this->settings['driver_age'] = $rangeDriverAge;
			}
			$rangeRentalPeriod = RS_Company::getInstance()->getRentalPeriodRange();
			if($rangeRentalPeriod && $rangeRentalPeriod->from !== null) {
				$this->settings['rental_days'] = $rangeRentalPeriod;
			}
			$currencyPosition = RS_Company::getInstance()->getCurrencyPosition();
			if($currencyPosition) {
				$this->settings['price_format'] = $currencyPosition;
			}
			$timeForBooking = RS_Company::getInstance()->getTimeForBooking();
			if($timeForBooking) {
				$this->settings['orderTime'] = $timeForBooking * 1000;
				$this->settings['orderUpdateTime'] = $timeForBooking * 1000;
			}
			$hoursBeforeRental = RS_Company::getInstance()->getHoursBeforeRental();
			if($hoursBeforeRental) {
				$this->settings['hours_before_rental'] = $hoursBeforeRental;
			}
			$dateFormat = RS_Company::getInstance()->getDateFormat();
			if($dateFormat) {
				$this->settings['date_format'] = $dateFormat;
			}
			$canDiffReturn = RS_Company::getInstance()->getCanDiffReturn();
			if($canDiffReturn) {
				$this->settings['canDiffReturn'] = $canDiffReturn;
			}
			$baseLocations = RS_Company::getInstance()->getBaseLocations();
			if($baseLocations) {
				$this->settings['baseLocations'] = $baseLocations;
			}
			$this->settings['withCoupon'] = RS_Company::getInstance()->getWithCoupon();

			$acceptedLink = get_permalink(get_option('rentsyst_privacy_policy_page_id'));
			if($acceptedLink) {
				$this->settings['accepted'] = [
					'link' => $acceptedLink,
				];
			}

			if($vehicleParams = get_option('rentsyst_booking_vehicle_params')) {
				$this->settings['vehicle_params'] = $vehicleParams;
			}

		} catch (Exception $exception) {

		}

		if($currentLanguage = Rentsyst_Language::getLanguageCode()) {
			$translationSetting = get_option('rentsyst_design_settings_' . $currentLanguage);
			if($translationSetting) {
				$this->settings = array_replace_recursive($this->settings, $translationSetting);
			}
		}

		if($vehicle_per_page = get_option( 'posts_per_page' )) {
			$this->settings['filters']['per_page']['value'] = $vehicle_per_page;
			$this->settings['filters']['per_page']['values'] = [$vehicle_per_page, $vehicle_per_page*2, $vehicle_per_page*3];
		}

		return $this;

	}

	public function setDefaultSettings()
	{
		$this->settings = [
			'lang' => Rentsyst_Language::getLanguageCode(),
			'isFilters' => true,
			'filters' => [
				'page' => 1,
				'per_page' => [
					'value' => 9,
					'values' => [
						9, 15, 21, 30
					],
				],
				'sort' => [
					'values' => [
						['value' => "price_asc", 'label' => "Sort by low Price"],
						['value' => "price_desc", 'label' => "Sort by high Price" ]
					]
				],
				'aside' => [
					'year' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Year',
						'type' => 'multiple',
						'values' => []
					],
					'number_seats' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Number of seats',
						'type' => 'multiple',
						'values' => []
					],
					'number_doors' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Number of doors',
						'type' => 'multiple',
						'values' => []
					],
					'large_bags' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Large bags',
						'type' => 'multiple',
						'values' => []
					],
					'small_bags' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Small bags',
						'type' => 'multiple',
						'values' => []
					],
					'marks' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Marks',
						'type' => 'multiple',
						'values' => []
					],
					'brands' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Brands',
						'type' => 'multiple',
						'values' => []
					],
					'groups' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Groups',
						'type' => 'multiple',
						'values' => []
					],
					'color' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Color',
						'type' => 'color',
						'values' => []
					],
					'body_type' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Body type',
						'type' => 'multiple',
						'values' => []
					],
					'fuels' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Fuels',
						'type' => 'multiple',
						'values' => []
					],
					'transmissions' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Transmissions',
						'type' => 'multiple',
						'values' => []
					],
					'options' => [
						'fixed' => [],
						'disabled' => 0,
						'title' => 'Options',
						'type' => 'multiple',
						'values' => []
					],
				],
			],
			'locations'  => [
				[ 'id' => 151, 'name' => "Larnaca Office" ],
				[ 'id' => 152, 'name' => "Limassol Office" ],
				[
					'id'   => 153,
					'name' => "Paphos Office"
				]
			],
			'preloader_path' => plugin_dir_url(WP_RENTSYST_PLUGIN_DIR ) . 'rentsyst/resources-v1/img/preloader.gif'    ,
			'rental_days' => [
				'from' => 43200,
				'to' => 31622400
			],
			'driver_age' => [
				'from' => 18,
				'to' => 80
			],
			'steps'      => [
				[ 'view' => "vertical", 'views' => [ "vertical", "horizontal" ], 'allowed' => ! 0 ],
				[
					'view'    => "vertical",
					'views'   => [ "vertical", "horizontal"],
					'allowed' => ! 0
				],
				[ 'view' => "cube", 'views' => [ "vertical", "horizontal", "cube" ], 'allowed' => ! 0 ],
				[
					'view'    => "cube",
					'views'   => [ "vertical", "horizontal", "cube" ],
					'allowed' => ! 0
				]
			],
			'driverForm' =>
				[
					'main_fields'    =>
						[
							0 =>
								[
									0 =>
										[
											'name' => "email",
											'type' => "email",
											'placeholder' => "E-mail",
											'required' => true
										],
									1 =>
										[
											'name' => "code",
											'type' => "text",
											'placeholder' => "Code",
											'required' => false
										]
								],
							1 =>
								[
									0 =>
										[
											'name'        => 'first_name',
											'type'        => 'text',
											'placeholder' => 'First Name',
											'required'    => true,
										],
									1 =>
										[
											'name'        => 'last_name',
											'type'        => 'text',
											'placeholder' => 'Second Name',
											'required'    => true,
										],
								],
							2 =>
								[
									0 =>
										[
											'name'        => 'phone',
											'type'        => 'tel',
											'placeholder' => 'Phone number',
											'required'    => true,

										],
									1 =>
										[
											'name'        => 'country',
											'type'        => 'text',
											'placeholder' => 'Country',
											'required'    => true,
										],
								],
							3 =>
								[
									0 =>
										[
											'name'        => 'city',
											'type'        => 'text',
											'placeholder' => 'City',
											'required'    => true,
										],
									1 =>
										[
											'name'        => 'address',
											'type'        => 'text',
											'placeholder' => 'Address',
											'required'    => true,
										],
								],
							4 =>
								[
									0 =>
										[
											'name'        => 'birthday',
											'type'        => 'picker',
											'placeholder' => 'Date of birthday',
											'required'    => true,
										],
								],
						],
					'license_fields' =>
						[
							0 =>
								[
									0 =>
										[
											'name'        => 'license_num',
											'type'        => 'text',
											'placeholder' => 'Document number',
											'required'    => false,
										],
								],
							1 =>
								[
									0 =>
										[
											'name'        => 'license_from',
											'type'        => 'picker',
											'icon'        => 'calendar',
											'placeholder' => 'Issue date',
											'required'    => false,
										],
									1 =>
										[
											'name'        => 'license_to',
											'type'        => 'picker',
											'icon'        => 'calendar',
											'placeholder' => 'Exp date',
											'required'    => false,
										],
								],
							2 =>
								[
									0 =>
										[
											'name' => 'license_files',
											'type' => 'file',
										],
								],
						],
				],
			'payment_method' => [
				[
					['name' => "Card", 'icon' => "http://mysite.com/test/img/VisaMaster.svg"],
					['name' => "Cash", 'icon' => "/img/cash.png"],
					['name' => "Electronic money", 'icon' => "/img/PayPal.svg"],
					['name' => "Bank transfer", 'icon' => "/img/Bank.svg"],
				]
			],
			'font' => "inherit",
			'withCoupon' => 1,
			'tabs' => 1,
			'tabsColors' => [
				'value' => "#959595",
				'active' => "#000000"
			],
			'colorMain' => [
		        'custom' => false,
				'value' => "#000000"
			],
			'colorSecond' => [
				'custom' => false,
				'value' => "#696969"
			],
			'textColor' => [
				'custom' => false,
				'value' => "#000000"
			],
			'bgColor' => ['custom' => ! 1, 'value' => "#FFFFFF"],
			'loaderColor' => [
				'custom' => false,
				'value' => "#000000"
			],
			'blockTypes' => [
				'vehicle_list' => 0,
				'extras' => 0,
				'summary' => 0
			],
			'icons' => true,
			'disabledBlocks' => [],
			'blocksPadding' => [],
			'text'       => [
				//header
				"header_text" => "%3Cp%3E%3Cspan%20style=%22font-size:38px%22%3E%3Cstrong%3EBuild%20Your%20Service!%3C/strong%3E%3C/span%3E%3C/p%3E%3Cbr/%3E%3Cp%3E%3Cspan%20style=%22font-size:20px%22%3E4%20easy%20steps%20to%20success!%3C/span%3E%3C/p%3E",
				"time_left" => "Time left:",
				//time out popup
				"time_over_text" => "%3Cp%3E%3Cspan%20style=%22font-size:32px%22%3E%3Cstrong%3EBooking%20time%20has%20ended%3C/strong%3E%3C/span%3E%3C/p%3E%3Cbr/%3E%3Cp%3E%3Cspan%20style=%22font-size:20px%22%3EStart%20over%3C/span%3E%3C/p%3E",
				"time_over_button" => "Start",
				//time is running out popup
				"time_left_text" => "%3Cp%3E%3Cspan%20style=%22font-size:32px%22%3E%3Cstrong%3EAre%20you%20still%20here?%3C/strong%3E%3C/span%3E%3C/p%3E",
				"time_left_button" => "Yes",
				//tabs buttons
				"tab_prev_btn" => "%3Cstrong%3EPrev%3C/strong%3E",
				"tab_next_btn" => "%3Cstrong%3ENext%3C/strong%3E",
				//step 1
				"location_label_pick" => "%3Cstrong%3EPick%20Up%3C/strong%3E",
				"location_label_return" => "%3Cstrong%3EReturn%3C/strong%3E",
				"location_placeholder_pick" => "Choose%20loction",
				"location_placeholder_return" => "Choose%20loction",
				"location_gm_btn" => "Google%20Maps",
				"location_check_return" => "I%20want%20to%20return%20to%20different%20location",
				"date_from_label" => "%3Cstrong%3EDate%20From%3C/strong%3E",
				"date_from_placeholder" => "Select%20date%20from",
				"date_to_label" => "%3Cstrong%3EDate%20To%3C/strong%3E",
				"date_to_placeholder" => "Select%20date%20to",
				"show_car_btn" => "%3Cstrong%3EShow%20car%3C/strong%3E",
				//step 2
				"transmission" => "Transmission:",
				"big_luggage" => "Big luggage:",
				"adults" => "Adults:",
				"doors" => "Doors:",
				'mileage_limit' => "Mileage limit:",
				'refill' => "Refill:",
				'vehicle_year' => "Year:",
				'vehicle_seats' => "Number seats:",
				'vehicle_volume_engine' => "Engine capacity:",
				'vehicle_volume_tank' => "Tank capacity:",
				'vehicle_odometer' => "Odometer:",
				"book_btn" => "%3Cstrong%3EBook%3C/strong%3E",
				"filters_title" => "Filters",
				"submit_filters" => "%3Cstrong%3ESubmit%3C/strong%3E",
				"reset_filters" => "%3Cstrong%3EReset%3C/strong%3E",
				"sort_select_placeholder" => "Choose%20sort",
				"pagination_showing" => "Showing",
				"pagination_of" => "of",
				"results_found" => "Results%20Found",
				"vehicle_days_total" => "Total%20for",
				"vehicle_days_days" => "days",
				"vehicle_price_total" => "Total:",
				"vehicle_days_horizontal" => "Number of days:",
				//step 3
				"extas_title" => "Extras",
				"extras_subtitle_equipment" => "%3Cstrong%3EEquipment%20and%20services%3C/strong%3E",
				"extras_subtitle_insurence" => "%3Cstrong%3EInsurance%3C/strong%3E",
				"continue_btn" => "%3Cstrong%3EContinue%3C/strong%3E",
				"security_deposit" => "SECURITY%20DEPOSIT",
				"damage_excess" => "DAMAGE%20EXCESS",
				"extras_price_day" => "/%20day",
				"insurance_price_day" => "/%20day",
				//step 4
				"customer_title" => "Customer",
				"head_driver" => "%3Cstrong%3EHead%20driver%3C/strong%3E",
				"advansed_driver" => "%3Cstrong%3EAdvanced%20driver%3C/strong%3E",
				"driver_license" => "%3Cstrong%3EDrive%20licence%3C/strong%3E",
				"payment_subtitle" => "%3Cstrong%3EPayment%3C/strong%3E",
				"confirm_btn" => "%3Cstrong%3EConfirm%3C/strong%3E",
				"required_text" => "*%20-%20Required%20fields",
				"form_first_name" => "First%20Name",
				"form_last_name" => "Second%20Name",
				"form_email" => "E-mail",
				"form_password" =>  "Password",
				"form_password_send" => "Send%20&rarr;",
				"form_phone" => "Phone%20number",
				"form_country" => "Country",
				"form_city" => "City",
				"form_address" => "Address",
				"form_birthday" => "Date%20of%20birthday",
				"form_license_num" => "Document%20number",
				"form_license_from" => "Issue%20date",
				"form_license_to" => "Exp%20date",
				"form_email_reg" => "f%20you%20have%20made%20a%20reservation%20before%20-%20this%20will%20speed%20up%20the%20booking%20process",
				"form_code_send" => "he%20code%20has%20been%20sent%20to%20Email:",
				"form_code_not_received" => "Didn\u0027t%20get%20the%20code?",
				"form_try_again" => "%3Cstrong%3E%3Cu%3ETry%20again%3C\/u%3E%3C\/strong%3E",
				"form_try_over" => "after",
				"form_try_seconds" => "seconds",
				"license_files" => "%3Cstrong%3EUpload%20licence%3C/strong%3E",
				"add_driver_btn" => "%3Cstrong%3EAdd%20new%20driver%3C/strong%3E",
				"accept_link" => "I%20accept%20the%20Privacy%20Policy",
				"coupon_title" => "%3Cstrong%3ECoupon%3C/strong%3E",
				"coupon_activate" => "Add coupon",
				"apply_coupon" => "Apply",
				//summary
				"summary_title" => "Summary",
				"summary_label_date_from" => "%3Cstrong%3EPick%20Up:%3C/strong%3E",
				"summary_label_location_from" => "%3Cstrong%3ELocation:%3C/strong%3E",
				"summary_label_date_to" => "%3Cstrong%3EReturn:%3C/strong%3E",
				"summary_label_location_to" => "%3Cstrong%3ELocation:%3C/strong%3E",
				"summary_edit_btn" => "%3Cstrong%3EChange%3C/strong%3E",
				"summary_subtitle_extras" => "%3Cstrong%3EExtras%3C/strong%3E",
				"summary_subtitle_taxes" => "%3Cstrong%3ETaxes%3C/strong%3E",
				"summary_subtitle_delivery" => "%3Cstrong%3EDelivery%3C/strong%3E",
				"summary_subtitle_coupon" => "%3Cstrong%3ECoupon%3C/strong%3E",
				"summary_subtitle_discount" => "%3Cstrong%3EDiscount%3C/strong%3E",
				"summary_subtitle_total" => "%3Cstrong%3ETotal%3C/strong%3E",
				"summary_subtitle_driver" => "%3Cstrong%3EDriver%3C/strong%3E",
				"additional_info" => "%3Cstrong%3EAdditional%20information%3C/strong%3E",
				"summary_security_deposit" => "Security%20deposit:",
				"summary_damage_excess" => "Damage%20excess:",
				"summary_total_tax" => "Total",
				"summary_rental_days_type_left" => "Rental%20period",
				"summary_rental_days_type_right" => "days",
			],
			'textStyles' => [
				"summary_rental_days_type1" => "font-weight:bold",
				"form_send_email" => "font-weight:bold"
			],
			'accepted' => [
				'status' => 0,
				'link' => ''
			],
			'license'   => ! 0,
			'orderTime' => 600000,
			'orderUpdateTime' => 600000,
			'hours_before_rental' => 2,
			'date_format' => "dd/MM/yyyy HH:mm",
			'price_format' => '{currency}&nbsp;{price}',
			'canDiffReturn' => true,
			'baseLocations' => false,
			'updateToken' => true,
		];
	}

	public function setAdminUrls()
	{

		$url = get_rest_url( 0, '/rentsyst/v1/settings/save'  );

		if(str_contains($url, '?')) {
			$url .= '&';
		} else {
			$url .= '?';
		}

		$url .= 'lang=' . Rentsyst_Language::getLanguageCode();

		$adminUrl['urls'] = [
			'setApp' => [
				'link' => $url,
				'params' => [
					'version' => 'v1'
				]
			],
		];

		$this->settings = array_merge($this->settings, $adminUrl);
	}



	public function setPublicUrls()
	{

		$publicUrl['urls'] = [
			'bookOrder' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['bookOrder']
			],
			'createOrder' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['createOrder']
			],
            'updateOrder' => [
            	'link' => $this->api->baseUrl . $this->api->getConfig()['updateOrder']
            ],
			'updateCoupon' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['updateCoupon']
			],
            'orderConfirm' => [
            	'link' => $this->api->baseUrl . $this->api->getConfig()['orderConfirm']
            ],
            'orderCancel' => [
            	'link' => $this->api->baseUrl . $this->api->getConfig()['orderCancel']
            ],
			'disableDates' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['disableDates']
			],
			'updateTime' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['updateTime']
			],
			'getWorkTimes' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['getWorkTimes']
			],
			'updatePayment' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['paymentDiscount']
			],
			'getPass' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['getPass'],
				'params' => [],
			],
			'getDriver' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['getDriver'],
				'params' => []
			],
			'updateToken' => [
				'link' => get_rest_url( 0, '/rentsyst/v1/access-token'),
			],
		];
		$publicUrl['return_urls'] = [
			'return_url' =>  get_page_link(get_option('rentsyst_confirmation_page_id')),
		];
		$this->settings = array_merge($this->settings, $publicUrl);
	}

	public function setToken()
	{
		$token['accessToken'] = $this->api->getToken();
		$this->settings = array_merge($this->settings, $token);
		return (boolean) $token['accessToken'];
	}
}
