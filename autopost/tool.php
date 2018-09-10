<?php
/**
 * Created by PhpStorm.
 * User: bachnguyen
 * Date: 11/07/2017
 * Time: 08:25
 */
require_once 'simple_html_dom.php';


function getHtml($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getPostUrl($output)
{
    $html = str_get_html($output);
    $main_content = $html->find('#main-content')[0];
    $item_lists = $main_content->find('.item-list');
    $item = $item_lists[0];
    $title = $item->find('.post-box-title')[0];
    $a = $title->find('a')[0];
    $url = $a->href;
    return $url;
}

function getImgUrl($output)
{
    $html = str_get_html($output);
    $main_content = $html->find('#main-content')[0];
    $item_lists = $main_content->find('.item-list');
    $item = $item_lists[0];
    $thumnail = $item->find('.post-thumbnail')[0];
    $img = $thumnail->find('img')[0];
    $img_url = $img->src;
    return $img_url;
}

function getPost($url)
{
    $output = getHtml($url);
    $html = str_get_html($output);
    $post = $html->find('#the-post')[0];
    $title = $post->find('h1.name')[0];
    $title = $title->plaintext;
    $content = $post->find('div.entry')[0];
    $content = $content->innertext;
    $post = [
        'title' => $title,
        'content' => $content,
    ];
    return $post;

}

function upload_image($path, $token)
{
    $request_url = 'http://gxdangian.org/wp-json/wp/v2/media';

    $image = file_get_contents($path);
    $filename = basename($path);
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $image);
    finfo_close($finfo);
    $api = curl_init();

    //set the url, POST data
    curl_setopt($api, CURLOPT_URL, $request_url);
    curl_setopt($api, CURLOPT_POST, 1);
    curl_setopt($api, CURLOPT_POSTFIELDS, $image);
    curl_setopt($api, CURLOPT_HTTPHEADER, array(
        'Content-Type: ' . $mime_type,
        'Content-Disposition: attachment; filename="' . basename($path) . '"',
        'Authorization: Bearer ' . $token,
    ));
    curl_setopt($api, CURLOPT_RETURNTRANSFER, 1);

    //execute post
    $result = curl_exec($api);

    //close connection
    curl_close($api);

    return $result;
}

function Generate_Featured_Image($image_url, $token)
{
    $result = upload_image($image_url, $token);
    $result = json_decode($result, true);
    return $result['id'];

}


function checkPostExist($title, $content = '')
{
    if ($content != '') {
        $check = post_exists($title, $content);
    } else {
        $check = post_exists($title);
    }
    return $check;

}


function autopost_add_post($token,$linkCategory, $category)
{
    $catID = doPost('dangian/v1/getcat',['cat'=>$category],$token);
    $output = getHtml($linkCategory);
    $postUrl = getPostUrl($output);
    $postThumbnailImgUrl = getImgUrl($output);
    $imgid = Generate_Featured_Image($postThumbnailImgUrl, $token);
    $post = getPost($postUrl);
    $post['status'] = 'publish';
    $post['categories'] = $catID;
    $post['featured_media'] = $imgid;
    return $post;
}

/**
 * @param $output
 * @return mixed
 */
function cg_getPostUrl($output)
{
    $html = str_get_html($output);
    $main_content = $html->find('div.main_news')[0];
    $url = $main_content->find('a.view_detail')[0]->href;
    return $url;
}

function cg_getThumbnailImg($output)
{
    $html = str_get_html($output);
    $main_content = $html->find('div.main_news')[0];
    $img = $main_content->find('a.img img')[0]->src;
    return $img;
}

function cg_getPost($url)
{
    $output = getHtml($url, false);
    $html = str_get_html($output);
    $main = $html->find('div.nwsdetail')[0];
    $title = $main->find('h3.title')[0]->innertext;
    $img = $main->find('img');
    $main->find('span.time')[0]->innertext = '';
    $main->find('h3.title')[0]->innertext = '';
    foreach ($img as $key => $i) {
        $href = $i->src;
        $img[$key]->src = 'http://conggiao.info' . $href;

    }
    $post = [
        'title' => $title,
        'content' => $main->innertext,
    ];
    return $post;
}

function cg_autopost_add_post($token,$linkCategory, $category)
{
    $catID = doPost('dangian/v1/getcat',['cat'=>$category],$token);
    $output = getHtml($linkCategory);
    $postUrl = cg_getPostUrl($output);
    $postThumbnailImgUrl = cg_getThumbnailImg($output);
    $imgid = Generate_Featured_Image($postThumbnailImgUrl, $token);
    $post = cg_getPost($postUrl);
    $post['status'] = 'publish';
    $post['categories'] = $catID;
    $post['featured_media'] = $imgid;
    return $post;
}

function tgp_getPostUrl($categoryUrl)
{
    $output = getHtml($categoryUrl);
    $html = str_get_html($output);
    $main_content = $html->find('div.column-1')[0];
    $a = 'https://tonggiaophanhanoi.org' . $main_content->find('a')[0]->href;
    return $a;
}

function tgp_getPost($url)
{
    $output = getHtml($url);
    $html = str_get_html($output);
    $main = $html->find('div.item-page')[0];
    $thumbnail = $main->find('div.item-image')[0];
    $thumbnailimg = $thumbnail->find('img')[0]->src;
    $thumbnailimg = 'https://tonggiaophanhanoi.org' . $thumbnailimg;
    $title = $main->find('h2')[0]->innertext;
    $content = $main->find("div[itemprop='articleBody']")[0];
    $img = $content->find('img');
    foreach ($img as $key => $i) {
        $href = $i->src;
        $img[$key]->src = 'https://tonggiaophanhanoi.org' . $href;

    }
    $a = $content->find('a');
    foreach ($a as $key => $item) {
        $a[$key]->href = '#';
        $a[$key]->target = '';
        $a[$key]->tag = 'div';
        $a[$key]->style = 'color: black';
    }
    $post = [
        'title' => $title,
        'content' => $content->innertext,
        'thumbnail' => $thumbnailimg
    ];
    return $post;

}

function tgp_autopost_add_post($token,$linkCategory, $category)
{
    $catID = doPost('dangian/v1/getcat',['cat'=>$category],$token);
    $output = getHtml($linkCategory);
    $postUrl = tgp_getPostUrl($linkCategory);
//    $postThumbnailImgUrl = getThumbnailImg($output);
//    $imgid = Generate_Featured_Image($postThumbnailImgUrl, $token);
    $post = tgp_getPost($postUrl);
    $post['status'] = 'publish';
    $post['categories'] = $catID;
    return $post;
}

function post($token, $post)
{
    $exist = doPost('dangian/v1/checkexist',['title'=>$post['title']],$token);
    if(!$exist){
        $output = doPost('wp/v2/posts',$post,$token);
    }else{
        return -1;
    }
    return $output;
}

function getToken($username, $password)
{
    $url = 'http://gxdangian.org/wp-json/jwt-auth/v1/token';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['username' => $username, 'password' => $password]);
    $output = curl_exec($ch);
    curl_close($ch);
    $token = json_decode($output, true);
    if (isset($token['token'])) {
        return $token['token'];
    } else {
        return '0';
    }

}

function validateToken($token)
{
    $url = 'http://gxdangian.org/wp-json/jwt-auth/v1/token/validate';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $token,
    ));
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    if ($output['data']['status'] == '200') {
        return 1;
    } else {
        return 0;
    }
}

function refreshToken()
{
    $base = __DIR__;
    if (is_file($base . '/token')) {
        $file2 = fopen($base . '/token', 'rb+');
        $token = fread($file2, 1000);
        if ($token != '') {
            if (!validateToken($token)) {
                $file = fopen($base . '/token', 'wb+');
                $token = getToken('admin', 'Melentroi1508');
                fwrite($file, $token);
                return $token;
            } else {
                return $token;
            }
        } else {
            $file = fopen($base . '/token', 'wb+');
            $token = getToken('admin', 'Melentroi1508');
            fwrite($file, $token);
            return $token;
        }
    } else {
        $file = fopen($base . '/token', 'wb+');
        $token = getToken('admin', 'Melentroi1508');
        fwrite($file, $token);
        return $token;
    }


}

function doPost($url,$param,$token){
    $base = 'http://gxdangian.org/wp-json/';
    $url = $base.$url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $token,
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}