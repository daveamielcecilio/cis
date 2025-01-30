<?php
require('fpdf186/fpdf.php'); // Include FPDF library
require_once('connect.php'); // Connect to the database

class PDF extends FPDF {
    // Page header
    function Header() {
        // Date and Time (Top Right Corner)
        $this->SetFont('Arial', '', 10);
        date_default_timezone_set('Asia/Manila'); // Set the timezone to your desired location
        $this->Cell(0, 14, 'Date: ' . date('Y-m-d H:i:s'), 0, 0, 'R'); // Align to the right
        $this->Ln(20); // Move to the next line

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Archived Inventory Records', 0, 1, 'C');
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        // Set the position to 1.5 cm from the bottom
        $this->SetY(-15);


        // Add the page number on the lower-right corner
        $this->SetFont('Arial', 'I', 8);
        $this->SetY(-10); // Position it slightly above the edge
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R'); // Align to the right
    }

    // Table header
    function TableHeader() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(194, 164, 255); // Purple Table Header

        // Draw the header row with bottom border
        $this->Cell(60, 10, 'ID Number', 'B', 0, 'C', true);
        $this->Cell(40, 10, 'Item Type', 'B', 0, 'C', true);
        $this->Cell(50, 10, 'Brand Name', 'B', 0, 'C', true);
        $this->Cell(40, 10, 'Quantity', 'B', 1, 'C', true);

        $this->Ln(1); // Add space between the header and the first row
    }

    // Table body
    function TableBody($data) {
        $this->SetFont('Arial', '', 12);
        foreach ($data as $row) {
            $this->Cell(60, 10, $row['supply_id'], 'B', 0, 'C');
            $this->Cell(40, 10, $row['type'], 'B', 0, 'C');
            $this->Cell(50, 10, $row['brand'], 'B', 0, 'C');
            $this->Cell(40, 10, $row['qty'], 'B', 1, 'C');
        }
    }
}

// Build the query
$query = "SELECT * FROM archived";
$query_run = mysqli_query($db, $query);

if (!$query_run) {
    die("Query failed: " . mysqli_error($db));
}

$item_data = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

// Set headers to force the download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="archvied_inventory_records.pdf"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Generate the PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->TableHeader();
$pdf->TableBody($item_data);
$pdf->Output('I', 'archived_inventory_records.pdf'); // Output to view and download
exit;
?>
