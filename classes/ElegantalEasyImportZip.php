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
 * This is helper class for importing .zip files
 */
class ElegantalEasyImportZip
{
    public static function convertToCsv($file, $model)
    {
        if (!class_exists('ZipArchive')) {
            return;
        }
        $path_parts = pathinfo($file);
        $zip_dir = ElegantalEasyImportTools::getTempDir() . '/' . $path_parts['filename'];
        $zip = new ZipArchive();
        $zip_open = $zip->open($file);
        if ($zip_open === true) {
            $zip->extractTo($zip_dir);
            $zip->close();
            $files = array_diff(scandir($zip_dir), ['.', '..']);
            if ($files) {
                foreach ($files as $zip_file) {
                    $extension = Tools::strtolower(pathinfo($zip_file, PATHINFO_EXTENSION));
                    if (in_array($extension, ElegantalEasyImportClass::$allowed_file_types)) {
                        $mime = ElegantalEasyImportTools::getMimeType($zip_dir . '/' . $zip_file, $extension);
                        if (in_array($mime, ElegantalEasyImportClass::$allowed_mime_types)) {
                            copy($zip_dir . '/' . $zip_file, $file);
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
                            break;
                        }
                    }
                }
            }
            ElegantalEasyImportTools::deleteFolderRecursively($zip_dir);
        }

        return true;
    }

    public static function compressToZip($file)
    {
        if (!class_exists('ZipArchive') || !is_file($file)) {
            return;
        }
        $pathinfo = pathinfo($file);
        $zip_file = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.zip';
        if (is_file($zip_file)) {
            @unlink($zip_file);
        }
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE);
        $zip->addFile($file, $pathinfo['basename']);
        $zip->close();
    }
}
