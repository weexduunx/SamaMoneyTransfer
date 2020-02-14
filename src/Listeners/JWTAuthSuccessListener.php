<?php
namespace App\Listeners;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthSuccessListener {

    private $entityManager;
    private $loginInfos;
    

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request
        )
    {
        $this->loginInfos  = json_decode($request->getCurrentRequest()->getContent());
        $this->entityManager = $entityManager;

    }
    

 
    /**
     * @param AuthenticationSuccessEvent $event
     */
   public function onAuthenticationSuccess(AuthenticationSuccessEvent $event) {
    $response = $event->getResponse();
     
    $user = $this->entityManager->getRepository(User::class)->findOneBy([
        'username'=>$this->loginInfos->username,
        'isActive' => false]);

    $data = $event->getData();
     
    if(is_null($user) === false){

       $response->setStatusCode(Response::HTTP_LOCKED);

       unset($data['token']);

       $data['message'] = "You has been blocked. Contact your supervisor for more informations";
    }

        $event->setData($data);
    
   





   
   }

}
