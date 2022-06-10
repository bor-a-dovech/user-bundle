<?php

namespace Glavnivc\UserBundle\Controller\Web;

use Doctrine\ORM\EntityManagerInterface;
use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * TODO: пагинация
     *
     * @Route("/user/list/", name="user_list")
     * @Template("@User/user/list.html.twig")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function list(
        Request $request,
        UserRepository $userRepository
    )
    {
        $users = $userRepository->findAll();
        return [
            'users' => $users,
        ];

    }

    /**
     * Добавление нового пользователя.
     * @Route("/user/add/", name="user_add")
     */
    public function add()
    {


    }

    /**
     * Редактирование пользователя.
     * @Route("/user/{id}/edit/", name="user_edit")
     * @Template("@User/user/edit.html.twig")
     *
     */
    public function edit(User $user)
    {
        return [
            'user' => $user,
        ];

    }

    /**
     * Удаление пользователя.
     *
     * @Route("/user/{id}/delete/", name="user_delete")
     */
    public function delete(
        User $user,
        Request $request
    )
    {
        // event
        $this->em->remove($user);
        $this->em->flush();
        $this->addFlash('success', 'User has been deleted.');
        $redirectUrl = (($request->query->get('fromUrl'))
            ?
            : $this->generateUrl('user_list')
        );
        return $this->redirect($redirectUrl);
    }

    /**
     * Просмотр карточки пользователя.
     * @Route("/user/{id}/view/", name="user_view")
     * @Template("@User/user/view.html.twig")
     */
    public function view(User $user)
    {
        return [
            'user' => $user,
        ];

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
    public function init() : Response
    {
        $user = new User();
        $user
            ->setUsername('bsa')
            ->setEmail('b-s-a@mail.ru')
            ->setPassword('kek')
        ;
        try {
            $this->em->persist($user);
            $this->em->flush();
            $message = 'Создан пользователь <b>' . $user->getUsername() . '</b>';
        } catch (\Exception $e) {
            $message = '<b>Пользователя создать не удалось.</b><br>' . $e->getMessage();
        }
        return new Response($message);
    }

    /**
     * Создание нескольких тестовых пользователей.
     * TODO: удалить
     *
     * @Route("/user/init/{count}", name="user_init_count")
     */
    public function initcount(int $count) : Response
    {
        $good = 0;
        $bad = 0;
        $words = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $colors = ['red', 'green', 'blue', 'rose', 'purple', 'yellow', 'pink', 'aquamarine'];
        for ($i = 0; $i < $count; $i++) {
            $word = $words[array_rand($words)];
            $color = $colors[array_rand($colors)];
            $user = new User();
            $user
                ->setUsername('robot ' . $color . ' ' . $word . ' ' . $i)
                ->setEmail('b-s-a' . rand(10000, 99999) . '@mail.ru')
                ->setPassword('kek')
            ;
            try {
                $this->em->persist($user);
                $this->em->flush();
                $good++;
                $message = 'Создан пользователь <b>' . $user->getUsername() . '</b>';
            } catch (\Exception $e) {
                $bad++;
                $message = '<b>Пользователя создать не удалось.</b><br>' . $e->getMessage();
            }
        }

        return new Response($good . ' пользователей создано');
    }
}
