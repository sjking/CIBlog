<div id="news_item">

<div id="news_content">
    <h1><?php echo $news_item['news_title']; ?></h1>

    <?php //echo img(array('src' => $header_image, 'class' => 'header_image')); ?>
    <?php //echo anchor($header_anchor['uri'], img($header_image), $header_anchor['attributes']); ?>
    <?php echo $news_item['news_text']; ?>
</div> <!-- end of news_content -->

<div id="comment">
<ul id="news_comment">
    <h2>Comments</h1>
    <?php foreach ($comments as $comment): ?>
    <li>
    <div class="comment">
        <p class="comment_name"><?php echo $comment['comment_name']; ?></p>
        <p class="comment_time"><?php echo $comment['comment_timestamp']; ?></p>
        <p class="comment_text"><?php echo $comment['comment_text']; ?></p> 
    </div> <!-- end of comment class -->
    </li>
    <?php endforeach; ?>
    <a name="comment_end"></a>
    <div id="pagination">
        <?php echo $this->pagination->create_links(); ?>
    </div> <!-- end of pagination -->

    <a name="comment">
    <h3>Post a comment</h3>
    <div id="form">
        <?php echo form_open('news/submit_comment'); ?>
        <p><label for="name">Name: </label><?php echo form_input($form_data['name_data']); ?></p>
        <p><label for="email">Email: </label><?php echo form_input($form_data['email_data']); ?></p> 
        <p><label for="url">Website: </label><?php echo form_input($form_data['url_data']); ?></p> 
        <p><label for="text">Comment: </label><?php echo form_textarea($form_data['text_data'], '', 'maxlength="500"'); ?></p>
        <?php echo form_hidden('news_id', strval($news_item['id'])); ?>
        <?php echo form_hidden('slug', $news_item['slug']); ?>
        <div class="submit"><?php echo form_submit('submit', 'Submit'); ?></div>
        <?php echo form_close(); ?>
    </div> <!-- form -->
    </a> <!-- a comment -->
    
    <a name="errors">   
    <div id="post_errors">
        <?php echo $validation_errors; ?>
    </div> <!-- post_errors -->
    </a> <!-- errors -->

</ul> <!-- end of news_comment -->

</div> <!-- end of comment -->
</div> <!-- end of news-item -->
