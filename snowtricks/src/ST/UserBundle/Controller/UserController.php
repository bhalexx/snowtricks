<?php

namespace ST\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ST\UserBundle\Form\UserType;
use ST\UserBundle\Entity\User;

class UserController extends Controller
{
	/**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('STUserBundle:User');

		$admin = $repository->findByGroup('admin');
		$members = $repository->findByGroup('member');

        return $this->render('STUserBundle:User:index.html.twig', array(
        	'admin' => $admin,
        	'countAdmin' => count($admin),
        	'members' => $members,
        	'countMembers' => count($members),
        ));
	}

	/**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function addAction(Request $request)
	{
		$user = new User();
		$form = $this->get('form.factory')->create(UserType::class, $user);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');

            $user->setPlainPassword($user->getUsername());
            
            $userManager->updateUser($user);

            $request->getSession()->getFlashBag()->add('success', "L'utilisateur a bien été créé !");

            return $this->redirectToRoute('st_user.users');
        }

		return $this->render('STUserBundle:User:add.html.twig', array(
			'form' => $form->createView(),
			'user' => $user
		));
	}

	/**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function editAction(Request $request, User $user)
	{
		$form = $this->get('form.factory')->create(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');

            $userManager->updateUser($user);

            $request->getSession()->getFlashBag()->add('success', "L'utilisateur a bien été modifié !");

            return $this->redirectToRoute('st_user.users');
        }

        return $this->render('STUserBundle:User:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
	}

	/**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function deleteAction(Request $request, User $user)
	{
		$userManager = $this->container->get('fos_user.user_manager');
    
        //Create an empty form with only CSRF to secure user deletion
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        	$userManager->deleteUser($user);

        	$request->getSession()->getFlashBag()->add('success', "L'utilisateur a bien été supprimé !");

            return $this->redirectToRoute('st_user.users');
        }

        return $this->render('STUserBundle:User:delete.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
	}

	/**
     * @Security("has_role('ROLE_MEMBER')")
     */
	public function viewAction(User $user)
	{
        $em = $this->getDoctrine()->getManager();

        $nbComments = count($em->getRepository('STSnowTricksBundle:Comment')->findByAuthor($user));
        $nbTricks = count($em->getRepository('STSnowTricksBundle:Trick')->findByAuthor($user));

		return $this->render('STUserBundle:User:view.html.twig', array(
			'user' => $user,
            'nbComments' => $nbComments,
            'nbTricks' => $nbTricks
		));
	}

	/**
	 * Removes user's profile picture
	 * @return JsonResponse
	 */
    public function removeProfilePictureAction(Request $request, User $user)
    {
    	//This request is only callable by Ajax
		if ($request->isXmlHttpRequest()) {
	    	$em = $this->getDoctrine()->getManager();

	    	$user->removeProfilePicture();

			$em->persist($user);
			$em->flush();

	    	return new JsonResponse(array('response' => 'deleted'));
	    }

	    //If request not called by Ajax
        throw new NotFoundHttpException();
    }

    /**
	 * Delete user's account
	 */
    public function deleteMyAccountAction(Request $request)
	{
		$userManager = $this->container->get('fos_user.user_manager');
    
        //Create an empty form with only CSRF to secure user deletion
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        	$userManager->deleteUser($this->getUser());

        	$request->getSession()->getFlashBag()->add('success', "Votre compte est supprimé. Vous allez nous manquer !");

            return $this->redirectToRoute('st_snowtricks.homepage');
        }

        return $this->render('STUserBundle:User:delete_my_account.html.twig', array(
            'user' => $this->getUser(),
            'form' => $form->createView()
        ));
    }
}
