<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use App\Entity\UserJob;
use App\Entity\UserTeam;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Antoine');
        $user->setLastname('OZEER');
        $user->setEmail('antoine.ozeer@troisprime.com');
        $user->setPassword($this->hasher->hashPassword(
            $user,
            'antoine_1994'
        ));

        $user2 = new User();
        $user2->setFirstname('Mélanie');
        $user2->setLastname('PETIT');
        $user2->setEmail('melanie.petit@troisprime.com');
        $user2->setPassword($this->hasher->hashPassword(
            $user2,
            'melanie_1994'
        ));

        $manager->persist($user);
        $manager->persist($user2);

        $job = new UserJob();
        $job->setName('Chef de Projet');
        $job2 = new UserJob();
        $job2->setName('Directeur de Projet');

        $manager->persist($job);
        $manager->persist($job2);

        $team = new UserTeam();
        $team->setName('Team Mélanie');
        $team->setManager($user2);
        $team->addUser($user);
        $team->addUser($user2);

        $manager->persist($team);

        $user->addTeam($team);
        $user->setJob($job);

        $user2->addTeam($team);
        $user2->setJob($job2);

        // Clients
        $client = new Client();
        $client->setName('Janssen');
        $client->setCreatedBy($user);
        $client2 = new Client();
        $client2->setName('Bausch & Lomb');
        $client2->setCreatedBy($user);
        $client3 = new Client();
        $client3->setName('MSD');
        $client3->setCreatedBy($user);
        $client4 = new Client();
        $client4->setName('Takeda');
        $client4->setCreatedBy($user);
        $client5 = new Client();
        $client5->setName('Chugai');
        $client5->setCreatedBy($user);

        $manager->persist($client);
        $manager->persist($client2);
        $manager->persist($client3);
        $manager->persist($client4);
        $manager->persist($client5);

        $manager->flush();
    }
}
