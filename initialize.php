<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

@ini_set('auto_detect_line_endings', '1'); // This is depreceted since PHP v8.1 but it is needed to support files with \r line ending that is used in old MacOS.
// Maybe better replace \r with \n ? file_put_contents($file, str_replace("\r", "\n", file_get_contents($file)));

require_once dirname(__FILE__) . '/classes/ElegantalEasyImportTools.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportMap.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportCsv.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportXml.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportExcel.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportJson.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportZip.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportGz.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportObjectModel.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportModule.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportCategoryMap.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportData.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportError.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportHistory.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportExport.php';
require_once dirname(__FILE__) . '/classes/ElegantalEasyImportClass.php';
