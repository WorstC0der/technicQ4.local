<?php
class ChartData {
    private $labels = [];
    private $values = [];
    private $chartType;
    private $xAxisLabel;
    private $yAxisLabel;

    public function loadFromFile($filePath) {
        if (!is_uploaded_file($filePath) || !file_exists($filePath)) {
            throw new Exception("Ошибка загрузки файла.");
        }

        $data = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Получаем метки для осей из первой строки
        if (isset($data[0])) {
            list($this->xAxisLabel, $this->yAxisLabel) = array_map('trim', explode(',', $data[0]));
        }

        foreach ($data as $index => $line) {
            if ($index === 0) continue; // Пропускаем первую строку (заголовок)

            $parts = array_map('trim', explode(',', $line));

            if (count($parts) >= 2) {
                $label = $parts[0];
                $value = (float)$parts[1];

                if (!empty($label) && is_numeric($value)) {
                    $this->labels[] = $label;
                    $this->values[] = $value;
                } else {
                    throw new Exception("Некорректные данные в строке: " . htmlspecialchars($line));
                }
            }
        }
    }

    public function setChartType($chartType) {
        $this->chartType = htmlspecialchars($chartType, ENT_QUOTES, 'UTF-8');
    }

    public function getChartType() {
        return $this->chartType;
    }

    public function getLabels() {
        return $this->labels;
    }
    
    public function getValues() {
        return $this->values;
    }

    public function getXAxisLabel() {
        return $this->xAxisLabel;
    }

    public function getYAxisLabel() {
        return $this->yAxisLabel;
    }
}
?>
