<?php

namespace MiniShopBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MiniShopBundle\Entity\Photo;
use MiniShopBundle\Form\Type\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use MiniShopBundle\Entity\Product;

class ProductController extends Controller
{
    public function indexAction($page = 1, $limit = 10)
    {
        $em    = $this->getDoctrine()->getManager();
        $dql   = "SELECT p FROM MiniShopBundle:Product p";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );

        return $this->render('MiniShopBundle:Product:index.html.twig', array(
            'title' => 'Products',
            'pagination' => $pagination
        ));
    }

    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = new Product();
        $product->addPhoto(new Photo());

        $form = $this->createForm(new ProductType(), $product, array(
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if( !$form->isValid() ){
            return $this->render('MiniShopBundle:Product:create.html.twig',array(
                    'title' =>  'Create product',
                    'form' => $form->createView()
                )
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Successfully added new Product');

        return $this->redirectToRoute('mini_shop_product_update', array(
            'id' => $product->getId()
        ));

    }

    public function showAction($id){

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('MiniShopBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'Product could not be found by id '.$id
            );
        }

        return $this->render('MiniShopBundle:Product:show.html.twig',array(
                'title' => sprintf("Product '%s' details", $product->getTitle()),
                'product' => $product
            )
        );
    }

    public function updateAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('MiniShopBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'Product could not be found by id '.$id
            );
        }

        $originalPhotos = new ArrayCollection();
        foreach ($product->getPhotos() as $photo) {
            $originalPhotos->add($photo);
        }

        $form = $this->createForm(new ProductType(), $product, array(
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if( !$form->isValid() ){
            return $this->render('MiniShopBundle:Product:update.html.twig',array(
                    'title' => sprintf('Edit product "%s"', $product->getTitle()) ,
                    'form' => $form->createView()
                )
            );
        }

        foreach ($originalPhotos as $photo) {
            if (false === $product->getPhotos()->contains($photo)) {
                $em->remove($photo);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Successfully updated Product');

        return $this->redirectToRoute('mini_shop_product_update', array(
            'id' => $product->getId()
        ));
    }

    public function removeAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository('MiniShopBundle:Product')->find($id);

        if (!$product) {
            return new JsonResponse(array('status' => 'false'));
        }

        foreach($product->getPhotos() as $photo){
            $em->remove($photo);
        }

        $em->remove($product);
        $em->flush();

        return new JsonResponse(array('status' => 'ok'));
    }

    public function buyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('MiniShopBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'Product could not be found by id '.$id
            );
        }

        return $this->render('MiniShopBundle:Product:buy.html.twig',array(
                'title' => sprintf('You buy product "%s"', $product->getTitle()) ,
            )
        );
    }

}
