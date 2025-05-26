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
 * This is helper class for importing .gz files
 */
class ElegantalEasyImportGz
{
    public static function convertToCsv($file, $model)
    {
        if (!function_exists('gzopen')) {
            return;
        }

        $tmp_file = ElegantalEasyImportTools::getTempDir() . '/' . Tools::passwdGen(8);
        $handle_input = gzopen($file, 'rb');
        $handle_output = fopen($tmp_file, 'wb');
        $buffer_size = 4096; // read 4kb at a time
        while (!gzeof($handle_input)) {
            fwrite($handle_output, gzread($handle_input, $buffer_size));
        }
        fclose($handle_output);
        gzclose($handle_input);

        $mime = ElegantalEasyImportTools::getMimeType($tmp_file);
        if (in_array($mime, ElegantalEasyImportClass::$allowed_mime_types)) {
            copy($tmp_file, $file);
            $extension = ElegantalEasyImportTools::getExtensionFromMimeType($mime);
            switch ($extension) {
                case 'csv':
                case 'txt':
                    ElegantalEasyImportCsv::convertToCsv($file, $model);
                    break;
                case 'xml':
                case 'rss':
                    ElegantalEasyImportXml::convertToCsv($file, $model);
                    break;
                case 'json':
                    ElegantalEasyImportJson::convertToCsv($file, $model);
                    break;
                case 'xls':
                case 'xlsx':
                case 'ods':
                    ElegantalEasyImportExcel::convertToCsv($file, $model);
                    break;
                default:
                    break;
            }
        }
        @unlink($tmp_file);

        return true;
    }
}
