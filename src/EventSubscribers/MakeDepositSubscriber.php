<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Deposit;
use App\Entity\User;
use App\Utils\AccountNumberGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class MakeDepositSubscriber implements EventSubscriberInterface
{
   private $tokenStorage;
   private $generator;

    public function __construct(
       TokenStorageInterface $tokenStorage,
       AccountNumberGenerator $generator
       )
    {
       $this->tokenStorage = $tokenStorage;
       $this->generator = $generator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['checkDepositData', EventPriorities::POST_VALIDATE],
        ];
    }

    public function checkDepositData(ViewEvent $event)
    {
        
        $deposit = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$deposit instanceof Deposit || (Request::METHOD_POST !== $method && Request::METHOD_PUT !== $method))
        {
            return;
        }

        $currentUser = $this->tokenStorage->getToken()->getUser();

        if (!$currentUser instanceof User)
        {
            return;
        }
        

        $deposit->setCreator($currentUser);

        $deposit->getAccount()->setBalance($deposit->getAccount()->getBalance() + $deposit->getAmount());
       
        
    }
}