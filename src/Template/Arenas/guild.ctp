<html>
    
    <?php $this->assign('title', 'Guilds overview');?>
    
    <?php
        foreach($guild_field as $g) {
    ?>
        
        <table> <tr>
                <td> <h1> Guild <?php echo $g['name']; ?> </h1> </td>
                <td> <?php 
                    echo $this->Form->create();
                    echo $this->Form->hidden('process', array('value' => $g['id']));
                    echo $this->Form->button('Join');
                    echo $this->Form->end();
                ?> </td>
        </tr> </table>
        <h2> Fighters in the guild : </h2>
        <ul>
            <?php foreach (array_keys($guild_members) as $key) {
                    if ($key == $g['id']) {
                        foreach (array_keys($guild_members[$key]) as $k) {
            ?>
                <li> <?php echo $guild_members[$key][$k]; ?> </li>
            <?php       }
                    }
                }
            ?>
        </ul>
              
            <?php } ?>
    
    <h1> Create a new guild </h1>
    <?php echo $this->Form->create('NewGuild'); ?>
        <?php echo $this->Form->hidden('process', array('value'=>"create")); ?>
        <?php echo $this->Form->input('guildname', array('label'=>"Name", 'type' => 'text')); ?>
        <?php echo $this->Form->button('Submit'); ?>
    <?php echo $this->Form->end(); ?>
    
</html>

