<?php
include 'utils.php';

$dark = isset($_GET['theme']) && $_GET['theme'] === 'dark';
$username = get('username', '');

$stats = fetch("user/$username");
if (!$stats) {
    include 'error.php';
    exit;
}

list($langs, $bar, $height, $header, $background) = extracted($stats['languages'], $dark);
?>
<svg width="540" viewBox="0 0 660 <?php echo $height + 1 ?>" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="descId">
    <title id="titleId"><?php echo $username ?>'s Code Stats</title>
    <desc id="descId">User Card</desc>
    <style>
        .icon {
            display: block;
            fill: #8b849e;
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
            .stat {
                font-size: 12px;
            }
        }
        .stat {
            font: 600 14px 'Segoe UI', Ubuntu, "Helvetica Neue", Sans-Serif;
            fill: <?php echo $header ?>;
        }
        .stagger {
            opacity: 0;
            animation: fadeInAnimation 0.3s ease-in-out forwards;
        }
        .bold {
            font-weight: 700;
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
        <g transform="translate(-14, 0)">
            <svg y="-13" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon">
                <?php if (!$stats['org']) { ?><path fill-rule="evenodd" d="M10.5 5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm.061 3.073a4 4 0 10-5.123 0 6.004 6.004 0 00-3.431 5.142.75.75 0 001.498.07 4.5 4.5 0 018.99 0 .75.75 0 101.498-.07 6.005 6.005 0 00-3.432-5.142z"></path>
                <?php } else { ?><path fill-rule="evenodd" d="M1.5 14.25c0 .138.112.25.25.25H4v-1.25a.75.75 0 01.75-.75h2.5a.75.75 0 01.75.75v1.25h2.25a.25.25 0 00.25-.25V1.75a.25.25 0 00-.25-.25h-8.5a.25.25 0 00-.25.25v12.5zM1.75 16A1.75 1.75 0 010 14.25V1.75C0 .784.784 0 1.75 0h8.5C11.216 0 12 .784 12 1.75v12.5c0 .085-.006.168-.018.25h2.268a.25.25 0 00.25-.25V8.285a.25.25 0 00-.111-.208l-1.055-.703a.75.75 0 11.832-1.248l1.055.703c.487.325.779.871.779 1.456v5.965A1.75 1.75 0 0114.25 16h-3.5a.75.75 0 01-.197-.026c-.099.017-.2.026-.303.026h-3a.75.75 0 01-.75-.75V14h-1v1.25a.75.75 0 01-.75.75h-3zM3 3.75A.75.75 0 013.75 3h.5a.75.75 0 010 1.5h-.5A.75.75 0 013 3.75zM3.75 6a.75.75 0 000 1.5h.5a.75.75 0 000-1.5h-.5zM3 9.75A.75.75 0 013.75 9h.5a.75.75 0 010 1.5h-.5A.75.75 0 013 9.75zM7.75 9a.75.75 0 000 1.5h.5a.75.75 0 000-1.5h-.5zM7 6.75A.75.75 0 017.75 6h.5a.75.75 0 010 1.5h-.5A.75.75 0 017 6.75zM7.75 3a.75.75 0 000 1.5h.5a.75.75 0 000-1.5h-.5z"></path><?php } ?>
            </svg>
        </g>
        <g transform="translate(8, 0)">
            <text x="0" y="0" class="header" data-testid="header"><?php echo $username ?></text>
        </g>
        <g transform="translate(<?php echo 100 + (strlen($username) - 8) * 8 ?>, 0)" class="stagger">
            <svg y="-13" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon">
                <path fill-rule="evenodd" d="M11.536 3.464a5 5 0 010 7.072L8 14.07l-3.536-3.535a5 5 0 117.072-7.072v.001zm1.06 8.132a6.5 6.5 0 10-9.192 0l3.535 3.536a1.5 1.5 0 002.122 0l3.535-3.536zM8 9a2 2 0 100-4 2 2 0 000 4z"></path>
            </svg>
            <text x="20" class="stat bold"><?php echo $stats['location'] ?></text>
        </g>
    </g>
    <g data-testid="main-card-info" transform="translate(0, 55)"><svg x="0" y="0">
            <g transform="translate(0, 0)">
                <g class="stagger" style="animation-delay: 450ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">Followers:</text>
                    <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M5.5 3.5a2 2 0 100 4 2 2 0 000-4zM2 5.5a3.5 3.5 0 115.898 2.549 5.507 5.507 0 013.034 4.084.75.75 0 11-1.482.235 4.001 4.001 0 00-7.9 0 .75.75 0 01-1.482-.236A5.507 5.507 0 013.102 8.05 3.49 3.49 0 012 5.5zM11 4a.75.75 0 100 1.5 1.5 1.5 0 01.666 2.844.75.75 0 00-.416.672v.352a.75.75 0 00.574.73c1.2.289 2.162 1.2 2.522 2.372a.75.75 0 101.434-.44 5.01 5.01 0 00-2.56-3.012A3 3 0 0011 4z"></path></svg>
                    <text class="stat bold" x="192.01" y="12.5" data-testid="followers"><?php echo $stats['followers'] ?></text>
                </g>
            </g><g transform="translate(0, 25)">
                <g class="stagger" style="animation-delay: 600ms" transform="translate(25, 0)">
                    <text class="stat bold" y="12.5">Repos:</text>
                    <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M2 2.5A2.5 2.5 0 014.5 0h8.75a.75.75 0 01.75.75v12.5a.75.75 0 01-.75.75h-2.5a.75.75 0 110-1.5h1.75v-2h-8a1 1 0 00-.714 1.7.75.75 0 01-1.072 1.05A2.495 2.495 0 012 11.5v-9zm10.5-1V9h-8c-.356 0-.694.074-1 .208V2.5a1 1 0 011-1h8zM5 12.25v3.25a.25.25 0 00.4.2l1.45-1.087a.25.25 0 01.3 0L8.6 15.7a.25.25 0 00.4-.2v-3.25a.25.25 0 00-.25-.25h-3.5a.25.25 0 00-.25.25z"></path></svg>
                    <text class="stat bold" x="192.01" y="12.5" data-testid="repos"><?php echo $stats['repos'] ?></text>
                </g>
                <g transform="translate(0, 25)">
                    <g class="stagger" style="animation-delay: 750ms" transform="translate(25, 0)">
                        <text class="stat bold" y="12.5">Stars Earned:</text>
                        <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M8 .25a.75.75 0 01.673.418l1.882 3.815 4.21.612a.75.75 0 01.416 1.279l-3.046 2.97.719 4.192a.75.75 0 01-1.088.791L8 12.347l-3.766 1.98a.75.75 0 01-1.088-.79l.72-4.194L.818 6.374a.75.75 0 01.416-1.28l4.21-.611L7.327.668A.75.75 0 018 .25zm0 2.445L6.615 5.5a.75.75 0 01-.564.41l-3.097.45 2.24 2.184a.75.75 0 01.216.664l-.528 3.084 2.769-1.456a.75.75 0 01.698 0l2.77 1.456-.53-3.084a.75.75 0 01.216-.664l2.24-2.183-3.096-.45a.75.75 0 01-.564-.41L8 2.694v.001z"></path></svg>
                        <text class="stat bold" x="192.01" y="12.5" data-testid="stars"><?php echo $stats['stars'] ?></text>
                    </g>
                </g>
                <g transform="translate(0, 50)">
                    <g class="stagger" style="animation-delay: 1050ms" transform="translate(25, 0)">
                        <text class="stat bold" y="12.5">Forks Earned:</text>
                        <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M5 3.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm0 2.122a2.25 2.25 0 10-1.5 0v.878A2.25 2.25 0 005.75 8.5h1.5v2.128a2.251 2.251 0 101.5 0V8.5h1.5a2.25 2.25 0 002.25-2.25v-.878a2.25 2.25 0 10-1.5 0v.878a.75.75 0 01-.75.75h-4.5A.75.75 0 015 6.25v-.878zm3.75 7.378a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm3-8.75a.75.75 0 100-1.5.75.75 0 000 1.5z"></path></svg>
                        <text class="stat bold" x="192.01" y="12.5" data-testid="forks"><?php echo $stats['forks'] ?></text>
                    </g>
                </g>
                <g transform="translate(0, 75)">
                    <g class="stagger" style="animation-delay: 1200ms" transform="translate(25, 0)">
                        <text class="stat bold" y="12.5">Open Issues:</text>
                        <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path d="M8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path><path fill-rule="evenodd" d="M8 0a8 8 0 100 16A8 8 0 008 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z"></path></svg>
                        <text class="stat bold" x="192.01" y="12.5" data-testid="issues"><?php echo $stats['issues'] ?></text>
                    </g>
                </g>
                <g transform="translate(0, 100)">
                    <g class="stagger" style="animation-delay: 1350ms" transform="translate(25, 0)">
                        <text class="stat bold" y="12.5">Watchers Total:</text>
                        <svg x="169.01" aria-hidden="true" height="16" viewBox="0 0 16 16" width="16" data-view-component="true" class="icon"><path fill-rule="evenodd" d="M1.679 7.932c.412-.621 1.242-1.75 2.366-2.717C5.175 4.242 6.527 3.5 8 3.5c1.473 0 2.824.742 3.955 1.715 1.124.967 1.954 2.096 2.366 2.717a.119.119 0 010 .136c-.412.621-1.242 1.75-2.366 2.717C10.825 11.758 9.473 12.5 8 12.5c-1.473 0-2.824-.742-3.955-1.715C2.92 9.818 2.09 8.69 1.679 8.068a.119.119 0 010-.136zM8 2c-1.981 0-3.67.992-4.933 2.078C1.797 5.169.88 6.423.43 7.1a1.619 1.619 0 000 1.798c.45.678 1.367 1.932 2.637 3.024C4.329 13.008 6.019 14 8 14c1.981 0 3.67-.992 4.933-2.078 1.27-1.091 2.187-2.345 2.637-3.023a1.619 1.619 0 000-1.798c-.45-.678-1.367-1.932-2.637-3.023C11.671 2.992 9.981 2 8 2zm0 8a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                        <text class="stat bold" x="192.01" y="12.5" data-testid="watchers"><?php echo $stats['watchers'] ?></text>
                    </g>
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
