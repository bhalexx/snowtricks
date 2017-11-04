<?php

namespace ST\SnowTricksBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\Tools\SchemaTool;
use ST\SnowTricksBundle\Entity\Family;
use ST\UserBundle\Entity\User;

class FamilyControllerTest extends WebTestCase
{
	private $client;
	private $container;
	private $em;
	private $adminUser;
	private $memberUser;

	public function setUp()
	{
		$this->client = static::createClient();
		$this->container = $this->client->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();

        static $metadatas;
        
        if (!isset($metadatas)){
            $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        }
        
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        
        if(!empty($metadatas)){
            $schemaTool->createSchema($metadatas);
        }

        $this->loadFixtures();        
	}

	public function tearDown()
	{
		$this->client = null;
		$this->container = null;
		$this->em = null;
	}

    public function testIndexAction()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAddActionRedirectionIfNotLoggedIn()
    {
        $crawler = $this->client->request('GET', '/groupes/ajouter');
        
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        
        $this->client->followRedirect();

        $this->assertContains('Connexion', $this->client->getResponse()->getContent());
    }

    public function testAddActionIfLoggedIn()
    {
        $this->login($this->memberUser);

        $crawler = $this->client->request('GET', '/groupes/ajouter');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Ajouter un groupe', $this->client->getResponse()->getContent());

        $form = $crawler->selectButton('ENREGISTRER')->form();
        $form['family[name]'] = 'Rotations';
        $form['family[description]'] = 'Les rotations blablabla...';
        
        $crawler = $this->client->submit($form);

        $this->assertContains('Redirecting to /groupes', $this->client->getResponse()->getContent());
        
        $family = $this->em->getRepository('STSnowTricksBundle:Family')->findOneBy(['id' => 2]);
        $this->assertEquals('Rotations', $family->getName());
    }
    
    public function testEditActionRedirectionIfNotLoggedIn()
    {
    	$crawler = $this->client->request('GET', '/groupes/modifier/1/grabs');
        
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        
        $this->client->followRedirect();

        $this->assertContains('Connexion', $this->client->getResponse()->getContent());
    }

    public function testEditActionIfLoggedIn()
    {
        $this->login($this->memberUser);

        $crawler = $this->client->request('GET', '/groupes/modifier/1/grabs');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Modifier le groupe', $this->client->getResponse()->getContent());

        $form = $crawler->selectButton('ENREGISTRER')->form();
        $form['family[name]'] = 'Grabss';
        $form['family[description]'] = 'Les grabs blablabla...';
        
        $crawler = $this->client->submit($form);

        $this->assertContains('Redirecting to /groupes', $this->client->getResponse()->getContent());
        
        $family = $this->em->getRepository('STSnowTricksBundle:Family')->findOneBy(['id' => 1]);
        $this->assertEquals('Grabss', $family->getName());
    }

    public function testDeleteActionRedirectionIfNotLoggedIn()
    {
    	$crawler = $this->client->request('GET', '/groupes/supprimer/1/grabs');
        
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        
        $this->client->followRedirect();

        $this->assertContains('Connexion', $this->client->getResponse()->getContent());
    }

    public function testDeleteActionIfLoggedIn()
    {
    	$this->login($this->memberUser);

        $crawler = $this->client->request('GET', '/groupes/supprimer/1/grabs');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Supprimer le groupe', $this->client->getResponse()->getContent());

        $form = $crawler->selectButton('Supprimer')->form();
        $crawler = $this->client->submit($form);

        $this->assertContains('Redirecting to /groupes', $this->client->getResponse()->getContent());
        
        $family = $this->em->getRepository('STSnowTricksBundle:Family')->findOneBy(['id' => 1]);
        $this->assertNull($family);
    }

    public function testListAction()
    {
    	$crawler = $this->client->request('GET', '/groupes');

    	$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Liste des groupes', $this->client->getResponse()->getContent());
    }

    public function testViewActionUnexistingFamily()
    {
        $crawler = $this->client->request('GET', '/groupes/0/afamilythatdoesntexist');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testViewAction()
    {
    	$crawler = $this->client->request('GET', '/groupes/1/grabs');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Accueil', $this->client->getResponse()->getContent());
    }

    public function loadFixtures()
	{
		$this->adminUser = $this->createUser('ROLE_ADMIN');
        $this->memberUser = $this->createUser('ROLE_MEMBER');

        $family = new Family();
    	$family->setName('Grabs');
    	$family->setDescription('Les grabs blablabla');

    	$this->em->persist($family);
    	$this->em->flush();
	}

    private function createUser($role)
    {
    	$userManager = $this->container->get('fos_user.user_manager');
    	$user = $userManager->createUser();

    	if ($role === 'ROLE_ADMIN') {
    		$user->setUsername('SnowTricks');
    		$user->setFirstname('SnowTricks');
    		$user->setLastname('SnowTricks');
    		$user->setEmail('contact@snowtricks.com');
    		$user->setPlainPassword('test');
    	} else {
    		$user->setUsername('Membre');
    		$user->setFirstname('Membre');
    		$user->setLastname('Membre');
    		$user->setEmail('membre@snowtricks.com');
    		$user->setPlainPassword('test');
    	}

		$user->addRole($role);

		$userManager->updateUser($user);

		return $user;
    }

    private function login($user)
    {
    	$session = $this->client->getContainer()->get('session');

		$firewall = 'main';

		$userManager = $this->container->get('fos_user.user_manager');
		$token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
		
		$session->set('_security_'.$firewall, serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
    }
}
