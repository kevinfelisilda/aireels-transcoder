<?php
set_time_limit(0);

require_once 'inc/bootstrap.php';

use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

$hashfile = uniqid();

function echoF($text){
    ob_end_flush();
    echo '<div><pre>' . $text . '</pre></div><br>';
    ob_start();
}

function getFile($url, $filename) {
    $filepath = dirname(__FILE__) . '/tmp/' . $filename;
    $fp = fopen($filepath, 'w+');
    //Here is the file we are downloading, replace spaces with %20
    $ch = curl_init(str_replace(" ","%20",$url));
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    // write curl response to file
    curl_setopt($ch, CURLOPT_FILE, $fp); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // get curl response
    curl_exec($ch); 
    curl_close($ch);
    fclose($fp);
    return $filepath;
}

$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : false;
$message = '';

if ($url) {
    $videofile = getFile($url, $hashfile);
    $message = 'Submitted: ' . $url . '<br>';
    $filename = basename($url);
    try {
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($videofile);
        $video
            ->filters()
            ->resize(new Dimension(1280, 720), ResizeFilter::RESIZEMODE_INSET)
            ->synchronize();
        $video
            ->filters()
            ->watermark('logo-white.png', array(
                'x' => '(W-w)/2',
                'y' => '(H-h)/2',
            ));

        $format = new FFMpeg\Format\Video\X264();
        //$format->on('progress', function ($video, $format, $percentage) {
        //    echo "$percentage % transcoded\n";
        //});

        $video
            ->save($format, "export/$filename-$hashfile.mp4");
        //    ->save(new FFMpeg\Format\Video\WMV(), 'export.wmv');
        //    ->save(new FFMpeg\Format\Video\WebM(), 'export.webm');
        unlink($videofile);
    } catch (Exception $e) {
        $message .= 'Error: ' . $e->getMessage();
    }
}

$files = scandir('export');

include_once 'view/list.php';
