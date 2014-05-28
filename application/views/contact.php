<h1>Contact</h1>
<p>Use the form below to contact.</p>
<div id="form">
    <?php echo form_open('contact/submit'); ?>
    <p><label for="name">Name: </label><?php echo form_input($form_data['name_data']); ?></p>
    <p><label for="email">Email: </label><?php echo form_input($form_data['email_data']); ?></p>
    <p><label for="message">Message: </label><?php echo form_textarea($form_data['message_data'], '', 'maxlength="500"'); ?></p>
    
    <div class="submit"><?php echo form_submit('submit', 'Submit'); ?></div>
    <?php echo form_close(); ?>
    
    <a name="errors">   
    <div id="post_errors">
        <?php echo $validation_errors; ?>
    </div> <!-- post_errors -->
    </a> <!-- errors -->

</div> <!-- form --> 
