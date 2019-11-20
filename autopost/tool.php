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

function getPostUrl($output, $url = '')
{
    $html = str_get_html($output);
    $main_content = $html->find('div.post-listing')[0];
    $item_lists = $main_content->find('article.item-list');
    $urls = [];
    foreach ($item_lists as $item) {
        $title = $item->find('h2.post-box-title')[0];
        $a = $title->find('a')[0];
        $url = $a->href;
        $urls[] = $url;
    }
    return $urls;
}

function getImgUrl($output, $index = 0)
{
    $html = str_get_html($output);
    $main_content = $html->find('#main-content')[0];
    $item_lists = $main_content->find('.item-list');
    $item = $item_lists[$index];
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
    $content->find('div.share-post')[0]->outertext = '';
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


function autopost_add_post($token, $linkCategory, $category)
{
    $catID = doPost('dangian/v1/getcat', ['cat' => $category], $token);
    $output = getHtml($linkCategory);
    $postUrls = getPostUrl($output, $linkCategory);
    $posts = [];
    foreach ($postUrls as $index => $postUrl) {
        $postThumbnailImgUrl = getImgUrl($output, $index);
        $imgid = Generate_Featured_Image($postThumbnailImgUrl, $token);
        $post = getPost($postUrl);
        $post['status'] = 'publish';
        $post['categories'] = $catID;
        $post['featured_media'] = $imgid;
        $posts[] = $post;
    }
    return $posts;
}

/**
 * @param $output
 * @return mixed
 */
function cg_getPostUrl($output)
{
    $html = str_get_html($output);
    $main_content = $html->find('div.main_news')[0];
    $urls = [];
    foreach ($main_content->find('a.view_detail') as $content) {
        $url = $content->href;
        $urls[] = $url;
    }

    return $urls;
}

function cg_getThumbnailImg($output, $index = 0)
{
    $html = str_get_html($output);
    $main_content = $html->find('div.main_news')[0];
    $img = $main_content->find('a.img img')[$index]->src;
    return $img;
}

function cg_getPost($url)
{
    $output = getHtml($url);
    $html = str_get_html($output);
    $main = $html->find('div.nwsdetail')[0];
    $title = $main->find('h3.title')[0]->innertext;
    $img = $main->find('img');
    $main->find('span.time')[0]->innertext = '';
    $main->find('h3.title')[0]->innertext = '';
    foreach ($main->find('p') as $key => $p) {
        $plantext = $p->plaintext;
        if (empty(trim($plantext))) {
           unset($main->find('p')[$key]);
        } else {
            $p->innertext = $plantext;
        }

    }
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

function cg_autopost_add_post($token, $linkCategory, $category)
{
    $catID = doPost('dangian/v1/getcat', ['cat' => $category], $token);
    $output = getHtml($linkCategory);
    $postUrls = cg_getPostUrl($output);
    $posts = [];
    foreach ($postUrls as $index => $postUrl) {
        $postThumbnailImgUrl = cg_getThumbnailImg($output, $index);
        $imgid = Generate_Featured_Image($postThumbnailImgUrl, $token);
        $post = cg_getPost($postUrl);
        $post['status'] = 'publish';
        $post['categories'] = $catID;
        $post['featured_media'] = $imgid;
        $posts[] = $post;
    }
    return $posts;
}

function tgp_getPostUrl($categoryUrl)
{
    $output = getHtml($categoryUrl);
    $html = str_get_html($output);
    $main_content = $html->find('div.item');
    $arr = [];
    foreach ($main_content as $content) {
        $a = 'https://tonggiaophanhanoi.org' . $content->find('a')[0]->href;
        $arr[] = $a;
    }
    return $arr;
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

function tgp_getThumbnailImg($output, $postIndex = 0)
{
    $html = str_get_html($output);
    $main_content = $html->find('div.item-image')[$postIndex];
    $img = $main_content->find('a img');
    if (isset($img[0])) {
        $img = 'https://tonggiaophanhanoi.org' . $img[0]->src;
        return $img;
    }
    return false;
}

function tgp_autopost_add_post($token, $linkCategory, $category)
{
    $catID = doPost('dangian/v1/getcat', ['cat' => $category], $token);
    $output = getHtml($linkCategory);
    $postUrls = tgp_getPostUrl($linkCategory);
    $posts = [];
    foreach ($postUrls as $index => $postUrl) {
        $postThumbnailImgUrl = tgp_getThumbnailImg($output, $index);
        if ($postThumbnailImgUrl) {
            $imgid = Generate_Featured_Image($postThumbnailImgUrl, $token);
        } else {
            $imgid = 2922;
        }
        $post = tgp_getPost($postUrl);
        $post['status'] = 'publish';
        $post['categories'] = $catID;
        $post['featured_media'] = $imgid;
        $posts[] = $post;
    }
    return $posts;
}

function post($token, $post)
{
    $exist = doPost('dangian/v1/checkexist', ['title' => $post['title']], $token);
    if (!$exist) {
        $output = doPost('wp/v2/posts', $post, $token);
    } else {
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

function refreshToken($username, $password)
{
    $base = __DIR__;
    if (is_file($base . '/token')) {
        $file2 = fopen($base . '/token', 'rb+');
        $token = fread($file2, 1000);
        if ($token != '') {
            if (!validateToken($token)) {
                $file = fopen($base . '/token_' . $username, 'wb+');
                $token = getToken($username, $password);
                fwrite($file, $token);
                return $token;
            } else {
                return $token;
            }
        } else {
            $file = fopen($base . '/token_' . $username, 'wb+');
            $token = getToken($username, $password);
            fwrite($file, $token);
            return $token;
        }
    } else {
        $file = fopen($base . '/token_' . $username, 'wb+');
        $token = getToken($username, $password);
        fwrite($file, $token);
        return $token;
    }


}

function doPost($url, $param, $token)
{
    $base = 'http://gxdangian.org/wp-json/';
    $url = $base . $url;
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