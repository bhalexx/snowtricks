<?php

	namespace ST\CoreBundle\Command;

	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Yaml\Yaml;
	use ST\SnowTricksBundle\Entity\Family;
	use ST\SnowTricksBundle\Entity\Trick;
	use ST\UserBundle\Entity\User;

	class LoadFixturesCommand extends ContainerAwareCommand
	{
		private $em;

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

        	// Drop database
			$output->writeln([
	            '===================================================',
	            'Dropping DataBase',
	            '===================================================',
	            '',
	        ]);

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

	        //Load fixtures
	        $output->writeln([
	            '',
	            '===================================================',
	            'Load Fixtures',
	            '===================================================',
	            '',
	        ]);

	        //Load & create users from YAML file
	        $this->loadUsers($output);
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

		public function loadUsers(OutputInterface $output)
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

			//Parse YAML file
			$users = Yaml::parse(file_get_contents(__DIR__.'/../DataFixtures/UserData.yml'));
			
			foreach($users['Users'] as $userData) {
				$user = new User();
				$user->setUsername($userData['username']);
				$user->setPassword($userData['password']);
				$user->setEmail($userData['email']);
				$user->setEnabled(true);
				
				foreach($userData['roles'] as $role) {
					$user->addRole($role);
				}
				
				$manager->createUser($user);

				$this->em->persist($user);
			}

			$this->em->flush();

			//Feedback end
			$output->writeln([
	            '',				
				count($users['Users']).' users successfully created',
				'',
			]);
		}

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

			foreach($families['Families'] as $familyData) {
				$family = new Family();
				foreach ($familyData as $key => $value) {
					if ($key !== 'tricks') {
						$method = 'set'.ucfirst($key);
						$family->$method($value);
					}
				}
				$this->em->persist($family);

				foreach ($familyData['tricks'] as $trickData) {
					$trick = new Trick();
					foreach($trickData as $key => $value) {
						$method = 'set'.ucfirst($key);
						$trick->$method($value);
						$trick->setAuthor($users[rand(0, (count($users)-1))]);
						$trick->setFamily($family);						
					}
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
	}