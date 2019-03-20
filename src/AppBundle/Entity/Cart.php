<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="carts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
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
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;

    /**
     * @var Product[] $products
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="carts")
     * @ORM\JoinTable(name="carts_products",
     *     joinColumns={@ORM\JoinColumn(name="cart_id", referencedColumnName="orderId")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")})
     */
    private $products;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="carts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var bool
     * @ORM\Column(name="is_verify", type="boolean")
     */
    private $isVerify;

    /**
     * @var Delivery
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Delivery", inversedBy="cart", cascade={"persist"})
     * @ORM\JoinColumn(name="delivery", referencedColumnName="id")
     */
    private $delivery;

    /**
     * @return Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     * @return Cart
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
        return $this;
    }


    /**
     * @return bool
     */
    public function getIsVerify()
    {
        return $this->isVerify;
    }

    /**
     * @param bool $isVerify
     */
    public function setIsVerify($isVerify)
    {
        $this->isVerify = $isVerify;
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product $product
     * @return Cart
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Cart
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * Set total
     *
     * @param float $price
     *
     * @return Cart
     */
    public function setTotal($price)
    {
        $this->total = $price;
        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }
}

