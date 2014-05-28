<div id="footer">

<ul>
    <?php foreach ($navigation as $title => $uri): ?>
        <li><?php echo anchor($uri, $title); ?></li>
    <?php endforeach; ?>
</ul>

</div> <!-- footer -->
</body>
</html>
