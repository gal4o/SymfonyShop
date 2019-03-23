<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Services\CartServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Cart controller.
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @Route("cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/add/{id}", name="cart_add")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCart($id)
    {
        /** @var Cart $cart */
        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $this->getUser(),
                'isVerify' => false]);
        /** @var Product $product */
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);
        if ($product->getQuantity()== 0) {
            $this->addFlash('info', "Product is sold out!");
            return $this->redirectToRoute("homepage");
        }
        $cart->addProduct($product);
        $cart->setTotal($cart->getTotal()+$product->getPrice());
        $product->setQuantity($product->getQuantity()-1);
        $product->addCarts($cart);
        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        $em->persist($product);
        $em->flush();
        $this->addFlash('info', "Product is added successfully!");
        return $this->redirectToRoute("cart_show");

    }

    /**
     * @Route("/remove/{id}", name="cart_remove")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromCart($id)
    {
        /** @var Cart $cart */
        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user' => $this->getUser(),
                'isVerify' => false]);
        /** @var Product $product */
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $cart->getProducts()->removeElement($product);
        $cart->setTotal($cart->getTotal()-$product->getPrice());
        $product->getCarts()->removeElement($cart);
        $product->setQuantity($product->getQuantity()+1);
        $em->persist($product);
        $em->persist($cart);
        $em->flush();
        $this->addFlash('info', "Product is removed successfully!");
        return $this->redirectToRoute("cart_show");

    }

    /**
     * Lists all cart entities.
     *
     * @Route("/", name="cart_index")
     */
    public function indexAction()
    {
        /** @var Cart $carts */
        $carts = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('cart/index.html.twig', array(
            'carts' => $carts,
        ));
    }

    /**
     * Finds and displays a cart entity.
     *
     * @Route("/show", name="cart_show")
     */
    public function showAction()
    {
        $cartId = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findOneBy(['user'=>$this->getUser(),
                'isVerify'=>false])
            ->getId();
        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($cartId);
        return $this->render('cart/show.html.twig', array(
            'cart' => $cart,
        ));
    }

    /**
     * @Route("/showOne/{id}", name="cart_show_one")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneAction($id)
    {
        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($id);
        return $this->render('cart/showOne.html.twig', array(
            'cart' => $cart,
        ));
    }


}
