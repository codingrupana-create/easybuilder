import os
import shutil
import re
import json

src_dir = r"c:\xampp\htdocs\resumeantisingle2mobile"
dest_dir = r"c:\xampp\htdocs\resumeantisingle2mobile\android_builder\public"

if not os.path.exists(dest_dir):
    os.makedirs(dest_dir)

templates = []
hardcodedIcons = {
    'elegant_dark.php': '🌙',
    'structural_modern.php': '🏛️',
    'modern2withcolor.php': '🎨',
    'timeline_modern.php': '⏱️',
    'creative_cards.php': '🃏',
    'modern_minimal.php': '🖊️',
    'infographic_style.php': '📊',
    'classic_professional.php': '👔',
    'bold_creative.php': '✨',
    'simple4.php': '📄',
    'simpletwo.php': '📄',
    'modern2.php': '🟦',
    'professional4.php': '💼',
    'creative6.php': '🎨',
    'elegant.php': '🍷',
    'executive3.php': '🕴️',
    'impact2.php': '💥',
    'gurp.php': '📄'
}

# 1. Gather all files to copy
from datetime import datetime
current_date = datetime.now().strftime("%d/%m/%Y")

for item in os.listdir(src_dir):
    src_path = os.path.join(src_dir, item)
    if os.path.isfile(src_path):
        ext = os.path.splitext(item)[1].lower()
        if item in ['get_templates.php', 'dashboard.php']:
            continue
        
        dest_filename = item
        if ext == '.php':
            dest_filename = item.replace('.php', '.html')
            
            # Record it for templates.json if it is a template (not index.php)
            if item not in ['index.php']:
                name = item.replace('_', ' ').replace('.php', '').replace('2', '').replace('4', '').replace('6', '').replace('3', '')
                name = name.replace('withcolor', ' Color').strip().title()
                icon = hardcodedIcons.get(item, '📄')
                templates.append({
                    "file": dest_filename,
                    "name": name,
                    "icon": icon
                })

            # Read and Clean PHP content
            with open(src_path, 'r', encoding='utf-8', errors='ignore') as f:
                content = f.read()
            
            # Remove the POST handling block usually at the start of templates
            # It matches from <?php up to the first ?>
            content = re.sub(r'<\?php.*?if\s*\(\$_SERVER\[\'REQUEST_METHOD\'\].*?exit;\s*\}\s*\?>', '', content, flags=re.DOTALL)
            
            # Replace dynamic date
            content = content.replace('<?php echo date("d/m/Y"); ?>', current_date)
            content = re.sub(r'<\?php echo date\(.*?\);\s*\?>', current_date, content)
            
            # General PHP tag removal (for anything remaining)
            content = re.sub(r'<\?php.*?\?>', '', content, flags=re.DOTALL)
            
            dest_path = os.path.join(dest_dir, dest_filename)
            with open(dest_path, 'w', encoding='utf-8') as f:
                f.write(content)
        else:
            # For non-php files (js, css, images), just copy
            dest_path = os.path.join(dest_dir, dest_filename)
            shutil.copy2(src_path, dest_path)

# 2. Write templates.json
with open(os.path.join(dest_dir, 'templates.json'), 'w', encoding='utf-8') as f:
    json.dump(templates, f, indent=4)

# 3. Rewrite all internal .php references in the new public/ folder
for item in os.listdir(dest_dir):
    dest_path = os.path.join(dest_dir, item)
    if os.path.isfile(dest_path) and dest_path.endswith(('.html', '.js', '.css')):
        with open(dest_path, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        # Replace template.php references
        # Ensure we only replace references cleanly (like href="index.php" or 'creative6.php')
        new_content = re.sub(r'([a-zA-Z0-9_]+)\.php', r'\1.html', content)
        
        if content != new_content:
            with open(dest_path, 'w', encoding='utf-8') as f:
                f.write(new_content)

print(f"Migration complete. Generated {len(templates)} templates inside templates.json.")
