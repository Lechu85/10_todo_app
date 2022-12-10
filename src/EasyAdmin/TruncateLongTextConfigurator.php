<?php

namespace App\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

use function Symfony\Component\String\u;

class TruncateLongTextConfigurator implements FieldConfiguratorInterface
{
	private const MAX_LENGTH = 25;

	public function supports(FieldDto $field, EntityDto $entityDto): bool
	{
		return $field->getFieldFqcn() === TextareaField::class;
	}

	public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
	{

		//dd($field);// fieldDto zwraca wszystkie opcje i parametry do modyfikacji.

		$crud = $context->getCrud();
		if ($crud?->getCurrentPage() === Crud::PAGE_DETAIL) {//sometimes crud return null so ? should be
			return; //do nothing
		}

		if (strlen($field->getFormattedValue()) < self::MAX_LENGTH) {
			return; //do nothing
		}

		//$truncatedValue = $this->u($field->getFormattedValue())
		//	->truncate(self::MAX_LENGTH, '...', false);

		$truncatedValue = substr($field->getFormattedValue(), 0, 25).'...';

		$field->setFormattedValue($truncatedValue);

	}


}