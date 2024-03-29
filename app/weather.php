<?php
if (!extension_loaded('gd')) {
    die("The GD library is required to run this script.");
}

$apiKey = '-INSERT YOUR OPENWEATHERMAP API KEY -';

$cityCode = isset($_GET['location']) ? $_GET['location'] : '2643743'; // Default to London if no city provided

$url = "https://api.openweathermap.org/data/2.5/weather?id={$cityCode}&appid={$apiKey}&units=metric";

$contents = file_get_contents($url);
if ($contents === false) {
    die("Failed to fetch weather data.");
}

$weatherData = json_decode($contents);
if (!isset($weatherData->weather[0])) {
    die("Weather information is unavailable.");
}
$iconCode = $weatherData->weather[0]->icon;

$iconPath = __DIR__ . '/icons/' . substr($iconCode, 0, 2) . '.png'; // Using substr to get the first 2 characters

if (!file_exists($iconPath)) {
    die("Icon file does not exist.");
}

$icon = imagecreatefrompng($iconPath);

$im = imagecreatetruecolor(240, 240);

$background = imagecolorallocate($im, 0, 0, 0); // Black
$textColor = imagecolorallocate($im, 255, 255, 255); // White

imagefill($im, 0, 0, $background);

$iconWidth = imagesx($icon);
$iconHeight = imagesy($icon);
imagecopy($im, $icon, (240 - $iconWidth) / 2, (240 - $iconHeight) / 2 - 30, 0, 0, $iconWidth, $iconHeight);

$city = $weatherData->name;
$temperature = $weatherData->main->temp . '°C';
$conditions = $weatherData->weather[0]->description;

$font = __DIR__ . '/PixeloidSans.ttf'; 

imagettftext($im, 14, 0, 10, 240 - 35, $textColor, $font, "{$city}");
imagettftext($im, 14, 0, 10, 240 - 10, $textColor, $font, "Temp: {$temperature}");

header('Content-Type: image/png');

imagepng($im);

imagedestroy($im);
imagedestroy($icon);
?>
