<?php

namespace Glavnivc\UserBundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Glavnivc\UserBundle\Entity\Role;
use Glavnivc\UserBundle\Normalizer\RoleNormalizer;
use Glavnivc\UserBundle\Repository\RoleRepository;
use Glavnivc\UserBundle\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/role")
 */
class RoleController extends AbstractController
{
    const PAGINATION_LIMITS = [10, 30, 120];

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @Route("/list", name="api_role_list")
     * @Template("@User/role/list.html.twig")
     *
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function list(
        Request $request,
        RoleRepository $roleRepository,
        PaginatorInterface $paginator
    )
    {
        $serializer = new Serializer(
            [new RoleNormalizer()],
            [new JsonEncoder()]
        );
        $currentLimit = (int)$request->query->get('limit', self::PAGINATION_LIMITS[0]) ? : 1024;
        $page = $request->query->getInt('page', 1);

        $query = $roleRepository->createQueryBuilder('r')->getQuery();

        $roles = $paginator->paginate(
            $query,
            $page,
            $currentLimit,
            []
        );
        $result = [];
        foreach ($roles->getItems() as $item) {
            $result[] = $serializer->normalize($item);
        }
        return new JsonResponse($result);
    }

    /**
     * Удаление роли.
     *
     * @Route("/{id}/delete", name="api_role_delete", methods={"DELETE"})
     */
    public function delete(Role $role)
    {
        $this->em->remove($role);
        $this->em->flush();
        $result = [
            'result' => 'ok',
        ];
        return new JsonResponse($result);
    }

    /**
     * Просмотр карточки роли.
     * @Route("/{id}/view", name="api_role_view", methods={"GET"})
     */
    public function view(Role $role, UserRepository $userRepository) : JsonResponse
    {
        $serializer = new Serializer(
            [new RoleNormalizer()],
            [new JsonEncoder()]
        );
        $result = $serializer->normalize($role);
        return new JsonResponse($result);
    }
}
