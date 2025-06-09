<?php

namespace App\Security;

use App\Entity\User\User;
use App\Repository\QrcodeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class UrlTokenAuthenticator extends AbstractAuthenticator
{
    private User $user;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly QrcodeRepository $qrcodeRepository,
    )
    {}

    public function supports(Request $request): ?bool
    {
        return 'token_login' === $request->attributes->get('_route')
            && $request->isMethod('GET');
    }

    public function authenticate(Request $request): Passport
    {
        $session = $request->attributes->get('session');
        $token = $request->attributes->get('token');

        if (null === $session || null === $token) {
            throw new CustomUserMessageAuthenticationException('Missing authentication parameters.');
        }

        $qrcode = $this->qrcodeRepository
            ->findOneBy([
                'token' => $token,
                'session' => $session
            ]);

        $this->user = $qrcode->getUser();

        // Crée un Passport avec l'utilisateur authentifié
        return new Passport(
            new UserBadge($this->user->getUserIdentifier()),
            new CustomCredentials(function () {
                return true;
            }, $token)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $sessions = $this->user->getGroupEvent()->getSessions();
        $event = $sessions[0]->getEvent();

        $redirection = new RedirectResponse(
            $this->urlGenerator->generate('event_show', [
                'event' => $event->getId()
            ])
        );

        return $redirection;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->getFlashBag()->add('error', $exception->getMessage());
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}
