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
use Cake\Datasource\Exception\RecordNotFoundException;

class SurroundingsTable extends Table{
    
    public function generate_table(){
        $table=$this->find('all');
        $surrounding=$table->toArray();
        if(empty($surrounding)){
            $this->generate_monster();
            $this->generate_pillars();
            $this->generate_traps();
        }
    }
    
    public function generate_monster(){
        $monster=$this->newEntity();
        $monster->type='Monster';
        $monster->coordinate_x= rand(0,14);
        $monster->coordinate_y = rand(0,9);
        if ($this->save($monster)) {
            // The $article entity contains the id now
            $id = $monster->id;
        }
    }
    
    public function generate_traps(){
        for($i=0;$i<15;$i++){
            $trap=$this->newEntity();
            $trap->type='Trap';
            do{
                $coordX= rand(0,14);
                $coordY = rand(0,9);
            }while($this->checkCoord($coordX,$coordY)!=1);
            $trap->coordinate_x= $coordX;
            $trap->coordinate_y = $coordY;
            if ($this->save($trap)) {
                // The $article entity contains the id now
                $id = $trap->id;
            }
        }            
    }
    
    public function generate_pillars(){
        for($i=0;$i<15;$i++){
            $trap=$this->newEntity();
            $trap->type='Pillar';
            do{
                $coordX= rand(0,14);
                $coordY = rand(0,9);
            }while($this->checkCoord($coordX,$coordY)!=1);
            $trap->coordinate_x= rand(0,14);
            $trap->coordinate_y = rand(0,9);
            if ($this->save($trap)) {
                // The $article entity contains the id now
                $id = $trap->id;
            }
        }            
    }
    
    public function checkCoord($coordX,$coordY){
        $check=1;
        $query=$this->find('all');//get all surroundings
        $surroundings=$query->toArray();//put them in an array
        foreach($surroundings as $element){
            if($element->coordinate_x == $coordX && $element->coordinate_y == $coordY)
                $check=0;
        }
        return $check;
    }
    
    public function getPillars($fid){
        $fighters = TableRegistry::get("Fighters");
        $myfighter = $fighters->get($fid);
        $list= array();
        
        $query=$this->find('all');//get all fighters
        $surroundings=$query->toArray();//put them in an array
        
        foreach ($surroundings as $element) {
            
            if($element->type=='Pillar' && abs($myfighter->coordinate_x - $element->coordinate_x)+ 
               abs($myfighter->coordinate_y - $element->coordinate_y)<=$myfighter->skill_sight)
            {
                $list[]=$element;//add surrounding to list
            }
        }
        return $list;
    }
    
    public function detectedMonster($fid){
        $fighters = TableRegistry::get("Fighters");
        $myfighter = $fighters->get($fid);
        $detected=0;
        
        $query=$this->find('all');//get all fighters
        $surroundings=$query->toArray();//put them in an array
        foreach ($surroundings as $element) {
            if($element->type=='Monster' && abs($myfighter->coordinate_x - $element->coordinate_x)+ 
               abs($myfighter->coordinate_y - $element->coordinate_y)<=1)
                $detected=1;
        }
        return $detected;
    }
    
    public function detectedTrap($fid){
        $fighters = TableRegistry::get("Fighters");
        $myfighter = $fighters->get($fid);
        $detected=0;
        
        $query=$this->find('all');//get all fighters
        $surroundings=$query->toArray();//put them in an array
        foreach ($surroundings as $element) {
            if($element->type=='Trap' && abs($myfighter->coordinate_x - $element->coordinate_x)+ 
               abs($myfighter->coordinate_y - $element->coordinate_y)<=1)
                $detected=1;
        }
        return $detected;
    }
    
    public function getSurroundings($coordX,$coordY){
        $mypillar=NULL;
        $query= $this->find('all');//fetch all fighters
        $list=$query->toArray();//put results into array
        foreach ($list as $pillar) {
            //if ennemy at attack position, send ennemy datas
            if($pillar->coordinate_x == $coordX && $pillar->coordinate_y == $coordY && $pillar->type=='Pillar')
                $mypillar=$pillar;
        }
        return $mypillar;
    }
    
    public function checkTrapMonster($coordX,$coordY){
        $trapMonster=0;
        $query=$this->find('all');
        $list=$query->toArray();//put results into array
        foreach ($list as $surround) {
            if($surround->coordinate_x == $coordX && $surround->coordinate_y == $coordY &&($surround->type=="Monster" || $surround->type=="Trap")){
                $trapMonster=1;//monster or trap detected
            }
        }
        return $trapMonster;
    }
    
    public function destroyMonster($x,$y){
        $query=$this->find()->where(['type' => 'Monster']);
        $monster=$query->toArray();
        if(!empty($monster))
            echo 'OK';
        foreach($monster as $current){
            if($current->coordinate_x == $x && $current->coordinate_y == $y){
                echo 'attack ok';
                $this->delete ($current);
            }
        }
    }
}