<?php

declare(strict_types=1);

namespace App\Model\Webinar;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Nette\Caching\Cache;
use Psr\Http\Message\ResponseInterface;

class WebinarApi
{
	protected const API_URL = 'https://webinarjam.genndi.com/api/everwebinar';

	/**
	 * @var Cache
	 */
	protected $cache;

	/**
	 * @var Client
	 */
	protected $guzzleClient;

	/**
	 * @var array $additionalRequestParams
	 */
	protected $additionalRequestParams = [];


	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
		$this->guzzleClient = new Client(['base_uri' => self::API_URL]);
	}


	protected function setAdditionalRequestParams(array $requestParams) :void
	{
		$this->additionalRequestParams = $requestParams;
	}


	protected function getAdditionalRequestParams() :array
	{
		return $this->additionalRequestParams;
	}


	protected function getRequestParams() :array
	{
		return [
			'form_params' =>
				array_merge(['api_key' => getenv('API_KEY')], $this->getAdditionalRequestParams())
		];
	}


	protected function sendRequest(string $resource, string $method = 'GET') :ResponseInterface
	{
		return $this->guzzleClient->request(
			$method,
			self::API_URL . $resource,
			$this->getRequestParams()
		);
	}


	protected function getResponseAsArray(Response $response) : ?array
	{
		if ($response && is_object($response)) {
			return json_decode($response->getBody()
										->getContents(), TRUE);
		}
		return NULL;
	}


	protected function getWebinarCacheId($id)
	{
		return Config::WEBINAR . '_' . $id;
	}


	protected function isResponseSuccess(array $response)
	{
		if ($response[Config::API_RESPONSE_STATUS] === Config::API_RESPONSE_STATUS_SECCESS)
		{
			return true;
		}

		return false;
	}

	public function getWebinarInfo(string $id) :?array
	{
		$webinarCacheId = $this->getWebinarCacheId($id);

		if ($webinar = $this->cache->load($webinarCacheId))
		{
			return $webinar;
		}

		$this->setAdditionalRequestParams(['webinar_id' => $id]);
		$response = $this->sendRequest('/webinar', 'POST');
		$webinar = $this->getResponseAsArray($response);

		if ($this->isResponseSuccess($webinar))
		{
			$webinar = $webinar[Config::WEBINAR];
			$this->cache->save($webinarCacheId, $webinar, [
				Cache::EXPIRE => '10 minutes'
			]);
			return $webinar;
		}

		return null;
	}

	public function getWebinars() :?array
	{
		if ($webinars = $this->cache->load(Config::WEBINARS))
		{
			return $webinars;
		}

		$response = $this->sendRequest('/webinars', 'POST');
		$webinars = $this->getResponseAsArray($response);
		if ($this->isResponseSuccess($webinars))
		{
			$webinars = $webinars[Config::WEBINARS];
			$this->cache->save(Config::WEBINARS, $webinars, [
				Cache::EXPIRE => '10 minutes'
			]);
			return $webinars;
		}

		return null;
	}

	public function register($data)
	{
		$params = [
			'webinar_id' => $data['webinar'],
			'first_name' => $data['firstname'],
			'schedule' => 0,
			'email' => $data['firstname']
		];
		foreach (['lastname', 'email', 'phone', 'promo'] as $param) {
			if (isset($data[$param]))
			{
				$params[$param] = $data[$param];
			}
		}



		return [];

//		$this->setAdditionalRequestParams($params);
//		return
//		$response = $this->sendRequest('/register', 'POST');
//		return $this->getResponseAsArray($response);
	}
}