<div id="banner">
<h1>howToXYZ.com</h1>

<ul>
    <?php foreach ($navigation as $title => $uri): ?>
        <li><?php echo anchor($uri, $title); ?></li>
    <?php endforeach; ?>
</ul>

</div> <!-- bannner -->
