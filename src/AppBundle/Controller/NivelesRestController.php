<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class NivelesRestController extends FOSRestController {
	public function getNivelesAction() {
		$em      = $this->getDoctrine()->getManager();
		$puertos = $em->getRepository( 'AppBundle:Puerto' )->findAll();

		header( 'Access-Control-Allow-Origin: *' );
		header( "Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method" );
		header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );

		$vista = $this->view( $puertos,
			200 );

		return $this->handleView( $vista );
	}

	public function getNivelesPuertoAction( Request $request, $puerto ) {
		$em            = $this->getDoctrine()->getManager();
		$arrayCriteria = array( 'nombre' => $puerto );

		$puertos = $em->getRepository( 'AppBundle:Puerto' )->findOneBy( $arrayCriteria );

		header( 'Access-Control-Allow-Origin: *' );
		header( "Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method" );
		header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );

		$vista = $this->view( $puertos,
			200 );

		return $this->handleView( $vista );
	}


}
