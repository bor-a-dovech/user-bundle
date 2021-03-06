<?php

namespace Glavnivc\UserBundle\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Normalizer\RoleNormalizer;
use Glavnivc\UserBundle\Repository\RoleRepository;
use Glavnivc\UserBundle\Repository\UserRepository;
use Glavnivc\UserBundle\Service\ResultJsonService;
use Glavnivc\UserBundle\Service\StringService;
use Glavnivc\UserBundle\Service\UserRightsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Контроллер для связи ролей с пользователем.
 *
 * @Route("/api/user")
 */
class UserRoleController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        ResultJsonService $resultJsonService,
        UserRepository $userRepository,
        UserRightsService $userRightsService,
        StringService $stringService,
        RoleRepository $roleRepository
    )
    {
        $this->em = $em;
        $this->userPasswordEncoder = $encoder;
        $this->resultJsonService = $resultJsonService;
        $this->userRepository = $userRepository;
        $this->userRightsService = $userRightsService;
        $this->stringService = $stringService;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Привязать роль к пользователю.
     * Если у пользователя такая роль уже есть, ничего не происходит.
     * Можно привязывать в пакетном режиме.
     *
     * @Route("/{id}/role/{role}/", name="rest_api_user_role_add", methods={"POST"})
     *
     * @param User $user
     * @param string $role Id роли или Json-массив со списком id;
     * @return JsonResponse
     */
    public function add(User $user, string $role) : JsonResponse
    {
        $roles = (($this->stringService->isJson($role))
            ? json_decode($role)
            : [$role]
        );
        foreach ($roles as $roleId) {
            $role = $this->roleRepository->find($roleId);
            if ($role) {
                $userRoles = $user->getRole();
                if (!$userRoles->contains($role)) {
                    $userRoles->add($role);
                }
            } else {
                return new JsonResponse($this->resultJsonService->error('Role with id ' . $roleId . ' not found.'));
            }
        }
        $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse($this->resultJsonService->ok());
    }

    /**
     * Отвязать роль от пользователя.
     * Можно в пакетном режиме.
     *
     * @Route("/{id}/role/{role}/", name="rest_api_user_role_delete", methods={"DELETE"})
     *
     * @param User $user
     * @param string $role Id роли или Json-массив со списком id;
     * @return JsonResponse
     */
    public function delete(User $user, string $role) : JsonResponse
    {
        $roles = (($this->stringService->isJson($role))
            ? json_decode($role)
            : [$role]
        );
        foreach ($roles as $roleId) {
            $role = $this->roleRepository->find($roleId);
            if ($role) {
                $userRoles = $user->getRole();
                if ($userRoles->contains($role)) {
                    $userRoles->removeElement($role);
                }
            } else {
                return new JsonResponse($this->resultJsonService->error('Role with id ' . $roleId . ' not found.'));
            }
        }
        $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse($this->resultJsonService->ok());
    }

    /**
     * Список ролей, привязанных к пользователю.
     *
     * @Route("/{id}/role/", name="rest_api_user_role_list", methods={"GET"})
     *
     * @param User $user
     */
    public function list(User $user) : JsonResponse
    {
        $serializer = new Serializer(
            [new RoleNormalizer()],
            [new JsonEncoder()]
        );
        $roles = $this->userRightsService->getRoles($user);
        $result = [];
        if ($roles) {
            foreach ($roles as $role) {
                $result[] = $serializer->normalize($role);
            }
        }
        return new JsonResponse($result);
    }
}
