<?php

namespace App\Controller\EasyAdmin;

use App\EasyAdmin\VotesField;
use App\Entity\Answer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use phpDocumentor\Reflection\Types\Integer;

class AnswerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Answer::class;
    }

    public function configureFields(string $pageName): iterable
    {
		yield IdField::new('id')
			->onlyOnIndex();
		yield Field::new('answer');
		yield VotesField::new('votes');
		yield AssociationField::new('question')
			->hideOnIndex()
			->autocomplete()
			->setCrudController(QuestionCrudController::class);
		yield AssociationField::new('answeredBy');
		yield Field::new('createdAt')
			->hideOnIndex();
		yield Field::new('updatedAt')
			->onlyOnDetail();

    }

}
