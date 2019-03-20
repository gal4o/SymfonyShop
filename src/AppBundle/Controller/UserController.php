<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Services\CartServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * @var CartServiceInterface
     */
    private $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User[] $users */
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $userRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
            $user->setRole($userRole);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $em->persist($this->cartService->createCart($user));
            $em->flush();
            $this->addFlash('info', "Your registry is successfully!");
            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(User $user)
    {

        return $this->render('user/show.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

}
