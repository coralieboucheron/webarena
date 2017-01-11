<html>   
    <?php echo $this->Form->create() ?>
        <fieldset>
            <legend> <?= __('If you forgot your password, please reset it') ?></legend>
            <?php echo $this->Form->input('email', ['type' => 'email', 'label' => 'Email']); ?>
            <?= $this->Form->input('password', ['label' => 'New Password']) ?>
            <?= $this->Form->input('confirmpassword', ['type' => 'password', 'label' => 'Confirm New Password']) ?>
        </fieldset>
        <?= $this->Form->button('Reset password'); ?>
    <?php echo $this->Form->end() ?>
</html>
