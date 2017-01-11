<?php $this->assign('title', 'Messages');?>

<html>
    <head>
        <title> Messages </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <section>
            <h2>Messages</h2><?php
            echo '<table>';
                echo '<tr>';
                    echo '<th>From</th>';
                    echo '<th>To</th>';
                    echo '<th>Title</th>';
                    echo '<th>Date</th>';
                    echo '<th>Message</th>';
                echo '</tr>';
                foreach($messages as $m)
                {
                    echo '<tr>';
                        echo '<td>';
                            foreach($allFighters as $f)
                            {
                                if($f->id==$m->fighter_id_from)
                                {
                                    echo $f->name;
                                }
                            }
                        echo '</td>';
                        echo '<td>';
                            foreach($allFighters as $f)
                            {
                                if($f->id==$m->fighter_id)
                                {
                                    echo $f->name;
                                }
                            }
                        echo '</td>';
                        echo '<td>';
                            echo $m->title;
                        echo '</td>';
                        echo '<td>';
                            echo $m->date;
                        echo '</td>';
                        echo '<td>';
                            echo $m->message;
                        echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                ?>
        </section>
        <hr>
        <section>
            <h2>Send message</h2><?php 
            echo $this->Form->create();
            $tabFighters = array();
            foreach($allFighters as $f)
            {
                $tabFighters[$f->id] = $f->name;
            }
            echo $this->Form->select('receiver',$tabFighters);
            echo $this->Form->input('title', array('label'=>"Title", 'type' => 'text'));
            echo $this->Form->input('message', array('label'=>"Message", 'type' => 'textarea', 'required' => 'true'));
            echo $this->Form->button('SEND',['type' => 'submit']);
            echo $this->Form->end();
            ?>
        </section>
    </body>
</html>

