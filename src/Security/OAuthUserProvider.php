<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Exception\OAuthRegistrationNeededException;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class OAuthUserProvider implements OAuthAwareUserProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository         $userRepository,
        private RequestStack           $requestStack,

    )
    {
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface
    {
        $data = $response->getData();
        $service = $response->getResourceOwner()->getName(); // 'github' or 'google'
        $providerId = $response->getUsername() ?? $data['id'] ?? null;                 // the provider's user id
        $email = $response->getEmail() ?? $data['email'] ?? null;

        // 1. Already linked to this provider before?
        $user = $this->userRepository->findOneBy([$service . 'Id' => $providerId]);

        // 2. Otherwise, try to match an existing account by email (account linking)
        $user = $this->getByEmail(user: $user, service: $service, email: $email, providerId: $providerId);
        if (!$user) {
            // Not registered yet — save what we got, and bail out with a special exception
            $this->requestStack->getSession()->set('oauth_pending_registration', [
                'service' => $service,
                'providerId' => $providerId,
                'email' => $email,
                'name' => $response->getRealName() ?? $data['name'] ?? null,
                'avatar' => $response->getProfilePicture() ?? $data['picture'] ?? null,
                'firstName' => $response->getFirstName() ?? $data['given_name'] ?? null,
                'lastName' => $response->getLastName() ?? $data['family_name'] ?? null,
            ]);

            throw new OAuthRegistrationNeededException('Registration required.');
        }

        return $user;
    }

    /**
     * @param User|null $user
     * @param string $service
     * @param mixed $email
     * @param string|int|null $providerId
     * @return User|null
     */
    public function getByEmail(User|null $user, string $service, string|null $email, string|int|null $providerId): User|null
    {
        if (!$user && $email) {
            $user = $this->userRepository->findOneBy(['email' => $email]);
            $getter = 'get' . ucfirst($service) . 'Id';
            if ($user && $user?->$getter() === null) {

                $setter = 'set' . ucfirst($service) . 'Id';
                $user->$setter($providerId);
                $this->em->persist($user);
                $this->em->flush();
            }
        }
        return $user;
    }

}
