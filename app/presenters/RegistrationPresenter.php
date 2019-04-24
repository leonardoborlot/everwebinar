<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Webinar;
use Nette;
use App\Forms\RegistrationFormFactory;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

final class RegistrationPresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @var Webinar
	 */
	protected $webinar;

	public function __construct(Webinar $webinar, )
	{
		Presenter::__construct();
		$this->webinar = $webinar;
	}

	public function renderSuccess() :void
	{

	}

	public function renderError() :void
	{

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
		$values = $form->getValues();


	}
}