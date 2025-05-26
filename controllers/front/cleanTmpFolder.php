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
 * This is a controller for cleaning tmp folder from redundant files.
 */
class ElegantalEasyImportCleanTmpFolderModuleFrontController extends ModuleFrontController
{
    /** @var ElegantalEasyImport */
    public $module;

    public function display()
    {
        $deleted_files = [];
        $failed_files = [];
        $max_time_for_tmp_files_to_stay = strtotime('-1 hour');

        $tmp_files = $this->getFilesFromTmpFolder();
        if ($tmp_files) {
            $rule_files = $this->getRulesFiles();
            foreach ($tmp_files as $tmp_file) {
                if (in_array($tmp_file, $rule_files)) {
                    continue;
                }
                $modified_time = ElegantalEasyImportTools::getFileModificationTime($tmp_file);
                if ($modified_time && $modified_time < $max_time_for_tmp_files_to_stay) {
                    // It means this file is redundant and has been on the disk for more than 1 hour, so delete it.
                    if (@unlink($tmp_file)) {
                        $deleted_files[] = $tmp_file;
                    } else {
                        $failed_files[] = $tmp_file;
                    }
                }
            }
        }

        if (!$deleted_files && !$failed_files) {
            exit('No redundant files were found.');
        }
        if ($deleted_files) {
            echo 'The following files were deleted: <br> ';
            echo implode(' <br> ', $deleted_files);
        }
        if ($failed_files) {
            echo ' <hr> ';
            echo 'WARNING: The following files were NOT deleted: <br> ';
            echo implode(' <br> ', $failed_files);
        }
        exit;
    }

    private function getFilesFromTmpFolder()
    {
        $tmp_files = [];
        $tmp_dir = ElegantalEasyImportTools::getTempDir();
        $files = scandir($tmp_dir);
        foreach ($files as $f) {
            if ($f == '.' || $f == '..' || $f == 'index.php') {
                continue;
            }
            $file = $tmp_dir . '/' . $f;
            if (is_file($file) && realpath($file)) {
                $tmp_files[] = realpath($file);
            }
        }

        return $tmp_files;
    }

    private function getRulesFiles()
    {
        $import_rule_files = $this->getImportRuleFiles();
        $export_rule_files = $this->getExportRuleFiles();

        return array_merge($import_rule_files, $export_rule_files);
    }

    private function getImportRuleFiles()
    {
        $import_rule_files = [];
        $tmp_dir = ElegantalEasyImportTools::getTempDir();
        $import_rules = ElegantalEasyImportClass::model()->findAll();
        if (empty($import_rules)) {
            return [];
        }
        foreach ($import_rules as $rule) {
            if (isset($rule['csv_file']) && $rule['csv_file']) {
                $file = $tmp_dir . '/' . $rule['csv_file'];
                if (is_file($file) && realpath($file)) {
                    $import_rule_files[] = realpath($file);
                }
            }
        }

        return $import_rule_files;
    }

    private function getExportRuleFiles()
    {
        $export_rule_files = [];
        $export_rules = ElegantalEasyImportExport::model()->findAll();
        if (empty($export_rules)) {
            return [];
        }
        foreach ($export_rules as $rule) {
            if (isset($rule['file_path']) && $rule['file_path'] && is_file($rule['file_path']) && realpath($rule['file_path'])) {
                $export_rule_files[] = realpath($rule['file_path']);
            }
        }

        return $export_rule_files;
    }
}
