<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

/**
 * Description of ApiController
 *
 * @author Marion Cottet
 */
class ApiController extends AppController {
    
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
    
    /*
     * Récupérer la vue d'un combattant donné
     * ex : /Api/fighterview/1
     */
    public function fighterview($fid = null)
    {
        if($fid==null)
        {
            $fighterview = "no fighter id given";
            $this->set(compact('fighterview'));
            $this->set('_serialize', ['fighterview']);
        }
        else
        {
            $this->loadModel('Fighters');
            $fighter = $this->Fighters->getFighter($fid);
            
            if(empty($fighter))
            {
                $fighterview = "no fighter corresponding to given id";
                $this->set(compact('fighterview'));
                $this->set('_serialize', ['fighterview']);
            }
            else
            {
                $fighterview = $fighter->skill_sight;
                $this->set(compact('fighterview'));
                $this->set('_serialize', ['fighterview']);
            }
        }    
    }
    
    /*
     * Déplacer un combattant
     * ex : Api/fighterdomove/1/north
     */
    public function fighterdomove($fid,$direction)
    {
        $this->loadModel('Fighters');
        if($direction=='north')
        {
            $this->Fighters->upX($fid);
            $message = "Do move north done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
        if($direction=='sud')
        {
            $this->Fighters->downX($fid);
            $message = "Do move sud done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
        if($direction=='east')
        {
            $this->Fighters->upY($fid);
            $message = "Do move east done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
        if($direction=='west')
        {
            $this->Fighters->downY($fid);
            $message = "Do move west done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
    }
    
    /*
     * Attaquer dans une direction
     * ex : Api/fighterdoattack/1/north
     */
    public function fighterdoattack($fid,$direction)
    {
        $this->loadModel('Fighters');
        if($direction=='north')
        {
            $this->Fighters->attack($fid, -1, 0);
            $message = "Attack north done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
        if($direction=='sud')
        {
            $this->Fighters->attack($fid, 1, 0);
            $message = "Attack sud done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
        if($direction=='east')
        {
            $this->Fighters->attack($fid, 0, 1);
            $message = "Attack east done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
        if($direction=='west')
        {
            $this->Fighters->attack($fid, 0, -1);
            $message = "Attack west done";
            $this->set(compact('message'));
            $this->set('_serialize', ['message']);
        }
    }
}
