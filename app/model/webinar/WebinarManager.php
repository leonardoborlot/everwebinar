<?php

declare(strict_types=1);

namespace App\Model\Webinar;

use App\Exception\RegistrationException;
use App\Forms\Config as FormsConfig;

class WebinarManager
{
	protected $webinarApi;

	protected $webinarDatabase;

	public function __construct(WebinarApi $webinarApi, WebinarDatabase $webinarDatabase)
	{
		$this->webinarApi = $webinarApi;
		$this->webinarDatabase = $webinarDatabase;
	}

	public function getWebinarsIdNamePair() :?array
	{
		$webinars = $this->webinarApi->getWebinars();

		if ($webinars)
		{
			$ids = array_column($webinars, Config::WEBINAR_ATTR_WEBINAR_ID);

			$names = array_column($webinars, Config::WEBINAR_ATTR_NAME);

			return array_combine($ids, $names);
		}

		return null;
	}

	protected function existRegistration(string $userId, string $webinarId) :bool
	{
		if ($this->webinarDatabase->loadRegistration($userId, $webinarId))
		{
			return true;
		}

		return false;
	}

	public function register(array $data) :bool
	{
		$webinarId = $data[FormsConfig::FORM_CONTROL_NAME_WEBINAR];
		$userEmail = $data[FormsConfig::FORM_CONTROL_NAME_EMAIL];

		$webinar = $this->webinarDatabase->loadWebinar($webinarId);
		$user = $this->webinarDatabase->loadUser($userEmail);

		if ($webinar && $user)
		{
			if ($this->existRegistration((string)$user->Id, (string)$webinar->Id))
			{
				throw new RegistrationException('User with email \''.$userEmail.'\' is already registered.');
			}
		}

		if ($webinar) {
			$registrationWebinarId = $webinar->Id;
		} else
		{
			$webinar = $this->webinarApi->getWebinarInfo($webinarId);
			if ($webinar)
			{
				$registrationWebinarId = $this->webinarDatabase->saveWebinarFromApi($webinar);
			} else {
				throw new RegistrationException('Unknown webinar.');
			}
		}

		if ($user)
		{
			$registrationUserId = $user->Id;
		} else
		{
			$newUser = [
				'Firstname' => $data[FormsConfig::FORM_CONTROL_NAME_FIRSTNAME],
				'Lastname' => $data[FormsConfig::FORM_CONTROL_NAME_LASTNAME],
				'Email' => $userEmail,
				'Phone' => $data[FormsConfig::FORM_CONTROL_NAME_PHONE]
			];
			$registrationUserId = $this->webinarDatabase->saveUser($newUser);
		}

		$this->webinarDatabase->saveRegistration($registrationUserId, $registrationWebinarId, $data[Config::WEBINAR_ATTR_PROMO]);

		return false;
	}

}