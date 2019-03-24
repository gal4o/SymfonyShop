<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Services\CartServiceInterface;
use AppBundle\Services\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @package AppBundle\Controller
 * @Route("admin")
 */
class AdminController extends Controller
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
     * @Route("/users", name="users_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexUsersAction()
    {
        if ($this->getUser()->isAdmin())
        {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();
            return $this->render('admin/users/index.html.twig', array('users' => $users));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/user/adm/{id}", name="user_make_admin")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function makeUserAdmin($id) {
        if ($this->getUser()->isAdmin()) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id);
            $role = $this->getDoctrine()
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_ADMIN']);
            $em = $this->getDoctrine()
                ->getManager();
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                $user->setRole($role);
                $em->persist($user);
                $em->flush();
                $this->addFlash('info', "You make this user admin!");
                return $this->redirectToRoute('admin_user_show', ['id' => $id]);
            } else {
                $this->addFlash('info', "Oops this user is admin!");
                return $this->redirectToRoute('admin_user_show', ['id' => $id]);
            }
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    } //take role

    /**
     * @Route("/user/{id}", name="admin_user_show")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserAction(User $user)
    {
        if ($this->getUser()->isAdmin()) {
            return $this->render('admin/users/show.html.twig', array(
                'user' => $user,
            ));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/products", name="products_list")
     */
    public function indexProductsAction()
    {
        if ($this->getUser()->isAdmin()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Product $products */
            $products = $em->getRepository('AppBundle:Product')
                ->findBy([], ['addedOn' => 'desc']);

            return $this->render('admin/products/index.html.twig', array(
                'products' => $products,
            ));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/product/new", name="admin_product_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newProductAction(Request $request)
    {
        if ($this->getUser()->isAdmin()) {
            $fileUploader = new FileUploader('images_directory');
            $product = new Product();
            $form = $this->createForm('AppBundle\Form\ProductType', $product);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $product->getImage();
                $fileName = $fileUploader->upload($file);

                $product->setImage($fileName);
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                $this->addFlash('info', "Product is added successfully!");
                return $this->redirectToRoute('admin_product_show', array('id' => $product->getId()));
            }

            return $this->render('admin/products/new.html.twig', array(
                'product' => $product,
                'form' => $form->createView(),
            ));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/product/{id}", name="admin_product_show")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProductAction(Product $product)
    {
        if ($this->getUser()->isAdmin()) {
            return $this->render('admin/products/show.html.twig', array(
                'product' => $product,
            ));
        }
    $this->addFlash('info', "Your do not have needed authorization!");
    return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/product/edit/{id}", name="admin_product_edit")
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProductAction(Request $request, Product $product)
    {
        if ($this->getUser()->isAdmin()) {
            $fileUploader = new FileUploader('images_directory');
            $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                /** @var UploadedFile $file */
                $file = $product->getImage();
                $fileName = $fileUploader->upload($file);
                $product->setImage($fileName);
                $em = $this->getDoctrine()
                    ->getManager();
                $em->persist($product);
                $em->flush();
                $this->addFlash('info', "Product is edited successfully!");
                return $this->redirectToRoute('admin_product_show', array('id' => $product->getId()));
            }

            return $this->render('admin/products/edit.html.twig', array(
                'product' => $product,
                'edit_form' => $editForm->createView(),
            ));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/carts", name="carts_list")
     */
    public function indexCartsAction()
    {
        if ($this->getUser()->isAdmin()) {
            /** @var Cart $carts */
            $carts = $this->getDoctrine()
                ->getRepository(Cart::class)
                ->findAll();
            return $this->render('admin/carts/index.html.twig', array(
                'carts' => $carts,
            ));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/cart/showOne/{id}", name="admin_cart_show")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSomeCartAction($id)
    {
        if ($this->getUser()->isAdmin()) {
            $cart = $this->getDoctrine()
                ->getRepository(Cart::class)
                ->find($id);
            return $this->render('admin/carts/show.html.twig', array(
                'cart' => $cart,
            ));
        }
        $this->addFlash('info', "Your do not have needed authorization!");
        return $this->redirectToRoute('homepage');
    }

}
