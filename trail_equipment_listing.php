<!DOCTYPE html>
<html>
    <head>
        <title>Equipment listing from Trail</title>
    </head>
    <body>
    <table>
<?php

require_once('./config.php');

// set SEARCH variables
$freematch = ''; // set manually here if needed

if(isset($_GET['free'])) {
     $freematch = $_GET['free']; // get from url parameter, ?free=xxx
     
     // Replace Finnish alphabets
     $freematch = str_replace('å', '%C3%A5', $freematch);
     $freematch = str_replace('Å', '%C3%85', $freematch);
     $freematch = str_replace('ä', '%C3%A4', $freematch);
     $freematch = str_replace('Ä', '%C3%84', $freematch);
     $freematch = str_replace('ö', '%C3%B6', $freematch);
     $freematch = str_replace('Ö', '%C3%96', $freematch);
};

$model1 = ''; // set manually here if needed, for example 303979439
if(isset($_GET['model1'])) {
     $model1 = $_GET['model1']; // get from url parameter, ?model1=xxx
};

$model2 = ''; // set manually here if needed
if(isset($_GET['model2'])) {
     $model2 = $_GET['model2']; // get from url parameter, ?model2=xxx
};

$location1 = ''; // set manually here if needed
if(isset($_GET['location1'])) {
     $location1 = $_GET['location1']; // get from url parameter, ?location1=xxx
};

// Minimal error check
if($code == '') {
     echo '<p>API key not set.</p>';
     die;
};

if($department1 == '') {
     echo '<p>Department not set.</p>';
     die;
};

// Check if models are defined
if($model1 != '') {
     $model_category_id1 = '&search%5Bmodel_category_ids%5D%5B%5D='.$model1;
};

if($model2 != '') {
     $model_category_id2 = '&search%5Bmodel_category_ids%5D%5B%5D='.$model2;
};

// Check if department is defined instead of default from config.php
if(isset($_GET['department'])) {
     $department1 = $_GET['department'];
};

// Check if 2nd department is defined
if(isset($_GET['department2'])) {
     $department2 = $_GET['department2'];
};

if($department2 != '') {
     $department2 = '&search%5Bdepartment_ids%5D%5B%5D='.$department2;
};

// set POST variables
$url = 'https://api.trail.fi/api/v1/items?&search%5Bfree%5D='.$freematch.'&search%5Bdepartment_ids%5D%5B%5D='.$department1.''.$department2.'&search%5Blocations%5D%5B%5D='.$location1.''.$model_category_id1.''.$model_category_id2.'&search%5Bitem_type_id%5D=&search%5Bafter%5D=&search%5Bbefore%5D=&search%5Baudited_after%5D=&search%5Baudited_before%5D=&search%5Bexpires_after%5D=&search%5Bexpires_before%5D=&search%5Bprice_above%5D=&search%5Bprice_below%5D=&search%5Bcreated_after%5D=&search%5Bmarked%5D=&search%5Bdeleted%5D=&search%5Bdeleted_after%5D=&search%5Bdeleted_before%5D=&search%5Bdelete_reason%5D=&search%5Breservable%5D=&page=1&per_page=50000';

// open connection
$ch = curl_init();

// set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$code));

// save response to variable $result
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// execute post
$json = curl_exec($ch);

// close connection
curl_close($ch);

// var_dump($result);

// create PHP array from Trail JSON export
$array = json_decode($json, true);

// Display root location
echo "<H1>".$array['data'][0]['root_location']."</H1>";

// Create an array for the web page
echo "<tr><td>Amount</td><td>Manufacturer</td><td>Model</td><td>Description</td></tr>";

$results = [];

foreach ($array['data'] as $item) {
    // Fetch product name, manufacturer and description
    $name = $item['model']['name'];
    $manufacturer = $item['manufacturer'];
    $description = $item['description'];

    // Check if the item name is already in the array
    if (!isset($results[$name])) {
        $results[$name] = [
            'count' => 1,                    // Count + 1 if there are multiple items of the same model
            'manufacturer' => $manufacturer, // Add manufacturer to the array
            'description' => $description    // Add desciption to the array
        ];
    } else {
        $results[$name]['count']++;
    }
}

foreach ($results as $name => $information) {
     // Output the array to html rows and columns
    echo "<tr><td>{$information['count']} pcs</td><td>{$information['manufacturer']}</td><td>$name</td><td>{$information['description']}</td></tr>";
}

?>
</table>

<?php

// print API URI and PHP array for debugging purposes, set debug as url parameter
if(isset($_GET['debug'])) {
     echo '<h3>Query URL</h4>';
     echo $url;
     echo '<h3>PHP array</h4>';
     echo '<pre>'; print_r($array); echo '</pre>';
     echo '<p>end of report</p>';
};

?>

</body>
<style>
body {
     font-family: "Segoe UI", "Segoe UI Web (West European)", "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", sans-serif;
     font-size: 14px;
}

td {
     padding: 5px 10px;
}

table {
     max-width: 100%;
     border: 0;
     border-collapse: collapse;
}

table td:nth-child(2),
table td:nth-child(3) {
     white-space:nowrap;
     width: 1px;
     padding-right: 30px;
     margin: 10px;
}

table tr:nth-child(1) {
     font-weight: bold;
}

tr {
border-bottom: 1px solid white;
}
</style>
</html>
