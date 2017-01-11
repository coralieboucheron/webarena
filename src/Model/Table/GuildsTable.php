<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Description of GuildsTable
 *
 * @author Marion Cottet
 */
class GuildsTable extends Table {
    
    public function createGuild($name) {
        $guild = $this->newEntity();
        $guild->name = $name;
        $this->save($guild);
    }
}
