<?php

$routes['trang-chu.html'] = 'index';

// $routes['tin-tuc'] = 'post_list';
$routes['danh-sach-tin-tuc/(.+?).html'] = 'post_list/$1';
$routes['tin-tuc/(.+?).html'] = 'post/$1';

$routes['tim-kiem.html'] = 'search';

// $routes['san-pham'] = 'product_list';
$routes['danh-sach-san-pham/(.+?).html'] = 'product_list/$1';
$routes['san-pham/(.+?).html'] = 'product/$1';

$routes['lien-he.html'] = 'contact';
$routes['dai-ly.html'] = 'agent';

$routes['admin'] = 'admin/index';

$routes['bai-viet/(.+?).html'] = 'article/$1';

$routes['hosting.html'] = 'hosting';

$routes['danh-sach-du-an/(.+?).html'] = 'project_list/$1';
$routes['du-an/(.+?).html'] = 'project/$1';

$routes['gio-hang.html'] = 'cart';