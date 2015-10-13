<?php

set_time_limit ( 3000 );

include_once('simple_html_dom.php');

error_reporting(E_ERROR);

$f = file_get_html('http://brw-shop.by/catalog/');

$main_categories_link = $main_categories_name = $main_categories_pages = $goods_link = $goods_name  = array();

//$name = reset($nam)->plaintext.'<br>';
mkdir('/catalog');


foreach($f->find('div.sidebar-menu__po__prim a') as $cat) {           //get categories link
		array_push($main_categories_link, 'http://www.brw-shop.by' . $cat->href);
	}

foreach($f->find('div.sidebar-menu__po__prim div.sidebar-menu-nav__item__text') as $cat) {           //get categories name
		$cat_name = iconv('UTF-8', 'Windows-1251', $cat->plaintext);
		$cat_name = trim($cat_name);
		array_push($main_categories_name, $cat_name);
		mkdir('/catalog/'.$cat_name);
	}




 $i=0; //category number

   foreach ($main_categories_link as $category_link) {
   	$f_additional = file_get_html($category_link);

   	foreach($f_additional->find('div.novelty-content__item__title--no-option a') as $cat) {           //get subcategories link
   		$collection_link = 'http://www.brw-shop.by' . $cat->href;
   		$cat_name = iconv('UTF-8', 'Windows-1251', $cat->plaintext);
   		$cat_name = trim($cat_name);
   		mkdir('/catalog/'.$main_categories_name[$i].'/'.$cat_name);

   		$f_additional = file_get_html($collection_link);
   		$solution_array = array();
   		foreach($f_additional->find('div.complete-problem__item div.complete-problem-item__img a') as $solution) {  //get solution link
   			array_push($solution_array, 'http://www.brw-shop.by'.$solution->href);	
   		}

   		$solution_array = array_unique($solution_array);


   		$file_good = fopen('/catalog/'.$main_categories_name[$i].'/'.$cat_name.'/1.ќписание.txt', 'wt+');
         fwrite($file_good, $collection_link."\r\n\r\n");

   		$field = $f_additional->find('div#content div.content-box-title--no-top span.content-box-title__name__title');
   			$good_name = iconv('UTF-8', 'Windows-1251', reset($field)->plaintext);
   			$good_name = trim($good_name);
   			fwrite($file_good, $good_name."\r\n\r\n÷вет:\r\n\r\n");

         foreach($f_additional->find('div.ready-made-solution-box__data__color-pick__box__cont span.color-pick__box__cont__text') as $field) {  
            $field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
            fwrite($file_good, $field_string."\r\n");
         }

   		foreach($f_additional->find('div.ready-made-solution-box__data__price-box__price span') as $field) {  
   			$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
   			$field_string = str_replace (" руб.", "", $field_string);
            $field_string = str_replace (".", " ", $field_string);
            fwrite($file_good, "\r\n÷ена:  ".$field_string."\r\n\r\n\r\n");
   		}

   		$field = $f_additional->find('div.article-one div.description_min',0)->children(0);
   			$field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
   			$field_string = strip_tags($field_string);
   			$field_string = trim($field_string); 
   			fwrite($file_good, $field_string."\r\nЁлементы решени€:\r\n\r\n\r\n");

         foreach($f_additional->find('div.novelty-content__item__enter div.novelty-content__item__title a') as $field) {  
            $field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
            fwrite($file_good, $field_string."                   ");

            $field_string = 'http://www.brw-shop.by' . $field->href;
            fwrite($file_good, $field_string."\r\n");
         }

         foreach($f_additional->find('div.ready-made-solution-box__image a') as $field) {
            $img_link = 'http://www.brw-shop.by' . $field->href;
            $img_path = '/catalog/'.$main_categories_name[$i].'/'.$cat_name.'/1.Image.jpg';  
            copy ($img_link , $img_path);
         }

         fclose($file_good);



// ********************  GO TO ANOTHER SOLUTIONS

      $q = 2; //number solution

      for ($sol=0; $sol < count($solution_array) ; $sol++) { 
         $f_additional = file_get_html($solution_array[$sol]);

         $file_good = fopen('/catalog/'.$main_categories_name[$i].'/'.$cat_name.'/'.$q.'.ќписание.txt', 'wt+');
         fwrite($file_good, $collection_link."\r\n\r\n");

         $field = $f_additional->find('div#content div.content-box-title--no-top span.content-box-title__name__title');
            $good_name = iconv('UTF-8', 'Windows-1251', reset($field)->plaintext);
            $good_name = trim($good_name);
            fwrite($file_good, $good_name."\r\n\r\n÷вет:\r\n\r\n");

         foreach($f_additional->find('div.ready-made-solution-box__data__color-pick__box__cont span.color-pick__box__cont__text') as $field) {  
            $field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
            fwrite($file_good, $field_string."\r\n");
         }

         foreach($f_additional->find('div.ready-made-solution-box__data__price-box__price span') as $field) {  
            $field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
            $field_string = str_replace (" руб.", "", $field_string);
            $field_string = str_replace (".", " ", $field_string);
            fwrite($file_good, "\r\n÷ена:  ".$field_string."\r\n\r\n\r\n");
         }

         $field = $f_additional->find('div.article-one div.description_min',0)->children(0);
            $field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
            $field_string = strip_tags($field_string);
            $field_string = trim($field_string); 
            fwrite($file_good, $field_string."\r\nЁлементы решени€:\r\n\r\n\r\n");

         foreach($f_additional->find('div.novelty-content__item__enter div.novelty-content__item__title a') as $field) {  
            $field_string = iconv('UTF-8', 'Windows-1251', $field->plaintext);
            fwrite($file_good, $field_string."                   ");

            $field_string = 'http://www.brw-shop.by' . $field->href;
            fwrite($file_good, $field_string."\r\n");
         }

         foreach($f_additional->find('div.ready-made-solution-box__image a') as $field) {
            $img_link = 'http://www.brw-shop.by' . $field->href;
            $img_path = '/catalog/'.$main_categories_name[$i].'/'.$cat_name.'/'.$q.'.Image.jpg';  
            copy ($img_link , $img_path);
         }



         $q++;
     }






// ******************    END SOLUTIONS
	   }

   	$i++;
   }

echo 'DONE';
?>