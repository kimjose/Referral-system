import os
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
import markdown2
import re
from bs4 import BeautifulSoup
import requests
from io import BytesIO
import base64
import tempfile
import subprocess

def convert_mermaid_to_image(mermaid_code):
    try:
        # Clean up the mermaid code
        mermaid_code = mermaid_code.strip()
        if mermaid_code.startswith('```mermaid'):
            mermaid_code = mermaid_code[9:]
        if mermaid_code.endswith('```'):
            mermaid_code = mermaid_code[:-3]
        mermaid_code = mermaid_code.strip()

        # Create a temporary file for the Mermaid diagram
        with tempfile.NamedTemporaryFile(suffix='.mmd', delete=False, mode='w', encoding='utf-8') as temp_mmd:
            temp_mmd.write(mermaid_code)
            temp_mmd_path = temp_mmd.name

        # Create a temporary file for the output image
        with tempfile.NamedTemporaryFile(suffix='.png', delete=False) as temp_png:
            temp_png_path = temp_png.name

        # Use mmdc (Mermaid CLI) to convert the diagram to an image
        mmdc_path = '/usr/local/lib/node_modules/@mermaid-js/mermaid-cli/bin/mmdc.js'
        result = subprocess.run(['node', mmdc_path, '-i', temp_mmd_path, '-o', temp_png_path], 
                              capture_output=True, text=True)
        
        if result.returncode != 0:
            print(f"Mermaid conversion error: {result.stderr}")
            return None

        # Read the generated image
        with open(temp_png_path, 'rb') as f:
            image_data = f.read()

        # Clean up temporary files
        os.unlink(temp_mmd_path)
        os.unlink(temp_png_path)

        return BytesIO(image_data)
    except Exception as e:
        print(f"Error converting Mermaid diagram: {e}")
        return None

def add_image_to_doc(doc, img_src, base_path):
    try:
        if img_src.startswith('http://') or img_src.startswith('https://'):
            response = requests.get(img_src)
            if response.status_code == 200:
                image_stream = BytesIO(response.content)
                doc.add_picture(image_stream, width=Inches(6))
        else:
            # Local image
            img_path = os.path.join(base_path, img_src)
            if os.path.exists(img_path):
                doc.add_picture(img_path, width=Inches(6))
            else:
                print(f"Image not found: {img_path}")
    except Exception as e:
        print(f"Error adding image {img_src}: {e}")

def create_document():
    doc = Document()
    
    # Add title
    title = doc.add_heading('Angaza Referral System Documentation', 0)
    title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    
    # Add sections based on mkdocs.yml navigation
    sections = [
        ('User Guide', [
            'getting-started.md',
            'features.md',
            'troubleshooting.md',
            'user-manual.md'
        ]),
        ('Development', [
            'overview.md',
            'CONTRIBUTING.md',
            'setup.md',
            'testing.md',
            'database.md',
            'api-documentation.md',
            'views.md',
            'migrations.md'
        ]),
        ('Architecture', [
            'system-overview.md',
            'system-diagrams.md',
            'class-diagram.md',
            'sequence-diagram.md'
        ]),
        ('API', [
            'overview.md',
            'endpoints.md',
            'controllers.md',
            'models.md',
            'routes.md'
        ]),
        ('Database', [
            'schema.md',
            'migrations.md'
        ]),
        ('Standards', [
            'health-standards.md',
            'hl7-compliance.md',
            'icd11-compliance.md'
        ])
    ]
    
    for section, files in sections:
        # Add section heading
        doc.add_heading(section, level=1)
        
        for file in files:
            section_dir = section.lower().replace(' ', '_')
            file_path = os.path.join('docs', section_dir, file)
            if os.path.exists(file_path):
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                # Convert markdown to HTML
                html = markdown2.markdown(content)
                soup = BeautifulSoup(html, 'html.parser')
                
                # Add file heading
                doc.add_heading(file.replace('.md', '').replace('-', ' ').title(), level=2)
                
                # Process content
                for element in soup.find_all(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'ul', 'ol', 'pre', 'img']):
                    if element.name.startswith('h'):
                        level = int(element.name[1])
                        doc.add_heading(element.get_text(), level=level)
                    elif element.name == 'p':
                        # Check for images inside paragraphs
                        imgs = element.find_all('img')
                        if imgs:
                            for img in imgs:
                                img_src = img.get('src')
                                add_image_to_doc(doc, img_src, os.path.dirname(file_path))
                        text = element.get_text()
                        if text.strip():
                            doc.add_paragraph(text)
                    elif element.name in ['ul', 'ol']:
                        for li in element.find_all('li'):
                            doc.add_paragraph(li.get_text(), style='List Bullet' if element.name == 'ul' else 'List Number')
                    elif element.name == 'pre':
                        # Check if it's a Mermaid diagram
                        code = element.get_text()
                        if code.startswith('```mermaid') or 'sequenceDiagram' in code or 'graph' in code:
                            image_stream = convert_mermaid_to_image(code)
                            if image_stream:
                                doc.add_picture(image_stream, width=Inches(6))
                            else:
                                # If conversion fails, add the code as text
                                p = doc.add_paragraph()
                                run = p.add_run(code)
                                run.font.name = 'Courier New'
                                run.font.size = Pt(10)
                        else:
                            # Regular code block
                            p = doc.add_paragraph()
                            run = p.add_run(code)
                            run.font.name = 'Courier New'
                            run.font.size = Pt(10)
                            run.font.color.rgb = RGBColor(0, 0, 0)
                    elif element.name == 'img':
                        img_src = element.get('src')
                        add_image_to_doc(doc, img_src, os.path.dirname(file_path))
                
                # Add page break between files
                doc.add_page_break()
    
    # Save the document
    doc.save('Angaza_Referral_System_Documentation.docx')

if __name__ == '__main__':
    create_document() 