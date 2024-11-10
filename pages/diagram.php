<?php
require_once("ChartData.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataFile'])) {
    try {
        $chartData = new ChartData();
        $chartData->loadFromFile($_FILES['dataFile']['tmp_name']);
        $chartData->setChartType($_POST['chartType']);

        // Параметры для передачи в URL
        $chartType = $chartData->getChartType();
        $labels = $chartData->getLabels();
        $values = $chartData->getValues();

        // Получаем метки для осей из загруженного файла
        $xAxisLabel = $chartData->getXAxisLabel();
        $yAxisLabel = $chartData->getYAxisLabel();

        // Редирект на reviews.php с параметрами для отображения диаграммы
        header("Location: reviews.php?chart=" . urlencode($chartType) . 
               "&labels=" . urlencode(serialize($labels)) . 
               "&values=" . urlencode(serialize($values)) . 
               "&xAxisLabel=" . urlencode($xAxisLabel) . 
               "&yAxisLabel=" . urlencode($yAxisLabel));
        exit();
    } catch (Exception $e) {
        echo '<p style="color: red;">Ошибка: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>
