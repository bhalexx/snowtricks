<?php

namespace ST\SnowTricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ST\SnowTricksBundle\Entity\Trick;
use ST\SnowTricksBundle\Entity\Comment;
use ST\SnowTricksBundle\Form\CommentType;

class CommentController extends Controller
{
	/**
	 * @ParamConverter("trick", options={"mapping": {"trick": "id"}})
	 */
	public function listAction(Request $request, Trick $trick, $page)
	{
		//This request is only callable by Ajax
		if ($request->isXmlHttpRequest())
        {
			$nbPerPage = 10;
			$nbPages;
            
            //Get comments
            $em = $this->getDoctrine()->getManager();
            $comments = $em->getRepository('STSnowTricksBundle:Comment')->getPaginatedComments($page, $nbPerPage, $trick);

            //Return JSON response
            $view = $this->renderView('STSnowTricksBundle:Comment:list.html.twig', array(
            	'comments' => $comments
            ));

            $nbPages = ceil(count($comments) / $nbPerPage);

            return new JsonResponse(array(
            	'lastPage' => $nbPages - 1 <= (int)$page, 
            	'view' => $view
        	));
        }

        //If request not called by Ajax
        throw new NotFoundHttpException();
	}

    /**
     * @Security("has_role('ROLE_MEMBER')")
     */
	public function addAction(Request $request, Trick $trick)
	{
		$comment = new Comment();
		$comment->setTrick($trick);
		$form = $this->get('form.factory')->create(CommentType::class, $comment);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->setAuthor($this->getUser());
            $em->persist($comment);
            $em->flush();

            //Unset comment & form to reset form
            unset($comment);
            unset($form);
            $comment = new Comment();
            $comment->setTrick($trick);
            $form = $this->get('form.factory')->create(CommentType::class, $comment);

            $request->getSession()->getFlashBag()->add('success', 'Merci pour votre commentaire !');
        }

		return $this->render('STSnowTricksBundle:Comment:add.html.twig', array(
			'trick' => $trick,
			'form' => $form->createView()
		));
	}
}