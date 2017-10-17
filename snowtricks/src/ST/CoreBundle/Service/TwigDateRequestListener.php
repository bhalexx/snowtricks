<?php

	namespace ST\CoreBundle\Service;

	use Symfony\Component\HttpKernel\HttpKernelInterface;
	use Symfony\Component\HttpKernel\Event\GetResponseEvent;

	class TwigDateRequestListener
	{
		protected $twig;

		public function __construct(\Twig_Environment $twig)
		{
			$this->twig = $twig;
		}

		public function onKernelRequest(GetResponseEvent $event) {
			$this->twig
	        		->getExtension('Twig_Extension_Core')
	        		->setTimezone('Europe/Paris');
	    }
	}
