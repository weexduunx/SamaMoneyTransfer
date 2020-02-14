<?php

namespace App\Utils;

use App\Entity\PartnerAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountNumberGenerator 
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    public function generate ()
    {
      $accountNumber = rand(100000000, 999999999) ;

      $checkUnique = $this->manager->getRepository(PartnerAccount::class)->findOneBy(['accountNumber'=>$accountNumber]) ;

     while(!is_null($checkUnique))
     {
        $accountNumber = rand(100000000, 999999999) ;
        $checkUnique = $this->manager->getRepository(PartnerAccount::class)->findOneBy(['accountNumber'=>$accountNumber]) ;
     }

      return $accountNumber;
    }

}