<?php

$PLUGIN_UTILS = __DIR__ . '/../utils/utils.php';
require_once $PLUGIN_UTILS;


class DataService {
    private static function getData($function_name) {
        $data = call_user_func(array('self', $function_name));
        $isError = is_wp_error($data);
        return $isError ? $data : rest_ensure_response($data);
    }

    private static function getAllServicesData() {
        return getAllServicesData();
    }

    private static function getWeddingServicesData() {
        return getWeddingServicesData();
    }

    private static function getWeddingServicesPackageData() {
        return getWeddingServicesPackageData();
    }

    public static function get_all_services_data() {
        $data = self::getData('getAllServicesData');
        return $data;
    }

    public static function get_wedding_services_data() {
        $data = self::getData('getWeddingServicesData');
        return $data;
    }

    public static function get_wedding_packages_data() {
        $data = self::getData('getWeddingServicesPackageData');
        return $data;
    }
}


// @deprecated
function getPageData() {
    $siteURL = get_site_url();
    $endpointURL = "$siteURL/wp-json/wp/v2/pages?per_page=50";
    $response = wp_remote_get($endpointURL, array('timeout' => 120));

    // Handle API request errors
    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Failed to fetch content', array('status' => 500));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if content is valid and exists
    if (!$data) {
        return new WP_Error('content_error', 'Content not found', array('status' => 404));
    }

    return $data;
}

function getAllServicesData() {
    $servicesPageID = 390;
    $siteURL = get_site_url();
    $endpointURL = "$siteURL/wp-json/wp/v2/pages?parent=$servicesPageID";
    $response = wp_remote_get($endpointURL, array('timeout' => 120));

    // Handle API request errors
    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Failed to fetch content', array('status' => 500));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if content is valid and exists
    if (!$data) {
        return new WP_Error('content_error', 'Content not found', array('status' => 404));
    }

    return $data;
}

function getWeddingServicesData() {
    $data = getAllServicesData();
    $servicePages = [];
    $targetPageIds = [2020, 1765, 381];

    if (!$data) {
        return new WP_Error('content_error', 'Content not found', array('status' => 404));
    }

    $filteredServiceData = array_filter($data, function ($item) use ($targetPageIds) {
        return in_array($item['id'], $targetPageIds);
    });

    foreach ($filteredServiceData as $item) {
        $page_id = $item['id'];
        $link = $item['link'];
        $slug = $item['slug'];
        $content = clean_html_content($item['content']['rendered']);

        $page = [
            'id' => $page_id,
            'link' => $link,
            'slug' => $slug,
            'content' => $content,
        ];
        $servicePages[] = $page;
    }

    return $servicePages;
}

function getWeddingServicesPackageData() {
    $packages = [];
    $servicesData = getWeddingServicesData();

    if (!$servicesData) {
        return $packages;
    }

    foreach ($servicesData as $service) {
        $html_content = $service['content'];
        $id = $service['id'];
        $pageURL = $service['link'];
        $packageCategory = $service['slug'];
        $packages[] = createPackage($html_content, $id, $pageURL, $packageCategory);
    }

    return array_merge(...$packages);
}
