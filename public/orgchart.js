document.getElementById('orgchartpdf').addEventListener('click', function() {
    // Replace 'path/to/your/organizational_chart.pdf' with the actual path to your organizational chart PDF file
    const pdfUrl = 'public/assets/BCH-Organizational-Chart-as-of-February-15-2023.pdf';
  
    // Create an anchor element
    const anchor = document.createElement('a');
    anchor.href = pdfUrl;
  
    // Add some attributes to the anchor element to make it hidden and trigger the download
    anchor.style.display = 'none';
    anchor.target = '_blank'; // Open the PDF in a new tab
  
    // Append the anchor element to the document
    document.body.appendChild(anchor);
  
    // Simulate a click on the anchor element to open the PDF
    anchor.click();
  
    // Clean up: remove the anchor element from the document
    document.body.removeChild(anchor);
  });
  