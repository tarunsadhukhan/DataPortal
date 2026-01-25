from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib import colors
from reportlab.lib.units import inch

def create_esi_verification_pdf(filename="esi_verification__October 2025_generated.pdf"):
    """
    Generates a PDF file replicating the ESI Verification of Wages document
    for October 2025 using ReportLab.
    """
    doc = SimpleDocTemplate(filename, pagesize=letter,
                            topMargin=0.5*inch, bottomMargin=0.5*inch)
    styles = getSampleStyleSheet()
    Story = []

    # Custom Style for Centering Title
    styles.add(ParagraphStyle(name='TitleStyle',
                             fontSize=12,
                             alignment=1, # Center alignment
                             fontName='Helvetica-Bold'))

    # --- Title ---
    title_text = "VERIFICATION OF WAGES FOR THE PURPOSE OF SECTION 2 (9) OF THE E.S.I ACT'1948"
    Story.append(Paragraph(title_text, styles['TitleStyle']))
    Story.append(Spacer(1, 0.25 * inch))

    # --- Header Information ---
    header_data = [
        ["1. Name and address of the Employer with Code No.", ": The Empire Jute Co Ltd, 15, B.T. Road, Kolkata - 700123 (Code: 40000031740000102)"],
        ["2. Name of the employee", ": D KANTA"],
        ["3. Insurance No.", ": 4005270591"],
        ["4. Date of Employment Injury", ": "],
        ["5. Location of Injury", ": "],
        ["6. Contribution Period in which E.I took place", ": "],
    ]

    header_table = Table(header_data, colWidths=[2.8 * inch, 4.2 * inch])
    header_table.setStyle(TableStyle([
        ('FONTNAME', (0, 0), (-1, -1), 'Helvetica'),
        ('FONTNAME', (0, 0), (0, -1), 'Helvetica-Bold'),
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('LEFTPADDING', (0, 0), (-1, -1), 0),
        ('RIGHTPADDING', (0, 0), (-1, -1), 0),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 2),
    ]))
    Story.append(header_table)
    Story.append(Spacer(1, 0.1 * inch))

    # --- Wage Particulars Header ---
    Story.append(Paragraph("7. Particulars of Wage paid/Payable during the Wage Period i.e. <b>October 2025</b>", styles['Normal']))
    Story.append(Spacer(1, 0.1 * inch))

    # --- Wage Data Table ---
    # Rows: Item, Rate/Notes, Payable (Rs.), Item, Deduction (Rs.), Paid (Rs.)
    wage_data = [
        ['WORKING DAYS', '', '20', '', '', ''],
        ['FESTIVAL DAYS', '', '', '', '', ''],
        ['BASIC PAY RATE/PD', '@624.82', '12,496.30', 'PF', '1,251.00', ''],
        ['DA/A.D.A.', '', '0.00', 'ESI', '98.00', ''],
        ['SPECIAL ALLOWANCE', '', '0.00', '', '', '0.00'],
        ['HRA', '@3.75%', '468.61', 'PTax', '110.00', ''],
        ['P.D/Incentive', '', '0.00', '', '', '0.00'],
        ['Other Wage(s)', '', '0.00', '', '', '0.00'],
        ['TOTAL (Rs.)', '', '12,964.91', '', '1,459.00', '11,505.91']
    ]

    # Column widths: Item (1.5), Rate (1.0), Payable (1.5), Item (1.0), Deduction (1.0), Paid (1.5)
    col_widths = [1.5*inch, 1.0*inch, 1.5*inch, 1.0*inch, 1.0*inch, 1.5*inch]
    wage_table = Table(wage_data, colWidths=col_widths)

    # Style for the main wage table
    wage_table.setStyle(TableStyle([
        ('FONTNAME', (0, 0), (-1, -1), 'Helvetica'),
        ('FONTSIZE', (0, 0), (-1, -1), 10),
        ('ALIGN', (0, 0), (0, -1), 'LEFT'),  # Labels left-aligned
        ('ALIGN', (2, 0), (2, -1), 'RIGHT'), # Payable right-aligned
        ('ALIGN', (3, 0), (3, -1), 'LEFT'),  # Deduction Labels left-aligned
        ('ALIGN', (4, 0), (4, -1), 'RIGHT'), # Deduction Amount right-aligned
        ('ALIGN', (5, 0), (5, -1), 'RIGHT'), # Paid Amount right-aligned
        ('LINEBELOW', (0, -1), (-1, -1), 0.5, colors.black), # Line below TOTAL row
        ('LINEABOVE', (0, -1), (-1, -1), 0.5, colors.black), # Line above TOTAL row
        ('FONTNAME', (0, -1), (-1, -1), 'Helvetica-Bold'), # Total row bold
    ]))

    Story.append(wage_table)
    Story.append(Spacer(1, 0.4 * inch))

    # --- Signatures and Verification ---
    Story.append(Paragraph("Verified from Wage Records", styles['Normal']))
    Story.append(Spacer(1, 0.5 * inch))

    signature_data = [
        ["Investigating Officer", "", "Signature of the Employer"],
        ["(Umesh Chandra Sahoo)", "", ""],
        ["Branch Manager", "", ""],
        ["Counter Signed", "", ""],
        ["Branch Manager", "", ""],
        ["(Umesh Chandra Sahoo)", "", ""],
        ["Branch Manager", "", ""],
    ]

    # Adjust widths for signature section layout
    sig_table = Table(signature_data, colWidths=[2.5 * inch, 2.0*inch, 2.5 * inch])

    sig_table.setStyle(TableStyle([
        ('FONTNAME', (0, 0), (-1, -1), 'Helvetica'),
        ('FONTSIZE', (0, 0), (-1, -1), 10),
        ('VALIGN', (0, 0), (-1, -1), 'TOP'),
        ('LEFTPADDING', (0, 0), (-1, -1), 0),
        ('RIGHTPADDING', (0, 0), (-1, -1), 0),
    ]))

    Story.append(sig_table)

    # Build the document
    doc.build(Story)
    print(f"PDF successfully created as: {filename}")

if __name__ == '__main__':
    create_esi_verification_pdf()