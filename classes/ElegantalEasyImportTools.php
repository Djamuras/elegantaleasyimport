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
 * This is a helper class which provides some functions used all over the module
 */
class ElegantalEasyImportTools
{
    /**
     * Make array storable in database
     *
     * @param array $array
     *
     * @return string
     */
    public static function storable($array)
    {
        $function = 'serialize';

        return @call_user_func('base64_encode', call_user_func($function, $array));
    }

    /**
     * Convert storable string into array
     *
     * @param string $string
     *
     * @return array
     */
    public static function unstorable($string)
    {
        $function = 'unserialize';
        $array = @call_user_func($function, call_user_func('base64_decode', $string));

        return empty($array) ? [] : $array;
    }

    /**
     * Returns formatted file size in GB, MB, KB or bytes
     *
     * @param int $size
     *
     * @return string
     */
    public static function displaySize($size)
    {
        $size = (int) $size;

        if ($size < 1024) {
            $size .= ' bytes';
        } elseif ($size < 1048576) {
            $size = round($size / 1024) . ' KB';
        } elseif ($size < 1073741824) {
            $size = round($size / 1048576, 1) . ' MB';
        } else {
            $size = round($size / 1073741824, 1) . ' GB';
        }

        return $size;
    }

    /**
     * Checks if given module is installed
     *
     * @param string $module_name
     *
     * @return bool
     *
     * @throws PrestaShopException
     */
    public static function isModuleInstalled($module_name)
    {
        return (bool) Module::getModuleIdByName($module_name);
    }

    /**
     * Returns converted dimension value
     *
     * @param float $value
     * @param string $from_unit
     * @param string $to_unit
     *
     * @return float
     */
    public static function getConvertedDimension($value, $from_unit, $to_unit)
    {
        $value = (float) $value;
        if ($from_unit == 'm' && $to_unit == 'cm') { // Convert m to cm
            $value *= 100;
        } elseif ($from_unit == 'mm' && $to_unit == 'cm') { // Convert mm to cm
            $value /= 10;
        } elseif ($from_unit == 'cm' && $to_unit == 'm') { // Convert cm to m
            $value /= 100;
        } elseif ($from_unit == 'mm' && $to_unit == 'm') { // Convert mm to m
            $value /= 1000;
        } elseif ($from_unit == 'cm' && $to_unit == 'mm') { // Convert cm to mm
            $value *= 10;
        } elseif ($from_unit == 'm' && $to_unit == 'mm') { // Convert m to mm
            $value *= 1000;
        }

        return $value;
    }

    /**
     * Returns converted weight value
     *
     * @param float $value
     * @param string $from_unit
     * @param string $to_unit
     *
     * @return float
     */
    public static function getConvertedWeight($value, $from_unit, $to_unit)
    {
        $value = (float) $value;
        $from_unit = Tools::strtolower($from_unit);
        $to_unit = Tools::strtolower($to_unit);
        if ($from_unit == 'g' && $to_unit == 'kg') { // Convert g to kg
            $value /= 1000;
        } elseif ($from_unit == 'kg' && $to_unit == 'g') { // Convert kg to g
            $value *= 1000;
        }

        return $value;
    }

    public static function getModifiedPriceByFormula($price, $formula, $decimal_char = '.')
    {
        $is_price_negative = false;
        if ($price < 0) {
            $is_price_negative = true;
            $price *= -1;
        }
        $original_price = $price;

        // # symbol is used as infinity symbol, so we replace it with big number
        $formula = str_replace('#', '9999999', $formula);

        $formulas = explode(';', str_replace(' ', '', $formula));
        foreach ($formulas as $current_formula) {
            $matches = null;
            if ($current_formula && preg_match_all("/(\[([\d]+[\.\,]?[\d]*)-([\d]+[\.\,]?[\d]*)\])?([\+\-\*\/]|round|floor|ceil)([\d]+[\.\,]?[\d]*)([\%]?)/", $current_formula, $matches)) {
                // Check if price is within given range
                if ($matches[2][0] != '' && $matches[3][0] != '') {
                    if ($decimal_char == ',') {
                        $matches[2][0] = preg_replace('/[^0-9,]/', '', $matches[2][0]);
                        $matches[2][0] = preg_replace('/,/', '.', $matches[2][0]);
                        $matches[3][0] = preg_replace('/[^0-9,]/', '', $matches[3][0]);
                        $matches[3][0] = preg_replace('/,/', '.', $matches[3][0]);
                    } else {
                        $matches[2][0] = preg_replace('/[^0-9.]/', '', $matches[2][0]);
                        $matches[3][0] = preg_replace('/[^0-9.]/', '', $matches[3][0]);
                    }
                    if ($original_price < $matches[2][0] || $original_price > $matches[3][0]) {
                        continue; // Skip this formula because it is out of range
                    }
                }
                // Apply each formula
                foreach ($matches[4] as $key => $arithmetic_operator) {
                    $arithmetic_value = $matches[5][$key];
                    if ($decimal_char == ',') {
                        // If decimal char is comma in the file, the formula must use comma as well. For example: [0 - 25,5]*2,5
                        $arithmetic_value = preg_replace('/[^0-9,]/', '', $arithmetic_value);
                        $arithmetic_value = preg_replace('/,/', '.', $arithmetic_value);
                    } else {
                        $arithmetic_value = preg_replace('/[^0-9.]/', '', $arithmetic_value);
                    }
                    $arithmetic_value = (float) $arithmetic_value;
                    $is_percent = ($matches[6][$key] == '%') ? true : false;
                    switch ($arithmetic_operator) {
                        case '*':
                            if ($is_percent) {
                                $arithmetic_value /= 100;
                            }
                            $price *= $arithmetic_value;
                            break;
                        case '/':
                            if ($is_percent) {
                                $arithmetic_value /= 100;
                            }
                            if ($arithmetic_value > 0) {
                                $price /= $arithmetic_value;
                            }
                            break;
                        case '+':
                            if ($is_percent) {
                                $price *= (1 + ($arithmetic_value / 100));
                            } else {
                                $price += $arithmetic_value;
                            }
                            break;
                        case '-':
                            if ($is_percent) {
                                $price *= (1 - ($arithmetic_value / 100));
                            } else {
                                $price -= $arithmetic_value;
                            }
                            break;
                        case 'round':
                            $arithmetic_value = $arithmetic_value > 6 ? 6 : ($arithmetic_value < 0 ? 0 : (int) $arithmetic_value);
                            $price = round($price, $arithmetic_value);
                            break;
                        case 'ceil':
                            $price = ceil($price);
                            break;
                        case 'floor':
                            $price = floor($price);
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        if ($is_price_negative) {
            $price *= -1;
        }

        return (float) number_format((float) $price, 6, '.', '');
    }

    /**
     * Returns real path of given file
     *
     * @param string $fileName
     *
     * @return bool|string
     */
    public static function getRealPath($fileName)
    {
        $targetFile = self::getTempDir() . '/' . $fileName;
        if ($fileName && is_file($targetFile)) {
            return $targetFile;
        }

        return false;
    }

    /**
     * Creates path to given filename. This path is to a non-existing file. It is used to create the file.
     *
     * @param string $fileName
     *
     * @return string
     */
    public static function createPath($fileName)
    {
        if (empty($fileName)) {
            throw new Exception('File name is not valid.');
        }
        $targetDir = self::getTempDir() . '/';
        $targetFile = $targetDir . $fileName;
        $fileType = Tools::strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        if (is_file($targetFile)) {
            $count = 0;
            while (is_file($targetFile)) {
                ++$count;
                $targetFile = $targetDir . $fileNameWithoutExt . '_' . $count . '.' . $fileType;
            }
        }

        return $targetFile;
    }

    public static function isValidUrl($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            return (bool) preg_match('/^(https?:)\/\/[$~:;\'!#,%&_=\(\)\[\]\{\}\.\? \+\-@\/a-zA-Z0-9]+$/', $url);
        }

        return false;
    }

    public static function cleanDescription($value)
    {
        $jsevents = 'onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange';
        $jsevents .= '|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave|onerror|onselect|onreset|onabort|ondragdrop|onresize|onactivate|onafterprint|onmoveend';
        $jsevents .= '|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onmove';
        $jsevents .= '|onbounce|oncellchange|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondeactivate|ondrag|ondragend|ondragenter|onmousewheel';
        $jsevents .= '|ondragleave|ondragover|ondragstart|ondrop|onerrorupdate|onfilterchange|onfinish|onfocusin|onfocusout|onhashchange|onhelp|oninput|onlosecapture|onmessage|onmouseup|onmovestart';
        $jsevents .= '|onoffline|ononline|onpaste|onpropertychange|onreadystatechange|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onsearch|onselectionchange';
        $jsevents .= '|onselectstart|onstart|onstop';
        $value = preg_replace('/(<[^>]+) (' . $jsevents . ')[\s]*=[\s]*".*?"/is', '$1', $value);
        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
        $value = preg_replace('/{{[\w]+ (.*?)}}/is', '', $value);
        $value = preg_replace('/(<[^>]+) href[\s]*=[\s]*"javascript.*?"/is', '$1', $value);
        $value = html_entity_decode($value); // We need this to convert special HTML entities (&gt;) back to characters

        return $value;
    }

    public static function downloadFileFromUrl($url_to_file, $local_file = null, $username = null, $password = null, $method = 'GET', $post_params = '', $timeout = 500)
    {
        $url_to_file = str_replace('\\', '/', $url_to_file);
        if (self::isValidUrl($url_to_file)) {
            $url_to_file = str_replace(' ', '%20', $url_to_file);
            $url_to_file = str_replace('&amp;', '&', $url_to_file);

            $parced_url = parse_url($url_to_file);
            if (isset($parced_url['host']) && Tools::strtolower($parced_url['host']) == 'www.dropbox.com' && Tools::substr($url_to_file, -5) == '?dl=0') {
                // Convert dropbox URL to downloadable by making ?dl=1
                $url_to_file = substr_replace($url_to_file, '1', -1);
            } elseif (isset($parced_url['host']) && Tools::strtolower($parced_url['host']) == 'drive.google.com' && isset($parced_url['path']) && preg_match("/^(\/file\/d\/)(.+?(?=\/))/", $parced_url['path'], $path_match)) {
                // Convert Google Drive link to direct download link
                $url_to_file = 'https://drive.google.com/uc?id=' . $path_match[2] . '&export=download&confirm=t';
                // Get filename from headers
                if (!$local_file) {
                    $headers = get_headers($url_to_file, true);
                    if (isset($headers['Content-Disposition']) && preg_match('/filename="(.+?)"/', $headers['Content-Disposition'], $match)) {
                        $local_file = self::getTempDir() . '/' . $match[1];
                    }
                }
            } elseif (isset($parced_url['host']) && Tools::strtolower($parced_url['host']) == 'docs.google.com' && isset($parced_url['path']) && preg_match("/^(\/spreadsheets\/d\/.+)(\/edit)$/", $parced_url['path'], $path_match)) {
                // Convert Google Docs link to direct download link
                $url_to_file = 'https://docs.google.com' . $path_match[1] . '/export';
                // Get filename from headers
                if (!$local_file) {
                    $headers = get_headers($url_to_file, true);
                    if (isset($headers['Content-Disposition']) && preg_match('/filename="(.+?)"/', $headers['Content-Disposition'], $match)) {
                        $local_file = self::getTempDir() . '/' . $match[1];
                    }
                }
            }
        }

        // Replace accented characters with urlencode value
        $accented = [
            'á' => '%C3%A1', 'Á' => '%C3%81', 'à' => '%C3%A0', 'À' => '%C3%80', 'â' => '%C3%A2', 'Â' => '%C3%82',
            'ä' => '%C3%A4', 'Ä' => '%C3%84', 'ã' => '%C3%A3', 'Ã' => '%C3%83', 'å' => '%C3%A5', 'Å' => '%C3%85',
            'ā' => '%C4%81', 'Ā' => '%C4%80', 'ȧ' => '%C8%A7', 'Ȧ' => '%C8%A6', 'ă' => '%C4%83', 'Ă' => '%C4%82',
            'ǎ' => '%C7%8E', 'Ǎ' => '%C7%8D', 'ą' => '%C4%85', 'Ą' => '%C4%84', 'æ' => '%C3%A6', 'Æ' => '%C3%86',
            'č' => '%C4%8D', 'Č' => '%C4%8C', 'ç' => '%C3%A7', 'Ç' => '%C3%87', 'ć' => '%C4%87', 'Ć' => '%C4%86',
            'ċ' => '%C4%8B', 'Ċ' => '%C4%8A', 'ĉ' => '%C4%89', 'Ĉ' => '%C4%88', 'ď' => '%C4%8F', 'Ď' => '%C4%8E',
            'ð' => '%C3%B0', 'Ð' => '%C3%90', 'đ' => '%C4%91', 'Đ' => '%C4%90', 'é' => '%C3%A9', 'É' => '%C3%89',
            'è' => '%C3%A8', 'È' => '%C3%88', 'ė' => '%C4%97', 'Ė' => '%C4%96', 'ê' => '%C3%AA', 'Ê' => '%C3%8A',
            'ë' => '%C3%AB', 'Ë' => '%C3%8B', 'ē' => '%C4%93', 'Ē' => '%C4%92', 'ȩ' => '%C8%A9', 'Ȩ' => '%C8%A8',
            'ę' => '%C4%99', 'Ę' => '%C4%98', 'ĕ' => '%C4%95', 'Ĕ' => '%C4%94', 'ě' => '%C4%9B', 'Ě' => '%C4%9A',
            'ġ' => '%C4%A1', 'Ġ' => '%C4%A0', 'ĝ' => '%C4%9D', 'Ĝ' => '%C4%9C', 'ģ' => '%C4%A3', 'Ģ' => '%C4%A2',
            'ğ' => '%C4%9F', 'Ğ' => '%C4%9E', 'ǧ' => '%C7%A7', 'Ǧ' => '%C7%A6', 'ĥ' => '%C4%A5', 'Ĥ' => '%C4%A4',
            'ȟ' => '%C8%9F', 'Ȟ' => '%C8%9E', 'í' => '%C3%AD', 'Í' => '%C3%8D', 'ì' => '%C3%AC', 'Ì' => '%C3%8C',
            'î' => '%C3%AE', 'Î' => '%C3%8E', 'ï' => '%C3%AF', 'Ï' => '%C3%8F', 'ī' => '%C4%AB', 'Ī' => '%C4%AA',
            'į' => '%C4%AF', 'Į' => '%C4%AE', 'ĭ' => '%C4%AD', 'Ĭ' => '%C4%AC', 'ǐ' => '%C7%90', 'Ǐ' => '%C7%8F',
            'ĩ' => '%C4%A9', 'Ĩ' => '%C4%A8', 'ı' => '%C4%B1', 'İ' => '%C4%B0', 'ĵ' => '%C4%B5', 'Ĵ' => '%C4%B4',
            'ķ' => '%C4%B7', 'Ķ' => '%C4%B6', 'ǩ' => '%C7%A9', 'Ǩ' => '%C7%A8', 'ĺ' => '%C4%BA', 'Ĺ' => '%C4%B9',
            'ļ' => '%C4%BC', 'Ļ' => '%C4%BB', 'ľ' => '%C4%BE', 'Ľ' => '%C4%BD', 'ł' => '%C5%82', 'Ł' => '%C5%81',
            'ń' => '%C5%84', 'Ń' => '%C5%83', 'ņ' => '%C5%86', 'Ņ' => '%C5%85', 'ň' => '%C5%88', 'Ň' => '%C5%87',
            'ñ' => '%C3%B1', 'Ñ' => '%C3%91', 'ŋ' => '%C5%8B', 'Ŋ' => '%C5%8A', 'ó' => '%C3%B3', 'Ó' => '%C3%93',
            'ò' => '%C3%B2', 'Ò' => '%C3%92', 'ô' => '%C3%B4', 'Ô' => '%C3%94', 'ö' => '%C3%B6', 'Ö' => '%C3%96',
            'õ' => '%C3%B5', 'Õ' => '%C3%95', 'ō' => '%C5%8D', 'Ō' => '%C5%8C', 'ȯ' => '%C8%AF', 'Ȯ' => '%C8%AE',
            'ő' => '%C5%91', 'Ő' => '%C5%90', 'ǫ' => '%C7%AB', 'Ǫ' => '%C7%AA', 'ŏ' => '%C5%8F', 'Ŏ' => '%C5%8E',
            'ǒ' => '%C7%92', 'Ǒ' => '%C7%91', 'ø' => '%C3%B8', 'Ø' => '%C3%98', 'œ' => '%C5%93', 'Œ' => '%C5%92',
            'þ' => '%C3%BE', 'Þ' => '%C3%9E', 'ř' => '%C5%99', 'Ř' => '%C5%98', 'ŕ' => '%C5%95', 'Ŕ' => '%C5%94',
            'ŗ' => '%C5%97', 'Ŗ' => '%C5%96', 'š' => '%C5%A1', 'Š' => '%C5%A0', 'ś' => '%C5%9B', 'Ś' => '%C5%9A',
            'ŝ' => '%C5%9D', 'Ŝ' => '%C5%9C', 'ş' => '%C5%9F', 'Ş' => '%C5%9E', 'ţ' => '%C5%A3', 'Ţ' => '%C5%A2',
            'ť' => '%C5%A5', 'Ť' => '%C5%A4', 'ú' => '%C3%BA', 'Ú' => '%C3%9A', 'ù' => '%C3%B9', 'Ù' => '%C3%99',
            'û' => '%C3%BB', 'Û' => '%C3%9B', 'ü' => '%C3%BC', 'Ü' => '%C3%9C', 'ū' => '%C5%AB', 'Ū' => '%C5%AA',
            'ű' => '%C5%B1', 'Ű' => '%C5%B0', 'ų' => '%C5%B3', 'Ų' => '%C5%B2', 'ů' => '%C5%AF', 'Ů' => '%C5%AE',
            'ŭ' => '%C5%AD', 'Ŭ' => '%C5%AC', 'ǔ' => '%C7%94', 'Ǔ' => '%C7%93', 'ũ' => '%C5%A9', 'Ũ' => '%C5%A8',
            'ŵ' => '%C5%B5', 'Ŵ' => '%C5%B4', 'ý' => '%C3%BD', 'Ý' => '%C3%9D', 'ÿ' => '%C3%BF', 'Ÿ' => '%C5%B8',
            'ȳ' => '%C8%B3', 'Ȳ' => '%C8%B2', 'ŷ' => '%C5%B7', 'Ŷ' => '%C5%B6', 'ž' => '%C5%BE', 'Ž' => '%C5%BD',
            'ź' => '%C5%BA', 'Ź' => '%C5%B9', 'ż' => '%C5%BC', 'Ż' => '%C5%BB', 'ß' => '%C3%9F', '¡' => '%C2%A1',
            '¿' => '%C2%BF', '̀' => '%CC%80', '́' => '%CC%81', '̂' => '%CC%82', '̃' => '%CC%83', '̄' => '%CC%84',
            '̅' => '%CC%85', '̆' => '%CC%86', '̇' => '%CC%87', '̈' => '%CC%88', '̉' => '%CC%89', '̊' => '%CC%8A',
            '̋' => '%CC%8B', '̌' => '%CC%8C', '̍' => '%CC%8D', '̎' => '%CC%8E', '̏' => '%CC%8F', '̐' => '%CC%90',
            '̑' => '%CC%91', '̒' => '%CC%92', '̓' => '%CC%93', '̔' => '%CC%94', '̕' => '%CC%95', '̖' => '%CC%96',
            '̗' => '%CC%97', '̘' => '%CC%98', '̙' => '%CC%99', '̚' => '%CC%9A', '̛' => '%CC%9B', '̜' => '%CC%9C',
            '̝' => '%CC%9D', '̞' => '%CC%9E', '̟' => '%CC%9F', '̠' => '%CC%A0', '̡' => '%CC%A1', '̢' => '%CC%A2',
            '̣' => '%CC%A3', '̤' => '%CC%A4', '̥' => '%CC%A5', '̦' => '%CC%A6', '̧' => '%CC%A7', '̨' => '%CC%A8',
            '̩' => '%CC%A9', '̪' => '%CC%AA', '̫' => '%CC%AB', '̬' => '%CC%AC', '̭' => '%CC%AD', '̮' => '%CC%AE',
            '̯' => '%CC%AF', '̰' => '%CC%B0', '̱' => '%CC%B1', '̲' => '%CC%B2', '̳' => '%CC%B3', '̴' => '%CC%B4',
            '̵' => '%CC%B5', '̶' => '%CC%B6', '̷' => '%CC%B7', '̸' => '%CC%B8', '̹' => '%CC%B9', '̺' => '%CC%BA',
            '̻' => '%CC%BB', '̼' => '%CC%BC', '̽' => '%CC%BD', '̾' => '%CC%BE', '̿' => '%CC%BF', '̀' => '%CD%80',
            '́' => '%CD%81', '͂' => '%CD%82', '̓' => '%CD%83', '̈́' => '%CD%84', 'ͅ' => '%CD%85', '͆' => '%CD%86',
            '͇' => '%CD%87', '͈' => '%CD%88', '͉' => '%CD%89', '͊' => '%CD%8A', '͋' => '%CD%8B', '͌' => '%CD%8C',
            '͍' => '%CD%8D', '͎' => '%CD%8E', '͏' => '%CD%8F', '͐' => '%CD%90', '͑' => '%CD%91', '͒' => '%CD%92',
            '͓' => '%CD%93', '͔' => '%CD%94', '͕' => '%CD%95', '͖' => '%CD%96', '͗' => '%CD%97', '͘' => '%CD%98',
            '͙' => '%CD%99', '͚' => '%CD%9A', '͛' => '%CD%9B', '͜' => '%CD%9C', '͝' => '%CD%9D', '͞' => '%CD%9E',
            '͟' => '%CD%9F', '͠' => '%CD%A0', '͡' => '%CD%A1', '͢' => '%CD%A2', 'ͣ' => '%CD%A3', 'ͤ' => '%CD%A4',
            'ͥ' => '%CD%A5', 'ͦ' => '%CD%A6', 'ͧ' => '%CD%A7', 'ͨ' => '%CD%A8', 'ͩ' => '%CD%A9', 'ͪ' => '%CD%AA',
            'ͫ' => '%CD%AB', 'ͬ' => '%CD%AC', 'ͭ' => '%CD%AD', 'ͮ' => '%CD%AE', 'ͯ' => '%CD%AF', 'Ͱ' => '%CD%B0',
            'ͱ' => '%CD%B1', 'Ͳ' => '%CD%B2', 'ͳ' => '%CD%B3', 'ʹ' => '%CD%B4', '͵' => '%CD%B5', 'Ͷ' => '%CD%B6',
            'ͷ' => '%CD%B7', '͸' => '%CD%B8', '͹' => '%CD%B9', 'ͺ' => '%CD%BA', 'ͻ' => '%CD%BB', 'ͼ' => '%CD%BC',
            'ͽ' => '%CD%BD', ';' => '%CD%BE', 'Ϳ' => '%CD%BF',
        ]; // -> Example: è. This is e%CC%80,
        $url_to_file = strtr($url_to_file, $accented);

        $url_to_file = self::encodeFullUrl($url_to_file);

        $local_file = $local_file ? $local_file : self::getTempDir() . '/' . rand(1000, 1000000) . '.' . pathinfo($url_to_file, PATHINFO_EXTENSION);

        if (self::isValidUrl($url_to_file)) {
            $file_contents = self::getFileContentsByCurl($url_to_file, $username, $password, $method, $post_params, null, $timeout);
            if (!$file_contents) {
                // Try with deprecated SHA1 signatures
                $file_contents = self::getFileContentsByCurl($url_to_file, $username, $password, $method, $post_params, 'DEFAULT@SECLEVEL=1', $timeout);
                if (!$file_contents) {
                    // Try with file_get_contents() function
                    $file_contents = Tools::file_get_contents($url_to_file);
                }
            }
        } elseif ($method == 'GET') {
            $file_contents = self::getFileContents($url_to_file, $username, $password, $timeout);
        } else {
            throw new Exception('An error occured while downloading the file. Valid URL is required for POST method.');
        }

        if (empty($file_contents)) {
            throw new Exception('An error occured while downloading the file.');
        } elseif (!file_put_contents($local_file, $file_contents)) {
            throw new Exception('An error occured while downloading the file. Failed to save the file.');
        }

        return $local_file;
    }

    public static function getFileContents($url_to_file, $username = null, $password = null, $timeout = 500)
    {
        $header = "Referrer-Policy: no-referrer\r\n";
        $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/54.0.1\r\n";
        if ($username && $password) {
            $header .= 'Authorization: Basic ' . call_user_func('base64_encode', $username . ':' . $password) . "\r\n";
        }
        $context = stream_context_create([
            'http' => [
                'header' => $header,
            ],
        ]);

        return Tools::file_get_contents($url_to_file, false, $context, $timeout, true);
    }

    public static function getFileContentsByCurl($url_to_file, $username = null, $password = null, $method = 'GET', $post_params = '', $ssl_ciphers = null, $timeout = 500)
    {
        if (!self::isValidUrl($url_to_file)) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/54.0.1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url_to_file);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // If it gives SSL error, try to check it with CURLOPT_SSL_VERIFYHOST set to 0
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int) $timeout);
        if ($ssl_ciphers) {
            curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, $ssl_ciphers);
        }
        if (Tools::strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            if ($post_params) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
            }
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        if ($username && $password) {
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM | CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        } elseif ($password) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$password]);
        }

        $file_contents = null;

        $response = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error = '';
        if ($response === false) {
            $error .= '. cURL error: ' . curl_error($ch);
        }

        curl_close($ch);

        if ($response && $http_code == 200) {
            $file_contents = $response;
        } else {
            // die($error);
        }

        return $file_contents;
    }

    public static function encodeFullUrl($url)
    {
        $parts = parse_url($url);

        // Optional but we only sanitize URLs with scheme and host defined
        if (empty($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return $url;
        }

        // Don't encode if the URL is Amazon S3 because it does not like rawurlencode
        if (strpos($parts['host'], 's3') === 0 && strpos($parts['host'], 'amazonaws.com') !== false) {
            return $url;
        }

        $sanitized_path = '';
        if (!empty($parts['path'])) {
            $path_parts = explode('/', $parts['path']);
            // The part might already be urlencoded
            $path_parts = array_map('rawurldecode', $path_parts);
            $path_parts = array_map('rawurlencode', $path_parts);
            $sanitized_path .= implode('/', $path_parts);
        }

        $sanitized_query = '';
        if (!empty($parts['query'])) {
            $query_parts = explode('&', $parts['query']);
            foreach ($query_parts as $query_part) {
                if (empty($query_part)) {
                    continue;
                }
                $query_part_parts = explode('=', $query_part);
                if (isset($query_part_parts[0])) {
                    $sanitized_query .= $sanitized_query ? '&' : '';
                    // The part might already be urlencoded
                    $sanitized_query .= rawurlencode(rawurldecode($query_part_parts[0]));
                    if (isset($query_part_parts[1])) {
                        $sanitized_query .= '=' . rawurlencode(rawurldecode($query_part_parts[1]));
                    }
                }
            }
        }

        // Build the url
        $final_url = $parts['scheme'] . '://' .
            ((!empty($parts['user']) && !empty($parts['pass'])) ? $parts['user'] . ':' . $parts['pass'] . '@' : '') .
            $parts['host'] .
            (!empty($parts['port']) ? ':' . $parts['port'] : '') .
            (!empty($sanitized_path) ? $sanitized_path : '') .
            (!empty($sanitized_query) ? '?' . $sanitized_query : '') .
            (!empty($parts['fragment']) ? '#' . $parts['fragment'] : '');

        return $final_url;
    }

    public static function copyFile($source, $destination, $stream_context = null)
    {
        if (is_null($stream_context) && !preg_match('/^https?:\/\//', $source)) {
            return @copy($source, $destination);
        }

        return @file_put_contents($destination, Tools::file_get_contents($source, false, $stream_context));
    }

    /**
     * Copies image file for product image
     *
     * @param int $id_product
     * @param object $image Image object
     * @param string $url
     *
     * @return bool
     */
    public static function copyImg($id_product, $image, $url, $username = null, $password = null, $convert_to = null)
    {
        // $url = urldecode(trim($url));
        $tmp_file = self::getTempDir() . '/' . rand(1000, 1000000) . '.' . pathinfo($url, PATHINFO_EXTENSION);
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));
        $path = $image->getPathForCreation();
        $context = Context::getContext();

        try {
            if (self::downloadFileFromUrl($url, $tmp_file, $username, $password, 'GET', '', 30)) {
                // Check if real image
                $mime_type = self::getMimeType($tmp_file);
                if (!$mime_type || strpos($mime_type, 'image') === false) {
                    @unlink($tmp_file);

                    return false;
                }

                // Convert image to different format
                if ($convert_to) {
                    $convert_to = Tools::strtolower($convert_to);
                    self::convertImage($tmp_file, $mime_type, $convert_to);
                }

                // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
                if (!ImageManager::checkImageMemoryLimit($tmp_file)) {
                    @unlink($tmp_file);

                    return false;
                }

                ImageManager::resize($tmp_file, $path . '.' . $image->image_format, null, null, $image->image_format);

                // Regenerate. It is enabled because one customer reported that images are not generated when this is disabled.
                $regenerate = true;
                if ($regenerate) {
                    $images_types = ImageType::getImagesTypes('products', true);
                    foreach ($images_types as $image_type) {
                        ImageManager::resize($tmp_file, $path . '-' . call_user_func('stripslashes', $image_type['name']) . '.' . $image->image_format, $image_type['width'], $image_type['height'], $image->image_format);
                        if (in_array($image_type['id_image_type'], $watermark_types)) {
                            Hook::exec('actionWatermark', ['id_image' => $image->id, 'id_product' => $id_product]);
                        }
                    }
                }
            } else {
                @unlink($tmp_file);

                return false;
            }
        } catch (Exception $e) {
            @unlink($tmp_file);

            return false;
        }

        @unlink($tmp_file);

        if (is_file(_PS_TMP_IMG_DIR_ . 'product_' . (int) $id_product . '.jpg')) {
            @unlink(_PS_TMP_IMG_DIR_ . 'product_' . (int) $id_product . '.jpg');
        }
        if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_product . '.jpg')) {
            @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_product . '.jpg');
        }
        if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_product . '_' . (int) $context->shop->id . '.jpg')) {
            @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_product . '_' . (int) $context->shop->id . '.jpg');
        }

        return true;
    }

    /**
     * Converts image from one format to another
     *
     * @param string $file
     * @param string $from_mime
     * @param string $to_format
     *
     * @return bool
     */
    public static function convertImage($file, $from_mime, $to_format)
    {
        if ($from_mime == 'image/webp' && ($to_format == 'jpg' || $to_format == 'jpeg') && function_exists('imagecreatefromwebp')) {
            $image = call_user_func('imagecreatefromwebp', $file);
            call_user_func('imagejpeg', $image, $file, 100);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/webp' && $to_format == 'png' && function_exists('imagecreatefromwebp')) {
            $image = call_user_func('imagecreatefromwebp', $file);
            call_user_func('imagepng', $image, $file, 1);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/webp' && $to_format == 'gif' && function_exists('imagecreatefromwebp')) {
            $image = call_user_func('imagecreatefromwebp', $file);
            call_user_func('imagegif', $image, $file);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/jpeg' && $to_format == 'webp' && function_exists('imagecreatefromjpeg')) {
            $image = call_user_func('imagecreatefromjpeg', $file);
            call_user_func('imagewebp', $image, $file, 100);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/jpeg' && $to_format == 'png' && function_exists('imagecreatefromjpeg')) {
            $image = call_user_func('imagecreatefromjpeg', $file);
            call_user_func('imagepng', $image, $file, 1);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/jpeg' && $to_format == 'gif' && function_exists('imagecreatefromjpeg')) {
            $image = call_user_func('imagecreatefromjpeg', $file);
            call_user_func('imagegif', $image, $file);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/png' && $to_format == 'webp' && function_exists('imagecreatefrompng')) {
            $image = call_user_func('imagecreatefrompng', $file);
            $width = call_user_func('imagesx', $image);
            $height = call_user_func('imagesy', $image);
            $output = call_user_func('imagecreatetruecolor', $width, $height);
            $white = call_user_func('imagecolorallocate', $output, 255, 255, 255);
            call_user_func('imagefilledrectangle', $output, 0, 0, $width, $height, $white);
            call_user_func('imagecopy', $output, $image, 0, 0, 0, 0, $width, $height);
            call_user_func('imagewebp', $output, $file, 100);
            call_user_func('imagedestroy', $image);
            call_user_func('imagedestroy', $output);
        } elseif ($from_mime == 'image/png' && ($to_format == 'jpg' || $to_format == 'jpeg') && function_exists('imagecreatefrompng')) {
            $image = call_user_func('imagecreatefrompng', $file);
            $width = call_user_func('imagesx', $image);
            $height = call_user_func('imagesy', $image);
            $output = call_user_func('imagecreatetruecolor', $width, $height);
            $white = call_user_func('imagecolorallocate', $output, 255, 255, 255);
            call_user_func('imagefilledrectangle', $output, 0, 0, $width, $height, $white);
            call_user_func('imagecopy', $output, $image, 0, 0, 0, 0, $width, $height);
            call_user_func('imagejpeg', $output, $file, 100);
            call_user_func('imagedestroy', $image);
            call_user_func('imagedestroy', $output);
        } elseif ($from_mime == 'image/png' && $to_format == 'gif' && function_exists('imagecreatefrompng')) {
            $image = call_user_func('imagecreatefrompng', $file);
            $width = call_user_func('imagesx', $image);
            $height = call_user_func('imagesy', $image);
            $output = call_user_func('imagecreatetruecolor', $width, $height);
            $white = call_user_func('imagecolorallocate', $output, 255, 255, 255);
            call_user_func('imagefilledrectangle', $output, 0, 0, $width, $height, $white);
            call_user_func('imagecopy', $output, $image, 0, 0, 0, 0, $width, $height);
            call_user_func('imagegif', $output, $file);
            call_user_func('imagedestroy', $image);
            call_user_func('imagedestroy', $output);
        } elseif ($from_mime == 'image/gif' && $to_format == 'webp' && function_exists('imagecreatefromgif')) {
            $image = call_user_func('imagecreatefromgif', $file);
            call_user_func('imagewebp', $image, $file, 100);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/gif' && ($to_format == 'jpg' || $to_format == 'jpeg') && function_exists('imagecreatefromgif')) {
            $image = call_user_func('imagecreatefromgif', $file);
            call_user_func('imagejpeg', $image, $file, 100);
            call_user_func('imagedestroy', $image);
        } elseif ($from_mime == 'image/gif' && $to_format == 'png' && function_exists('imagecreatefromgif')) {
            $image = call_user_func('imagecreatefromgif', $file);
            call_user_func('imagepng', $image, $file, 1);
            call_user_func('imagedestroy', $image);
        }

        return true;
    }

    /**
     * Check if given image_file_1 is a duplicate of image_file_2
     *
     * @param string $image_file_1
     * @param string $image_file_2
     *
     * @return bool
     */
    public static function checkIfImageIsDuplicateByImagick($image_file_1, $image_file_2)
    {
        if (!extension_loaded('imagick') || $image_file_1 == $image_file_2 || !is_file($image_file_1) || !is_file($image_file_2)) {
            return false;
        }

        $imagick_class = 'Imagick';
        $imagick1 = new $imagick_class($image_file_1);
        $imagick2 = new $imagick_class($image_file_2);
        $compare = $imagick1->compareImages($imagick2, $imagick_class::METRIC_MEANSQUAREERROR);
        if ($compare && isset($compare[0], $compare[1]) && $compare[1] >= 0 && $compare[1] <= 0.00001) {
            return true;
        }

        return false;
    }

    /**
     * Returns mime type of given file
     *
     * @param string $file
     * @param string $extension
     *
     * @return string|bool
     */
    public static function getMimeType($file, $extension = null)
    {
        if (!is_file($file)) {
            return false;
        }
        $mime = null;
        if (function_exists('finfo_file') && function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE')) {
            // Use the Fileinfo PECL extension (PHP 5.3+)
            $mime = finfo_file(call_user_func('finfo_open', FILEINFO_MIME_TYPE), $file);
        } elseif (function_exists('mime_content_type')) {
            // Deprecated in PHP 5.3
            $mime = mime_content_type($file);
        } elseif (function_exists('exif_imagetype')) {
            $exif_imagetype = call_user_func('exif_imagetype', $file);
            if ($exif_imagetype == IMAGETYPE_PNG) {
                $mime = 'image/png';
            } elseif ($exif_imagetype == IMAGETYPE_JPEG) {
                $mime = 'image/jpeg';
            }
        }
        $mime = $mime ? Tools::strtolower($mime) : null;

        if ($mime == 'text/plain' || $mime == 'application/octet-stream') {
            $handle = fopen($file, 'r');
            $line = fread($handle, 10);
            $first_char = Tools::substr($line, 0, 1);
            fclose($handle);
            if ($first_char == '{' || $first_char == '[') {
                $mime = 'application/json';
            } elseif (Tools::strtolower(Tools::substr($line, 0, 5)) == '<?xml') {
                $mime = 'application/xml';
            }
        } elseif ($mime == 'application/cdfv2' && $extension == 'xls') {
            $mime = 'application/vnd.ms-excel';
        } elseif ($mime == 'application/zip' && $extension == 'xls') {
            $mime = 'application/vnd.ms-excel';
        } elseif ($mime == 'application/zip' && $extension == 'xlsx') {
            $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } elseif ($mime == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } elseif ($mime == 'text/x-algol68') {
            $mime = 'text/csv';
        } elseif ($mime == 'text/x-fortran') {
            $mime = 'text/csv';
        }

        return $mime ? $mime : false;
    }

    /**
     * Returns file extension for the given mime type
     *
     * @param string $mime
     *
     * @return string|null
     */
    public static function getExtensionFromMimeType($mime)
    {
        $extension = null;
        switch ($mime) {
            case 'text/xml':
            case 'text/html':
            case 'application/xml':
                $extension = 'xml';
                break;
            case 'text/csv':
            case 'text/plain':
            case 'application/csv':
            case 'application/x-csv':
            case 'text/comma-separated-values':
            case 'text/x-comma-separated-values':
            case 'text/tab-separated-values':
            case 'message/news':
                $extension = 'csv';
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.ms-office':
                $extension = 'xls';
                break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $extension = 'xlsx';
                break;
            case 'application/vnd.oasis.opendocument.spreadsheet':
                $extension = 'ods';
                break;
            case 'application/json':
                $extension = 'json';
                break;
            default:
                break;
        }

        return $extension;
    }

    /**
     * Encodes an ISO-8859-1 string to UTF-8
     *
     * @param string $str
     *
     * @return string
     */
    public static function encodeUtf8($str)
    {
        if (function_exists('utf8_encode')) {
            $str = call_user_func('utf8_encode', $str);
        } elseif (function_exists('mb_convert_encoding')) {
            $str = call_user_func('mb_convert_encoding', $str, 'UTF-8', 'ISO-8859-1');
        } elseif (function_exists('iconv')) {
            $str = call_user_func('iconv', 'ISO-8859-1', 'utf-8', $str);
        }

        /* Take care of some characters that do not work in UTF-8.
        https://www.php.net/manual/en/function.utf8-encode.php
        This structure encodes the difference between ISO-8859-1 and Windows-1252, as a map from the UTF-8
        encoding of some ISO-8859-1 control characters to the UTF-8 encoding of the non-control characters
        that Windows-1252 places at the equivalent code points. */
        $cp1252_map = [
            "\xc2\x80" => "\xe2\x82\xac", // EURO SIGN
            "\xc2\x82" => "\xe2\x80\x9a", // SINGLE LOW-9 QUOTATION MARK
            "\xc2\x83" => "\xc6\x92", // LATIN SMALL LETTER F WITH HOOK
            "\xc2\x84" => "\xe2\x80\x9e", // DOUBLE LOW-9 QUOTATION MARK
            "\xc2\x85" => "\xe2\x80\xa6", // HORIZONTAL ELLIPSIS
            "\xc2\x86" => "\xe2\x80\xa0", // DAGGER
            "\xc2\x87" => "\xe2\x80\xa1", // DOUBLE DAGGER
            "\xc2\x88" => "\xcb\x86", // MODIFIER LETTER CIRCUMFLEX ACCENT
            "\xc2\x89" => "\xe2\x80\xb0", // PER MILLE SIGN
            "\xc2\x8a" => "\xc5\xa0", // LATIN CAPITAL LETTER S WITH CARON
            "\xc2\x8b" => "\xe2\x80\xb9", // SINGLE LEFT-POINTING ANGLE QUOTATION
            "\xc2\x8c" => "\xc5\x92", // LATIN CAPITAL LIGATURE OE
            "\xc2\x8e" => "\xc5\xbd", // LATIN CAPITAL LETTER Z WITH CARON
            "\xc2\x91" => "\xe2\x80\x98", // LEFT SINGLE QUOTATION MARK
            "\xc2\x92" => "\xe2\x80\x99", // RIGHT SINGLE QUOTATION MARK
            "\xc2\x93" => "\xe2\x80\x9c", // LEFT DOUBLE QUOTATION MARK
            "\xc2\x94" => "\xe2\x80\x9d", // RIGHT DOUBLE QUOTATION MARK
            "\xc2\x95" => "\xe2\x80\xa2", // BULLET
            "\xc2\x96" => "\xe2\x80\x93", // EN DASH
            "\xc2\x97" => "\xe2\x80\x94", // EM DASH
            "\xc2\x98" => "\xcb\x9c", // SMALL TILDE
            "\xc2\x99" => "\xe2\x84\xa2", // TRADE MARK SIGN
            "\xc2\x9a" => "\xc5\xa1", // LATIN SMALL LETTER S WITH CARON
            "\xc2\x9b" => "\xe2\x80\xba", // SINGLE RIGHT-POINTING ANGLE QUOTATION
            "\xc2\x9c" => "\xc5\x93", // LATIN SMALL LIGATURE OE
            "\xc2\x9e" => "\xc5\xbe", // LATIN SMALL LETTER Z WITH CARON
            "\xc2\x9f" => "\xc5\xb8", // LATIN CAPITAL LETTER Y WITH DIAERESIS
            "\0" => '',
            "\x00" => '',
        ];
        $str = strtr($str, $cp1252_map);

        return $str;
    }

    /**
     * Removes BOM from given string
     *
     * @param string $string
     *
     * @return string
     */
    public static function removeBOM($string)
    {
        // Use json_encode() to view hidden characters
        // Cannot use Tools::substr() because it is not the same as substr(). It caused an issue.
        // \ufeff is the same as chr(0xEF) . chr(0xBB) . chr(0xBF)
        // $string = preg_replace('/[\x00-\x1F\x80-\xFF]/', "", $string); // Remove all non-ASCII characters e.g. \ufeff  This is not needed because it removes characters like ę, ó, ą, ś

        if (substr($string, 0, 3) == chr(0xEF) . chr(0xBB) . chr(0xBF)) { // UTF-8
            $string = substr($string, 3);
        } elseif (substr($string, 0, 4) == chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF)) { // UTF-32BE
            $string = substr($string, 4);
        } elseif (substr($string, 0, 4) == chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00)) { // UTF-32LE
            $string = substr($string, 4);
        } elseif (substr($string, 0, 2) == chr(0xFE) . chr(0xFF)) { // UTF-16BE
            $string = substr($string, 2);
        } elseif (substr($string, 0, 2) == chr(0xFF) . chr(0xFE)) { // UTF-16LE
            $string = substr($string, 2);
        }

        return $string;
    }

    /**
     * Removes wrong characters from XML node name
     *
     * @param string $string
     *
     * @return string
     */
    public static function xmlNodeNameFilter($string)
    {
        if (empty($string)) {
            return $string;
        }
        $string = str_replace(' ', '_', $string);
        $string = preg_replace("/[^a-zA-Z0-9\_\-\:]/", '', $string);
        $string = str_replace('__', '_', $string);

        return $string;
    }

    /**
     * Returns temporary directory name
     *
     * @return string
     */
    public static function getTempDir()
    {
        $filename = dirname(__FILE__) . '/../tmp';
        if (!is_dir($filename)) {
            mkdir($filename);
            chmod($filename, 0777);
        }
        if (is_dir($filename) && is_writable($filename)) {
            return $filename;
        } elseif (function_exists('sys_get_temp_dir')) {
            return sys_get_temp_dir();
        }

        return dirname(__FILE__);
    }

    /**
     * Generates a filename in tmp folder. It does not create actual file yet.
     *
     * @param string $ext
     *
     * @return string
     */
    public static function generateTmpFilename($ext)
    {
        $filename = Tools::passwdGen(8) . '.' . $ext;
        $file_path = self::getTempDir() . '/' . $filename;
        while (is_file($file_path)) {
            $filename = self::generateTmpFilename($ext);
        }

        return $filename;
    }

    /**
     * Deletes given file
     *
     * @param string $filename
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function deleteTmpFile($filename)
    {
        if (empty($filename)) {
            return true;
        }
        $targetFile = self::getTempDir() . '/' . $filename;

        if (is_file($targetFile) && !@unlink($targetFile)) {
            throw new Exception('File could not be deleted.');
        }

        return true;
    }

    /**
     * Deletes given folder if it is empty. index.php and fileType files are excluded.
     *
     * @param string $dir
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function deleteFolderIfEmpty($dir)
    {
        if (empty($dir) || !is_dir($dir)) {
            return;
        }

        $folder_empty = true;
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != 'index.php' && $file != 'fileType') {
                $folder_empty = false;
                break;
            }
        }

        if (!$folder_empty) {
            return;
        }

        if (Tools::substr($dir, -1) != '/') {
            $dir .= '/';
        }

        // We delete index.php and fileType before deleting the folder
        if (is_file($dir . 'index.php')) {
            @unlink($dir . 'index.php');
        }
        if (is_file($dir . 'fileType')) {
            @unlink($dir . 'fileType');
        }

        // Delete the folder
        @rmdir($dir);

        return true;
    }

    public static function deleteFolderRecursively($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        if (substr($dir, strlen($dir) - 1, 1) != '/') {
            $dir .= '/';
        }
        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteFolderRecursively($file);
            } else {
                @unlink($file);
            }
        }
        rmdir($dir);
    }

    /**
     * Returns file creation time in Unix timestamp format.
     * We cannot use just filectime function because it returns inode (permissions, owner, group, or other metadata) change time of the file on Unix.
     * So if the file was created a long time ago, but its metadata was recently changed, filectime returns recent date, not creation date.
     * So in this case we compare filectime with filemtime and return the oldest date, that will be more closer to the real creation date.
     *
     * @param string $file
     *
     * @return string|null
     */
    public static function getFileCreationTime($file)
    {
        if (!is_file($file)) {
            return null;
        }
        $filectime = filectime($file);
        $filemtime = filemtime($file);

        return ($filemtime < $filectime) ? $filemtime : $filectime;
    }

    /**
     * Returns file modification time in Unix timestamp format.
     * We cannot use just filemtime function because it returns content change time of the file.
     * So if the file was copied now and its content was modified a long time ago, filemtime returns very old date, not recent date which is when the file was copied.
     * So in this case we compare filectime with filemtime and return the latest date, that will be more closer to the real modification date.
     *
     * @param string $file
     *
     * @return string|null
     */
    public static function getFileModificationTime($file)
    {
        if (!is_file($file)) {
            return null;
        }
        $filectime = filectime($file);
        $filemtime = filemtime($file);

        return ($filectime > $filemtime) ? $filectime : $filemtime;
    }
}
