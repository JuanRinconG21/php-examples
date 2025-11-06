<?php

?>
<!doctype html>
<html>

<body>
    <h1>Posts</h1>
    <?php if (empty($posts)): ?>
        <p>No hay posts</p>
    <?php else: ?>
        <ul>
            <?php foreach ($posts as $p): ?>
                <li><?= htmlspecialchars($p['title'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>