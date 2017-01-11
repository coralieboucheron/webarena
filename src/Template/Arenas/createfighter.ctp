<html>
    
    <?php $this->assign('title', 'Create a new fighter');?>
    
    <?php echo $this->Form->create('NewFighter', ['type' => 'file']); ?>
        <?php echo $this->Form->hidden('process', array('value'=>"login")); ?>
        <?php echo $this->Form->input('fightername', array('label'=>"Name", 'type' => 'text')); ?>
        <?php echo $this->Form->input('avatar', array('label'=>"Avatar", 'type' => 'file')); ?>
        <?php echo $this->Form->button('Submit'); ?>
    <?php echo $this->Form->end(); ?>
    
</html>