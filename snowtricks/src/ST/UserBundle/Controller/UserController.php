<?php

namespace ST\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use ST\UserBundle\User;

class UserController extends Controller
{
    public function removeProfilePictureAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$user = $this->getUser();
    	$user->removeProfilePicture();

		$em->persist($user);
		$em->flush();

    	return new JsonResponse(array('response' => 'deleted'));
    }
}
