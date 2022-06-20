<?php

namespace Glavnivc\UserBundle\Controller;

use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Repository\UserRepository;
use Glavnivc\UserBundle\UserBundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestController extends AbstractController
{


    /**
     * @Route("/test/", name="test")
     */
    public function test(Request $request)
    {
        return new Response('ok');
    }

    /**
     * @Route("/profile", name="user.profile")
     */
    public function profile()
    {
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/ppp", name="test_password")
     */
    public function password(UserPasswordEncoderInterface $encoder)
    {
        // whatever *your* User object is
        $user = new User();
        $plainPassword = 'kek';
        $encoded = $encoder->encodePassword($user, $plainPassword);

        $user->setPassword($encoded);
        dump($encoded, $user);
        die();
    }

}
