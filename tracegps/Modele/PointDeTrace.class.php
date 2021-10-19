<?php
// Projet TraceGPS
// fichier : modele/PointDeTrace.class.php
// R�le : la classe PointDeTrace repr�sente un point de passage sur un parcours
// Derni�re mise � jour : 9/7/2021 par dPlanchet
include_once ('Point.class.php');
class PointDeTrace extends Point
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs priv�s de la classe -------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    private $idTrace; // identifiant de la trace
    private $id; // identifiant relatif du point dans la trace
    private $dateHeure; // date et heure du passage au point
    private $rythmeCardio; // rythme cardiaque (en bpm : battements par minute)
    private $tempsCumule; // temps cumul� depuis le d�part (en secondes)
    private $distanceCumulee; // distance cumul�e depuis le d�part (en Km)
    private $vitesse; // vitesse instantan�e au point de passage (en Km/h)
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Constructeur avec 10 param�tres :
    // $unIdTrace : identifiant de la trace
    // $unId : identifiant relatif du point dans la trace
    // $uneLatitude : latitude du point (en degr�s d�cimaux)
    // $uneLongitude : longitude du point (en degr�s d�cimaux)
    // $uneAltitude : altitude du point (en m�tres)
    // $uneDateHeure : heure de passage au point
    // $unRythmeCardio : rythme cardiaque au passage au point
    // $unTempsCumule : temps cumul� depuis le d�part(en secondes)
    // $uneDistanceCumulee : distance cumul�e depuis le d�part (en Km)
    // $uneVitesse : vitesse instantan�e, calcul�e entre le point pr�c�dent et le point suivant (en Km/h)
    public function __construct($unIdTrace, $unID, $uneLatitude, $uneLongitude, $uneAltitude, 
        $uneDateHeure, $unRythmeCardio, $unTempsCumule, $uneDistanceCumulee, $uneVitesse) 
    {
            // appelle le constructeur de la classe m�re avec 3 param�tres
            parent::__construct($uneLatitude, $uneLongitude, $uneAltitude);
            $this->idTrace = $unIdTrace;
            $this->id = $unID;
            $this->dateHeure = $uneDateHeure;
            $this->rythmeCardio = $unRythmeCardio;
            $this->tempsCumule = $unTempsCumule;
            $this->distanceCumulee = $uneDistanceCumulee;
            $this->vitesse = $uneVitesse;
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getIdTrace(){return $this->idTrace;}
    public function setIdTrace($unIdTrace) {$this->idTrace = $unIdTrace;}
    
    public function getId() {return $this->id;}
    public function setId($unId) {$this->id = $unId;}
    
    public function getDateHeure() {return $this->dateHeure;}
    public function setDateHeure($uneDateHeure) {$this->dateHeure = $uneDateHeure;}
    
    public function getRythmeCardio() {return $this->rythmeCardio;}
    public function setRythmeCardio($unRythmeCardio) {$this->rythmeCardio = $unRythmeCardio;}
    
    public function getTempsCumule() {return $this->tempsCumule;}
    public function setTempsCumule($unTempsCumule) {$this->tempsCumule = $unTempsCumule;}
    
    public function getDistanceCumulee() {return $this->distanceCumulee;}
    public function setDistanceCumulee($uneDistanceCumulee) {$this->distanceCumulee = $uneDistanceCumulee;}
    
    public function getVitesse() {return $this->vitesse;}
    public function setVitesse($uneVitesse) {$this->vitesse = $uneVitesse;}
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- M�thodes d'instances ----------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Fournit une chaine contenant toutes les donn�es de l'objet
    public function toString() {
        $msg = "IdTrace : " . $this->getIdTrace() . "<br>";
        $msg .= "Id : " . $this->getId() . "<br>";
        $msg .= parent::toString();
        if ($this->dateHeure != null) {
            $msg .= "Heure de passage : " . $this->dateHeure . "<br>";
        }
        $msg .= "Rythme cardiaque : " . $this->rythmeCardio . "<br>";
        $msg .= "Temps cumule (s) : " . $this->tempsCumule . "<br>";
        $msg .= "Temps cumule (hh:mm:ss) : " . $this->getTempsCumuleEnChaine() . "<br>";
        $msg .= "Distance cumul�e (Km) : " . $this->distanceCumulee . "<br>";
        $msg .= "Vitesse (Km/h) : " . $this->vitesse . "<br>";
        return $msg;
    }
    
    // M�thode fournissant le temps cumul� depuis le d�part (sous la forme d'une chaine "hh:mm:ss")
    public function getTempsCumuleEnChaine()
    {
        $heures = 0;
        $minutes = 0;
        $secondes = 0;
        
        $heures = (PointDeTrace::getTempsCumule()/3600);
        $minutes = ((PointDeTrace::getTempsCumule() % 3600) / 60);
        $secondes = ((PointDeTrace::getTempsCumule()%3600) %60);
        
        return sprintf("%02d",$heures) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$secondes);
    }
    
} // fin de la classe PointDeTrace
// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces apr�s la balise de fin de script !!!!!!!!!!!!