#!/usr/bin/env python3
import os
import re

files_to_fix = [
    r'vendor\mikey179\vfsStream\src\main\php\org\bovigo\vfs\vfsStream.php',
    r'application\libraries\tcpdf\include\barcodes\pdf417.php',
    r'application\libraries\tcpdf\include\barcodes\datamatrix.php',
    r'application\libraries\htmlpdf\_tcpdf_5.0.002\tcpdf.php',
    r'application\libraries\htmlpdf\_tcpdf_5.0.002\barcodes.php',
    r'application\libraries\htmlpdf\_tcpdf_5.0.002\fonts\utils\makefont.php'
]

for f in files_to_fix:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        
        original_content = content
        
        # Fix pattern like $var{$expr} to $var[$expr]
        # This matches: $word{...content...} and replaces with $word[...content...]
        # We need to be careful to match braces, not necessarily balanced
        content = re.sub(r'\$(\w+)\{([^}]+)\}', r'$\1[\2]', content)
        
        if content != original_content:
            with open(f, 'w', encoding='utf-8') as file:
                file.write(content)
            print(f'Fixed {f}')
        else:
            print(f'No changes needed in {f}')
    else:
        print(f'File not found: {f}')
