<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {
	public function indexAction() {
		return $this->render( 'AppBundle:Default:index.html.twig' );
	}

	public function importarTablaPrefecturaAction( Request $request ) {

		$datos = $this->get( 'manager.rios' )->getDatosPrefectura();
		if ( $datos ) {
			$this->get( 'session' )->getFlashBag()->add(
				'success',
				'Datos de Prefectura importados Correctamente'
			);
		} else {
			$this->get( 'session' )->getFlashBag()->add(
				'error',
				'Ocurrió un error al procesar Datos de Prefectura'
			);
		}

		return $this->render( 'AppBundle:Default:index.html.twig');

	}
}
