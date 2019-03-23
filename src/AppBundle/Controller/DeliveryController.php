<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Services\CartServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("delivery")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class DeliveryController extends Controller
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
     * @Route("/{id}/verify", name="cart_verify")
     * @param Request $request
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyAction(Request $request, Cart $cart)
    {
        $deliveryDetails = $this
            ->getDoctrine()
            ->getRepository(\AppBundle\Entity\Delivery::class)
            ->find(['id' => $cart->getDelivery()->getId()]);

        $editForm = $this->createForm('AppBundle\Form\DeliveryType', $deliveryDetails);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $cart->setIsVerify(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($deliveryDetails);
            $em->persist($cart);
            $em->persist($this->cartService->createCart($this->getUser()));
            $em->flush();
            $this->addFlash('info', "The order is verified successfully!");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('cart/verify.html.twig', array(
            'cart' => $cart,
            'edit_form' => $editForm->createView(),
        ));
    }
}