<?php

	namespace ST\UserBundle\EventListener;

	use FOS\UserBundle\FOSUserEvents;
	use FOS\UserBundle\Event\FormEvent;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Doctrine\ORM\EntityManager;

	/**
	 * Listener responsible to add user ROLE_MEMBER role on register
	 */
	class RegistrationListener implements EventSubscriberInterface
	{
		private $em;
		private $router;

		public function __construct(UrlGeneratorInterface $router, EntityManager $em)
		{
			$this->router = $router;
			$this->em = $em;
		}

		public static function getSubscribedEvents()
		{
			return array(
				FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
			);
		}

		/**
		 * Add default role (ROLE_MEMBER) to user on registration success
		 * @param  FormEvent $event
		 */
		public function onRegistrationSuccess(FormEvent $event)
		{
			$user = $event->getForm()->getData();
			$group = $this->em->getRepository('STUserBundle:Group')->findOneByName('member');
			$user->addGroup($group);

			//Redirect on HomePage
			$url = $this->router->generate('st_snowtricks.homepage');
			$event->setResponse(new RedirectResponse($url));
		}
	}