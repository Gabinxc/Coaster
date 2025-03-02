<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CoasterVoter extends Voter
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ) {}

    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Coaster;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) return true;
        if(in_array("ADMIN", $user->getRoles())) {
            return true;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $subject->getUser() === $user;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return true;
        }

        return false;
    }

}
