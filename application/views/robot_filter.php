<div id='bot_filter'>
<h1>Bot Filter</h1>

<p>Please enter the characters displayed in the image to prove that you are human.</p>

<div id='captcha'>
    <?php echo $cap['image']; ?>
    <div id='captcha_form'>
        <?php echo form_open('robot-filter/check'); ?>
        <?php echo form_input($form_data); ?>
        <?php echo form_submit('submit', 'Submit'); ?>
        <?php echo form_close(); ?>
    </div> <!-- captcha_form -->
</div> <!-- captcha -->
</div> <!-- bot_filter -->
