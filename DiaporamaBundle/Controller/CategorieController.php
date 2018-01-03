<?php

namespace DiaporamaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DiaporamaBundle\Form\CategorieType;
use DiaporamaBundle\Entity\Categorie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategorieController extends Controller
{

    /**
     * Ajouter
     */
    public function ajouterAdminAction(Request $request)
    {
        $categorie = new Categorie;
        $form = $this->get('form.factory')->create(CategorieType::class, $categorie);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Catégorie enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_diaporama_categorie_manager'));
        }

        return $this->render('DiaporamaBundle:Admin/Categorie:ajouter.html.twig',
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
        $recherches = $rechercheService->setRecherche('diaporamacategorie_manager', array(
                'langue'
            )
        );

        $categories = $this->getDoctrine()
                           ->getRepository('DiaporamaBundle:Categorie')
                           ->getAllCategories($recherches['langue']);

        /* La liste des langues */
        $langues = $this->getDoctrine()->getRepository('GlobalBundle:Langue')->findAll();

        return $this->render('DiaporamaBundle:Admin/Categorie:manager.html.twig',array(
                'categories' => $categories,
                'recherches' => $recherches,
                'langues' => $langues
            )
        );
    }

    /**
     * Supprimer
     */
    public function supprimerAdminAction(Request $request, Categorie $categorie)
    {
        if(count($categorie->getGaleries()) != 0)  throw new NotFoundHttpException('Cette page n\'est pas disponible');

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        $request->getSession()->getFlashBag()->add('succes', 'Catégorie supprimée avec succès');
        return $this->redirect($this->generateUrl('admin_diaporama_categorie_manager'));
    }

    /**
     * Modifier
     */
    public function modifierAdminAction(Request $request, Categorie $categorie)
    {
        $form = $this->get('form.factory')->create(CategorieType::class, $categorie);

        /* Récéption du formulaire */
        if ($form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('succes', 'Catégorie enregistrée avec succès');
            return $this->redirect($this->generateUrl('admin_diaporama_categorie_manager'));
        }

        /* BreadCrumb */
        $breadcrumb = array(
            'Accueil' => $this->generateUrl('admin_page_index'),
            'Gestion des catégories' => $this->generateUrl('admin_diaporama_categorie_manager'),
            'Modifier une catégorie' => ''
        );

        return $this->render('DiaporamaBundle:Admin/Categorie:modifier.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
                'categorie' => $categorie,
                'form' => $form->createView()
            )
        );

    }

}
