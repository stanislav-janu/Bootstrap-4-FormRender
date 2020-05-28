<?php

require __DIR__ . '/../vendor/autoload.php';

\Tracy\Debugger::$productionMode = false;
\Tracy\Debugger::$maxDepth = 5;
\Tracy\Debugger::enable();

?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<title>Hello, world!</title>
</head>
<body>

<div class="container">
	<h1>Example 1</h1>
	<?php
	$form1 = new \JCode\BootstrapFormRender\BootstrapForm();
	$form1->setRenderMode(\JCode\BootstrapFormRender\Enums\RenderMode::SIDE_BY_SIDE_MODE);

	$form1->addText('name', 'Jméno hotelu')
		->setRequired('Jméno hotelu je nutné vyplnit.');

	$form1->addText('companyName', 'Jméno společnosti')
		->setRequired('Jméno společnosti je nutné vyplnit.');

	$form1->addText('street', 'Ulice a číslo')
		->setRequired('Ulici a číslo je nutné vyplnit.');

	$form1->addText('city', 'Obec')
		->setRequired('Obec je nutné vyplnit.');

	$form1->addText('zip', 'PSČ')
		->addRule(\Nette\Forms\Form::MAX_LENGTH, 'Maximlální délka PSČ je %d znaků.', 5)
		->setRequired('PSČ je nutné vyplnit.');

	$form1->addText('ico', 'IČ')
		->addRule(\Nette\Forms\Form::MAX_LENGTH, 'Maximlální délka IČ je %d znaků.', 10)
		->addRule(\Nette\Forms\Form::NUMERIC, 'IČ by mělo být číselné.')
		->setRequired('IČ je nutné vyplnit.');

	$form1->addSubmit('submit', 'Přidat');
	echo $form1;
	?>
</div>

<div class="container">
	<h1>Example 2</h1>
	<?php
	$form2 = new \JCode\BootstrapFormRender\BootstrapForm();

	$form2->addGroup('');

	$form2->addText('accountName', 'forms.order.accountName')
		->setRequired('forms.order.accountNameRequired');

	$form2->addText('accountShortName', 'forms.order.accountShortName')
		->addRule(Nette\Application\UI\Form::MAX_LENGTH, 'forms.order.accountShortNameMaxLen', 40)
		->setRequired('forms.order.accountShortNameRequired');

	$form2->addGroup('forms.order.user.groupTitle');

	$form2->addText('userFullName', 'forms.order.user.fullName')
		->setRequired('forms.order.user.fullNameRequired');

	$form2->addText('userEmail', 'forms.order.user.email')
		->setHtmlType('email')
		->addRule(Nette\Application\UI\Form::EMAIL, 'forms.order.user.emailNotValid')
		->setRequired('forms.order.user.emailRequired');

	$form2->addGroup('forms.order.company.groupTitle');

	$form2->addText('companyName', 'forms.order.company.name')
		->setRequired('forms.order.company.nameRequired');

	$form2->addText('companyStreet', 'forms.order.company.street')
		->setRequired('forms.order.company.streetRequired');

	$form2->addText('companyCity', 'forms.order.company.city')
		->setRequired('forms.order.company.cityRequired');

	$form2->addText('companyZip', 'forms.order.company.zip')
		->setRequired('forms.order.company.zipRequired');

	$row = $form2->addRow();

	$row->addCell(6)
		->addText('companyBid', 'forms.order.company.bid')
		->setRequired('forms.order.company.bidRequired');

	$row->addCell(6)
		->addText('companyVatId', 'forms.order.company.vatId');

	$form2->addText('companyEmail', 'forms.order.company.email')
		->setHtmlType('email')
		->addRule(Nette\Application\UI\Form::EMAIL, 'forms.order.company.emailNotValid')
		->setRequired('forms.order.company.emailRequired');

	$form2->addText('companyPhone', 'forms.order.company.phone');

	$form2->addGroup('');

	$form2->addCheckbox('gdprAgreement', 'forms.order.gdprAgreement')
		->setRequired('forms.order.gdprAgreementRequired');

	$form2->addCheckbox('businessAgreement', 'forms.order.businessAgreement')
		->setRequired('forms.order.businessAgreementRequired');

	$form2->addGroup('');

	$form2->addSubmit('submit', 'forms.order.submit');

	echo $form2;
	?>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
