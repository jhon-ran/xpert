<?php
if (isset($_POST['reporte'])) {
    $reporteHTML = $_POST['reporte'];

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=reporte.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<html><body>";
    echo $reporteHTML;
    echo "</body></html>";
}
?>
