<?php

namespace GalerieImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GalerieImageBundle\Form\ImageType;
use GalerieImageBundle\Entity\Image;
use GalerieImageBundle\Entity\Galerie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImageController extends Controller
{

    /**
     * Ajouter
     */
    public function ajouterAdminAction(Request $request, Galerie $galerie)
    {
        $image = new Image;
        $form = $this->get('form.factory')->create(ImageType::class, $image);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $image->uploadImage();
            $image->setGalerie($galerie);

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Image enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_galerieimage_image_manager',array('galerie' => $galerie->getId())));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des galeries' => $this->generateUrl('admin_galerieimage_manager'),
            'Gestion des images' => $this->generateUrl('admin_galerieimage_image_manager', array('galerie' => $galerie->getId())),
            'Ajouter une image' => ''
        );

        return $this->render('GalerieImageBundle:Admin/Image:ajouter.html.twig',
            array(
                'form' => $form->createView(),
                'galerie' => $galerie,
                'breadcrumb' => $breadcrumb
            )
        );
    }

    /**
     * Gestion
     */
    public function managerAdminAction(Galerie $galerie)
    {
        /* La liste des images */
        $images = $this->getDoctrine()
                       ->getRepository('GalerieImageBundle:Image')
                       ->findBy(array('galerie' => $galerie),array('id' => 'DESC'));

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des galeries' => $this->generateUrl('admin_galerieimage_manager'),
            'Gestion des images' => ''
        );

        return $this->render( 'GalerieImageBundle:Admin/Image:manager.html.twig', array(
                'images' => $images,
                'galerie' => $galerie,
                'breadcrumb' => $breadcrumb
            )
        );

    }

    /**
     * Modifier
     */
    public function modifierAdminAction(Request $request, Galerie $galerie, Image $image)
    {
        $form = $this->get('form.factory')->create(ImageType::class, $image);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $image->uploadImage();

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Image enregistré avec succès');
            return $this->redirect($this->generateUrl('admin_galerieimage_image_manager',array('galerie' => $galerie->getId())));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des galeries' => $this->generateUrl('admin_galerieimage_manager'),
            'Gestion des images' => $this->generateUrl('admin_galerieimage_image_manager', array('galerie' => $galerie->getId())),
            'Modifier une image' => ''
        );

        return $this->render('GalerieImageBundle:Admin/Image:modifier.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
                'galerie' => $galerie,
                'image' => $image,
                'form' => $form->createView()
            )
        );

    }

    /**
     * Publication
     */
    public function publierAdminAction(Request $request, Galerie $galerie, Image $image){

        if($request->isXmlHttpRequest()){
            $state = $image->reverseState();
            $image->setIsActive($state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return new JsonResponse(array('state' => $state));
        }

    }

    /**
     * Supprimer
     */
    public function supprimerAdminAction(Request $request, Galerie $galerie, Image $image)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        $request->getSession()->getFlashBag()->add('succes', 'Image supprimée avec succès');
        return $this->redirect($this->generateUrl('admin_galerieimage_image_manager', array('galerie' => $galerie->getId())));
    }

    /**
     * Poid
     */
    public function poidAdminAction(Request $request, Galerie $galerie, Image $image, $poid){

        if($request->isXmlHttpRequest()){
            $image->setPoid($poid);

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return new JsonResponse(array('status' => 'succes'));
        }

    }

}
