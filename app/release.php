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

$desc = str_replace("&hellip;", "-", str_replace("<br>", "<br></br>", $stats['description']));
$descHeight = substr_count($stats['description'], "\n") * 12;
$assets = $stats['assets'];
$position = 140 + $descHeight;
$height = 215 + count($assets) * 28 + $descHeight;

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
            .desc * {
                font: 400 12px 'Segoe UI', Ubuntu, Sans-Serif;
                font-weight: normal;
            }
            .desc h1, .desc h2, .desc h3 {
                font-weight: bold;
                transform: translateX(32px);
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
                <g transform="translate(90, 78)">
                    <g class="stagger" style="animation-delay: 750ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M9.5 3.25a2.25 2.25 0 1 1 3 2.122V6A2.5 2.5 0 0 1 10 8.5H6a1 1 0 0 0-1 1v1.128a2.251 2.251 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.5 0v1.836A2.493 2.493 0 0 1 6 7h4a1 1 0 0 0 1-1v-.628A2.25 2.25 0 0 1 9.5 3.25Zm-6 0a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0Zm8.25-.75a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM4.25 12a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo $stats['branch'] ?></text>
                    </g>
                </g>
                <g transform="translate(<?php echo strlen($stats['branch']) * 14 + 120 ?>, 78)">
                    <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="m.427 1.927 1.215 1.215a8.002 8.002 0 1 1-1.6 5.685.75.75 0 1 1 1.493-.154 6.5 6.5 0 1 0 1.18-4.458l1.358 1.358A.25.25 0 0 1 3.896 6H.25A.25.25 0 0 1 0 5.75V2.104a.25.25 0 0 1 .427-.177ZM7.75 4a.75.75 0 0 1 .75.75v2.992l2.028.812a.75.75 0 0 1-.557 1.392l-2.5-1A.751.751 0 0 1 7 8.25v-3.5A.75.75 0 0 1 7.75 4Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo rtrim(str_replace('T', ' ', $stats['date']), "Z") ?></text>
                    </g>
                </g>
                <g transform="translate(0, 104)">
                    <g class="stagger" style="animation-delay: 1200ms">
                        <foreignObject class="stat" x="0" y="0" width="100%" height="<?php echo $descHeight ?>" data-testid="description">
                            <body xmlns="http://www.w3.org/1999/xhtml" class="desc">
                                <?php echo $desc ?>
                            </body>
                        </foreignObject>
                    </g>
                </g>
            </svg>
        </g>
        <g xmlns="http://www.w3.org/2000/svg" transform="translate(6, <?php echo $position ?>)" data-testid="assets">
            <svg data-testid="assets" x="0" y="0">
                <rect data-testid="card-bg" x="0.5" y="0.5" rx="4.5" height="<?php echo $height - $position - 10 ?>" stroke="#e4e2e2" width="96%" fill="<?php echo $background ?>" stroke-opacity="1" />
                <g class="stagger" style="animation-delay: 1100ms" transform="translate(18, 10)">
                    <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M3.5 1.75v11.5c0 .09.048.173.126.217a.75.75 0 0 1-.752 1.298A1.748 1.748 0 0 1 2 13.25V1.75C2 .784 2.784 0 3.75 0h5.586c.464 0 .909.185 1.237.513l2.914 2.914c.329.328.513.773.513 1.237v8.586A1.75 1.75 0 0 1 12.25 15h-.5a.75.75 0 0 1 0-1.5h.5a.25.25 0 0 0 .25-.25V4.664a.25.25 0 0 0-.073-.177L9.513 1.573a.25.25 0 0 0-.177-.073H7.25a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1 0-1.5h-3a.25.25 0 0 0-.25.25Zm3.75 8.75h.5c.966 0 1.75.784 1.75 1.75v3a.75.75 0 0 1-.75.75h-2.5a.75.75 0 0 1-.75-.75v-3c0-.966.784-1.75 1.75-1.75ZM6 5.25a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 6 5.25Zm.75 2.25h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1 0-1.5ZM8 6.75A.75.75 0 0 1 8.75 6h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 8 6.75ZM8.75 3h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1 0-1.5ZM8 9.75A.75.75 0 0 1 8.75 9h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 8 9.75Zm-1 2.5v2.25h1v-2.25a.25.25 0 0 0-.25-.25h-.5a.25.25 0 0 0-.25.25Z"></path></svg>
                    <text class="stat bold" style="animation-delay: 0ms" x="20" y="12" mask="url(#rect-mask)">Source code</text>
                    <text class="stat" style="animation-delay: 20ms" x="95" y="12" mask="url(#rect-mask)">(zip)</text>
                </g>
                <g class="stagger" style="animation-delay: 1100ms" transform="translate(18, 38)">
                    <line x1="-2" y1="-6" x2="360" y2="-6" stroke="#eeecec"></line>
                    <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M3.5 1.75v11.5c0 .09.048.173.126.217a.75.75 0 0 1-.752 1.298A1.748 1.748 0 0 1 2 13.25V1.75C2 .784 2.784 0 3.75 0h5.586c.464 0 .909.185 1.237.513l2.914 2.914c.329.328.513.773.513 1.237v8.586A1.75 1.75 0 0 1 12.25 15h-.5a.75.75 0 0 1 0-1.5h.5a.25.25 0 0 0 .25-.25V4.664a.25.25 0 0 0-.073-.177L9.513 1.573a.25.25 0 0 0-.177-.073H7.25a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1 0-1.5h-3a.25.25 0 0 0-.25.25Zm3.75 8.75h.5c.966 0 1.75.784 1.75 1.75v3a.75.75 0 0 1-.75.75h-2.5a.75.75 0 0 1-.75-.75v-3c0-.966.784-1.75 1.75-1.75ZM6 5.25a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 6 5.25Zm.75 2.25h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1 0-1.5ZM8 6.75A.75.75 0 0 1 8.75 6h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 8 6.75ZM8.75 3h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1 0-1.5ZM8 9.75A.75.75 0 0 1 8.75 9h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 8 9.75Zm-1 2.5v2.25h1v-2.25a.25.25 0 0 0-.25-.25h-.5a.25.25 0 0 0-.25.25Z"></path></svg>
                    <text class="stat bold" style="animation-delay: 0ms" x="20" y="12" mask="url(#rect-mask)">Source code</text>
                    <text class="stat" style="animation-delay: 20ms" x="95" y="12" mask="url(#rect-mask)">(tar.gz)</text>
                </g>
                <?php foreach ($assets as $idx => $asset) { ?>
                    <g class="stagger" style="animation-delay: <?php echo 1300 + ($idx * 100)?>ms" transform="translate(18, <?php echo $idx * 28 + 66 ?>)">
                        <line x1="-2" y1="-6" x2="360" y2="-6" stroke="#eeecec"></line>
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="m8.878.392 5.25 3.045c.54.314.872.89.872 1.514v6.098a1.75 1.75 0 0 1-.872 1.514l-5.25 3.045a1.75 1.75 0 0 1-1.756 0l-5.25-3.045A1.75 1.75 0 0 1 1 11.049V4.951c0-.624.332-1.201.872-1.514L7.122.392a1.75 1.75 0 0 1 1.756 0ZM7.875 1.69l-4.63 2.685L8 7.133l4.755-2.758-4.63-2.685a.248.248 0 0 0-.25 0ZM2.5 5.677v5.372c0 .09.047.171.125.216l4.625 2.683V8.432Zm6.25 8.271 4.625-2.683a.25.25 0 0 0 .125-.216V5.677L8.75 8.432Z"></path></svg>
                        <text class="stat bold" style="animation-delay: <?php echo $idx * 100 ?>ms" x="20" y="12" mask="url(#rect-mask)"><?php echo truncate($asset['name'], 46) ?></text>
                        <text class="stat bold" style="animation-delay: <?php echo $idx * 100 + 50 ?>ms" x="320" y="12" mask="url(#rect-mask)"><?php echo $asset['size'] ?></text>
                    </g>
                <?php } ?>
            </svg>
        </g>
    </svg>

<?php
ob_end_flush();
?>