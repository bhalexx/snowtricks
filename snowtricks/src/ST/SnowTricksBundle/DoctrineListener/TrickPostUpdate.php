<?php

	namespace ST\SnowTricksBundle\DoctrineListener;

	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
	use ST\SnowTricksBundle\Entity\Trick;

	class TrickPostUpdate
	{
		public function postUpdate(LifecycleEventArgs $args)
		{
			//Get entity
			$entity = $args->getObject();

			if (!$entity instanceof Trick) {
				return;
			}

			//Get entity manager
			$em = $args->getObjectManager();

			//Get trick's actual videos
			$trickVideos = new ArrayCollection($em->getRepository('STSnowTricksBundle:Video')->findBy(array(
				'trick' => $entity
			)));

			//Get trick's new videos
			$newVideos = $entity->getVideos();
			
			foreach ($trickVideos as $video) {
				//Remove video if no more in new videos
				if (!$newVideos->contains($video)) {
					$em->remove($video);
				}
        	}

        	//Update db
        	$em->flush();			
		}
	}