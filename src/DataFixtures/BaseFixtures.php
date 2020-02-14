<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use App\Entity\Roles;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker =  Factory::create('fr_FR');
         
         $role1 = new  Roles();
         $role1->setRoleName('ROLE_SUPER_ADMIN');
         $manager->persist($role1);

         $role2 = new  Roles();
         $role2->setRoleName('ROLE_ADMIN');
         $manager->persist($role2);


         $role3 = new  Roles();
         $role3->setRoleName('ROLE_CAISSIER');
         $manager->persist($role3);


         $role4 = new  Roles();
         $role4->setRoleName('ROLE_PROPIETAIRE_AGENCE');
         $manager->persist($role4);

         $role5 = new  Roles();
         $role5->setRoleName('ROLE_AGENCY_ADMIN');
         $manager->persist($role5);

         $role6 = new  Roles();
         $role6->setRoleName('ROLE_AGENCY_CASHIER');
         $manager->persist($role6);


         


        $superAdmin = new User();

        $superAdmin->setUsername($faker->userName);
        $superAdmin->setPassword($this->encoder->encodePassword($superAdmin,'super'));
        $superAdmin->setEmail($faker->email);
        $superAdmin->setFname($faker->firstName());
        $superAdmin->setLname($faker->lastName);
        $superAdmin->setPhone($faker->phoneNumber);
        $superAdmin->setIsActive($faker->boolean(true));
        $superAdmin->setCountry($faker->country);
        $superAdmin->setCity($faker->city);
        $superAdmin->setAddress($faker->address);
        $role1->addUser($superAdmin);
        $manager->persist($superAdmin);

        for ($i=0; $i < 5; $i++){
            $admin = new User();
            $admin->setUsername($faker->userName);
            $admin->setPassword($this->encoder->encodePassword($superAdmin,'admin'));
            $admin->setEmail($faker->email);
            $admin->setFname($faker->firstName());
            $admin->setLname($faker->lastName);
            $admin->setPhone($faker->phoneNumber);
            $admin->setIsActive($faker->boolean(true));
            $admin->setCountry($faker->country);
            $admin->setCity($faker->city);
            $admin->setAddress($faker->address);
            $role2->addUser($admin);
            $admin->addSupervisor($superAdmin);
            $superAdmin->addSupervisorUser($admin);

            $manager->persist($admin);

        
            $casher = new User();
            $casher->setUsername($faker->userName);
            $casher->setPassword($this->encoder->encodePassword($superAdmin,'casher'));
            $casher->setEmail($faker->email);
            $casher->setFname($faker->firstName());
            $casher->setLname($faker->lastName);
            $casher->setPhone($faker->phoneNumber);
            $casher->setIsActive($faker->boolean(true));
            $casher->setCountry($faker->country);
            $casher->setCity($faker->city);
            $casher->setAddress($faker->address);
            $role3->addUser($casher);
            $casher->addSupervisor($admin);
            $admin->addSupervisorUser($casher);

            $manager->persist($casher);

           /*


            $owner = new User();
            $owner->setUsername($faker->userName);
            $$owner->setPassword($this->encoder->encodePassword($superAdmin,'owner'));
            $owner->setEmail($faker->email);
            $owner->setFname($faker->firstName());
            $owner->setLname($faker->lastName);
            $owner->setPhone($faker->phoneNumber);
            $owner->setIsActive($faker->boolean(true));
            $owner->setCountry($faker->country);
            $owner->setCity($faker->city);
            $owner->setAddress($faker->address);
            $role4->addUser($owner);
            $owner->addSupervisor($admin);
            $admin->addSupervisorUser($owner);
       
            $manager->persist($owner);

            */

        }
        
     

        $manager->flush();
    }
}
