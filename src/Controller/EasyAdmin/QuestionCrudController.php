<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Question;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }


    public function configureFields(string $pageName): iterable
    {
		yield IdField::new('id')
			->onlyOnIndex();
		yield Field::new('name');
	    yield AssociationField::new('topic');
		yield TextareaField::new('question')
			->hideOnIndex();
	    yield AssociationField::new('askedBy')
		    ->autocomplete()
	        ->formatValue(static function($value, Question $question) { //value to wartośc pola, a $question to obiekt question obecny
				if (!$user = $question->getAskedBy()) { //sprawdza czy zmienna user istnieje i czy sa zwraca coś getAskedBy()
					return null;
				}

				return sprintf('%s&nbsp;(%s)', $user->getEmail(), $user->getQuestions()->count());

	        })
		    ->setQueryBuilder(function (QueryBuilder $queryBuilder) {//tutaj tylko modyfikiujemy queryBuildera więc nie potrzebujemy nic zwracać
				$queryBuilder->andWhere('entity.enabled = :enabled')
					->setParameter('enabled', 1);
		    });
	    yield Field::new('votes','Total Votes')
	        ->setTextAlign('right');
		yield AssociationField::new('answers')//orphant removal trzeba dołożyć, żeby nie było pytań bez autora :)
			->autocomplete()
			//jak jest autoicomplete to 'choice_label nie działa'
			//->setFormTypeOption('choice_label', 'id')//tutaj wybieramy z które pole encji chcemy wybrać
			->setFormTypeOption('by_reference', false);//musi byc, żeby sięzapisało
	    yield Field::new('createAt')
	        ->hideOnForm();
    }

}
