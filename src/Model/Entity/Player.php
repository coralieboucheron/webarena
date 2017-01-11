<?php
namespace App\Model\Entity;
use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Player
 *
 * @author Marion
 */
class Player extends Entity{
    

    // Make all fields mass assignable for now.
    protected $_accessible = [
        '*' => true,
        'id'=>false];
    // ...
    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
    // ...

}
