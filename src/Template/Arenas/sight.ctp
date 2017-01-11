<?php $this->assign('title', 'Sight');?>

<html>
    <head>
        <title> Fighter </title>
        <meta charset="utf-8" />
        <?php echo $this->Html->css('sight.css'); ?>
    </head>
    <body>
        <section id="sight-arena">
            <?php 
                $indexedList=array();
                $surrounds=array();
                foreach($f_list as $f)
                    $indexedList[$f->coordinate_x][$f->coordinate_y]=$f;
                foreach ($pillar_list as $pillar){
                    $surrounds[$pillar->coordinate_x][$pillar->coordinate_y]=$pillar;
                }
                    
                $width=15;
                $height=10;
                echo '<table id="arena">';
                for($x=0;$x<$width;$x++){
                    echo '<tr>';
                    for($y=0;$y<$height;$y++){
                        if(abs($myfighter->coordinate_x - $x) + abs($myfighter->coordinate_y - $y) <= $myfighter->skill_sight)
                        {
                            echo '<td class="cell colored" id="'.$x.'.'.$y.'">';
                        }
                        else
                        {
                            echo '<td class="cell" id="'.$x.'.'.$y.'">';
                        }
                        if(isset($indexedList[$x][$y]))
                        {
                            //echo $indexedList[$x][$y]->name.'</br>';
                            echo '<span class="element-infos">';
                            echo "Name: ".$indexedList[$x][$y]->name;
                            echo "</br>Level: ".$indexedList[$x][$y]->level;
                            echo "</br>Health: ".$indexedList[$x][$y]->current_health;
                            echo "</br>Strength: ".$indexedList[$x][$y]->skill_strength;
                            echo '</span>';
                            echo '<img class="avatar_arena" src="./../webroot/img/fighter_'.$indexedList[$x][$y]->id.'.jpg" alt="avatar" />';
                            
                        }
                        if(isset($surrounds[$x][$y])){
                            echo '<img class="avatar_arena" src="./../webroot/img/pillar.png" alt="pillar" />';
                        }
                        if($x==$myfighter->coordinate_x && $y==$myfighter->coordinate_y && $monster==1){
                            echo 'Smell</br>';
                        }
                        if($x==$myfighter->coordinate_x && $y==$myfighter->coordinate_y && $trap==1){
                            echo 'Breeze</br>';
                        }
                        //else echo '.';
                        echo '</td>';
                    }
                    echo '</tr>';
                }
                
                echo '</table>';
            ?>
        </section>
        <section id="player_infos">
            
        </section>
        <section id="sight-controllers">
            <section id="sight-move">
                <h2>Move</h2>
                <!--AJOUTS-->
                <table>
                    <tr>
                        <td><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'moveDown']);
                echo $this->Form->button('DOWN',['type' => 'submit']);
                echo $this->Form->end();
                ?></td>

                        <td><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'moveUp']);
                echo $this->Form->button('UP',['type' => 'submit']);
                echo $this->Form->end();
                ?></td>
                        <td>
                <?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'moveLeft']);
                echo $this->Form->button('LEFT',['type' => 'submit']);
                echo $this->Form->end();
                ?></td>

                <td><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'moveRight']);
                echo $this->Form->button('RIGHT',['type' => 'submit']);
                echo $this->Form->end();
                ?></td></tr></table>

                <!--AJOUTS-->
            </section>
            <section id="sight-attack">
                <h2>Attack</h2>
                <table>
                    <tr>
                        <td><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'attackDown']);
                echo $this->Form->button('DOWN',['type' => 'submit']);
                echo $this->Form->end();
                ?></td>

                        <td><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'attackUp']);
                echo $this->Form->button('UP',['type' => 'submit']);
                echo $this->Form->end();
                ?></td>
                        <td>
                <?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'attackLeft']);
                echo $this->Form->button('LEFT',['type' => 'submit']);
                echo $this->Form->end();
                ?></td>

                <td><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'attackRight']);
                echo $this->Form->button('RIGHT',['type' => 'submit']);
                echo $this->Form->end();
                ?></td></tr></table>
            </section>
            <section>
                <h2>Event</h2><?php 
                echo $this->Form->create();
                echo $this->Form->hidden('process', ['value' => 'shout']);
                echo $this->Form->input('event', array('label'=>"Event description", 'type' => 'text', 'required' => 'true'));
                echo $this->Form->button('SHOUT',['type' => 'submit']);
                echo $this->Form->end();
                ?>
            </section>
        </section>
    </body>
</html>