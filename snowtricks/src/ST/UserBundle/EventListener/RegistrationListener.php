<?php

	namespace ST\UserBundle\EventListener;

	use FOS\UserBundle\FOSUserEvents;
	use FOS\UserBundle\Event\FormEvent;
	use Symfony\Component\EventDispatcher\EventSubscriberInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;

	/**
	 * Listener responsible to add user ROLE_MEMBER role on register
	 */
	class RegistrationListener implements EventSubscriberInterface
	{
		private $router;

		public function __construct(UrlGeneratorInterface $router)
		{
			$this->router = $router;
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
			$user->addRole('ROLE_MEMBER');

			//Redirect on HomePage
			$url = $this->router->generate('st_snowtricks.homepage');
			$event->setResponse(new RedirectResponse($url));
		}
	}