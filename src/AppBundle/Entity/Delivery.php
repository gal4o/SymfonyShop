<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Delivery
 *
 * @ORM\Table(name="deliverys")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryRepository")
 */
class Delivery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="payment", type="string", length=255)
     */
    private $payment;

    /**
     * @var Cart
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Cart", mappedBy="delivery")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cart;

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     * @return Delivery
     */
    public function setCart($cart = null)
    {
        $this->cart = $cart;
        return $this;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Delivery
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Delivery
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set payment
     *
     * @param string $payment
     *
     * @return Delivery
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }
}

