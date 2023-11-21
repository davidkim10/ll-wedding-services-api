<?php

function clean_html_content($content) {
    if (isset($content)) {
        $content = wp_kses_post($content);
        $content = html_entity_decode($content);
        $content = str_replace(array("\n", "\t"), '', $content);
    }
    return $content;
}

// Parse html and return specific element with class name
function get_html_by_className($html_content, $className) {
    $xpath = new DOMXPath($html_content);
    $tableElementQuery = "//div[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]";
    $elements = $xpath->query($tableElementQuery);
    $pricing_table_html = '';

    foreach ($elements as $element) {
        $pricing_table_html .= $html_content->saveHTML($element);
    }

    return $pricing_table_html;
}

// Parse HTML and generate package data [[], [], [], ...]
function createPackage($html_content, $pageId, $pageURL, $packageCategory) {
    $packages = [];
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html_content, 'HTML-ENTITIES', 'UTF-8'));

    // DOM Queries
    $xpath = new DOMXPath($dom);
    $priceTableQuery = "//div[contains(concat(' ', normalize-space(@class), ' '), ' basel-price-table ')]";
    $priceElementQuery = ".//div[contains(concat(' ', normalize-space(@class), ' '), ' basel-plan-price ')]";
    $featureElementQuery = ".//div[contains(concat(' ', normalize-space(@class), ' '), ' basel-plan-feature ')]";

    // Find elements with specific classes
    $price_tables = $xpath->query($priceTableQuery);

    foreach ($price_tables as $price_table) {
        $price = '';
        $features = [];

        // DOM Elements
        $price_elements = $xpath->query($priceElementQuery, $price_table);
        $feature_elements = $xpath->query($featureElementQuery, $price_table);

        foreach ($price_elements as $price_element) {
            $price = trim($price_element->nodeValue);
        }

        foreach ($feature_elements as $feature_element) {
            $featureText = trim($feature_element->nodeValue);
            $isFeature = !str_starts_with($featureText, '----') && !empty($featureText);

            if ($isFeature) {
                $features[] = $featureText;
            }
        }

        // Package information for each occurrence of "basel-price-table"
        $package_info = array(
            'pageId' => $pageId,
            'category' => $packageCategory,
            'price' => $price,
            'features' => $features,
            'url' => $pageURL,
        );

        $packages[] = $package_info; // Store package info in the packages array
    }

    return $packages;
}
