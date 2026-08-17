#!/usr/bin/env python3
import os
import re

files = [
    r'application\libraries\collectors\BenchmarkCollector.php',
    r'application\libraries\collectors\CodeIgniterRequestCollector.php',
    r'application\libraries\collectors\IncludedFileCollector.php',
    r'application\libraries\collectors\QueryCollector.php',
    r'application\libraries\collectors\SessionCollector.php',
]

for f in files:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        
        original_content = content
        
        # Add return type declarations to methods that are likely missing them
        # Pattern: public function getName() { -> public function getName(): string {
        content = re.sub(r'public function getName\(\)', r'public function getName(): string', content)
        
        # Pattern: public function collect() { -> public function collect(): array {
        content = re.sub(r'public function collect\(\)', r'public function collect(): array', content)
        
        # Pattern: public function getWidgets() { -> public function getWidgets(): array {
        content = re.sub(r'public function getWidgets\(\)', r'public function getWidgets(): array', content)
        
        # Pattern: public function setDataFormatter( -> public function setDataFormatter( (might already have return type)
        # Let's handle common return types
        content = re.sub(r'public function getAssets\(\)', r'public function getAssets(): array', content)
        content = re.sub(r'public function setAssets\(\)', r'public function setAssets(): \$this', content)
        content = re.sub(r'public function setAdditionalData\(\)', r'public function setAdditionalData(', content)
        
        if content != original_content:
            with open(f, 'w', encoding='utf-8') as file:
                file.write(content)
            print(f'Fixed {f}')
        else:
            print(f'No changes needed in {f}')
    else:
        print(f'File not found: {f}')
