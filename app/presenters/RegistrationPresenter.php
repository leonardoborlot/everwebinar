<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Webinar\WebinarManager;
use Nette;
use App\Forms\RegistrationFormFactory;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use App\Exception\RegistrationException;

final class RegistrationPresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @var WebinarManager
	 */
	protected $webinarManager;


	public function __construct(WebinarManager $webinarManager)
	{
		Presenter::__construct();
		$this->webinarManager = $webinarManager;
	}

	public function renderSuccess(array $values) :void
	{
		$this->template->firstname = $values['firstname'];
		$this->template->lastname = $values['lastname'];

		$webinarsIdsNames = $this->webinarManager->getWebinarsIdNamePair();
		$this->template->webinar = $webinarsIdsNames[$values['webinar']];
	}

	public function renderError(string $message) :void
	{
		$this->template->message = $message;
	}

	protected function createComponentRegistrationForm(): Form
	{
		$formFactory = new RegistrationFormFactory($this->webinarManager);

		$form = $formFactory->create();

		$form->onSuccess[] = [$this, 'registrationFormSubmitted'];

		return $form;
	}

	public function registrationFormSubmitted(Form $form)
	{
		$values = (array)$form->getValues();

		try {
			$this->webinarManager->register($values);
			$this->forward(':success', ['values' => $values]);
		} catch (RegistrationException $e)
		{
			$this->forward(':error', ['error' => $e->getMessage()]);
		}
	}
}