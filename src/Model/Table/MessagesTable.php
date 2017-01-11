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
 * Description of MessagesTable
 *
 * @author Marion Cottet
 */
class MessagesTable extends Table{
    
    public function sendMessage($transmitter,$receiver,$title,$message){
        
        $fighters = TableRegistry::get("Fighters");
        $fighterTransmitter = $fighters->find()
                        ->where(['player_id' => $transmitter])
                        ->first();
        $fighterReceiver = $fighters->find()
                        ->where(['id' => $receiver])
                        ->first();
        
        $new_message = $this->newEntity();
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime('now');
        $x = $date->format('Y-m-d H:i:s'); //get the actual time
        $new_message->date = $x;
        $new_message->title =  $title;
        $new_message->message = $message;
        $new_message->fighter_id_from = $fighterTransmitter->id;
        $new_message->fighter_id = $fighterReceiver->id;
        $this->save($new_message);
    }
    
    public function getMessages($id)
    {
        $fighters = TableRegistry::get("Fighters");
        $myfighter = $fighters->find()
                        ->where(['player_id' => $id])
                        ->first();
        
        $messageslist=array();//array which will contain all the messages for and from the fighter 
        $query=$this->find('all', ['order' => ['date' => 'DESC']])//get all messages
                        ->where(['OR' => [['fighter_id' => $myfighter->id], ['fighter_id_from' => $myfighter->id]]]); //for or from the fighter
        $messageslist=$query->toArray();//put them in an array
        
        return $messageslist;
    }
}
