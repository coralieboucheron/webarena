<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><a href=""><?= $this->fetch('title') ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                <li><?php echo $this->Html->link('Index', array('controller' => 'Arenas', 'action'=> 'index')); ?></li>
                <li><?php echo $this->Html->link('Fighter', array('controller' => 'Arenas', 'action'=> 'fighter')); ?></li>
                <li><?php echo $this->Html->link('Sight', array('controller' => 'Arenas', 'action'=> 'sight')); ?></li>
                <li><?php echo $this->Html->link('Diary', array('controller' => 'Arenas', 'action'=> 'diary')); ?></li>
                <li><?php echo $this->Html->link('Guild', array('controller' => 'Arenas', 'action'=> 'guild')); ?></li>
                <li><?php echo $this->Html->link('Messages', array('controller' => 'Arenas', 'action'=> 'messages')); ?></li>
                <li><?php echo $this->Html->link('Logout', array('controller' => 'Arenas', 'action'=> 'deconnection')); ?></li>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
        <p> Group : SI5-06 -- Options : B & G & D</p>
        <p> Author : BOUCHERON Coralie - COTTET Marion - JOLLY Marion - MOTEL Augustin </p>
        <p><a href="/webarena_group_SI5-06-BG/webroot/files/versions.log">Fichier de log du versioning</a><p>
        <p><a href="https://webarenaSI0506BG.valars.com">Site en ligne</a><p>
    </footer>
</body>
</html>
