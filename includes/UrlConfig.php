<?php
namespace rentsyst\includes;

class UrlConfig
{
	public static function getApiUrl(): string
	{
		return RENTSYST_MOD === 'dev' ? 'https://api-dev.rentsyst.com/v1' : 'https://api.rentsyst.com/v1';
	}

	public static function getApiUrlV2(): string
	{
		return RENTSYST_MOD === 'dev' ? 'https://api-dev.rentsyst.com/v2' : 'https://api.rentsyst.com/v2';
	}

	public static function getCrmUrl(): string
	{
		return RENTSYST_MOD === 'dev' ? 'https://dev.rentsyst.com' : 'https://rentsyst.com';
	}
}
