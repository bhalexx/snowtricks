<?php

namespace ST\SnowTricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ST\SnowTricksBundle\Entity\Family;
use ST\SnowTricksBundle\Form\FamilyType;

class FamilyController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $families = $em->getRepository('STSnowTricksBundle:Family')->findAll();

        return $this->render('STSnowTricksBundle:Family:index.html.twig', array(
        	'families' => $families
        ));
    }

    public function viewAction(Family $family)
    {
    	return $this->render('STSnowTricksBundle:Family:view.html.twig', array(
        	'family' => $family,
            'count' => count($family->getTricks())
        ));
    }

    /**
     * @Security("has_role('ROLE_MEMBER')")
     */
    public function addAction(Request $request)
    {
    	$family = new Family();

    	$form = $this->get('form.factory')->create(FamilyType::class, $family);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($family);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le groupe a bien été créé !');

            return $this->redirectToRoute('st_snowtricks.family_view', array('id' => $family->getId(), 'slug' => $family->getSlug()));
        }

    	return $this->render('STSnowTricksBundle:Family:add.html.twig', array(
    		'form' => $form->createView(),
    		'family' => $family
    	));
    }

    /**
     * @Security("has_role('ROLE_MEMBER')")
     */
    public function editAction(Request $request, Family $family)
    {
        $form = $this->get('form.factory')->create(FamilyType::class, $family);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le groupe a bien été modifié !');

            return $this->redirectToRoute('st_snowtricks.family_view', array('id' => $family->getId(), 'slug' => $family->getSlug()));
        }

        return $this->render('STSnowTricksBundle:Family:edit.html.twig', array(
            'form' => $form->createView(),
            'family' => $family
        ));
    }

    /**
     * @Security("has_role('ROLE_MEMBER')")
     */
    public function deleteAction(Request $request, Family $family)
    {
        $em = $this->getDoctrine()->getManager();

        //Create an empty form with only CSRF to secure family deletion
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($family);
            $em->flush();

            return $this->redirectToRoute('st_snowtricks.family_list');
        }

        return $this->render('STSnowTricksBundle:Family:delete.html.twig', array(
            'family' => $family,
            'form' => $form->createView()
        ));
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $families = $em->getRepository('STSnowTricksBundle:Family')->findAll();

        return $this->render('STSnowTricksBundle:Family:list.html.twig', array(
            'families' => $families,
            'count' => count($families)
        ));
    }
}
