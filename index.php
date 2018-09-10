<?php
/**
 * Created by PhpStorm.
 * User: bachnguyen
 * Date: 10/09/2018
 * Time: 10:46
 */
require_once 'vendor/autoload.php';
require_once 'autopost/tool.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$base = dirname(__FILE__);
$logFileName = 'Log_' . date('y_m_d') . '.log';
$log = new Logger('name');
try {
    $log->pushHandler(new StreamHandler($base . '/logs/' . $logFileName, Logger::INFO));
    $log->info('====Start job at '.date('y-m-d').'====');
} catch (Exception $e) {
    echo 'Error open log file';
}

$token = refreshToken();

$p = autopost_add_post($token, 'https://dongten.net/category/cau-nguyen/loi-chua-cho-ngay-song/', "Lời Chúa Mỗi Ngày");
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}

$p = autopost_add_post($token, 'https://dongten.net/category/cau-nguyen/loi-chua-cho-ngay-song/', "Lời Chúa Mỗi Ngày");
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = autopost_add_post($token, 'https://dongten.net/category/hoc-lam-nguoi/', "Học Làm Người");
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = autopost_add_post($token, 'https://dongten.net/category/hoc-lam-nguoi/le-song/', "Lẽ Sống");
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = autopost_add_post($token, 'https://dongten.net/category/phuc-vu-duc-tin/duc-tin-va-nguoi-tre/', "Đức Tin Và Người Trẻ");
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = cg_autopost_add_post($token,'http://conggiao.info/viet-nam-n-1488', 'Tin Giáo Hội Việt Nam ');
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = cg_autopost_add_post($token,'http://conggiao.info/vatican-n-809', 'Tin Giáo Hội Thế Giới');
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = cg_autopost_add_post($token,'http://conggiao.info/hoan-vu-n-810', 'Tin Giáo Hội Thế Giới');
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}
$p = tgp_autopost_add_post($token,'https://tonggiaophanhanoi.org/tin-tuc/tin-giao-phan/228-tin-tong-hop', 'Tin Giáo Hội Việt Nam');
$post = post($token, $p);
if ($post == -1) {
    $log->error($p['title'] . ' is exist');
} else {
    $post = json_decode($post, true);
    $log->info($post['title']["raw"]);
}