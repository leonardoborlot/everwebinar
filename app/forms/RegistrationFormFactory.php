<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\Webinar;
use Nette\Application\UI\Form;

class RegistrationFormFactory
{
	public function __construct(Webinar $webinar)
	{
		$this->webinar = $webinar;
	}


	public function create(): Form
	{
		$form = new Form;

		$webinarsIdsNames = $this->webinar->getWebinarsIdsNames();

		$form->addSelect('webinar', 'Webinar:')->setItems($webinarsIdsNames);

		$form->addText('firstname', 'First Name:')->setRequired()->setMaxLength(100);

		$form->addText('lastname', 'Last Name:')->setMaxLength(100);

		$form->addText('email', 'Email:')->addRule(Form::EMAIL)->setMaxLength(100);

		$form->addText('phone', 'Phone:')->addRule(Form::PATTERN, 'Phone must contain only numbers', '[0-9]+')->setMaxLength(20);

		$form->addTextArea('promo', 'Promo code')->setMaxLength(100);

		$form->addSubmit('register', 'Register!');

		return $form;
	}
}