<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NivelesRestController extends Controller {
	public function getNivelesAction() {
		$em      = $this->getDoctrine()->getManager();
		$puertos = $em->getRepository( 'AppBundle:Puerto' )->findAll();

		return array( 'puertos' => $puertos );
	}

}
