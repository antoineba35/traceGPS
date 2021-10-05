<?php
// Projet TraceGPS
// fichier : modele/Point.class.php
// R�le : la classe Point repr�sente un point g�ographique
// Derni�re mise � jour : 9/7/2019 par dPlanchet
class Point
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs prot�g�s de la classe -----------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // protected au lieu de private car cette classe fera l'objet d'un h�ritage
    protected $latitude; // latitude
    protected $longitude; // longitude
    protected $altitude; // altitude
    
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function __construct($uneLatitude, $uneLongitude, $uneAltitude) {
        $this->latitude = $uneLatitude;
        $this->longitude = $uneLongitude;
        $this->altitude = $uneAltitude;
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getLatitude() {return $this->latitude;}
    public function setLatitude($uneLatitude) {$this->latitude = $uneLatitude;}
    
    public function getLongitude() {return $this->longitude;}
    public function setLongitude($uneLongitude) {$this->longitude = $uneLongitude;}
    
    public function getAltitude() {return $this->altitude;}
    public function setAltitude($uneAltitude) {$this->altitude = $uneAltitude;}
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- M�thodes d'instances ----------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Fournit une chaine contenant toutes les donn�es de l'objet
    public function toString() {
        $msg = "latitude : " . $this->latitude . "<br>";
        $msg .= "longitude : " . $this->longitude . "<br>";
        $msg .= "altitude : " . $this->altitude . "<br>";
        return $msg;
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- M�thodes statiques ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    // M�thode statique priv�e
    // calcule la distance (en Km) entre 2 points g�ographiques pass�s avec 4 param�tres :
    // $latitude1 : latitude point 1 (en degr�s d�cimaux)
    // $longitude1 : longitude point 1 (en degr�s d�cimaux)
    // $latitude2 : latitude point 2 (en degr�s d�cimaux)
    // $longitude2 : longitude point 2 (en degr�s d�cimaux)
    // fournit : la distance (en Km) entre les 2 points
    private static function getDistanceBetween ($latitude1, $longitude1, $latitude2, $longitude2) {
        if (abs($latitude1 - $latitude2) < 0.000001 && abs($longitude1 - $longitude2) < 0.000001) return 0;
        try
        { $a = pi() / 180;
        $latitude1 = $latitude1 * $a;
        $latitude2 = $latitude2 * $a;
        $longitude1 = $longitude1 * $a;
        
        $longitude2 = $longitude2 * $a;
        $t1 = sin($latitude1) * sin($latitude2);
        $t2 = cos($latitude1) * cos($latitude2);
        $t3 = cos($longitude1 - $longitude2);
        $t4 = $t2 * $t3;
        $t5 = $t1 + $t4;
        $rad_dist = atan(-$t5 / sqrt(-$t5 * $t5 + 1)) + 2 * atan(1);
        return ($rad_dist * 3437.74677 * 1.1508) * 1.6093470878864446;
        }
        catch (Exception $ex)
        { return 0;
        }
    }
    
    // M�thode statique publique
    // calcule la distance (en Km) entre 2 points g�ographiques pass�s en param�tres :
    // point1 : le premier point
    // point2 : le second point
    // fournit : la distance (en Km) entre les 2 points
    public static function getDistance (Point $point1, Point $point2) {
        return Point::getDistanceBetween($point1->latitude, $point1->longitude, $point2->latitude, $point2->longitude ) ;
    }
    
} // fin de la classe Point
// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces apr�s la balise de fin de script !!!!!!!!!!!!