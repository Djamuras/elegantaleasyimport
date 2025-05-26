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
 * This is helper class for XML functions
 */
class ElegantalEasyImportXml
{
    public static function convertToCsv($file, $model)
    {
        $entity = $model->entity;
        $multiple_value_separator = $model->multiple_value_separator;

        $f = fopen($file, 'r');
        $line = fgets($f);
        $line = trim($line);
        fclose($f);
        if ($line == 'This XML file does not appear to have any style information associated with it. The document tree is shown below.') {
            $fileArr = file($file);
            unset($fileArr[0]);
            file_put_contents($file, $fileArr);
            unset($fileArr);
        }

        // Replace & in XML file, as it is not allowed
        $file_content = Tools::file_get_contents($file);
        $file_content = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $file_content);
        $file_content = preg_replace('/soap\:/', '', $file_content);
        file_put_contents($file, $file_content);
        unset($file_content);

        // Load XML and convert to associative array. LIBXML_NOCDATA ignores <![CDATA[...]]>
        $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (empty($xml) || !is_object($xml)) {
            throw new Exception('XML file is not valid.');
        }

        // Step 1
        // Make changes in xml object here if necessary
        if ($entity == 'product' && isset($xml->shop, $xml->shop->categories, $xml->shop->offers->offer)) {
            // Get categories into array
            $shop_categories = [];
            if (isset($xml->shop->categories)) {
                foreach ($xml->shop->categories->category as $category) {
                    $shop_categories[(int) $category->attributes()->id[0]] = $category . '';
                }
            }
            foreach ($xml->shop->offers->offer as $offer) {
                // Replace category ID with category name
                if (isset($offer->categoryId, $shop_categories[(int) $offer->categoryId]) && $shop_categories[(int) $offer->categoryId]) {
                    $offer->categoryId[0] = $shop_categories[(int) $offer->categoryId];
                }
                // Parse features into one column
                if (isset($offer->param)) {
                    $n = 1;
                    foreach ($offer->param as $param) {
                        $param[0] = (isset($param->attributes()->name[0]) ? $param->attributes()->name[0] . '' : '') . ':' . $param . ':' . $n . ':0';
                        ++$n;
                    }
                }
            }
        } elseif ($entity == 'product' && isset($xml->categories, $xml->categories->category, $xml->categories->category[0]->id, $xml->categories->category[0]->name, $xml->categories->category[0]->parent, $xml->products->product)) {
            $shop_categories = [];
            // Anonymous function to get category name including its parents
            $getCategoriesWithParents = function ($xml_category) use (&$getCategoriesWithParents, $xml) {
                if (empty($xml_category->parent[0] . '')) {
                    return $xml_category->name[0] . '';
                }
                $parent_cat = null;
                foreach ($xml->categories->category as $tmp_cat) {
                    if ($tmp_cat->id[0] . '' == $xml_category->parent[0] . '') {
                        $parent_cat = $tmp_cat;
                        break;
                    }
                }

                return ($parent_cat) ? $getCategoriesWithParents($parent_cat) . '->' . $xml_category->name[0] : $xml_category->name[0] . '';
            };
            foreach ($xml->categories->category as $category) {
                $shop_categories[(int) $category->id[0]] = $getCategoriesWithParents($category);
            }
            foreach ($xml->products->product as $xml_product) {
                if (isset($xml_product->categories->category)) {
                    foreach ($xml_product->categories->category as $xml_cat) {
                        $xml_cat[0] = isset($shop_categories[(int) $xml_cat[0]]) ? $shop_categories[(int) $xml_cat[0]] : '';
                    }
                }
            }
        } elseif ($entity == 'product' && isset($xml->categories, $xml->categories->category, $xml->categories->category[0]->attributes()->id, $xml->categories->category[0]->attributes()->name, $xml->categories->category[0]->attributes()->parent, $xml->products->product)) {
            $shop_categories = [];
            // Anonymous function to get category name including its parents
            $getCategoriesWithParents = function ($xml_category) use (&$getCategoriesWithParents, $xml, $multiple_value_separator) {
                if (empty($xml_category->attributes()->parent . '')) {
                    return $xml_category->attributes()->name . '';
                }
                $parent_cat = null;
                foreach ($xml->categories->category as $tmp_cat) {
                    if ($tmp_cat->attributes()->id . '' == $xml_category->attributes()->parent . '') {
                        $parent_cat = $tmp_cat;
                        break;
                    }
                }

                return ($parent_cat) ? $getCategoriesWithParents($parent_cat) . $multiple_value_separator . $xml_category->attributes()->name : $xml_category->attributes()->name . '';
            };
            foreach ($xml->categories->category as $category) {
                $shop_categories[(int) $category->attributes()->id] = $getCategoriesWithParents($category);
            }
            foreach ($xml->products->product as $xml_product) {
                // Replace category ID with categories path
                if (isset($xml_product->attributes()->category)) {
                    $xml_product->attributes()->category = isset($shop_categories[(int) $xml_product->attributes()->category]) ? $shop_categories[(int) $xml_product->attributes()->category] : '';
                }
                // arguments are features
                if (isset($xml_product->arguments->argument[0], $xml_product->arguments->argument[0]->attributes()->name, $xml_product->arguments->argument[0]->attributes()->value)) {
                    $arguments = '';
                    $arguments_eng = '';
                    foreach ($xml_product->arguments->argument as $argument) {
                        if (isset($argument->attributes()->name, $argument->attributes()->value) && !empty($argument->attributes()->name . '')) {
                            $arguments .= $arguments ? $multiple_value_separator : '';
                            $arguments .= $argument->attributes()->name . ':"' . $argument->attributes()->value . '"';
                        }
                        if (isset($argument->attributes()->name_eng, $argument->attributes()->value_eng) && !empty($argument->attributes()->name_eng . '')) {
                            $arguments_eng .= $arguments_eng ? $multiple_value_separator : '';
                            $arguments_eng .= $argument->attributes()->name_eng . ':"' . $argument->attributes()->value_eng . '"';
                        }
                    }
                    $xml_product->arguments = $arguments;
                    $xml_product->arguments_eng = $arguments_eng;
                }
            }
            unset($xml->categories);
        } elseif ($entity == 'product' && isset($xml->categories, $xml->categories->category, $xml->categories->category[0]->category_id, $xml->categories->category[0]->category_name, $xml->categories->category[0]->category_parrent_id, $xml->products->product)) {
            $shop_categories = [];
            // Anonymous function to get category name including its parents
            $getCategoriesWithParents = function ($xml_category, $multiple_value_separator) use (&$getCategoriesWithParents, $xml) {
                if (empty($xml_category->category_parrent_id[0] . '')) {
                    return $xml_category->category_name[0] . '';
                }
                $parent_cat = null;
                foreach ($xml->categories->category as $tmp_cat) {
                    if ($tmp_cat->category_id[0] . '' == $xml_category->category_parrent_id[0] . '') {
                        $parent_cat = $tmp_cat;
                        break;
                    }
                }

                return ($parent_cat) ? $getCategoriesWithParents($parent_cat, $multiple_value_separator) . $multiple_value_separator . $xml_category->category_name[0] : $xml_category->category_name[0] . '';
            };
            foreach ($xml->categories->category as $category) {
                $shop_categories[(int) $category->category_id[0]] = $getCategoriesWithParents($category, $multiple_value_separator);
            }
            foreach ($xml->products->product as $xml_product) {
                if (isset($xml_product->category_id)) {
                    $xml_product->category_id = isset($shop_categories[(int) $xml_product->category_id]) ? $shop_categories[(int) $xml_product->category_id] : '';
                }
            }
        } elseif ($entity == 'product' && isset($xml->categories, $xml->categories->category, $xml->product)) {
            $shop_categories = [];
            foreach ($xml->categories->category as $category) {
                $shop_categories[(int) $category->id[0]] = $category->name[0] . '';
            }
            foreach ($xml->product as $xml_product) {
                if (isset($xml_product->categories->category)) {
                    foreach ($xml_product->categories->category as $xml_cat) {
                        $xml_cat[0] = isset($shop_categories[(int) $xml_cat[0]]) ? $shop_categories[(int) $xml_cat[0]] : '';
                    }
                }
            }
        } elseif (isset($xml->Product, $xml->Product[0]->SellingPrices)) {
            foreach ($xml->Product as $product) {
                if (isset($product->SellingPrices->SellingPrice)) {
                    $count = 1;
                    foreach ($product->SellingPrices->SellingPrice as $sellingPrice) {
                        $product->{'SellingPrice' . $count . '_Price'} = $sellingPrice->Price . '';
                        $product->{'SellingPrice' . $count . '_PriceIncludingVAT'} = $sellingPrice->PriceIncludingVAT . '';
                        $product->{'SellingPrice' . $count . '_MinQuantity'} = $sellingPrice->MinQuantity . '';
                        ++$count;
                    }
                    unset($product->SellingPrices);
                }
                if (isset($product->Manufacturer->Name)) {
                    $product->Manufacturer = $product->Manufacturer->Name . '';
                }
                if (isset($product->Condition->Name)) {
                    $product->Condition = $product->Condition->Name . '';
                }
                if (isset($product->ProdCategories->ProdCategory->FullPathName)) {
                    $product->ProdCategories = $product->ProdCategories->ProdCategory->FullPathName . '';
                }
                if (isset($product->Tax->PercentAmount)) {
                    $product->Tax_PercentAmount = $product->Tax->PercentAmount . '';
                }
                if (isset($product->Tax->Code)) {
                    $product->Tax_Code = $product->Tax->Code . '';
                }
                if (isset($product->Tax->Id)) {
                    $product->Tax_Id = $product->Tax->Id . '';
                }
                if (isset($product->OfficialPriceList->Price)) {
                    $product->OfficialPriceList = $product->OfficialPriceList->Price . '';
                }
                // Remove original nodes that has lang attribute and create new nodes for lang
                $lang_attrs = [];
                foreach ($product as $key => $child) {
                    if (isset($child->attributes()->lang) && $child->attributes()->lang && count($product->{$key}) > 1 && !in_array($key, $lang_attrs)) {
                        $lang_attrs[] = $key;
                    }
                }
                if ($lang_attrs) {
                    foreach ($lang_attrs as $key) {
                        foreach ($product->{$key} as $node) {
                            if (isset($node->attributes()->lang)) {
                                $lang = Tools::strtoupper(preg_replace('/[^a-zA-Z]+/', '', $node->attributes()->lang . ''));
                                if ($lang && !$product->{$key . '_' . $lang}) {
                                    $product->{$key . '_' . $lang} = $node . '';
                                }
                            } else {
                                if (!$product->{$key . '_NoLang'}) {
                                    $product->{$key . '_NoLang'} = $node . '';
                                }
                            }
                        }
                        unset($product->{$key});
                    }
                }
            }
        } elseif ($entity == 'combination' && isset($xml->Product[0]->Product_code, $xml->Product[0]->Product_id)) {
            // Check if this file has variants
            $variants_exist = false;
            foreach ($xml->Product as $product) {
                if (isset($product->variants->variant[0]->spec)) {
                    $variants_exist = true;
                    break;
                }
            }
            if ($variants_exist) {
                foreach ($xml->Product as $product) {
                    foreach ($product->variants->variant as $variant) {
                        $attribute_names = '';
                        $attribute_values = '';
                        if (isset($variant->spec)) {
                            foreach ($variant->spec as $spec) {
                                $attribute_names .= $attribute_names ? $multiple_value_separator : '';
                                $attribute_names .= $spec->attributes()->name . '';
                                $attribute_values .= $attribute_values ? $multiple_value_separator : '';
                                $attribute_values .= $spec . '';
                            }
                        }
                        $variant->AttributeNames = $attribute_names;
                        $variant->AttributeValues = $attribute_values;
                    }
                }
            }
        } elseif ($entity == 'combination' && isset($xml->ITEM[0], $xml->ITEM[0]->ID, $xml->ITEM[0]->ID_MAIN, $xml->ITEM[0]->CODE, $xml->ITEM[0]->PARAMS)) {
            foreach ($xml->ITEM as $product) {
                $attribute_names = '';
                $attribute_values = '';
                if (isset($product->PARAMS->PARAM)) {
                    foreach ($product->PARAMS->PARAM as $param) {
                        $attribute_names .= $attribute_names ? $multiple_value_separator : '';
                        $attribute_names .= $param->attributes()->name . '';
                        $attribute_values .= $attribute_values ? $multiple_value_separator : '';
                        $attribute_values .= $param . '';
                    }
                }
                $product->AttributeNames = $attribute_names;
                $product->AttributeValues = $attribute_values;
            }
        } elseif ($entity == 'product' && isset($xml->titul, $xml->titul->attributes()->ean, $xml->titul->attributes()->id, $xml->titul->attributes()->timestamp)) {
            $xml_tmp = [];
            foreach ($xml->titul as $titul) {
                $xml_tmp[] = [
                    'ean' => $titul->attributes()->ean . '',
                    'id' => $titul->attributes()->id . '',
                    'timestamp' => $titul->attributes()->timestamp . '',
                    'pocet' => $titul->attributes()->pocet . '',
                    'titul' => $titul . '',
                ];
            }
            $xml = $xml_tmp;
        } elseif ($entity == 'product' && isset($xml->article[0]->article_nr, $xml->article[0]->article_configurations->configuration)) {
            foreach ($xml->article as $product) {
                if (isset($product->article_configurations->configuration[0]->code)) {
                    foreach ($product->article_configurations->configuration as $c) {
                        if (isset($c->prices->price)) {
                            $key = 1;
                            foreach ($c->prices->price as $price) {
                                $product->{'price_original_' . $key} = $price->attributes()->original_price . '';
                                $product->{'price_quantity_' . $key} = $price->attributes()->quantity . '';
                                $product->{'price_discount_percentage_' . $key} = $price->attributes()->discount_percentage . '';
                                ++$key;
                            }
                        }
                    }
                }
            }
        } elseif (isset($xml->group->o[0], $xml->group->o[0]->attributes()->id, $xml->group->o[0]->attrs->a)) {
            if ($entity == 'combination' && isset($xml->group->o[0]->versions->a)) {
                $xml_tmp = [];
                foreach ($xml->group->o as $product) {
                    foreach ($product->versions->a as $v) {
                        $xml_tmp[] = [
                            'id' => $product->attributes()->id . '',
                            'combination_reference' => $v->attributes()->option_id . '',
                            'Size' => $v->attributes()->name . '',
                            'ean' => $v->attributes()->ean . '',
                            'stock' => trim($v[0]) . '',
                            'nr_cat' => $v->attributes()->nr_cat . '',
                        ];
                    }
                }
                $xml = $xml_tmp;
            } else {
                foreach ($xml->group->o as $product) {
                    $attrs_feature = '';
                    foreach ($product->attrs->a as $a) {
                        if (empty($a->attributes()->name . '') || trim($a[0] . '') === '') {
                            continue;
                        }
                        $attrs_feature .= $attrs_feature ? $multiple_value_separator : '';
                        $attrs_feature .= $a->attributes()->name . ':' . trim($a[0]);
                    }
                    $product->attrs = $attrs_feature;
                    unset($product->versions);
                }
            }
        } elseif (isset($xml->Categories, $xml->Producers, $xml->Products->Product[0]->TechnicalSpecification)) {
            unset($xml->Categories);
            unset($xml->Producers);
            foreach ($xml->Products->Product as $product) {
                unset($product->TechnicalSpecification);
            }
        } elseif (isset($xml->ITEM, $xml->ITEM[0]->PRICES->PRICE, $xml->ITEM[0]->PRICES->PRICE[0]->attributes()->level, $xml->ITEM[0]->PRICES_VAT->PRICE_VAT, $xml->ITEM[0]->PRICES_VAT->PRICE_VAT[0]->attributes()->level)) {
            foreach ($xml->ITEM as $ITEM) {
                foreach ($ITEM->PRICES->PRICE as $PRICE) {
                    if (isset($PRICE->attributes()->level)) {
                        $ITEM->{'PRICE_' . $PRICE->attributes()->level . ''} = $PRICE . '';
                    }
                }
                foreach ($ITEM->PRICES_VAT->PRICE_VAT as $PRICE_VAT) {
                    if (isset($PRICE_VAT->attributes()->level)) {
                        $ITEM->{'PRICE_VAT_' . $PRICE_VAT->attributes()->level . ''} = $PRICE_VAT . '';
                    }
                }
                unset($ITEM->PRICES);
                unset($ITEM->PRICES_VAT);
            }
        } elseif (isset($xml->product, $xml->product[0]->param, $xml->product[0]->param[0]->attributes()->name)) {
            foreach ($xml->product as $product) {
                $params = '';
                foreach ($product->param as $param) {
                    $params .= $params ? $multiple_value_separator : '';
                    $params .= $param->attributes()->name . ':' . str_replace(':', '∶', $param . '');
                }
                unset($product->param);
                $product->param = $params;
            }
        } elseif (isset($xml->group->product[0], $xml->group->product[0]->attrs->a, $xml->group->product[0]->attrs->a->attributes()->name)) {
            foreach ($xml->group->product as $product) {
                $attrs = '';
                foreach ($product->attrs->a as $a) {
                    if ($a . '' === '') {
                        continue;
                    }
                    $attrs .= $attrs ? $multiple_value_separator : '';
                    $attrs .= $a->attributes()->name . ':"' . $a . '"';
                }
                unset($product->attrs);
                $product->attrs = $attrs;
            }
        } elseif ($entity == 'product' && isset($xml->products->product[0]->prime_costs)) {
            $attributes_exist = false;
            foreach ($xml->products->product as $product) {
                if (isset($product->attributes->attribute, $product->attributes->attribute->attributes()->title)) {
                    $attributes_exist = true;
                    break;
                }
            }
            if ($attributes_exist) {
                foreach ($xml->products->product as $product) {
                    $attrs = '';
                    if (isset($product->attributes->attribute)) {
                        foreach ($product->attributes->attribute as $attr) {
                            $attrs .= $attrs ? $multiple_value_separator : '';
                            $attrs .= $attr->attributes()->title . ':"' . $attr . '"';
                        }
                    }
                    unset($product->attributes);
                    $product->attributes = $attrs;
                }
            }
        } elseif ($entity == 'product' && isset($xml->PRODUCT->PRICES->PRICE, $xml->PRODUCT->PRICES->PRICE->attributes()->type)) {
            foreach ($xml->PRODUCT as $product) {
                if (isset($product->PRICES->PRICE->attributes()->type)) {
                    foreach ($product->PRICES->PRICE as $price) {
                        $product->{'PRICE_' . $price->attributes()->type . ''} = $price . '';
                    }
                    unset($product->PRICES);
                }
                if ((isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->height))
                    || (isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->width))
                    || (isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->depth))
                    || (isset($product->PACKAGE->WEIGHT, $product->PACKAGE->WEIGHT->attributes()->value))) {
                    if (isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->height)) {
                        $product->PACKAGE_DIMENSIONS_height = $product->PACKAGE->DIMENSIONS->attributes()->height . '';
                    }
                    if (isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->width)) {
                        $product->PACKAGE_DIMENSIONS_width = $product->PACKAGE->DIMENSIONS->attributes()->width . '';
                    }
                    if (isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->depth)) {
                        $product->PACKAGE_DIMENSIONS_depth = $product->PACKAGE->DIMENSIONS->attributes()->depth . '';
                    }
                    if (isset($product->PACKAGE->DIMENSIONS, $product->PACKAGE->DIMENSIONS->attributes()->unit)) {
                        $product->PACKAGE_DIMENSIONS_unit = $product->PACKAGE->DIMENSIONS->attributes()->unit . '';
                    }
                    if (isset($product->PACKAGE->WEIGHT, $product->PACKAGE->WEIGHT->attributes()->value)) {
                        $product->WEIGHT_value = $product->PACKAGE->WEIGHT->attributes()->value . '';
                    }
                    if (isset($product->PACKAGE->WEIGHT, $product->PACKAGE->WEIGHT->attributes()->unit)) {
                        $product->WEIGHT_unit = $product->PACKAGE->WEIGHT->attributes()->unit . '';
                    }
                    unset($product->PACKAGE);
                }
            }
        } elseif (isset($xml->broker->adverts->advert, $xml->broker->adverts->advert[0]->advert_features, $xml->broker->adverts->advert[0]->boat_features)) {
            $xml = $xml->broker->adverts;
            foreach ($xml->advert as $product) {
                if (isset($product->advert_features)) {
                    foreach ($product->advert_features->children() as $advert_feature_key => $advert_feature_value) {
                        if ($advert_feature_key . '' == 'marketing_descs') {
                            if (isset($advert_feature_value->marketing_desc[0])) {
                                $advert_feature_value = $advert_feature_value->marketing_desc[0] . '';
                            } else {
                                $advert_feature_value = '';
                            }
                        }
                        $product->{$advert_feature_key . ''} = $advert_feature_value . '';
                    }
                    unset($product->advert_features);
                }
                if (isset($product->boat_features)) {
                    $boat_features = '';
                    foreach ($product->boat_features->children() as $boat_feature_section => $boat_feature_items) {
                        if ($boat_feature_section . '' == 'item') {
                            if ($boat_feature_items . '' !== '' && isset($boat_feature_items->attributes()->name)) {
                                $boat_features .= $boat_features ? $multiple_value_separator : '';
                                $boat_features .= ucfirst(str_replace('_', ' ', $boat_feature_items->attributes()->name . '')) . ':"' . $boat_feature_items . '"';
                            }
                        } else {
                            foreach ($boat_feature_items as $boat_feature_item) {
                                if (isset($boat_feature_item->attributes()->name)) {
                                    if ($boat_feature_item . '' === '') {
                                        continue;
                                    }
                                    $boat_features .= $boat_features ? $multiple_value_separator : '';
                                    $boat_features .= ucfirst(str_replace('_', ' ', $boat_feature_item->attributes()->name . '')) . ':"' . $boat_feature_item . '"';
                                }
                            }
                        }
                    }
                    $product->boat_features = $boat_features;
                }
            }
        } elseif ((isset($xml->products->product[0]->description->name[0]) && $xml->products->product[0]->description->name[0]->attributes('xml', true)->lang)
            || (isset($xml->products->product[0]->description->long_desc[0]) && $xml->products->product[0]->description->long_desc[0]->attributes('xml', true)->lang)
            || (isset($xml->products->product[0]->description->short_desc[0]) && $xml->products->product[0]->description->short_desc[0]->attributes('xml', true)->lang)) {
            foreach ($xml->products->product as $product) {
                if (isset($product->description->name[0]) && $product->description->name[0]->attributes('xml', true)->lang) {
                    foreach ($product->description->name as $name) {
                        if ($name->attributes('xml', true)->lang) {
                            $product->{'name_' . $name->attributes('xml', true)->lang} = $name . '';
                        }
                    }
                }
                if (isset($product->description->long_desc[0]) && $product->description->long_desc[0]->attributes('xml', true)->lang) {
                    foreach ($product->description->long_desc as $long_desc) {
                        if ($long_desc->attributes('xml', true)->lang) {
                            $product->{'long_desc_' . $long_desc->attributes('xml', true)->lang} = $long_desc . '';
                        }
                    }
                }
                if (isset($product->description->short_desc[0]) && $product->description->short_desc[0]->attributes('xml', true)->lang) {
                    foreach ($product->description->short_desc as $short_desc) {
                        if ($short_desc->attributes('xml', true)->lang) {
                            $product->{'short_desc_' . $short_desc->attributes('xml', true)->lang} = $short_desc . '';
                        }
                    }
                }
                unset($product->description);
            }
        } elseif (isset($xml->products->product[0]->unique_id, $xml->products->product[0]->code, $xml->products->product[0]->name)) {
            $is_attribute_group_exist = false;
            foreach ($xml->products->product as $product) {
                if (isset($product->attribute_group, $product->attribute_group[0]->attribute, $product->attribute_group[0]->attribute->attributes()->name)) {
                    $is_attribute_group_exist = true;
                    break;
                }
            }
            if ($is_attribute_group_exist) {
                foreach ($xml->products->product as $product) {
                    $attributes = '';
                    foreach ($product->attribute_group as $attribute_group) {
                        if (isset($attribute_group->attributes()->name, $attribute_group->attribute[0]->attributes()->name)) {
                            foreach ($attribute_group->attribute as $attribute) {
                                if (isset($attribute->attributes()->name) && $attribute->attributes()->name) {
                                    $attributes .= $attributes ? $multiple_value_separator : '';
                                    $attributes .= trim($attribute->attributes()->name) . ':"' . trim($attribute) . '"';
                                }
                            }
                        }
                    }
                    $product->attributes_group = $attributes;
                    unset($product->attribute_group);
                }
            }
        } elseif (isset($xml->product[0]->id, $xml->product[0]->attrs->attr, $xml->product[0]->images->image, $xml->product[0]->stock->item)) {
            $new_xml = new DOMDocument('1.0', 'utf-8');
            $new_xml->appendChild($new_xml->createElement('content'));
            foreach ($xml->product as $product) {
                if (isset($product->prices->retail_netto)) {
                    $product->prices_retail_netto = $product->prices->retail_netto;
                }
                if (isset($product->prices->wholesale_netto)) {
                    $product->prices_wholesale_netto = $product->prices->wholesale_netto;
                }
                if (isset($product->prices->catalogue_netto)) {
                    $product->prices_catalogue_netto = $product->prices->catalogue_netto;
                }
                if (isset($product->prices->tax_rate)) {
                    $product->prices_tax_rate = $product->prices->tax_rate;
                }
                unset($product->prices);

                if ($entity == 'combination') {
                    unset($product->url);
                    unset($product->attrs);
                    unset($product->sizes);
                    unset($product->images);
                    unset($product->category);
                    unset($product->description);
                    if (isset($product->stock->item, $product->stock->item[0]->attributes()->ean, $product->stock->item[0]->attributes()->uid, $product->stock->item[0]->attributes()->quantity)) {
                        foreach ($product->stock->item as $item) {
                            // Convert product node into DOM because we cannot make it with simplexml
                            $product_dom = dom_import_simplexml($product);
                            // Copy the element into the "owner document" of our target node
                            $product_dom_copy = $new_xml->importNode($product_dom, true);
                            // Make changes to product
                            $item_ean = isset($item->attributes()->ean) ? $item->attributes()->ean . '' : '';
                            $item_uid = isset($item->attributes()->uid) ? $item->attributes()->uid . '' : '';
                            $item_quantity = isset($item->attributes()->uid) ? $item->attributes()->quantity . '' : '';
                            $product_dom_copy->appendChild($new_xml->createElement('Size', $item . ''));
                            $product_dom_copy->appendChild($new_xml->createElement('item_ean', $item_ean));
                            $product_dom_copy->appendChild($new_xml->createElement('item_uid', $item_uid));
                            $product_dom_copy->appendChild($new_xml->createElement('item_quantity', $item_quantity));
                            $product_dom_copy->removeChild($product_dom_copy->getElementsByTagName('stock')->item(0));
                            // Add the node as a new child
                            $new_xml->getElementsByTagName('content')->item(0)->appendChild($product_dom_copy);
                        }
                    }
                } else {
                    unset($product->sizes);
                    if (isset($product->attrs->attr)) {
                        $product_attrs = '';
                        foreach ($product->attrs->attr as $attr) {
                            $attr_name = trim($attr->attributes()->name . '');
                            $attr_value = trim($attr . '');
                            if ($attr_name) {
                                $product_attrs .= $product_attrs ? $multiple_value_separator : '';
                                $product_attrs .= $attr_name . ':"' . $attr_value . '"';
                            }
                        }
                        $product->attrs = $product_attrs;
                    }
                    if (isset($product->stock, $product->stock->attributes()->quantity)) {
                        $product->stock_quantity = (int) $product->stock->attributes()->quantity;
                        unset($product->stock);
                    }
                }
            }
            if ($entity == 'combination') { // Replace xml with new_xml because combinations are created into new_xml
                unset($xml);
                $xml = simplexml_import_dom($new_xml);
            }
        } elseif (isset($xml->products->product[0]->prices->grossPriceCustomer, $xml->products->product[0]->prices->netPriceCustomer, $xml->products->product[0]->prices->grossPriceCatalog, $xml->products->product[0]->prices->netPriceCatalog, $xml->products->product[0]->prices->tax)) {
            foreach ($xml->products->product as $product) {
                $product->prices_grossPriceCustomer = isset($product->prices->grossPriceCustomer) ? $product->prices->grossPriceCustomer : '';
                $product->prices_netPriceCustomer = isset($product->prices->netPriceCustomer) ? $product->prices->netPriceCustomer : '';
                $product->prices_grossPriceCatalog = isset($product->prices->grossPriceCatalog) ? $product->prices->grossPriceCatalog : '';
                $product->prices_netPriceCatalog = isset($product->prices->netPriceCatalog) ? $product->prices->netPriceCatalog : '';
                $product->prices_tax = isset($product->prices->tax) ? $product->prices->tax : '';
                unset($product->prices);
                if (isset($product->attributes->attribute[0], $product->attributes->attribute[0]->attributes()->name)) {
                    $features = '';
                    foreach ($product->attributes->attribute as $feature) {
                        if (isset($feature->attributes()->name) && $feature->attributes()->name . '') {
                            $features .= $features ? $multiple_value_separator : '';
                            $features .= trim($feature->attributes()->name . '') . ':"' . trim($feature . '') . '"';
                        }
                    }
                    $product->attributes = $features;
                }
            }
        } elseif (isset($xml->art, $xml->art[0], $xml->art[0]->attributes()->GIDNumer)) {
            $new_xml = new DOMDocument('1.0', 'utf-8');
            $new_xml->appendChild($new_xml->createElement('articles'));
            foreach ($xml->art as $product) {
                if (isset($product->photos, $product->photos->photo)) {
                    $photos = '';
                    foreach ($product->photos->photo as $photo) {
                        if (isset($photo->attributes()->Link)) {
                            $photos .= $photos ? $multiple_value_separator : '';
                            $photos .= $photo->attributes()->Link . '';
                        }
                    }
                    $product->photos = $photos;
                }
                unset($product->groups);

                if ($entity == 'combination') {
                    unset($product->descriptions);
                    unset($product->attributes);
                    if (isset($product->features, $product->features->feature, $product->features->feature[1])) {
                        // If features->feature is more than 1, it means there are combinations
                        foreach ($product->features->feature as $combination) {
                            // Convert product node into DOM because we cannot make it with simplexml
                            $product_dom = dom_import_simplexml($product);
                            // Copy the element into the "owner document" of our target node
                            $product_dom_copy = $new_xml->importNode($product_dom, true);
                            // Make changes to product
                            $item_size = isset($combination->attributes()->Feature) ? $combination->attributes()->Feature . '' : '';
                            $item_ean = isset($combination->attributes()->EAN) ? $combination->attributes()->EAN . '' : '';
                            $item_quantity = isset($combination->attributes()->AvailQuantity) ? $combination->attributes()->AvailQuantity . '' : '';
                            $product_dom_copy->appendChild($new_xml->createElement('Size', $item_size . ''));
                            $product_dom_copy->appendChild($new_xml->createElement('EAN', $item_ean));
                            $product_dom_copy->appendChild($new_xml->createElement('AvailQuantity', $item_quantity));
                            $product_dom_copy->removeChild($product_dom_copy->getElementsByTagName('features')->item(0));
                            // Add the node as a new child
                            $new_xml->getElementsByTagName('articles')->item(0)->appendChild($product_dom_copy);
                        }
                    }
                } else {
                    if (isset($product->descriptions, $product->descriptions->desc)) {
                        foreach ($product->descriptions->desc as $desc) {
                            if (isset($desc->attributes()->LanguageID, $desc->attributes()->Description)) {
                                $product->{'description_' . $desc->attributes()->LanguageID} = $desc->attributes()->Description . '';
                            }
                        }
                        unset($product->descriptions);
                    }
                    if (isset($product->features->feature[0])) {
                        $product->EAN = isset($product->features->feature[0]->attributes()->EAN) ? $product->features->feature[0]->attributes()->EAN : '';
                        $product->AvailQuantity = isset($product->features->feature[0]->attributes()->AvailQuantity) ? $product->features->feature[0]->attributes()->AvailQuantity : '';
                        unset($product->features);
                    }
                    if (isset($product->attributes, $product->attributes->attribute)) {
                        $features = '';
                        foreach ($product->attributes->attribute as $feature) {
                            if (isset($feature->attributes()->Name) && $feature->attributes()->Name . '' && isset($feature->attributes()->Value)) {
                                $features .= $features ? $multiple_value_separator : '';
                                $features .= trim($feature->attributes()->Name . '') . ':"' . trim($feature->attributes()->Value . '') . '"';
                            }
                        }
                        $product->attributes = $features;
                    }
                }
            }
            if ($entity == 'combination') { // Replace xml with new_xml because combinations are created into new_xml
                unset($xml);
                $xml = simplexml_import_dom($new_xml);
            }
        } elseif (isset($xml->o[0]->stock->mag) || isset($xml->o[0]->attrs->a)) {
            foreach ($xml->o as $product) {
                if (isset($product->stock->mag->attributes()->stock)) {
                    $product->stock = $product->stock->mag->attributes()->stock . '';
                }
                if (isset($product->attrs->a)) {
                    $attrs = '';
                    foreach ($product->attrs->a as $a) {
                        if ($a . '' === '' || !isset($a->attributes()->name) || !$a->attributes()->name) {
                            continue;
                        }
                        $attrs .= $attrs ? $multiple_value_separator : '';
                        $attrs .= $a->attributes()->name . ':"' . $a . '"';
                    }
                    unset($product->attrs);
                    $product->attrs = $attrs;
                }
            }
        } elseif (isset($xml->ZBOZI->POLOZKA, $xml->ZBOZI->POLOZKA[0], $xml->ZBOZI->POLOZKA[0]->PARAMETRY->PARAMETR)) {
            foreach ($xml->ZBOZI->POLOZKA as $product) {
                $parameter_manufacturer = '';
                $parameter_categories = '';
                $parameter_features = '';
                if (isset($product->PARAMETRY->PARAMETR)) {
                    foreach ($product->PARAMETRY->PARAMETR as $parameter) {
                        if (isset($parameter->attributes()->TYP) && $parameter->attributes()->TYP == 'Manufacturer') {
                            $parameter_manufacturer = $parameter . '';
                        } elseif (isset($parameter->attributes()->TYP) && $parameter->attributes()->TYP == 'Category') {
                            $parameter_categories .= $parameter_categories ? $multiple_value_separator : '';
                            $parameter_categories .= $parameter . '';
                        } elseif (isset($parameter->attributes()->TYP) && $parameter->attributes()->TYP) {
                            $parameter_features .= $parameter_features ? $multiple_value_separator : '';
                            $parameter_features .= $parameter->attributes()->TYP . ':"' . $parameter . '"';
                        }
                    }
                    unset($product->PARAMETRY);
                }
                $product->PARAMETR_Manufacturer = $parameter_manufacturer;
                $product->PARAMETR_Category = $parameter_categories;
                $product->PARAMETR_Features = $parameter_features;
            }
        }

        // Step 2
        // Here you can remove namespaces if exist. This code is generic but you can allow it only for xml files that need it.
        if ((isset($xml->channel, $xml->channel->title, $xml->channel->link))
            || (isset($xml->title, $xml->link, $xml->entry))
            // (isset($xml->attributes()->file_format) && isset($xml->attributes()->generated) && isset($xml->attributes()->expires) && isset($xml->attributes()->version) && isset($xml->attributes()->extensions))
        ) {
            $namespaces = $xml->getDocNamespaces(true);
            if ($namespaces && is_array($namespaces)) {
                $file_content = Tools::file_get_contents($file);
                foreach ($namespaces as $ns => $ns_url) {
                    $file_content = str_replace(['<' . $ns . ':', '</' . $ns . ':'], ['<', '</'], $file_content);
                }
                file_put_contents($file, $file_content);
                unset($file_content);
                $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
            }
        }

        $json = json_encode($xml);
        unset($xml);

        $first_array = json_decode($json, true);
        unset($json);

        $array = $first_array;

        // Step 3
        if (isset($array['@attributes'])) {
            unset($array['@attributes']);
        }
        if (isset($array['message_header'])) {
            unset($array['message_header']);
        }
        if (isset($array['products']['@attributes'])) {
            unset($array['products']['@attributes']);
        }
        if (isset($array['Header'])) {
            unset($array['Header']);
        }
        if (isset($array['Service']['Header'])) {
            unset($array['Service']['Header']);
        }
        if (isset($array['envelop'])) {
            unset($array['envelop']);
        }
        if (isset($array['channel']['title'])) {
            unset($array['channel']['title']);
        }
        if (isset($array['channel']['link'])) {
            unset($array['channel']['link']);
        }
        if (isset($array['channel']['description'])) {
            unset($array['channel']['description']);
        }
        if (isset($array['channel']['comment'])) {
            unset($array['channel']['comment']);
        }
        if (isset($array['channel']['language'])) {
            unset($array['channel']['language']);
        }
        if (isset($array['channel']['pubDate'])) {
            unset($array['channel']['pubDate']);
        }
        if (isset($array['created_at'])) {
            unset($array['created_at']);
        }
        if (isset($array['created_by'])) {
            unset($array['created_by']);
        }
        if (isset($array['created'])) {
            unset($array['created']);
        }
        if (isset($array['lastupdate'])) {
            unset($array['lastupdate']);
        }
        if (isset($array['updated'])) {
            unset($array['updated']);
        }
        if (isset($array['status'])) {
            unset($array['status']);
        }
        if (isset($array['methodName'])) {
            unset($array['methodName']);
        }
        if (isset($array['EXPORT_INFO'])) {
            unset($array['EXPORT_INFO']);
        }
        if (isset($array['DATE'])) {
            unset($array['DATE']);
        }
        if (isset($array['DATE_CREATED'])) {
            unset($array['DATE_CREATED']);
        }
        if (isset($array['DATE_UPDATED'])) {
            unset($array['DATE_UPDATED']);
        }
        if (isset($array['AUTHOR'])) {
            unset($array['AUTHOR']);
        }
        if (isset($array['HEAD'])) {
            unset($array['HEAD']);
        }
        if (isset($array['headerInfo'])) {
            unset($array['headerInfo']);
        }
        if (isset($array['categories'])) {
            unset($array['categories']);
        }
        if (isset($array['Categories'])) {
            unset($array['Categories']);
        }
        if (isset($array['Producers'])) {
            unset($array['Producers']);
        }
        if (isset($array['shop']['name'])) {
            unset($array['shop']['name']);
        }
        if (isset($array['shop']['company'])) {
            unset($array['shop']['company']);
        }
        if (isset($array['shop']['currencies'])) {
            unset($array['shop']['currencies']);
        }
        if (isset($array['shop']['categories'])) {
            unset($array['shop']['categories']);
        }
        if (isset($array['shop']['url'])) {
            unset($array['shop']['url']);
        }
        if (isset($array['Shop']['id'])) {
            unset($array['Shop']['id']);
        }
        if (isset($array['Shop']['Categories'])) {
            unset($array['Shop']['Categories']);
        }
        if (isset($array['estado'])) {
            unset($array['estado']);
        }
        if (isset($array['Дата'])) {
            unset($array['Дата']);
        }
        if (isset($array['DocTimeStamp'])) {
            unset($array['DocTimeStamp']);
        }
        if (isset($array['datetime'])) {
            unset($array['datetime']);
        }
        if (isset($array['date'])) {
            unset($array['date']);
        }
        if (isset($array['time'])) {
            unset($array['time']);
        }
        if (isset($array['creationdate'])) {
            unset($array['creationdate']);
        }
        if (isset($array['store_name'])) {
            unset($array['store_name']);
        }
        if (isset($array['store_url'])) {
            unset($array['store_url']);
        }
        if (isset($array['excluded_products'])) {
            unset($array['excluded_products']);
        }
        if (isset($array['excluded_categories'])) {
            unset($array['excluded_categories']);
        }
        if (isset($array['generation_date'])) {
            unset($array['generation_date']);
        }
        if (isset($array['title'])) {
            unset($array['title']);
        }
        if (isset($array['link'])) {
            unset($array['link']);
        }
        if (isset($array['description'])) {
            unset($array['description']);
        }
        if (isset($array['comment'])) {
            unset($array['comment']);
        }
        if (isset($array['site_url'])) {
            unset($array['site_url']);
        }
        if (isset($array['created_for_customer_email'])) {
            unset($array['created_for_customer_email']);
        }
        if (isset($array['customer_key'])) {
            unset($array['customer_key']);
        }
        if (isset($array['customer_categories'])) {
            unset($array['customer_categories']);
        }
        if (isset($array['products_found_num'])) {
            unset($array['products_found_num']);
        }
        if (isset($array['time_for_creation'])) {
            unset($array['time_for_creation']);
        }
        if (isset($array['shop']['platform'])) {
            unset($array['shop']['platform']);
        }
        if (isset($array['shop']['version'])) {
            unset($array['shop']['version']);
        }
        if (isset($array['shop']['agency'])) {
            unset($array['shop']['agency']);
        }
        if (isset($array['shop']['email'])) {
            unset($array['shop']['email']);
        }
        if (isset($array['shop']['local_delivery_cost'])) {
            unset($array['shop']['local_delivery_cost']);
        }
        if (isset($array['COMP_CODE'])) {
            unset($array['COMP_CODE']);
        }
        if (isset($array['LANG'])) {
            unset($array['LANG']);
        }
        if (isset($array['METADATA'])) {
            unset($array['METADATA']);
        }
        if (isset($array['COMP_CODE_BUYER'])) {
            unset($array['COMP_CODE_BUYER']);
        }
        if (isset($array['SEARCH_CODE'])) {
            unset($array['SEARCH_CODE']);
        }
        if (isset($array['MANUFACTURER_NAME'])) {
            unset($array['MANUFACTURER_NAME']);
        }
        if (isset($array['TYPE_NAME'])) {
            unset($array['TYPE_NAME']);
        }
        if (isset($array['SUPPLY_TYPE'])) {
            unset($array['SUPPLY_TYPE']);
        }
        if (isset($array['SUPPLIER'])) {
            unset($array['SUPPLIER']);
        }
        if (isset($array['TIME'])) {
            unset($array['TIME']);
        }
        if (isset($array['LANGUAGE'])) {
            unset($array['LANGUAGE']);
        }
        if (isset($array['total_products'])) {
            unset($array['total_products']);
        }
        if (isset($array['MessageNumber'])) {
            unset($array['MessageNumber']);
        }
        if (isset($array['MessageFunctionCode'])) {
            unset($array['MessageFunctionCode']);
        }
        if (isset($array['MessageDate'])) {
            unset($array['MessageDate']);
        }
        if (isset($array['ETIMVersion'])) {
            unset($array['ETIMVersion']);
        }
        if (isset($array['Datapool'])) {
            unset($array['Datapool']);
        }
        if (isset($array['RETURN_STATUS'])) {
            unset($array['RETURN_STATUS']);
        }
        if (isset($array['STATUS_TEXT'])) {
            unset($array['STATUS_TEXT']);
        }
        if (isset($array['CUSTOMER_NUMBER'])) {
            unset($array['CUSTOMER_NUMBER']);
        }
        if (isset($array['CURRENCY'])) {
            unset($array['CURRENCY']);
        }
        if (isset($array['PRICELIST_DATE'])) {
            unset($array['PRICELIST_DATE']);
        }
        if (isset($array['Mensaje'])) {
            unset($array['Mensaje']);
        }
        if (isset($array['Estado'])) {
            unset($array['Estado']);
        }
        if (isset($array['ib_note'])) {
            unset($array['ib_note']);
        }
        if (isset($array['generator'])) {
            unset($array['generator']);
        }
        if (isset($array['categories_list'])) {
            unset($array['categories_list']);
        }
        if ($entity == 'combination' && isset($array['Produs']) && is_array($array['Produs']) && isset($array['Produs'][0]) && is_array($array['Produs'][0]) && isset($array['Produs'][0]['Combinatii'])) {
            foreach ($array['Produs'] as $key => $produs) {
                $array['Produs'][$key]['Combination Attributes'] = '';
                $array['Produs'][$key]['Combination Values'] = '';
                $array['Produs'][$key]['Combination Referinta'] = '';
                $array['Produs'][$key]['Combination EAN13'] = '';
                $array['Produs'][$key]['Combination DataDisponibilitate'] = '';
                $array['Produs'][$key]['Combination StocFurnizor'] = '';
                $array['Produs'][$key]['Combination Stoc'] = '';
                if (isset($produs['Combinatii']) && is_array($produs['Combinatii']) && isset($produs['Combinatii']['Combinatie']) && is_array($produs['Combinatii']['Combinatie']) && $produs['Combinatii']['Combinatie']) {
                    if (isset($produs['Combinatii']['Combinatie'][0]) && is_array($produs['Combinatii']['Combinatie'][0]) && isset($produs['Combinatii']['Combinatie'][0]['Nume'])) {
                        foreach ($produs['Combinatii']['Combinatie'] as $combination_key => $combinatie) {
                            $combinatie_numes = explode(';', $combinatie['Nume']);
                            if ($combination_key === 0) {
                                foreach ($combinatie_numes as $combinatie_nume) {
                                    $combinatie_nume = explode(':', $combinatie_nume);
                                    $array['Produs'][$key]['Combination Attributes'] .= $array['Produs'][$key]['Combination Attributes'] ? $multiple_value_separator : '';
                                    $array['Produs'][$key]['Combination Attributes'] .= trim($combinatie_nume[0]);
                                    $array['Produs'][$key]['Combination Values'] .= $array['Produs'][$key]['Combination Values'] ? $multiple_value_separator : '';
                                    $array['Produs'][$key]['Combination Values'] .= trim($combinatie_nume[1]);
                                }
                                $array['Produs'][$key]['Combination Referinta'] = trim($combinatie['Referinta']);
                                $array['Produs'][$key]['Combination EAN13'] = trim($combinatie['EAN13']);
                                $array['Produs'][$key]['Combination DataDisponibilitate'] = trim($combinatie['DataDisponibilitate']);
                                $array['Produs'][$key]['Combination StocFurnizor'] = trim($combinatie['StocFurnizor']);
                                $array['Produs'][$key]['Combination Stoc'] = trim($combinatie['Stoc']);
                            } else {
                                $produs['Combination Attributes'] = '';
                                $produs['Combination Values'] = '';
                                foreach ($combinatie_numes as $combinatie_nume) {
                                    $combinatie_nume = explode(':', $combinatie_nume);
                                    $produs['Combination Attributes'] .= $produs['Combination Attributes'] ? $multiple_value_separator : '';
                                    $produs['Combination Attributes'] .= trim($combinatie_nume[0]);
                                    $produs['Combination Values'] .= $produs['Combination Values'] ? $multiple_value_separator : '';
                                    $produs['Combination Values'] .= trim($combinatie_nume[1]);
                                }
                                $produs['Combination Referinta'] = trim($combinatie['Referinta']);
                                $produs['Combination EAN13'] = trim($combinatie['EAN13']);
                                $produs['Combination DataDisponibilitate'] = trim($combinatie['DataDisponibilitate']);
                                $produs['Combination StocFurnizor'] = trim($combinatie['StocFurnizor']);
                                $produs['Combination Stoc'] = trim($combinatie['Stoc']);
                                $array['Produs'][] = $produs;
                            }
                        }
                    } elseif (isset($produs['Combinatii']['Combinatie']['Nume'])) {
                        $combinatie_numes = explode(';', $produs['Combinatii']['Combinatie']['Nume']);
                        foreach ($combinatie_numes as $combinatie_nume) {
                            $combinatie_nume = explode(':', $combinatie_nume);
                            $array['Produs'][$key]['Combination Attributes'] .= $array['Produs'][$key]['Combination Attributes'] ? $multiple_value_separator : '';
                            $array['Produs'][$key]['Combination Attributes'] .= trim($combinatie_nume[0]);
                            $array['Produs'][$key]['Combination Values'] .= $array['Produs'][$key]['Combination Values'] ? $multiple_value_separator : '';
                            $array['Produs'][$key]['Combination Values'] .= trim($combinatie_nume[1]);
                        }
                        $array['Produs'][$key]['Combination Referinta'] = trim($produs['Combinatii']['Combinatie']['Referinta']);
                        $array['Produs'][$key]['Combination EAN13'] = trim($produs['Combinatii']['Combinatie']['EAN13']);
                        $array['Produs'][$key]['Combination DataDisponibilitate'] = trim($produs['Combinatii']['Combinatie']['DataDisponibilitate']);
                        $array['Produs'][$key]['Combination StocFurnizor'] = trim($produs['Combinatii']['Combinatie']['StocFurnizor']);
                        $array['Produs'][$key]['Combination Stoc'] = trim($produs['Combinatii']['Combinatie']['Stoc']);
                    }
                }
            }
        }
        if (isset($array['Ladu'][0]['Rida']) && is_array($array['Ladu'][0]['Rida']) && isset($array['Ladu'][0]['Rida'][0]['s'], $array['Ladu'][0]['Rida'][0]['a'])) {
            $array_new = [];
            foreach ($array['Ladu'] as $ladu) {
                $array_new = array_merge($array_new, $ladu['Rida']);
            }
            $array = $array_new;
            unset($array_new);
        }
        if (isset($array['CODEBOOKS'], $array['PRODUCTS'])) {
            unset($array['CODEBOOKS']);
        }
        if (isset($array['Shopdaten'], $array['Artikeldaten'])) {
            $array = $array['Artikeldaten'];
        }
        if (isset($array['priceInfo']['models']['model'])) {
            $array = $array['priceInfo']['models'];
            if (isset($array['@attributes'])) {
                unset($array['@attributes']);
            }
        }
        if (isset($array['stockFeed']['models']['model'])) {
            $array = $array['stockFeed']['models'];
            if (isset($array['@attributes'])) {
                unset($array['@attributes']);
            }
        }
        if (isset($array['productfeed']['@attributes'])) {
            unset($array['productfeed']['@attributes']);
        }
        if (isset($array['group']['o'][0]['@attributes']['id'])) {
            $array = $array['group']['o'];
        }
        if (isset($array['channel']['item'][0]['title'])) {
            $array = $array['channel']['item'];
        }
        if (isset($array['channel']['item'][0]['id'])) {
            $array = $array['channel']['item'];
        }
        if (isset($array['group']['@attributes']['name'], $array['group']['product'])) {
            unset($array['group']['@attributes']);
        }
        if (isset($array['grupy'][0]['products'])) {
            $array = $array['grupy'][0]['products'];
        }
        if (isset($array['ProductCatalog']['Product'][0])) {
            $array = $array['ProductCatalog']['Product'];
        }
        if (isset($array['BODYACTION']['created_at'])) {
            unset($array['BODYACTION']['created_at']);
        }
        if (isset($array['Body']['GetCikkekAuthResponse']['GetCikkekAuthResult']['valasz']['cikk'])) {
            $array = $array['Body']['GetCikkekAuthResponse']['GetCikkekAuthResult']['valasz']['cikk'];
        }
        if (isset($array['Body']['GetCikkekKeszletenAuthResponse']['GetCikkekKeszletenAuthResult']['valasz']['cikkek']['cikk'])) {
            $array = $array['Body']['GetCikkekKeszletenAuthResponse']['GetCikkekKeszletenAuthResult']['valasz']['cikkek']['cikk'];
        }
        if (isset($array['Body']['GetCikkekKeszletValtozasAuthResponse']['GetCikkekKeszletValtozasAuthResult']['valasz']['cikkek']['cikk'])) {
            $array = $array['Body']['GetCikkekKeszletValtozasAuthResponse']['GetCikkekKeszletValtozasAuthResult']['valasz']['cikkek']['cikk'];
        }
        if (isset($array['Body']['GetCikkKepekAuthResponse']['GetCikkKepekAuthResult']['valasz']['cikk'])) {
            $array = $array['Body']['GetCikkKepekAuthResponse']['GetCikkKepekAuthResult']['valasz']['cikk'];
        }
        if (isset($array['Body']['GetArlistaAuthResponse']['GetArlistaAuthResult']['valasz']['arak']['ar'])) {
            $array = $array['Body']['GetArlistaAuthResponse']['GetArlistaAuthResult']['valasz']['arak']['ar'];
        }
        if (isset($array['Body']['GetArValtozasAuthResponse']['GetArValtozasAuthResult']['valasz']['arak'])) {
            $array = $array['Body']['GetArValtozasAuthResponse']['GetArValtozasAuthResult']['valasz']['arak'];
        }
        if (isset($array['broker']['adverts']['advert'])) {
            $array = $array['broker']['adverts']['advert'];
        }
        if (isset($array['products']['product'][0])) {
            $array = $array['products']['product'];
        }
        if (isset($array['Products']['Product'][0])) {
            $array = $array['Products']['Product'];
        }
        if (isset($array['products']['product']['name']) || isset($array['products']['product']['id'])) {
            $array = [$array['products']['product']];
        }
        if (isset($array['channel']['product'][0])) {
            $array = $array['channel']['product'];
        }
        if (!isset($array['all_items']) && (isset($array['item'][0]['SKU']) || isset($array['item1'][0]['SKU']) || isset($array['item2'][0]['SKU']) || isset($array['item3'][0]['SKU']))) {
            $array_new = ['all_items' => []];
            for ($i = 0; $i <= 10; ++$i) {
                $item_node = $i > 0 ? 'item' . $i : 'item';
                if (isset($array[$item_node])) {
                    foreach ($array[$item_node] as $product) {
                        $array_new['all_items'][] = $product;
                    }
                }
            }
            $array = $array_new;
            unset($array_new);
        }

        // Step 4
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
            $array = $first_array;
        }

        // Step 5
        // Process products array before writing to csv
        if ($entity == 'combination' && isset($array[0], $array[0]['id'], $array[0]['title'], $array[0]['sku'], $array[0]['categories'], $array[0]['childrens'])) {
            foreach ($array as $key => $product) {
                if (isset($product['childrens']['child']) && is_array($product['childrens']['child'])) {
                    foreach ($product['childrens']['child'] as $child) {
                        $product['Combination SKU'] = $child['sku'];
                        $product['Size'] = $child['size'];
                        $product['Count'] = $child['count'];
                        unset($product['description']);
                        unset($product['childrens']);
                        $array[] = $product;
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'combination' && isset($array[0], $array[0]['model'], $array[0]['category_name'], $array[0]['variants'])) {
            foreach ($array as $key => $product) {
                if (isset($product['variants']['variant']) && is_array($product['variants']['variant'])) {
                    if (isset($product['variants']['variant']['@attributes']['code']) || isset($product['variants']['variant']['@attributes']['size'])) {
                        $product['variants']['variant'] = [$product['variants']['variant']];
                    }
                    foreach ($product['variants']['variant'] as $variant) {
                        if (isset($variant['@attributes']['code'], $variant['@attributes']['size'])) {
                            $product['Combination Code'] = $variant['@attributes']['code'];
                            $product['Size'] = $variant['@attributes']['size'];
                            unset($product['description']);
                            unset($product['description_long']);
                            unset($product['variants']);
                            $array[] = $product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'combination' && isset($array[0], $array[0]['product_code'], $array[0]['category_id'], $array[0]['variants'])) {
            foreach ($array as $key => $product) {
                if (isset($product['variants']['variant']) && is_array($product['variants']['variant'])) {
                    if (isset($product['variants']['variant']['product_code'])) {
                        $product['variants']['variant'] = [$product['variants']['variant']];
                    }
                    foreach ($product['variants']['variant'] as $variant) {
                        $tmp_product = $product;
                        $attribute_names = '';
                        $attribute_values = '';
                        if (isset($variant['parameters']['parameter'])) {
                            if (isset($variant['parameters']['parameter']['name'])) {
                                $variant['parameters']['parameter'] = [$variant['parameters']['parameter']];
                            }
                            if (isset($variant['parameters']['parameter'][0]['name'])) {
                                foreach ($variant['parameters']['parameter'] as $param) {
                                    $attribute_names .= $attribute_names ? $multiple_value_separator : '';
                                    $attribute_names .= $param['name'];
                                    $attribute_values .= $attribute_values ? $multiple_value_separator : '';
                                    $attribute_values .= $param['value'];
                                }
                            }
                        }
                        if (isset($variant['parameters'])) {
                            unset($variant['parameters']);
                        }
                        foreach ($variant as $variant_key => $variant_value) {
                            $tmp_product['variant_' . $variant_key] = $variant_value;
                        }
                        $tmp_product['attribute_names'] = $attribute_names;
                        $tmp_product['attribute_values'] = $attribute_values;
                        unset($tmp_product['variants']);
                        $array[] = $tmp_product;
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'combination' && isset($array[0], $array[0]['NAME'], $array[0]['ITEM_TYPE'], $array[0]['VARIANTS'])) {
            foreach ($array as $key => $product) {
                if (isset($product['VARIANTS']['VARIANT']) && is_array($product['VARIANTS']['VARIANT'])) {
                    foreach ($product['VARIANTS']['VARIANT'] as $variant) {
                        if (isset($variant['PARAMETERS']['PARAMETER']) && is_array($variant['PARAMETERS']['PARAMETER'])) {
                            $comb_attributes = '';
                            $comb_values = '';
                            if (isset($variant['PARAMETERS']['PARAMETER']['NAME'], $variant['PARAMETERS']['PARAMETER']['VALUE'])) {
                                $comb_attributes = $variant['PARAMETERS']['PARAMETER']['NAME'];
                                $comb_values = $variant['PARAMETERS']['PARAMETER']['VALUE'];
                            } elseif (isset($variant['PARAMETERS']['PARAMETER'][0]['NAME'], $variant['PARAMETERS']['PARAMETER'][0]['VALUE'])) {
                                foreach ($variant['PARAMETERS']['PARAMETER'] as $parameter) {
                                    $comb_attributes .= $comb_attributes ? $multiple_value_separator : '';
                                    $comb_attributes .= $parameter['NAME'];
                                    $comb_values .= $comb_values ? $multiple_value_separator : '';
                                    $comb_values .= $parameter['VALUE'];
                                }
                            }
                            $product['VARIANT PARAMETER NAME'] = $comb_attributes;
                            $product['VARIANT PARAMETER VALUE'] = $comb_values;
                            $product['VARIANT CODE'] = $variant['CODE'];
                            $product['VARIANT EAN'] = $variant['EAN'];
                            $product['VARIANT CURRENCY'] = $variant['CURRENCY'];
                            $product['VARIANT VAT'] = $variant['VAT'];
                            $product['VARIANT PRICE'] = $variant['PRICE'];
                            $product['VARIANT PURCHASE_PRICE'] = $variant['PURCHASE_PRICE'];
                            $product['VARIANT STANDARD_PRICE'] = $variant['STANDARD_PRICE'];
                            $product['VARIANT PRICE_VAT'] = $variant['PRICE_VAT'];
                            $product['VARIANT AVAILABILITY'] = $variant['AVAILABILITY'];
                            unset($product['SHORT_DESCRIPTION']);
                            unset($product['DESCRIPTION']);
                            unset($product['CATEGORIES']);
                            unset($product['VARIANTS']);
                            $array[] = $product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'combination' && isset($array[0]['Product_code'], $array[0]['Product_id'])) {
            // Check if this file has variants
            $variants_exist = false;
            foreach ($array as $key => $product) {
                if (isset($product['variants']['variant'][0]['spec']) || isset($product['variants']['variant']['spec'])) {
                    $variants_exist = true;
                    break;
                }
            }
            if ($variants_exist) {
                foreach ($array as $key => $product) {
                    if (isset($product['variants']['variant'][0]['spec'])) {
                        foreach ($product['variants']['variant'] as $variant) {
                            unset($variant['spec']); // This is taken care of in the beginning $xml
                            $new_product = array_merge(['Product_code' => $product['Product_code']], $variant);
                            $array[] = $new_product;
                        }
                    } elseif (isset($product['variants']['variant']['spec'])) {
                        unset($product['variants']['variant']['spec']);
                        $new_product = array_merge(['Product_code' => $product['Product_code']], $product['variants']['variant']);
                        $array[] = $new_product;
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0], $array[0]['inventory']['quantity'], $array[0]['inventory']['price'])) {
            foreach ($array as $key => $product) {
                if (isset($product['inventory']) && is_array($product['inventory']) && isset($product['inventory']['quantity'], $product['inventory']['price'])) {
                    $array[$key]['inventory_quantity'] = $product['inventory']['quantity'];
                    $array[$key]['inventory_price'] = $product['inventory']['price'];
                    unset($array[$key]['inventory']);
                }
            }
        } elseif (isset($first_array['SHOPITEM'][0]['ITEM_ID'], $array[0]['EAN'], $array[0]['PRODUCT'])) {
            $ITEMGROUP_IDs = [];
            foreach ($array as $key => $product) {
                if (isset($product['DELIVERY']) && is_array($product['DELIVERY']) && isset($product['DELIVERY'][0]['DELIVERY_ID']) && $product['DELIVERY'][0]['DELIVERY_ID'] && isset($product['DELIVERY'][0]['DELIVERY_PRICE'])) {
                    $delivery_id = '';
                    $delivery_price = '';
                    foreach ($product['DELIVERY'] as $delivery) {
                        if (isset($delivery['DELIVERY_ID'], $delivery['DELIVERY_PRICE'])) {
                            $delivery_id .= $delivery_id ? $multiple_value_separator : '';
                            $delivery_id .= $delivery['DELIVERY_ID'];
                            $delivery_price .= $delivery_price ? $multiple_value_separator : '';
                            $delivery_price .= $delivery['DELIVERY_PRICE'];
                        }
                    }
                    $product['DELIVERY_ID'] = $delivery_id;
                    $product['DELIVERY_PRICE'] = $delivery_price;
                    $array[$key]['DELIVERY_ID'] = $delivery_id;
                    $array[$key]['DELIVERY_PRICE'] = $delivery_price;
                }
                if ($entity == 'product' && isset($product['ITEMGROUP_ID'], $product['PARAM'])) {
                    if (!in_array($product['ITEMGROUP_ID'], $ITEMGROUP_IDs)) {
                        $ITEMGROUP_IDs[] = $product['ITEMGROUP_ID'];
                        $array[] = $product;
                    }
                    unset($array[$key]);
                } elseif ($entity == 'combination') {
                    if (isset($product['ITEMGROUP_ID'], $product['PARAM'])) {
                        $attribute_names = '';
                        $attribute_values = '';
                        if (isset($product['PARAM']['PARAM_NAME'])) {
                            $attribute_names = $product['PARAM']['PARAM_NAME'];
                            $attribute_values = $product['PARAM']['VAL'];
                        } elseif (isset($product['PARAM'][0])) {
                            foreach ($product['PARAM'] as $param) {
                                $attribute_names .= $attribute_names ? $multiple_value_separator : '';
                                $attribute_names .= $param['PARAM_NAME'];
                                $attribute_values .= $attribute_values ? $multiple_value_separator : '';
                                $attribute_values .= $param['VAL'];
                            }
                        }
                        $array[$key]['AttributeNames'] = $attribute_names;
                        $array[$key]['AttributeValues'] = $attribute_values;
                        unset($array[$key]['PARAM']);
                    } else {
                        unset($array[$key]);
                    }
                }
            }
        } elseif (isset($first_array['ITEM'][0]['ITEM_ID'], $array[0]['ITEMGROUP_ID'], $array[0]['EAN'])) {
            $ITEMGROUP_IDs = [];
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    if (isset($product['ITEMGROUP_ID']) && !in_array($product['ITEMGROUP_ID'], $ITEMGROUP_IDs)) {
                        $ITEMGROUP_IDs[] = $product['ITEMGROUP_ID'];
                        if (isset($product['DELIVERY'])) {
                            unset($product['DELIVERY']);
                        }
                        $array[] = $product;
                    }
                    unset($array[$key]);
                } elseif ($entity == 'combination') {
                    if (isset($product['ITEMGROUP_ID'])) {
                        if (isset($product['PARAM'])) {
                            unset($array[$key]['PARAM']);
                        }
                        if (isset($product['DELIVERY'])) {
                            unset($array[$key]['DELIVERY']);
                        }
                    } else {
                        unset($array[$key]);
                    }
                }
            }
        } elseif (isset($first_array['Produkt'][0]['ID'], $array[0]['WID'], $array[0]['Ilosc'], $array[0]['Nazwa'])) {
            $Produkt_IDs = [];
            if ($entity == 'product') {
                foreach ($array as $key => $product) {
                    if (isset($product['ID']) && $product['ID'] && !in_array($product['ID'], $Produkt_IDs)) {
                        $Produkt_IDs[] = $product['ID'];
                    } else {
                        unset($array[$key]);
                    }
                }
            } elseif ($entity == 'combination') {
                foreach ($array as $key => $product) {
                    if (isset($product['Atrybuty'], $product['Atrybuty']['Atrybut'])) {
                        unset($array[$key]['Opis']);
                        $attribute_names = '';
                        $attribute_values = '';
                        if (isset($product['Atrybuty']['Atrybut'][0])) {
                            foreach ($product['Atrybuty']['Atrybut'] as $Atrybut) {
                                $attribute_names .= $attribute_names ? $multiple_value_separator : '';
                                $attribute_names .= $Atrybut['NazwaAtrybutu'];
                                $attribute_values .= $attribute_values ? $multiple_value_separator : '';
                                $attribute_values .= $Atrybut['WartoscAtrybutu'];
                            }
                        }
                        $array[$key]['AttributeNames'] = $attribute_names;
                        $array[$key]['AttributeValues'] = $attribute_values;
                        unset($array[$key]['Atrybuty']);
                    } else {
                        unset($array[$key]);
                    }
                }
            }
        } elseif ($entity == 'combination' && isset($first_array['product'][0], $array[0]['reward'], $array[0]['product_id'], $array[0]['reference'])) {
            foreach ($array as $key => $product) {
                if (isset($product['attributes']) && is_array($product['attributes'])) {
                    $product_attributes = $product['attributes'];
                    foreach ($product_attributes as $key => $attribute) {
                        if (isset($attribute['group_name'], $attribute['attribute_name'], $attribute['quantity'])) {
                            $product['attribute_reference'] = $product['reference'] . str_replace('attributes', '', $key);
                            $product['attribute_group_name'] = $attribute['group_name'];
                            $product['attribute_name'] = $attribute['attribute_name'];
                            $product['attribute_quantity'] = $attribute['quantity'];
                        }
                        unset($product['attributes']);
                        unset($product['description']);
                        unset($product['description_short']);
                        unset($product['meta_description']);
                        unset($product['manufacturer_name']);
                        $array[] = $product;
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'product' && isset($first_array['product'][0]['attr_group_1'], $first_array['product'][0]['attribute_1'], $first_array['product'][0]['attribute_value_1'])) {
            foreach ($array as $key => $product) {
                $features = '';
                for ($i = 1; $i <= 30; ++$i) {
                    if (isset($product['attribute_' . $i]) && $product['attribute_' . $i] && isset($product['attribute_value_' . $i]) && $product['attribute_value_' . $i]) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $product['attribute_' . $i] . ':' . $product['attribute_value_' . $i];
                    }
                }
                $array[$key]['FEATURES'] = $features;
            }
        } elseif ($entity == 'product' && isset($first_array['product'][0]['product_names']['product_name'][0]['language_code'], $first_array['product'][0]['product_names']['product_name'][0]['name'])) {
            foreach ($array as $key => $product) {
                if (isset($product['product_names']['product_name'])) {
                    if (isset($product['product_names']['product_name']['name'])) {
                        $array[$key]['product_name_' . $product['product_names']['product_name']['language_code']] = $product['product_names']['product_name']['name'];
                    } else {
                        foreach ($product['product_names']['product_name'] as $n) {
                            $array[$key]['product_name_' . $n['language_code']] = $n['name'];
                        }
                    }
                    unset($array[$key]['product_names']);
                }
                if (isset($product['product_descriptions']['product_description'])) {
                    if (isset($product['product_descriptions']['product_description']['description'])) {
                        $array[$key]['product_description_' . $product['product_descriptions']['product_description']['language_code']] = $product['product_descriptions']['product_description']['description'];
                    } else {
                        foreach ($product['product_descriptions']['product_description'] as $d) {
                            $array[$key]['product_description_' . $d['language_code']] = $d['description'];
                        }
                    }
                    unset($array[$key]['product_descriptions']);
                }
            }
        } elseif ($entity == 'product' && isset($first_array['productfeedRow'][0]['Model'], $first_array['productfeedRow'][0]['ItemCode'], $first_array['productfeedRow'][0]['ItemDesc'], $first_array['productfeedRow'][0]['ExtDesc'])) {
            $Model_IDs = [];
            foreach ($array as $key => $product) {
                if (isset($product['Model']) && $product['Model'] && !in_array($product['Model'], $Model_IDs)) {
                    $Model_IDs[] = $product['Model'];
                } else {
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'combination' && isset($first_array['priceInfo']['models']['model'][0]['items']['item'])) {
            foreach ($array as $key => $product) {
                if (isset($product['items']['item'][0])) {
                    foreach ($product['items']['item'] as $item) {
                        $new_item = [
                            'modelcode' => $product['@attributes']['modelcode'],
                            'itemcode' => $item['@attributes']['itemcode'],
                            'nettPrice' => $item['scales']['scale'][0]['nettPrice'],
                        ];
                        $array[] = $new_item;
                    }
                } elseif (isset($product['items']['item']['scales'])) {
                    $new_item = [
                        'modelcode' => $product['@attributes']['modelcode'],
                        'itemcode' => $product['items']['item']['@attributes']['itemcode'],
                        'nettPrice' => $product['items']['item']['scales']['scale'][0]['nettPrice'],
                    ];
                    $array[] = $new_item;
                }
                unset($array[$key]);
            }
        } elseif ($entity == 'combination' && isset($first_array['stockFeed']['models']['model'][0]['items']['item'])) {
            foreach ($array as $key => $product) {
                if (isset($product['items']['item'][0])) {
                    foreach ($product['items']['item'] as $item) {
                        $new_item = [
                            'modelCode' => $product['@attributes']['modelCode'],
                            'itemCode' => $item['@attributes']['itemCode'],
                            'stockDirect' => $item['stockDirect'],
                        ];
                        $array[] = $new_item;
                    }
                } elseif (isset($product['items']['item']['stockDirect'])) {
                    $new_item = [
                        'modelCode' => $product['@attributes']['modelCode'],
                        'itemCode' => $product['items']['item']['@attributes']['itemCode'],
                        'stockDirect' => $product['items']['item']['stockDirect'],
                    ];
                    $array[] = $new_item;
                }
                unset($array[$key]);
            }
        } elseif (isset($first_array['article'][0]['article_nr'], $first_array['article'][0]['article_configurations']['configuration'])) {
            if ($entity == 'product') {
                foreach ($array as $key => $product) {
                    $array[$key]['title_it'] = '';
                    $array[$key]['description_it'] = '';
                    $array[$key]['categories'] = '';
                    $array[$key]['price'] = '';

                    if (isset($product['article_configurations']['configuration'])) {
                        if (isset($product['article_configurations']['configuration']['code'])) {
                            $first_configuration = $product['article_configurations']['configuration'];
                        } else {
                            $first_configuration = $product['article_configurations']['configuration'][0];
                        }

                        $array[$key]['title_it'] = isset($first_configuration['title']['it']) ? $first_configuration['title']['it'] : '';
                        $array[$key]['description_it'] = isset($first_configuration['description']['it']) ? $first_configuration['description']['it'] : '';

                        if (isset($first_configuration['categories']['category'][0])) {
                            $all_cats = [];
                            foreach ($first_configuration['categories']['category'] as $cat) {
                                if (!in_array($cat['@attributes']['id'], $all_cats)) {
                                    $all_cats[$cat['@attributes']['id']] = $cat['it'];
                                }
                            }
                            foreach ($first_configuration['categories']['category'] as $cat) {
                                if (!isset($cat['@attributes']['parent_id'])) {
                                    continue;
                                }
                                if ($array[$key]['categories']) {
                                    $array[$key]['categories'] .= $multiple_value_separator;
                                }
                                if (isset($cat['@attributes']['parent_id'], $all_cats[$cat['@attributes']['parent_id']])) {
                                    $array[$key]['categories'] .= $all_cats[$cat['@attributes']['parent_id']] . '/';
                                }
                                $array[$key]['categories'] .= $cat['it'];
                            }
                        }

                        $array[$key]['price'] = is_array($first_configuration['prices']['price']) ? end($first_configuration['prices']['price']) : '';

                        unset($array[$key]['article_configurations']);
                        unset($array[$key]['labels']);
                        unset($array[$key]['materials']);
                        unset($array[$key]['videos']);
                    }
                }
            } elseif ($entity == 'combination') {
                foreach ($array as $key => $product) {
                    if (isset($product['article_configurations']['configuration'][0]['code'])) {
                        foreach ($product['article_configurations']['configuration'] as $c) {
                            $new_item = [
                                'article_nr' => $product['article_nr'],
                                'code' => $c['code'],
                                'ean' => $c['ean'],
                                'color' => $c['color']['it'],
                                'version' => $c['version']['it'],
                                'size' => $c['size']['it'],
                                'images' => $c['images'],
                            ];
                            $array[] = $new_item;
                        }
                    } elseif (isset($product['article_configurations']['configuration']['code'])) {
                        $c = $product['article_configurations']['configuration'];
                        $new_item = [
                            'article_nr' => $product['article_nr'],
                            'code' => $c['code'],
                            'ean' => $c['ean'],
                            'color' => $c['color']['it'],
                            'version' => $c['version']['it'],
                            'size' => $c['size']['it'],
                        ];
                        $array[] = $new_item;
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'product' && isset($first_array['PRODUCTS']['PRODUCT'][0]['PRODUCT_NUMBER'], $first_array['PRODUCTS']['PRODUCT'][0]['PRODUCT_BASE_NUMBER'], $first_array['PRODUCTS']['PRODUCT'][0]['PRODUCT_PRINT_ID'])) {
            $PRODUCT_IDs = [];
            foreach ($array as $key => $product) {
                if (isset($product['PRODUCT_BASE_NUMBER']) && $product['PRODUCT_BASE_NUMBER'] && !in_array($product['PRODUCT_BASE_NUMBER'], $PRODUCT_IDs)) {
                    $PRODUCT_IDs[] = $product['PRODUCT_BASE_NUMBER'];
                } else {
                    unset($array[$key]);
                }
            }
        } elseif (isset($first_array['productfeed']['models']['model'][0]['items']['item'])) {
            if ($entity == 'product') {
                foreach ($array as $key => $product) {
                    $categoryData = [];
                    $first_item = [];
                    if (isset($product['items']['item'][0]['categoryData']['groupDesc'])) {
                        $first_item = $product['items']['item'][0];
                        $categoryData = $product['items']['item'][0]['categoryData'];
                    } elseif (isset($product['items']['item']['categoryData']['groupDesc'])) {
                        $first_item = $product['items']['item'];
                        $categoryData = $product['items']['item']['categoryData'];
                    }
                    $categoryDataText = '';
                    if (isset($categoryData['groupDesc']) && $categoryData['groupDesc']) {
                        $categoryDataText = $categoryData['groupDesc'];
                    }
                    if (isset($categoryData['catDesc']) && $categoryData['catDesc']) {
                        $categoryDataText .= $categoryDataText ? $multiple_value_separator : '';
                        $categoryDataText .= $categoryData['catDesc'];
                    }
                    $array[$key]['categoryData'] = $categoryDataText;

                    if (isset($first_item['colors']['color'])) {
                        if (isset($first_item['colors']['color'][0])) {
                            $first_item['colors']['color'] = $first_item['colors']['color'][0];
                        }
                        $first_item = array_merge($first_item, $first_item['colors']['color']);
                    }
                    if (isset($first_item['measurements'])) {
                        $first_item = array_merge($first_item, $first_item['measurements']);
                    }
                    unset($first_item['@attributes']);
                    unset($first_item['colors']);
                    unset($first_item['measurements']);
                    unset($first_item['decorationSettings']);
                    unset($first_item['categoryData']);
                    unset($first_item['battery']);
                    unset($first_item['imageData']);
                    unset($first_item['catalogues']);
                    unset($first_item['themes']);
                    unset($array[$key]['items']);

                    $array[$key] = array_merge($array[$key], $first_item);
                }
            } elseif ($entity == 'combination') {
                foreach ($array as $key => $product) {
                    $modelCode = isset($product['@attributes']['modelCode']) ? $product['@attributes']['modelCode'] : '';
                    if (isset($product['items']['item']['@attributes']['itemCode'])) {
                        $product['items']['item'] = [$product['items']['item']];
                    }
                    if (isset($product['items']['item'][0])) {
                        foreach ($product['items']['item'] as $item) {
                            $itemCode = isset($item['@attributes']['itemCode']) ? $item['@attributes']['itemCode'] : '';
                            if (isset($item['colors']['color'])) {
                                if (isset($item['colors']['color'][0])) {
                                    $item['colors']['color'] = $item['colors']['color'][0];
                                }
                                $item = array_merge($item, $item['colors']['color']);
                            }
                            if (isset($item['imageData'])) {
                                $item = array_merge($item, $item['imageData']);
                            }
                            if (isset($item['measurements'])) {
                                $item = array_merge($item, $item['measurements']);
                            }
                            unset($item['@attributes']);
                            unset($item['categoryData']);
                            unset($item['colors']);
                            unset($item['imageData']);
                            unset($item['measurements']);
                            unset($item['decorationSettings']);
                            unset($item['battery']);
                            unset($item['catalogues']);
                            unset($item['themes']);
                            $new_item = array_merge(['modelCode' => $modelCode, 'itemCode' => $itemCode], $item);
                            $array[] = $new_item;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($first_array['articolo'][0]['@attributes']['codice'], $first_array['articolo'][0]['articolo_padre'], $first_array['articolo'][0]['nome_articolo'])) {
            $Produkt_IDs = [];
            if ($entity == 'product') {
                foreach ($array as $key => $product) {
                    if (isset($product['articolo_padre']) && $product['articolo_padre'] && !in_array($product['articolo_padre'], $Produkt_IDs)) {
                        $Produkt_IDs[] = $product['articolo_padre'];
                    } else {
                        unset($array[$key]);
                    }
                }
            } elseif ($entity == 'combination') {
                foreach ($array as $key => $product) {
                    if ((isset($product['deco_colore_articolo']) && $product['deco_colore_articolo']) || (isset($product['taglia_articolo']) && $product['taglia_articolo'])) {
                        // Make sure at least one attribute exists. Otherwise this product has no combinations.
                    } else {
                        unset($array[$key]);
                    }
                }
            }
        } elseif (isset($array[0]['STOCK']['AMOUNT'], $array[0]['STOCK']['MINIMAL_AMOUNT'])) {
            foreach ($array as $key => $product) {
                if (isset($product['STOCK']['AMOUNT'], $product['STOCK']['MINIMAL_AMOUNT'])) {
                    $array[$key]['STOCK_AMOUNT'] = $product['STOCK']['AMOUNT'];
                    $array[$key]['STOCK_MINIMAL_AMOUNT'] = $product['STOCK']['MINIMAL_AMOUNT'];
                    unset($array[$key]['STOCK']);
                }
            }
        } elseif (isset($array[0]['CHARACTERISTICS']['WEIGHT'])) {
            foreach ($array as $key => $product) {
                if (isset($product['CHARACTERISTICS']) && is_array($product['CHARACTERISTICS'])) {
                    foreach ($product['CHARACTERISTICS'] as $char_name => $char_value) {
                        $array[$key][$char_name] = $char_value;
                    }
                    unset($array[$key]['CHARACTERISTICS']);
                }
            }
        } elseif (isset($array[0]['ProductIdentifier']['ProductIDType'], $array[0]['ProductIdentifier']['IDValue'])) {
            foreach ($array as $key => $product) {
                $array[$key]['NAME'] = '';
                if (isset($product['DescriptiveDetail']['TitleDetail']['TitleStatement'])) {
                    $array[$key]['NAME'] = $product['DescriptiveDetail']['TitleDetail']['TitleStatement'];
                }
                $array[$key]['EAN'] = '';
                if (isset($product['ProductIdentifier']['IDValue'])) {
                    $array[$key]['EAN'] = $product['ProductIdentifier']['IDValue'];
                }
                $array[$key]['DESCRIPTION'] = '';
                if (isset($product['CollateralDetail']['TextContent']['Text'])) {
                    $array[$key]['DESCRIPTION'] = $product['CollateralDetail']['TextContent']['Text'];
                }
                $array[$key]['WHOLESALE_PRICE'] = '';
                if (isset($product['ProductSupply']['SupplyDetail']['Price']['PriceAmount'])) {
                    $array[$key]['WHOLESALE_PRICE'] = $product['ProductSupply']['SupplyDetail']['Price']['PriceAmount'];
                }
                $array[$key]['CATEGORIES'] = '';
                if (isset($product['DescriptiveDetail']['Collection']['TitleDetail']['TitleElement']['TitleWithoutPrefix'])) {
                    $array[$key]['CATEGORIES'] = $product['DescriptiveDetail']['Collection']['TitleDetail']['TitleElement']['TitleWithoutPrefix'];
                }
                $array[$key]['PRODUCT_IMAGES'] = '';
                if (isset($product['CollateralDetail']['SupportingResource']) && is_array($product['CollateralDetail']['SupportingResource'])) {
                    foreach ($product['CollateralDetail']['SupportingResource'] as $supportingResource) {
                        if (isset($supportingResource['ResourceVersion']['ResourceLink'])) {
                            $array[$key]['PRODUCT_IMAGES'] .= $array[$key]['PRODUCT_IMAGES'] ? $multiple_value_separator : '';
                            $array[$key]['PRODUCT_IMAGES'] .= $supportingResource['ResourceVersion']['ResourceLink'];
                        }
                    }
                }
                unset($array[$key]['ProductIdentifier']);
                unset($array[$key]['DescriptiveDetail']);
                unset($array[$key]['CollateralDetail']);
                unset($array[$key]['PublishingDetail']);
                unset($array[$key]['ProductSupply']);
            }
        } elseif (isset($first_array['SHOPITEM'][0]['PRODUCT'], $first_array['SHOPITEM'][0]['ID_PRODUCT'], $first_array['SHOPITEM'][0]['SKUPINA'], $first_array['SHOPITEM'][0]['ID_PRODUCT'])) {
            $product_refs = [];
            if ($entity == 'product') {
                foreach ($array as $key => $product) {
                    if (isset($product['PRODUCT']) && $product['PRODUCT'] && !in_array($product['PRODUCT'], $product_refs)) {
                        $product_refs[] = $product['PRODUCT'];
                    } else {
                        unset($array[$key]);
                    }
                }
            }
        } elseif ($entity == 'combination' && isset($first_array['SHOPITEM'][0]['PRODUCT_ID'], $array[0]['PRODUCT_OPTIONS'])) {
            foreach ($array as $key => $product) {
                if (isset($product['DESCRIPTION'])) {
                    $array[$key]['DESCRIPTION'] = '';
                    $product['DESCRIPTION'] = '';
                }
                if (isset($product['CATEGORY'])) {
                    $array[$key]['CATEGORY'] = '';
                    $product['CATEGORY'] = '';
                }
                if ((isset($product['PRODUCT_OPTIONS']['OPTIONS']['OPTION_GROUP']) || isset($product['PRODUCT_OPTIONS']['OPTIONS'][0]['OPTION_GROUP']))
                    && (isset($product['PRODUCT_OPTIONS']['OPTIONS']['CHOICES']['CHOICE']) || isset($product['PRODUCT_OPTIONS']['OPTIONS'][0]['CHOICES']['CHOICE']))) {
                    if (!isset($product['PRODUCT_OPTIONS']['OPTIONS'][1])) {
                        $product['PRODUCT_OPTIONS']['OPTIONS'] = [$product['PRODUCT_OPTIONS']['OPTIONS']];
                    }
                    $tmp_product = $product;
                    $tmp_product['PRODUCT_OPTIONS'] = '';
                    foreach ($product['PRODUCT_OPTIONS']['OPTIONS'] as $OPTION) {
                        $tmp_product['PRODUCT_OPTIONS_GROUP'] = $OPTION['OPTION_GROUP'];
                        if (isset($OPTION['CHOICES']['CHOICE']['NAME'])) {
                            $OPTION['CHOICES']['CHOICE'] = [$OPTION['CHOICES']['CHOICE']];
                        }
                        foreach ($OPTION['CHOICES']['CHOICE'] as $CHOICE) {
                            $tmp_product['PRODUCT_OPTIONS_CHOICE_NAME'] = $CHOICE['NAME'];
                            $tmp_product['PRODUCT_OPTIONS_CHOICE_PRICE'] = $CHOICE['PRICE'];
                            $tmp_product['PRODUCT_OPTIONS_CHOICE_UPC'] = $CHOICE['UPC'];
                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($first_array['SHOPITEM'][0]['ID'], $first_array['SHOPITEM'][0]['PRODUCT'], $first_array['SHOPITEM'][0]['CODE'], $first_array['SHOPITEM'][0]['URL'])) {
            $ID_GROUPS = [];
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    if (isset($product['ID_GROUP']) && $product['ID_GROUP']) {
                        if (in_array($product['ID_GROUP'], $ID_GROUPS)) {
                            unset($array[$key]);
                        } else {
                            $ID_GROUPS[] = $product['ID_GROUP'];
                            $array[$key]['ID'] = $product['ID_GROUP'];
                        }
                    }
                } elseif ($entity == 'combination') {
                    if (!isset($product['ID_GROUP']) || !$product['ID_GROUP']) {
                        unset($array[$key]);
                    } elseif (isset($product['SIZE']['NAME'], $product['SIZE']['VALUE'])) {
                        $array[$key]['SIZE_NAME'] = $product['SIZE']['NAME'];
                        $array[$key]['SIZE_VALUE'] = str_replace(':', '∶', $product['SIZE']['VALUE']);
                    }
                }
            }
        } elseif ((isset($array[0]['name']['item'][0]['language_code'], $array[0]['name']['item'][0]['text'])) || (isset($array[0]['description']['item'][0]['language_code'], $array[0]['description']['item'][0]['text']))) {
            foreach ($array as $key => $product) {
                if (isset($product['name']['item'][0]['language_code'], $product['name']['item'][0]['text'])) {
                    foreach ($product['name']['item'] as $name_item) {
                        $array[$key]['name_' . $name_item['language_code']] = $name_item['text'];
                    }
                    unset($array[$key]['name']);
                }
                if (isset($product['description']['item'][0]['language_code'], $product['description']['item'][0]['text'])) {
                    foreach ($product['description']['item'] as $description_item) {
                        $array[$key]['description_' . $description_item['language_code']] = $description_item['text'];
                    }
                    unset($array[$key]['description']);
                }
            }
        } elseif (isset($array[0]['nazev'], $array[0]['hlavniobrazek'], $array[0]['dalsiobrazky'], $array[0]['ceny']['moc'], $array[0]['ceny']['voc'])) {
            foreach ($array as $key => $product) {
                if (isset($product['hlavniobrazek']['maly'])) {
                    unset($array[$key]['hlavniobrazek']['maly']);
                }
                if (isset($product['dalsiobrazky']['obrazek'])) {
                    if (isset($product['dalsiobrazky']['obrazek']['maly'])) {
                        $array[$key]['dalsiobrazky']['obrazek'] = [$product['dalsiobrazky']['obrazek']];
                    }
                    foreach ($array[$key]['dalsiobrazky']['obrazek'] as $i => $obrazek) {
                        if (isset($obrazek['maly'])) {
                            unset($array[$key]['dalsiobrazky']['obrazek'][$i]['maly']);
                        }
                    }
                }
                if (isset($product['ceny']['moc']['bezdph'])) {
                    $array[$key]['ceny_moc_bezdph'] = $product['ceny']['moc']['bezdph'];
                    if (isset($product['ceny']['moc']['sdph'])) {
                        $array[$key]['ceny_moc_sdph'] = $product['ceny']['moc']['sdph'];
                    }
                    if (isset($product['ceny']['voc']['bezdph'])) {
                        $array[$key]['ceny_voc_bezdph'] = $product['ceny']['voc']['bezdph'];
                    }
                    if (isset($product['ceny']['voc']['sdph'])) {
                        $array[$key]['ceny_voc_sdph'] = $product['ceny']['voc']['sdph'];
                    }
                    unset($array[$key]['ceny']);
                }
            }
        } elseif ($entity == 'combination' && isset($first_array['SHOPITEM'][0]['PRODUCT_ID'], $array[0]['OBJEDNAVKA'])) {
            // check if it has VARIANT
            $has_variant = false;
            foreach ($array as $key => $product) {
                if (isset($product['VARIANT']['VARIANT_ID']) || isset($product['VARIANT'][0]['VARIANT_ID'])) {
                    $has_variant = true;
                    break;
                }
            }
            if ($has_variant) {
                foreach ($array as $key => $product) {
                    if (isset($product['VARIANT']['VARIANT_ID'])) {
                        $product['VARIANT'] = [$product['VARIANT']];
                    }
                    if (isset($product['DESCRIPTION'])) {
                        unset($product['DESCRIPTION']);
                    }
                    if (isset($product['DESCRIPTION_SHORT'])) {
                        unset($product['DESCRIPTION_SHORT']);
                    }
                    if (isset($product['CATEGORYTEXT'])) {
                        unset($product['CATEGORYTEXT']);
                    }
                    if (isset($product['VARIANT'][0]['VARIANT_ID'])) {
                        foreach ($product['VARIANT'] as $variant) {
                            if (!isset($variant['PARAM'][0])) {
                                $variant['PARAM'] = [$variant['PARAM']];
                            }
                            if (isset($variant['PARAM'][0]['PARAM_NAME'], $variant['PARAM'][0]['VAL'])) {
                                $variant['PARAM_NAMES'] = '';
                                $variant['PARAM_VALS'] = '';
                                foreach ($variant['PARAM'] as $param) {
                                    $variant['PARAM_NAMES'] .= $variant['PARAM_NAMES'] ? $multiple_value_separator : '';
                                    $variant['PARAM_NAMES'] .= $param['PARAM_NAME'];
                                    $variant['PARAM_VALS'] .= $variant['PARAM_VALS'] ? $multiple_value_separator : '';
                                    $variant['PARAM_VALS'] .= $param['VAL'];
                                }
                            }
                            $tmp_product = array_merge($product, $variant);
                            unset($tmp_product['VARIANT']);
                            unset($tmp_product['PARAM']);

                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif ($entity == 'product' && isset($array[0]['ITEM_ID'], $array[0]['ITEMGROUP_ID'])) {
            $has_attachment = false;
            foreach ($array as $key => $product) {
                if (isset($product['FILE_ATTACHMENT']['ATTACHMENT_NAME']) || isset($product['FILE_ATTACHMENT'][0]['ATTACHMENT_NAME'])) {
                    $has_attachment = true;
                    break;
                }
            }
            if ($has_attachment) {
                foreach ($array as $key => $product) {
                    if (isset($product['FILE_ATTACHMENT']['ATTACHMENT_NAME'])) {
                        $product['FILE_ATTACHMENT'] = [$product['FILE_ATTACHMENT']];
                    }
                    if (isset($product['FILE_ATTACHMENT'][0]['ATTACHMENT_NAME'])) {
                        $attachment_names = '';
                        foreach ($product['FILE_ATTACHMENT'] as $file_attachment) {
                            if (isset($file_attachment['ATTACHMENT_NAME'])) {
                                $attachment_names .= $attachment_names ? $multiple_value_separator : '';
                                $attachment_names .= $file_attachment['ATTACHMENT_NAME'];
                            }
                        }
                        $array[$key]['ATTACHMENT_NAMES'] = $attachment_names;
                    }
                }
            }
        } elseif (isset($array[0]['id'], $array[0]['barcode'], $array[0]['variants']['variant']) && (isset($array[0]['variants']['variant']['@attributes']['group_title']) || isset($array[0]['variants']['variant'][0]['@attributes']['group_title']))) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['variants']);
                } else {
                    if (isset($product['variants']['variant']['title'])) {
                        $product['variants']['variant'] = [$product['variants']['variant']];
                    }
                    foreach ($product['variants']['variant'] as $variant) {
                        $tmp_product = $product;
                        $tmp_product['variant_group_title'] = isset($variant['@attributes']['group_title']) ? $variant['@attributes']['group_title'] : '';
                        $tmp_product['variant_title'] = isset($variant['title']) ? $variant['title'] : '';
                        $tmp_product['variant_quantity'] = isset($variant['quantity']) ? $variant['quantity'] : '';
                        $tmp_product['variant_barcode'] = isset($variant['barcode']) ? $variant['barcode'] : '';
                        $tmp_product['variant_price'] = isset($variant['price']) ? $variant['price'] : '';
                        unset($tmp_product['variants']);
                        $array[] = $tmp_product;
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($first_array['@attributes']['file_format'], $first_array['@attributes']['generated'], $first_array['@attributes']['version'], $first_array['products']['product'])) {
            foreach ($array as $key => $product) {
                if (isset($product['description']['name'][0]) && is_array($product['description']['name'])) {
                    foreach ($product['description']['name'] as $key_name => $name) {
                        $product['name_' . ($key_name + 1)] = $name;
                    }
                } elseif (isset($product['description']['name'])) {
                    $product['name'] = $product['description']['name'];
                }
                if (isset($product['description']['long_desc'][0]) && is_array($product['description']['long_desc'])) {
                    foreach ($product['description']['long_desc'] as $key_long_desc => $long_desc) {
                        $product['long_desc_' . ($key_long_desc + 1)] = $long_desc;
                    }
                } elseif (isset($product['description']['long_desc'])) {
                    $product['long_desc'] = $product['description']['long_desc'];
                }
                if (isset($product['description']['short_desc'][0]) && is_array($product['description']['short_desc'])) {
                    foreach ($product['description']['short_desc'] as $key_short_desc => $short_desc) {
                        $product['short_desc_' . ($key_short_desc + 1)] = $short_desc;
                    }
                } elseif (isset($product['description']['short_desc'])) {
                    $product['short_desc'] = $product['description']['short_desc'];
                }
                unset($product['description']);
                if (isset($product['price']['@attributes']['gross'], $product['price']['@attributes']['net'])) {
                    $product['price_gross'] = $product['price']['@attributes']['gross'];
                    $product['price_net'] = $product['price']['@attributes']['net'];
                    unset($product['price']);
                }
                if (isset($product['srp']['@attributes']['gross'], $product['srp']['@attributes']['net'])) {
                    $product['srp_gross'] = $product['srp']['@attributes']['gross'];
                    $product['srp_net'] = $product['srp']['@attributes']['net'];
                    unset($product['srp']);
                }
                if (isset($product['strikethrough_retail_price']['@attributes']['gross'], $product['strikethrough_retail_price']['@attributes']['net'])) {
                    $product['strikethrough_retail_price_gross'] = $product['strikethrough_retail_price']['@attributes']['gross'];
                    $product['strikethrough_retail_price_net'] = $product['strikethrough_retail_price']['@attributes']['net'];
                    unset($product['strikethrough_retail_price']);
                }
                if (isset($product['strikethrough_wholesale_price']['@attributes']['gross'], $product['strikethrough_wholesale_price']['@attributes']['net'])) {
                    $product['strikethrough_wholesale_price_gross'] = $product['strikethrough_wholesale_price']['@attributes']['gross'];
                    $product['strikethrough_wholesale_price_net'] = $product['strikethrough_wholesale_price']['@attributes']['net'];
                    unset($product['strikethrough_wholesale_price']);
                }
                if (isset($product['parameters']['parameter']['@attributes'])) {
                    $product['parameters']['parameter'] = [$product['parameters']['parameter']];
                }
                if (isset($product['parameters']['parameter'][0]['@attributes']['name'])) {
                    $parameters = '';
                    foreach ($product['parameters']['parameter'] as $param) {
                        if (isset($param['@attributes']['name'], $param['value']['@attributes']['name'])) {
                            $parameters .= $parameters ? $multiple_value_separator : '';
                            $parameters .= $param['@attributes']['name'] . ':"' . $param['value']['@attributes']['name'] . '"';
                        }
                    }
                    $product['parameters'] = $parameters;
                }
                if (isset($product['attachments']['file']['@attributes'])) {
                    $product['attachments']['file'] = [$product['attachments']['file']];
                }
                if (isset($product['attachments']['file'][0]['@attributes'])) {
                    $attachment_urls = '';
                    $attachment_names = '';
                    foreach ($product['attachments']['file'] as $attachment_file) {
                        $attachment_urls .= $attachment_urls ? $multiple_value_separator : '';
                        $attachment_urls .= $attachment_file['@attributes']['url'];
                        $attachment_names .= $attachment_names ? $multiple_value_separator : '';
                        $attachment_names .= $attachment_file['name'];
                    }
                    $product['attachments'] = $attachment_urls;
                    $product['attachment_names'] = $attachment_names;
                }
                if (isset($product['sizes']['size']['@attributes'])) {
                    $product['sizes']['size'] = [$product['sizes']['size']];
                }
                if ($entity == 'product') {
                    if (isset($product['sizes']['size'][0]['@attributes']['id'])) {
                        $size = $product['sizes']['size'][0];
                        $product['size_id'] = isset($size['@attributes']['id']) ? $size['@attributes']['id'] : '';
                        $product['size_name'] = isset($size['@attributes']['name']) ? $size['@attributes']['name'] : '';
                        $product['size_panel_name'] = isset($size['@attributes']['panel_name']) ? $size['@attributes']['panel_name'] : '';
                        $product['size_code'] = isset($size['@attributes']['code']) ? $size['@attributes']['code'] : '';
                        $product['size_weight'] = isset($size['@attributes']['weight']) ? $size['@attributes']['weight'] : '';
                        $product['size_code_producer'] = isset($size['@attributes']['code_producer']) ? $size['@attributes']['code_producer'] : '';
                        $product['size_available'] = isset($size['@attributes']['available']) ? $size['@attributes']['available'] : '';
                        $product['size_price_gross'] = isset($size['price']['@attributes']['gross']) ? $size['price']['@attributes']['gross'] : '';
                        $product['size_price_net'] = isset($size['price']['@attributes']['net']) ? $size['price']['@attributes']['net'] : '';
                        $product['size_srp_gross'] = isset($size['srp']['@attributes']['gross']) ? $size['srp']['@attributes']['gross'] : '';
                        $product['size_srp_net'] = isset($size['srp']['@attributes']['net']) ? $size['srp']['@attributes']['net'] : '';
                        if (isset($size['strikethrough_retail_price']['@attributes']['gross'])) {
                            $product['size_strikethrough_retail_price_gross'] = $size['strikethrough_retail_price']['@attributes']['gross'];
                        }
                        if (isset($size['strikethrough_retail_price']['@attributes']['net'])) {
                            $product['size_strikethrough_retail_price_net'] = $size['strikethrough_retail_price']['@attributes']['net'];
                        }
                        if (isset($size['strikethrough_wholesale_price']['@attributes']['gross'])) {
                            $product['size_strikethrough_wholesale_price_gross'] = $size['strikethrough_wholesale_price']['@attributes']['gross'];
                        }
                        if (isset($size['strikethrough_wholesale_price']['@attributes']['net'])) {
                            $product['size_strikethrough_wholesale_price_net'] = $size['strikethrough_wholesale_price']['@attributes']['net'];
                        }
                        if (isset($size['stock']['@attributes'])) {
                            $product['size_stock_quantity'] = $size['stock']['@attributes']['quantity'];
                        } elseif (isset($size['stock'][1]['@attributes'])) {
                            $product['size_stock_quantity'] = $size['stock'][1]['@attributes']['quantity'];
                        } else {
                            $product['size_stock_quantity'] = '';
                        }
                    }
                    unset($product['sizes']);
                    $array[] = $product;
                } else {
                    if (isset($product['sizes']['size'][0]['@attributes']['id'])) {
                        foreach ($product['sizes']['size'] as $size) {
                            $tmp_product = $product;
                            $tmp_product['size_id'] = isset($size['@attributes']['id']) ? $size['@attributes']['id'] : '';
                            $tmp_product['size_name'] = isset($size['@attributes']['name']) ? $size['@attributes']['name'] : '';
                            $tmp_product['size_panel_name'] = isset($size['@attributes']['panel_name']) ? $size['@attributes']['panel_name'] : '';
                            $tmp_product['size_code'] = isset($size['@attributes']['code']) ? $size['@attributes']['code'] : '';
                            $tmp_product['size_weight'] = isset($size['@attributes']['weight']) ? $size['@attributes']['weight'] : '';
                            $tmp_product['size_code_producer'] = isset($size['@attributes']['code_producer']) ? $size['@attributes']['code_producer'] : '';
                            $tmp_product['size_available'] = isset($size['@attributes']['available']) ? $size['@attributes']['available'] : '';
                            $tmp_product['size_price_gross'] = isset($size['price']['@attributes']['gross']) ? $size['price']['@attributes']['gross'] : '';
                            $tmp_product['size_price_net'] = isset($size['price']['@attributes']['net']) ? $size['price']['@attributes']['net'] : '';
                            $tmp_product['size_srp_gross'] = isset($size['srp']['@attributes']['gross']) ? $size['srp']['@attributes']['gross'] : '';
                            $tmp_product['size_srp_net'] = isset($size['srp']['@attributes']['net']) ? $size['srp']['@attributes']['net'] : '';
                            if (isset($size['strikethrough_retail_price']['@attributes']['gross'])) {
                                $tmp_product['size_strikethrough_retail_price_gross'] = $size['strikethrough_retail_price']['@attributes']['gross'];
                            }
                            if (isset($size['strikethrough_retail_price']['@attributes']['net'])) {
                                $tmp_product['size_strikethrough_retail_price_net'] = $size['strikethrough_retail_price']['@attributes']['net'];
                            }
                            if (isset($size['strikethrough_wholesale_price']['@attributes']['gross'])) {
                                $tmp_product['size_strikethrough_wholesale_price_gross'] = $size['strikethrough_wholesale_price']['@attributes']['gross'];
                            }
                            if (isset($size['strikethrough_wholesale_price']['@attributes']['net'])) {
                                $tmp_product['size_strikethrough_wholesale_price_net'] = $size['strikethrough_wholesale_price']['@attributes']['net'];
                            }
                            if (isset($size['stock']['@attributes'])) {
                                $tmp_product['size_stock_quantity'] = $size['stock']['@attributes']['quantity'];
                            } elseif (isset($size['stock'][1]['@attributes'])) {
                                $tmp_product['size_stock_quantity'] = $size['stock'][1]['@attributes']['quantity'];
                            } else {
                                $tmp_product['size_stock_quantity'] = '';
                            }
                            unset($tmp_product['sizes']);
                            $array[] = $tmp_product;
                        }
                    } else {
                        unset($product['sizes']);
                        $array[] = $product;
                    }
                }
                unset($array[$key]);
            }
        } elseif (isset($array[0]['urun_id'], $array[0]['urun_kodu'], $array[0]['baslik'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['varyasyonlar']);
                } else {
                    if (isset($product['varyasyonlar']['varyasyon'])) {
                        if (isset($product['varyasyonlar']['varyasyon']['id'])) {
                            $product['varyasyonlar']['varyasyon'] = [$product['varyasyonlar']['varyasyon']];
                        }
                        foreach ($product['varyasyonlar']['varyasyon'] as $variant) {
                            $array[] = array_merge(['urun_kodu' => $product['urun_kodu']], $variant);
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['sizes']['size_1']['id'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['sizes']);
                } else {
                    if (isset($product['sizes']['size_1'])) {
                        foreach ($product['sizes'] as $size) {
                            $tmp_product = $product;
                            unset($tmp_product['sizes']);
                            if (isset($size['id'])) {
                                foreach ($size as $size_key => $size_value) {
                                    $tmp_product['size_' . $size_key] = $size_value;
                                }
                            }
                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['id'], $array[0]['variations']['variation']) && (isset($array[0]['variations']['variation'][0]['options']['option']) || isset($array[0]['variations']['variation']['options']['option']))) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['variations']);
                    if (isset($product['properties']['property']['name'], $product['properties']['property']['value'])) {
                        $product['properties']['property'] = [$product['properties']['property']];
                    }
                    if (isset($product['properties']['property'][0]['name'], $product['properties']['property'][0]['value'])) {
                        $properties = '';
                        foreach ($product['properties']['property'] as $property) {
                            $properties .= $properties ? $multiple_value_separator : '';
                            $properties .= $property['name'] . ':"' . $property['value'] . '"';
                        }
                        $array[$key]['properties'] = $properties;
                    }
                } else {
                    if (isset($product['variations']['variation']['id'])) {
                        $product['variations']['variation'] = [$product['variations']['variation']];
                    }
                    if (isset($product['variations']['variation'][0]['id'])) {
                        foreach ($product['variations']['variation'] as $variation) {
                            $tmp_product = $product;
                            unset($tmp_product['variations']);
                            unset($tmp_product['description']);
                            unset($tmp_product['images']);
                            unset($tmp_product['properties']);
                            foreach ($variation as $variation_key => $variation_value) {
                                if (is_array($variation_value)) {
                                    continue;
                                }
                                $tmp_product['variation_' . $variation_key] = $variation_value;
                            }
                            if (isset($variation['options']['option']['name'])) {
                                $variation['options']['option'] = [$variation['options']['option']];
                            }
                            if (isset($variation['options']['option'][0]['name'], $variation['options']['option'][0]['value'])) {
                                $variation_attr_names = '';
                                $variation_attr_values = '';
                                foreach ($variation['options']['option'] as $option) {
                                    $variation_attr_names .= $variation_attr_names ? $multiple_value_separator : '';
                                    $variation_attr_names .= $option['name'];
                                    $variation_attr_values .= $variation_attr_values ? $multiple_value_separator : '';
                                    $variation_attr_values .= $option['value'];
                                }
                                $tmp_product['variation_attribute_names'] = $variation_attr_names;
                                $tmp_product['variation_attribute_values'] = $variation_attr_values;
                            }
                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['sizes']['size'][0]['@attributes']['code'], $array[0]['sizes']['size'][0]['@attributes']['title'])) {
            if ($entity == 'combination') {
                foreach ($array as $key => $product) {
                    foreach ($product['sizes']['size'] as $size) {
                        $tmp_product = $product;
                        unset($tmp_product['sizes']);
                        $tmp_product['size'] = $size['@attributes']['title'];
                        if (isset($product['colors']['color']['@attributes']['title'])) {
                            unset($tmp_product['colors']);
                            $tmp_product['color'] = $product['colors']['color']['@attributes']['title'];
                        }
                        if (isset($product['standars']['standard']['@attributes']['title'])) {
                            unset($tmp_product['standars']);
                            $tmp_product['standard'] = $product['standars']['standard']['@attributes']['title'];
                        }
                        if (isset($product['standards']['standard']['@attributes']['title'])) {
                            unset($tmp_product['standards']);
                            $tmp_product['standard'] = $product['standards']['standard']['@attributes']['title'];
                        }
                        if (isset($product['season']['@attributes']['title'])) {
                            $tmp_product['season'] = $product['season']['@attributes']['title'];
                        }
                        if (isset($tmp_product['images']['image']['path'])) {
                            $tmp_product['images']['image'] = [$tmp_product['images']['image']];
                        }
                        if (isset($tmp_product['images']['image'][0]['path'])) {
                            $images = '';
                            foreach ($tmp_product['images']['image'] as $image) {
                                $images .= $images ? $multiple_value_separator : '';
                                $images .= $image['path'];
                            }
                            $tmp_product['images'] = $images;
                        }
                        $array[] = $tmp_product;
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['language']['lang'][0]['name']) || isset($array[0]['language']['lang'][0]['description']) || isset($array[0]['language']['lang'][0]['description_short'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    if (isset($product['language']['lang']) && is_array($product['language']['lang'])) {
                        foreach ($product['language']['lang'] as $key_lang => $lang) {
                            $lang_iso = (isset($lang['@attributes']['iso'])) ? $lang['@attributes']['iso'] : $key_lang + 1;
                            $array[$key]['name_' . $lang_iso] = (isset($lang['name'])) ? $lang['name'] : '';
                            $array[$key]['description_' . $lang_iso] = (isset($lang['description'])) ? $lang['description'] : '';
                            $array[$key]['description_short_' . $lang_iso] = (isset($lang['description_short'])) ? $lang['description_short'] : '';
                        }
                    }
                }
                unset($array[$key]['language']);
                if (isset($product['price']['tax']) || isset($product['price']['price']) || isset($product['price']['srp'])) {
                    $array[$key]['price_tax'] = (isset($product['price']['tax'])) ? $product['price']['tax'] : '';
                    $array[$key]['price_price'] = (isset($product['price']['price'])) ? $product['price']['price'] : '';
                    $array[$key]['price_srp'] = (isset($product['price']['srp'])) ? $product['price']['srp'] : '';
                    unset($array[$key]['price']);
                }
            }
        } elseif (isset($first_array['SHOPITEM'], $array[0]['CODE'], $array[0]['ITEM_TYPE']) && ($array[0]['ITEM_TYPE'] == 'Simple' || $array[0]['ITEM_TYPE'] == 'Variable')) {
            $Product_Codes = [];
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    if (isset($product['BASE_VARIANT_CODE'])) {
                        if (in_array($product['BASE_VARIANT_CODE'], $Product_Codes)) {
                            unset($array[$key]);
                        } else {
                            $Product_Codes[] = $product['BASE_VARIANT_CODE'];
                        }
                    } elseif (isset($product['CODE'])) {
                        $array[$key]['BASE_VARIANT_CODE'] = $product['CODE'];
                    } else {
                        unset($array[$key]);
                    }
                } else {
                    if (isset($product['ITEM_TYPE']) && $product['ITEM_TYPE'] != 'Variable') {
                        unset($array[$key]);
                    } else {
                        if (isset($product['PARAMETERS']['PARAMETER'])) {
                            if (isset($product['PARAMETERS']['PARAMETER']['NAME'])) {
                                $product['PARAMETERS']['PARAMETER'] = [$product['PARAMETERS']['PARAMETER']];
                            }
                            $attribute_names = '';
                            $attribute_values = '';
                            if (isset($product['PARAMETERS']['PARAMETER'][0]['NAME'], $product['PARAMETERS']['PARAMETER'][0]['VALUE'])) {
                                foreach ($product['PARAMETERS']['PARAMETER'] as $parameter) {
                                    $attribute_names .= $attribute_names ? $multiple_value_separator : '';
                                    $attribute_names .= $parameter['NAME'];
                                    $attribute_values .= $attribute_values ? $multiple_value_separator : '';
                                    $attribute_values .= $parameter['VALUE'];
                                }
                            }
                            $array[$key]['AttributeNames'] = $attribute_names;
                            $array[$key]['AttributeValues'] = $attribute_values;
                        }
                    }
                }
            }
        } elseif (isset($array[0]['stockCode'], $array[0]['variants'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['variants']);
                } else {
                    if (isset($product['variants']['variant']['vStockCode'])) {
                        $product['variants']['variant'] = [$product['variants']['variant']];
                    }
                    if (isset($product['variants']['variant']) && is_array($product['variants']['variant']) && $product['variants']['variant']) {
                        foreach ($product['variants']['variant'] as $variant) {
                            $tmp_product = $product;
                            if (is_array($variant)) {
                                foreach ($variant as $variant_key => $variant_value) {
                                    if (!is_array($variant_value)) {
                                        $tmp_product[$variant_key] = $variant_value;
                                    } else {
                                        if ($variant_key == 'options' && isset($variant_value['option']['variantName'], $variant_value['option']['variantValue'])) {
                                            $tmp_product['variantName'] = $variant_value['option']['variantName'];
                                            $tmp_product['variantValue'] = $variant_value['option']['variantValue'];
                                        } else {
                                            continue;
                                        }
                                    }
                                }
                            }
                            unset($tmp_product['variants']);
                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['@attributes']['product_id'],$array[0]['sku'], $array[0]['sizes']['size'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['sizes']);
                } else {
                    if (isset($product['sizes']['size']['@attributes'])) {
                        $product['sizes']['size'] = [$product['sizes']['size']];
                    }
                    if (isset($product['sizes']['size']) && is_array($product['sizes']['size']) && $product['sizes']['size']) {
                        foreach ($product['sizes']['size'] as $size) {
                            $tmp_product = $product;
                            if (isset($size['@attributes']) && is_array($size['@attributes'])) {
                                foreach ($size['@attributes'] as $size_key => $size_value) {
                                    if (is_array($size_value)) {
                                        continue;
                                    }
                                    $tmp_product['size_' . $size_key] = $size_value;
                                }
                            }
                            unset($tmp_product['sizes']);
                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['ProductType']) && in_array($array[0]['ProductType'], ['Standard', 'Multivariation', 'Variation'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    if (!in_array($product['ProductType'], ['Standard', 'Multivariation'])) {
                        unset($array[$key]);
                    }
                } elseif ($entity == 'combination') {
                    if ($product['ProductType'] != 'Variation') {
                        unset($array[$key]);
                    }
                }
            }
        } elseif (isset($array[0]['VARIANTS']['VARIANT']['TITLE']) || isset($array[0]['VARIANTS']['VARIANT'][0]['TITLE'])) {
            foreach ($array as $key => $product) {
                if (!isset($product['VARIANTS'])) {
                    continue;
                }
                if (isset($product['VARIANTS']['VARIANT']['TITLE'])) {
                    $product['VARIANTS']['VARIANT'] = [$product['VARIANTS']['VARIANT']];
                }
                foreach ($product['VARIANTS']['VARIANT'] as $variant) {
                    $tmp_product = $product;
                    if (isset($variant['IMAGES']['IMAGE'][0]['URL']) || isset($variant['IMAGES']['IMAGE']['URL'])) {
                        if (isset($variant['IMAGES']['IMAGE']['URL'])) {
                            $variant['IMAGES']['IMAGE'] = [$variant['IMAGES']['IMAGE']];
                        }
                        $variant_images = '';
                        foreach ($variant['IMAGES']['IMAGE'] as $variant_image) {
                            $variant_images .= $variant_images ? $multiple_value_separator : '';
                            $variant_images .= $variant_image['URL'];
                        }
                        $variant['IMAGES'] = $variant_images;
                    }
                    if (isset($variant['PRICE']['@attributes'])) {
                        unset($variant['PRICE']['@attributes']);
                    }
                    if (isset($variant['PRICE_VAT']['@attributes'])) {
                        unset($variant['PRICE_VAT']['@attributes']);
                    }
                    if (isset($variant['VAT']['@attributes'])) {
                        unset($variant['VAT']['@attributes']);
                    }
                    foreach ($variant as $variant_key => $variant_value) {
                        if ($variant_key == 'SERIES' && is_array($variant_value)) {
                            foreach ($variant_value as $series_key => $series_value) {
                                $tmp_product['VARIANT_SERIES_' . $series_key] = is_array($series_value) ? reset($series_value) : $series_value;
                            }
                        } elseif ($variant_key == 'DIMENSIONS' && is_array($variant_value)) {
                            if (isset($variant_value['PACKAGE']) && is_array($variant_value['PACKAGE'])) {
                                foreach ($variant_value['PACKAGE'] as $package_key => $package_value) {
                                    $tmp_product['VARIANT_DIMENSIONS_PACKAGE_' . $package_key] = $package_value;
                                }
                            }
                            if (isset($variant_value['PRODUCT']) && is_array($variant_value['PRODUCT'])) {
                                foreach ($variant_value['PRODUCT'] as $prod_key => $prod_value) {
                                    $tmp_product['VARIANT_DIMENSIONS_PRODUCT_' . $prod_key] = $prod_value;
                                }
                            }
                        } elseif ($variant_key == 'PARAMS' && is_array($variant_value)) {
                            foreach ($variant_value as $params_key => $params_value) {
                                $tmp_product['VARIANT_PARAMS_' . $params_key] = $params_value;
                            }
                        } else {
                            $tmp_product['VARIANT_' . $variant_key] = $variant_value;
                        }
                    }
                    unset($tmp_product['VARIANTS']);
                    $array[] = $tmp_product;

                    if ($entity == 'product') {
                        break;
                    }
                }
                unset($array[$key]);
            }
        } elseif (isset($array[0]['properties']['property'], $array[0]['colours']['colour']['modifications'])) {
            foreach ($array as $key => $product) {
                if (isset($product['properties']['property']['id'], $product['properties']['property']['values']['value']) && $product['properties']['property']['id'] == 'manufacturer' && $product['properties']['property']['values']['value']) {
                    $array[$key]['manufacturer'] = $product['properties']['property']['values']['value'];
                    unset($array[$key]['properties']);
                }
                if (isset($product['colours']['colour'])) {
                    if (isset($product['colours']['colour']['images']['image'])) {
                        $array[$key]['images'] = $product['colours']['colour']['images']['image'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['weight'])) {
                        $array[$key]['weight'] = $product['colours']['colour']['modifications']['modification']['weight'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['length'])) {
                        $array[$key]['length'] = $product['colours']['colour']['modifications']['modification']['length'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['height'])) {
                        $array[$key]['height'] = $product['colours']['colour']['modifications']['modification']['height'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['attributes']['barcodes']['barcode'])) {
                        $array[$key]['barcode'] = $product['colours']['colour']['modifications']['modification']['attributes']['barcodes']['barcode'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['attributes']['supplier-code'])) {
                        $array[$key]['supplier-code'] = $product['colours']['colour']['modifications']['modification']['attributes']['supplier-code'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['prices']['base-price']['value'])) {
                        $array[$key]['base-price'] = $product['colours']['colour']['modifications']['modification']['prices']['base-price']['value'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['prices']['base-price']['currency'])) {
                        $array[$key]['base-price-currency'] = $product['colours']['colour']['modifications']['modification']['prices']['base-price']['currency'];
                    }
                    if (isset($product['colours']['colour']['modifications']['modification']['quantity'])) {
                        $array[$key]['quantity'] = $product['colours']['colour']['modifications']['modification']['quantity'];
                    }
                    unset($array[$key]['colours']);
                }
            }
        } elseif (isset($array[0]['nuovo'], $array[0]['codiceOD'], $array[0]['partNumber'], $array[0]['classificazione'])) {
            foreach ($array as $key => $product) {
                if (isset($product['classificazione']['catalogo']['categoria']['descrizione'], $product['classificazione']['catalogo']['sottocategoria']['descrizione'])) {
                    $array[$key]['classificazione'] = $product['classificazione']['catalogo']['categoria']['descrizione'] . $multiple_value_separator . $product['classificazione']['catalogo']['sottocategoria']['descrizione'];
                }
                if (isset($product['packaging']) && is_array($product['packaging'])) {
                    if (isset($product['packaging']['pezzo']) && is_array($product['packaging']['pezzo'])) {
                        foreach ($product['packaging']['pezzo'] as $pezzo_key => $pezzo_value) {
                            $array[$key]['pezzo_' . $pezzo_key] = $pezzo_value;
                        }
                    }
                    unset($array[$key]['packaging']);
                }
                if (isset($product['disponibilita']['giacenzaDisponibile'])) {
                    $array[$key]['disponibilita'] = $product['disponibilita']['giacenzaDisponibile'];
                }
                if (isset($product['iva']['descrizione'])) {
                    $array[$key]['iva'] = $product['iva']['descrizione'];
                }
                if (isset($product['prodottoProprieta'])) {
                    if (isset($product['prodottoProprieta']['prodottoCaratteristiche']['prodottoCaratteristica'])) {
                        if (isset($product['prodottoProprieta']['prodottoCaratteristiche']['prodottoCaratteristica']['caratteristicaChiave'])) {
                            $product['prodottoProprieta']['prodottoCaratteristiche']['prodottoCaratteristica'] = [$product['prodottoProprieta']['prodottoCaratteristiche']['prodottoCaratteristica']];
                        }
                        $prodottoCaratteristiche = '';
                        foreach ($product['prodottoProprieta']['prodottoCaratteristiche']['prodottoCaratteristica'] as $prodottoCaratteristica) {
                            if (isset($prodottoCaratteristica['caratteristicaChiave']) && $prodottoCaratteristica['caratteristicaChiave'] && isset($prodottoCaratteristica['caratteristicaValore'])) {
                                $prodottoCaratteristiche .= $prodottoCaratteristiche ? $multiple_value_separator : '';
                                $prodottoCaratteristiche .= $prodottoCaratteristica['caratteristicaChiave'] . ':"' . $prodottoCaratteristica['caratteristicaValore'] . '"';
                            }
                        }
                        $array[$key]['prodottoCaratteristiche'] = $prodottoCaratteristiche;
                    }
                    if (isset($product['prodottoProprieta']['prodottoTags']['tags'])) {
                        $array[$key]['prodottoTags'] = $product['prodottoProprieta']['prodottoTags']['tags'];
                    }
                    unset($array[$key]['prodottoProprieta']);
                }
                if (isset($product['prodottoMediaLink']['prodottoMedia'])) {
                    if (isset($product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine'])) {
                        if (isset($product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine']['immagineLink'])) {
                            $product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine'] = [$product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine']];
                        }
                        $immagini = '';
                        foreach ($product['prodottoMediaLink']['prodottoMedia']['immagini']['immagine'] as $immagine) {
                            if (isset($immagine['immagineLink']) && $immagine['immagineLink']) {
                                $immagini .= $immagini ? $multiple_value_separator : '';
                                $immagini .= $immagine['immagineLink'];
                            }
                        }
                        $array[$key]['immagini'] = $immagini;
                    }
                    if (isset($product['prodottoMediaLink']['prodottoMedia']['prodottoDocumentazione']['documentazione'])) {
                        if (isset($product['prodottoMediaLink']['prodottoMedia']['prodottoDocumentazione']['documentazione']['documentoLink'])) {
                            $product['prodottoMediaLink']['prodottoMedia']['prodottoDocumentazione']['documentazione'] = [$product['prodottoMediaLink']['prodottoMedia']['prodottoDocumentazione']['documentazione']];
                        }
                        $prodottoDocumentazione = '';
                        $prodottoDocumentazioneTipo = '';
                        foreach ($product['prodottoMediaLink']['prodottoMedia']['prodottoDocumentazione']['documentazione'] as $documentazione) {
                            if (isset($documentazione['documentoLink']) && $documentazione['documentoLink']) {
                                $prodottoDocumentazione .= $prodottoDocumentazione ? $multiple_value_separator : '';
                                $prodottoDocumentazione .= $documentazione['documentoLink'];
                            }
                            if (isset($documentazione['documentoTipo']) && $documentazione['documentoTipo']) {
                                $prodottoDocumentazioneTipo .= $prodottoDocumentazioneTipo ? $multiple_value_separator : '';
                                $prodottoDocumentazioneTipo .= $documentazione['documentoTipo'];
                            }
                        }
                        $array[$key]['prodottoDocumentazione'] = $prodottoDocumentazione;
                        $array[$key]['prodottoDocumentazioneTipo'] = $prodottoDocumentazioneTipo;
                    }
                    unset($array[$key]['prodottoMediaLink']);
                }
            }
        } elseif (isset($array[0]['PRICES']['PRICE']['PRICELISTS']['PRICELIST'])) {
            foreach ($array as $key => $product) {
                if (isset($product['DESCRIPTIONS']['DESCRIPTION']['TITLE'])) {
                    foreach ($product['DESCRIPTIONS']['DESCRIPTION'] as $desc_key => $desc_value) {
                        if (!is_array($desc_value)) {
                            $array[$key][$desc_key] = $desc_value;
                        }
                    }
                    unset($array[$key]['DESCRIPTIONS']);
                }
                if (isset($product['CATEGORIES']['CATEGORY']['NAME'])) {
                    $product['CATEGORIES']['CATEGORY'] = [$product['CATEGORIES']['CATEGORY']];
                }
                if (isset($product['CATEGORIES']['CATEGORY'][0]['NAME'])) {
                    $categories = '';
                    foreach ($product['CATEGORIES']['CATEGORY'] as $category) {
                        if (isset($category['NAME']) && $category['NAME'] && !is_array($category['NAME'])) {
                            $categories .= $categories ? $multiple_value_separator : '';
                            $categories .= $category['NAME'];
                        }
                    }
                    $array[$key]['CATEGORIES'] = $categories;
                }
                if (isset($product['PRICES']['PRICE']['PRICELISTS']['PRICELIST']['NAME'])) {
                    $product['PRICES']['PRICE']['PRICELISTS']['PRICELIST'] = [$product['PRICES']['PRICE']['PRICELISTS']['PRICELIST']];
                }
                if (isset($product['PRICES']['PRICE']['PRICELISTS']['PRICELIST']) && is_array($product['PRICES']['PRICE']['PRICELISTS']['PRICELIST'])) {
                    $zakaznik_price_w_vat = '';
                    $zakaznik_price_wo_vat = '';
                    $partner_price_w_vat = '';
                    $partner_price_wo_vat = '';
                    foreach ($product['PRICES']['PRICE']['PRICELISTS']['PRICELIST'] as $price_list) {
                        if (isset($price_list['NAME'], $price_list['PRICE_WITH_VAT'], $price_list['PRICE_WITHOUT_VAT'])) {
                            // Get first price in case Zakaznik or Partner is missing
                            if (!$zakaznik_price_w_vat || !$zakaznik_price_wo_vat) {
                                $zakaznik_price_w_vat = $price_list['PRICE_WITH_VAT'];
                                $zakaznik_price_wo_vat = $price_list['PRICE_WITHOUT_VAT'];
                            }
                            if (!$partner_price_w_vat || !$partner_price_wo_vat) {
                                $partner_price_w_vat = $price_list['PRICE_WITH_VAT'];
                                $partner_price_wo_vat = $price_list['PRICE_WITHOUT_VAT'];
                            }
                            if ($price_list['NAME'] == 'Prodejní') {
                                $zakaznik_price_w_vat = $price_list['PRICE_WITH_VAT'];
                                $zakaznik_price_wo_vat = $price_list['PRICE_WITHOUT_VAT'];
                            } elseif ($price_list['NAME'] == 'Partner') {
                                $partner_price_w_vat = $price_list['PRICE_WITH_VAT'];
                                $partner_price_wo_vat = $price_list['PRICE_WITHOUT_VAT'];
                            }
                        }
                    }
                    $array[$key]['PRICELIST_Zakaznik_PRICE_WITH_VAT'] = $zakaznik_price_w_vat;
                    $array[$key]['PRICELIST_Zakaznik_PRICE_WITHOUT_VAT'] = $zakaznik_price_wo_vat;
                    $array[$key]['PRICELIST_Partner_PRICE_WITH_VAT'] = $partner_price_w_vat;
                    $array[$key]['PRICELIST_Partner_PRICE_WITHOUT_VAT'] = $partner_price_wo_vat;
                    unset($array[$key]['PRICES']);
                }
                if (isset($product['VATS']['VAT'])) {
                    unset($array[$key]['VATS']);
                }
            }
        } elseif (isset($array[0]['prices']['retail_price']['value'], $array[0]['prices']['sell_price']['value'])) {
            foreach ($array as $key => $product) {
                if (isset($product['prices']['retail_price']['value'], $product['prices']['sell_price']['value'])) {
                    $array[$key]['retail_price_value'] = $product['prices']['retail_price']['value'];
                    $array[$key]['sell_price_value'] = $product['prices']['sell_price']['value'];
                    if (isset($product['prices']['retail_price']['currency'])) {
                        $array[$key]['prices_currency'] = $product['prices']['retail_price']['currency'];
                    }
                    unset($array[$key]['prices']);
                }
                if (isset($product['attributes']['barcode'], $product['attributes']['supplier_code'], $product['attributes']['manufacturer_code'])) {
                    $array[$key]['attributes_barcode'] = $product['attributes']['barcode'];
                    $array[$key]['attributes_supplier_code'] = $product['attributes']['supplier_code'];
                    $array[$key]['attributes_manufacturer_code'] = $product['attributes']['manufacturer_code'];
                    unset($array[$key]['attributes']);
                }
            }
        } elseif (isset($array[0]['@attributes']['id'], $array[0]['options'], $array[0]['name'], $array[0]['category_path'], $array[0]['category'], $array[0]['price'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'product') {
                    unset($array[$key]['options']);
                } else {
                    if (isset($product['description'])) {
                        unset($product['description']);
                    }
                    if (isset($product['options']['option']['option_name'])) {
                        $product['options']['option'] = [$product['options']['option']];
                    }
                    if (isset($product['options']['option'][0]['option_name'])) {
                        foreach ($product['options']['option'] as $option) {
                            $tmp_product = $product;
                            $tmp_product['option_id'] = isset($option['@attributes']['id']) ? $option['@attributes']['id'] : '';
                            foreach ($option as $option_key => $option_value) {
                                if ($option_key == '@attributes') {
                                    continue;
                                }
                                $tmp_product['option_' . $option_key] = $option_value;
                            }
                            unset($tmp_product['options']);
                            $array[] = $tmp_product;
                        }
                    }
                    unset($array[$key]);
                }
            }
        } elseif (isset($array[0]['@attributes']['code'], $array[0]['datafields']['data'])) {
            foreach ($array as $key => $product) {
                if (isset($product['datafields']['data'][0]['@attributes']['code'])) {
                    foreach ($product['datafields']['data'] as $data) {
                        if (isset($data['@attributes']['code'], $data['@attributes']['content'])) {
                            $param_lang = isset($data['@attributes']['param']) ? '_' . $data['@attributes']['param'] : '';
                            $array[$key][$data['@attributes']['code'] . $param_lang] = $data['@attributes']['content'];
                        }
                    }
                }
                unset($array[$key]['datafields']);
            }
        } elseif (isset($array[0]['variants']['variant']['matnr']) || isset($array[0]['variants']['variant'][0]['matnr'])) {
            foreach ($array as $key => $product) {
                if ($entity == 'combination') {
                    if (isset($product['variants']['variant']['matnr'])) {
                        $product['variants']['variant'] = [$product['variants']['variant']];
                    }
                    if (isset($product['variants']['variant'][0]['matnr'])) {
                        foreach ($product['variants']['variant'] as $variant) {
                            if ($variant && is_array($variant)) {
                                $tmp_product = $product;
                                foreach ($variant as $variant_key => $variant_value) {
                                    if (!is_array($variant_value)) {
                                        $tmp_product['variant_' . $variant_key] = $variant_value;
                                    }
                                }
                                unset($tmp_product['variants']);
                                $array[] = $tmp_product;
                            }
                        }
                    }
                    unset($array[$key]);
                } else {
                    unset($array[$key]['variants']);
                }
            }
        }

        unset($first_array);

        // Open file pointer
        $handle = fopen($file, 'w');

        // Step 6
        // Write CSV header
        // Build header from all rows, because some rows may have columns that does not exist on other rows.
        $header = [];
        foreach ($array as $product) {
            if (!is_array($product)) {
                continue;
            }
            if (isset($product['@attributes'])) {
                // Add attributes as columns
                $attributes = $product['@attributes'];
                unset($product['@attributes']);
                $product = array_merge($attributes, $product);
            }
            foreach ($product as $attr => $value) {
                if (!in_array($attr, $header)) {
                    $header[] = $attr;
                }
            }
        }
        fputcsv($handle, $header, ';', '"');

        // Step 7
        // Write each row to csv
        foreach ($array as $product) {
            if (!is_array($product)) {
                continue;
            }
            if (isset($product['@attributes'])) {
                // Add attributes as columns
                $attributes = $product['@attributes'];
                unset($product['@attributes']);
                $product = array_merge($attributes, $product);
            }
            // Remove unwanted data
            if (isset($product['category']['@attributes']['id'])) {
                unset($product['category']['@attributes']['id']);
            }
            if (isset($product['category_idosell']['@attributes']['id'])) {
                unset($product['category_idosell']['@attributes']['id']);
            }
            if (isset($product['producer']['@attributes']['id'])) {
                unset($product['producer']['@attributes']['id']);
            }
            if (isset($product['unit']['@attributes']['id'])) {
                unset($product['unit']['@attributes']['id']);
            }
            if (isset($product['Manufacturer']['Id'])) {
                unset($product['Manufacturer']['Id']);
            }
            if (isset($product['Tax']['Id'])) {
                unset($product['Tax']['Id']);
            }
            if (isset($product['warranty']['@attributes']['id'])) {
                unset($product['warranty']['@attributes']['id']);
            }
            if (isset($product['warranty']['@attributes']['type'])) {
                unset($product['warranty']['@attributes']['type']);
            }
            if (isset($product['warranty']['@attributes']['period'])) {
                unset($product['warranty']['@attributes']['period']);
            }
            if (isset($product['SellingPrices']['SellingPrice']['Price']) && $product['SellingPrices']['SellingPrice']['Price']) {
                $product['SellingPrices'] = $product['SellingPrices']['SellingPrice']['Price'];
            }
            if (isset($product['ProdCategories']['ProdCategory']['Id'])) {
                unset($product['ProdCategories']['ProdCategory']['Id']);
            }
            if (isset($product['ProdCategories']['ProdCategory']['Code'])) {
                unset($product['ProdCategories']['ProdCategory']['Code']);
            }
            if (isset($product['ProdCategories']['ProdCategory']['FullPathName']) && $product['ProdCategories']['ProdCategory']['FullPathName']) {
                $product['ProdCategories'] = explode(' / ', $product['ProdCategories']['ProdCategory']['FullPathName']);
                if (isset($product['MainProdCategory'])) {
                    $product['MainProdCategory'] = $product['ProdCategories'];
                }
            }
            if (isset($product['ProdCategories']['ProdCategory'][0]['FullPathName']) && $product['ProdCategories']['ProdCategory'][0]['FullPathName']) {
                if (isset($product['MainProdCategory']) && $product['MainProdCategory']) {
                    $MainProdCategory = $product['MainProdCategory'];
                    // default value
                    $product['MainProdCategory'] = explode(' / ', $product['ProdCategories']['ProdCategory'][0]['FullPathName']);
                    // find default category by id
                    foreach ($product['ProdCategories']['ProdCategory'] as $key => $ProdCategory) {
                        if ($ProdCategory['Id'] == $MainProdCategory) {
                            $product['MainProdCategory'] = explode(' / ', $ProdCategory['FullPathName']);
                            unset($product['ProdCategories']['ProdCategory'][$key]);
                            break;
                        }
                    }
                }
                $product['ProdCategories']['ProdCategory'] = reset($product['ProdCategories']['ProdCategory']);
                $product['ProdCategories'] = explode(' / ', $product['ProdCategories']['ProdCategory']['FullPathName']);
            }
            if (isset($product['Photos']['Photo']) && $product['Photos']['Photo'] && is_array($product['Photos']['Photo'])) {
                if (isset($product['Photos']['Photo'][0]) && is_array($product['Photos']['Photo'][0])) {
                    foreach ($product['Photos']['Photo'] as &$photo) {
                        if (isset($photo['RelativeFilePath'])) {
                            $photo = $photo['RelativeFilePath'];
                        }
                    }
                } elseif (isset($product['Photos']['Photo']['RelativeFilePath'])) {
                    $product['Photos']['Photo'] = $product['Photos']['Photo']['RelativeFilePath'];
                }
            }
            if (isset($product['ITEMGROUP_ID']) || isset($product['ITEM_GROUP_ID'])) {
                if (isset($product['PRODUCTNAME'], $product['VARIANT']) && $product['VARIANT']) {
                    $product['PRODUCTNAME'] .= ' - ' . $product['VARIANT'];
                }
            }
            if (isset($product['PARAM']) && $product['PARAM'] && is_array($product['PARAM'])) {
                if (isset($product['PARAM']['PARAM_NAME'], $product['PARAM']['VAL'])) {
                    $product['PARAM'] = [$product['PARAM']];
                }
                if (isset($product['PARAM'][0]['PARAM_NAME'], $product['PARAM'][0]['VAL'])) {
                    $params = '';
                    foreach ($product['PARAM'] as $param) {
                        if (isset($param['PARAM_NAME']) && $param['PARAM_NAME'] && isset($param['VAL']) && (!empty($param['VAL']) || $param['VAL'] === '0')) {
                            $params .= $params ? $multiple_value_separator : '';
                            if (is_array($param['VAL']) && $param['VAL']) {
                                $param['VAL'] = implode($multiple_value_separator, $param['VAL']);
                            }
                            $params .= str_replace(':', '∶', trim($param['PARAM_NAME'])) . ':"' . trim($param['VAL']) . (isset($param['UNIT']) && $param['VAL'] ? ' ' . trim($param['UNIT']) : '') . '"';
                        }
                    }
                    $product['PARAM'] = $params;
                }
                if (isset($product['PARAM']['PARAM_NAME'], $product['PARAM']['PARAM_VAL'])) {
                    $product['PARAM'] = [$product['PARAM']];
                }
                if (isset($product['PARAM'][0]['PARAM_NAME'], $product['PARAM'][0]['PARAM_VAL'])) {
                    $params = '';
                    foreach ($product['PARAM'] as $param) {
                        if (isset($param['PARAM_NAME']) && $param['PARAM_NAME'] && isset($param['PARAM_VAL'])) {
                            $params .= $params ? $multiple_value_separator : '';
                            $params .= str_replace(':', '∶', trim($param['PARAM_NAME'])) . ':' . str_replace(':', '∶', trim($param['PARAM_VAL']) . (isset($param['PARAM_UNIT']) ? ' ' . trim($param['PARAM_UNIT']) : ''));
                        }
                    }
                    $product['PARAM'] = $params;
                }
                if (isset($product['PARAM']['NAME'], $product['PARAM']['VALUE'])) {
                    $product['PARAM'] = [$product['PARAM']];
                }
                if (isset($product['PARAM'][0]['NAME'], $product['PARAM'][0]['VALUE'])) {
                    $params = '';
                    foreach ($product['PARAM'] as $param) {
                        if (isset($param['NAME']) && $param['NAME'] && isset($param['VALUE'])) {
                            $params .= $params ? $multiple_value_separator : '';
                            $params .= str_replace(':', '∶', trim($param['NAME'])) . ':' . str_replace(':', '∶', trim($param['VALUE']) . (isset($param['PARAM_UNIT']) ? ' ' . trim($param['PARAM_UNIT']) : ''));
                        }
                    }
                    $product['PARAM'] = $params;
                }
            }
            if (isset($product['Categoria'], $product['SubCat1'], $product['SubCat2'])) {
                if ($product['SubCat1']) {
                    $product['Categoria'] .= ',' . $product['SubCat1'];
                }
                if ($product['SubCat2']) {
                    $product['Categoria'] .= ',' . $product['SubCat2'];
                }
            }
            if (isset($product['IMGURL'], $product['IMGURL_ALTERNATIVE']) && $product['IMGURL_ALTERNATIVE']) {
                if (is_array($product['IMGURL_ALTERNATIVE'])) {
                    $product['IMGURL'] = array_merge([$product['IMGURL']], $product['IMGURL_ALTERNATIVE']);
                } else {
                    $product['IMGURL'] = [$product['IMGURL'], $product['IMGURL_ALTERNATIVE']];
                }
            }
            if (isset($product['stock']['inStockLocal'], $product['stock']['inStockCentral'])) {
                $product['stock'] = $product['stock']['inStockLocal'] ? $product['stock']['inStockLocal'] : $product['stock']['inStockCentral'];
            }
            if (isset($product['priceLevels']['normalPricing']['price'])) {
                $product['priceLevels'] = $product['priceLevels']['normalPricing']['price'];
            }
            if (isset($product['description']) && is_array($product['description']) && isset($product['description']['name'][0]) && is_array($product['description']['name']) && isset($product['description']['long_desc'][0])) {
                $product['description'] = $product['description']['long_desc'][0];
            }
            if (isset($product['price']) && is_array($product['price']) && isset($product['price']['@attributes']['gross'], $product['price']['@attributes']['net'], $product['price']['@attributes']['vat'])) {
                $product['price'] = $product['price']['@attributes']['vat'];
            }
            if (isset($product['images']['icons']['icon'])) {
                unset($product['images']['icons']);
            }
            if (isset($product['images']['icons']['icon'])) {
                unset($product['images']['icons']);
            }
            if (isset($product['images']['large']['image']['@attributes']['url'])) {
                $product['images'] = $product['images']['large']['image']['@attributes']['url'];
            }
            if (isset($product['images']['large']['image'][0]['@attributes']['url'])) {
                $images = '';
                foreach ($product['images']['large']['image'] as $image) {
                    $images .= $images ? $multiple_value_separator : '';
                    $images .= $image['@attributes']['url'];
                }
                $product['images'] = $images;
            }
            if (isset($product['Tax'], $product['Tax']['Code'], $product['Tax']['PercentAmount'])) {
                $product['Tax'] = $product['Tax']['Code'];
            }
            if (isset($product['arrivi']['arrivo']['qta'])) {
                $product['arrivi'] = $product['arrivi']['arrivo']['qta'];
            }
            if (isset($product['arrivi']['arrivo'][0]['qta'])) {
                $product['arrivi'] = $product['arrivi']['arrivo'][0]['qta'];
            }
            if (isset($product['Pctrs']['@attributes'])) {
                unset($product['Pctrs']['@attributes']);
            }
            if (isset($product['AttrSet']['@attributes'])) {
                unset($product['AttrSet']['@attributes']);
            }
            if (isset($product['AttrSet']['ItmAttr']) && is_array($product['AttrSet']['ItmAttr']) && $product['AttrSet']['ItmAttr']) {
                $AttrSet = '';
                foreach ($product['AttrSet']['ItmAttr'] as $key => $ItmAttr) {
                    if (isset($ItmAttr['@attributes']['No'], $ItmAttr['@attributes']['Desc'])) {
                        $AttrSet .= $AttrSet ? $multiple_value_separator : '';
                        $AttrSet .= $ItmAttr['@attributes']['Desc'] . ':' . $ItmAttr['@attributes']['No'] . ':' . ($key + 1) . ':0';
                    }
                }
                $product['AttrSet'] = $AttrSet;
            }
            if (isset($product['Cats']['Cat'][0]['Sub']) && is_array($product['Cats']['Cat'][0]['Sub'])) {
                foreach ($product['Cats']['Cat'] as $key => $cat) {
                    if (is_array($cat['Sub']) && $cat['Sub']) {
                        $subCats = '';
                        foreach ($cat['Sub'] as $sub) {
                            $subCats .= $subCats ? '/' : '';
                            $subCats .= $sub;
                        }
                        $product['Cats']['Cat'][$key] = $subCats;
                    }
                }
            }
            if (isset($product['ProductCode'], $product['AttrList']) && is_array($product['AttrList']) && isset($product['AttrList']['element']) && is_array($product['AttrList']['element'])) {
                $AttrListFeatures = '';
                if (isset($product['AttrList']['element']['@attributes'], $product['AttrList']['element']['@attributes']['Name']) && $product['AttrList']['element']['@attributes']['Name'] && isset($product['AttrList']['element']['@attributes']['Value']) && $product['AttrList']['element']['@attributes']['Value']) {
                    $AttrListFeatures = $product['AttrList']['element']['@attributes']['Name'] . ':' . Tools::substr(strip_tags($product['AttrList']['element']['@attributes']['Value']), 0, 255);
                } else {
                    foreach ($product['AttrList']['element'] as $AttrListElement) {
                        if (isset($AttrListElement['@attributes']) && is_array($AttrListElement['@attributes']) && isset($AttrListElement['@attributes']['Name']) && $AttrListElement['@attributes']['Name'] && isset($AttrListElement['@attributes']['Value']) && $AttrListElement['@attributes']['Value']) {
                            $AttrListFeatures .= $AttrListFeatures ? $multiple_value_separator : '';
                            $AttrListFeatures .= $AttrListElement['@attributes']['Name'] . ':' . Tools::substr(strip_tags($AttrListElement['@attributes']['Value']), 0, 255);
                        }
                    }
                }
                $product['AttrList'] = $AttrListFeatures;
            }
            if ($entity == 'product' && isset($product['attributes']['attribute']) && is_array($product['attributes']['attribute'])) {
                $product_attributes_final = '';
                if (isset($product['attributes']['attribute']['@attributes']) && is_array($product['attributes']['attribute']['@attributes'])) {
                    $product['attributes']['attribute'] = [$product['attributes']['attribute']];
                }
                foreach ($product['attributes']['attribute'] as $product_attributes) {
                    if (isset($product_attributes['attributetitle'], $product_attributes['attributevalue'])) {
                        $product_attributes_final .= $product_attributes_final ? $multiple_value_separator : '';
                        $product_attributes_final .= $product_attributes['attributetitle'] . ':"' . $product_attributes['attributevalue'] . '"';
                    } elseif (isset($product_attributes['@attributes']['name'], $product_attributes['values']['value'])) {
                        $product_attributes_final .= $product_attributes_final ? $multiple_value_separator : '';
                        $product_attributes_final .= $product_attributes['@attributes']['name'] . ':"' . (is_array($product_attributes['values']['value']) ? implode('/', $product_attributes['values']['value']) : $product_attributes['values']['value']) . '"';
                    }if (isset($product_attributes['attribute_name'], $product_attributes['attribute_value'])) {
                        $product_attributes_final .= $product_attributes_final ? $multiple_value_separator : '';
                        $product_attributes_final .= $product_attributes['attribute_name'] . ':"' . $product_attributes['attribute_value'] . '"';
                    }
                }
                $product['attributes'] = $product_attributes_final;
            }
            if ($entity == 'product' && isset($product['prices']['price']) && is_array($product['prices']['price']) && isset($product['prices']['price'][0]['price_sell'])) {
                $product['prices'] = $product['prices']['price'][0]['price_sell'];
            }
            if (isset($product['artnum'], $product['attributes']) && is_array($product['attributes']) && isset($product['attributes']['attribute']) && is_array($product['attributes']['attribute'])) {
                if (isset($product['attributes']['attribute'][0]['attributetitle'], $product['attributes']['attribute'][0]['attributevalue'])) {
                    $features = '';
                    foreach ($product['attributes']['attribute'] as $feature) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $feature['attributetitle'] . ':' . $feature['attributevalue'];
                    }
                    $product['attributes'] = $features;
                } elseif (isset($product['attributes']['attribute']['attributetitle'], $product['attributes']['attribute']['attributevalue'])) {
                    $product['attributes'] = $product['attributes']['attribute']['attributetitle'] . ':' . $product['attributes']['attribute']['attributevalue'];
                }
            }
            if (isset($product['IdProdus']) && $product['IdProdus'] && isset($product['Stoc'], $product['StocFurnizor'])) {
                if ($product['StocFurnizor'] > $product['Stoc']) {
                    $product['Stoc'] = $product['StocFurnizor'];
                }
            }
            if (isset($product['TECHNICAL_ATTACHMENT']['ITEM'][0]['URL'])) {
                $tech_attachment = '';
                foreach ($product['TECHNICAL_ATTACHMENT']['ITEM'] as $tech_attach_item) {
                    $tech_attachment .= $tech_attachment ? $multiple_value_separator : '';
                    $tech_attachment .= $tech_attach_item['URL'];
                }
                $product['TECHNICAL_ATTACHMENT'] = trim($tech_attachment);
            }
            if (isset($product['id'], $product['group']['category']['name'])) {
                $group_category = $product['group']['category']['name'];
                if (isset($product['group']['category']['subcategory']['name'])) {
                    $group_category .= $multiple_value_separator . $product['group']['category']['subcategory']['name'];
                }
                $product['group'] = $group_category;
            }
            if (isset($product['id'], $product['filters']['filter'])) {
                $features = '';
                if (isset($product['filters']['filter'][0]['name'], $product['filters']['filter'][0]['value'])) {
                    foreach ($product['filters']['filter'] as $filter) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $filter['name'] . ':' . $filter['value'];
                    }
                } elseif (isset($product['filters']['filter']['name'], $product['filters']['filter']['value'])) {
                    $features = $product['filters']['filter']['name'] . ':' . $product['filters']['filter']['value'];
                }
                $product['filters'] = $features;
            }
            if (isset($product['sku'], $product['title'], $product['characteristics']['attribute']['@attributes']) && $product['characteristics']['attribute']['@attributes']) {
                $characteristics = '';
                foreach ($product['characteristics']['attribute']['@attributes'] as $attr_key => $attr_value) {
                    $characteristics .= $characteristics ? $multiple_value_separator : '';
                    $characteristics .= $attr_key . ':' . $attr_value;
                }
                $product['characteristics'] = $characteristics;
            }
            if (isset($product['categories']['category'][0]['name'], $product['categories']['category'][0]['level'], $product['categories']['category'][0]['externalId'])) {
                $product['categories']['category'] = array_reverse($product['categories']['category']);
                $categories = '';
                foreach ($product['categories']['category'] as $c) {
                    if (isset($c['name']) && $c['name']) {
                        $categories .= $categories ? $multiple_value_separator : '';
                        $categories .= $c['name'];
                    }
                }
                $product['categories'] = $categories;
            }
            if (isset($product['allCategories']['category']['name'], $product['allCategories']['category']['externalId'], $product['allCategories']['category']['primary'])) {
                $product['allCategories'] = $product['allCategories']['category']['name'];
            } elseif (isset($product['allCategories']['category'][0]['name'], $product['allCategories']['category'][0]['externalId'], $product['allCategories']['category'][0]['primary'])) {
                $allCategories = '';
                foreach ($product['allCategories']['category'] as $c) {
                    if (isset($c['primary']) && $c['primary']) {
                        $allCategories = $c['name'];
                        break;
                    }
                }
                $product['allCategories'] = $allCategories;
            }
            if (isset($product['AVAILABILITY']['img']['@attributes']['alt'])) {
                $product['AVAILABILITY'] = $product['AVAILABILITY']['img']['@attributes']['alt'];
            }
            if (isset($product['PHOTOS']['PHOTO_0']['URL'])) {
                $photos = '';
                foreach ($product['PHOTOS'] as $photo) {
                    if (isset($photo['URL'])) {
                        $photos .= $photos ? $multiple_value_separator : '';
                        $photos .= $photo['URL'];
                    }
                }
                $product['PHOTOS'] = $photos;
            }
            if (isset($product['product_categories']['@attributes']['prod_cat_num'])) {
                unset($product['product_categories']['@attributes']);
            }
            if (isset($product['price']['@attributes'], $product['price']['price_original'])) {
                $product['price'] = $product['price']['price_original'];
            }
            if (isset($product['availability']['@attributes']['quantity'])) {
                $product['availability'] = $product['availability']['@attributes']['quantity'];
            }
            if (isset($product['specification']['property'][0]['name'], $product['specification']['property'][0]['values']['value'])) {
                $features = '';
                foreach ($product['specification']['property'] as $property) {
                    if (isset($property['name']) && $property['name'] && isset($property['values']['value']) && $property['values']['value']) {
                        if (is_array($property['values']['value'])) {
                            foreach ($property['values']['value'] as $property_val) {
                                $features .= $features ? $multiple_value_separator : '';
                                $features .= $property['name'] . ':' . $property_val;
                            }
                        } else {
                            $features .= $features ? $multiple_value_separator : '';
                            $features .= $property['name'] . ':' . $property['values']['value'];
                        }
                    }
                }
                $product['specification'] = $features;
            }
            if (isset($product['product_listprices']['product_listprice']['listprice_value'])) {
                $product['product_listprices'] = $product['product_listprices']['product_listprice']['listprice_value'];
            }
            if (isset($product['product_retail_prices']['product_retail_price']['retail_price_value'])) {
                $product['product_retail_prices'] = $product['product_retail_prices']['product_retail_price']['retail_price_value'];
            }
            if (isset($product['category']['id'], $product['category']['name'])) {
                $product['category'] = $product['category']['name'];
            }
            if (isset($product['additional_variant_attribute']['label'], $product['additional_variant_attribute']['value'])) {
                $product['additional_variant_attribute'] = $product['additional_variant_attribute']['label'] . ':' . $product['additional_variant_attribute']['value'];
            }
            if (isset($product['ATTRIBUTE_LIST']['ATTRIBUTE'][0]['@attributes']['name'], $product['ATTRIBUTE_LIST']['ATTRIBUTE'][0]['@attributes']['value'])) {
                $attr_list = '';
                foreach ($product['ATTRIBUTE_LIST']['ATTRIBUTE'] as $atr) {
                    $attr_list .= $attr_list ? $multiple_value_separator : '';
                    $attr_list .= $atr['@attributes']['name'] . ':' . $atr['@attributes']['value'];
                }
                $product['ATTRIBUTE_LIST'] = $attr_list;
            }
            if (isset($product['Description']['@attributes']['locale']) && count($product['Description']['@attributes']) == 1) {
                $product['Description'] = '';
            }
            if (isset($product['Attribute'][0]['Name'], $product['Attribute'][0]['Value'])) {
                $attr_list = '';
                foreach ($product['Attribute'] as $atr) {
                    $attr_list .= $attr_list ? $multiple_value_separator : '';
                    $attr_list .= $atr['Name'] . ':' . $atr['Value'];
                }
                $product['Attribute'] = $attr_list;
            }
            if (isset($product['especificacion']['caracteristica1']['tipo'], $product['especificacion']['caracteristica1']['valor'])) {
                $especificacion = '';
                foreach ($product['especificacion'] as $param) {
                    $especificacion .= $especificacion ? $multiple_value_separator : '';
                    $especificacion .= $param['tipo'] . ':' . $param['valor'];
                }
                $product['especificacion'] = $especificacion;
            }
            if (isset($product['existencia']) && is_array($product['existencia']) && Validate::isInt(reset($product['existencia']))) {
                $product['existencia'] = array_sum($product['existencia']);
            }
            if (isset($product['OTHERIMAGES']['OTHERIMG']) && is_array($product['OTHERIMAGES']['OTHERIMG'])) {
                $otherimages = '';
                if (isset($product['OTHERIMAGES']['OTHERIMG']['OTHERIMGURL'])) {
                    $product['OTHERIMAGES']['OTHERIMG'] = [$product['OTHERIMAGES']['OTHERIMG']];
                }
                foreach ($product['OTHERIMAGES']['OTHERIMG'] as $otherimg) {
                    if (isset($otherimg['OTHERIMGURL']) && $otherimg['OTHERIMGURL']) {
                        $otherimages .= $otherimages ? $multiple_value_separator : '';
                        $otherimages .= $otherimg['OTHERIMGURL'];
                    }
                }
                $product['OTHERIMAGES'] = $otherimages;
            }
            if (isset($product['PARAMETERS']['PARAMETER']) && is_array($product['PARAMETERS']['PARAMETER'])) {
                $params = '';
                if (isset($product['PARAMETERS']['PARAMETER']['NAME'])) {
                    $product['PARAMETERS']['PARAMETER'] = [$product['PARAMETERS']['PARAMETER']];
                }
                foreach ($product['PARAMETERS']['PARAMETER'] as $param) {
                    if (isset($param['NAME']) && $param['NAME']) {
                        $param_name = trim($param['NAME']);
                        $param_val = null;
                        if (isset($param['VAL'])) {
                            $param_val = trim($param['VAL']);
                        } elseif (isset($param['VALUE'])) {
                            $param_val = trim($param['VALUE']);
                        }
                        if ($param_name && $param_val !== '') {
                            $params .= $params ? $multiple_value_separator : '';
                            $params .= $param_name . ':"' . $param_val . '"';
                        }
                    }
                }
                $product['PARAMETERS'] = $params;
            }
            if ((isset($product['parameters']['parameter']['name'], $product['parameters']['parameter']['value'])) || (isset($product['parameters']['parameter'][0]['name'], $product['parameters']['parameter'][0]['value']))) {
                $params = '';
                if (isset($product['parameters']['parameter']['name'])) {
                    $product['parameters']['parameter'] = [$product['parameters']['parameter']];
                }
                foreach ($product['parameters']['parameter'] as $param) {
                    if (isset($param['name']) && $param['name'] && isset($param['value'])) {
                        $params .= $params ? $multiple_value_separator : '';
                        $params .= trim($param['name']) . ':"' . trim($param['value']) . '"';
                    }
                }
                $product['parameters'] = $params;
            }
            if ((isset($product['categories']['category']['name'])) || (isset($product['categories']['category'][0]['name']))) {
                $categories = '';
                if (isset($product['categories']['category']['name'])) {
                    $product['categories']['category'] = [$product['categories']['category']];
                }
                foreach ($product['categories']['category'] as $category) {
                    if (isset($category['path']) && $category['path']) {
                        $categories .= $categories ? $multiple_value_separator : '';
                        $categories .= trim($category['path']);
                    } elseif (isset($category['name']) && $category['name']) {
                        $categories .= $categories ? $multiple_value_separator : '';
                        $categories .= trim($category['name']);
                    }
                }
                $product['categories'] = $categories;
            }
            if (isset($product['PARAMETERS']['Parameter']) && is_array($product['PARAMETERS']['Parameter'])) {
                $params = '';
                if (isset($product['PARAMETERS']['Parameter']['ParamName'])) {
                    $product['PARAMETERS']['Parameter'] = [$product['PARAMETERS']['Parameter']];
                }
                foreach ($product['PARAMETERS']['Parameter'] as $param) {
                    if (isset($param['ParamName']) && $param['ParamName'] && isset($param['ParamValue'])) {
                        if (is_array($param['ParamValue'])) {
                            $param['ParamValue'] = '';
                        }
                        $params .= $params ? $multiple_value_separator : '';
                        $params .= trim($param['ParamName']) . ':"' . trim($param['ParamValue']) . ((isset($param['ParamUnit']) && $param['ParamUnit'] && $param['ParamValue']) ? ' ' . $param['ParamUnit'] : '') . '"';
                    }
                }
                $product['PARAMETERS'] = $params;
            }
            if (isset($product['Images']['Image']['@attributes']['url']) || isset($product['Images']['Image'][0]['@attributes']['url'])) {
                if (isset($product['Images']['Image']['@attributes']['url'])) {
                    $product['Images']['Image'] = [$product['Images']['Image']];
                }
                $images = '';
                foreach ($product['Images']['Image'] as $image) {
                    if (isset($image['@attributes']['url'])) {
                        $images .= $images ? $multiple_value_separator : '';
                        $images .= trim($image['@attributes']['url']);
                    }
                }
                $product['Images'] = $images;
            }
            if (isset($product['stockInfo']['stockInfoValue'], $product['stockInfo']['stockInfoData'])) {
                $product['stockInfo'] = $product['stockInfo']['stockInfoData'];
            }
            if (isset($product['parameters']['parameter'][0]['parameter_name']['item'][0]['text'], $product['parameters']['parameter'][0]['option_name']['item'][0]['text'])) {
                $parameters = '';
                foreach ($product['parameters']['parameter'] as $parameter) {
                    if (isset($parameter['parameter_name']['item'][0]['text'], $parameter['option_name']['item'][0]['text'])) {
                        $parameters .= $parameters ? $multiple_value_separator : '';
                        $parameters .= str_replace(':', '∶', $parameter['parameter_name']['item'][0]['text']) . ':' . str_replace(':', '∶', $parameter['option_name']['item'][0]['text']);
                    }
                }
                $product['parameters'] = $parameters;
            }
            if (isset($product['properties']['property']) && is_array($product['properties']['property'])) {
                $properties = '';
                if (!isset($product['properties']['property'][0])) {
                    $product['properties']['property'] = [$product['properties']['property']];
                }
                foreach ($product['properties']['property'] as $property) {
                    if (isset($property['name'], $property['values']['value']) && $property['name'] && $property['values']['value'] !== '' && !is_array($property['values']['value'])) {
                        $properties .= $properties ? $multiple_value_separator : '';
                        $properties .= trim($property['name']) . ':"' . trim($property['values']['value']) . '"';
                    } elseif (isset($property['id'], $property['values']['value']) && $property['id'] && $property['values']['value'] !== '' && !is_array($property['values']['value'])) {
                        $properties .= $properties ? $multiple_value_separator : '';
                        $properties .= trim($property['id']) . ':"' . trim($property['values']['value']) . '"';
                    }
                }
                $product['properties'] = $properties;
            }
            if (isset($product['prices']['cost-price']['value'], $product['prices']['cost-price']['currency'])) {
                $product['prices'] = $product['prices']['cost-price']['value'] . ' ' . $product['prices']['cost-price']['currency'];
            }
            if (isset($product['enclosure']['@attributes']['url'])) {
                $product['enclosure'] = $product['enclosure']['@attributes']['url'];
            }
            if (isset($product['FILE_ATTACHMENT']) && $product['FILE_ATTACHMENT']) {
                if (isset($product['FILE_ATTACHMENT']['ATTACHMENT_URL'], $product['FILE_ATTACHMENT']['ATTACHMENT_NAME'])) {
                    $product['FILE_ATTACHMENT'] = [$product['FILE_ATTACHMENT']];
                }
                if (isset($product['FILE_ATTACHMENT'][0]['ATTACHMENT_URL'], $product['FILE_ATTACHMENT'][0]['ATTACHMENT_NAME'])) {
                    $attachments = '';
                    foreach ($product['FILE_ATTACHMENT'] as $attachment) {
                        if (isset($attachment['ATTACHMENT_URL']) && $attachment['ATTACHMENT_URL']) {
                            $attachments .= $attachments ? $multiple_value_separator : '';
                            $attachments .= $attachment['ATTACHMENT_URL'];
                        }
                    }
                    $product['FILE_ATTACHMENT'] = $attachments;
                }
            }
            if ((isset($product['parameters']['item']['name'], $product['parameters']['item']['value'])) || isset($product['parameters']['item'][0]['name'], $product['parameters']['item'][0]['value'])) {
                $params = '';
                if (isset($product['parameters']['item']['name'], $product['parameters']['item']['value'])) {
                    $product['parameters']['item'] = [$product['parameters']['item']];
                }
                foreach ($product['parameters']['item'] as $param) {
                    $params .= $params ? $multiple_value_separator : '';
                    $params .= str_replace(':', '∶', $param['name']) . ':' . str_replace(':', '∶', $param['value']);
                }
                $product['parameters'] = $params;
            }
            if (isset($product['atributi']) && is_array($product['atributi'])) {
                $atributi = '';
                foreach ($product['atributi'] as $atributi_key => $atributi_value) {
                    if ($atributi_key && $atributi_value && !is_array($atributi_value)) {
                        $atributi_key = str_replace('_', ' ', $atributi_key);
                        $atributi_key = ucfirst($atributi_key);
                        $atributi .= $atributi ? $multiple_value_separator : '';
                        $atributi .= $atributi_key . ':"' . $atributi_value . '"';
                    }
                }
                $product['atributi'] = $atributi;
            }
            if (isset($product['features']['f'][0]['name'], $product['features']['f'][0]['value'])) {
                $features = '';
                foreach ($product['features']['f'] as $f) {
                    if (isset($f['name']) && $f['name'] && isset($f['value']) && !is_array($f['value'])) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $f['name'] . ':"' . $f['value'] . '"';
                    }
                }
                $product['features'] = $features;
            }
            if (isset($product['attributes']['attr'][0]['name'], $product['attributes']['attr'][0]['text'])) {
                $features = '';
                foreach ($product['attributes']['attr'] as $attr_feature) {
                    if (isset($attr_feature['name']) && $attr_feature['name'] && isset($attr_feature['text']) && !is_array($attr_feature['text'])) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $attr_feature['name'] . ':"' . $attr_feature['text'] . '"';
                    }
                }
                $product['attributes'] = $features;
            }
            if (isset($product['Attributes']['Attribute'][0]['name']) || isset($product['Attributes']['Attribute']['name'])) {
                if (isset($product['Attributes']['Attribute']['name'])) {
                    $product['Attributes']['Attribute'] = [$product['Attributes']['Attribute']];
                }
                $features = '';
                foreach ($product['Attributes']['Attribute'] as $attr_feature) {
                    if (isset($attr_feature['name']) && $attr_feature['name'] && isset($attr_feature['value']) && !is_array($attr_feature['value'])) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $attr_feature['name'] . ':"' . $attr_feature['value'] . '"';
                    }
                }
                $product['Attributes'] = $features;
            }
            if (isset($product['atributes']['atribute'][0]['name']) || isset($product['atributes']['atribute']['name'])) {
                if (isset($product['atributes']['atribute']['name'])) {
                    $product['atributes']['atribute'] = [$product['atributes']['atribute']];
                }
                $features = '';
                foreach ($product['atributes']['atribute'] as $attr_feature) {
                    if (isset($attr_feature['name']) && $attr_feature['name'] && isset($attr_feature['value']) && !is_array($attr_feature['value'])) {
                        $features .= $features ? $multiple_value_separator : '';
                        $features .= $attr_feature['name'] . ':"' . $attr_feature['value'] . '"';
                    }
                }
                $product['atributes'] = $features;
            }
            if (isset($product['images']['image'][0]['link']) || isset($product['images']['image']['link'])) {
                if (isset($product['images']['image']['link'])) {
                    $product['images']['image'] = [$product['images']['image']];
                }
                $images = '';
                foreach ($product['images']['image'] as $image) {
                    $images .= $images ? $multiple_value_separator : '';
                    $images .= $image['link'];
                }
                $product['images'] = $images;
            }
            if ((isset($product['parameters']['parameter']['@attributes']['name'], $product['parameters']['parameter']['@attributes']['value'])) || (isset($product['parameters']['parameter'][0]['@attributes']['name'], $product['parameters']['parameter'][0]['@attributes']['value']))) {
                $params = '';
                if (isset($product['parameters']['parameter']['@attributes']['name'])) {
                    $product['parameters']['parameter'] = [$product['parameters']['parameter']];
                }
                foreach ($product['parameters']['parameter'] as $param) {
                    if (isset($param['@attributes']['name']) && $param['@attributes']['name'] && isset($param['@attributes']['value'])) {
                        $params .= $params ? $multiple_value_separator : '';
                        $params .= trim($param['@attributes']['name']) . ':"' . trim($param['@attributes']['value']) . '"';
                    }
                }
                $product['parameters'] = $params;
            }
            if (isset($product['IMAGES']['IMAGE'][0]['URL']) || isset($product['IMAGES']['IMAGE']['URL'])) {
                if (isset($product['IMAGES']['IMAGE']['URL'])) {
                    $product['IMAGES']['IMAGE'] = [$product['IMAGES']['IMAGE']];
                }
                $images = '';
                foreach ($product['IMAGES']['IMAGE'] as $image) {
                    $images .= $images ? $multiple_value_separator : '';
                    $images .= $image['URL'];
                }
                $product['IMAGES'] = $images;
            }
            if (isset($product['ATTACHMENTS']['FILE'][0]['URL']) || isset($product['ATTACHMENTS']['FILE']['URL'])) {
                if (isset($product['ATTACHMENTS']['FILE']['URL'])) {
                    $product['ATTACHMENTS']['FILE'] = [$product['ATTACHMENTS']['FILE']];
                }
                $attachments = '';
                foreach ($product['ATTACHMENTS']['FILE'] as $attachment) {
                    $attachments .= $attachments ? $multiple_value_separator : '';
                    $attachments .= $attachment['URL'];
                }
                $product['ATTACHMENTS'] = $attachments;
            }
            if (isset($product['Barcodes']['Barcode']['Code']) || isset($product['Barcodes']['Barcode'][0]['Code'])) {
                // If more than one barcode, take the last one
                $product['Barcodes'] = isset($product['Barcodes']['Barcode'][0]) ? end($product['Barcodes']['Barcode'])['Code'] : $product['Barcodes']['Barcode']['Code'];
                if (isset($product['Code']) && $product['Barcodes'] == $product['Code']) {
                    $product['Barcodes'] = '';
                }
            }
            if ((isset($product['product_detail'][0]['attribute_name'], $product['product_detail'][0]['attribute_value'])) || (isset($product['product_detail']['attribute_name'], $product['product_detail']['attribute_value']))) {
                if (isset($product['product_detail']['attribute_name'], $product['product_detail']['attribute_value'])) {
                    $product['product_detail'] = [$product['product_detail']];
                }
                $product_detail = '';
                foreach ($product['product_detail'] as $p_detail) {
                    if (isset($p_detail['attribute_name']) && $p_detail['attribute_name'] && isset($p_detail['attribute_value']) && !is_array($p_detail['attribute_value'])) {
                        $product_detail .= $product_detail ? $multiple_value_separator : '';
                        $product_detail .= $p_detail['attribute_name'] . ':"' . $p_detail['attribute_value'] . '"';
                    }
                }
                $product['product_detail'] = $product_detail;
            }
            if (isset($product['gallery']['image']) && is_array($product['gallery']['image'])) {
                if (isset($product['gallery']['image']['thumb'], $product['gallery']['image']['image'])) {
                    $product['gallery'] = $product['gallery']['image']['image'];
                } elseif (isset($product['gallery']['image'][0]['thumb'], $product['gallery']['image'][0]['image'])) {
                    $gallery = '';
                    foreach ($product['gallery']['image'] as $gallery_image) {
                        if (isset($gallery_image['image']) && $gallery_image['image']) {
                            $gallery .= $gallery ? $multiple_value_separator : '';
                            $gallery .= $gallery_image['image'];
                        }
                    }
                    $product['gallery'] = $gallery;
                }
            }
            if ((isset($product['specs']['spec']['label'], $product['specs']['spec']['value'])) || (isset($product['specs']['spec'][0]['label'], $product['specs']['spec'][0]['value']))) {
                if (isset($product['specs']['spec']['value'])) {
                    $product['specs']['spec'] = [$product['specs']['spec']];
                }
                $specs = '';
                foreach ($product['specs']['spec'] as $spec) {
                    if (isset($spec['label']) && $spec['label'] && isset($spec['value']) && !is_array($spec['value'])) {
                        $specs .= $specs ? $multiple_value_separator : '';
                        $specs .= $spec['label'] . ':"' . $spec['value'] . '"';
                    }
                }
                $product['specs'] = $specs;
            }
            if (isset($product['category']['id'], $product['category']['category_name'])) {
                $product['category'] = $product['category']['category_name'];
            }
            if ((isset($product['parameters']['item'][0]['et']['id'], $product['parameters']['item'][0]['et']['name'], $product['parameters']['item'][0]['et']['value'])) || (isset($product['parameters']['item']['et']['id'], $product['parameters']['item']['et']['name'], $product['parameters']['item']['et']['value']))) {
                if (isset($product['parameters']['item']['et']['id'])) {
                    $product['parameters']['item'] = [$product['parameters']['item']];
                }
                $parameters = '';
                foreach ($product['parameters']['item'] as $parameter) {
                    if (isset($parameter['et']['name']) && $parameter['et']['name'] && isset($parameter['et']['value']) && !is_array($parameter['et']['value'])) {
                        $parameters .= $parameters ? $multiple_value_separator : '';
                        $parameters .= $parameter['et']['name'] . ':"' . $parameter['et']['value'] . '"';
                    }
                }
                $product['parameters'] = $parameters;
            }
            if (isset($product['attributes'], $product['id'],$product['name'],$product['category']) && is_array($product['attributes'])) {
                $attributes = '';
                foreach ($product['attributes'] as $attribute_key => $attribute_value) {
                    if (!$attribute_key || $attribute_value === '') {
                        continue;
                    }
                    if (is_array($attribute_value)) {
                        foreach ($attribute_value as $attribute_value2) {
                            if (!is_array($attribute_value2)) {
                                $attributes .= $attributes ? $multiple_value_separator : '';
                                $attributes .= $attribute_key . ':"' . $attribute_value2 . '"';
                            }
                        }
                    } else {
                        $attributes .= $attributes ? $multiple_value_separator : '';
                        $attributes .= $attribute_key . ':"' . $attribute_value . '"';
                    }
                }
                $product['attributes'] = $attributes;
            }
            if (isset($product['id'], $product['price'], $product['promo'], $product['price_nopromo'])) {
                if ($product['promo'] == 0 && $product['price_nopromo'] == 0 && $product['price'] > 0) {
                    $product['price_nopromo'] = $product['price'];
                }
            }
            if (isset($product['images']['image']['imagemax']) || isset($product['images']['image'][0]['imagemax'])) {
                if (isset($product['images']['image']['imagemax'])) {
                    $product['images']['image'] = [$product['images']['image']];
                }
                $images = '';
                foreach ($product['images']['image'] as $image) {
                    if (isset($image['imagemax']) && $image['imagemax']) {
                        $images .= $images ? $multiple_value_separator : '';
                        $images .= $image['imagemax'];
                    }
                }
                $product['images'] = $images;
            }
            if (isset($product['categories']['category_ref_1'], $product['categories']['category_name_1'])) {
                $categories = '';
                for ($i = 1; $i <= 5; ++$i) {
                    if (isset($product['categories']['category_name_' . $i]) && $product['categories']['category_name_' . $i]) {
                        $categories .= $categories ? $multiple_value_separator : '';
                        $categories .= $product['categories']['category_name_' . $i];
                    }
                }
                $product['categories'] = $categories;
            }

            // Step 8
            // Take care of multiple values
            foreach ($product as $attr => $value) {
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
                // $product_final[$column] = isset($product[$column]) ? str_replace('&amp;', '&', $product[$column]) : "";
                $product_final[$column] = isset($product[$column]) ? html_entity_decode($product[$column]) : '';
            }
            fputcsv($handle, $product_final, ';', '"');
        }

        unset($array);

        // Close file pointer
        fclose($handle);

        return true;
    }
}
