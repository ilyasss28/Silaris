#!/usr/bin/env python3
import os
import re

# Find all PHP files in the workspace
php_files = []
for root, dirs, files in os.walk('.'):
    # Skip vendor directory from search for now - we'll do system and application
    if 'vendor' in root:
        continue
    for file in files:
        if file.endswith('.php'):
            php_files.append(os.path.join(root, file))

print(f"Found {len(php_files)} PHP files to process")

fixed_count = 0
total_replacements = 0

for f in php_files:
    try:
        with open(f, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        
        original_content = content
        
        # Replace all $var{...} patterns with $var[...]
        # This handles: $var{0}, $var{$i}, $var{expr}, etc.
        # Pattern: $word{...} -> $word[...]
        content = re.sub(r'\$([a-zA-Z_][a-zA-Z0-9_]*(?:->[a-zA-Z_][a-zA-Z0-9_]*)*)\{', r'$\1[', content)
        content = re.sub(r'\}(?=(?:[^[\]]*\[?)*$)', r']', content)
        
        # More targeted approach for the specific problematic pattern
        # $this->_compile_{$section} -> $this->{"_compile_" . $section}
        # But let's try a simpler approach: variable variables
        # $this->prop{expr} -> $this->{"prop" . expr} or similar
        
        if content != original_content:
            replacement_count = len(re.findall(r'\$\w+\{', original_content)) - len(re.findall(r'\$\w+\{', content))
            total_replacements += replacement_count
            with open(f, 'w', encoding='utf-8') as file:
                file.write(content)
            print(f'Fixed {f}')
            fixed_count += 1
    except Exception as e:
        print(f'Error processing {f}: {e}')

print(f"Total files fixed: {fixed_count}")
print(f"Total replacements: {total_replacements}")
