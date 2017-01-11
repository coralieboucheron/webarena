<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Security;

/**
 * Description of LoginTable
 *
 * @author coralieboucheron
 */
class PlayersTable extends Table {

    public function connectplayer($user) {
        $player = $this->newEntity();
        $result = $this->find('all')
                ->where(['email' => $user['email']])
                ->first();
        if ($result) { //Si un utilisateur avec la même adresse email a été trouvé dans la BDD
            $data = $result->toArray();
            $player = $this->patchEntity($player, $data);
        } else {
            $data = $this->generateinfo($user['email']);
            $player = $this->patchEntity($player, $data);
            if (!$this->save($player)) {
                $this->Flash->set('Erreur lors de l\'enregistrement');
                $this->redirect(['action' => 'logout']);
            }
        }
        return $player;
    }
    
    public function generateinfo($email) {
        $data = array();
        $data['email'] = $email;
        $data['id'] = Security::hash($data['email'], 'md5', false);
        $data['password'] = $this->randomPassword();
        return $data;
    }

    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
    public function resetPassword($a, $user) {
        $myuser = $this->get($user);
        $myuser->password = $a['password'];
        $this->save($myuser);
    }
}

