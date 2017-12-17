<?php

require './restful_api/handle.php';
require './restful_api/restful_api.php';

class api extends restful_api {

	function __construct(){
		parent::__construct();
	}

	function bayes(){
		if ($this->method == 'GET'){
			// Hãy viết code xử lý LẤY dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
		elseif ($this->method == 'POST'){

			// Hãy viết code xử lý dữ liệu ở đây		
			$test=$_POST["test"];
			//			
			$input_reg=reg($test); // cắt kí tự đặt biệt
			$input_stopword=cut_stopword($input_reg); // cắt stopword
			$input_strtolower=mb_strtolower($input_stopword); // chuyển về chữ thường 
			$input_trim=trim($input_strtolower); // cắt khoảng trắng đầu cuối
			$input_cutwords=(explode(" ",$input_trim));
			// 
			$search1 = 'classifier 1';
			$search2 = 'classifier 2';
			$search3 = 'classifier 3';
			$search4 = 'classifier 4';
			$search5 = 'classifier 5';
			//mở file	   
			$lines = file('./training/classifier.txt');
			//lấy giá trị của classifier_1
			$classifier_1='';
			foreach($lines as $line)
			{
				if(strpos($line, $search1) !== false) {
					$liner=explode(': ',$line);
					$classifier_1.= $liner[1];
				}

			}
			//lấy giá trị của classifier_2
			$classifier_2='';
			foreach($lines as $line)
			{
			if(strpos($line, $search2) !== false) {
				$liner=explode(': ',$line);
				$classifier_2.= $liner[1];
				}

			}
			//lấy giá trị của classifier_3
			$classifier_3='';
			foreach($lines as $line)
			{
			if(strpos($line, $search3) !== false) {
				$liner=explode(': ',$line);
				$classifier_3.= $liner[1];
				}

			}
			//lấy giá trị của classifier_4
			$classifier_4='';
			foreach($lines as $line)
			{
			if(strpos($line, $search4) !== false) {
				$liner=explode(': ',$line);
				$classifier_4.= $liner[1];
				}

			}
			//lấy giá trị của classifier_5
			$classifier_5='';
			foreach($lines as $line)
			{
			if(strpos($line, $search5) !== false) {
				$liner=explode(': ',$line);
				$classifier_5.= $liner[1];
				}

			}
			//tính tần suất của input.
			$read_keyword = file_get_contents("./training/keyword.txt"); //đọc nội dung file
			$keywords = explode("\n", $read_keyword);	
			$temp_total_keywords=array();
			foreach($keywords as $keyword){
				$total_keyword=0; 
				foreach($input_cutwords as $input_cutword){
					if($keyword==$input_cutword){
						$total_keyword+=1;
					}
				}
				array_push($temp_total_keywords,$total_keyword);  
			}    
			//end tần suất của input.

			//lấy giá trị của lamda1 (Multinomial Naive Bayes)
			$read_lamda1 = file_get_contents("./training/lamda1.txt"); 
			$lamda1 = explode("\n", $read_lamda1);
			//lấy giá trị của lamda2
			$read_lamda2 = file_get_contents("./training/lamda2.txt"); 
			$lamda2 = explode("\n", $read_lamda2);
			//lấy giá trị của lamda3
			$read_lamda3 = file_get_contents("./training/lamda3.txt"); 
			$lamda3 = explode("\n", $read_lamda3);
			//lấy giá trị của lamda4
			$read_lamda4 = file_get_contents("./training/lamda4.txt"); 
			$lamda4 = explode("\n", $read_lamda4);
			//lấy giá trị của lamda5
			$read_lamda5 = file_get_contents("./training/lamda5.txt"); 
			$lamda5 = explode("\n", $read_lamda5);
			//get lamda

			//kq số sao 1
			$tong1=1;
			for($i=0;$i<count($keywords) ;$i++){
				if($temp_total_keywords[$i]!=0){
					$tong1*=pow($lamda1[$i],$temp_total_keywords[$i]);   
				}
			}
			$result_1= $tong1*$classifier_1;
			//kq số sao 2
			$tong2=1;
			for($i=1;$i<count($keywords) ;$i++){
				if($temp_total_keywords[$i]!=0){
					$tong2*=pow($lamda2[$i],$temp_total_keywords[$i]);   
				}
			}
			$result_2= $tong2*$classifier_2;
			//kq số sao 3
			$tong3=1;
			for($i=1;$i<count($keywords) ;$i++){
				if($temp_total_keywords[$i]!=0){
					$tong3*=pow($lamda3[$i],$temp_total_keywords[$i]);   
				}
			}
			$result_3= $tong3*$classifier_3;
			//kq số sao 4
			$tong4=1;
			for($i=1;$i<count($keywords) ;$i++){
				if($temp_total_keywords[$i]!=0){
					$tong4*=pow($lamda4[$i],$temp_total_keywords[$i]);   
				}
			}
			$result_4= $tong4*$classifier_4;
			//kq số sao 5
			$tong5=1;
			for($i=1;$i<count($keywords) ;$i++){
				if($temp_total_keywords[$i]!=0){
					$tong5*=pow($lamda5[$i],$temp_total_keywords[$i]);   
				}
			}
			$result_5= $tong5*$classifier_5;

			//tim số sao lớn nhất
			$mang=array('1'=>log($result_1),'2'=>log($result_2),'3'=>log($result_3),
			'4'=>log($result_4),'5'=>log($result_5));

			$maxsao; //số sao lớn nhất
			foreach($mang as $key => $item) {
				if(max($mang)==$item){	
					//echo $key."=>".$item."<br>";								
					$maxsao=$key;
				}  				
			}	
			//tính phần trắm
			$sum5star =$result_1+$result_2+$result_3+$result_4+$result_5;
			$percent_star1=round(($result_1/$sum5star)*100,2);
			$percent_star2=round(($result_2/$sum5star)*100,2);
			$percent_star3=round(($result_3/$sum5star)*100,2);
			$percent_star4=round(($result_4/$sum5star)*100,2);
			$percent_star5=round(($result_5/$sum5star)*100,2);

			//
			$myObj = new stdClass();
			$myObj->danhgia = $maxsao;
			$myObj->binhluan = $test;
			$myObj->phantram=array('sao1'=>$percent_star1.'%','sao2'=>$percent_star2.'%','sao3'=>$percent_star3.'%','sao4'=>$percent_star4.'%','sao5'=>$percent_star5.'%');

			//
			echo json_encode($myObj,JSON_UNESCAPED_UNICODE);

		}
		elseif ($this->method == 'PUT'){
			// Hãy viết code xử lý CẬP NHẬT dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
		elseif ($this->method == 'DELETE'){
			// Hãy viết code xử lý XÓA dữ liệu ở đây
			// trả về dữ liệu bằng cách gọi: $this->response(200, $data)
		}
	}
}

$user_api = new api();


?>