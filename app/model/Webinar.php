<?php

declare(strict_types=1);

namespace App\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Webinar
{
	protected const API_URL = 'https://webinarjam.genndi.com/api/everwebinar';
	protected const API_KEY = '';

	/**
	 * @var Client
	 */
	protected $guzzleClient;

	/**
	 * @var array $additionalHeaders
	 */
	protected $additionalHeaders = [];

	public function __construct()
	{
		$this->guzzleClient = new Client(['base_uri' => self::API_URL]);
	}

	protected function setAdditionalHeaders(array $headers) :void
	{
		$this->additionalHeaders = $headers;
	}

	protected function getAdditionalHeaders() :array
	{
		return $this->additionalHeaders;
	}

	protected function getHeaders() :array
	{
		return ['headers' =>
			array_merge(['api_key' => self::API_KEY], $this->getAdditionalHeaders())
		];
	}

	protected function sendRequest(string $resource, string $method = 'GET') :ResponseInterface
	{
		return $this->guzzleClient->request(
			$method,
			self::API_URL . $resource,
			$this->getHeaders()
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

	protected function getResponseTextAsArray(string $response) : ?array
	{
		if ($response)
		{
			return json_decode($response, TRUE);
		}
		return NULL;
	}

	public function getWebinarInfo(string $id) :array
	{
		$response = '{
			 "status": "success",
			 "message": "",
			 "webinar": {
			 "webinar_id": "demo123",
			 "name": "name of webinar",
			 "description": "description of webinar",
			 "schedules": [
			 {
			 "date": "Every Day, 06:30 PM",
			 "schedule": 0
			 },
			 {
			 "date": "Monday, 6 Jul 08:30 AM",
			 "schedule": 1
			 },
			 {
			 "date": "Every Tuesday, 6 Jul 03:00 PM",
			 "schedule": 2
			 }
			 ],
			 "timezone": "America/Los_Angeles",
			 "presenters": [
			 {
			 "name": "John Doe",
			 "email": "john.doe@gmail.com",
			 "picture": "https://test.s3.amazonaws.com/default_user.jpg"
			 }
			 ],
			 "registration_url": "http://app.webinarjam.net/register/1/demo123",
			 "registration_type": "free",
			 "registration_fee": 0,
			 "registration_currency": "",
			 "registration_checkout_url": "",
			 "registration_post_payment_url": ""
			 }
			}';
		return $this->getResponseTextAsArray($response);
//		$this->setAdditionalHeaders(['webinar_id' => $id]);
//		$response = $this->sendRequest('/webinars', 'POST');
//		return $this->getResponseAsArray($response);
	}

	public function getWebinars() :array
	{
		$response = '{
			 "status": "success",
			 "webinars": [
			 {
			 "webinar_id": "demo123",
			 "name": "WebinarOne",
			 "description": "description of webinar one",
			 "schedules": [
			 "Every Day 18:30 PM",
			 "Every Wednesday 19:00 PM",
			 "Mon, 6 Jul 20:01 PM"
			 ],
			 "timezone": "America/Chicago",
			 },{
			 "webinar_id": "demo1234",
			 "name": "WebinarTwo",
			 "description": "description of webinar two",
			 "schedules": [
			 "Every Day 18:30 PM",
			 "Every Wednesday 19:00 PM",
			 "Mon, 6 Jul 20:01 PM"
			 ],
			 "timezone": "America/Chicago",
			 },
			 ]
			}';
		return $this->getResponseTextAsArray($response);
		$response = $this->sendRequest('/webinar', 'POST');
		return $this->getResponseAsArray($response);
	}

	public function register()
	{

	}
}