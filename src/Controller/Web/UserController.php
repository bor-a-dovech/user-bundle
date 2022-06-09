<?php

namespace Glavnivc\UserBundle\Controller\Web;

use Doctrine\ORM\EntityManagerInterface;
use Glavnivc\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @Route("/user/test/", name="usertest")
     */
    public function test(Request $request)
    {
        return new Response('ok!!!');
    }

    /**
     * Создание тестового пользователя.
     * TODO: удалить
     *
     * @Route("/user/init/", name="user_init")
     */
    public function init()
    {
        $user = new User();
        $user
            ->setUsername('bsa')
            ->setEmail('b-s-a@mail.ru')
            ->setRoles([
                'SUPER ADMIN'
            ])
        ;
        dump($user, $this->em);
        die();


    }

    public function list()
    {

    }


}
