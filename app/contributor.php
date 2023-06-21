<?php
include 'utils.php';

$dark = isset($_GET['theme']) && $_GET['theme'] === 'dark';
$header = $dark ? "#fff" : "#434d58";
$background = $dark ? "#000" : "#fffefe";
$username = get('username', '');
$repo = get('repo', '');
$column = max((int)get('column', '6'), 4);

$stats = fetch("contributor/$username/$repo");
if (!$stats) {
    include 'error.php';
    exit;
}
$name = "$username / $repo";
$number = count($stats['contributors']);
$height = 100 + (ceil($number / $column) * 64);

ob_start('compress');
?>
<svg width="540" viewBox="0 0 <?php echo 100 + ($column * 64) ?> <?php echo $height + 1 ?>" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="descId">
    <title id="titleId"><?php echo $repo ?>'s Contributors</title>
    <desc id="descId">Contributor Card</desc>
    <defs>
        <mask id="circle-mask"><circle cx="25" cy="25" r="25" fill="white" /></mask>
        <?php foreach ($stats['contributors'] as $idx => $contributor) { ?>
            <image id="image-<?php echo $idx ?>" height="50" width="50" xlink:href="data:image/png;base64,<?php echo $contributor['image'] ?>"></image>
        <?php } ?>
    </defs>
    <style>
        .circle {
            animation: fadeInAnimation 0.8s ease-in-out forwards;
        }
        .avatar {
            animation: fadeInAnimation 0.8s ease-in-out forwards;
            border-radius: 50%;
        }
        .header {
            font: 600 18px 'Segoe UI', Ubuntu, Sans-Serif;
            fill: #2f80ed;
            animation: fadeInAnimation 0.8s ease-in-out forwards;
        }
        @supports (appearance: auto) {
            .header {
                font-size: 16px;
            }
        }
        @keyframes growWidthAnimation {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }
        #rect-mask rect {
            animation: fadeInAnimation 1s ease-in-out forwards;
        }
        @keyframes fadeInAnimation {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
    <rect data-testid="card-bg" x="0.5" y="0.5" rx="4.5" height="99%" stroke="#e4e2e2" width="99%" fill="<?php echo $background ?>" stroke-opacity="1"/>
    <g data-testid="card-title" transform="translate(40, 35)">
        <g transform="translate(0, 0)">
            <circle class="circle" cx="-10" cy="-5" r="5" fill="<?php echo $stats['color'] ?>" />
            <text x="0" y="0" class="header" data-testid="header"><?php echo $name ?></text>
        </g>
    </g>
    <g xmlns="http://www.w3.org/2000/svg" transform="translate(18, 65)" data-testid="main-card-progress">
        <svg data-testid="contributors" x="25">
            <mask id="rect-mask"><rect x="0" y="0" width="300" height="8" fill="white" rx="5"/></mask>
            <?php foreach ($stats['contributors'] as $idx => $contributor) { ?>
                <g class="avatar" transform="translate(<?php echo ($idx % $column) * 64 ?>, <?php echo (int)($idx / $column) * 64 ?>)" style="animation-duration: <?php echo $idx * 50 + 800 ?>ms">
                    <use xlink:href="#image-<?php echo $idx ?>" mask="url(#circle-mask)" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="avatar" />
                </g>
            <?php } ?>
        </svg>
    </g>
</svg>

<?php
ob_end_flush();
?>