<?php

namespace Fab\Domain\Participant\Model;
use \Fab\Library\fabQueryHandler as fabQueryHandler;

class participantQueryHandler extends fabQueryHandler
{
    public function __construct($db)
    {
        parent::__construct($db);
    }
    
    /**
     * Returns a list of all paticipants of a certain event
     * @param int $event_id
     * @return array
     */
    public function loadParticipantsByEvent($event_id)
    {
        if (intval($event_id)>0) {
            return $this->baseLoadEntriesByAttributeWithOrder("fab_teilnehmer", "event_id", "i", $event_id, "nachname", "ASC");
        }
        else {
            throw new \Exception("no valid EventId available!");
        }
    }

    /**
     * Returns all saved data for a specific participant
     * @param int $id
     * @return array
     */
    public function loadParticipantById($id)
    {
        return $this->baseGetEntryById($id, "fab_teilnehmer");
    }
}