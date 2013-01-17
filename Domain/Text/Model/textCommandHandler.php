<?php

namespace Fab\Domain\Text\Model;
use \lw_registry as lw_registry;
use \LWddd\ValueObject as ValueObject;
use \LWddd\Entity as Entity;
use \Fab\Library\fabCommandHandler as fabCommandHandler;

class textCommandHandler extends fabCommandHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
    
    /**
     * Creation of a new entry
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function addText(ValueObject $entity)
    {
        $this->db->setStatement("INSERT INTO t:fab_text ( `key`, content, language, category, first_date, last_date ) VALUES ( :key, :content, :language, :category, :first_date, :last_date ) ");
        $this->db->bindParameter("key", "s", $entity->getValueByKey('key'));
        $this->db->bindParameter("content", "s", $entity->getValueByKey('content'));
        if($entity->getValueByKey('language') == ""){
            $this->db->bindParameter("language", "s", "de");
        }else{
            $this->db->bindParameter("language", "s", $entity->getValueByKey('language'));
        }
        $this->db->bindParameter("category", "s", $entity->getValueByKey('category'));
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        
        return $this->basePdbinsert("fab_text");
    }
    
    /**
     * Text-entry with certain id will be updated
     * @param int $id
     * @param \LWddd\ValueObject $entity
     * @return true/exception
     */
    public function saveText($id, ValueObject $entity)
    {
        $this->db->setStatement("UPDATE t:fab_text SET `key` = :key, content = :content, language = :language, category = :category, last_date = :last_date WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("key", "s", $entity->getValueByKey('key'));
        $this->db->bindParameter("content", "s", $entity->getValueByKey('content'));
        if($entity->getValueByKey('language') == ""){
            $this->db->bindParameter("language", "s", "de");
        }else{
            $this->db->bindParameter("language", "s", $entity->getValueByKey('language'));
        }
        $this->db->bindParameter("category", "s", $entity->getValueByKey('category'));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        
        return $this->basePdbquery();
    }
    
    /**
     * 
     * @param \LWddd\Entity $entity
     * @return true/exception
     */
    public function deleteText(Entity $entity)
    {
        return $this->baseDelete($entity, "fab_text");
    }
    
    /**
     * The existance of fab_text will be checked and created if the table is missing
     * @return true/exception
     */
    public function createTable()
    {
        $table_create_statement = "CREATE TABLE IF NOT EXISTS ".$this->db->gt("fab_text")." (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  content longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  language varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  category varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  first_date bigint(14) NOT NULL,
                                  last_date bigint(14) NOT NULL,
                                  PRIMARY KEY (id)
                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ; ";
        
        $ok = $this->baseCreateTable("fab_text", $table_create_statement);
        if($ok){
            return $this->updateTable();
        }else{
            return false;
        }
    }
    
    /**
     * Execute changes for the table fab_text
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
        return $this->baseSetDebug($bool);
    }
}