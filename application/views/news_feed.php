<div id="news_feed">
<h1>News Feed</h1>
<ul>
<?php foreach ($news_feed as $feed): ?>

    <div id="feed_item">
    <li> 
        <?php //echo anchor('news/' . $feed['slug'], img('images/thumbnail/' . $feed['image_name']), $feed['slug']); ?>
        <?php echo anchor('news/' . $feed['slug'], img($feed['thumbnail_image'])); ?>
        <h3 class="feed_item_heading"><?php echo anchor('news/' . $feed['slug'], $feed['news_title']); ?></h3>
        <p><?php echo $this->string_formatter->ellipsis($feed['news_text'], $preview_length); ?></p>
        <p class="date"><?php echo $this->string_formatter->date_formatter($feed['news_date']); ?></p>
    </li> 
    </div><!-- feed_item -->

<?php endforeach; ?>
</ul>

<div id="pagination">
    <?php echo $this->pagination->create_links(); ?>
</div> <!-- end of pagination -->

</div> <!-- end of news_feed -->
