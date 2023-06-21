<?php
include 'utils.php';

$dark = isset($_GET['theme']) && $_GET['theme'] === 'dark';
$username = get('username', '');
$repo = get('repo', '');
$id = get('id', '1');

$stats = fetch("pull/$username/$repo/$id");
if (!$stats) {
    include 'error.php';
    exit;
}
$name = "$username / $repo";

$header = $dark ? "#fff" : "#434d58";
$background = $dark ? "#000" : "#fffefe";

$base = truncate($stats['migration']['base'], 34);
$head = truncate($stats['migration']['head'], 34);
ob_start('compress');
?>
    <svg width="540" viewBox="0 0 420 250" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="descId">
        <title id="titleId"><?php echo $repo ?>'s Pull Request #<?php echo $id ?></title>
        <desc id="descId">Pull Request Card</desc>
        <defs>
            <mask id="circle-mask"><circle cx="10" cy="10" r="10" fill="white" /></mask>
            <image id="avatar" height="20" width="20" xlink:href="data:image/png;base64,<?php echo $stats['creator']['image'] ?>"></image>
        </defs>
        <style>
            .icon {
                display: block;
                fill: #8b849e;
            }
            .circle {
                animation: fadeInAnimation 0.8s ease-in-out forwards;
            }
            .id {
                font: normal 16px 'Segoe UI', Ubuntu, Sans-Serif !important;
                fill: rgb(125, 133, 144) !important;
                animation: fadeInAnimation 0.5s ease-in-out forwards;
            }
            .header {
                font: 600 16px 'Segoe UI', Ubuntu, Sans-Serif !important;
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
            .bold {
                font-weight: 700;
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
        <g data-testid="card-title" transform="translate(30, 25)">
            <g transform="translate(0, 0)">
                <?php if ($stats['state'] == 'open'): ?>
                    <svg class="icon" x="0" aria-hidden="true" y="10" height="16" style="fill: #238636" viewBox="0 0 16 16" width="16" data-view-component="true">
                        <path d="M1.5 3.25a2.25 2.25 0 1 1 3 2.122v5.256a2.251 2.251 0 1 1-1.5 0V5.372A2.25 2.25 0 0 1 1.5 3.25Zm5.677-.177L9.573.677A.25.25 0 0 1 10 .854V2.5h1A2.5 2.5 0 0 1 13.5 5v5.628a2.251 2.251 0 1 1-1.5 0V5a1 1 0 0 0-1-1h-1v1.646a.25.25 0 0 1-.427.177L7.177 3.427a.25.25 0 0 1 0-.354ZM3.75 2.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm0 9.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm8.25.75a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0Z"></path>
                    </svg>
                <?php elseif ($stats['state'] == 'merged'): ?>
                    <svg class="icon" x="0" aria-hidden="true" y="10" height="16" style="fill: #a371f7" viewBox="0 0 16 16" width="16" data-view-component="true">
                        <path d="M5.45 5.154A4.25 4.25 0 0 0 9.25 7.5h1.378a2.251 2.251 0 1 1 0 1.5H9.25A5.734 5.734 0 0 1 5 7.123v3.505a2.25 2.25 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.95-.218ZM4.25 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8.5-4.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM5 3.25a.75.75 0 1 0 0 .005V3.25Z"></path>
                    </svg>
                <?php else: ?>
                    <svg class="icon" x="0" aria-hidden="true" y="10" height="16" style="fill: #f85149" viewBox="0 0 16 16" width="16" data-view-component="true">
                        <path d="M3.25 1A2.25 2.25 0 0 1 4 5.372v5.256a2.251 2.251 0 1 1-1.5 0V5.372A2.251 2.251 0 0 1 3.25 1Zm9.5 5.5a.75.75 0 0 1 .75.75v3.378a2.251 2.251 0 1 1-1.5 0V7.25a.75.75 0 0 1 .75-.75Zm-2.03-5.273a.75.75 0 0 1 1.06 0l.97.97.97-.97a.748.748 0 0 1 1.265.332.75.75 0 0 1-.205.729l-.97.97.97.97a.751.751 0 0 1-.018 1.042.751.751 0 0 1-1.042.018l-.97-.97-.97.97a.749.749 0 0 1-1.275-.326.749.749 0 0 1 .215-.734l.97-.97-.97-.97a.75.75 0 0 1 0-1.06ZM2.5 3.25a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0ZM3.25 12a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm9.5 0a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Z"></path>
                    </svg>
                <?php endif; ?>
                <text x="22" y="24" class="stat id" data-testid="header">#<?php echo $stats['id'] ?></text>
                <text x="<?php echo strlen($stats['id']) * 10 + 36 ?>" y="24" class="stat header" data-testid="header"><?php echo truncate($stats['title'], 38) ?></text>
            </g>
        </g>
        <line x1="30" y1="60" x2="390" y2="60" stroke="#eeecec"></line>
        <g data-testid="main-card-info" transform="translate(0, 55)"><svg x="0" y="0">
                <g transform="translate(6, 16)">
                    <g class="stagger" style="animation-delay: 150ms" transform="translate(25, 0)">
                        <text class="stat" x="<?php echo (140 - (strlen($base) * 6)) / 2 ?>" y="12.5" data-testid="base"><?php echo $base ?></text>
                        <svg class="icon" x="160" aria-hidden="true" height="16" viewBox="0 0 24 24" width="16" data-view-component="true"><path d="M12.707 17.293 8.414 13H18v-2H8.414l4.293-4.293-1.414-1.414L4.586 12l6.707 6.707z"></path></svg>
                        <text class="stat" x="<?php echo (140 - (strlen($base) * 6)) / 2 + 180 ?>" y="12.5" data-testid="head"><?php echo $head ?></text>
                    </g>
                </g>
                <g transform="translate(50, 62)" style="animation-delay: 300ms">
                    <circle class="circle" cx="-10" cy="-5" r="5" fill="<?php echo $stats['color'] ?>" />
                    <text x="0" y="0" class="stat project" data-testid="header"><?php echo $name ?></text>
                </g>
                <g transform="translate(6, 78)">
                    <g class="stagger" style="animation-delay: 450ms" transform="translate(25, 0)">
                        <use xlink:href="#avatar" mask="url(#circle-mask)" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="avatar" />
                        <text class="stat" x="26" y="13">opened by</text>
                        <text class="stat bold" x="88" y="13" data-testid="creator"><?php echo $stats['creator']['username'] ?></text>
                    </g>
                </g>
                <g transform="translate(6, 114)">
                    <g class="stagger" style="animation-delay: 600ms" transform="translate(25, 0)">
                        <svg class="icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="m.427 1.927 1.215 1.215a8.002 8.002 0 1 1-1.6 5.685.75.75 0 1 1 1.493-.154 6.5 6.5 0 1 0 1.18-4.458l1.358 1.358A.25.25 0 0 1 3.896 6H.25A.25.25 0 0 1 0 5.75V2.104a.25.25 0 0 1 .427-.177ZM7.75 4a.75.75 0 0 1 .75.75v2.992l2.028.812a.75.75 0 0 1-.557 1.392l-2.5-1A.751.751 0 0 1 7 8.25v-3.5A.75.75 0 0 1 7.75 4Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo datetime($stats['date']) ?></text>
                    </g>
                </g>
                <g transform="translate(168, 114)">
                    <g class="stagger" style="animation-delay: 750ms" transform="translate(25, 0)">
                        <svg class="icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0 1 13.25 12H9.06l-2.573 2.573A1.458 1.458 0 0 1 4 13.543V12H2.75A1.75 1.75 0 0 1 1 10.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h4.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="comments"><?php echo $stats['comments'] ?></text>
                    </g>
                </g>
                <g transform="translate(240, 114)">
                    <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                        <svg class="icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M1 1.75C1 .784 1.784 0 2.75 0h7.586c.464 0 .909.184 1.237.513l2.914 2.914c.329.328.513.773.513 1.237v9.586A1.75 1.75 0 0 1 13.25 16H2.75A1.75 1.75 0 0 1 1 14.25Zm1.75-.25a.25.25 0 0 0-.25.25v12.5c0 .138.112.25.25.25h10.5a.25.25 0 0 0 .25-.25V4.664a.25.25 0 0 0-.073-.177l-2.914-2.914a.25.25 0 0 0-.177-.073ZM8 3.25a.75.75 0 0 1 .75.75v1.5h1.5a.75.75 0 0 1 0 1.5h-1.5v1.5a.75.75 0 0 1-1.5 0V7h-1.5a.75.75 0 0 1 0-1.5h1.5V4A.75.75 0 0 1 8 3.25Zm-3 8a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="files"><?php echo $stats['changed_files'] ?></text>
                    </g>
                </g>
                <g transform="translate(6, 150)">
                    <g class="stagger" style="animation-delay: 1050ms" transform="translate(25, 0)">
                        <svg class="icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M11.93 8.5a4.002 4.002 0 0 1-7.86 0H.75a.75.75 0 0 1 0-1.5h3.32a4.002 4.002 0 0 1 7.86 0h3.32a.75.75 0 0 1 0 1.5Zm-1.43-.75a2.5 2.5 0 1 0-5 0 2.5 2.5 0 0 0 5 0Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="commits"><?php echo $stats['commits'] ?> commit<?php echo $stats['commits'] > 1 ? 's' : '' ?></text>
                        <text class="stat" x="162" y="12.5" data-testid="additions" style="fill: #3fb950">+<?php echo $stats['additions'] ?></text>
                        <text class="stat" x="234" y="12.5" data-testid="deletions" style="fill: #cb2431">-<?php echo $stats['deletions'] ?></text>
                    </g>
                </g>
            </svg>
        </g>
    </svg>

<?php
ob_end_flush();
?>