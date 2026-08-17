#!/usr/bin/env python3
import os
import re

files = [
    r'application\libraries\htmlpdf\_tcpdf_5.0.002\tcpdf.php',
    r'application\libraries\htmlpdf\_tcpdf_5.0.002\barcodes.php',
    r'application\libraries\Excel\PHPExcel\Calculation.php',
    r'application\libraries\Excel\PHPExcel\Cell\DefaultValueBinder.php',
    r'application\libraries\Excel\PHPExcel\Calculation\Engineering.php',
    r'application\libraries\Excel\PHPExcel\Calculation\TextData.php',
    r'application\libraries\Excel\PHPExcel\Calculation\Functions.php',
    r'application\libraries\Excel\PHPExcel\ReferenceHelper.php',
    r'application\libraries\Excel\PHPExcel\Reader\SYLK.php'
]

for f in files:
    if os.path.exists(f):
        with open(f, 'r', encoding='utf-8', errors='ignore') as file:
            content = file.read()
        # Replace all $var{n} patterns with $var[n]
        content = re.sub(r'\$([a-zA-Z_][a-zA-Z0-9_]*)\{(\d+)\}', r'$\1[\2]', content)
        with open(f, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f'Fixed {f}')
