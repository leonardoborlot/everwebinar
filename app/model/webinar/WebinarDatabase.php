<?php

declare(strict_types=1);

namespace App\Model\Webinar;

use Nette\Database\Connection;

class WebinarDatabase
{
	protected $database;

	public function __construct(Connection $database)
	{
		$this->database = $database;
	}

	public function loadWebinars()
	{
		return $this->database->fetchAll('SELECT * FROM Webinar');
	}

	public function loadWebinar($id)
	{
		return $this->database->fetch('SELECT * FROM Webinar WHERE ApiId = ?', $id);
	}

	public function saveWebinar($webinar)
	{
		$this->database->query('INSERT INTO Webinar', $webinar);
		return $this->database->getInsertId();
	}

	public function saveWebinarFromApi($webinar)
	{
		$newWebinar['ApiId'] = $webinar[Config::WEBINAR_ATTR_WEBINAR_ID];
		$newWebinar['Name'] = $webinar[Config::WEBINAR_ATTR_NAME];
		$newWebinar['Description'] = $webinar[Config::WEBINAR_ATTR_DESCRIPTION];
		return $this->saveWebinar($newWebinar);
	}

	public function loadUser($email)
	{
		return $this->database->fetch('SELECT * FROM User WHERE Email = ?', $email);
	}

	public function saveUser($user)
	{
		$this->database->query('INSERT INTO User', $user);
		return $this->database->getInsertId();
	}

	public function loadRegistration($userId, $webinarId)
	{
		return $this->database->fetch('SELECT * FROM Registration WHERE UserId = ? AND WebinarId', $userId, $webinarId);
	}

	public function saveRegistration($userId, $webinarId, $promo = '')
	{
		$this->database->query('INSERT INTO Registration', ['UserId' => $userId, 'WebinarId' => $webinarId, 'promo' => $promo]);
		return $this->database->getInsertId();
	}
}