<?php
/**
 * model\Etape.php 
 *
 * @copyright  2015-2017 Telecom SudParis
 * @license    "MIT/X" License - cf. LICENSE file at project root
 */
namespace Model;

/**
 * Classe "Étape de circuit" du Modèle
 *
 * Entité du Modèle qui gère les étapes des circuits pouvant être (ou ayant pu être) organisés
 * par l'agence de voyage
 */
class Etape
{
    // stores the number of instances created (to generate next object id)
    protected static $instances = 0;

    /**
     * @var int
     */
    private $_id;

    /**
     * @var int Numéro de l'étape dans son circuit
     */
    private $numeroEtape;

    /**
     * @var string
     */
    private $villeEtape;

    /**
     * @var int Durée de l'étape dans cette ville
     */
    private $nombreJours;

    /**
     * Circuit de l'étape
     *
     * @var \Model\Circuit
     */
    protected $circuit;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set numeroEtape
     *
     * @param integer $numeroEtape
     *
     * @return Etape
     */
    public function setNumeroEtape($numeroEtape)
    {
        $this->numeroEtape = $numeroEtape;
        
        return $this;
    }

    /**
     * Get numeroEtape
     *
     * @return int
     */
    public function getNumeroEtape()
    {
        return $this->numeroEtape;
    }

    /**
     * Set villeEtape
     *
     * @param string $villeEtape
     *
     * @return Etape
     */
    public function setVilleEtape($villeEtape)
    {
        $this->villeEtape = $villeEtape;
        
        return $this;
    }

    /**
     * Get villeEtape
     *
     * @return string
     */
    public function getVilleEtape()
    {
        return $this->villeEtape;
    }

    /**
     * Set nombreJours
     *
     * @param integer $nombreJours
     *
     * @return Etape
     */
    public function setNombreJours($nombreJours)
    {
        $this->nombreJours = $nombreJours;
        
        return $this;
    }

    /**
     * Get nombreJours
     *
     * @return int
     */
    public function getNombreJours()
    {
        return $this->nombreJours;
    }

    /**
     * Constructeur
     *
     * @var int $numeroEtape
     * @var string $villeEtape
     * @var int $nombreJours
     * @var \Model\Circuit $circuitAssocie
     * @var int $id (null par defaut)
     */
    public function __construct($numeroEtape, $villeEtape, $nombreJours, $circuitAssocie, $id = null)
    {
        ++ self::$instances;
        if ($id) 
        {
            $this->_id = $id;
        } 
        else 
       {
            $this->_id = self::$instances;
        }
        
        $this->numeroEtape = $numeroEtape;
        $this->villeEtape = $villeEtape;
        $this->nombreJours = $nombreJours;
        $this->circuit = $circuitAssocie;
    }

    /**
     * Set circuit
     *
     * @param \Model\Circuit $circuit
     *
     * @return Etape
     */
    public function setCircuit(\Model\Circuit $circuit = null)
    {
        $this->circuit = $circuit;
        
        return $this;
    }

    /**
     * Get circuit
     *
     * @return \Model\Circuit
     */
    public function getCircuit()
    {
        return $this->circuit;
    }
}
