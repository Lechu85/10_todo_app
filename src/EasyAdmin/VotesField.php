<?php

namespace App\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class VotesField implements FieldInterface
{
	use FieldTrait;

	public static function new(string $propertyName, ?string $label = null)
	{
		return (new self())
			->setProperty($propertyName)
			->setLabel($label)
			//info this template is used on index and detail page
			->setTemplatePath('easy_admin/field/votes.html.twig')
			//info this is used on edit and new page
			->setFormType(IntegerType::class)
			->addCssClass('field-integer')
			->setDefaultColumns('col-md-4 col-xxl-3');//nie powinniśmy tej metody użyć na zewnatrz, ale w klasie pola Field możemy. a my jestesmy w tym polu wiec działąmy :)
	}


}