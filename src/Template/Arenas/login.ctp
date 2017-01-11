<html>   
    <?php $this->assign('title', 'Login');?>
    
    <h2> Login </h2>
    <?php echo $this->Form->create('Login'); ?>
        <?php echo $this->Form->hidden('process', array('value'=>"login")); ?>
        <?php echo $this->Form->input('email', array('label'=>"Login", 'type' => 'text')); ?>
        <?php echo $this->Form->input('password', array('label'=>"Password", 'type' => 'password')); ?>
        <?php echo $this->Form->button('Submit'); ?>
    <?php echo $this->Form->end(); ?>
    
    <?php echo $this->Html->link('Forgot password ?', array('controller' => 'Arenas', 'action'=> 'resetpassword')); ?>
    
    <?php echo $this->Form->create('Google'); ?>
    <?php echo $this->Form->hidden('process', array('value'=>"google")); ?>
    <?php echo $this->Form->button('Connect with Google'); ?>
    <?php echo $this->Form->end(); ?>
    
    <h2> Subscribe </h2>
    <?php echo $this->Form->create('Subscribe'); ?>
        <?php echo $this->Form->hidden('process', array('value'=>"subscribe")); ?>
        <?php echo $this->Form->input('email', array('label'=>"Email", 'type' => 'email')); ?>
        <?php echo $this->Form->input('password', array('label'=>"Password", 'type' => 'password')); ?>
        <?php echo $this->Form->button('Submit'); ?>
    <?php echo $this->Form->end(); ?>
    
</html>