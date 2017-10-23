<?php
/**
 * model\Circuit.php
 * 
 * @copyright  2015-2017 Telecom SudParis
 * @license    "MIT/X" License - cf. LICENSE file at project root
 */

namespace Model;

/**
 * Classe "Circuit" du Modèle
 *
 * Entité du Modèle qui gère les circuits pouvant être (ou ayant pu être) organisés par l'agence 
 * de voyage
 */
class Circuit
{
    // stores the number of instances created (to generate next object id)
    protected static $instances = 0;

    private $_id;
    private $description;
    private $paysDepart;
    private $villeDepart;
    private $villeArrivee;
    private $pathImage;

    /**
     * @return mixed
     */
    public function getPathImage()
    {
        return $this->pathImage;
    }

    /**
     * @param mixed $pathImage
     */
    public function setPathImage($pathImage)
    {
        $this->pathImage = $pathImage;
    }
    /**
     * @var int  Durée du circuit (attribut calculé)
     */
    private $dureeCircuit;
    protected $programmations;
    private $etapes;
    /**
     * @var int Nombre d'étapes (attribut calculé)
     */
    private $nbEtapes;
    
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
     * Set description
     *
     * @param string $description
     *
     * @return Circuit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set paysDepart
     *
     * @param string $paysDepart
     *
     * @return Circuit
     */
    public function setPaysDepart($paysDepart)
    {
        $this->paysDepart = $paysDepart;

        return $this;
    }

    /**
     * Get paysDepart
     *
     * @return string
     */
    public function getPaysDepart()
    {
        return $this->paysDepart;
    }

    /**
     * Set villeDepart
     *
     * @param string $villeDepart
     *
     * @return Circuit
     */
    public function setVilleDepart($villeDepart)
    {
        $this->villeDepart = $villeDepart;

        return $this;
    }

    /**
     * Get villeDepart
     *
     * @return string
     */
    public function getVilleDepart()
    {
        return $this->villeDepart;
    }

    /**
     * Set villeArrivee
     *
     * @param string $villeArrivee
     *
     * @return Circuit
     */
    public function setVilleArrivee($villeArrivee)
    {
        $this->villeArrivee = $villeArrivee;

        return $this;
    }

    /**
     * Get villeArrivee
     *
     * @return string
     */
    public function getVilleArrivee()
    {
        return $this->villeArrivee;
    }

    /**
     * Set dureeCircuit
     *
     * @param integer $dureeCircuit
     * 
     * Attention : attribut normalement calculé au fil des ajout des étapes
     *
     * @return Circuit
     */
    public function setDureeCircuit($dureeCircuit)
    {
        $this->dureeCircuit = $dureeCircuit;

        return $this;
    }

    /**
     * Get dureeCircuit
     *
     * @return int
     */
    public function getDureeCircuit()
    {
        return $this->dureeCircuit;
    }

    /**
     * Constructor
     * 
     * @param int $id
     * 
     * Passed Ids only necessary when loading from the DB for instance
     */
    public function __construct($id = null)
    {
    	++self::$instances;
    	
    	if ($id) {
    		$this->_id = $id;
    	}
    	else {
	    	// Generate ID from number of instances (safe if no decrement at destruction)
		    $this->_id = self::$instances;
    	}
	    
	    $this->programmations = array();
	    $this->etapes = array();
	    
	    // Calculated attributes
	    $this->nbEtapes = 0;
	    $this->dureeCircuit = 0;
    }
		
    /**
     * Add programmation
     *
     * @param \Model\ProgrammationCircuit $programmation
     *
     * @return ProgrammationCircuit
     */
    public function addProgrammation(\Model\ProgrammationCircuit $programmation)
    {
        $this->programmations[] = $programmation;

		// normaly useless       
		// $programmation->setCircuit($this);

        return $programmation;
    }

    /**
     * Get programmations
     *
     * @return array
     */
    public function getProgrammations()
    {
        return $this->programmations;
    }

    /**
     * Add etape
     *
     * @param string $nom nom de la ville étape
     * @param int $duree durée de l'étape dans cette ville
     * @param int $id identifiant de l'étape
     *
     * @return Etape
     */
    public function addEtape($nom, $duree, $id=null)
    {
        $this->nbEtapes++;
        
        $etape = new Etape($this->nbEtapes, $nom, $duree, $this, $id);
        
    	if($this->nbEtapes == 1) {
    		$this->villeDepart = $nom;
    	}
    	// we always add etape at the end of the circuit so last Etape is arrival
    	$this->villeArrivee = $nom;

        $this->etapes[] = $etape;
        
        $this->dureeCircuit += $duree;
        
        return $etape;
    }

    /**
     * Suppression d'étape
     * 
     * @param Etape $etape
     * 
     * @return number
     */
    public function removeEtape($etape)
    {
        $found = -1;
        $etape_depart=null;
        $etape_arrivee=null;
        
        foreach($this->etapes as $i => $e) 
        {
            // we found the etape to be removed
            if($e === $etape) 
            {
                $found = $i;
                $this->dureeCircuit -= $e->getNombreJours();
            }
            else 
          {
                if(!isset($etape_depart)) 
                {
                    $etape_depart = $e;
                }
                $etape_arrivee = $e;
            }
            // renumber later etapes
            if($found >= 0) 
            {
                $e->setNumeroEtape($e->getNumeroEtape() - 1);
            }
        }
        if($found >= 0) 
        {
            // actually remove its from the list of Etapes of the Circuit
            unset($this->etapes[$found]);
            
            // update calculated attributes
            $this->nbEtapes--;

            if(isset($etape_depart)) 
            {
                $this->setVilleDepart($etape_depart->getVilleEtape());
                $this->setVilleArrivee($etape_arrivee->getVilleEtape());
            }
            else 
            {
                $this->setVilleDepart('');
                $this->setVilleArrivee('');
            }
        }
        return $found;
    }
    
    /**
     * Get etapes
     *
     * @return array
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

}
