<?php
/**
 * Created by PhpStorm.
 * User: bachnguyen
 * Date: 10/09/2018
 * Time: 10:46
 */
require_once 'vendor/autoload.php';
require_once 'autopost/tool.php';


$token = refreshToken('dongten.net', 'KZ9xVz)%@9^pSmpeKGw%SDv^');

$p = autopost_add_post($token, 'https://dongten.net/category/cau-nguyen/loi-chua-cho-ngay-song/', "	Lời Chúa cho ngày sống");

$p = autopost_add_post($token, 'https://dongten.net/category/ve-dong-ten/cac-thanh/', "Hạnh các Thánh");

$posts = autopost_add_post($token, 'https://dongten.net/category/hoc-lam-nguoi/', "Học làm người");
$posts = autopost_add_post($token, 'https://dongten.net/category/hoc-lam-nguoi/le-song/', "Lẽ sống");


/// conggiao.info

$token = refreshToken('conggiao.info', 'F(NqSGcA2xpqS8VHFAV!0Z*T');
$posts = cg_autopost_add_post($token, 'http://conggiao.info/viet-nam-n-1488', 'Tin Giáo Hội Việt Nam');
//
$posts = cg_autopost_add_post($token, 'http://conggiao.info/vatican-n-809', 'Vatican');
//
$posts = cg_autopost_add_post($token, 'http://conggiao.info/hoan-vu-n-810', 'Tin Giáo Hội hoàn vũ');
//
//
/////Tong giao phan ha noi
$token = refreshToken('tonggiaophanhanoi.org', 'bmn#P8iT2mCt)Ao&lBL%cnY6');
$posts = tgp_autopost_add_post($token, 'https://tonggiaophanhanoi.org/tin-tuc/tin-giao-phan/228-tin-tong-hop', 'Tin Giáo Phận Hà Nội');
//
$posts = tgp_autopost_add_post($token, 'https://tonggiaophanhanoi.org/tin-tuc/tin-giao-phan/', 'Tin Giáo Phận Hà Nội');

