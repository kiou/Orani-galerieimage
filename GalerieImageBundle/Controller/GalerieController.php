<?php

namespace GalerieImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GalerieImageBundle\Form\GalerieType;
use GalerieImageBundle\Entity\Galerie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalerieController extends Controller
{
    /**
     * Ajouter
     */
    public function ajouterAdminAction(Request $request)
    {
        $galerie = new Galerie;
        $form = $this->get('form.factory')->create(GalerieType::class, $galerie);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $galerie->uploadImage();
            $galerie->getReferencement()->uploadOgimage();

            $em = $this->getDoctrine()->getManager();
            $em->persist($galerie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Galerie d\'image enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_galerieimage_manager'));
        }

        return $this->render('GalerieImageBundle:Admin:ajouter.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Gestion
     */
    public function managerAdminAction(Request $request)
    {
        /* Services */
        $rechercheService = $this->get('recherche.service');
        $recherches = $rechercheService->setRecherche('galerieimage_manager', array(
                'recherche',
            )
        );

        /* La liste des galeries d'images */
        $galeries = $this->getDoctrine()
                         ->getRepository('GalerieImageBundle:Galerie')
                         ->getAllGaleries($recherches['recherche'], null, true);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $galeries, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            50/*limit per page*/
        );

        return $this->render('GalerieImageBundle:Admin:manager.html.twig',array(
                'pagination' => $pagination,
                'recherches' => $recherches
            )
        );
    }

    /**
     * Publication
     */
    public function publierAdminAction(Request $request, Galerie $galerie){

        if($request->isXmlHttpRequest()){
            $state = $galerie->reverseState();
            $galerie->setIsActive($state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($galerie);
            $em->flush();

            return new JsonResponse(array('state' => $state));
        }

    }

    /**
     * Supprimer
     */
    public function supprimerAdminAction(Request $request, Galerie $galerie)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($galerie);
        $em->flush();

        $request->getSession()->getFlashBag()->add('succes', 'Galerie d\'image supprimée avec succès');
        return $this->redirect($this->generateUrl('admin_galerieimage_manager'));
    }

    /**
     * Poid
     */
    public function poidAdminAction(Request $request, Galerie $galerie, $poid){

        if($request->isXmlHttpRequest()){
            $galerie->setPoid($poid);

            $em = $this->getDoctrine()->getManager();
            $em->persist($galerie);
            $em->flush();

            return new JsonResponse(array('status' => 'succes'));
        }

    }

    /**
     * Modifier
     */
    public function modifierAdminAction(Request $request, Galerie $galerie)
    {
        $form = $this->get('form.factory')->create(GalerieType::class, $galerie);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $galerie->uploadImage();
            $galerie->getReferencement()->uploadOgimage();

            $em = $this->getDoctrine()->getManager();
            $em->persist($galerie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Galerie d\'image enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_galerieimage_manager'));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des galeries d\'images' => $this->generateUrl('admin_galerieimage_manager'),
            'Modifier une galerie d\'image' => ''
        );

        return $this->render('GalerieImageBundle:Admin:modifier.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
                'galerie' => $galerie,
                'form' => $form->createView()
            )
        );

    }

    /**
     * Supprimer l'image
     */
    public function AdminSupprimerImageAction(Request $request, Galerie $galerie)
    {
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $galerie->setImage(null);
            $em->flush();

            return new JsonResponse(array('state' => 'ok'));
        }
    }

    /**
     * Manager client
     */
    public function managerClientAction(Request $request)
    {

        /* Services */
        $rechercheService = $this->get('recherche.service');
        $recherches = $rechercheService->setRecherche('galeries-images', array(
                'categorie',
            )
        );

        /* La liste des galeries d'images */
        $galeries = $this->getDoctrine()
                         ->getRepository('GalerieImageBundle:Galerie')
                         ->getAllGaleries(null, $recherches['categorie'], false);

        /* La liste des catégories */
        $categories = $this->getDoctrine()
                           ->getRepository('GalerieImageBundle:Categorie')
                           ->findBy([],['id' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $galeries, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            16 /*limit per page*/
        );

        return $this->render('GalerieImageBundle:Client:manager.html.twig', array(
                'pagination' => $pagination,
                'categories' => $categories,
                'recherches' => $recherches
            )
        );
    }

    /*
     * View
     */
    public function viewClientAction($id)
    {
        /* galerie d'image en cours */
        $galerie = $this->getDoctrine()
                        ->getRepository('GalerieImageBundle:Galerie')
                        ->getCurrentGalerie($id);

        if(is_null($galerie)) throw new NotFoundHttpException('Cette page n\'est pas disponible');

        /* BreadCrumb */
        $breadcrumb = array(
            'Les galeries d\'images' => $this->generateUrl('client_galerieimage_manager'),
            $galerie->getTitre() => ''
        );

        return $this->render( 'GalerieImageBundle:Client:view.html.twig',array(
                'galerie' => $galerie,
                'breadcrumb' => $breadcrumb
            )
        );
    }

}
