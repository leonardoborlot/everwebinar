<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Webinar;
use Nette;
use App\Forms\RegistrationFormFactory;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use RegisterException;

final class RegistrationPresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @var Webinar
	 */
	protected $webinar;


	public function __construct(Webinar $webinar)
	{
		Presenter::__construct();
		$this->webinar = $webinar;
	}

	public function renderSuccess(array $values) :void
	{
		$this->template->firstname = $values['firstname'];
		$this->template->lastname = $values['lastname'];

		$webinarsIdsNames = $this->webinar->getWebinarsIdsNames();
		$this->template->webinar = $webinarsIdsNames[$values['webinar']];
	}

	protected function createComponentRegistrationForm(): Form
	{
		$formFactory = new RegistrationFormFactory($this->webinar);

		$form = $formFactory->create();

		$form->onSuccess[] = [$this, 'registrationFormSubmitted'];

		return $form;
	}

	public function registrationFormSubmitted(Form $form)
	{
		$values = (array)$form->getValues();

		try {
			$this->webinar->register($values);
			$this->forward(':success', ['values' => $values]);
		} catch (RegisterException $e)
		{
			$this->forward(':error');
		}
	}
}