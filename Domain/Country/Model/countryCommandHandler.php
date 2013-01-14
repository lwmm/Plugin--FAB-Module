<?php

namespace Fab\Domain\Country\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;
use \Exception as Exception;

class countryCommandHandler extends fabCommandHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * A country list will be imported from /data/countries.csv and saved into the
     * database table "fab_laender".
     * @return true/exception
     */
    public function importCountries()
    {
        $data = array();
        $values = "";
        
        $file = fopen( dirname(__FILE__)."/data/countries.csv", 'r');
        while (($line = fgetcsv($file,100,";")) !== FALSE) {
            array_push($data, $line);
        }
        fclose($file);
        
        foreach ($data as $value){
            $values .= "('".$value[0]."', '".$value[1]."'),";
        }
        $values = substr($values, 0, strlen($values) - 1);
        
        $this->db->setStatement("INSERT INTO t:fab_laender ( land , bezeichnung ) VALUES ".$values." "); 
        #die($this->db->prepare());
        return $this->db->pdbquery();
    }
    
    /**
     * The existance of fab_laender will be checked and created if the table is missing
     * @return true/exception
     */
    public function createTable()
    {
        $table_create_statement = "CREATE TABLE IF NOT EXISTS ".$this->db->gt("fab_laender")." (
                                  land varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  bezeichnung varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
                                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ";
        
        $ok = $this->baseCreateTable("fab_laender", $table_create_statement);
        if($ok){
            return $this->updateTable();
        }else{
            return false;
        }
    }
    
    /**
     * Execute changes for the table fab_tagungen
     * @return boolean
     */
    public function updateTable()
    {
        return true;
        /*
         * Wenn es noch keine Erweiterung gibt, dann true zurückgeben
         * Für jede Erweiterung erst prüfen, ob die neue Spalte bereits vorhanden ist 
         * und wenn nicht, dann die Spalte mit "ALTER TABLE tablename ADD COLUMN ..." erstellen.
         * 
         */
    }
    
    /**
     * Switches the debug modus on/off
     * @param bool $bool
     */
    public function setDebug($bool = true)
    {
        $this->baseSetDebug($bool);
    }
}