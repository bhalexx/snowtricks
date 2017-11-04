<?php

	namespace ST\CoreBundle\Command;

	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Yaml\Yaml;
	use ST\SnowTricksBundle\Entity\Family;
	use ST\SnowTricksBundle\Entity\Trick;
	use ST\SnowTricksBundle\Entity\Picture;
	use ST\SnowTricksBundle\Entity\Video;
	use ST\SnowTricksBundle\Entity\Comment;
	use ST\UserBundle\Entity\Group;
	use ST\UserBundle\Entity\User;

	class LoadFixturesCommand extends ContainerAwareCommand
	{
		private $em;
		private $picturesFolder = __DIR__.'/../../../../web/tricks';
		private $profilePicturesFolder = __DIR__.'/../../../../web/users';

		protected function configure()
		{
			$this
				// Command's name (after php bin/console)
				->setName('snowtricks:fixtures:load')
				// Short description shown while running "php bin/console list"
				->setDescription('Purges database and loads all fixtures to database.')
				// Full command description shown when running the command with "--help" option
		        ->setHelp('This command purges database and allows you to load fixtures in database so that your website already contains families, tricks, users with different roles...')
    		;
		}

		protected function execute(InputInterface $input, OutputInterface $output)
		{
			$this->em = $this->getContainer()->get('doctrine')->getManager();

			$application = $this->getApplication();
        	$application->setAutoExit(false);

   //      	// Drop database
			// $output->writeln([
	  //           '===================================================',
	  //           'Dropping DataBase',
	  //           '===================================================',
	  //           '',
	  //       ]);

			$options = array('command' => 'doctrine:database:drop',"--force" => true);
        	$application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        	// Create database
        	$output->writeln([
	            '',
	            '===================================================',
	            'Creating DataBase',
	            '===================================================',
	            '',
	        ]);

	        $options = array('command' => 'doctrine:database:create',"--if-not-exists" => true);
	        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

	        //Update schema
	        $output->writeln([
	            '',
	            '===================================================',
	            'Updating Schema',
	            '===================================================',
	            '',
	        ]);

	        //Create the Schema
	        $options = array('command' => 'doctrine:schema:update',"--force" => true);
	        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));


	        //Clear pictures to purge folder
	        $output->writeln([
	            '',
	            '===================================================',
	            'Purging pictures folder',
	            '===================================================',
	            '',
	        ]);

	        $this->clearPictures($output);

	        //Load fixtures
	        $output->writeln([
	            '',
	            '===================================================',
	            'Load Fixtures',
	            '===================================================',
	            '',
	        ]);

	        //Load & create groups and users from YAML file
	        $this->loadGroupsAndUsers($output);
	        //Load & create families and tricks from YAML file
  			$this->loadFamiliesAndTricks($output);

  			//Feedback end
	        $output->writeln([
	            '',
	            '===================================================',
	            '',
	            'Everything was successfully loaded. Your website is ready. Enjoy! ;)',
	            '',
	        ]);
		}

		/**
		 * Loads groups and users
		 * @param  OutputInterface $output
		 */
		public function loadGroupsAndUsers(OutputInterface $output)
		{
			//Load FOSUser user manipulator
			$manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
			$manager = $this->getContainer()->get('fos_user.user_manager');

			//Feedback begin
			$output->writeln([
	            '',
				'Create Users',
	            '===================================================',
				'',
			]);

			//Parse userGroups YAML file
			$userGroups = Yaml::parse(file_get_contents(__DIR__.'/../DataFixtures/UserGroupData.yml'));
			$usersCount = 0;

			//Browse userGroups
			foreach ($userGroups['Groups'] as $groupData) {
				$group = new Group();
				$group->setName($groupData['name']);
				$group->setLabel($groupData['label']);

				foreach ($groupData['roles'] as $role) {
					$group->addRole($role);
				}

				foreach($groupData['users'] as $userData) {
					$user = new User();
					$user->setFirstname($userData['firstname']);
					$user->setLastname($userData['lastname']);
					$user->setUsername($userData['username']);
					$user->setPlainPassword($userData['password']);
					$user->setEmail($userData['email']);
					$user->setEnabled(true);

					$folder = __DIR__.'/../DataFixtures/Pictures/Users/';
					$handle = opendir($folder);

					//Upload profile picture
					while(($file = readdir($handle)) !== false) {
		                if ($file != '.' && $file != '..') {
		                	if (preg_replace('/\\.[^.\\s]{3,4}$/', '', $file) === strtolower($userData['username'])) {
		                		$user->setProfilePicturePath($file);
		                		//Copy file in folder
		        				copy($folder.'/'.$file, $this->profilePicturesFolder.'/'.$file);                		
		                	}
		                }
		            }	

					$manager->createUser($user);
					$this->em->persist($user);
					$usersCount++;
					
					$group->addUser($user);
				}

				$this->em->persist($group);
			}

			$this->em->flush();

			//Feedback end
			$output->writeln([
	            '',				
				$usersCount.' users successfully created',
				'',
			]);
		}

		/**
		 * Loads families and tricks with pictures
		 * @param  OutputInterface $output
		 */
		public function loadFamiliesAndTricks(OutputInterface $output)
		{
			//Get users from database
			$users = $this->em->getRepository('STUserBundle:User')->findAll();

			//Feedback begin
			$output->writeln([
	            '',
				'Create Tricks',
	            '===================================================',
				'',
			]);
			 
			//Parse YAML file
			$families = Yaml::parse(file_get_contents(__DIR__.'/../DataFixtures/FamilyTrickData.yml'));

			$familiesCount = count($families['Families']);
			$tricksCount = 0;
			$rootFolder = __DIR__.'/../DataFixtures/Pictures';

			//Browse families
			foreach($families['Families'] as $familyData) {
				$family = new Family();

				foreach ($familyData as $key => $value) {
					if ($key !== 'tricks') {
						$method = 'set'.ucfirst($key);
						$family->$method($value);
					}
				}
				//Persist family
				$this->em->persist($family);

				//Browse each family's tricks
				foreach ($familyData['tricks'] as $trickData) {
					$trick = new Trick();
					//Get trick's pictures folder path
					$folder = $rootFolder.'/'.$family->getName().'/'.$trickData['name'];
					$handle = opendir($folder);

					foreach($trickData as $key => $value) {
						if ($key !== 'comments' && $key !== 'videos') {
							$method = 'set'.ucfirst($key);
							$trick->$method($value);
						}
						$trick->setAuthor($users[rand(0, (count($users) - 1))]);
						$trick->setFamily($family);
						
						//Upload trick's pictures
						while(($file = readdir($handle)) !== false) {
			                if ($file != '.' && $file != '..'){
			                	$picture = $this->uploadPicture($file, $folder);
			                    $trick->addPicture($picture);
			                }
			            }
					}
		            //Add comments to trick
		            foreach($trickData['comments'] as $commentData) {
		            	$comment = new Comment();
		            	$comment->setAuthor($users[rand(0, (count($users)-1))]);
		            	$comment->setContent($commentData);

		            	$trick->addComment($comment);
		            }

		            //Add videos to trick
		            foreach($trickData['videos'] as $videoData) {
		            	$video = new Video();
		            	$video->setPath($videoData);

		            	$trick->addVideo($video);
		            }

					//Persist trick
					$this->em->persist($trick);
					$tricksCount++;
				}
			}

			$this->em->flush();

			//Feedback end
			$output->writeln([
	            '',				
				$familiesCount.' families and '.$tricksCount.' tricks successfully created',
				'',
			]);
		}

		/**
		 * Uploads picture in folder
		 * @param File $file
		 * @param string $folder
		 */
		private function uploadPicture($file, $folder){
			//Get file extension
	        $ext = substr(strrchr($file,'.'), 1);
	        //Create random file name
	        $newName = sha1(uniqid(mt_rand(), true)).'.'.$ext;
	        
	        //Create Picture entity
	        $picture = new Picture();
	        //Set path with new filename
	        $picture->setPath($newName);

	        //Copy file in folder
	        copy($folder.'/'.$file, $this->picturesFolder.'/'.$picture->getPath());
	        
	        return $picture;
	    }

	    /**
	     * Clears pictures folder content
	     * @param  OutputInterface $output
	     */
		private function clearPictures(OutputInterface $output){
			$folder = opendir($this->picturesFolder);

			if (!$folder) {
				return;
			}

			while(($file = readdir($folder)) !== false) {
                if ($file != '.' && $file != '..'){
                    unlink($this->picturesFolder.'/'.$file);
                }
            } 

	        //Feedback end
			$output->writeln([
	            '',				
				'Pictures successfully removed from folder',
				'',
			]);
	    }
	}