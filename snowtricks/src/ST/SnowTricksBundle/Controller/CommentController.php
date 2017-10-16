<?php

namespace ST\SnowTricksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ST\SnowTricksBundle\Entity\Trick;
use ST\SnowTricksBundle\Entity\Comment;
use ST\SnowTricksBundle\Form\CommentType;

class CommentController extends Controller
{
	public function listAction(Trick $trick)
	{
		$comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

		return $this->render('STSnowTricksBundle:Comment:list.html.twig', array(
			'trick' => $trick,
			'form' => $form->createView()
		));
	}

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