<?php


namespace Glavnivc\UserBundle\Security\Permission\Service;

use Glavnivc\UserBundle\Security\Credentials\CheckCredentialsServiceInterface;
use Glavnivc\UserBundle\Security\Permission\Provider\PermissionProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Cache\CacheInterface;

class LocalPermissionService extends PermissionService
{
    /**
     * LocalPermissionService constructor.
     * @param PermissionProviderInterface $permissionProvider
     * @param CheckCredentialsServiceInterface $checkCredentialsService
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        PermissionProviderInterface $permissionProvider,
        CheckCredentialsServiceInterface $checkCredentialsService,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($permissionProvider, $checkCredentialsService, $cache, $logger);
    }


    /**
     * @param UserInterface $user
     * @param string $permissionName
     * @return bool
     */
    public function hasUserPermission(UserInterface $user, string $permissionName): bool
    {
        $roles = $user->getRoles();
        if (in_array("ROLE_ADMIN", $roles)) {
            return true;
        }
        $permissions = $this->getUserPermissions($user);
        if (isset($permissions[$permissionName])) {
            return true;
        }
        return false;
    }

    public function getUserPermissions(UserInterface $user): array
    {
        $roles = $user->getRoles();
        $result = $this->getPermissionProvider()->getUserPermissions($roles);
        return $result;
    }
}
