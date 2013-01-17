<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;
use \Exception as Exception;

class eventCommandHandler extends fabCommandHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
        
    /**
     * Creation of a new Event
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function addEvent(ValueObject $entity)
    {
        $this->db->setStatement("INSERT INTO t:fab_tagungen ( buchungskreis, v_schluessel, auftragsnr, bezeichnung, v_land, v_ort, anmeldefrist_beginn, anmeldefrist_ende, v_beginn, v_ende, cpd_konto, erloeskonto, steuerkennzeichen, steuersatz, ansprechpartner, ansprechpartner_tel, organisationseinheit, ansprechpartner_mail, stellvertreter_mail, standardbetrag, first_date, last_date ) VALUES ( :buchungskreis, :v_schluessel, :auftragsnr, :bezeichnung, :v_land, :v_ort, :anmeldefrist_beginn, :anmeldefrist_ende, :v_beginn, :v_ende, :cpd_konto, :erloeskonto, :steuerkennzeichen, :steuersatz, :ansprechpartner, :tel_ansprechpartner, :organisationseinheit, :mail_ansprechpartner, :stellvertreter_mail, :standardbetrag, :first_date, :last_date ) ");
        $this->db->bindParameter("buchungskreis", "s", $entity->getValueByKey('buchungskreis'));
        $this->db->bindParameter("v_schluessel", "s", $entity->getValueByKey('v_schluessel'));
        $this->db->bindParameter("auftragsnr", "s", $entity->getValueByKey('auftragsnr'));
        $this->db->bindParameter("bezeichnung", "s", $entity->getValueByKey('bezeichnung'));
        $this->db->bindParameter("v_land", "s", $entity->getValueByKey('v_land'));
        $this->db->bindParameter("v_ort", "s", $entity->getValueByKey('v_ort'));
        $this->db->bindParameter("anmeldefrist_beginn", "i", $entity->getValueByKey('anmeldefrist_beginn'));
        $this->db->bindParameter("anmeldefrist_ende", "i", $entity->getValueByKey('anmeldefrist_ende'));
        $this->db->bindParameter("v_beginn", "i", $entity->getValueByKey('v_beginn'));
        $this->db->bindParameter("v_ende", "i", $entity->getValueByKey('v_ende'));
        $this->db->bindParameter("cpd_konto", "s", $entity->getValueByKey('cpd_konto'));
        $this->db->bindParameter("erloeskonto", "s", $entity->getValueByKey('erloeskonto'));
        $this->db->bindParameter("steuerkennzeichen", "s", $entity->getValueByKey('steuerkennzeichen'));
        $this->db->bindParameter("steuersatz", "s", $entity->getValueByKey('steuersatz'));
        $this->db->bindParameter("ansprechpartner", "s", $entity->getValueByKey('ansprechpartner'));
        $this->db->bindParameter("tel_ansprechpartner", "i", $entity->getValueByKey('ansprechpartner_tel'));
        $this->db->bindParameter("organisationseinheit", "s", $entity->getValueByKey('organisationseinheit'));
        $this->db->bindParameter("mail_ansprechpartner", "s", $entity->getValueByKey('ansprechpartner_mail'));
        $this->db->bindParameter("stellvertreter_mail", "s", $entity->getValueByKey('stellvertreter_mail'));
        $this->db->bindParameter("standardbetrag", "s", $entity->getValueByKey('standardbetrag'));
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));

        return $this->basePdbinsert("fab_tagungen");
        
    }
    
    /**
     * An Event with certain id will be updated
     * @param int $id
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function saveEvent($id, ValueObject $entity)
    {
        $this->db->setStatement("UPDATE t:fab_tagungen SET buchungskreis = :buchungskreis, v_schluessel = :v_schluessel, auftragsnr = :auftragsnr, bezeichnung = :bezeichnung, v_land = :v_land, v_ort = :v_ort, anmeldefrist_beginn = :anmeldefrist_beginn, anmeldefrist_ende = :anmeldefrist_ende, v_beginn = :v_beginn, v_ende = :v_ende, cpd_konto = :cpd_konto, erloeskonto = :erloeskonto, steuerkennzeichen = :steuerkennzeichen, steuersatz = :steuersatz, ansprechpartner = :ansprechpartner, ansprechpartner_tel = :tel_ansprechpartner, organisationseinheit = :organisationseinheit, ansprechpartner_mail = :mail_ansprechpartner, stellvertreter_mail = :stellvertreter_mail, standardbetrag = :standardbetrag, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("buchungskreis", "s", $entity->getValueByKey('buchungskreis'));
        $this->db->bindParameter("v_schluessel", "s", $entity->getValueByKey('v_schluessel'));
        $this->db->bindParameter("auftragsnr", "s", $entity->getValueByKey('auftragsnr'));
        $this->db->bindParameter("bezeichnung", "s", $entity->getValueByKey('bezeichnung'));
        $this->db->bindParameter("v_land", "s", $entity->getValueByKey('v_land'));
        $this->db->bindParameter("v_ort", "s", $entity->getValueByKey('v_ort'));
        $this->db->bindParameter("anmeldefrist_beginn", "i", $entity->getValueByKey('anmeldefrist_beginn'));
        $this->db->bindParameter("anmeldefrist_ende", "i", $entity->getValueByKey('anmeldefrist_ende'));
        $this->db->bindParameter("v_beginn", "i", $entity->getValueByKey('v_beginn'));
        $this->db->bindParameter("v_ende", "i", $entity->getValueByKey('v_ende'));
        $this->db->bindParameter("cpd_konto", "s", $entity->getValueByKey('cpd_konto'));
        $this->db->bindParameter("erloeskonto", "s", $entity->getValueByKey('erloeskonto'));
        $this->db->bindParameter("steuerkennzeichen", "s", $entity->getValueByKey('steuerkennzeichen'));
        $this->db->bindParameter("steuersatz", "s", $entity->getValueByKey('steuersatz'));
        $this->db->bindParameter("ansprechpartner", "s", $entity->getValueByKey('ansprechpartner'));
        $this->db->bindParameter("tel_ansprechpartner", "i", $entity->getValueByKey('ansprechpartner_tel'));
        $this->db->bindParameter("organisationseinheit", "s", $entity->getValueByKey('organisationseinheit'));
        $this->db->bindParameter("mail_ansprechpartner", "s", $entity->getValueByKey('ansprechpartner_mail'));
        $this->db->bindParameter("stellvertreter_mail", "s", $entity->getValueByKey('stellvertreter_mail'));
        $this->db->bindParameter("standardbetrag", "s", $entity->getValueByKey('standardbetrag'));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));

        return $this->basePdbquery();
    }
    
    /**
     * 
     * @param \LWddd\Entity $entity
     * @return true/exception
     */
    public function deleteEvent(Entity $entity)
    {
        return $this->baseDelete($entity, "fab_tagungen");
    }

    public function saveReplacement($id, $stellvertreter_mail)
    {
        $this->db->setStatement("UPDATE t:fab_tagungen SET stellvertreter_mail = :stellvertreter_mail WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("stellvertreter_mail", "s", $stellvertreter_mail);

        return $this->basePdbquery();
    }    
    
    /**
     * The existance of fab_tagungen will be checked and created if the table is missing
     * @return true/exception
     */
    public function createTable()
    {
        $table_create_statement = "CREATE TABLE IF NOT EXISTS ".$this->db->gt("fab_tagungen")." (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  buchungskreis varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  v_schluessel varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  auftragsnr varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  bezeichnung varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  v_land varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  v_ort varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  anmeldefrist_beginn int(8) NOT NULL,
                                  anmeldefrist_ende int(8) NOT NULL,
                                  v_beginn int(8) NOT NULL,
                                  v_ende int(8) NOT NULL,
                                  cpd_konto varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  erloeskonto varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  steuerkennzeichen varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  steuersatz varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ansprechpartner varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ansprechpartner_tel varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  organisationseinheit varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  ansprechpartner_mail varchar(241) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  stellvertreter_mail varchar(241) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  standardbetrag varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  first_date bigint(14) NOT NULL,
                                  last_date bigint(14) NOT NULL,
                                  PRIMARY KEY (id),
                                  UNIQUE KEY v_schluessel (v_schluessel)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
        
        $ok = $this->baseCreateTable("fab_tagungen", $table_create_statement);
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
