<?php

namespace App\Security;

use App\Entity\Tools\Signature\Qrcode;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $urlGenerator;
    private $user;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator)
    {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request)
    {
        return 'token_login' === $request->attributes->get('_route')
            && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->attributes->get('token'),
            'session' => $request->attributes->get('session')
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'];
        $session = $credentials['session'];

        if (null === $token || null === $session) {
            return;
        }

        $qrcode = $this->em->getRepository(Qrcode::class)
            ->findOneBy([
                'token' => $token,
                'session' => $session
            ]);

        $user = $qrcode->getUser();
        $this->user = $user;

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
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

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
