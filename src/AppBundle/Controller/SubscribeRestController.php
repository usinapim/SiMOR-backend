<?php
/**
 * Created by PhpStorm.
 * User: matias
 * Date: 9/5/16
 * Time: 9:22 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriptor;
use AppBundle\Form\SubscriptorType;
use AppBundle\Repository\SubscriptorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;


class SubscribeRestController extends FOSRestController implements ClassResourceInterface {


	private $manager;
	private $repo;
	private $formFactory;
	private $router;

	/**
	 * Controller constructor
	 * @var ObjectManager $manager
	 * @var SubscriptorRepository $repo
	 * @var FormFactoryInterface $formFactory
	 * @var RouterInterface $router
	 */
	public function __construct(
		ObjectManager $manager,
		SubscriptorRepository $repo,
		FormFactoryInterface $formFactory,
		RouterInterface $router
	) {
		$this->manager     = $manager;
		$this->repo        = $repo;
		$this->formFactory = $formFactory;
		$this->router      = $router;
	}

	/**
	 * Create an organisation
	 * @var Request $request
	 * @return View|FormInterface
	 * @Post("/susbcribe")
	 *
	 */
	public function postAction( Request $request ) {

		$subscriptor = new Subscriptor();
		$form        = $this->formFactory->createNamed( '', new SubscriptorType(), $subscriptor );
		$form->handleRequest( $request );
		if ( $form->isValid() ) {
			$this->manager->persist( $subscriptor );
			$this->manager->flush( $subscriptor );

			return View::create( $subscriptor, 200 );

		}

		return View::create( $form, 400 );
	}

}