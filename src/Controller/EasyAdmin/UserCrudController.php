<?php

namespace App\Controller\EasyAdmin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
				->onlyOnIndex();
		yield AvatarField::new('avatar')
			->formatValue(static function($value, ?User $user) {
				return $user?->getAvatarUrl();
			})
			->hideOnForm();
		yield ImageField::new('avatar')
			->setBasePath('/uploads/avatars')
			->setUploadDir('public/uploads/avatars')
			->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
			->onlyOnForms();
		yield EmailField::new('email');
	    yield TextField::new('fullName')//nie ma pola fullName, ale jest wystarczająco bystry, żeby odnaleźć getera getFullName() w encji :)
	            ->hideOnForm();
	    yield TextField::new('name')//Przerobić na firstname i last name
	        ->onlyOnForms();
	    yield BooleanField::new('enabled');
	    yield DateField::new('createdAt');

	    $roles = ['ROLE_SUPERADMIN', 'ROLE_ADMIN', 'ROLE_MODERATOR', 'ROLE_USER'];
	    yield ChoiceField::new('roles')
	        ->setChoices(array_combine($roles, $roles))
	        ->allowMultipleChoices()
	        ->renderExpanded()
	        ->renderAsBadges();



    }

	//jak tutaj dorobić configureActions?

}
