<?php

namespace rentsyst\includes;

use WP_Post;

class RS_Vehicle
{
	/** @var WP_Post */
	private $vehicle;

	public function __construct($post)
	{
		$this->vehicle = $post;
	}

	public function getMark()
	{
		return get_post_meta($this->vehicle->ID, 'mark', true);
	}

	public function getDescription()
	{
		return $this->vehicle->post_content;
	}

	public function getComment()
	{
		ob_start();
		comments_template();
		return ob_get_clean();
	}

	public function getBrand()
	{
		return get_post_meta($this->vehicle->ID, 'brand', true);
	}

	public function getId()
	{
		add_action('get_footer', function () {
			require_once WP_RENTSYST_PLUGIN_DIR . '/site/partials/booking-panel.php';
		});
		return get_post_meta($this->vehicle->ID, 'id', true);
	}

	public function getIdWithoutPanel()
	{
		return get_post_meta($this->vehicle->ID, 'id', true);
	}

	public function getLink()
	{
		return get_permalink($this->vehicle->ID);
	}

	public function getBookingButton($title = 'Rental Request', $class = 'btn btn--red form__submit')
	{
		$id = $this->getId();
		return " <button type=\"submit\" class=\"$class rentsyst_reserve\" data-id=\"$id\">$title</button>";
	}

	public function getYear()
	{
		return get_post_meta($this->vehicle->ID, 'year', true);
	}

	public function getNumberSeats()
	{
		return get_post_meta($this->vehicle->ID, 'number_seats', true);
	}

	public function getNumberDoors()
	{
		return get_post_meta($this->vehicle->ID, 'number_doors', true);
	}

	public function getLargeBags()
	{
		return get_post_meta($this->vehicle->ID, 'large_bags', true);
	}

	public function getSmallBags()
	{
		return get_post_meta($this->vehicle->ID, 'small_bags', true);
	}

	public function getGroup()
	{
		return get_post_meta($this->vehicle->ID, 'group', true);
	}

	public function getColor()
	{
		return get_post_meta($this->vehicle->ID, 'color', true);
	}

	public function getColorBadge()
	{
		return '<span style="background-color: #' . $this->getColor() . '" class="rentsyst-color-badge"></span>';
	}

	public function getBodyType()
	{
		return get_post_meta($this->vehicle->ID, 'body_type', true);
	}

	public function getPrice()
	{
		return get_post_meta($this->vehicle->ID, 'price', true);
	}

	public function getMinPrice()
	{
		return get_post_meta($this->vehicle->ID, 'min_price', true);
	}

	public function getDiscountPrice()
	{
		return get_post_meta($this->vehicle->ID, 'discount_price', true);
	}

	public function getPeriodsPrice()
	{
		return get_post_meta($this->vehicle->ID, 'periods_price', true);
	}

	public function getFuel()
	{
		return get_post_meta($this->vehicle->ID, 'fuel', true);
	}


	public function getVolumeTank()
	{
		return get_post_meta($this->vehicle->ID, 'volume_tank', true);
	}


	public function getVolumeEngine()
	{
		return (int) get_post_meta($this->vehicle->ID, 'volume_engine', true) ?? 0;
	}

	public function getInsuranceDeposit()
	{
		return get_post_meta($this->vehicle->ID, 'insurance_deposit', true) ?? 0;
	}

	public function getTransmission()
	{
		return get_post_meta($this->vehicle->ID, 'transmission', true);
	}

	public function getLocations()
	{
		return get_post_meta($this->vehicle->ID, 'locations', true);
	}

	public function getOptions()
	{
		return get_post_meta($this->vehicle->ID, 'options');
	}

	public function getThumbnailUrl()
	{
		return get_post_meta($this->vehicle->ID, 'thumbnail', true);
	}

	public function getPhotoUrls()
	{
		return get_post_meta($this->vehicle->ID, 'photos', true);
	}

	public function getOdometer()
	{
		return get_post_meta($this->vehicle->ID, 'odometer', true) ?: '(not set)';
	}
}
