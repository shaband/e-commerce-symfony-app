<?php
namespace App\EventListener;

use App\Security\Exception\OAuthRegistrationNeededException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

#[AsEventListener]
class LoginFailureListener
{
    public function __construct(private readonly RouterInterface $router) {}

    public function __invoke(LoginFailureEvent $event): void
    {
        if ($event->getException() instanceof OAuthRegistrationNeededException) {
            $event->setResponse(new RedirectResponse(
                $this->router->generate('app_register')
            ));
        }
    }
}
