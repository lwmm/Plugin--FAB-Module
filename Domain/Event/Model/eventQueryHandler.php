<?php

namespace Fab\Domain\Event\Model;
use \lw_registry as lw_registry;
use \Fab\Library\fabQueryHandler as fabQueryHandler;

class eventQueryHandler extends fabQueryHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
    
    /**
     * Returns a list all saved events with all atributes in defined order
     * @return array
     */
    public function getAllEvents()
    {
        $this->db->setStatement("SELECT * FROM t:fab_tagungen ORDER BY anmeldefrist_beginn DESC ");
        return $this->db->pselect();
    }
    
    /**
     * Returns all saved data for a specific event
     * @param int $id
     * @return array
     */
    public function getEventById($id)
    {
        return $this->baseGetEntryById($id, "fab_tagungen");
    }
    
    /**
     * Returns a list of all events where a certain email is saved as responsible
     * @param string $ansprechpartner_mail
     * @return array
     */
    public function loadEventsByResponsible($ansprechpartner_mail)
    {
        return $this->baseLoadEntriesByAttributeWithOrder("fab_tagungen", "ansprechpartner_mail", "s", $ansprechpartner_mail, "anmeldefrist_beginn", "DESC");
    }
}