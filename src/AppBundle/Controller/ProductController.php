<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/{id}", name="product_show")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProductAction(Product $product)
    {
            return $this->render('product/show.html.twig', array(
                'product' => $product,
            ));
    }

}
