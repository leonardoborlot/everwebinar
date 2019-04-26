<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Report\ReportDatabase;
use Nette;
use Nette\Application\UI\Presenter;

final class ReportPresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @var ReportDatabase
	 */
	protected $reportDatabase;


	public function __construct(ReportDatabase $reportDatabase)
	{
		Presenter::__construct();
		$this->reportDatabase = $reportDatabase;
	}

	public function renderDefault()
	{
		$this->template->webinarsUsersCount = $this->reportDatabase->getWebinarsUsersCount();

		$this->template->webinarsUsers = $this->reportDatabase->getWebinarsUsers();
	}
}