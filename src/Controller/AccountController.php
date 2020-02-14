<?php 

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Agency;
use App\Entity\Roles;
use App\Entity\User;
use App\PermissionRoles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class  AccountController  {

     /**
      * @var TokenStorageInterface $storage
      */
     private $storage;

     private $entityManager;

     private $normalizer;

     public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer)
     {
        $this->storage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
     }

     public function account()
     {
         $account = new Account();

        $token = $this->storage->getToken();
        if ($token instanceof TokenInterface) {

         /** 
          * @var User $user 
          */
         $user = $token->getUser();
         $role = $user->getRoles()[0];
         $userData =   $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$user->getUsername()]);
         

         $account->setUsername($user->getUsername());
         $account->setEmail($userData->getEmail());
         $account->setPhone($userData->getPhone());
         $account->setAddress($userData->getAddress());

         if($role == PermissionRoles::SUPER_ADMIN || $role == PermissionRoles::ADMIN)
         {
            $numberOfAgencies =   count($this->entityManager->getRepository(Agency::class)->findAll());
            $numberOfPartner  =   count($this->entityManager->getRepository(User::class)->findBy(['userRoles'=>
            
            $this->entityManager->getRepository(Roles::class)->findOneBy(['role_name'=>PermissionRoles::OWNER])->getId()
            ]));
            $account->setNumberOfAgencies($numberOfAgencies);
            $account->setNumberOfPartner($numberOfPartner);
            
         }

         if($role == PermissionRoles::OWNER)
         {
            $totalBalance = 0;

            $currentUserAgencies = $this->entityManager->getRepository(Agency::class)->findBy([
               'owner' => $userData
            ]);

            foreach ($currentUserAgencies as $agency) {
               $totalBalance += $agency->getAmount();
            }

            $account->setTotalBalance($totalBalance);
         }

         $data = $this->normalizer->normalize($account,'json',[]);
         return new JsonResponse($data);

     } 
       

     }

}