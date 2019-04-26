<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\Webinar\WebinarManager;
use Nette\Application\UI\Form;

class RegistrationFormFactory
{
	protected $webinarManager;

	public function __construct(WebinarManager $webinarManager)
	{
		$this->webinarManager = $webinarManager;
	}


	public function create(): Form
	{
		$form = new Form;

		$webinarsIdNamePair = $this->webinarManager->getWebinarsIdNamePair();

		$form->addSelect(Config::FORM_CONTROL_NAME_WEBINAR, 'Webinar:')->setItems($webinarsIdNamePair);

		$form->addText(Config::FORM_CONTROL_NAME_FIRSTNAME, 'First Name:')->setRequired()->setMaxLength(100);

		$form->addText(Config::FORM_CONTROL_NAME_LASTNAME, 'Last Name:')->setMaxLength(100);

		$form->addText(Config::FORM_CONTROL_NAME_EMAIL, 'Email:')->setRequired()->addRule(Form::EMAIL)->setMaxLength(100);

		$form->addText(Config::FORM_CONTROL_NAME_PHONE, 'Phone:')->addRule(Form::PATTERN, 'Phone must contain only numbers', '[0-9]+')->setMaxLength(20);

		$form->addTextArea(Config::FORM_CONTROL_NAME_PROMO, 'Promo code')->setMaxLength(100);

		$form->addSubmit(Config::FORM_CONTROL_NAME_REGISTER, 'Register!');

		return $form;
	}
}