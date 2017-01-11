<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FightersTable
 *
 * @author Marion
 */


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

class FightersTable extends Table
{
    public function getFighter($fid)
    {
        try{
            $myfighter = $this->get($fid);
            return $myfighter;
        }catch(RecordNotFoundException $e){
            return null;
        }
    }
    
    public function getAllFighters()
    {
        $fightersList = array();//array of fighters
        $query = $this->find('all');
        $fightersList = $query->toArray();
        return $fightersList;
    }
    
    public function getSight($fid)
    {
        //get current fighter and set border for the manhattan distance
        $myfighter= $this->get($fid);
        $sightlist=array();//array which will contain 
        
        $query=$this->find('all');//get all fighters
        $ennemylist=$query->toArray();//put them in an array
        foreach ($ennemylist as $current) {
            
            if(abs($myfighter->coordinate_x - $current->coordinate_x)+ 
               abs($myfighter->coordinate_y - $current->coordinate_y)<=$myfighter->skill_sight)
            {
                $sightlist[]=$current;//add fighter to sight list if he is in the sight field
            }
        }
        
        return $sightlist;
    }
    
    public function upX($fid)
    {
        try{
            $myfighter=$this->get($fid);
            $check=$this->checkNextCell($myfighter->coordinate_x-1,$myfighter->coordinate_y);
            if($myfighter->coordinate_x>0 && $check==1)
                $myfighter->coordinate_x--;
            $this->save($myfighter);
            $death=0;
            $surroundings = TableRegistry::get("Surroundings");
            $death=$surroundings->checkTrapMonster($myfighter->coordinate_x,$myfighter->coordinate_y);
            if($death!=0){
                $myfighter->current_health=0;
                $events = TableRegistry::get("Events"); //get Events table
                $events->createEvent($fid,$myfighter->name." was killed by a trap or a monster ");
                $this->delete($myfighter);
            }
        }catch(RecordNotFoundException $e){
            
        }
        return $death;
    }
    
    public function downX($fid)
    {
        try{
            $myfighter=$this->get($fid);
            $check=$this->checkNextCell($myfighter->coordinate_x+1,$myfighter->coordinate_y);
            if($myfighter->coordinate_x<14 && $check==1)
                $myfighter->coordinate_x++;
            $this->save($myfighter);
            $death=0;
            $surroundings = TableRegistry::get("Surroundings");
            $death=$surroundings->checkTrapMonster($myfighter->coordinate_x,$myfighter->coordinate_y);
            if($death!=0){
                $myfighter->current_health=0;
                $events = TableRegistry::get("Events"); //get Events table
                $events->createEvent($fid,$myfighter->name." was killed by a trap or a monster ");
                $this->delete($myfighter);
            }
        }catch(RecordNotFoundException $e){
            
        }
        return $death;
    }
    
    public function upY($fid)
    {
        try{
            $myfighter=$this->get($fid);
            $check=$this->checkNextCell($myfighter->coordinate_x,$myfighter->coordinate_y+1);
            if($myfighter->coordinate_y<9 && $check==1)
                $myfighter->coordinate_y++;
            $this->save($myfighter);
            $death=0;
            $surroundings = TableRegistry::get("Surroundings");
            $death=$surroundings->checkTrapMonster($myfighter->coordinate_x,$myfighter->coordinate_y);
            if($death!=0){
                $myfighter->current_health=0;
                $events = TableRegistry::get("Events"); //get Events table
                $events->createEvent($fid,$myfighter->name." was killed by a trap or a monster ");
                $this->delete($myfighter);
            }
        }catch(RecordNotFoundException $e){

        }
        return $death;
    }
    
    public function downY($fid)
    {
        try{
            $myfighter=$this->get($fid);
            $check=$this->checkNextCell($myfighter->coordinate_x,$myfighter->coordinate_y-1);
            if($myfighter->coordinate_y>0 && $check==1)
                $myfighter->coordinate_y--;
            $this->save($myfighter);
            $death=0;
            $surroundings = TableRegistry::get("Surroundings");
            $death=$surroundings->checkTrapMonster($myfighter->coordinate_x,$myfighter->coordinate_y);
            if($death!=0){
                $myfighter->current_health=0;
                $events = TableRegistry::get("Events"); //get Events table
                $events->createEvent($fid,$myfighter->name." was killed by a trap or a monster ");
                $this->delete($myfighter);
            }
        }catch(RecordNotFoundException $e){
            return null;
        }
        return $death;
    }
    
    public function checkNextCell($x,$y){
        $ennemy=$this->getEnnemy($x,$y);
        $surroundings=TableRegistry::get("Surroundings");
        $pillar=$surroundings->getSurroundings($x,$y);//check if pillar at next position
        if(!empty($ennemy) || !empty($pillar)) return 0; //ennemy at the future position
        else return 1;//no ennemy at next position
    }
    
    public function attack($fid,$x,$y)
    {
        try{
            $bonus=0;
            $myfighter=$this->get($fid);//get fighter in the database

            //calculate attack coordinate
            $coordX=$myfighter->coordinate_x+$x;
            $coordY=$myfighter->coordinate_y+$y;

            $ennemy= $this->getEnnemy($coordX,$coordY);//find ennemy at the attack position


            if(!empty($ennemy))
            {
                $bonus=$this->getFriends($myfighter, $ennemy);//add attack bonus if guid's friends near ennemy
                $this->attackPerform($myfighter,$ennemy,$bonus);
            }
            else{
                $surroundings=TableRegistry::get("Surroundings");
                $surroundings->destroyMonster($coordX,$coordY);
            }
            
        }catch(RecordNotFoundException $e){
            return null;
        }
    }
    
    public function getFriends($myfighter,$myennemy){
        $friends=0;
        $query= $this->find('all');
        $list=$query->toArray();//put results into array
        foreach ($list as $player) {
            //if friends near attack position, return friends
            if($player->guild_id == $myfighter->guild_id
                    && $player->coordinate_x != $myfighter->coordinate_x && $player->coordinate_y != $myfighter->coordinate_y
                    && $player->coordinate_x < $myennemy->coordinate_x+2 && $player->coordinate_y < $myennemy->coordinate_y+2
                    && $player->coordinate_x > $myennemy->coordinate_x-2 && $player->coordinate_y > $myennemy->coordinate_y-2){
                $friends++;
                    }
        }
        return $friends;
    }
    
    public function getEnnemy($coordX,$coordY)
    {
        $myennemy=NULL;
        $query= $this->find('all');//fetch all fighters
        $list=$query->toArray();//put results into array
        foreach ($list as $ennemy) {
            //if ennemy at attack position, send ennemy datas
            if($ennemy->coordinate_x == $coordX && $ennemy->coordinate_y == $coordY)
                $myennemy=$ennemy;
        }
        return $myennemy;
    }
    
    public function attackPerform($myfighter,$ennemy,$bonus)
    {
        $events = TableRegistry::get("Events"); //get Events table
        
        $action= rand(1, 20);//generate random number between 1 and 20
        
        //if attack points are suffisants
        if($action>=10+$ennemy->level-$myfighter->level)
        {
            $ennemy->current_health=$ennemy->current_health - ($myfighter->skill_strength+$bonus);//decrease ennemy's health
            $this->save($ennemy);//save in the database new health

            //message diary for attack succeded
            $events->createEvent($myfighter->id,$myfighter->name." suceeded attack against ".$ennemy->name);
            
            $myfighter->xp++;//increase fighter experience
            //if ennemy's health points less are equal 0
            if($ennemy->current_health<=0)
            {                
                $myfighter->xp+=$ennemy->level;//increase fighter xp
                //message diary for killed ennemy
                $events->createEvent($myfighter->id,$myfighter->name." killed ".$ennemy->name);
                $this->delete($ennemy); //delete ennemy fighter (kill)
            }
            $this->save($myfighter);//save in the database new xp for fighter
        }
        else
        {
            //message diary for attack failed
             $events->createEvent($myfighter->id,$myfighter->name." failed attack against ".$ennemy->name);
        }
    }
    
    public function setFighter($a, $id) {

        // ---- Creating new fighter into the database ----
        $fighter = $this->newEntity();
        $fighter->name = $a['fightername'];
        $fighter->player_id = $id;
        $fighter->coordinate_x = rand(0,14);
        $fighter->coordinate_y = rand(0,9);
        $coordinates = array($fighter->coordinate_x, $fighter->coordinate_y);

        // Getting the coordinates of all the other fighters
        $fighterlist = $this->find('all', array(
            'champs' => array('coordinate_x', 'coordinate_y')
        ));
        $totallist=$fighterlist->toArray();
        $surroundings=TableRegistry::get("Surroundings");
        $surroundlist=$surroundings->find('all', array(
            'champs' => array('coordinate_x', 'coordinate_y')));
        //put surroundings into previous array 
        foreach($surroundlist as $current)
            array_push ($totallist, $current);
        
        // Change the coordinates of the fighter if someone is already on the case
        foreach ($totallist as $f) {
            if ($f == $coordinates) {
                $fighter->coordinate_x = rand(0,14);
                $fighter->coordinate_y = rand(0,9);
            }
        }

        $fighter->level = 1;
        $fighter->xp = 0;
        $fighter->skill_sight = 0;
        $fighter->skill_strength = 1;
        $fighter->skill_health = 3;
        $fighter->current_health = 3;
        $fighter->next_action_time = NULL;
        $fighter->guild_id = NULL;
        $this->save($fighter);
        
        $events = TableRegistry::get("Events"); //get Events table
        $events->createEvent($fighter->id,$fighter->name." entered the game ");

        // ---- Uploading the avatar into the folder ----
        $avatar = $a['avatar'];
        // Getting the extension
        $ext = substr(strtolower(strrchr($avatar['name'], '.')), 1);
        // Setting allowed image extensions
        $arr_ext = array('jpg', 'jpeg', 'gif', 'png');
        // Renaming the file
        $newFileName = "fighter_".$fighter['id'];

        // If the extension is valid
        if (in_array($ext, $arr_ext)) {
            move_uploaded_file($avatar['tmp_name'], WWW_ROOT . '/img/' . $newFileName.'.jpg');
        }
    }
    
    public function joinGuild($fighter, $id_guild) {
        $fighter->guild_id = $id_guild;
        $this->save($fighter);
    }
    
    public function levelUp($fid,$choice){
        /*$fighter = $this->Fighters->find()
                        ->where(['player_id' => $id])
                        ->first();*/
        
        $myfighter=$this->get($fid);//get fighter in the database
        if($myfighter->xp>=4){
            switch($choice){
                case 1: //sightUp
                    $myfighter->skill_sight+=1;
                    $myfighter->xp-=4;
                    $myfighter->level+=1;
                    break;
                case 2: //strengthUp
                    $myfighter->skill_strength+=1;
                    $myfighter->xp-=4;
                    $myfighter->level+=1;
                    break;
                case 3:
                    $myfighter->skill_health+=1;
                    $myfighter->xp-=4;
                    $myfighter->level+=1;
                    break;
                default:
                    break;
            }
            $this->save($myfighter);
        }
    }
}

