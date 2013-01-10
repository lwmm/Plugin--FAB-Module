<?php

namespace Fab\Domain\Participant\Service;

class participantValidate
{
    public function __construct()
    {
        $this->allowedKeys = array(
                "id",
                "event_id",
                "anrede",
                "sprache",
                "titel",
                "nachname",
                "vorname",
                "institut",
                "unternehmen",
                "strasse",
                "plz",
                "ort",
                "land",
                "mail",
                "veranstaltung",
                "ust_id_nr",
                "zahlweise",
                "refernznr",
                "teilnehmer_intern",
                "auftragsnr",
                "betrag");
                #"first_date",
                #"last_date");
        
        $this->errors = array();
    }

    public function setValues($array) 
    {
        $this->array = $array;
    }
    
    public function validate()
    {                   
        $valid = true;
        foreach($this->allowedKeys as $key){
            $function = $key."Validate";
            $result = $this->$function($this->array[$key]);
            if($result == false){
                $valid = false;
            }
        }
        
        return $valid;
    }
    
    private function addError($key, $number, $array=false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }
    
    public function idValidate($value){
        if(empty($value)){
            return true;
        }else{
            if(ctype_digit($value)){
                return true;
            }else{
                $this->addError("id", 1, array("errormsg" => "id darf nur aus Zahlen bestehen."));
                return false;
            }
        }
    }
    
    public function event_idValidate($value){
        if(empty($value)){
            return true;
        }else{
            if(ctype_digit($value)){
                return true;
            }else{
                $this->addError("event_id", 1, array("errormsg" => "id darf nur aus Zahlen bestehen."));
                return false;
            }
        }
    }
    
    public function anredeValidate($value)
    {
        return $this->defaultValidation("anrede",$value, 15);
    }
    
    public function spracheValidate($value)
    {
        return $this->defaultValidation("sprache", $value, 2);
    }
    
    public function titelValidate($value)
    {
        return $this->defaultValidation("titel", $value, 20);
    }
    
    public function nachnameValidate($value)
    {
        return $this->defaultValidation("nachname", $value, 35 , true);
    }
    
    public function vornameValidate($value)
    {
        return $this->defaultValidation("vorname", $value, 35 , true);
    }
    
    public function institutValidate($value)
    {
        return $this->defaultValidation("institut", $value, 35);
    }
    
    public function unternehmenValidate($value)
    {
        return $this->defaultValidation("unternehmen", $value, 35);
    }
    
    public function strasseValidate($value)
    {
        return $this->defaultValidation("strasse", $value, 30);
    }
    
    public function plzValidate($value)
    {
        if($value == ""){
            return true;
        }else{
            $zipcheck = new \Fab\Services\Zipcheck\zipcheck();
            if($this->landValidate($this->array["land"])){
                $ok = $zipcheck->check(strtoupper($this->array["land"]), $value);
                if($ok === 1){
                    return true;
                }else{
                    $this->addError("plz", 1, array("errormsg" => "unguelte PLZ"));
                    return false;
                }
            }
        }
    }

    public function ortValidate($value)
    {
        return $this->defaultValidation("ort", $value, 35, true);
    }

    public function landValidate($value)
    {
        return $this->defaultValidation("land", $value, 2);
    }

    public function mailValidate($value)
    {
        $bool = $this->requiredValidation("mail", $value);
        if($bool){
            return $this->emailValidation("mail", $value);
        }
        return false;
    }
    
    public function veranstaltungValidate($value)
    {
        return $this->defaultValidation("veranstaltung", $value, 8 , true);
    }
    
    public function ust_id_nrValidate($value)
    {
        return $this->defaultValidation("ust_id_nr", $value, 20);
    }
    
    public function zahlweiseValidate($value)
    {
        $bool = $this->defaultValidation("zahlweise", $value, 1, true);

        if(!in_array(strtoupper($value), array("K","U"))){
            $this->addError("zahlweise", 3, array("errormsg" => "unguelte Zahlweisenabkuerzung. ( K = Kreditzahlung, U = Ueberweisung )"));
            $bool = false;
        }
        
        if($bool === false){
            return false;
        }else{
            return true;
        }
    }
    
    public function referenznrValidate($value)
    {
        $bool = true;
        $bool = $this->defaultValidation("referenznr", $value, 8 , true);
        if(ctype_digit($value)){
            if($value < 400000){
                $this->addError("referenznr", 3, array("errormsg" => "Die Referenznummer beginnt bei 400000"));
                $bool = false;
            }
        }else{
            $this->addError("referenznr", 4, array("errormsg" => "Die Referenznummer besteht nicht aus Zahlen"));
            $bool = false;
        }
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function teilnehmer_internValidate($value)
    {
        if(!in_array($value, array("0","1"))){
            $this->addError("teilnehmer_intern", 1);
            return false;
        }else{
            return true;
        }
    }
    
    public function auftragsnrValidate($value)
    {
        return $this->defaultValidation("auftragsnr", $value, 12, true);
    }
    
    public function betragValidate($value)
    {
        return $this->defaultValidation("betrag", $value, 16, true);
    }

    public function first_dateValidate($value)
    {
        if(empty($value)){
            return true;
        }else{
            return $this->dateValidation("first_date", $value, true);
        }
    }
    
    public function last_dateValidate($value)
    {
        if(empty($value)){
            return true;
        }else{
            return $this->dateValidation("last_date", $value, true);
        }
    }
    
    public function defaultValidation($key,$value,$length,$required = false)
    {
        $bool = true;
        
        if($required === true){
            $bool = $this->requiredValidation($key, $value);
        }
        
        if(strlen($value) > $length){
            $this->addError($key, 2, array("errormsg" => "Die maximale Zeichenlaenge von ".$length." Zeichen ist einzuhalten."));
            $bool = false;
        }
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function requiredValidation($key, $value)
    {
        if($value == ""){
            $this->addError($key, 1, array("errormsg" => "Pflichtfeld ist auszufuellen."));
            return false;
        }
        return true;
    }
    
    public function dateValidation($key, $value, $opt_timecheck = false)
    {
        $bool = true;
        if($opt_timecheck == true){
            if(strlen($value) != 14){
                $this->addError($key, 2, array("errormsg" => "Datums- + Zeiteingabe nicht korrekt."));
                $bool = false;
            }
        }else{
            if(strlen($value) != 8){
                $this->addError($key, 2, array("errormsg" => "Eingabe nicht korrekt. Es wurden ".  strlen($value) . " Zeichen eingegeben. Das Datumsfeld muss aus 8 Zeichen bestehen YYYYMMDD"));
                $bool = false;
            }
        }
        
        if(strlen($value) >= 8){
            $year = substr($value, 0, 4);
            if($year < date("Y")){
                $this->addError($key, 3, array("errormsg" => "Ungueltiges Jahr, es darf kein vergangenes Jahr eingegeben werden."));
                $bool = false;
            }
        
            $month = substr($value, 4, 2); 
            $day   = substr($value, 6, 2);
            if(!checkdate($month, $day, $year)){
                $this->addError($key, 4, array("errormsg" => "Ungueltiges Datum."));
                $bool = false;
            }
        }
        
        if(strlen($value) == 14){
            if($opt_timecheck == true){
                $hour     = substr($value, 8, 2);
                $min      = substr($value, 10, 2);
                $sec      = substr($value, 12, 2);

                if($hour < 0 | $hour > 23){
                    $this->addError($key, 5, array("errormsg" => "Stunde existiert nicht (nur 00-23 erlaubt)."));
                    $bool = false;
                }

                if($min < 0 | $min > 59){
                    $this->addError($key, 6, array("errormsg" => "Minute existiert nicht (nur 00-59 erlaubt)."));
                    $bool = false;
                }

                if($sec < 0 | $sec > 59){
                    $this->addError($key, 7, array("errormsg" => "Sekunde existiert nicht (nur 00-59 erlaubt)."));
                    $bool = false;
                }
            }
        }
        
        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function emailValidation($key,$value)
    {
        $bool = true;

        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false){
            $this->addError($key, 2, array("errormsg" => "Es wurde keine korrekte EMail-Adresse eingegeben."));
            $bool = false;
        }

        if($bool == false){
            return false;
        }
        return true;
    }
    
    public function resetErrorKeyForTesting($key)
    {
        $this->errors[$key] = array();
    }
}