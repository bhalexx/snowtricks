<?php

namespace ST\SnowTricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ST\SnowTricksBundle\Entity\Family;
use ST\SnowTricksBundle\Entity\Trick;
use ST\SnowTricksBundle\Entity\Comment;
use ST\SnowTricksBundle\Entity\Picture;
use ST\SnowTricksBundle\Form\TrickType;
use ST\SnowTricksBundle\Form\CommentType;

class TrickController extends Controller
{
	public function viewAction(Trick $trick)
	{
        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

		return $this->render('STSnowTricksBundle:Trick:view.html.twig', array(
			'trick' => $trick,
            'form' => $form->createView()
		));
	}
    
	/**
	 * @ParamConverter("family", options={"mapping": {"family": "slug"}})
     * @Security("has_role('ROLE_MEMBER')") * 
	 */
	public function addAction(Request $request, Family $family = null)
	{
		$trick = new Trick();

		$trick->setFamily($family);
		$trick->setAuthor($this->getUser());

		$form = $this->get('form.factory')->create(TrickType::class, $trick);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La figure a bien été créée !');

            return $this->redirectToRoute('st_snowtricks.trick_view', array('id' => $trick->getId(), 'slug' => $trick->getSlug()));
        }

    	return $this->render('STSnowTricksBundle:Trick:add.html.twig', array(
    		'form' => $form->createView(),
    		'trick' => $trick
    	));
	}

    /**
     * @Security("has_role('ROLE_MEMBER')")
     */
	public function editAction(Request $request, Trick $trick)
    {
        $form = $this->get('form.factory')->create(TrickType::class, $trick);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //Set trick's date update
            $trick->setDateUpdate(new \DateTime());
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La figure a bien été modifiée !');

            return $this->redirectToRoute('st_snowtricks.trick_view', array('id' => $trick->getId(), 'slug' => $trick->getSlug()));
        }

        return $this->render('STSnowTricksBundle:Trick:edit.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick
        ));
    }

    /**
     * @Security("has_role('ROLE_MEMBER')")
     */
	public function deleteAction(Request $request, Trick $trick)
	{
		$em = $this->getDoctrine()->getManager();
		$family = $trick->getFamily();

        //Create an empty form with only CSRF to secure trick deletion
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($trick);
            $em->flush();

            return $this->redirectToRoute('st_snowtricks.family_view', array(
            	'id' => $family->getId(),
            	'slug' => $family->getSlug()
            ));
        }

        return $this->render('STSnowTricksBundle:Trick:delete.html.twig', array(
            'trick' => $trick,
            'form' => $form->createView()
        ));
	}

    /**
     * @ParamConverter("picture", options={"mapping": {"picture_id": "id"}})
     * @ParamConverter("trick", options={"mapping": {"trick_id": "id"}})
     * @Security("has_role('ROLE_MEMBER')")
     */
    public function removePictureFromTrickAction(Picture $picture, Trick $trick)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($picture);

        $em->persist($trick);
        $em->flush();

        return new JsonResponse(array('response' => 'deleted'));
    }
}