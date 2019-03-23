<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Services\FileUploader;
/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Product $products */
        $products = $em->getRepository('AppBundle:Product')
            ->findBy([],['addedOn' => 'desc' ]);

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

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
