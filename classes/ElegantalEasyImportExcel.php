<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * This is helper class for Excel functions.
 * Known issues:
 * 1) Your requested sheet index: 0 is out of bounds. The actual number of sheets is 0.
 * Solution: /Reader/Xlsx.php -> private function getFromZipArchive(): $contents = preg_replace('/(<\/?)(x+:)/', "$1", $contents);
 */
class ElegantalEasyImportExcel
{
    public static function convertToCsv($file, $model)
    {
        if (class_exists("\PhpOffice\PhpSpreadsheet\IOFactory")) {
            self::convertToCsvByPhpSpreadsheet($file, $model);
        } elseif (class_exists('PHPExcel_IOFactory')) {
            self::convertToCsvByPhpExcel($file, $model);
        } elseif (is_file(dirname(__FILE__) . '/../vendors/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php')) {
            require_once dirname(__FILE__) . '/../vendors/autoload.php';
            self::convertToCsvByPhpSpreadsheet($file, $model);
        } elseif (is_file(dirname(__FILE__) . '/../vendors/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php')) {
            require_once dirname(__FILE__) . '/../vendors/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
            self::convertToCsvByPhpExcel($file, $model);
        } else {
            throw new Exception('PHPExcel library could not be loaded. Please contact module developer.');
        }

        // Process CSV file if needed
        ElegantalEasyImportCsv::convertToCsv($file, $model);

        return true;
    }

    /**
     * Convert excel to csv by PHPExcel
     *
     * @param string $file
     *
     * @return bool
     */
    public static function convertToCsvByPhpSpreadsheet($file, $model)
    {
        if (!class_exists("\PhpOffice\PhpSpreadsheet\IOFactory")) {
            return false;
        }
        $reader = call_user_func(['\PhpOffice\PhpSpreadsheet\IOFactory', 'createReaderForFile'], $file);
        // $reader->setReadDataOnly(true); This should not be used. It caused an issue by changing cell value.
        $phpSpreadsheet = $reader->load($file);
        $writer = call_user_func(['\PhpOffice\PhpSpreadsheet\IOFactory', 'createWriter'], $phpSpreadsheet, 'Csv');
        $sheet_index = 0;
        if (preg_match("/SHEET([\d]+)/", $model->name, $match)) {
            $sheet_count = (int) $phpSpreadsheet->getSheetCount();
            if ($match[1] > 0 && $match[1] <= $sheet_count) {
                $sheet_index = $match[1] - 1;
            }
        }
        call_user_func([$writer, 'setSheetIndex'], $sheet_index);
        call_user_func([$writer, 'setDelimiter'], ';');
        $writer->save($file);
        unset($writer);
        unset($phpSpreadsheet);
        unset($reader);

        return true;
    }

    /**
     * Convert excel to csv by PHPExcel
     *
     * @param string $file
     *
     * @return bool
     */
    public static function convertToCsvByPhpExcel($file, $model)
    {
        if (!class_exists('PHPExcel_IOFactory')) {
            return false;
        }
        $PHPExcel_IOFactory_Class = 'PHPExcel_IOFactory';
        $reader = $PHPExcel_IOFactory_Class::createReaderForFile($file);
        // call_user_func(array($reader, 'setReadDataOnly'), true); This should not be used. It caused an issue by changing cell value.
        $phpExcel = $reader->load($file);
        $writer = $PHPExcel_IOFactory_Class::createWriter($phpExcel, 'CSV');
        $sheet_index = 0;
        if (preg_match("/SHEET([\d]+)/", $model->name, $match)) {
            $sheet_count = (int) $phpExcel->getSheetCount();
            if ($match[1] > 0 && $match[1] <= $sheet_count) {
                $sheet_index = $match[1] - 1;
            }
        }
        call_user_func([$writer, 'setSheetIndex'], $sheet_index);
        call_user_func([$writer, 'setDelimiter'], ';');
        $writer->save($file);
        unset($writer);
        unset($phpExcel);
        unset($reader);

        return true;
    }
}
