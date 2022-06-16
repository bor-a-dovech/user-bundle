<?php

namespace Glavnivc\UserBundle\Event\Listener\Menu;

use Glavnivc\UserBundle\Event\MenuEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

/**
 * Меню в списке пользователей.
 */
class UserListener
{
    private $security;
    private $requestStack;

    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    /**
     * @param MenuEvent $event
     */
    public function onMenu(MenuEvent $event)
    {
        $options = $event->getOptions();
        $id = $options['id'] ?? null;
        $menu = $event->getMenu();

        $menu->addChild('Users list menu caption', ['route' => 'user_list']);
        $menu->addChild('Roles list menu caption', ['route' => 'role_list']);
        $menu->addChild('Permissions list menu caption', ['route' => 'permission_list']);
    }
}
