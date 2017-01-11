<html>
    <?php $this->assign('title', 'Fighter');?>
    <head>
        <title> Fighter </title>
        <meta charset="utf-8" />
    </head>
    
    <body>
        
        <?php
            foreach($fight_field as $f){
        ?>
        <h1> Fighter <?php echo $f['name']; ?> </h1>
        <table id="display"> <tr>
            <td> <ul id="fighter_data">
                <li> Position : <?php echo $f['coordinate_x'].", ".$f['coordinate_y']; ?> </li>
                <li> Level : <?php echo $f['level']; ?><!--input type="button" value="Level Up" onclick="levelup();"/--></li>
                <li> Experience : <?php echo $f['xp']; ?> </li>
                <li> Within sight : <?php echo $f['skill_sight']; ?>
                    <?php 
                        echo $this->Form->create();
                        echo $this->Form->hidden('process', ['value' => 'sightUp']);
                        echo $this->Form->button('Sight Up',['type' => 'submit']);
                        echo $this->Form->end();
                    ?>
                </li>
                <li> Strength : <?php echo $f['skill_strength']; ?>
                    <?php 
                        echo $this->Form->create();
                        echo $this->Form->hidden('process', ['value' => 'strengthUp']);
                        echo $this->Form->button('Strength Up',['type' => 'submit']);
                        echo $this->Form->end();
                    ?>
                </li>
                <li> Max points of life : <?php echo $f['skill_health']; ?>
                    <?php 
                        echo $this->Form->create();
                        echo $this->Form->hidden('process', ['value' => 'healthUp']);
                        echo $this->Form->button('Health Up',['type' => 'submit']);
                        echo $this->Form->end();
                    ?>
                </li>
                <li> Current points of life : <?php echo $f['current_health']; ?> </li>
            </ul> </td>
            <?php $fid = $f['id'] ; ?>
            <td> <?php echo "<img src='./../webroot/img/fighter_$fid.jpg' alt='avatar' />" ; ?> </td>
        </tr> </table>
            <?php } ?>
    </body>
</html>