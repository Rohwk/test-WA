<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonneFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $personne = new Personne();
        $personne
            ->setNom("Dupond")
            ->setPrenom("Alban")
            ->setDateNaissance(new DateTime("01/01/2000"))
        ;
        $manager->persist($personne);

        $personne = new Personne();
        $personne
            ->setNom("Dupont")
            ->setPrenom("Bernard")
            ->setDateNaissance(new DateTime("02/02/2001"))
        ;
        $manager->persist($personne);

        $personne = new Personne();
        $personne
            ->setNom("Durand")
            ->setPrenom("Charles")
            ->setDateNaissance(new DateTime("03/03/2002"))
        ;
        $manager->persist($personne);

        $personne = new Personne();
        $personne
            ->setNom("Durant")
            ->setPrenom("Denis")
            ->setDateNaissance(new DateTime("04/04/2003"))
        ;
        $manager->persist($personne);

        $manager->flush();
    }
}
