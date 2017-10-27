<?php

namespace ST\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ST\UserBundle\User;

class UserController extends Controller
{
	/**
	 * Removes user's profile picture
	 * @return JsonResponse
	 */
    public function removeProfilePictureAction()
    {
    	//This request is only callable by Ajax
		if ($request->isXmlHttpRequest()) {
	    	$em = $this->getDoctrine()->getManager();

	    	$user = $this->getUser();
	    	$user->removeProfilePicture();

			$em->persist($user);
			$em->flush();

	    	return new JsonResponse(array('response' => 'deleted'));
	    }

	    //If request not called by Ajax
        throw new NotFoundHttpException();
    }
}
