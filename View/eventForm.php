<?php

namespace FabBackend\View;

class eventForm
{
    public function __construct(\lw_ddd_domainEvent $domainEvent)
    {
        $this->domainEvent = $domainEvent;
        $this->view = new \lw_view(dirname(__FILE__).'/templates/formView.tpl.phtml');
    }
    
    public function render()
    {
        if ($this->domainEvent->getEventName() == "showAddFormAction" || $this->domainEvent->getEventName() == "addEventAction") {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"addEvent"));
        }
        else {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>"saveEvent", "id" => $this->domainEvent->getId()));
        }
        if ($this->domainEvent->hasEntity()) {
            $this->domainEvent->getEntity()->renderView($this->view);
        }
        return $this->view->render();
    }
}