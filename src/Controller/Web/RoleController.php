<?php

namespace Glavnivc\UserBundle\Controller\Web;

use Doctrine\ORM\EntityManagerInterface;
use Glavnivc\UserBundle\Entity\Permission;
use Glavnivc\UserBundle\Entity\Role;
use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Repository\RoleRepository;
use Glavnivc\UserBundle\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/role")
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
     * @Route("/list", name="role_list")
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
        $currentLimit = (int)$request->query->get('limit', self::PAGINATION_LIMITS[0]) ? : 1024;
        $page = $request->query->getInt('page', 1);

        $query = $roleRepository->createQueryBuilder('r')->getQuery();

        $users = $paginator->paginate(
            $query,
            $page,
            $currentLimit,
            []
        );
        return [
            'roles' => $users,
            'currentLimit' => $currentLimit,
            'limitsList' => self::PAGINATION_LIMITS,
        ];

    }

    /**
     * @Route("/add/", name="role_add")
     */
    public function add()
    {


    }

    /**
     * Редактирование роли.
     * @Route("/{id}/edit/", name="role_edit")
     * @Template("@User/role/edit.html.twig")
     *
     */
    public function edit(Role $role)
    {
        return [
            'role' => $role,
        ];

    }

    /**
     * Удаление роли.
     *
     * @Route("/{id}/delete", name="role_delete")
     */
    public function delete(
        Role $role,
        Request $request
    )
    {
        $this->em->remove($role);
        $this->em->flush();
        $this->addFlash('success', 'Role has been deleted.');
        $redirectUrl = (($request->query->get('fromUrl'))
            ?
            : $this->generateUrl('role_list')
        );
        return $this->redirect($redirectUrl);
    }

    /**
     * Просмотр карточки роли.
     * @Route("/{id}/view", name="role_view")
     * @Template("@User/role/view.html.twig")
     */
    public function view(Role $role)
    {
        return [
            'role' => $role,
        ];

    }

    /**
     * Создание тестовой роли с пермишном.
     * TODO: удалить
     *
     * @Route("/init", name="role_init")
     */
    public function init() : Response
    {
        $permission = (new Permission())
            ->setName('can everything')
            ->setTitle('может все')
        ;
        $permission2 = (new Permission())
            ->setName('can something else')
            ->setTitle('может кое-что еще')
        ;
        $this->em->persist($permission);
        $this->em->persist($permission2);
        $role = (new Role())
            ->setName('SUPER_ADMIN')
            ->setTitle('супер админ')
            ->addPermission($permission)
            ->addPermission($permission2)
        ;
        try {
            $this->em->persist($role);
            $this->em->flush();
            $message = 'Создана роль <b>' . $role->getName() . '</b>';
        } catch (\Exception $e) {
            $message = '<b>Создать роль не удалось.</b><br>' . $e->getMessage();
        }
        return new Response($message);
    }
}
