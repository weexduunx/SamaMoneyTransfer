<?php 

namespace App\EventSubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

final class RegisterMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $twig;

    public function __construct(
        \Swift_Mailer $mailer, 
        Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event)
    {

        
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $message = (new \Swift_Message('Your account has been created successfully'))
            ->setFrom('senghor.pape912@hotmail.com')
            ->setTo('senghor.pape912@hotmail.com')
            ->setBody($this->twig->render('accountValidation.html.twig',[
                'user' => $user
            ]));

        $this->mailer->send($message);

        
    }
}