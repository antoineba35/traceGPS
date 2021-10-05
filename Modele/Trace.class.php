<?php
// Projet TraceGPS
// fichier : modele/Trace.class.php
// R�le : la classe Trace repr�sente une trace ou un parcours
// Derni�re mise � jour : 9/7/2021 par dPlanchet
include_once ('PointDeTrace.class.php');
class Trace
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs priv�s de la classe -------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    private $id; // identifiant de la trace
    private $dateHeureDebut; // date et heure de d�but
    private $dateHeureFin; // date et heure de fin
    private $terminee; // true si la trace est termin�e, false sinon
    private $idUtilisateur; // identifiant de l'utilisateur ayant cr�� la trace
    private $lesPointsDeTrace; // la collection (array) des objets PointDeTrace formant la trace
    
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function __construct($unId, $uneDateHeureDebut, $uneDateHeureFin, $terminee, $unIdUtilisateur) {
        $this->id = $unId;
        $this->dateHeureDebut = $uneDateHeureDebut;
        $this->dateHeureFin = $uneDateHeureFin;
        $this->terminee = $terminee;
        $this->idUtilisateur = $unIdUtilisateur;
        
        $this->lesPointsDeTrace = array();
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getId() {return $this->id;}
    public function setId($unId) {$this->id = $unId;}
    
    public function getDateHeureDebut() {return $this->dateHeureDebut;}
    public function setDateHeureDebut($uneDateHeureDebut) {$this->dateHeureDebut = $uneDateHeureDebut;}
    public function getDateHeureFin() {return $this->dateHeureFin;}
    public function setDateHeureFin($uneDateHeureFin) {$this->dateHeureFin= $uneDateHeureFin;}
    
    public function getTerminee() {return $this->terminee;}
    public function setTerminee($terminee) {$this->terminee = $terminee;}
    
    public function getIdUtilisateur() {return $this->idUtilisateur;}
    public function setIdUtilisateur($unIdUtilisateur) {$this->idUtilisateur = $unIdUtilisateur;}
    
    public function getLesPointsDeTrace() {return $this->lesPointsDeTrace;}    
    public function setLesPointsDeTrace($lesPointsDeTrace) {$this->lesPointsDeTrace = $lesPointsDeTrace;}
    
    // Fournit une chaine contenant toutes les donn�es de l'objet
    public function toString() {
        $msg = "Id : " . $this->getId() . "<br>";
        $msg .= "Utilisateur : " . $this->getIdUtilisateur() . "<br>";
        if ($this->getDateHeureDebut() != null) {
            $msg .= "Heure de d�but : " . $this->getDateHeureDebut() . "<br>";
        }
        if ($this->getTerminee()) {
            $msg .= "Termin�e : Oui <br>";
        }
        else {
            $msg .= "Termin�e : Non <br>";
        }
        $msg .= "Nombre de points : " . $this->getNombrePoints() . "<br>";
        if ($this->getNombrePoints() > 0) {
            if ($this->getDateHeureFin() != null) {
                $msg .= "Heure de fin : " . $this->getDateHeureFin() . "<br>";
            }
            $msg .= "Durée en secondes : " . $this->getDureeEnSecondes() . "<br>";
            $msg .= "Durée totale : " . $this->getDureeTotale() . "<br>";
            $msg .= "Distance totale en Km : " . $this->getDistanceTotale() . "<br>";
            $msg .= "Denivele en m : " . $this->getDenivele() . "<br>";
            $msg .= "Denivele positif en m : " . $this->getDenivelePositif() . "<br>";
            $msg .= "Denivele négatif en m : " . $this->getDeniveleNegatif() . "<br>";
            $msg .= "Vitesse moyenne en Km/h : " . $this->getVitesseMoyenne() . "<br>";
            $msg .= "Centre du parcours : " . "<br>";
            $msg .= " - Latitude : " . $this->getCentre()->getLatitude() . "<br>";
            $msg .= " - Longitude : " . $this->getCentre()->getLongitude() . "<br>";
            $msg .= " - Altitude : " . $this->getCentre()->getAltitude() . "<br>";
        }
        return $msg;
    }
    
    public function getNombrePoints(){ return sizeof($this->lesPointsDeTrace); }
    
    public function getCentre()
    {
        if(sizeof($this->lesPointsDeTrace) == 0)
        {
            return null;
        }
        else 
        {
            $lePoint = $this->lesPointsDeTrace[0];
            $latitudeMini = $lePoint->getLatitude();
            $latitudeMaxi = $lePoint->getLatitude();
            
            $longitudeMini = $lePoint->getLongitude();
            $longitudeMaxi = $lePoint->getLongitude();
            
            for ($i = 0; $i < sizeof($this->lesPointsDeTrace) ; $i++) {
                $lePoint = $this->lesPointsDeTrace[$i];
                if ($latitudeMini > $lePoint->getLatitude()) $latitudeMini = $lePoint->getLatitude();
                if ($latitudeMaxi < $lePoint->getLatitude()) $latitudeMaxi = $lePoint->getLatitude();
                if ($longitudeMini > $lePoint->getLongitude()) $longitudeMini = $lePoint->getLongitude();
                if ($longitudeMaxi < $lePoint->getLongitude()) $longitudeMaxi = $lePoint->getLongitude();
            }
            
            $pointCentre = new Point(($latitudeMaxi + $latitudeMini) / 2, ($longitudeMaxi + $longitudeMini) /2, 0);
            return $pointCentre;
        }
    }
      
    public function getDenivele()
    {
        if(sizeof($this->lesPointsDeTrace) == 0)
        {
            return 0;
        }
        else 
        {
            $lePoint = $this->lesPointsDeTrace[0];
            $altitudeMini = $lePoint->getAltitude();
            $altitudeMaxi = $lePoint->getAltitude();
            
            for ($i = 0; $i < sizeof($this->lesPointsDeTrace) ; $i++) {
                $lePoint = $this->lesPointsDeTrace[$i];
                if ($altitudeMini > $lePoint->getAltitude()) $altitudeMini = $lePoint->getAltitude();
                if ($altitudeMaxi < $lePoint->getAltitude()) $altitudeMaxi = $lePoint->getAltitude();
            }
            return $altitudeMaxi - $altitudeMini;            
        }
    }
    
    public function getDureeEnSecondes()
    {
        if(sizeof($this->lesPointsDeTrace) == 0)
        {
            return 0;
        }
        else 
        {
            $premierPoint = $this->lesPointsDeTrace[0];
            $dernierPoint = $this->lesPointsDeTrace[sizeof($this->lesPointsDeTrace) - 1];
            
            $tempspremierPoint = $premierPoint->getDateHeure();
            $tempsdernierPoint = $dernierPoint->getDateHeure();
            
            if(strtotime($tempsdernierPoint) - strtotime($tempspremierPoint) > 0)
            {
                $diff = strtotime($tempsdernierPoint) - strtotime($tempspremierPoint);
            }
            else
                $diff = 0;
            
            return $diff;
        }
    }

    public function getDureeTotale()
    {
        $heures = 0;
        $minutes = 0;
        $secondes = 0;
        
        $heures = (Trace::getDureeEnSecondes()/3600);
        $minutes = ((Trace::getDureeEnSecondes() % 3600) / 60);
        $secondes = ((Trace::getDureeEnSecondes()%3600) %60);
        
        return sprintf("%02d",$heures) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$secondes);
    }

    public function getDistanceTotale()
    {
        if(sizeof($this->lesPointsDeTrace) == 0)
        {
            return 0;
        }
        else 
        {
            if(sizeof($this->lesPointsDeTrace) > 0)
            {
                $dernierPoint = $this->lesPointsDeTrace[sizeof($this->lesPointsDeTrace) -1];
                return $dernierPoint->getDistanceCumulee();
            }
            else 
                return 0;
        }
    }
    
    public function getDenivelePositif()
    {
        $km = 0;
        if (sizeof($this->lesPointsDeTrace) != 0)
        {            
            for ($i = 1; $i < sizeof($this->lesPointsDeTrace) ; $i++) 
            {
                $point1 = $this->lesPointsDeTrace[$i-1];
                $point2 = $this->lesPointsDeTrace[$i];
                $altitude1 = $point1->getAltitude();
                $altitude2 = $point2->getAltitude();
                if($altitude1 < $altitude2) $km += $altitude2 - $altitude1;
            }
        }
        else
        {
            $km = 0;
        }
        
        return $km;
    }
    
    public function getDeniveleNegatif()
    {
        $km = 0;
        if (sizeof($this->lesPointsDeTrace) != 0)
        {
            for ($i = 1; $i < sizeof($this->lesPointsDeTrace) ; $i++)
            {
                $point1 = $this->lesPointsDeTrace[$i-1];
                $point2 = $this->lesPointsDeTrace[$i];
                $altitude1 = $point1->getAltitude();
                $altitude2 = $point2->getAltitude();
                if($altitude1 > $altitude2) $km += $altitude1 - $altitude2;
            }
        }
        else
        {
            $km = 0;
        }
        
        return $km;
    }

    public function getVitesseMoyenne()
    {
        $distance = $this->getDistanceTotale();
        $duree = $this->getDureeEnSecondes();
        $duree /= 3600;
        $resultat = $distance / $duree;
        if($distance == 0 && $duree == 0) return 0;
        return $resultat;
    }

    public function ajouterPoint(PointDeTrace $PointTrace)
    {
        if(sizeof($this->lesPointsDeTrace) == 0)
        {
            $PointTrace -> setTempsCumule(0);
            $PointTrace -> setDistanceCumulee(0);
            $PointTrace -> setVitesse(0);
            $this->lesPointsDeTrace[] = $PointTrace;
        }
        else 
        {
            $dernierPoint = $this->lesPointsDeTrace[sizeof($this->lesPointsDeTrace) - 1];
            
            // distance
            $distance = Point::getDistance($PointTrace, $dernierPoint);
            $dernierDist = $dernierPoint->getDistanceCumulee();
            $PointTrace -> setDistanceCumulee($dernierDist + $distance);
            
            // temps
            $dernierheure = $PointTrace->getDateHeure();
            $avantDernier =  $dernierPoint->getDateHeure();
             
            if(strtotime($dernierheure) - strtotime($avantDernier) > 0)
            {
                $duree = strtotime($dernierheure) - strtotime($avantDernier);
                $dureeCumule = $dernierPoint->getTempsCumule();
                $dureeTotale = $dureeCumule + $duree;
            }
            else 
                $dureeTotale = 0;
            $PointTrace -> setTempsCumule($dureeTotale);
            
            // vitesse
            $PointTrace -> setVitesse($distance / $dureeTotale);
            
            $this->lesPointsDeTrace[] = $PointTrace;
        }
    }

    public function viderListePoints()
    {
        $this->lesPointsDeTrace = array();
    }
    
} // fin de la classe Trace
// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces apr�s la balise de fin de script !!!!!!!!!!!!













