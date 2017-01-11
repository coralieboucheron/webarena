<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use DateTime;

/**
 * Description of EventsTable
 *
 * @author Marion Cottet
 */
class EventsTable extends Table{
    
    //get list of events in the current figther's sight
    public function getEvents($fid)
    {
        $fighters = TableRegistry::get("Fighters");
        $myfighter = $fighters->get($fid);
        
        $eventlistinSight=array();//array which will contain all the events in the sight fo the player
        
        $query=$this->find('all', ['order' => ['id' => 'DESC']]);//get all events
        $eventlist=$query->toArray();//put them in an array
        foreach ($eventlist as $current) { //for each event
            //if event is in the sight of the fighter
            if(abs($myfighter->coordinate_x - $current->coordinate_x)+ 
               abs($myfighter->coordinate_y - $current->coordinate_y)<=$myfighter->skill_sight)
            {
                $eventlistinSight[]=$current;//add event to the list
            }
        }
        
        return $eventlistinSight;
    }
    
    public function createEvent($fid,$description)
    {
        $fighters = TableRegistry::get("Fighters");
        $myfighter = $fighters->get($fid);
        
        $new_event = $this->newEntity();
        $new_event->name =  $description;
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime('now');
        $x = $date->format('Y-m-d H:i:s'); //get the actual time
        $new_event->date = $x;
        $new_event->coordinate_x = $myfighter->coordinate_x;
        $new_event->coordinate_y = $myfighter->coordinate_y;
        $this->save($new_event);
    }
}
