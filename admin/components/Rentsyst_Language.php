<?php

namespace rentsyst\admin\components;

class Rentsyst_Language
{
	public static function getLanguageCode()
	{
		$language = 'en';
		if(function_exists('pll_current_language') && pll_current_language()) {
			$language = pll_current_language();
		} elseif (apply_filters( 'wpml_current_language', NULL )) {
			$language = apply_filters( 'wpml_current_language', NULL );
		} elseif (isset($_REQUEST['lang'])) {
			$language = sanitize_text_field($_REQUEST['lang']);
		} elseif (function_exists('weglot_get_current_language') && weglot_get_current_language()) {
			$language = weglot_get_current_language();
		}
		return $language;
	}

	public static function getConnectedPagesId( $catalog_page_id )
	{
		if(function_exists('pll_get_post_translations') && pll_get_post_translations($catalog_page_id)) {
			return pll_get_post_translations($catalog_page_id);
		}
		if(function_exists('icl_object_id')) {
			$translated_page_id = icl_object_id($catalog_page_id, 'page', false, ICL_LANGUAGE_CODE);
			if($translated_page_id) {
				return [self::getLanguageCode() => $translated_page_id];
			}
		}

		return [$catalog_page_id];
	}
}
