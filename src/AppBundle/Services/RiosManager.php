<?php
/**
 * Created by PhpStorm.
 * User: matias
 * Date: 16/12/15
 * Time: 5:44 PM
 */

namespace AppBundle\Services;


use AppBundle\Entity\EstadoRio;
use AppBundle\Entity\Medida;
use AppBundle\Entity\Puerto;
use AppBundle\Entity\Rio;
use Doctrine\ORM\EntityManager;
use DOMDocument;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use UtilBundle\lib\DateTools;

class RiosManager {
	private $container;
	/* @var $em EntityManager */
	private $em;

	public function __construct( $container ) {
		$this->container = $container;
		$this->em        = $container->get( 'doctrine' )->getManager();
	}

	public function getDatosPrefectura() {
		$em = $this->em;

		$datos = $this->getDatosTablaByURL( "http://www.prefecturanaval.gov.ar/alturas/index.php", 0 );
		//$datos = array ('id'=>2);
		if ( $datos ) {
			unset( $datos [0] );
			unset( $datos [1] );
			$retorno = array();
			foreach ( $datos as $key => $value ) {
//				$retorno[] = $value;
				if ( array_key_exists( 'rio', $value ) ) {
					$rio = $em->getRepository( 'AppBundle:Rio' )->findOneByNombre( $value['rio'] );
					if ( ! $rio ) {
						$rio = new Rio();
						$rio->setNombre( $value['rio'] );
						$em->persist( $rio );
						$em->flush();
					}
					$slugEstado = strtolower( str_replace( ' ', '-', $value['estado'] ) );
					$slugEstado = str_replace( '.', '', $slugEstado );
					$estado     = $em->getRepository( 'AppBundle:EstadoRio' )->findOneBySlug( $slugEstado );
					if ( ! $estado ) {
						$estado = new EstadoRio();
						$estado->setDescripcion( $value['estado'] );
						$estado->setSlug( $slugEstado );
						$em->persist( $estado );
						$em->flush();
					}
					$puerto = $em->getRepository( 'AppBundle:Puerto' )->findOneByNombre( $value['puerto'] );
					if ( ! $puerto ) {
						$puerto = new Puerto();
						$puerto->setRio( $rio );
						$puerto->setNombre( $value['puerto'] );
						$em->persist( $puerto );
						$em->flush();
					}
					$value['ultregistro'] = str_replace( ',', '.', $value['ultregistro'] );
					$value['variacion']   = str_replace( ',', '.', $value['variacion'] );
					$value['alerta']      = str_replace( ',', '.', $value['alerta'] );
					$value['evacuacion']  = str_replace( ',', '.', $value['evacuacion'] );

					$medida = new Medida();
					$medida->setPuerto( $puerto );
					$medida->setUltimoRegistro( $value['ultregistro'] );
					$medida->setVariacion( $value['variacion'] );
					$medida->setPeriodo( $value['periodohs'] );
					$meses = DateTools::getAMesCortoEn();
					foreach ( $meses as $key => $mes ) {
						$fecha = str_replace( $key,
							$mes,
							$value['fechahora'],
							$count );
						if ( $count ) {
							break;
						}
					}
					$fechaHora = \DateTime::createFromFormat( 'd/M/y - Hi', $fecha );
					$medida->setFechaHora( $fechaHora );
					$medida->setEstadoRio( $estado );
					$medida->setAlerta( $value['alerta'] );
					$medida->setEvacuacion( $value['evacuacion'] );
					$medida->setFuenteDatos( 'Prefectura' );
					$em->persist( $medida );

				}
			}
			$em->flush();


//			$retorno = array( 'puertos' => $retorno );

			return true; // 200 being the HTTP response code
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param type $url url en la cual se encuetra la tabla
	 * @param type $posTabla posicion en la cual se encuetra la tabla dentro del DOC
	 */
	private function getDatosTablaByURL( $url, $posTabla = 0 ) {
		libxml_use_internal_errors( true );
		$dom = new DOMDocument();
//load the html
		$dom->loadHTMLFile( $url );
		//discard white space
		$dom->preserveWhiteSpace = false;
		//the table by its tag name
//		$tables = $dom->getElementsByTagName( 'table' );
		//get all rows from the table

		// get each column by tag name
		$thead       = $dom->getElementsByTagName( 'thead' );
		$cols        = $thead->item( 0 )->getElementsByTagName( 'th' );
		$row_headers = null;
		foreach ( $cols as $node ) {
//			print $node->nodeValue . "\n";

			$row_headers[] = $node->nodeValue;

		}
		$table = array();
		//get all rows from the table
		$tbody = $thead = $dom->getElementsByTagName( 'tbody' );
		$rows  = $tbody->item( $posTabla )->getElementsByTagName( 'tr' );
		foreach ( $rows as $row ) {
			// get each column by tag name
			$cols   = $row->getElementsByTagName( 'th' );
			$colsTD = $row->getElementsByTagName( 'td' );

			$row = array();
			$i   = 0;
			foreach ( array( $cols, $colsTD ) as $colis ) {


				foreach ( $colis as $nodeCols ) {
					# code...
					//print $nodeCols->nodeValue."\n";
					if ( $row_headers == null ) {
						$row[] = $nodeCols->nodeValue;
					} else {
						if ( array_key_exists( $i, $row_headers ) && $row_headers[ $i ] !== ' ' ) {
							$indice = $this->normaliza( $row_headers[ $i ] );

							$row[ $indice ] = $nodeCols->nodeValue;
						}

					}
					$i ++;
				}
			}
			$table[] = $row;
		}

		return $table;
	}

	function normaliza( $cadena ) {
//        $charset='ISO-8859-1'; // o 'UTF-8'
//        $str = iconv($charset, 'ASCII//TRANSLIT', $str);
		$originales  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$cadena      = utf8_decode( $cadena );
		$cadena      = strtr( $cadena, utf8_decode( $originales ), $modificadas );
		$cadena      = trim( $cadena );
		$cadena      = preg_replace( '([^A-Za-z0-9])', '', $cadena );
		$cadena      = strtolower( $cadena );

		return utf8_encode( $cadena );
	}

}