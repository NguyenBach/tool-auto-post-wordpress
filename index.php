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

//$base = dirname(__FILE__);
//$logFileName = 'Log_' . date('y_m_d') . '.log';
//$log = new Logger('name');
//try {
//    $log->pushHandler(new StreamHandler($base . '/logs/' . $logFileName, Logger::INFO));
//    $log->info('====Start job at ' . date('y-m-d') . '====');
//} catch (Exception $e) {
//    echo 'Error open log file';
//}

//$token = refreshToken('dongten.net', 'KZ9xVz)%@9^pSmpeKGw%SDv^');
//
//$p = autopost_add_post($token, 'https://dongten.net/category/cau-nguyen/loi-chua-cho-ngay-song/', "	Lời Chúa cho ngày sống");
//$post = post($token, $p[0]);
//$log->info($post);
//if ($post == -1) {
//    $log->error($p[0]['title'] . ' is exist');
//} else {
//    $post = json_decode($post, true);
//    $log->info($post['title']["raw"]);
//}
//
//$p = autopost_add_post($token, 'https://dongten.net/category/ve-dong-ten/cac-thanh/', "Hạnh các Thánh");
//$post = post($token, $p[0]);
//$log->info($post);
//if ($post == -1) {
//    $log->error($p[0]['title'] . ' is exist');
//} else {
//    $post = json_decode($post, true);
//    $log->info($post['title']["raw"]);
//}
//$posts = autopost_add_post($token, 'https://dongten.net/category/hoc-lam-nguoi/', "Học làm người");
//foreach ($posts as $p) {
//    $post = post($token, $p);
//    $log->info($post);
//    if ($post == -1) {
//        $log->error($p['title'] . ' is exist');
//    } else {
//        $post = json_decode($post, true);
//        $log->info($post['title']["raw"]);
//    }
//}
//$posts = autopost_add_post($token, 'https://dongten.net/category/hoc-lam-nguoi/le-song/', "Lẽ sống");
//foreach ($posts as $p) {
//    $post = post($token, $p);
//    $log->info($post);
//    if ($post == -1) {
//        $log->error($p['title'] . ' is exist');
//    } else {
//        $post = json_decode($post, true);
//        $log->info($post['title']["raw"]);
//    }
//}
//
///// conggiao.info
//
$token = refreshToken('conggiao.info', 'F(NqSGcA2xpqS8VHFAV!0Z*T');
$posts = cg_autopost_add_post($token, 'http://conggiao.info/viet-nam-n-1488', 'Tin Giáo Hội Việt Nam');
//foreach ($posts as $p) {
//    $post = post($token, $p);
//    $log->info($post);
//    if ($post == -1) {
//        $log->error($p['title'] . ' is exist');
//    } else {
//        $post = json_decode($post, true);
//        $log->info($post['title']["raw"]);
//    }
//}
//$posts = cg_autopost_add_post($token, 'http://conggiao.info/vatican-n-809', 'Vatican');
//foreach ($posts as $p) {
//    $post = post($token, $p);
//    $log->info($post);
//    if ($post == -1) {
//        $log->error($p['title'] . ' is exist');
//    } else {
//        $post = json_decode($post, true);
//        $log->info($post['title']["raw"]);
//    }
//}
//$posts = cg_autopost_add_post($token, 'http://conggiao.info/hoan-vu-n-810', 'Tin Giáo Hội hoàn vũ');
//foreach ($posts as $p) {
//    $post = post($token, $p);
//    $log->info($post);
//    if ($post == -1) {
//        $log->error($p['title'] . ' is exist');
//    } else {
//        $post = json_decode($post, true);
//        $log->info($post['title']["raw"]);
//    }
//}
//
/////Tong giao phan ha noi
//$token = refreshToken('tonggiaophanhanoi.org', 'bmn#P8iT2mCt)Ao&lBL%cnY6');
//$posts = tgp_autopost_add_post($token, 'https://tonggiaophanhanoi.org/tin-tuc/tin-giao-phan/228-tin-tong-hop', 'Tin Giáo Phận Hà Nội');
//$p = $posts[0];
//$post = post($token, $p);
//$log->info($post);
//if ($post == -1) {
//    $log->error($p[0]['title'] . ' is exist');
//} else {
//    $post = json_decode($post, true);
//    $log->info($post['title']["raw"]);
//}
//
//$posts = tgp_autopost_add_post($token, 'https://tonggiaophanhanoi.org/tin-tuc/tin-giao-phan/', 'Tin Giáo Phận Hà Nội');
//foreach ($posts as $p) {
//    $post = post($token, $p);
//    $log->info($post);
//    if ($post == -1) {
//        $log->error($p['title'] . ' is exist');
//    } else {
//        $post = json_decode($post, true);
//        $log->info($post['title']["raw"]);
//    }
//}
