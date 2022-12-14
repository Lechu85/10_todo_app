<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TopicFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		//todo - emaile użytkownikó ustawić wg roli czyli superadmin@todoapp.pl itp
        // Load Users
        UserFactory::new()
            ->withAttributes([
                'email' => 'superadmin@todoapp.pl',
                'plainPassword' => 'test123',
            ])
            ->promoteRole('ROLE_SUPER_ADMIN')
            ->create();

        UserFactory::new()
            ->withAttributes([
                'email' => 'admin@todoapp.pl',
                'plainPassword' => 'test123',
            ])
            ->promoteRole('ROLE_ADMIN')
            ->create();

        UserFactory::new()
            ->withAttributes([
                'email' => 'moderator@todoapp.pl',
                'plainPassword' => 'test123',
            ])
            ->promoteRole('ROLE_MODERATOR')
            ->create();

        UserFactory::new()
            ->withAttributes([
                'email' => 'user@todoapp.pl',
                'plainPassword' => 'test123',
                'name' => 'Tisha The Cat',
                'avatar' => 'tisha.png',
            ])
            ->create();

        // Load Topics
        TopicFactory::new()->createMany(5);

        // Load Questions
        QuestionFactory::new()->createMany(20);

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);

        // Load Answers
        AnswerFactory::new()->createMany(100);

        $manager->flush();
    }
}
