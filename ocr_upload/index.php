<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['invoice'])) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Sanitize filename
    $filename = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['invoice']['name']));
    $filePath = $uploadDir . $filename;
    move_uploaded_file($_FILES['invoice']['tmp_name'], $filePath);

    // Paths
    $tesseractPath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe'; // update if installed elsewhere
    $outputBase = $uploadDir . pathinfo($filename, PATHINFO_FILENAME);
    $outputFile = $outputBase . '.txt';

    // Run tesseract
    $cmd = '"' . $tesseractPath . '" "' . $filePath . '" "' . $outputBase . '" -l eng';
    shell_exec($cmd);

    // Read OCR text
    $text = file_exists($outputFile) ? file_get_contents($outputFile) : '';

    echo "<h3>Raw OCR Output</h3><pre>" . htmlspecialchars($text) . "</pre>";

    // --- Simple parsing ---
    $date = '';
    $store = '';
    $total = '';
    $items = [];

    $lines = preg_split('/\r\n|\r|\n/', $text);

    foreach ($lines as $line) {
        $trim = trim($line);

        // Date
        if (!$date && preg_match('/\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/', $trim)) {
            $date = $trim;
        }

        // Store
        if (!$store && stripos($trim, 'store') !== false) {
            $store = $trim;
        }

        // Total
        if (stripos($trim, 'total') !== false) {
            $parts = preg_split('/\s+/', $trim);
            $last = end($parts);
            if (is_numeric($last)) {
                $total = $last;
            }
        }

        // Items (basic: word + number)
        if (preg_match('/([A-Za-z ]+)\s+(\d+)\s*([A-Za-z0-9]*)/', $trim, $m)) {
            $items[] = [
                'item'  => trim($m[1]),
                'price' => $m[2],
                'qty'   => isset($m[3]) ? $m[3] : ''
            ];
        }
    }

    echo "<h3>Parsed Data</h3>";
    echo "<b>Date:</b> " . htmlspecialchars($date) . "<br>";
    echo "<b>Store:</b> " . htmlspecialchars($store) . "<br>";
    echo "<b>Total:</b> " . htmlspecialchars($total) . "<br>";

    echo "<h4>Items</h4><ul>";
    foreach ($items as $it) {
        echo "<li>" . htmlspecialchars($it['item']) . " - " .
            htmlspecialchars($it['qty']) . " - " .
            htmlspecialchars($it['price']) . "</li>";
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html>
<head><title>Invoice OCR (Tesseract)</title></head>
<body>
<h1>Upload Invoice (Tesseract OCR)</h1>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="invoice" accept="image/*" required>
    <input type="submit" value="Upload & OCR">
</form>
</body>
</html>
