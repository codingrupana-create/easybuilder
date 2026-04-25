import os

directory = r'c:\xampp\htdocs\resumeantisingle2'
templates = [f for f in os.listdir(directory) if f.endswith('.php') and f not in ('index.php', 'dashboard.php')]

script_tag = '<script src="template_switcher.js"></script>'

count = 0
for t in templates:
    path = os.path.join(directory, t)
    with open(path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    if script_tag not in content:
        # Insert before the last </body>
        parts = content.rsplit('</body>', 1)
        if len(parts) == 2:
            new_content = parts[0] + script_tag + '\n</body>' + parts[1]
            with open(path, 'w', encoding='utf-8') as f:
                f.write(new_content)
            count += 1
        else:
            print(f'No </body> found in {t}')

print(f'Done injecting script tag into {count} files.')
