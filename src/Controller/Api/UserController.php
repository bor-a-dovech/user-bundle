<?php

namespace Glavnivc\UserBundle\Controller\Api;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Glavnivc\UserBundle\Entity\Role;
use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Form\Type\UserType;
use Glavnivc\UserBundle\Normalizer\RoleNormalizer;
use Glavnivc\UserBundle\Normalizer\UserNormalizer;
use Glavnivc\UserBundle\Repository\RoleRepository;
use Glavnivc\UserBundle\Repository\UserRepository;
use Glavnivc\UserBundle\Service\UserRightsService;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Util\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    const PAGINATION_LIMITS = [10, 30, 120];

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->em = $em;
        $this->userPasswordEncoder = $encoder;
    }

    /**
     * @Route("/list", name="api_user_list")
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function list(
        Request $request,
        UserRepository $userRepository,
        PaginatorInterface $paginator
    ) : JsonResponse
    {
        $serializer = new Serializer(
            [new UserNormalizer()],
            [new JsonEncoder()]
        );
        $currentLimit = (int)$request->query->get('limit', self::PAGINATION_LIMITS[0]) ? : 1024;
        $page = $request->query->getInt('page', 1);

        $query = $userRepository->createQueryBuilder('u')->getQuery();

        $users = $paginator->paginate(
            $query,
            $page,
            $currentLimit,
            []
        );
        $result = [];
        foreach ($users->getItems() as $item) {
            $result[] = $serializer->normalize($item);
        }
        return new JsonResponse($result);
    }

    /**
     * Удаление пользователя.
     *
     * @Route("/{id}/delete", name="user_delete", methods={"DELETE"})
     */
    public function delete(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
        $result = [
            'result' => 'ok',
        ];
        return new JsonResponse($result);
    }

    /**
     * Просмотр карточки пользователя.
     * @Route("/{id}/view", name="api_user_view", methods={"GET"})
     */
    public function view(User $user, UserRightsService $userRightsService) : JsonResponse
    {
        $serializer = new Serializer(
            [new UserNormalizer()],
            [new JsonEncoder()]
        );
        $result = $serializer->normalize($user);
        $rolesValues = $userRightsService->getRolesValues($user);
        $permissionsValues = $userRightsService->getPermissionsValues($user);
        $result['roles'] = array_values($rolesValues);
        $result['permissions'] = array_values($permissionsValues);
        return new JsonResponse($result);
    }
}
