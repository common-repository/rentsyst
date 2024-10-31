<?php

namespace rentsyst\includes;

class RS_Company
{
	const COMPANY_SETTINGS_ID = 'rentsyst_company_settings';

	private $settings;

	private static $instance;

	private function __construct()
	{
		$this->settings = get_option(self::COMPANY_SETTINGS_ID);
	}

	public static function getInstance()
	{
		if(!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getCurrency()
	{
		return $this->settings->currency ?? '';
	}

	public function getLocations()
	{
		return $this->settings->locations ?? [];
	}

	public function getDriverAgeRange()
	{
		return $this->settings->range_driver_age ?? null;
	}

	public function getRentalPeriodRange()
	{
		return $this->settings->range_rental_period_seconds ?? null;
	}

	public function getCurrencyPosition()
	{
		return $this->settings->currency_position ?? null;
	}

	public function getTimeForBooking()
	{
		return $this->settings->time_for_booking ?? null;
	}

	public function getHoursBeforeRental()
	{
		return $this->settings->hours_before_rental ?? null;
	}

	public function getDateFormat()
	{
		return $this->settings->date_format ?? null;
	}

	/**
	 * @return null
	 * @deprecated Necessary for v1
	 */
	public function getCanDiffReturn()
	{
		return $this->settings->canDiffReturn ?? null;
	}

	public function isDifferentLocationEnabled()
	{
		return $this->settings->isDifferentLocationEnabled ?? true;
	}

	/**
	 * @return bool | null
	 * @deprecated Necessary only for v1
	 */
	public function getBaseLocations()
	{
		return $this->settings->baseLocations ?? null;
	}

	public function isCustomLocationEnabled()
	{
		return $this->settings->isCustomLocationEnabled ?? true;
	}

	public function getPayments()
	{
		return $this->settings->payment_methods ?? [];
	}

	public function getFilters()
	{
		return isset($this->settings->vehicle_filters) ? json_decode(json_encode($this->settings->vehicle_filters), true) : [];
	}

	public function getMetricSystem()
	{
		return $this->settings->metric_system;
	}

	public function getWithCoupon()
	{
		return isset($this->settings->withCoupon) ? $this->settings->withCoupon : true;
	}

	public function isCouponEnabled()
	{
		return (bool) isset($this->settings->isCouponEnabled) ? $this->settings->isCouponEnabled : true;
	}
}
