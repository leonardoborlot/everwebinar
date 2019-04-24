<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\Webinar;
use Nette\Application\UI\Form;

class RegistrationFormFactory
{
	/**
	 * @var Webinar
	 */
	protected $webinar;

	public function __construct(Webinar $webinar)
	{
		$this->webinar = $webinar;
	}


	public function create(): Form
	{
		$form = new Form;

		$form->addSelect('webinar', 'Webinar:')
			 ->setItems($this->webinar->getWebinars());

		$form->addText('firstname', 'First Name:')
			 ->setRequired()->setMaxLength(100);

		$form->addText('lastname', 'Last Name:')
			 ->setRequired()->setMaxLength(100);

		$form->addTextArea('promo', 'Promo code')->setMaxLength(100);

		$form->addSubmit('register', 'Register!');

		return $form;
	}
}