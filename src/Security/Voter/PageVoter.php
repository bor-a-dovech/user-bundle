<?php

namespace Glavnivc\UserBundle\Security\Voter;


use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Security\Permission\Service\PermissionServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PageVoter extends Voter
{
    private $decisionManager;
    private $permissionService;

    public function __construct(
        AccessDecisionManagerInterface $decisionManager,
        PermissionServiceInterface $permissionService
    )
    {
        $this->permissionService = $permissionService;
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if($attribute=="IS_AUTHENTICATED_ANONYMOUSLY"){
            return true;
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        $result = $this->permissionService->hasUserPermission($user,$attribute);
        return $result;
    }

}