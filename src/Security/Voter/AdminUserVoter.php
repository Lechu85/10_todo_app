<?php

namespace App\Security\Voter;

use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUserVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
	private Security $security;

	public function __construct(Security $security)	{
		$this->security = $security;
	}

	protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['ADMIN_USER_EDIT'])//self::EDIT, self::VIEW
            && $subject instanceof User; //\App\Entity\AdminUser;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

		if (!$user instanceof User) {
			throw new \LogicException('Subject is not an instance of User');
		}

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'ADMIN_USER_EDIT':
                return $user === $subject || $this->security->isGranted('ROLE_ADMIN');
                break;
        }

        return false;
    }
}
