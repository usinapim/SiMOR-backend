<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NivelesRestController extends Controller {
	public function getNivelesAction() {
		$em      = $this->getDoctrine()->getManager();
		$puertos = $em->getRepository( 'AppBundle:Puerto' )->findAll();

		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		return array( 'puertos' => $puertos );
	}

}
