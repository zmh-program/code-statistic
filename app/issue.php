<?php
include 'utils.php';

$dark = isset($_GET['theme']) && $_GET['theme'] === 'dark';
$username = get('username', '');
$repo = get('repo', '');
$id = get('id', '1');

$stats = fetch("issue/$username/$repo/$id");
if (!$stats) {
    include 'error.php';
    exit;
}
$name = "$username / $repo";

$header = $dark ? "#fff" : "#434d58";
$background = $dark ? "#000" : "#fffefe";

ob_start('compress');
?>
    <svg width="540" viewBox="0 0 420 190" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-labelledby="descId">
        <title id="titleId"><?php echo $repo ?>'s Issue #<?php echo $id ?></title>
        <desc id="descId">Issue Card</desc>
        <defs>
            <mask id="circle-mask"><circle cx="10" cy="10" r="10" fill="white" /></mask>
            <image id="avatar" height="20" width="20" xlink:href="data:image/png;base64,<?php echo $stats['opener']['image'] ?>"></image>
        </defs>
        <style>
            .oct-icon {
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
                <?php if ($stats['state'] == 'open'): ?>
                    <svg class="oct-icon" x="0" aria-hidden="true" y="10" height="16" style="fill: #3fb950" viewBox="0 0 16 16" width="16" data-view-component="true">
                        <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"></path>
                        <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"></path>
                    </svg>
                <?php elseif ($stats['state'] == 'closed'): ?>
                    <svg class="oct-icon" x="0" aria-hidden="true" y="10" height="16" style="fill: #7d8590" viewBox="0 0 16 16" width="16" data-view-component="true">
                        <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Zm9.78-2.22-5.5 5.5a.749.749 0 0 1-1.275-.326.749.749 0 0 1 .215-.734l5.5-5.5a.751.751 0 0 1 1.042.018.751.751 0 0 1 .018 1.042Z"></path>
                    </svg>
                <?php else: ?>
                    <svg class="oct-icon" x="0" aria-hidden="true" y="10" height="16" style="fill: #a371f7" viewBox="0 0 16 16" width="16" data-view-component="true">
                        <path d="M11.28 6.78a.75.75 0 0 0-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 0 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l3.5-3.5Z"></path>
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0Zm-1.5 0a6.5 6.5 0 1 0-13 0 6.5 6.5 0 0 0 13 0Z"></path>
                    </svg>
                <?php endif; ?>
                <text x="22" y="24" class="stat id" data-testid="header">#<?php echo $stats['id'] ?></text>
                <text x="<?php echo strlen($stats['id']) * 10 + 36 ?>" y="24" class="stat header" data-testid="header"><?php echo truncate($stats['title'], 38) ?></text>
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
                        <text class="stat" x="26" y="13">opened by</text>
                        <text class="stat bold" x="88" y="13" data-testid="publisher"><?php echo $stats['opener']['username'] ?></text>
                    </g>
                </g>
                <g transform="translate(6, 78)">
                    <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="m.427 1.927 1.215 1.215a8.002 8.002 0 1 1-1.6 5.685.75.75 0 1 1 1.493-.154 6.5 6.5 0 1 0 1.18-4.458l1.358 1.358A.25.25 0 0 1 3.896 6H.25A.25.25 0 0 1 0 5.75V2.104a.25.25 0 0 1 .427-.177ZM7.75 4a.75.75 0 0 1 .75.75v2.992l2.028.812a.75.75 0 0 1-.557 1.392l-2.5-1A.751.751 0 0 1 7 8.25v-3.5A.75.75 0 0 1 7.75 4Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo datetime($stats['date']) ?></text>
                    </g>
                </g>
                <g transform="translate(168, 78)">
                    <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0 1 13.25 12H9.06l-2.573 2.573A1.458 1.458 0 0 1 4 13.543V12H2.75A1.75 1.75 0 0 1 1 10.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h4.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo $stats['comments'] ?></text>
                    </g>
                </g>
                <g transform="translate(240, 78)">
                    <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                        <svg class="oct-icon" x="0" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Zm3.82 1.636a.75.75 0 0 1 1.038.175l.007.009c.103.118.22.222.35.31.264.178.683.37 1.285.37.602 0 1.02-.192 1.285-.371.13-.088.247-.192.35-.31l.007-.008a.75.75 0 0 1 1.222.87l-.022-.015c.02.013.021.015.021.015v.001l-.001.002-.002.003-.005.007-.014.019a2.066 2.066 0 0 1-.184.213c-.16.166-.338.316-.53.445-.63.418-1.37.638-2.127.629-.946 0-1.652-.308-2.126-.63a3.331 3.331 0 0 1-.715-.657l-.014-.02-.005-.006-.002-.003v-.002h-.001l.613-.432-.614.43a.75.75 0 0 1 .183-1.044ZM12 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM5 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm5.25 2.25.592.416a97.71 97.71 0 0 0-.592-.416Z"></path></svg>
                        <text class="stat" x="20" y="12.5" data-testid="tag"><?php echo $stats['reactions'] ?></text>
                    </g>
                </g>
            </svg>
        </g>
    </svg>

<?php
ob_end_flush();
?>