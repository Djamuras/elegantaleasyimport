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
 * This is helper class for JSON functions
 */
class ElegantalEasyImportJson
{
    public static function convertToCsv($file, $model)
    {
        $entity = $model->entity;
        $multiple_value_separator = $model->multiple_value_separator;

        $file_content = Tools::file_get_contents($file);
        $file_content = ElegantalEasyImportTools::removeBOM($file_content);
        $first_array = json_decode($file_content, true);
        if ($first_array === null && function_exists('iconv')) {
            $file_content_iconv = iconv('UTF-8', 'UTF-8//IGNORE', $file_content);
            if ($file_content_iconv !== false) {
                $file_content = $file_content_iconv;
            }
            $first_array = json_decode($file_content, true);
        }
        $array = $first_array;

        if (!$array || !is_array($array)) {
            throw new Exception('JSON file is not valid. ' . (function_exists('json_last_error') ? json_last_error() : ''));
        }

        if (isset($array['products'][0])) {
            $array = $array['products'];
        } elseif (isset($array['data']['products'][0])) {
            $array = $array['data']['products'];
        }

        if (isset($array['outListinoAnagraficaMagazzino']['@versione'])) {
            unset($array['outListinoAnagraficaMagazzino']['@versione']);
        }
        if (isset($array['outListinoAnagraficaMagazzino']['@riferimentoAnno'])) {
            unset($array['outListinoAnagraficaMagazzino']['@riferimentoAnno']);
        }
        if (isset($array['outListinoAnagraficaMagazzino']['@riferimentoMese'])) {
            unset($array['outListinoAnagraficaMagazzino']['@riferimentoMese']);
        }
        if (isset($array['outListinoAnagraficaMagazzino']['@riferimentoGiorno'])) {
            unset($array['outListinoAnagraficaMagazzino']['@riferimentoGiorno']);
        }
        if (isset($array['outListinoAnagraficaMagazzino']['@riferimentoOra'])) {
            unset($array['outListinoAnagraficaMagazzino']['@riferimentoOra']);
        }
        if (isset($array['outListinoAnagraficaMagazzino']['@identificativo'])) {
            unset($array['outListinoAnagraficaMagazzino']['@identificativo']);
        }
        if (isset($array['outListinoAnagraficaMagazzino']['@cliente'])) {
            unset($array['outListinoAnagraficaMagazzino']['@cliente']);
        }
        if (isset($array['error'])) {
            unset($array['error']);
        }
        if (isset($array['update'])) {
            unset($array['update']);
        }
        if (isset($array['service'])) {
            unset($array['service']);
        }
        if (isset($array['Success'])) {
            unset($array['Success']);
        }
        if (isset($array['resource']['name'], $array['stock'][0])) {
            unset($array['resource']);
        }
        if (isset($array['response_code'])) {
            unset($array['response_code']);
        }
        if (isset($array['response_message'])) {
            unset($array['response_message']);
        }
        if (isset($array['response_language'])) {
            unset($array['response_language']);
        }
        if (isset($array['response_language'])) {
            unset($array['response_language']);
        }
        if (isset($array['response_created'])) {
            unset($array['response_created']);
        }
        if (isset($array['recordsFound'])) {
            unset($array['recordsFound']);
        }
        if (isset($array['pageSize'])) {
            unset($array['pageSize']);
        }
        if (isset($array['pageNumber'])) {
            unset($array['pageNumber']);
        }
        if (isset($array['@odata.context'])) {
            unset($array['@odata.context']);
        }
        if (isset($array['replicationid'])) {
            unset($array['replicationid']);
        }
        if (isset($array['result']) && !is_array($array['result'])) {
            unset($array['result']);
        }

        // Find array of products. Array of products are under a node which has numeric keys.
        $key = key($array);
        while ($key !== 0 && $key && isset($array[$key])) {
            $array = $array[$key];
            if (is_array($array)) {
                $key = key($array);
            } else {
                $key = false;
            }
        }

        if (!is_array($array)) {
            if (isset($first_array['products']) && is_array($first_array['products'])) {
                $array = $first_array['products'];
            } elseif (is_array($first_array) && isset(reset($first_array)['stock'][0])) {
                $array = reset($first_array)['stock'];
            } else {
                $array = $first_array;
            }
        }

        // Process products array before writing to csv
        if ($entity == 'combination' && isset($array[0], $array[0]['id'], $array[0]['title'], $array[0]['body_html'], $array[0]['product_type'], $array[0]['variants'])) {
            foreach ($array as $key => $product) {
                $attributeName = (isset($product['options'][0]['name']) && $product['options'][0]['name']) ? $product['options'][0]['name'] : 'Size';
                if ($product['variants'] && is_array($product['variants']) && isset($product['variants'][0]['product_id'])) {
                    foreach ($product['variants'] as $variant) {
                        $variant['AttributeName'] = $attributeName;
                        $array[] = $variant;
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'product' && isset($array[0]['id'], $array[0]['sku'], $array[0]['qty_price'][1])) {
            foreach ($array as $key => $product) {
                foreach ($product['qty_price'] as $qty => $price) {
                    $array[$key]['qty_price_' . $qty] = $price;
                }
                unset($array[$key]['qty_price']);
            }
        } elseif ($entity == 'product' && isset($array[0]['_id'], $array[0]['infoCar']['description']['descriptionId'])) {
            foreach ($array as $key => $product) {
                $array[$key]['description_title_en'] = isset($product['infoCar']['description']['descriptionId']['title']['en']) ? $product['infoCar']['description']['descriptionId']['title']['en'] : '';
                $array[$key]['description_title_it'] = isset($product['infoCar']['description']['descriptionId']['title']['it']) ? $product['infoCar']['description']['descriptionId']['title']['it'] : '';
                $array[$key]['description_title_sq'] = isset($product['infoCar']['description']['descriptionId']['title']['sq']) ? $product['infoCar']['description']['descriptionId']['title']['sq'] : '';
                $array[$key]['description_category_en'] = isset($product['infoCar']['description']['descriptionId']['category']['en']) ? $product['infoCar']['description']['descriptionId']['category']['en'] : '';
                $array[$key]['description_category_it'] = isset($product['infoCar']['description']['descriptionId']['category']['it']) ? $product['infoCar']['description']['descriptionId']['category']['it'] : '';
                $array[$key]['description_category_sq'] = isset($product['infoCar']['description']['descriptionId']['category']['sq']) ? $product['infoCar']['description']['descriptionId']['category']['sq'] : '';
                unset($array[$key]['infoCar']);
            }
        } elseif (isset($array[0]['type'], $array[0]['id'], $array[0]['attributes']['name'], $array[0]['attributes']['price'])) {
            foreach ($array as $key => $product) {
                if (isset($product['relationships'])) {
                    unset($array[$key]['relationships']);
                }
                if (isset($product['attributes']) && is_array($product['attributes'])) {
                    foreach ($product['attributes'] as $p_attr_key => $p_attr_value) {
                        if ($p_attr_key == 'price' && is_array($p_attr_value)) {
                            foreach ($p_attr_value as $price_key => $price_value) {
                                if (!is_array($price_value)) {
                                    $array[$key]['price_' . $price_key] = $price_value;
                                }
                            }
                        } elseif ($p_attr_key == 'media') {
                            if (isset($p_attr_value['documents']) && is_array($p_attr_value['documents'])) {
                                $document_names = '';
                                $document_urls = '';
                                foreach ($p_attr_value['documents'] as $document) {
                                    if (isset($document['name']) && $document['name']) {
                                        $document_names .= $document_names ? $multiple_value_separator : '';
                                        $document_names .= $document['name'];
                                    }
                                    if (isset($document['url']) && $document['url']) {
                                        $document_urls .= $document_urls ? $multiple_value_separator : '';
                                        $document_urls .= $document['url'];
                                    }
                                }
                                $array[$key]['media_document_names'] = $document_names;
                                $array[$key]['media_document_urls'] = $document_urls;
                            }
                            if (isset($p_attr_value['images']) && is_array($p_attr_value['images'])) {
                                $images = '';
                                foreach ($p_attr_value['images'] as $image) {
                                    if (isset($image['big']) && $image['big']) {
                                        $images .= $images ? $multiple_value_separator : '';
                                        $images .= $image['big'];
                                    }
                                }
                                $array[$key]['media_images'] = $images;
                            }
                        } elseif (!is_array($p_attr_value)) {
                            $array[$key][$p_attr_key] = $p_attr_value;
                        }
                    }
                    unset($array[$key]['attributes']);
                }
            }
        } elseif (isset($array[0]['prices']['grossPriceCustomer'], $array[0]['prices']['netPriceCustomer'], $array[0]['prices']['grossPriceCatalog'], $array[0]['prices']['netPriceCatalog'], $array[0]['prices']['tax'])) {
            foreach ($array as $key => $product) {
                $array[$key]['prices_grossPriceCustomer'] = isset($product['prices']['grossPriceCustomer']) ? $product['prices']['grossPriceCustomer'] : '';
                $array[$key]['prices_netPriceCustomer'] = isset($product['prices']['netPriceCustomer']) ? $product['prices']['netPriceCustomer'] : '';
                $array[$key]['prices_grossPriceCatalog'] = isset($product['prices']['grossPriceCatalog']) ? $product['prices']['grossPriceCatalog'] : '';
                $array[$key]['prices_netPriceCatalog'] = isset($product['prices']['netPriceCatalog']) ? $product['prices']['netPriceCatalog'] : '';
                $array[$key]['prices_tax'] = isset($product['prices']['tax']) ? $product['prices']['tax'] : '';
                unset($array[$key]['prices']);
            }
        }

        // Open file pointer
        $handle = fopen($file, 'w');

        // Write CSV header
        // Build header from all rows, because some rows may have columns that does not exist on other rows.
        $header = [];
        foreach ($array as $product) {
            if (!is_array($product)) {
                continue;
            }
            if (isset($product['product'])) {
                $product = $product['product'];
            }
            foreach ($product as $attr => $value) {
                if (!in_array($attr, $header)) {
                    $header[] = $attr;
                }
            }
        }
        fputcsv($handle, $header, ';', '"');

        // Write CSV content
        foreach ($array as $product) {
            if (!is_array($product)) {
                continue;
            }
            if (isset($product['product'])) {
                $product = $product['product'];
            }

            if (isset($product['Manufacturer']['ManufacturerId'], $product['Manufacturer']['Description'])) {
                $product['Manufacturer'] = $product['Manufacturer']['Description'];
            }
            if (isset($product['Brand']['ManufacturerId'], $product['Brand']['BrandId'], $product['Brand']['Description'])) {
                $product['Brand'] = $product['Brand']['Description'];
            }
            if (isset($product['Category']) && is_array($product['Category'])) {
                if (isset($product['Category']['CategoryId'])) {
                    unset($product['Category']['CategoryId']);
                }
                if (isset($product['Category']['Subcategories']) && $product['Category']['Subcategories'] && is_array($product['Category']['Subcategories'])) {
                    foreach ($product['Category']['Subcategories'] as &$sub_cat1) {
                        if (isset($sub_cat1['CategoryId'])) {
                            unset($sub_cat1['CategoryId']);
                        }
                        if (isset($sub_cat1['Subcategories']) && $sub_cat1['Subcategories'] && is_array($sub_cat1['Subcategories'])) {
                            foreach ($sub_cat1['Subcategories'] as &$sub_cat2) {
                                if (isset($sub_cat2['CategoryId'])) {
                                    unset($sub_cat2['CategoryId']);
                                }
                                if (isset($sub_cat2['Subcategories']) && $sub_cat2['Subcategories'] && is_array($sub_cat2['Subcategories'])) {
                                    foreach ($sub_cat2['Subcategories'] as &$sub_cat3) {
                                        if (isset($sub_cat3['CategoryId'])) {
                                            unset($sub_cat3['CategoryId']);
                                        }
                                        if (isset($sub_cat3['Subcategories']) && $sub_cat3['Subcategories'] && is_array($sub_cat3['Subcategories'])) {
                                            foreach ($sub_cat3['Subcategories'] as &$sub_cat4) {
                                                if (isset($sub_cat4['CategoryId'])) {
                                                    unset($sub_cat4['CategoryId']);
                                                }
                                                if (isset($sub_cat4['Subcategories']) && $sub_cat4['Subcategories'] && is_array($sub_cat4['Subcategories'])) {
                                                    unset($sub_cat4['Subcategories']); // I stopped here, you can continue if needed
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (isset($product['images']) && is_array($product['images'])) {
                $product_images = '';
                foreach ($product['images'] as $key => $value) {
                    if (is_array($value) && isset($value['detailed']) && is_array($value['detailed']) && isset($value['detailed']['image_path']) && $value['detailed']['image_path']) {
                        $product_images .= $product_images ? $multiple_value_separator : '';
                        $product_images .= $value['detailed']['image_path'];
                    }
                }
                if ($product_images) {
                    $product['images'] = $product_images;
                }
            }
            if (isset($product['features']) && is_array($product['features']) && isset($product['features']['feature0'], $product['features']['feature0']['name'], $product['features']['feature0']['value'])) {
                $product_features = '';
                foreach ($product['features'] as $value) {
                    if (isset($value['name']) && $value['name'] && isset($value['value'])) {
                        $product_features .= $product_features ? $multiple_value_separator : '';
                        $product_features .= $value['name'] . ':' . $value['value'];
                    }
                }
                $product['features'] = $product_features;
            }
            if (isset($product['features']) && is_array($product['features'])) {
                $product_features = '';
                foreach ($product['features'] as $key => $value) {
                    if (is_array($value) && isset($value['feature_name']) && $value['feature_name'] && array_key_exists('feature_value_sel', $value)) {
                        $product_features .= $product_features ? $multiple_value_separator : '';
                        $product_features .= $value['feature_name'] . ':' . $value['feature_value_sel'];
                    }
                }
                if ($product_features) {
                    $product['features'] = $product_features;
                }
            }
            if (isset($product['categories']) && is_array($product['categories'])) {
                foreach ($product['categories'] as $key => $category) {
                    if (isset($category['category_id'], $category['category_name'])) {
                        $product['categories'][$key] = $category['category_name'];
                    }
                }
            }
            if (isset($product['extra_fields']) && is_array($product['extra_fields'])) {
                $product_features = '';
                foreach ($product['extra_fields'] as $key => $extra_field) {
                    if (isset($extra_field['name']) && $extra_field['name'] && isset($extra_field['value'])) {
                        $product_features .= $product_features ? $multiple_value_separator : '';
                        $product_features .= $extra_field['name'] . ':' . $extra_field['value'];
                    }
                }
                if ($product_features) {
                    $product['extra_fields'] = $product_features;
                }
            }
            if (isset($product['extra_images']) && is_array($product['extra_images'])) {
                foreach ($product['extra_images'] as $key => $extra_image) {
                    if (isset($extra_image['image']) && $extra_image['image'] && isset($extra_image['title'])) {
                        $product['extra_images'][$key] = $extra_image['image'];
                    }
                }
            }
            if (isset($product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine'][0]['immagineLink'])) {
                $immagini = '';
                foreach ($product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine'] as $immagine) {
                    $immagini .= $immagini ? $multiple_value_separator : '';
                    $immagini .= reset($immagine['immagineLink']);
                }
                $product['prodottoMediaLink'] = $immagini;
            }
            if (isset($product['images'][0]['src'])) {
                $images = '';
                foreach ($product['images'] as $image) {
                    $images .= $images ? $multiple_value_separator : '';
                    $images .= $image['src'];
                }
                $product['images'] = $images;
            }
            if (isset($product['image']['id'], $product['image']['src'])) {
                $product['image'] = $product['image']['src'];
            }
            if (isset($product['classificazione']['merceologica'], $product['classificazione']['catalogo'])) {
                $categories = '';
                if (isset($product['classificazione']['merceologica']['gruppoMerceologico']['descrizione'])) {
                    $categories .= $categories ? $multiple_value_separator : '';
                    $categories .= reset($product['classificazione']['merceologica']['gruppoMerceologico']['descrizione']);
                }
                if (isset($product['classificazione']['merceologica']['categoriaMerceologica']['descrizione'])) {
                    $categories .= $categories ? $multiple_value_separator : '';
                    $categories .= reset($product['classificazione']['merceologica']['categoriaMerceologica']['descrizione']);
                }
                if (isset($product['classificazione']['catalogo']['gruppo']['descrizione'])) {
                    $categories .= $categories ? $multiple_value_separator : '';
                    $categories .= reset($product['classificazione']['catalogo']['gruppo']['descrizione']);
                }
                if (isset($product['classificazione']['catalogo']['categoria']['descrizione'])) {
                    $categories .= $categories ? $multiple_value_separator : '';
                    $categories .= reset($product['classificazione']['catalogo']['categoria']['descrizione']);
                }
                if (isset($product['classificazione']['catalogo']['sottocategoria']['descrizione'])) {
                    $categories .= $categories ? $multiple_value_separator : '';
                    $categories .= reset($product['classificazione']['catalogo']['sottocategoria']['descrizione']);
                }
                $product['classificazione'] = $categories;
            }
            if (isset($product['prices']['rrp'], $product['prices']['wholesale'])) {
                $product['prices'] = $product['prices']['rrp'];
            }
            if (isset($product['images'][0]) && is_array($product['images'][0])) {
                $product_images = '';
                foreach ($product['images'] as $image) {
                    if (isset($image['large_url'])) {
                        $product_images .= $product_images ? $multiple_value_separator : '';
                        $product_images .= $image['large_url'];
                    } elseif (is_array($image)) {
                        krsort($image);
                        $product_images .= $product_images ? $multiple_value_separator : '';
                        $product_images .= reset($image);
                    }
                }
                $product['images'] = $product_images;
            }
            if (isset($product['categories'][0]['id'], $product['categories'][0]['title'], $product['categories'][0]['path'])) {
                foreach ($product['categories'] as $key => $value) {
                    $product['categories'][$key] = $value['path'];
                    $product['categories'] = array_reverse($product['categories']);
                }
            }
            if (isset($product['PrestaShopItemId'], $product['VismaNetItemId'], $product['Quantities'][0]['WarehouseId'], $product['Quantities'][0]['PrestaFeatureName'], $product['Quantities'][0]['QuantityAvailable'])) {
                $features = '';
                foreach ($product['Quantities'] as $i => $qty) {
                    $features .= $features ? $multiple_value_separator : '';
                    $features .= $qty['PrestaFeatureName'] . ':' . $qty['QuantityAvailable'] . ':' . $i . ':1';
                }
                $product['Quantities'] = $features;
            }
            if (isset($product['_id'], $product['date_created'], $product['images']) && $product['images'] && is_array($product['images']) && isset($product['oemCodes'], $product['old_id'])) {
                foreach ($product['images'] as $k => $img) {
                    $product['images'][$k] = $product['date_created'] . '&q=' . $product['_id'] . '-' . $img;
                }
            }
            if (isset($product['attributes'])) {
                if (!is_array($product['attributes'])) {
                    $json_decoded_attr = json_decode($product['attributes'], true);
                    if (is_array($json_decoded_attr)) {
                        $product['attributes'] = $json_decoded_attr;
                    }
                }
                if (isset($product['attributes'][0]['label'], $product['attributes'][0]['value'])) {
                    $product_attrs = '';
                    foreach ($product['attributes'] as $product_attr) {
                        if (isset($product_attr['label']) && $product_attr['label'] && isset($product_attr['value'])) {
                            $product_attrs .= $product_attrs ? $multiple_value_separator : '';
                            $product_attrs .= $product_attr['label'] . ':' . $product_attr['value'];
                        }
                    }
                    $product['attributes'] = $product_attrs;
                }
            }

            // Take care of multiple values
            foreach ($product as $attr => $value) {
                // Decode the value if it is json encoded
                if (!is_array($value)) {
                    $json_decoded_value = json_decode($value, true);
                    if (is_array($json_decoded_value)) {
                        $value = $json_decoded_value;
                    }
                }
                if (is_array($value) && empty($value)) {
                    $product[$attr] = '';
                    continue;
                } elseif (!is_array($value) || empty($value)) {
                    continue;
                }
                $new_value = '';
                foreach ($value as $sub_value) {
                    if (is_array($sub_value) && $sub_value) {
                        foreach ($sub_value as $sub_sub_value) {
                            if (is_array($sub_sub_value) && $sub_sub_value) {
                                foreach ($sub_sub_value as $sub_sub_sub_value) {
                                    if (is_array($sub_sub_sub_value) && $sub_sub_sub_value) {
                                        foreach ($sub_sub_sub_value as $sub_sub_sub_sub_value) {
                                            if (!is_array($sub_sub_sub_sub_value)) {
                                                $new_value .= $new_value ? $multiple_value_separator : '';
                                                $new_value .= $sub_sub_sub_sub_value;
                                            }
                                        }
                                    } elseif ($sub_sub_sub_value) {
                                        $new_value .= $new_value ? $multiple_value_separator : '';
                                        $new_value .= $sub_sub_sub_value;
                                    }
                                }
                            } elseif ($sub_sub_value) {
                                $new_value .= $new_value ? $multiple_value_separator : '';
                                $new_value .= $sub_sub_value;
                            }
                        }
                    } elseif ($sub_value) {
                        $new_value .= $new_value ? $multiple_value_separator : '';
                        $new_value .= $sub_value;
                    }
                }
                $product[$attr] = $new_value;
            }

            // Build new product array by header columns
            $product_final = [];
            foreach ($header as $column) {
                $product_final[$column] = isset($product[$column]) ? $product[$column] : '';
            }
            fputcsv($handle, $product_final, ';', '"');
        }

        fclose($handle);

        return true;
    }
}
