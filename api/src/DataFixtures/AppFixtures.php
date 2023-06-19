<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $employee = new Employee();
        $employee->setName('Doe');
        $employee->setFirstName('John');
        $employee->setEmail('john.doe@norsys.fr');
        $manager->persist($employee);

        $company = new Company();
        $company->setName('Norsys');
        $manager->persist($company);

        $company2 = new Company();
        $company2->setName('Kiss The Bride');
        $manager->persist($company2);

        $manager->flush();
    }
}
