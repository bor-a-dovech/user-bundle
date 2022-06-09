<?php

namespace Glavnivc\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        dump('profiled');
        die();
    }

}
