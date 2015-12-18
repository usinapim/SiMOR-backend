<?php

namespace UtilBundle\Services;


use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory;
use PHPExcel_Style_Border;


class ExcelTool {


	private $container;
	private $filename;
	private $title;
	private $descripcion;
	private $createby = 'BigD';
	private $doctrine;
	private $phpexcel;

	/**
	 * @return mixed
	 */
	public function getContainer() {
		return $this->container;
	}

	/**
	 * @param mixed $container
	 */
	public function setContainer( $container ) {
		$this->container = $container;
	}

	/**
	 * @return mixed
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @param mixed $filename
	 */
	public function setFilename( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}

	/**
	 * @param mixed $descripcion
	 */
	public function setDescripcion( $descripcion ) {
		$this->descripcion = $descripcion;
	}

	/**
	 * @return string
	 */
	public function getCreateby() {
		return $this->createby;
	}

	/**
	 * @param string $createby
	 */
	public function setCreateby( $createby ) {
		$this->createby = $createby;
	}

	/**
	 * @return mixed
	 */
	public function getDoctrine() {
		return $this->doctrine;
	}

	/**
	 * @param mixed $doctrine
	 */
	public function setDoctrine( $doctrine ) {
		$this->doctrine = $doctrine;
	}

	/**
	 * @return mixed
	 */
	public function getPhpexcel() {
		return $this->phpexcel;
	}

	/**
	 * @param mixed $phpexcel
	 */
	public function setPhpexcel( $phpexcel ) {
		$this->phpexcel = $phpexcel;
	}


	public function __construct( Factory $phpExcel, EntityManager $doctrine ) {
		$this->phpexcel = $phpExcel;
		$this->doctrine = $doctrine;
	}


	/**
	 *
	 * * Arma la hoja para el listado de personas que pertenecen al area
	 *
	 * @param type $resultSet
	 * @param type $areaStr
	 *
	 * @return type
	 */
	public function buildSheetResultados( $resultados ) {
		/* @var $phpExcelObject \PHPExcel */
		$phpExcelObject = $this->getPhpexcel()->createPHPExcelObject();
		$phpExcelObject->getProperties()->setLastModifiedBy( $this->createby );
		$phpExcelObject->getProperties()->setTitle( $this->title );
		$phpExcelObject->getProperties()->setDescription( $this->descripcion );
		$phpExcelObject->getProperties()->setCreator( $this->createby );

		$phpExcelObject->setActiveSheetIndex( 0 );

		$phpExcelObject->getActiveSheet()
		               ->setCellValue( 'A1', 'Nro' )
		               ->setCellValue( 'B1', 'Localidad' )
		               ->setCellValue( 'C1', 'Escuela' )
		               ->setCellValue( 'D1', 'Nro de mesa' )
		               ->setCellValue( 'E1', 'Resultados FPV' )
		               ->setCellValue( 'F1', 'Resultados Cambiemos' );

		$i = 2;
		/** @var $resultado \BalotajeBundle\Entity\Mesa */

		foreach ( $resultados as $resultado ) {
			$phpExcelObject->getActiveSheet()->setCellValue( 'A' . $i, $resultado->getId() );
			$phpExcelObject->getActiveSheet()->setCellValue( 'B' . $i, $resultado->getLocalidad() );
			$phpExcelObject->getActiveSheet()->setCellValue( 'C' . $i, $resultado->getEscuelaTestigo() );
			$phpExcelObject->getActiveSheet()->setCellValue( 'D' . $i, $resultado->getNumero() );
			$phpExcelObject->getActiveSheet()->setCellValue( 'E' . $i, $resultado->getResultadoFpv() );
			$phpExcelObject->getActiveSheet()->setCellValue( 'F' . $i, $resultado->getResultadoCambiemos() );
			$i ++;
		}

		foreach ( range( 'A', 'F' ) as $columnID ) {
			$phpExcelObject->getActiveSheet()->getColumnDimension( $columnID )
			               ->setAutoSize( true );
		}

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$phpExcelObject->getActiveSheet()->getStyle(
			'A1:' .
			$phpExcelObject->getActiveSheet()->getHighestColumn() .
			$phpExcelObject->getActiveSheet()->getHighestRow()
		)->applyFromArray( $styleArray );

		$headerStyleArray = array(
			'font' => array(
				'bold' => true,
				'size' => 11,
				'name' => 'Verdana'
			)
		);

		$phpExcelObject->getActiveSheet()->getStyle( 'A1:F1' )->applyFromArray( $headerStyleArray );


		$phpExcelObject->getActiveSheet()->setTitle( $this->title );

		$writer = $this->getPhpexcel()->createWriter( $phpExcelObject, 'Excel5' );
		// create the response
		$response = $this->getPhpexcel()->createStreamedResponse( $writer );

		return $response;

	}


}

