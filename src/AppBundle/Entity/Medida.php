<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Medida
 *
 * @ORM\Table(name="medidas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MedidaRepository")
 *
 */
class Medida {
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ultimo_registro", type="decimal", precision=10, scale=2)
	 *
	 */
	private $ultimoRegistro;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="variacion", type="decimal", precision=10, scale=2)
	 *
	 */
	private $variacion;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="periodo", type="integer", nullable=true)
	 */
	private $periodo;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fecha_hora", type="datetime", nullable=true)
	 */
	private $fechaHora;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alerta", type="decimal", precision=10, scale=2)
	 */
	private $alerta;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="evacuacion", type="decimal", precision=10, scale=2)
	 */
	private $evacuacion;

	/**
	 * @var $puerto
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Puerto", inversedBy="medidas" )
	 * @ORM\JoinColumn(name="puerto_id", referencedColumnName="id")
	 *
	 */

	private $puerto;

	/**
	 * @var $puerto
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EstadoRio" )
	 * @ORM\JoinColumn(name="estado_rio_id", referencedColumnName="id")
	 *
	 */

	private $estadoRio;

	/**
	 * @var datetime $creado
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(name="creado", type="datetime")
	 */
	private $creado;

	/**
	 * @var datetime $actualizado
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(name="actualizado",type="datetime")
	 */
	private $actualizado;

	/**
	 * @var integer $creadoPor
	 *
	 * @Gedmo\Blameable(on="create")
	 * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario")
	 * @ORM\JoinColumn(name="creado_por", referencedColumnName="id", nullable=true)
	 */
	private $creadoPor;

	/**
	 * @var integer $actualizadoPor
	 *
	 * @Gedmo\Blameable(on="update")
	 * @ORM\ManyToOne(targetEntity="UsuariosBundle\Entity\Usuario")
	 * @ORM\JoinColumn(name="actualizado_por", referencedColumnName="id", nullable=true)
	 */
	private $actualizadoPor;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fuente_datos", type="string", length=255)
	 */
	private $fuenteDatos;


	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set ultimoRegistro
	 *
	 * @param string $ultimoRegistro
	 *
	 * @return Medida
	 */
	public function setUltimoRegistro( $ultimoRegistro ) {
		$this->ultimoRegistro = $ultimoRegistro;

		return $this;
	}

	/**
	 * Get ultimoRegistro
	 *
	 * @return string
	 */
	public function getUltimoRegistro() {
		return $this->ultimoRegistro;
	}

	/**
	 * Set variacion
	 *
	 * @param string $variacion
	 *
	 * @return Medida
	 */
	public function setVariacion( $variacion ) {
		$this->variacion = $variacion;

		return $this;
	}

	/**
	 * Get variacion
	 *
	 * @return string
	 */
	public function getVariacion() {
		return $this->variacion;
	}

	/**
	 * Set periodo
	 *
	 * @param integer $periodo
	 *
	 * @return Medida
	 */
	public function setPeriodo( $periodo ) {
		$this->periodo = $periodo;

		return $this;
	}

	/**
	 * Get periodo
	 *
	 * @return int
	 */
	public function getPeriodo() {
		return $this->periodo;
	}

	/**
	 * Set fechaHora
	 *
	 * @param \DateTime $fechaHora
	 *
	 * @return Medida
	 */
	public function setFechaHora( $fechaHora ) {
		$this->fechaHora = $fechaHora;

		return $this;
	}

	/**
	 * Get fechaHora
	 *
	 * @return \DateTime
	 */
	public function getFechaHora() {
		return $this->fechaHora;
	}

	/**
	 * Set alerta
	 *
	 * @param string $alerta
	 *
	 * @return Medida
	 */
	public function setAlerta( $alerta ) {
		$this->alerta = $alerta;

		return $this;
	}

	/**
	 * Get alerta
	 *
	 * @return string
	 */
	public function getAlerta() {
		return $this->alerta;
	}

	/**
	 * Set evacuacion
	 *
	 * @param string $evacuacion
	 *
	 * @return Medida
	 */
	public function setEvacuacion( $evacuacion ) {
		$this->evacuacion = $evacuacion;

		return $this;
	}

	/**
	 * Get evacuacion
	 *
	 * @return string
	 */
	public function getEvacuacion() {
		return $this->evacuacion;
	}

	/**
	 * Set puerto
	 *
	 * @param \AppBundle\Entity\Puerto $puerto
	 *
	 * @return Medida
	 */
	public function setPuerto( \AppBundle\Entity\Puerto $puerto = null ) {
		$this->puerto = $puerto;

		return $this;
	}

	/**
	 * Get puerto
	 *
	 * @return \AppBundle\Entity\Puerto
	 */
	public function getPuerto() {
		return $this->puerto;
	}

	/**
	 * Set creado
	 *
	 * @param \DateTime $creado
	 *
	 * @return Medida
	 */
	public function setCreado( $creado ) {
		$this->creado = $creado;

		return $this;
	}

	/**
	 * Get creado
	 *
	 * @return \DateTime
	 */
	public function getCreado() {
		return $this->creado;
	}

	/**
	 * Set actualizado
	 *
	 * @param \DateTime $actualizado
	 *
	 * @return Medida
	 */
	public function setActualizado( $actualizado ) {
		$this->actualizado = $actualizado;

		return $this;
	}

	/**
	 * Get actualizado
	 *
	 * @return \DateTime
	 */
	public function getActualizado() {
		return $this->actualizado;
	}

	/**
	 * Set creadoPor
	 *
	 * @param \UsuariosBundle\Entity\Usuario $creadoPor
	 *
	 * @return Medida
	 */
	public function setCreadoPor( \UsuariosBundle\Entity\Usuario $creadoPor = null ) {
		$this->creadoPor = $creadoPor;

		return $this;
	}

	/**
	 * Get creadoPor
	 *
	 * @return \UsuariosBundle\Entity\Usuario
	 */
	public function getCreadoPor() {
		return $this->creadoPor;
	}

	/**
	 * Set actualizadoPor
	 *
	 * @param \UsuariosBundle\Entity\Usuario $actualizadoPor
	 *
	 * @return Medida
	 */
	public function setActualizadoPor( \UsuariosBundle\Entity\Usuario $actualizadoPor = null ) {
		$this->actualizadoPor = $actualizadoPor;

		return $this;
	}

	/**
	 * Get actualizadoPor
	 *
	 * @return \UsuariosBundle\Entity\Usuario
	 */
	public function getActualizadoPor() {
		return $this->actualizadoPor;
	}

	/**
	 * Set estadoRio
	 *
	 * @param \AppBundle\Entity\EstadoRio $estadoRio
	 *
	 * @return Medida
	 */
	public function setEstadoRio( \AppBundle\Entity\EstadoRio $estadoRio = null ) {
		$this->estadoRio = $estadoRio;

		return $this;
	}

	/**
	 * Get estadoRio
	 *
	 * @return \AppBundle\Entity\EstadoRio
	 */
	public function getEstadoRio() {
		return $this->estadoRio;
	}

	/**
	 * Set fuenteDatos
	 *
	 * @param string $fuenteDatos
	 *
	 * @return Medida
	 */
	public function setFuenteDatos( $fuenteDatos ) {
		$this->fuenteDatos = $fuenteDatos;

		return $this;
	}

	/**
	 * Get fuenteDatos
	 *
	 * @return string
	 */
	public function getFuenteDatos() {
		return $this->fuenteDatos;
	}
}
