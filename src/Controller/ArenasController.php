<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use \Cake\Network\Exception;
use Cake\Event\Event;
use Cake\Utility\Text;
use Google_Client;
use Google_Service_Oauth2;

define('GOOGLE_OAUTH_CLIENT_ID', '903834345419-6537panmm6nrmovd50jf9a47p7mcdjh0.apps.googleusercontent.com');
define('GOOGLE_OAUTH_CLIENT_SECRET', 'i8zWfmgefCvecpbCIbgBsyL5');
define('GOOGLE_OAUTH_REDIRECT_URI', 'http://localhost/webarena_group_si5-06-BG/arenas/googlecallback');

/**
 * Personal Controller
 * User personal interface
 *
 */
class ArenasController extends AppController {

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.

        $this->Auth->allow(['deconnection', 'googlecallback', 'login', 'resetpassword']);
    }

    public function index() {
        
    }

    public function login() {

        if ($this->request->is('post')) {
            $a = $this->request->data;
            $this->loadModel('Players');


            if ($a['process'] == 'login') {
                $user = $this->Auth->identify();


                //User identified
                if ($user) {
                    $this->Auth->setUser($user);
                    $id = $this->Auth->user('id');
                    $this->request->session()->write("log", $id);
                    //create surroundings if table surroundings is empty
                    $this->loadModel('Surroundings');
                    $this->Surroundings->generate_table();
                    //redirection to index
                    $this->redirect(['controller' => 'Arenas', 'action' => 'index']);
                } else {
                    //User not identified
                    $this->request->session()->write("log", 0);
                    $this->Flash->error('Not identified');
                }
            } elseif ($a['process'] == 'google') {

                $this->loadModel('Players');
                $client = new Google_Client();
                $client->setClientId(GOOGLE_OAUTH_CLIENT_ID);
                $client->setClientSecret(GOOGLE_OAUTH_CLIENT_SECRET);
                $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URI);

                $client->setScopes('email');
                $url = $client->createAuthUrl();
                $this->redirect($url);
            } elseif ($a['process'] == 'subscribe') {
                $user = $this->Players->newEntity();
                if ($this->request->is('post')) {
                    $user = $this->Players->patchEntity($user, $this->request->data);
                    if ($this->Players->save($user)) {
                        $user = $this->Auth->identify();
                        $this->Auth->setUser($user);
                        $id = $this->Auth->user('id');
                        $this->request->session()->write("log", $id);
                        $this->Flash->success('You are well registered');
                        //create surroundings if table surroundings is empty
                        $this->loadModel('Surroundings');
                        $this->Surroundings->generate_table();
                        //redirection to create fighter
                        $this->redirect(['controller' => 'Arenas', 'action' => 'createfighter']);
                    } else {
                        $this->Flash->error('Error, please try again');
                    }
                }
                $this->set(compact('user'));
                $this->set('_serialize', ['user']);
            }
        }
    }
    
    public function resetpassword() {
        if ($this->request->is('post')) {
            $a = $this->request->data;
            $this->loadModel('Players');
            
            $query = $this->Players->find('list', array('champs' => 'email'))
                ->where(['email' => $a['email']]);
            $number = $query->count();
            $user = $query->first();
            
            if ($number != 0) {
                if ($a['password'] == $a['confirmpassword']) {
                    $this->Players->resetPassword($a, $user);
                    $this->Flash->success('Your password has been modified');
                } else {
                    $this->Flash->error('The passwords entered do not correspond');
                }
            } else {
                $this->Flash->error('This email does not belong to any user');
            }
        }
    }
    
    public function googlecallback() {
        
        $session = $this->request->session();
        $TPlayer = TableRegistry::get('Players');
        $player = $TPlayer->newEntity();
        $this->loadModel('Players');
        
        $user = $this->Auth->identify();
        $this->Auth->setUser($user);
          
        
        $client = new Google_Client();
        $client->setClientId(GOOGLE_OAUTH_CLIENT_ID);
        $client->setClientSecret(GOOGLE_OAUTH_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_OAUTH_REDIRECT_URI);

        $client->setScopes('email');
        $client->setApprovalPrompt('auto');

        if (isset($this->request->query['code'])) {
            $client->authenticate($this->request->query['code']);
            $session->write('access_token', $client->getAccessToken());
        }

        if ($session->check('access_token') && ($session->read('access_token'))) {
            $client->setAccessToken($session->read('access_token'));
        }
        if ($client->getAccessToken()) {
            $session->write('access_token', $client->getAccessToken());
            $oauth2 = new Google_Service_Oauth2($client);
            $user = $oauth2->userinfo->get();
            
            
            try {
                if (!empty($user)) {
                   
                    $session->start();
                    $player = $TPlayer->connectplayer($user);
                    $this->Auth->setUser($player);
                    $this->request->session()->write('email', $player['email']);
                    $this->request->session()->write('id', $player['id']);
                    $this->redirect(['controller' => 'Arenas', 'action' => 'createfighter']);
                } else {
          
                    $this->Flash->set('Erreur les informations Google n\'ont pas été trouvée');
                    $this->redirect(['action' => 'login']);
                }
            } catch (\Exception $e) {
                $this->Flash->set('Grosse erreur Google, ca craint');
                return $this->redirect(['action' => 'login']);
            }
        }
        $this->redirect(['action' => 'login']);
    }

    public function fighter() {
        $this->loadModel('Fighters');
        $id = $this->Auth->user('id');
        $fighter = $this->Fighters->find()
                ->where(['player_id' => $id])
                ->first();

        if ($this->request->is('post')) {
            $a = $this->request->data;
            // --- MOVE --- \\
            if ($a['process'] == 'sightUp') {
                $this->loadModel("Fighters");
                $this->Fighters->levelUp($fighter['id'], 1);
            } else if ($a['process'] == 'strengthUp') {
                $this->loadModel("Fighters");
                $this->Fighters->levelUp($fighter['id'], 2);
            } else if ($a['process'] == 'healthUp') {
                $this->loadModel("Fighters");
                $this->Fighters->levelUp($fighter['id'], 3);
            }
        }
        $this->loadModel('Fighters');
        $fighterlist = $this->Fighters->find('all', array(
                    'champs' => array('name', 'coordinate_x', 'coordinate_y', 'level', 'xp', 'skill_sight', 'skill_strengh', 'skill_health', 'current_health')
                ))->where(['player_id' => $id]);
        $this->set('fight_field', $fighterlist);
        $this->set('id', $id);
    }

    public function sight() {
        $death = 0;
        $this->loadModel('Fighters');
        $id = $this->Auth->user('id');
        $fighter = $this->Fighters->find()
                ->where(['player_id' => $id])
                ->first();
        $fighter_bis = $this->Fighters->find()
                ->where(['player_id' => $id]);
        $number = $fighter_bis->count();

        if ($number != 0) {

            if ($this->request->is('post')) {
                $a = $this->request->data;
                // --- MOVE --- \\
                if ($a['process'] == 'moveUp') {
                    $this->loadModel("Fighters");
                    $death = $this->Fighters->upX($fighter['id']);
                } elseif ($a['process'] == 'moveDown') {
                    $this->loadModel("Fighters");
                    $death = $this->Fighters->downX($fighter['id']);
                } elseif ($a['process'] == 'moveRight') {
                    $this->loadModel("Fighters");
                    $death = $this->Fighters->upY($fighter['id']);
                } elseif ($a['process'] == 'moveLeft') {
                    $this->loadModel("Fighters");
                    $death = $this->Fighters->downY($fighter['id']);
                }
                // --- ATTACK --- \\
                elseif ($a['process'] == 'attackUp') {
                    $this->loadModel("Fighters");
                    $this->Fighters->attack($fighter['id'], -1, 0);
                } elseif ($a['process'] == 'attackDown') {
                    $this->loadModel("Fighters");
                    $this->Fighters->attack($fighter['id'], 1, 0);
                } elseif ($a['process'] == 'attackRight') {
                    $this->loadModel("Fighters");
                    $this->Fighters->attack($fighter['id'], 0, 1);
                } elseif ($a['process'] == 'attackLeft') {
                    $this->loadModel("Fighters");
                    $this->Fighters->attack($fighter['id'], 0, -1);
                }
                // --- EVENT --- \\
                elseif ($a['process'] == 'shout') {
                    $this->loadModel("Events");
                    $this->Events->createEvent($fighter['id'], $a['event']);
                }
            }

            if ($death != 0) {
                $this->Flash->error("Sorry, you are dead");
                $this->redirect(['controller' => 'Arenas', 'action' => 'createfighter']);
            } else {
                $this->loadModel('Fighters');
                $this->set("f_list", $this->Fighters->getSight($fighter['id']));
                $this->set("myfighter", $this->Fighters->getFighter($fighter['id']));

                $this->loadModel('Surroundings');
                $this->set("pillar_list", $this->Surroundings->getPillars($fighter['id']));
                $this->set("monster", $this->Surroundings->detectedMonster($fighter['id']));
                $this->set("trap", $this->Surroundings->detectedTrap($fighter['id']));
            }
        } else {
            $this->Flash->error("Sorry, you are dead");
            $this->redirect(['controller' => 'Arenas', 'action' => 'createfighter']);
        }
    }

    public function diary() {

        $this->loadModel('Fighters');
        $id = $this->Auth->user('id');
        $fighter = $this->Fighters->find()
                ->where(['player_id' => $id])
                ->first();

        $this->loadModel('Events');
        $this->set("event_list", $this->Events->getEvents($fighter['id']));
        $this->loadModel('Fighters');
        $this->set("myfighter", $this->Fighters->getFighter($fighter['id']));
    }

    public function createfighter() {
       

        if ($this->request->is('post')) {
            $b = $this->request->data;
             $id = $this->Auth->user('id');
            $this->loadModel('Fighters');

            if ($b['process'] == 'create') {

                $query = $this->Fighters->find('list', array('champs' => 'player_id'))
                        ->where(['player_id' => $id]);
                $number = $query->count();
                if ($number == 0) {
                    $a = $this->request->data;
                    if (!empty($a)) {
                        if (!empty($a['avatar']['name']) && !empty($a['fightername'])) {

                            // ---- Creating new fighter into the database ----
                            $this->Fighters->setFighter($a, $id);
                            $this->redirect(['controller' => 'Arenas', 'action' => 'fighter']);
                        }
                    } else {
                        $this->redirect(['controller' => 'Arenas', 'action' => 'fighter']);
                    }
                }
            } elseif ($b['process'] == 'already') {
                $this->redirect(['controller' => 'Arenas', 'action' => 'index']);
            }
        }
    }

    public function guild() {


        $id = $this->Auth->user('id');
        $this->loadModel('Guilds');
        $guildlist = $this->Guilds->find('all', array(
            'champs' => array('id', 'name')
        ));
        $this->set('guild_field', $guildlist);

        $this->loadModel('Fighters');
        $fighterlist = $this->Fighters->find('all', array(
            'fighter' => 'name'
        ));

        $array = [];
        foreach ($guildlist as $gf) {
            array_push($array, $gf['id']);
            $array[$gf['id']] = [];
            foreach ($fighterlist as $ff) {
                if ($ff['guild_id'] == $gf['id']) {
                    array_push($array[$gf['id']], $ff['name']);
                }
            }
        }
        $this->set('guild_members', $array);

        if ($this->request->is('post')) {
            $a = $this->request->data;

            // Create a guild
            if ($a['process'] == 'create') {
                $this->Guilds->createGuild($a['guildname']);
            } else {
                // Join a guild
                $this->loadModel('Fighters');
                $fighter = $this->Fighters->find()
                        ->where(['player_id' => $id])
                        ->first();
                $this->Fighters->joinGuild($fighter, $a['process']);
            }
        }
    }

    public function deconnection() {
        $this->request->session()
                ->destroy('access_token');
        $this->Flash->success('You are now logged out');
        $this->redirect(['controller' => 'Arenas', 'action' => 'login']);
    }

    public function messages() {

        $id = $this->Auth->user('id');

        if ($this->request->is('post')) {
            $a = $this->request->data;

            $this->loadModel('Messages');
            $this->Messages->sendMessage($id, $a['receiver'], $a['title'], $a['message']);
        }

        $this->loadModel('Fighters');
        $this->set("allFighters", $this->Fighters->getAllFighters());
        $this->loadModel('Messages');
        $this->set("messages", $this->Messages->getMessages($id));
    }

}
