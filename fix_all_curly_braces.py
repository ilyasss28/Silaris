#!/usr/bin/env python3
import os
import re
import glob

# Find all PHP files in the application/libraries directory
php_files = []
for root, dirs, files in os.walk(r'application\libraries'):
    for file in files:
        if file.endswith('.php'):
            php_files.append(os.path.join(root, file))

print(f"Found {len(php_files)} PHP files to process")

fixed_count = 0
for f in php_files:
    try:
        with open(f, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        
        original_content = content
        # Replace all $var{n} patterns with $var[n]
        content = re.sub(r'\$([a-zA-Z_][a-zA-Z0-9_]*)\{(\d+)\}', r'$\1[\2]', content)
        
        if content != original_content:
            with open(f, 'w', encoding='utf-8') as file:
                file.write(content)
            print(f'Fixed {f}')
            fixed_count += 1
    except Exception as e:
        print(f'Error processing {f}: {e}')

print(f"Total files fixed: {fixed_count}")
