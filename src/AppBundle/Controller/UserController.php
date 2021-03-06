<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Services\CartServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/show}", name="user_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        $user = $this->getUser();
        return $this->render('user/show.html.twig', array(
            'user' => $user,
        ));
    }

//    /**
//     * @Route("/edit", name="user_edit")
//     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
//     * @param Request $request
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
//     */
//    public function editAction(Request $request)
//    {
//        $user = $this->getDoctrine()
//            ->getRepository(User::class)
//            ->find($this->getUser()->getId());
//        $editForm = $this->createForm('AppBundle\Form\UserType');
//        $editForm->handleRequest($request);
//
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $password = $this->get('security.password_encoder')
//                ->encodePassword($user, $user->getPassword());
//            $user->setPassword($password);
//            $em = $this->getDoctrine()->getManager();
//            var_dump($user);exit();
//            $em->persist($user);
//            $em->flush();
//            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
//        }
//
//        return $this->render('user/edit.html.twig', array(
//            'user' => $user,
//            'edit_form' => $editForm->createView(),
//        ));
//    }

}
