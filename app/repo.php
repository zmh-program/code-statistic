<?php
include 'utils.php';

$dark = isset($_GET['theme']) && $_GET['theme'] === 'dark';
$username = get('username', '');
$repo = get('repo', '');

$stats = fetch("repo/$username/$repo");
if (!$stats) {
    include 'error.php';
    exit;
}
$langs = $stats['languages'];
$name = "$username / $repo";

list($langs, $bar, $height, $header, $background) = extracted($stats['languages'], $dark);
?>
<svg width="540" viewBox="0 0 660 <?php echo $height + 1 ?>" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="descId">
    <title id="titleId"><?php echo $repo ?>'s Code Stats</title>
    <desc id="descId">Repository Card</desc>
    <style>
        .icon {
            display: block;
            fill: #8b849e;
        }
        .circle {
            animation: fadeInAnimation 0.8s ease-in-out forwards;
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
        .lang {
            font: 400 11px "Segoe UI", Ubuntu, Sans-Serif;
            fill: <?php echo $header ?>;
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
    <rect data-testid="card-bg" x="0.5" y="0.5" rx="4.5" height="99%" stroke="#e4e2e2" width="659" fill="<?php echo $background ?>" stroke-opacity="1"/>
    <g data-testid="card-title" transform="translate(40, 35)">
        <g transform="translate(0, 0)">
            <circle class="circle" cx="-10" cy="-5" r="5" fill="<?php echo $stats['color'] ?>" />
            <text x="0" y="0" class="header" data-testid="header"><?php echo $name ?></text>
        </g>
    </g>
    <g data-testid="main-card-info" transform="translate(0, 55)"><svg x="0" y="0">
            <g transform="translate(0, 0)">
                <g class="stagger" style="animation-delay: 450ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">Stars:</text>
                    <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25zm0 2.445L6.615 5.5a.75.75 0 01-.564.41l-3.097.45 2.24 2.184a.75.75 0 01.216.664l-.528 3.084 2.769-1.456a.75.75 0 01.698 0l2.77 1.456-.53-3.084a.75.75 0 01.216-.664l2.24-2.183-3.096-.45a.75.75 0 01-.564-.41L8 2.694v.001z"></path></svg>
                    <text class="stat bold" x="192.01" y="12.5" data-testid="stars"><?php echo $stats['stars'] ?></text>
                </g>
            </g><g transform="translate(0, 25)">
                <g class="stagger" style="animation-delay: 600ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">Forks:</text>
                    <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M5 3.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm0 2.122a2.25 2.25 0 10-1.5 0v.878A2.25 2.25 0 005.75 8.5h1.5v2.128a2.251 2.251 0 101.5 0V8.5h1.5a2.25 2.25 0 002.25-2.25v-.878a2.25 2.25 0 10-1.5 0v.878a.75.75 0 01-.75.75h-4.5A.75.75 0 015 6.25v-.878zm3.75 7.378a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm3-8.75a.75.75 0 100-1.5.75.75 0 000 1.5z"></path></svg>
                    <text class="stat bold" x="192.01" y="12.5" data-testid="forks"><?php echo $stats['forks'] ?></text>
                </g>
            </g>
            <g transform="translate(0, 50)">
                <g class="stagger" style="animation-delay: 750ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">Watchers:</text>
                    <svg class="icon" x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true"><path fill-rule="evenodd" d="M1.679 7.932c.412-.621 1.242-1.75 2.366-2.717C5.175 4.242 6.527 3.5 8 3.5c1.473 0 2.824.742 3.955 1.715 1.124.967 1.954 2.096 2.366 2.717a.119.119 0 010 .136c-.412.621-1.242 1.75-2.366 2.717C10.825 11.758 9.473 12.5 8 12.5c-1.473 0-2.824-.742-3.955-1.715C2.92 9.818 2.09 8.69 1.679 8.068a.119.119 0 010-.136zM8 2c-1.981 0-3.67.992-4.933 2.078C1.797 5.169.88 6.423.43 7.1a1.619 1.619 0 000 1.798c.45.678 1.367 1.932 2.637 3.024C4.329 13.008 6.019 14 8 14c1.981 0 3.67-.992 4.933-2.078 1.27-1.091 2.187-2.345 2.637-3.023a1.619 1.619 0 000-1.798c-.45-.678-1.367-1.932-2.637-3.023C11.671 2.992 9.981 2 8 2zm0 8a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                    <text class="stat bold" x="192.01" y="12.5" data-testid="watchers"><?php echo $stats['watchers'] ?></text>
                </g>
            </g>
            <g transform="translate(0, 75)">
                <g class="stagger" style="animation-delay: 900ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">Repo Size:</text>
                    <svg class="icon" x="170.01" height="16" width="14" viewBox="0 0 14 16" aria-hidden="true"><path d="M6,15 C2.69,15 0,14.1 0,13 L0,11 C0,10.83 0.09,10.66 0.21,10.5 C0.88,11.36 3.21,12 6,12 C8.79,12 11.12,11.36 11.79,10.5 C11.92,10.66 12,10.83 12,11 L12,13 C12,14.1 9.31,15 6,15 L6,15 Z M6,11 C2.69,11 0,10.1 0,9 L0,7 C0,6.89 0.04,6.79 0.09,6.69 L0.09,6.69 C0.12,6.63 0.16,6.56 0.21,6.5 C0.88,7.36 3.21,8 6,8 C8.79,8 11.12,7.36 11.79,6.5 C11.84,6.56 11.88,6.63 11.91,6.69 L11.91,6.69 C11.96,6.79 12,6.9 12,7 L12,9 C12,10.1 9.31,11 6,11 L6,11 Z M6,7 C2.69,7 0,6.1 0,5 L0,4 L0,3 C0,1.9 2.69,1 6,1 C9.31,1 12,1.9 12,3 L12,4 L12,5 C12,6.1 9.31,7 6,7 L6,7 Z M6,2 C3.79,2 2,2.45 2,3 C2,3.55 3.79,4 6,4 C8.21,4 10,3.55 10,3 C10,2.45 8.21,2 6,2 L6,2 Z" fill-rule="evenodd"></path></svg>
                    <text class="stat bold" x="189.01" y="12.5" data-testid="size"><?php echo $stats['size'] ?></text>
                </g>
            </g>
            <g transform="translate(0, 100)">
                <g class="stagger" style="animation-delay: 1050ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">License:</text>
                    <svg aria-hidden="true" x="169.01" height="16" width="16" viewBox="0 0 16 16" class="icon"><path fill-rule="evenodd" d="M8.75.75a.75.75 0 00-1.5 0V2h-.984c-.305 0-.604.08-.869.23l-1.288.737A.25.25 0 013.984 3H1.75a.75.75 0 000 1.5h.428L.066 9.192a.75.75 0 00.154.838l.53-.53-.53.53v.001l.002.002.002.002.006.006.016.015.045.04a3.514 3.514 0 00.686.45A4.492 4.492 0 003 11c.88 0 1.556-.22 2.023-.454a3.515 3.515 0 00.686-.45l.045-.04.016-.015.006-.006.002-.002.001-.002L5.25 9.5l.53.53a.75.75 0 00.154-.838L3.822 4.5h.162c.305 0 .604-.08.869-.23l1.289-.737a.25.25 0 01.124-.033h.984V13h-2.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-2.5V3.5h.984a.25.25 0 01.124.033l1.29.736c.264.152.563.231.868.231h.162l-2.112 4.692a.75.75 0 00.154.838l.53-.53-.53.53v.001l.002.002.002.002.006.006.016.015.045.04a3.517 3.517 0 00.686.45A4.492 4.492 0 0013 11c.88 0 1.556-.22 2.023-.454a3.512 3.512 0 00.686-.45l.045-.04.01-.01.006-.005.006-.006.002-.002.001-.002-.529-.531.53.53a.75.75 0 00.154-.838L13.823 4.5h.427a.75.75 0 000-1.5h-2.234a.25.25 0 01-.124-.033l-1.29-.736A1.75 1.75 0 009.735 2H8.75V.75zM1.695 9.227c.285.135.718.273 1.305.273s1.02-.138 1.305-.273L3 6.327l-1.305 2.9zm10 0c.285.135.718.273 1.305.273s1.02-.138 1.305-.273L13 6.327l-1.305 2.9z"></path></svg>
                    <text class="stat bold" x="189.01" y="12.5" data-testid="license"><?php echo $stats['license'] ?></text>
                </g>
            </g>
        </svg>
    </g>
    <line x1="290" y1="40" x2="290" y2="<?php echo $height - 40 ?>" stroke="#eeecec"></line>
    <g xmlns="http://www.w3.org/2000/svg" transform="translate(300, 55)" data-testid="main-card-progress">
        <svg data-testid="lang-items" x="25">
            <mask id="rect-mask"><rect x="0" y="0" width="300" height="8" fill="white" rx="5"/></mask>
            <?php $cursor = 0. ?>
            <?php foreach ($langs as $lang) { ?>
                <rect
                    mask="url(#rect-mask)"
                    data-testid="lang-progress"
                    x="<?php echo $cursor * 3 ?>"
                    y="0" width="<?php echo $lang['percent'] * 3 ?>"
                    height="8"
                    fill="<?php echo $lang['color'] ?>"
                />
                <?php $cursor += $lang['percent'] ?>
            <?php } ?>
            <g transform="translate(0, 25)">
                <g transform="translate(0, 0)">
                    <?php foreach (array_splice($langs, 0, $bar) as $idx => $lang) { ?>
                        <g transform="translate(0, <?php echo $idx * 25 ?>)">
                            <g class="stagger" style="animation-delay: <?php echo 450 + ($idx * 150) ?>ms">
                                <circle cx="5" cy="6" r="5" fill="<?php echo $lang['color'] ?>"/>
                                <text data-testid="lang-name" x="15" y="10" class="lang"><?php echo $lang['text'] ?></text>
                            </g>
                        </g>
                    <?php } ?>
                </g>
                <g transform="translate(150, 0)">
                    <?php foreach (array_splice($langs, 0, $bar) as $idx => $lang) { ?>
                        <g transform="translate(0, <?php echo $idx * 25 ?>)">
                            <g class="stagger" style="animation-delay: <?php echo 450 + ($idx * 150) ?>ms">
                                <circle cx="5" cy="6" r="5" fill="<?php echo $lang['color'] ?>"/>
                                <text data-testid="lang-name" x="15" y="10" class="lang"><?php echo $lang['text'] ?></text>
                            </g>
                        </g>
                    <?php } ?>
                </g>
            </g>
        </svg>
    </g>
</svg>

<?php
ob_end_flush();
?>