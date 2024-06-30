<?php
require 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

// Define the PDF and CSV file paths
$pdfFilePath = './pdf/INV0085729_10.2023.pdf';
$csvFilePath = './csv/output.csv';

// Parse the PDF file
$parser = new Parser();
$pdf = $parser->parseFile($pdfFilePath);

// Extract text from PDF
$text = $pdf->getText();

// Define a function to parse the text into structured data
function parseTextToData($text) {
    $data = [];
    $lines = explode("\n", $text);

    // Loop through each line to extract relevant data
    foreach ($lines as $line) {
      $parts = preg_split('/\s{2,}/', $line);

        // Ensure the line contains all required parts
        if (count($parts) >= 4) {
            $location = trim($parts[0]);
            $description = trim($parts[1]);
            $servicePeriod = trim($parts[2]);
            $amount = trim($parts[3]);

            // Add the extracted data to the array
            $data[] = [
                'Location' => $location,
                'Description' => $description,
                'Service Period' => $servicePeriod,
                'Amount' => $amount,
            ];
        }
  }

    return $data;
}

// Parse the extracted text
$data = parseTextToData($text);

// Open a file in write mode to create CSV
$csvFile = fopen($csvFilePath, 'w');

// Write the header row
fputcsv($csvFile, ['Location', 'Description', 'Service Period', 'Amount']);

// Write data rows
foreach ($data as $row) {
    fputcsv($csvFile, $row);
}

// Close the CSV file
fclose($csvFile);

echo "CSV file created successfully at $csvFilePath\n";