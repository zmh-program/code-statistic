<?php
include 'utils.php';

$dark = isset($_GET['theme']) && $_GET['theme'] === 'dark';
$username = get('username', '');
$repo = get('repo', '');
$tag = get('tag', 'latest');

$stats = fetch("release/$username/$repo/$tag");
if (!$stats) {
    include 'error.php';
    exit;
}
$name = "$username / $repo";
$assets = $stats['assets']; $position = 215 + substr_count($stats['description'], "\n") * 12;
$height = 215 + count($assets) * 30 + substr_count($stats['description'], "\n") * 12;

$header = $dark ? "#fff" : "#434d58";
$background = $dark ? "#000" : "#fffefe";

ob_start('compress');
?>
    <svg width="540" viewBox="0 0 420 <?php echo $height + 1 ?>" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="descId">
        <title id="titleId"><?php echo $name ?>'s Release <?php echo $tag ?></title>
        <desc id="descId">Release Statistics</desc>
        <defs>
            <mask id="circle-mask"><circle cx="10" cy="10" r="10" fill="white" /></mask>
            <image id="avatar" height="20" width="20" xlink:href="data:image/png;base64,<?php echo $stats['author']['image'] ?>"></image>
        </defs>
        <style>
            .oct-icon {
                display: block;
                fill: #8b849e;
            }
            .circle {
                animation: fadeInAnimation 0.8s ease-in-out forwards;
            }
            .header {
                font: 600 26px 'Segoe UI', Ubuntu, Sans-Serif !important;
                animation: fadeInAnimation 0.8s ease-in-out forwards;
            }
            .project {
                font-size: 14px !important;
                fill: #2f80ed !important;
                animation: fadeInAnimation 0.8s ease-in-out forwards;
            }
            @supports (appearance: auto) {
                .header {
                    font-size: 16px;
                }
            }
            .stat {
                font: 600 14px 'Segoe UI', Ubuntu, "Helvetica Neue", Sans-Serif;
                fill: <?php echo $header ?>;
            }
            @supports (appearance: auto) {
                .stat {
                    font-size: 12px;
                }
            }
            .stagger {
                opacity: 0;
                animation: fadeInAnimation 0.3s ease-in-out forwards;
            }
            .bold {
                font-weight: 700;
            }
            @keyframes growWidthAnimation {
                from {
                    width: 0;
                }
                to {
                    width: 100%;
                }
            }
            .stagger {
                opacity: 0;
                animation: fadeInAnimation 0.3s ease-in-out forwards;
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
        <g data-testid="card-title" transform="translate(30, 25)">
            <g transform="translate(0, 0)">
                <text x="6" y="24" class="stat header" data-testid="header"><?php echo $stats['name'] ?></text>
            </g>
        </g>
        <line x1="30" y1="60" x2="390" y2="60" stroke="#eeecec"></line>
        <g data-testid="main-card-info" transform="translate(0, 55)"><svg x="0" y="0">
                <g transform="translate(50, 30)" style="animation-delay: 300ms">
                    <circle class="circle" cx="-10" cy="-5" r="5" fill="<?php echo $stats['color'] ?>" />
                    <text x="0" y="0" class="stat project" data-testid="header"><?php echo $name ?></text>
                </g>
                <g transform="translate(6, 46)">
                    <g class="stagger" style="animation-delay: 450ms" transform="translate(25, 0)">
                        <use xlink:href="#avatar" mask="url(#circle-mask)" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="avatar" />
                        <text class="stat" x="26" y="13">released by</text>
                        <text class="stat bold" x="92" y="13" data-testid="publisher"><?php echo $stats['author']['username'] ?></text>
                    </g>
                </g>
                <g transform="translate(8, 78)">
                    <g class="stagger" style="animation-delay: 600ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M1 7.775V2.75C1 1.784 1.784 1 2.75 1h5.025c.464 0 .91.184 1.238.513l6.25 6.25a1.75 1.75 0 0 1 0 2.474l-5.026 5.026a1.75 1.75 0 0 1-2.474 0l-6.25-6.25A1.752 1.752 0 0 1 1 7.775Zm1.5 0c0 .066.026.13.073.177l6.25 6.25a.25.25 0 0 0 .354 0l5.025-5.025a.25.25 0 0 0 0-.354l-6.25-6.25a.25.25 0 0 0-.177-.073H2.75a.25.25 0 0 0-.25.25ZM6 5a1 1 0 1 1 0 2 1 1 0 0 1 0-2Z"></path>                        </svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo $stats['tag'] ?></text>
                    </g>
                </g>
                <g transform="translate(64, 78)">
                    <g class="stagger" style="animation-delay: 750ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M9.5 3.25a2.25 2.25 0 1 1 3 2.122V6A2.5 2.5 0 0 1 10 8.5H6a1 1 0 0 0-1 1v1.128a2.251 2.251 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.5 0v1.836A2.493 2.493 0 0 1 6 7h4a1 1 0 0 0 1-1v-.628A2.25 2.25 0 0 1 9.5 3.25Zm-6 0a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0Zm8.25-.75a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM4.25 12a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo $stats['branch'] ?></text>
                    </g>
                </g>
                <g transform="translate(<?php echo strlen($stats['branch']) * 14 + 76 ?>, 78)">
                    <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="m.427 1.927 1.215 1.215a8.002 8.002 0 1 1-1.6 5.685.75.75 0 1 1 1.493-.154 6.5 6.5 0 1 0 1.18-4.458l1.358 1.358A.25.25 0 0 1 3.896 6H.25A.25.25 0 0 1 0 5.75V2.104a.25.25 0 0 1 .427-.177ZM7.75 4a.75.75 0 0 1 .75.75v2.992l2.028.812a.75.75 0 0 1-.557 1.392l-2.5-1A.751.751 0 0 1 7 8.25v-3.5A.75.75 0 0 1 7.75 4Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo rtrim(str_replace('T', ' ', $stats['date']), "Z") ?></text>
                    </g>
                </g>
                <g transform="translate(0, 94)">
                    <g class="stagger" style="animation-delay: 1050ms">
                        <foreignObject class="stat" x="0" y="0" width="100%" height="100%" data-testid="description">
                            <body xmlns="http://www.w3.org/1999/xhtml" style="font-weight: normal">
                                <?php echo $stats['description'] ?>
                            </body>
                        </foreignObject>
                    </g>
                </g>
            </svg>
        </g>
        <g xmlns="http://www.w3.org/2000/svg" transform="translate(2, <?php echo $position ?>)" data-testid="assets">
            <svg data-testid="assets" x="25">
                <mask id="rect-mask"><rect x="0" y="0" width="300" height="8" fill="white" rx="5"/></mask>
                <?php foreach ($assets as $idx => $asset) { ?>
                    <text class="stagger" style="animation-delay: <?php echo $idx * 100 ?>ms" x="0" y="8" mask="url(#rect-mask)"><?php echo $asset['name'] ?></text>
                <?php } ?>
            </svg>
        </g>
    </svg>

<?php
ob_end_flush();
?>