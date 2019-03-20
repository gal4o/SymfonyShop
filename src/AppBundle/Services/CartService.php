<?php

namespace AppBundle\Services;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Delivery;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CartService implements CartServiceInterface
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function createCart(User $user)
    {
        $cart = new Cart();
        $cart->setUser($user);
        $cart->setTotal('0');
        $cart->setIsVerify(false);
        $cart->setDelivery(new Delivery());
        return $cart;
    }

}