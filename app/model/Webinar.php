<?php

declare(strict_types=1);

namespace App\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Webinar
{
	protected const API_URL = 'https://webinarjam.genndi.com/api/everwebinar';

	/**
	 * @var Client
	 */
	protected $guzzleClient;

	/**
	 * @var array $additionalRequestParams
	 */
	protected $additionalRequestParams = [];

	public function __construct()
	{
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
		return ['form_params' =>
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
		if ($response && is_object($response))
		{
			return json_decode($response->getBody()->getContents(), TRUE);
		}
		return NULL;
	}

	public function getWebinarInfo(string $id) :array
	{
		$this->setAdditionalRequestParams(['webinar_id' => $id]);
		$response = $this->sendRequest('/webinar', 'POST');
		return $this->getResponseAsArray($response);
	}

	public function getWebinars() :?array
	{
		$response = $this->sendRequest('/webinars', 'POST');
		return $this->getResponseAsArray($response);
	}

	public function getWebinarsIdsNames() :array
	{
		$webinars = $this->getWebinars();

		$ids = array_column($webinars['webinars'], 'webinar_id');

		$names = array_column($webinars['webinars'], 'name');

		return array_combine($ids, $names);
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