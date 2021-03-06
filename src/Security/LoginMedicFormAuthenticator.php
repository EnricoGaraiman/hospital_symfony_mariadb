<?php

namespace App\Security;

use App\Entity\Medic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginMedicFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'login-medic';

    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    private $urlGenerator;
    private $entityManager;
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager,
                                EmailVerifier $emailVerifier)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->emailVerifier = $emailVerifier;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $email = $request->request->get('_username', '');
        $user = $this->entityManager->getRepository(Medic::class)->findOneBy(['email' => $email]);
        /**
         * @var Medic $user
         */
        if($user !== null) {
            if ($user->isVerified() == 0) {
                throw new CustomUserMessageAuthenticationException('Trebuie s?? ????i confirmi emailul pentru a te putea autentifica prima oar?? ??n cont.');
            }
            $request->getSession()->set(Security::LAST_USERNAME, $email);
            $badges = [new CsrfTokenBadge('authenticate', $request->get('_csrf_token'))];
            return new Passport(
                new UserBadge($email),
                new PasswordCredentials($request->request->get('_password', '')), $badges
            );
        }
        else{
            throw new CustomUserMessageAuthenticationException('Acest email nu exist?? ??n baza noastr?? de date. ');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        /**
         * @var Medic $user
         */
        $user = $token->getUser();

        return new RedirectResponse($this->urlGenerator->generate('medic_profile', [
            'id' => $user->getId()
        ]));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}