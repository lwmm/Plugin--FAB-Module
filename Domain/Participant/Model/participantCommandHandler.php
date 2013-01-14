<?php

namespace Fab\Domain\Participant\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;
use \Exception as Exception;

class participantCommandHandler extends fabCommandHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
  
    /**
     * Creation of a new participant for a certain event
     * @param int $event_id
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function addParticipant($event_id, ValueObject $entity)
    {
        $this->db->setStatement("INSERT INTO t:fab_teilnehmer ( event_id, anrede, sprache, titel, nachname, vorname, institut, unternehmen, strasse, plz, ort, land, mail, ust_id_nr, zahlweise, teilnehmer_intern, betrag, first_date, last_date ) VALUES ( :event_id, :anrede, :sprache, :titel, :nachname, :vorname, :institut, :unternehmen, :strasse, :plz, :ort, :land, :mail, :ust_id_nr, :zahlweise, :teilnehmer_intern, :betrag, :first_date, :last_date ) ");
        $this->db->bindParameter("event_id", "i", $event_id);
        $this->db->bindParameter("anrede", "s", $entity->getValueByKey('anrede'));
        $this->db->bindParameter("sprache", "s", $entity->getValueByKey('sprache'));
        $this->db->bindParameter("titel", "s", $entity->getValueByKey('titel'));
        $this->db->bindParameter("nachname", "s", $entity->getValueByKey('nachname'));
        $this->db->bindParameter("vorname", "s", $entity->getValueByKey('vorname'));
        $this->db->bindParameter("institut", "s", $entity->getValueByKey('institut'));
        $this->db->bindParameter("unternehmen", "s", $entity->getValueByKey('unternehmen'));
        $this->db->bindParameter("strasse", "s", $entity->getValueByKey('strasse'));
        $this->db->bindParameter("plz", "s", $entity->getValueByKey('plz'));
        $this->db->bindParameter("ort", "s", $entity->getValueByKey('ort'));
        $this->db->bindParameter("land", "s", $entity->getValueByKey('land'));
        $this->db->bindParameter("mail", "s", $entity->getValueByKey('mail'));
        $this->db->bindParameter("ust_id_nr", "s", $entity->getValueByKey('ust_id_nr'));
        $this->db->bindParameter("zahlweise", "s", $entity->getValueByKey('zahlweise'));
        $this->db->bindParameter("teilnehmer_intern", "i", $entity->getValueByKey('teilnehmer_intern'));
        $this->db->bindParameter("betrag", "s", $entity->getValueByKey('betrag'));
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));

        return $this->basePdbinsert("fab_teilnehmer");
    }
    
    /**
     * Saving changes for a certain participant
     * @param int $id
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function saveParticipant($id, ValueObject $entity)
    {
        $this->db->setStatement("UPDATE t:fab_teilnehmer SET anrede = :anrede, sprache = :sprache, titel = :titel, nachname = :nachname, vorname = :vorname, institut = :institut, unternehmen = :unternehmen, strasse = :strasse, plz = :plz, ort = :ort, land = :land, mail = :mail, ust_id_nr = :ust_id_nr, zahlweise = :zahlweise, teilnehmer_intern = :teilnehmer_intern, betrag = :betrag, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("anrede", "s", $entity->getValueByKey('anrede'));
        $this->db->bindParameter("sprache", "s", $entity->getValueByKey('sprache'));
        $this->db->bindParameter("titel", "s", $entity->getValueByKey('titel'));
        $this->db->bindParameter("nachname", "s", $entity->getValueByKey('nachname'));
        $this->db->bindParameter("vorname", "s", $entity->getValueByKey('vorname'));
        $this->db->bindParameter("institut", "s", $entity->getValueByKey('institut'));
        $this->db->bindParameter("unternehmen", "s", $entity->getValueByKey('unternehmen'));
        $this->db->bindParameter("strasse", "s", $entity->getValueByKey('strasse'));
        $this->db->bindParameter("plz", "s", $entity->getValueByKey('plz'));
        $this->db->bindParameter("ort", "s", $entity->getValueByKey('ort'));
        $this->db->bindParameter("land", "s", $entity->getValueByKey('land'));
        $this->db->bindParameter("mail", "s", $entity->getValueByKey('mail'));
        $this->db->bindParameter("ust_id_nr", "s", $entity->getValueByKey('ust_id_nr'));
        $this->db->bindParameter("zahlweise", "s", $entity->getValueByKey('zahlweise'));
        $this->db->bindParameter("teilnehmer_intern", "i", $entity->getValueByKey('teilnehmer_intern'));
        $this->db->bindParameter("betrag", "s", $entity->getValueByKey('betrag'));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));

        return $this->basePdbquery();
    }
    
    /**
     * 
     * @param \LWddd\Entity $entity
     * @return true/exception
     */
    public function deleteParticipant(Entity $entity)
    {
        return $this->baseDelete($entity, "fab_teilnehmer");                              
    }
    
    /**
     * The existance of fab_teilnehmer will be checked and created if the table is missing
     * @return true/exception
     */
    public function createTable()
    {
        $table_create_statement = "CREATE TABLE IF NOT EXISTS ".$this->db->gt("fab_teilnehmer")." (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  event_id int(11) NOT NULL,
                                  anrede varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  sprache varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  titel varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  nachname varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  vorname varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  institut varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  unternehmen varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  strasse varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  plz varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ort varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  land varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  mail varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ust_id_nr varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  zahlweise varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  teilnehmer_intern int(1) NOT NULL,
                                  betrag varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  first_date bigint(14) NOT NULL,
                                  last_date bigint(14) NOT NULL,
                                  PRIMARY KEY (id)
                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
        
        $ok = $this->baseCreateTable("fab_teilnehmer", $table_create_statement);
        if($ok){
            return $this->updateTable();    
        }else{
            return false;
        }
    }
    
    /**
     * Execute changes for the table fab_teilnehmer
     * @return boolean
     */
    public function updateTable()
    {
        return true;
        /*
         * Wenn es noch keine Erweiterung gibt, dann true zurÃ¼ckgeben
         * FÃ¼r jede Erweiterung erst prÃ¼fen, ob die neue Spalte bereits vorhanden ist 
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