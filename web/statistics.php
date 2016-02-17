<?php

require_once '../vendor/autoload.php';
require_once './.conf.php';

$storage = new \dd\Counter\Storage\PdoStorage(PDO_DSN, PDO_USER, PDO_PASSWORD);
//$storage = new \dd\Counter\Storage\FileStorage(FILE_PATH);

$report = (new \dd\Counter\Counter($storage))->report();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Counter</title>
</head>
<body>
    <?php if (empty($report)): ?>
        <p>no data</p>
    <?php else: ?>
        <ul>
            <?php foreach ($report as $domain => $data): ?>
                <li>
                    <?= $domain ?>
                    <ul>
                        <?php foreach ($data as $date => $stats): ?>
                            <li>
                                <?= $date ?>: visits - <?= isset($stats['count']) ? $stats['count'] : 0 ?>;
                                unique visitor - <?= isset($stats['uCount']) ? $stats['uCount'] : 0 ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="index.php">Back</a>
</body>
</html>