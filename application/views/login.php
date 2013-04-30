<div class="home-title blue-gradient">Simple Task Board</div>
<div id="login">
    <?php echo form_open('login/validate'); ?>

    <?php echo form_label('Email', 'email'); ?>
    <?php echo form_input('email', $email); ?>
    <br/>
    <?php echo form_label('Password', 'password'); ?>
    <?php echo form_password('password', $password); ?>

    <?php if(isset($error) && $error) { ?>
    <p class="error">That's not right! Please check your information and try again.</p>
    <?php } ?>

    <?php echo form_submit('login', 'Login', 'class="btn-blue"'); ?>

    <?php echo form_close(); ?>
</div>