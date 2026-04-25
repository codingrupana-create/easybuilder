<?php
// get_templates.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$dir = __DIR__;
$files = scandir($dir);
$templates = [];

$hardcodedIcons = [
    'elegant_dark.php' => '🌙',
    'structural_modern.php' => '🏛️',
    'modern2withcolor.php' => '🎨',
    'timeline_modern.php' => '⏱️',
    'creative_cards.php' => '🃏',
    'modern_minimal.php' => '🖊️',
    'infographic_style.php' => '📊',
    'classic_professional.php' => '👔',
    'bold_creative.php' => '✨',
    'simple4.php' => '📄',
    'simpletwo.php' => '📄',
    'modern2.php' => '🟦',
    'professional4.php' => '💼',
    'creative6.php' => '🎨',
    'elegant.php' => '🍷',
    'executive3.php' => '🕴️',
    'impact2.php' => '💥',
    'gurp.php' => '📄'
];

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && !in_array($file, ['index.php', 'dashboard.php', 'get_templates.php'])) {
        
        // Make a friendly name
        $name = str_replace(['_', '.php', '2', '4', '6', '3'], [' ', '', '', '', '', ''], $file);
        $name = str_replace('withcolor', ' Color', $name);
        $name = ucwords(trim($name));
        
        $icon = isset($hardcodedIcons[$file]) ? $hardcodedIcons[$file] : '📄';
        
        $templates[] = [
            'file' => $file,
            'name' => $name,
            'icon' => $icon
        ];
    }
}

echo json_encode($templates);
?>
