<?php
require_once('../config.php');
require_login();
global $USER, $CFG;

$q = optional_param('q', '', PARAM_TEXT);
$results = [];

if ($q) {
    try {
        $searchmanager = \core_search\manager::instance();
        $formdata = new stdClass();
        $formdata->q = $q;

       foreach ($searchresults as $sr) {
    // Determine type
    if (!empty($sr->type)) {
        $type = $sr->type;
    } elseif (!empty($sr->component)) {
        $type = $sr->component;
    } elseif (!empty($sr->url)) {
        // Infer from URL
        if (strpos($sr->url, '/course/view.php') !== false) {
            $type = 'Course';
        } elseif (strpos($sr->url, '/mod/forum') !== false) {
            $type = 'Forum';
        } elseif (strpos($sr->url, '/mod/assign') !== false) {
            $type = 'Assignment';
        } else {
            $type = 'Other';
        }
    } else {
        $type = 'Other';
    }

    $results[] = [
        'type' => $type,
        'name' => $sr->title ?? '',
        'description' => $sr->description ?? '',
        'url' => $sr->url ?? '#'
    ];
}

    } catch (Exception $e) {
        $results[] = [
            'type' => 'error',
            'name' => 'Search error',
            'description' => $e->getMessage(),
            'url' => '#'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'results' => $results
]);
exit;
