<?php

namespace rentsyst\admin\components;

use Exception;
use rentsyst\includes\RS_Company;
use function array_merge_recursive;
use function var_dump;
use const RENTSYST_PLUGIN_NAME;
use const WP_RENTSYST_PLUGIN_URL;

class Rentsyst_BuilderConstructorSettingV2
{

	public $settings = [];
	public $api;

	public function __construct()
	{
		$this->setDefaultSettings();
		$this->api = Rentsyst_Api::getInstance();
	}

	public function getAdminSettings()
	{
		$this->setAdminUrls();
		$this->setPublicUrls();
		$this->setSystemSettings();
		$this->setToken();

		return $this->settings;
	}

	public function getPublicSettings(): array
	{
		$this->setPublicUrls();
		$this->setSystemSettings();
		if ( ! $this->setToken() ) {
			return [];
		}

		return $this->settings;
	}

	public function setSystemSettings()
	{
		$oldSettings = get_option( 'rentsyst_design_settings_v2' );

		if ( $oldSettings ) {
			$this->settings = array_replace_recursive( $this->settings, $oldSettings );
		}

		$filters = RS_Company::getInstance()->getFilters();
		if ( $filters ) {
			$savedFilters = $this->settings['components']['step2.filters.list']['settings']['common']['filters'];
			foreach ( $filters as $key => $filter ) {
				$filters[ $key ]           = $savedFilters[ $key ];
				$filters[ $key ]['values'] = [];
				foreach ( $filter as $id => $option ) {
					$filters[ $key ]['values'][ $id ] = $savedFilters[ $key ]['values'][ $id ] ?? $option;
				}
			}
			$this->settings['components']['step2.filters.list']['settings']['common']['filters'] = $filters;
		}

		try {
			$locations = RS_Company::getInstance()->getLocations();
			if ( $locations ) {
				$this->settings['locations'] = $locations;
			}
			$payments = RS_Company::getInstance()->getPayments();
			if ( $payments ) {
				$this->settings['payment_method'] = $payments;
			}
			$rangeDriverAge = RS_Company::getInstance()->getDriverAgeRange();
			if ( $rangeDriverAge && $rangeDriverAge->from ) {
				$this->settings['driverForm'] = [
					'ageLimit' => $rangeDriverAge
				];
			}
			$rangeRentalPeriod = RS_Company::getInstance()->getRentalPeriodRange();
			if ( $rangeRentalPeriod && $rangeRentalPeriod->from !== null ) {
				$this->settings['rental_days'] = $rangeRentalPeriod;
			}
			$currencyPosition = RS_Company::getInstance()->getCurrencyPosition();
			if ( $currencyPosition ) {
				$this->settings['price_format'] = $currencyPosition;
			}
			$timeForBooking = RS_Company::getInstance()->getTimeForBooking();
			if ( $timeForBooking ) {
				$this->settings['timer']       = [
					'initialValue' => $timeForBooking * 1000,
					'updateValue' => $timeForBooking * 1000
				];
			}
			$hoursBeforeRental = RS_Company::getInstance()->getHoursBeforeRental();
			if ( $hoursBeforeRental ) {
				$this->settings['hours_before_rental'] = $hoursBeforeRental;
			}
			$dateFormat = RS_Company::getInstance()->getDateFormat();
			if ( $dateFormat ) {
				$this->settings['date_format'] = $dateFormat;
			}

			$this->settings['isDifferentLocationEnabled'] = RS_Company::getInstance()->isDifferentLocationEnabled();

			$isCustomLocationEnabled = RS_Company::getInstance()->isCustomLocationEnabled();
			if ( $isCustomLocationEnabled ) {
				$this->settings['isCustomLocationEnabled'] = $isCustomLocationEnabled;
			}
			$this->settings['isCouponEnabled'] = RS_Company::getInstance()->isCouponEnabled();

			$acceptedLink = get_permalink( get_option( 'rentsyst_privacy_policy_page_id' ) );
			if ( $acceptedLink ) {
				$this->settings['privacyPolicy'] = [
					'link' => $acceptedLink,
					'isDefaultChecked' => 0,
				];
			}

			if ( $vehicleParams = get_option( 'rentsyst_booking_vehicle_params' ) ) {
				$this->settings['vehicle_params'] = $vehicleParams;
			}

		} catch ( Exception $exception ) {

		}

		if ( $currentLanguage = Rentsyst_Language::getLanguageCode() ) {
			$translationSetting = get_option( 'rentsyst_design_settings_v2_' . $currentLanguage );
			if ( $translationSetting ) {
				$this->settings = array_replace_recursive( $this->settings, $translationSetting );
			}
		}

		return $this;

	}

	public function setDefaultSettings()
	{

		$this->settings = [
			'lang'                => Rentsyst_Language::getLanguageCode(),
			'resourcesPath' => WP_RENTSYST_PLUGIN_URL . '/resources-v2/',
			'pluginVersion' => RENTSYST_VERSION,
			'isFilters'           => true,
			'payment_page' => [
				'order_id' => 123,
				'type' => 'success',
			],
			'locations'           => [
				[ 'id' => 151, 'name' => "Larnaca Office" ],
				[ 'id' => 152, 'name' => "Limassol Office" ],
				[
					'id'   => 153,
					'name' => "Paphos Office"
				]
			],
			'rental_days'         => [
				'from' => 43200,
				'to'   => 31622400
			],
			'driverForm'          => [
				'ageLimit' => [
					'from' => 18,
					'to'   => 80
				]
			],
			'timer' => [
				'initialValue' => 1000*600,
				'updateValue' => 1000*600
			],
			'payment_method'      => [
				[
					[
						'id'       => 'card',
						'type'     => 'icon',
						'name'     => "Card",
						'icon'     => "https://rentsyst.com/assets/623a908c/img/VisaMaster.svg",
						'discount' => [
							'name' => 'discount 10%'
						]
					],
					[
						'id'   => 'cash',
						'type' => 'icon',
						'name' => "Cash",
						'icon' => "/img/cash.png"
					],
					[
						'id'   => 'e-money',
						'type' => 'icon',
						'name' => "Electronic money",
						'icon' => "https://rentsyst.com/assets/623a908c/img/svg/paydunya.svg"
					],
					[
						'id'   => 'bank-transfer',
						'type' => 'icon',
						'name' => "Bank transfer",
						'icon' => "https://rentsyst.com/assets/623a908c/img/Bank.svg"
					],
				]
			],
			'font'                => "inherit",
			'isCouponEnabled'          => 1,

			'icons'               => true,
			'privacyPolicy'            => [
				'isDefaultChecked' => 0,
				'link'   => ''
			],
			'license'             => ! 0,
			'orderTime'           => 600000,
			'orderUpdateTime'     => 600000,
			'hours_before_rental' => 2,
			'date_format'         => "dd/MM/yyyy HH:mm",
			'price_format'        => '{currency}&nbsp;{price}',
			'isDifferentLocationEnabled'       => true,
			'isCustomLocationEnabled'       => false,
			'updateToken'         => true,


			'global' => [
				'componentSize' => 'large',
				'prefixCls'     => 'rs',
				'darkMode'      => ! 1,
				'editMode'      => ! 1,
				'theme'         => [
					'token' => [
						'colorPrimary'     => '#1677ff',
						'colorBgLayout'    => 'rgba(255,255,255,0)',
						'colorText'        => '#1E1E1E',
						'fontFamily'       => 'Roboto',
						'fontSize'         => 14,
						'fontSizeHeading5' => 16,
						'borderRadius'     => 6,
						'borderRadiusLG'   => 8,
						'borderRadiusSM'   => 4,
						'borderRadiusXS'   => 2,
						'fontSizeHeading1' => 38,
						'fontSizeHeading4' => 20,
					],
				],
			],

			"components" => [
				"title"                             => [
					"text"   => [
						"Title" => __('Welcome to the Booking Page', RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"description"                       => [
					"text"   => [
						"Description" => __("Reserve your next trip with us", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"timer"                             => [
					"text"     => [
						"Time left:"          => __("Booking Time:", RENTSYST_PLUGIN_NAME),
						"Are you still here?" => __("Continue Booking?", RENTSYST_PLUGIN_NAME),
						"Yes"                 => __("Yes", RENTSYST_PLUGIN_NAME),
						"Cancel"              => __("Cancel", RENTSYST_PLUGIN_NAME),
						"min"                 => __("min", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"steps"                             => [
					"text"     => [
						"Date & Location"    => __("Dates", RENTSYST_PLUGIN_NAME),
						"Vehicles Catalog"   => __("Vehicles", RENTSYST_PLUGIN_NAME),
						"Extras & Insurance" => __("Extras", RENTSYST_PLUGIN_NAME),
						"Booking"            => __("Order", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showDescription" => true,
							"direction"       => "row",
							"width"           => 70
						],
						"xs"     => [
							"showDescription" => false,
							"width"           => 100
						],
						"sm"     => [
							"direction" => "column"
						],
						"lg"     => [
						]
					]
				],
				"step1"                             => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showSteps"               => true,
							"showDescription"         => true,
							"orientationLocationForm" => "column"
						],
						"xs"     => [
						],
						"sm"     => [
							"orientationLocationForm" => "row"
						],
						"lg"     => [
							"orientationLocationForm" => "row"
						]
					]
				],
				"step1.form"                        => [
					"text"     => [
						"Choose location"        => __("Choose location", RENTSYST_PLUGIN_NAME),
						"No available vehicles"        => __("Sorry, no available vehicles for this dates", RENTSYST_PLUGIN_NAME),
						"Choose return location" => __("Choose return location", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"direction"        => "row",
							"width"            => 50,
						],
						"xs"     => [
							"direction" => "column",
							"width"     => 100
						],
						"sm"     => [
							"width" => 60
						],
						"lg"     => [
						]
					]
				],
				"step1.form.pickup.label"           => [
					"text"   => [
						"Pickup location" => __("Pickup location", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step1.form.return.label"           => [
					"text"   => [
						"Return" => __("Return", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step1.form.checkbox"               => [
					"text"   => [
						"I want to return to different location" => __("I want to return to different location", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step1.form.dates.label"            => [
					"text"   => [
						"Select date" => __("Select dates", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step1.form.dates.picker"           => [
					"text"     => [
						"Select start day"         => __("Select start day", RENTSYST_PLUGIN_NAME),
						"Select end day"           => __("Select end day", RENTSYST_PLUGIN_NAME),
						"Dates range is required"  => __("Dates range is required", RENTSYST_PLUGIN_NAME),
						"Minimum rental period is" => __("Sorry, the minimum rental period is", RENTSYST_PLUGIN_NAME),
						"Maximum rental period is" => __("Sorry, the maximum rental period is", RENTSYST_PLUGIN_NAME),
						"Ok"                       => __("Ok", RENTSYST_PLUGIN_NAME),
						"Clear"                    => __("Clear", RENTSYST_PLUGIN_NAME),
						"Date is busy"             => __("Sorry, but chosen dates are busy for this car", RENTSYST_PLUGIN_NAME),
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"dateTimeFormat" => "DD.MM.YY HH:mm"
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step1.form.google-button"          => [
					"text"   => [
						"Google Maps" => __("Google Maps", RENTSYST_PLUGIN_NAME),
						"Search"      => __("Search", RENTSYST_PLUGIN_NAME),
						"Ok"          => __("Ok", RENTSYST_PLUGIN_NAME),
						"Cancel"      => __("Cancel", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step1.form.submit"                 => [
					"text"   => [
						"Show car" => __("Show results", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2"                             => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showSteps"       => true,
							"showDescription" => true,
							"isModalFilters"  => false,
							"showFilters"     => true,
							"width"           => 70
						],
						"xs"     => [
							"isModalFilters" => true,
							"width"          => 100
						],
						"sm"     => [
							"isModalFilters" => true,
							"width"          => 100
						],
						"lg"     => [
						]
					]
				],
				"step2.content"                     => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
					]
				],
				"step2.vehicles"                    => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"direction" => "row",
							"count"     => "2"
						],
						"xs"     => [
							"count" => "1"
						],
						"sm"     => [
							"count"     => "1",
							"direction" => "column"
						],
						"lg"     => [
						]
					]
				],
				"step2.vehicle.count-result"        => [
					"text"   => [
						"Results Found" => __("{count} Results Found", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.vehicle.select-sort"         => [
					"text"   => [
						"Choose sort"        => __("Choose sort", RENTSYST_PLUGIN_NAME),
						"Sort by low price"  => __("Sort by low price", RENTSYST_PLUGIN_NAME),
						"Sort by high price" => __("Sort by high price", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.vehicle.carousel"            => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"dotsCarousel"     => true,
							"autoplayCarousel" => true
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step2.vehicle.title"               => [
					"text"   => [
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.vehicle.desc"                => [
					"text"   => [
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.vehicle.parameters"          => [
					"text"     => [
						"Type"            => __("Type", RENTSYST_PLUGIN_NAME),
						"Brand"           => __("Brand", RENTSYST_PLUGIN_NAME),
						"Number of doors" => __("Number of doors", RENTSYST_PLUGIN_NAME),
						"Large bags"      => __("Large bags", RENTSYST_PLUGIN_NAME),
						"Transmission"    => __("Transmission", RENTSYST_PLUGIN_NAME),
						"Mileage limit"   => __("Mileage limit", RENTSYST_PLUGIN_NAME),
						"Fuel policy"     => __("Fuel policy", RENTSYST_PLUGIN_NAME),
						"Volume engine"   => __("Volume engine", RENTSYST_PLUGIN_NAME),
						"Fuel type"       => __("Fuel type", RENTSYST_PLUGIN_NAME),
						"Number of seats" => __("Number of seats", RENTSYST_PLUGIN_NAME),
						"Wheel Drive"     => __("Wheel Drive", RENTSYST_PLUGIN_NAME),
						"Volume Tank"     => __("Volume Tank", RENTSYST_PLUGIN_NAME),
						"Vehicle Group"   => __("Vehicle Group", RENTSYST_PLUGIN_NAME),
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showIcon"   => false,
							"direction"  => "row",
							"parameters" => [
								[
									"label" => "Type",
									"param" => "type",
									"show"  => true,
									"icon"  => "fico fico-vehicles"
								],
								[
									"label" => "Brand",
									"param" => "brand",
									"show"  => false,
									"icon"  => "fico fico-vehicles"
								],
								[
									"label" => "Number of doors",
									"param" => "number_doors",
									"show"  => true,
									"icon"  => "fico fico-door"
								],
								[
									"label" => "Large bags",
									"param" => "large_bags",
									"show"  => true,
									"icon"  => "fico fico-trunk"
								],
								[
									"label" => "Small bags",
									"param" => "small_bags",
									"show"  => true,
									"icon"  => "fico fico-profile"
								],
								[
									"label" => "Transmission",
									"param" => "transmission",
									"show"  => true,
									"icon"  => "fico fico-transmission"
								],
								[
									"label" => "Mileage limit",
									"param" => "mileage_limit",
									"show"  => false,
									"icon"  => "fico fico-gauge"
								],
								[
									"label" => "Fuel policy",
									"param" => "refill",
									"show"  => false,
									"icon"  => "fico fico-oil2"
								],
								[
									"label" => "Year",
									"param" => "year",
									"show"  => false,
									"icon"  => "fico fico-calendar"
								],
								[
									"label" => "Volume engine",
									"param" => "volume_engine",
									"show"  => false,
									"icon"  => "fico fico-engine"
								],
								[
									"label" => "Fuel type",
									"param" => "fuel",
									"show"  => false,
									"icon"  => "fico fico-gas-station"
								],
								[
									"label" => "Number of seats",
									"param" => "number_seats",
									"show"  => false,
									"icon"  => "fico fico-contacts"
								],
								[
									"label" => "Wheel Drive",
									"param" => "wheel_drive",
									"show"  => false,
									"icon"  => "fico fico-wheel"
								],
								[
									"label" => "Volume Tank",
									"param" => "volume_tank",
									"show"  => false,
									"icon"  => "fico fico-fuel-full"
								],
								[
									"label" => "Vehicle Group",
									"param" => "volume_tank",
									"show"  => false,
									"icon"  => "fico fico-replacement"
								],
							]
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step2.vehicle.price-info"          => [
					"text"   => [
						"Total days" => __("Total for {count} days", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.vehicle.price"               => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showOldPrice"   => true,
							"directionPrice" => "row"
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step2.vehicle.button"              => [
					"text"   => [
						"Book" => "Book"
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.filters.title"               => [
					"text"     => [
						"Filters" => "Filters"
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"xs" => [
						],
						"sm" => [
						],
						"lg" => [
						]
					]
				],
				"step2.filters.list"                => [
					"text"     => [
						"Year"            => __("Year", RENTSYST_PLUGIN_NAME),
						"Number of seats" => __("Number of seats", RENTSYST_PLUGIN_NAME),
						"Number of doors" => __("Number of doors", RENTSYST_PLUGIN_NAME),
						"Large bags"      => __("Large bags", RENTSYST_PLUGIN_NAME),
						"Small bags"      => __("Small bags", RENTSYST_PLUGIN_NAME),
						"Marks"           => __("Marks", RENTSYST_PLUGIN_NAME),
						"Brands"          => __("Brands", RENTSYST_PLUGIN_NAME),
						"Groups"          => __("Groups", RENTSYST_PLUGIN_NAME),
						"Color"           => __("Color", RENTSYST_PLUGIN_NAME),
						"Body type"       => __("Body type", RENTSYST_PLUGIN_NAME),
						"Fuels"           => __("Fuels", RENTSYST_PLUGIN_NAME),
						"Transmissions"   => __("Transmissions", RENTSYST_PLUGIN_NAME),
						"Options"         => __("Options", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"filters" => [
								"year"          => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Year",
									"type"     => "multiple",
									"values"   => []
								],
								"number_seats"  => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Number of seats",
									"type"     => "multiple",
									"values"   => []
								],
								"number_doors"  => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Number of doors",
									"type"     => "multiple",
									"values"   => []
								],
								"large_bags"    => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Large bags",
									"type"     => "multiple",
									"values"   => []
								],
								"small_bags"    => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Small bags",
									"type"     => "multiple",
									"values"   => []
								],
								"marks"         => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Marks",
									"type"     => "multiple",
									"values"   => []
								],
								"brands"        => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Brands",
									"type"     => "multiple",
									"values"   => []
								],
								"groups"        => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Groups",
									"type"     => "multiple",
									"values"   => []
								],
								"color"         => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Color",
									"type"     => "color",
									"values"   => []
								],
								"body_type"     => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Body type",
									"type"     => "multiple",
									"values"   => []
								],
								"fuels"         => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Fuels",
									"type"     => "multiple",
									"values"   => []
								],
								"transmissions" => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Transmissions",
									"type"     => "multiple",
									"values"   => []
								],
								"options"       => [
									"fixed"    => [
									],
									"disabled" => 0,
									"title"    => "Options",
									"type"     => "multiple",
									"values"   => []
								]
							]
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step2.filters.reset-button"        => [
					"text"   => [
						"Reset" => __("Reset", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step2.filters.submit-button"       => [
					"text"   => [
						"Submit" => __("Apply", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step3"                             => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showSteps"       => true,
							"showDescription" => true,
							"isModalSummary"  => false,
							"width"           => 65
						],
						"xs"     => [
							"isModalSummary" => true,
							"width"          => 100
						],
						"sm"     => [
							"isModalSummary" => true,
							"width"          => 100
						],
						"lg"     => [
						]
					]
				],
				"step3.content"                     => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
					]
				],
				"step3.extras"                      => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
					]
				],
				"summary"                           => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"photo"      => true,
							"parameters" => true
						],
						"xs"     => [
							"photo" => false
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"summary.content"                   => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
					]
				],
				"step3.extras.title"                => [
					"text"   => [
						"Extras" => __("Extras", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step3.equipment.title"             => [
					"text"   => [
						"Equipment and services" => __("Equipment and services", RENTSYST_PLUGIN_NAME)
					],
					"theme"  => [
					],
					"style"  => [
					],
					"layout" => [
					]
				],
				"step3.equipment.list"              => [
					"text"     => [
						"Per day" => __("/ day", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showIcon" => true
						],
						"xs"     => [
							"showIcon" => false
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.insurance.title"             => [
					"text"     => [
						"Insurance" => __("Insurance", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.insurance.list"              => [
					"text"     => [
						"Damage excess:"    => __("Damage excess:", RENTSYST_PLUGIN_NAME),
						"Security deposit:" => __("Security deposit:", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showIcon" => true,
							"info"     => [
								"damageExcess"    => [
									"text" => __("Damage excess:", RENTSYST_PLUGIN_NAME),
									"show" => true
								],
								"securityDeposit" => [
									"text" => __("Security deposit:", RENTSYST_PLUGIN_NAME),
									"show" => true
								]
							]
						],
						"xs"     => [
							"showIcon" => false
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.title"               => [
					"text"     => [
						"Summary" => __("Order Summary", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.vehicle-title"       => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.vehicle-desc"        => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.change-button"       => [
					"text"     => [
						"Change" => __("Change", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.pick-up-text"        => [
					"text"     => [
						"Pick Up:" => __("Pick Up:", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.pick-up-location"    => [
					"text"     => [
						"Location:" => __("Location:", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.return-text"         => [
					"text"     => [
						"Return:" => __("Return:", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.return-location"     => [
					"text"     => [
						"Location:" => __("Location:", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.rental-period"       => [
					"text"     => [
						"Rental period" => __("Rental period for {count} days", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.extras-title"        => [
					"text"     => [
						"Extras" => __("Extras", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.delivery-title"      => [
					"text"     => [
						"Delivery" => __("Delivery", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.taxes-title"         => [
					"text"     => [
						"Taxes" => __("Taxes", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.taxes-total"         => [
					"text"     => [
						"Total" => __("Total", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.coupon-title"        => [
					"text"     => [
						"Coupon" => __("Coupon", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.discount-title"      => [
					"text"     => [
						"Discount" => __("Discount", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.summary.total"               => [
					"text"     => [
						"Total" => __("Total", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step3.button.next"                 => [
					"text"     => [
						"Continue" => __("Continue", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4"                             => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"showSteps"       => true,
							"showDescription" => true,
							"isModalSummary"  => false,
							"width"   => 65,
						],
						"xs"     => [
							"isModalSummary" => true,
							"width"  => 100
						],
						"sm"     => [
							"isModalSummary" => true,
							"width"  => 100
						],
						"lg"     => [
						]
					]
				],
				"step4.content"                     => [
					"text"     => [
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
					]
				],
				"step4.customer.title"              => [
					"text"     => [
						"Customer" => __("Customer", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.driver.title-head"           => [
					"text"     => [
						"Head driver"       => __("Head driver", RENTSYST_PLUGIN_NAME),
						"Additional driver" => __("Additional driver", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
						"common" => [
						],
						"xs"     => [
							"textAlign" => "center"
						],
						"sm"     => [
							"textAlign" => "center"
						],
						"lg"     => [
						]
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.form.body"                   => [
					"text"     => [
						"First name"                        => __("First name", RENTSYST_PLUGIN_NAME),
						"Second name"                       => __("Second name", RENTSYST_PLUGIN_NAME),
						"Email"                             => __("Email", RENTSYST_PLUGIN_NAME),
						"Phone number"                      => __("Phone number", RENTSYST_PLUGIN_NAME),
						"City"                              => __("City", RENTSYST_PLUGIN_NAME),
						"Country"                           => __("Country", RENTSYST_PLUGIN_NAME),
						"Address"                           => __("Address", RENTSYST_PLUGIN_NAME),
						"Date of birthday"                  => __("Date of birthday", RENTSYST_PLUGIN_NAME),
						"Document number"                   => __("Document number", RENTSYST_PLUGIN_NAME),
						"Issue date"                        => __("Issue date", RENTSYST_PLUGIN_NAME),
						"Exp date"                          => __("Exp date", RENTSYST_PLUGIN_NAME),
						"Code"                              => __("Code", RENTSYST_PLUGIN_NAME),
						"Error message for required field"  => __("{field} is required!", RENTSYST_PLUGIN_NAME),
						"Error message for not valid field" => __("{field} is not a valid!", RENTSYST_PLUGIN_NAME),
						"Search"                            => __("Next", RENTSYST_PLUGIN_NAME),
						"Send"                              => __("Send", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"states" => [
						[
							"key" => "initial",
							"title" => __("Initial", RENTSYST_PLUGIN_NAME)
						],
						[
							"key" => "user_found",
							"title" => __("User found", RENTSYST_PLUGIN_NAME)
						],
						[
							"key" => "code_confirmed",
							"title" => __("Code Confirmed", RENTSYST_PLUGIN_NAME)
						],
						[
							"key" => "new_user",
							"title" => __("User was not found", RENTSYST_PLUGIN_NAME)
						]
					],
					"settings" => [
						"common" => [
							"direction" => "row",
							"isDriverLicenseEnabled" => !0,
							'fields' => [
								[
									"textKey" => "Email",
									"apiRequired" => 1,
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "First name",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Second name",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Phone number",
									"apiRequired" => 1,
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Country",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "City",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Address",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Date of birthday",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Document number",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Issue date",
									"required" => 1,
									"enabled" => 1,
								],
								[
									"textKey" => "Exp date",
									"required" => 1,
									"enabled" => 1,
								]
							]
						],
						"xs"     => [
							"direction" => "column"
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.form.label-1"                => [
					"text"     => [
						"If your E-mail is already in the database, this will speed up the registration process" => __("Please add your email to speed up the booking process", RENTSYST_PLUGIN_NAME),
						"Code has been sent to E-mail:"                                                          => __("Code has been sent to E-mail:", RENTSYST_PLUGIN_NAME),
						"Code not received?"                                                                     => __("Code not received?", RENTSYST_PLUGIN_NAME),
						"Try again"                                                                              => __("Try again", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.form.title-license"          => [
					"text"     => [
						"Driver license" => __("Driver license", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.form.title-coupon"           => [
					"text"     => [
						"Coupon" => __("Coupon", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.button.upload"               => [
					"text"     => [
						"Upload" => __("Upload", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.form.coupon"                 => [
					"text"     => [
						"Coupon" => __("Coupon name", RENTSYST_PLUGIN_NAME),
						"Apply"  => __("Get discount", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.form.button"                 => [
					"text"     => [
						"Add new driver" => __("Add new driver", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.button.next"                 => [
					"text"     => [
						"Confirm" => __("Confirm", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.customer.card"               => [
					"text"     => [
						"Head driver"                    => __("Head driver", RENTSYST_PLUGIN_NAME),
						"Additional driver"              => __("Additional driver", RENTSYST_PLUGIN_NAME),
						"Are you sure to remove driver?" => __("Are you sure to remove driver?", RENTSYST_PLUGIN_NAME),
						"Remove"                         => __("Remove", RENTSYST_PLUGIN_NAME),
						"Cancel"                         => __("Cancel", RENTSYST_PLUGIN_NAME),
						"Customer"                       => __("Customer", RENTSYST_PLUGIN_NAME),
						"E-mail"                         => __("E-mail", RENTSYST_PLUGIN_NAME),
						"Phone"                          => __("Phone", RENTSYST_PLUGIN_NAME),
						"Address"                        => __("Address", RENTSYST_PLUGIN_NAME),
						"License"                        => __("License", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
							"direction" => "row"
						],
						"xs"     => [
							"direction" => "column"
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.text.additional-information" => [
					"text"     => [
						"Additional information" => __("Additional information and Remarks", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.payment.title"               => [
					"text"     => [
						"Payment" => __("Payment", RENTSYST_PLUGIN_NAME)
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"step4.checkbox.privacy"            => [
					"text"     => [
						"I accept the" => __("Yes, I accept the", RENTSYST_PLUGIN_NAME),
						"Privacy Policy" => __("Terms & Conditions", RENTSYST_PLUGIN_NAME),
					],
					"theme"    => [
					],
					"style"    => [
					],
					"layout"   => [
					],
					"settings" => [
						"common" => [
						],
						"xs"     => [
						],
						"sm"     => [
						],
						"lg"     => [
						]
					]
				],
				"confirmation" => [
					"text"       => [],
					"theme"      => [],
					"style"      => [],
					"layout"     => [],
					"states"     => [
						[
							"key"   => "payment_success",
							"title" => __("Payment Success", RENTSYST_PLUGIN_NAME)
						],
						[
							"key"   => "payment_error",
							"title" => __("Payment Error", RENTSYST_PLUGIN_NAME)
						],
						[
							"key"   => "order_confirmed",
							"title" => __("Order Confirmed", RENTSYST_PLUGIN_NAME)
						],
						[
							"key"   => "order_accepted",
							"title" => __("Order Accepted", RENTSYST_PLUGIN_NAME)
						],
					]
				],
				"confirmation.icon" => [
					"text" => [],
					"theme" => [],
					"style" => [],
					"layout" => []
				],
				"confirmation.reservation" => [
					"text" => [
						"Reservation" => __("Reservation: {id}", RENTSYST_PLUGIN_NAME)
					],
					"theme" => [],
					"style" => [],
					"layout" => []
				],
				"confirmation.title" => [
					"text" => [
						"Your order has been confirmed" => __("Your order has been confirmed", RENTSYST_PLUGIN_NAME),
						"Your order has been accepted" => __("Your order has been accepted", RENTSYST_PLUGIN_NAME),
						"Payment error" => __("Payment error", RENTSYST_PLUGIN_NAME),
						"Your order has been paid successfully" => __("Your order has been paid successfully", RENTSYST_PLUGIN_NAME)
					],
					"theme" => [],
					"style" => [],
					"layout" => [],
				],
				"confirmation.description" => [
					"text" => [
						"Thank you!" => __("Thank you!", RENTSYST_PLUGIN_NAME),
						"Reason" => __("Reason: {reason}", RENTSYST_PLUGIN_NAME),
						"Please try again" => __("Please try again -", RENTSYST_PLUGIN_NAME),
						"Pay" => __("Pay", RENTSYST_PLUGIN_NAME)
					],
					"theme" => [],
					"style" => [],
					"layout" => [],
				],
				"confirmation.button" => [
					"text" => [
						"Manage my booking" => __("Manage my booking", RENTSYST_PLUGIN_NAME)
					],
					"theme" => [],
					"style" => [],
					"layout" => []
				],

			]
		];
	}

	public function setAdminUrls()
	{

		$url = get_rest_url( 0, '/rentsyst/v1/settings/save' );

		$adminUrl['urls'] = [
			'setApp' => [
				'link' => $url,
				'params' => [
					'version' => 'v2'
				]
			],
		];



		$this->settings = array_merge_recursive( $this->settings, $adminUrl );
	}


	public function setPublicUrls()
	{

		$publicUrl['urls']        = [
			'bookOrder'     => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['bookOrder']
			],
			'createOrder'   => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['createOrder']
			],
			'updateOrder'   => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['updateOrder']
			],
			'updateCoupon'  => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['updateCoupon']
			],
			'orderConfirm'  => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['orderConfirm']
			],
			'orderCancel'   => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['orderCancel']
			],
			'disableDates'  => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['disableDates']
			],
			'updateTime'    => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['updateTime']
			],
			'uploadImage'    => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['uploadImage']
			],
			'getWorkTimes'  => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['getWorkTimes'],
				'params' => [],
			],
			'updatePayment' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['paymentDiscount']
			],
			'getPass'       => [
				'link'   => $this->api->baseUrl . $this->api->getConfig()['getPass'],
				'params' => [],
			],
			'getDriver'     => [
				'link'   => $this->api->baseUrl . $this->api->getConfig()['getDriver'],
				'params' => []
			],
			'getOrder' => [
				'link' => $this->api->baseUrl . $this->api->getConfig()['getOrder'],
				'params' => []
			],
			'updateToken'   => [
				'link' => get_rest_url( 0, '/rentsyst/v1/access-token' ),
			],
		];
		$publicUrl['return_urls'] = [
			'return_url' => get_page_link( get_option( 'rentsyst_confirmation_page_id' ) ),
		];
		$this->settings           = array_merge_recursive( $this->settings, $publicUrl );
	}

	public function setToken()
	{
		$token['accessToken'] = $this->api->getToken();
		$this->settings       = array_merge( $this->settings, $token );

		return (boolean) $token['accessToken'];
	}
}
