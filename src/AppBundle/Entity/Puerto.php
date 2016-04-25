<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


/**
 * Puerto
 *
 * @ORM\Table(name="puertos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PuertoRepository")
 * @ExclusionPolicy("all")
 */
class Puerto {
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
	 * @ORM\Column(name="nombre", type="string", length=255, unique=true)
	 *
	 * @SerializedName("puerto")
	 * @Expose
	 */
	private $nombre;

	/**
	 * @var $rio
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rio" )
	 * @ORM\JoinColumn(name="rio_id", referencedColumnName="id")
	 */

	private $rio;

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
	 * @ORM\OneToMany(targetEntity="AppBundle\Entity\Medida", mappedBy="puerto")
	 */
	private $medidas;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="latitud", type="string", length=255, nullable=true)
	 *
	 * @SerializedName("latitud")
	 * @Expose
	 */
	private $latitud;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="longitud", type="string", length=255, nullable=true)
	 *
	 * @SerializedName("longitud")
	 * @Expose
	 */
	private $longitud;


	public function __toString() {
		return $this->nombre;
	}


	/**
	 * @SerializedName("rio")
	 * @VirtualProperty
	 */
	public function getNombreRio() {
		return $this->getRio()->getNombre();
	}

	/**
	 * @SerializedName("ultimo_registro")
	 * @VirtualProperty
	 */
	public function getMedidaUltimoRegistro() {
		return $this->getMedidas()->last()->getUltimoRegistro();
	}

	/**
	 * @SerializedName("variacion")
	 * @VirtualProperty
	 */
	public function getMedidaVariacion() {
		return $this->getMedidas()->last()->getVariacion();
	}

	/**
	 * @SerializedName("alerta")
	 * @VirtualProperty
	 */
	public function getMedidaAlerta() {
		return $this->getMedidas()->last()->getAlerta();
	}

	/**
	 * @SerializedName("evacuacion")
	 * @VirtualProperty
	 */
	public function getMedidaEvacuacion() {
		return $this->getMedidas()->last()->getEvacuacion();
	}

	/**
	 * @SerializedName("estado")
	 * @VirtualProperty
	 */
	public function getMedidaNombreEstadoRio() {
		return $this->getMedidas()->last()->getEstadoRio()->getDescripcion();
	}


	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set nombre
	 *
	 * @param string $nombre
	 *
	 * @return Puerto
	 */
	public function setNombre( $nombre ) {
		$this->nombre = $nombre;

		return $this;
	}

	/**
	 * Get nombre
	 *
	 * @return string
	 */
	public function getNombre() {
		return $this->nombre;
	}

	/**
	 * Set rio
	 *
	 * @param \AppBundle\Entity\Rio $rio
	 *
	 * @return Puerto
	 */
	public function setRio( \AppBundle\Entity\Rio $rio = null ) {
		$this->rio = $rio;

		return $this;
	}

	/**
	 * Get rio
	 *
	 * @return \AppBundle\Entity\Rio
	 */
	public function getRio() {
		return $this->rio;
	}

	/**
	 * Set creado
	 *
	 * @param \DateTime $creado
	 *
	 * @return Puerto
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
	 * @return Puerto
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
	 * @return Puerto
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
	 * @return Puerto
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
	 * Constructor
	 */
	public function __construct() {
		$this->medidas = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Add medida
	 *
	 * @param \AppBundle\Entity\Medida $medida
	 *
	 * @return Puerto
	 */
	public function addMedida( \AppBundle\Entity\Medida $medida ) {
		$this->medidas[] = $medida;

		return $this;
	}

	/**
	 * Remove medida
	 *
	 * @param \AppBundle\Entity\Medida $medida
	 */
	public function removeMedida( \AppBundle\Entity\Medida $medida ) {
		$this->medidas->removeElement( $medida );
	}

	/**
	 * Get medidas
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getMedidas() {
		return $this->medidas;
	}

    /**
     * Set latitud
     *
     * @param string $latitud
     *
     * @return Puerto
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return string
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param string $longitud
     *
     * @return Puerto
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return string
     */
    public function getLongitud()
    {
        return $this->longitud;
    }
}
