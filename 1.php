<?php

set_time_limit ( 3000 );

include_once('simple_html_dom.php');

error_reporting(E_ERROR);

$f = file_get_html(''); //for example http://brw-shop.by/catalog/

$main_categories_link = $main_categories_name = $main_categories_pages = $goods_link = $goods_name  = array();

//$name = reset($nam)->plaintext.'<br>';
mkdir('/catalog');


foreach($f->find('div.sidebar-menu__po__category a') as $cat) {           //get categories link
		array_push($main_categories_link, 'http://www.brw-shop.by' . $cat->href);
	}

foreach($f->find('div.sidebar-menu__po__category div.sidebar-menu-nav__item__text') as $cat) {           //get categories name
		$cat_name = iconv('UTF-8', 'Windows-1251', $cat->plaintext);
		$cat_name = trim($cat_name);
		array_push($main_categories_name, $cat_name);
		mkdir('/catalog/'.$cat_name);
	}


$i=0; //category number

  foreach ($main_categories_link as $category_link) {

  	$k = 1;  //good number
  	$f_additional = file_get_html($category_link);

  	$pages = $f_additional->find('div.page-nav-list__center a span');
	if (! end($pages)) $page = 0; else $page =array_pop($pages)->plaintext;
	array_push($page, $main_categories_pages);
	
	 $p=1; //page number

	 do{
	 //for ($p=2; $p<=$page; $p++) {  //page number

	  	foreach($f_additional->find('div.novelty-content__item__title a') as $good) {           //get goods
	  			$good_link = 'http://www.brw-shop.by' . $good->href;
	  			$good_name = iconv('UTF-8', 'Windows-1251', $good->plaintext);
	  			$good_name = trim($good_name);
	  			//str_replace ("/", "_", $good_name);
	 			array_push($goods_link, $good_link);
	 			array_push($goods_name, $good_name);


	 			$f_good = file_get_html($good_link);
	 			mkdir('/catalog/'.$main_categories_name[$i].'/'.$k);
	 			$file_good = fopen('/catalog/'.$main_categories_name[$i].'/'.$k.'/îïèñàíèå.txt', 'wt+');
	 			fwrite($file_good, $good_link."\r\n\r\n");
	 			fwrite($file_good, $good_name."\r\n\r\n");

	 			foreach($f_good->find('span.content-box-title--no-line__name') as $field) {
	 				$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);  
	 				fwrite($file_good, $field_string."\r\n");
	 			}

	 			foreach($f_good->find('div.ready-made-solution-box__data__cod') as $field) {
	 				$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);  
	 				fwrite($file_good, $field_string."\r\n");
	 			}
	 			

	 			$color = $f_good->find('span.color-pick__box__cont__text');
	 			$color_text = reset($color);
	 			$field_string = iconv('UTF-8', 'Windows-1251', $color_text->plaintext);  
	 			fwrite($file_good, 'Öâåò:    '.$field_string."\r\n");


	 			$price = $f_good->find('div.ready-made-solution-box__data__price-box__price span');
	 			$price_text = reset($price);
	 			$field_string = iconv('UTF-8', 'Windows-1251', $price_text->plaintext);  
	 			$field_string = str_replace (" ðóá.", "", $field_string);
	 			$field_string = str_replace (".", " ", $field_string);
	 			fwrite($file_good, 'Öåíà:  '.$field_string."\r\n\r\n\r\n");

	 			foreach($f_good->find('div ul li span') as $field) {
	 				$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
					$field_string = strip_tags($field_string);  
	 				fwrite($file_good, $field_string."\r\n");
	 			}

	 			foreach($f_good->find('div ul li font') as $field) {
	 				$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext); 
	 				$field_string = strip_tags($field_string); 
	 				fwrite($file_good, $field_string."\r\n");
	 			}

				foreach($f_good->find('blockquote li font') as $field) {
	 				$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);  
					$field_string = strip_tags($field_string); 
	 				fwrite($file_good, $field_string."\r\n");
	 			}

	 			fclose($file_good);
	 			
	 			$img = 1; //image number
				foreach($f_good->find('div.ready-made-solution-box__image__module div.mid a') as $field) {
	 				$img_link = 'http://www.brw-shop.by' . $field->href;
	 				$img_path = '/catalog/'.$main_categories_name[$i].'/'.$k.'/'.$img.'.jpg';  
					copy ($img_link , $img_path);
	 				$img++;
	 			}

	 			$k++;

	  	}

	  	 if ($page==0) break; else $f_additional = file_get_html($category_link.'?PAGEN_1='.++$p);

	 } while ($p<=$page);

  	$i++;
  }

echo 'DONE';
?>