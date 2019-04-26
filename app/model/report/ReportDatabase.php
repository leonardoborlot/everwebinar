<?php

declare(strict_types=1);

namespace App\Model\Report;

use App\Model\Webinar\WebinarDatabase;
use Nette\Database\Connection;

class ReportDatabase
{
	protected $database;

	protected $webinarDatabase;

	public function __construct(Connection $database, WebinarDatabase $webinarDatabase)
	{
		$this->database = $database;
		$this->webinarDatabase = $webinarDatabase;
	}

	public function getWebinarsUsersCount()
	{
		return $this->database->query('
			SELECT W.Id AS WebinarId, W.Name AS WebinarName, COUNT(r.Id) AS UsersCount
			FROM Webinar AS W
			INNER JOIN Registration AS R ON R.WebinarId = W.Id
			GROUP BY W.Id
		')->fetchAll();
	}

	public function getWebinarUsers($webinarId)
	{
		return $this->database->query('
			SELECT U.*
			FROM User AS U
			INNER JOIN Registration AS R ON R.UserId = U.Id
			WHERE R.WebinarId = ?
		', $webinarId)->fetchAll();
	}

	public function getWebinarsUsers()
	{
		$webinars = $this->webinarDatabase->loadWebinars();

		$result = [];
		foreach ($webinars as $webinar)
		{
			$webinarId = $webinar->Id;
			$webinarName = $webinar->Name;
			$users = $this->getWebinarUsers($webinarId);
			$result[] = [
				'webinarId' => $webinarId,
				'webinarName' => $webinarName,
				'users' => $users
			];
		}
		return $result;
	}
}