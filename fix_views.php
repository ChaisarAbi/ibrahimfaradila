<?php
$files = [
    'app/Views/customers/index.php',
    'app/Views/customers/create.php',
    'app/Views/customers/edit.php',
    'app/Views/packages/index.php',
    'app/Views/packages/create.php',
    'app/Views/packages/edit.php',
    'app/Views/stock/index.php',
    'app/Views/stock/create.php',
    'app/Views/stock/edit.php',
    'app/Views/kitchen/index.php',
    'app/Views/notifications/history.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "Skipping $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix opening: remove container-fluid, row, and wrap sidebar+main
    $content = preg_replace(
        '/<\?= view\(\'templates\/header\'\) \?>\s*<div class="container-fluid">\s*<div class="row">\s*<\?= view\(\'templates\/sidebar\'\) \?>\s*<div class="col-md-10 main-content">/',
        "<?= view('templates/header') ?>\n<?= view('templates/sidebar') ?>\n<main class=\"main-content\">",
        $content
    );
    
    // Remove <body> if present right after header include
    $content = preg_replace(
        '/<\?= view\(\'templates\/header\'\) \?>\s*<body>/',
        "<?= view('templates/header') ?>",
        $content
    );
    
    // Fix closing: remove </div> x3 and replace with </main>
    $content = preg_replace(
        '/\s*<\/div>\s*<\/div>\s*<\/div>\s*<script src="https:\/\/cdn\.jsdelivr\.net\/npm\/bootstrap/',
        "\n</main>\n<script src=\"https://cdn.jsdelivr.net/npm/bootstrap",
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Fixed: $file\n";
    } else {
        echo "No changes: $file\n";
    }
}

echo "\nDone.\n";