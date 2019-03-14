<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserOrder
 *
 * @ORM\Table(name="user_orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserOrderRepository")
 */
class UserOrder
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=14, scale=2)
     */
    private $total;

    /**
     * @var Product[] $products
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="orders")
     */
    private $products;

//    /**
//     * @var User
//     *
//     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="orders")
//     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
//     */
//    private $user;

    public function __construct()
    {
        $this->date = new \DateTime("now");
        $this->products = new ArrayCollection();
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     * @return UserOrder
     */
    public function setProducts($products)
    {
        $this->products[] = $products;
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
     * @return UserOrder
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return UserOrder
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set total
     *
     * @param string $total
     *
     * @return UserOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }
}

