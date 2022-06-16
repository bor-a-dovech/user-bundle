<?php

namespace Glavnivc\UserBundle\Controller\Api;

use App\Normalizer\PermissionNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Glavnivc\UserBundle\Entity\Permission;
use Glavnivc\UserBundle\Repository\PermissionRepository;
use Glavnivc\UserBundle\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/permission")
 */
class PermissionController extends AbstractController
{
    const PAGINATION_LIMITS = [10, 30, 120];

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * @Route("/list", name="api_permission_list", methods={"GET"})
     *
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function list(
        Request $request,
        PermissionRepository $permissionRepository,
        PaginatorInterface $paginator
    ) : JsonResponse
    {
        $serializer = new Serializer(
            [new PermissionNormalizer()],
            [new JsonEncoder()]
        );

        $currentLimit = (int)$request->query->get('limit', self::PAGINATION_LIMITS[0]) ?: 1024;
        $page = $request->query->getInt('page', 1);

        $query = $permissionRepository->createQueryBuilder('p')->getQuery();

        $permissions = $paginator->paginate(
            $query,
            $page,
            $currentLimit,
            []
        );

        $result = [];
        foreach ($permissions->getItems() as $item) {
            $result[] = $serializer->normalize($item);
        }
        return new JsonResponse($result);
    }

    /**
     * Удаление пермишна.
     *
     * @Route("/{id}/delete", name="api_permission_delete", methods={"DELETE"})
     */
    public function delete(Permission $permission) : JsonResponse
    {
        $this->em->remove($permission);
        $this->em->flush();
        $result = [
            'result' => 'ok',
        ];
        return new JsonResponse($result);

    }

    /**
     * Просмотр карточки пермишна.
     * @Route("/{id}/view", name="api_permission_view", methods={"GET"})
     */
    public function view(Permission $permission) : JsonResponse
    {
        $serializer = new Serializer(
            [new PermissionNormalizer()],
            [new JsonEncoder()]
        );
        $result = $serializer->normalize($permission);
        return new JsonResponse($result);
    }
}
