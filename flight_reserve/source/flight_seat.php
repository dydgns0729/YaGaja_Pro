<?php 

include '../../common_lib/createLink_db.php';
include './create_seat_state.php';

session_start();

if(!empty($_SESSION['id'])){
    $id = $_SESSION['id'];
}else{
    $id = "?";
}

if(!empty($_GET['fly'])){
    $fly = $_GET['fly'];
}else{
    $fly = "?";
} 

if(!empty($_GET['num'])){
    $num = $_GET['num'];
}else{
    $num = "?";
}

if(!empty($_GET['sapnum'])){
    $sapnum = $_GET['sapnum'];
}else{
    $sapnum = "?";
}

if(!empty($_GET['bapnum'])){
    $bapnum = $_GET['bapnum'];
}else{
    $bapnum = "?";
}

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>TICKETING</title>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<link type="text/css" rel="stylesheet" href="../css/ticket1.css?v=3">
<link type="text/css" rel="stylesheet" href="../../common_css/index_css3.css?v=2">
<link rel="stylesheet" href="//code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
<form name="reserve_form" method="post">
<script type="text/javascript">

//자바 스크립트에서 php변수를 사용할 떄 
var num = <?=json_encode($num)?>;
var count = 0;

$(document).ready(function() {
	   $("button").click(function(){		//버튼 클릭시
		
	      var id = $(this).attr("id");		//id값 //value값
	      var value = $(this).attr("value");	
	      
	      if(document.getElementById(id).style.backgroundColor == "skyblue" && (count >= 0 && count <= num)){		//선택좌석이고 인원수만큼 증가되게 됨
	      	if(count == num){		//카운트 인원수와 같아지면 //선택좌석취소, 카운트 --
	      		if(document.getElementById(id).style.backgroundColor == "skyblue"){		
	      			document.getElementById(id).style.backgroundColor = "white"; 
	      			count--;
	      		}
	      	}
	      	document.getElementById(id).style.backgroundColor = "white"; //선택해제
	      	
	      	//전달한 value 누적값을 remove
	      	
	      	 $.ajax({
  	            type : "post",
  	            url : "reserve.php",
  	            data : {value : value},
  	            
  	            error : function(){alert('통신실패2!!');},
  	            success : function(data){document.getElementById(value).remove();}
  	            });
	      	
	      	if(count != 0){	//카운트가 0이 아니면 카운트 --;	//선택해제 시 카운트 감소
	      		count--;
	      	}
	      	
	      }else if(document.getElementById(id).style.backgroundColor != "skyblue" && (count >= 0 && count <= num)){	//선택좌석이 아니고 count가 총인원수를 넘지않음
	    	if(count == num){return;} //인원수까지만 선택하도록 함
	    	
	      	document.getElementById(id).style.backgroundColor = "skyblue"; 	//좌석선택시 색상변경
	      	if(count != num){count++;} //count가 인원수와 같아지지 않았다면 카운트 ++; //맨처음클릭시 선택하면서 카운트 증가
	      	
	      //전달한 value 누적값을 append <li>
			//hidden 타입으로 choice_seat[]에 value을 담아서 배열로 전달	      	
	      	$.ajax({
                type : "post",
                url : "reserve.php",
                data : {value : value},		//네임 : 전달할 데이터() --- 여기서 value = $i
                error : function(){alert('통신실패1!!');},
                success : function(data){
                      $("#no").append("<li style='list-style:none; display:inline;' id='"+value+"'><input type='hidden' value='"+value+"' name='choice_seat[]'> "+value+"</li>") //li 태그는 value값이 없다함
                      }
                });   
	  		}
	   });
	});	      


function input_check(url){		//좌석예약하기
	
	reserve_form.action = url;
    reserve_form.submit();  
	
}

</script>

</head>
<body>
<header>
	<?php include_once '../../common_lib/top_login2.php';?>
</header>
<nav id="top">
	<?php include_once '../../common_lib/main_menu2.php';?>
</nav><br><br><br><br>

<h1 style="margin:0 auto; text-align: center">FLIGHT TICKETING</h1><br>
<div id="ticket_box3">
<p>
    <br><hr id="hr1"><br><br>
    &nbsp;1. 여정 선택  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    2. 항공편 선택  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    3. 결과 조회  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    4. 좌석 확인  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <p>
<table width=550 border=0 height=18 cellspacing=5 cellpadding=0>
<tr>
<?php
   echo "
        <td width='25%' bgcolor= '#dddddd'></td>
        <td width='25%' bgcolor='#dddddd' height=5></td>
        <td width='25%' bgcolor='#dddddd' height=5></td>
        <td width='25%' bgcolor='gray' height=5></td>";
?>
</tr>
</table><br>

<hr id="hr1">
<div id="select_ticket"><span style='font-size:15pt;'><br><br>좌석 배치</span>
    <div style="text-align:right;">
        <span style='font-size:12pt;'>예매불가 </span><input type='button' name='seat' value='' style='width:15pt;height:15pt; background: gray'>
        <span style='font-size:12pt;'>예매가능 </span><input type='button' name='seat' value='' style='width:15pt;height:15pt; background: white'>
        <span style='font-size:12pt;'>선택좌석 </span><input type='button' name='seat' value='' style='width:15pt;height:15pt; background: skyblue'>&nbsp;&nbsp;
    </div>
</div>

<img src="../image/ap_seat1.png" width='80%'>		<!-- 위  -->
<div id="seat_1"><br>

<?php

    //seat_state + flight_one_way 테이블에서 항공번호가 선택한 출발항공번호인 레코드를 모두 검색
    $sql = "select * from seat_state s inner join flight_one_way f on s.flght_ap_num = f.flght_ap_num where s.flght_ap_num ='$sapnum' ";
    
    $result = mysqli_query($con,$sql) or die("실패원인1: ".mysqli_error($con));
    
    while($row = mysqli_fetch_array($result)){
        $choice_seat = $row[choice_seat];       //좌석정보를 가져옴 /1/2
        $str .= $choice_seat;      //예약된 좌석번호 누적해서 변수에 저장 ex) "/4/5/6" + "/1/2/3"+ "/65/78"
    }
    
    $choice_seat = explode("/", $str);     // "/"를 기준으로 분리해서 배열에 저장 ex) $c[0]="", $c[1]="4", $c[2]="5", $c[2]="6"
     
    foreach ($choice_seat as $key => $val) {
        $seat[$val] = $val;    //배열에 원소수만큼 돌면서 $seat[4] = 4,  $seat[5] = 5, $seat[65] = 65
                               //[val]인덱스에 $val값을 저장
    }
 
for($i=1; $i<=100; $i++){   //좌석100개
    if($seat[$i]==$i){      //좌석정보 배열변수 seat[$i]인덱스와 $i 같은 경우   예약된 좌석이므로 disabled
        echo "<button type='button' id='seat$i' class='seat' value='$i' style='background-color:gray;' disabled>-</button>";
    }else{
        echo "<button type='button' id='seat$i' class='seat' value='$i' style='background-color:white; '>$i</button>";
    }
    
    if($i== 13 || $i== 38 || $i== 63 || $i== 88){
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    if($i%50 == 0){
        echo "<br><br>";
    }
}


?>

</div><br>

<img src="../image/ap_seat2.png" style="width:80%; float:left;"> <!-- 아래  -->

<div id="seat_info" style="margin-top:-300px; margin-left:800px; float:none;">
    <div id="select_ticket"><span style='font-size:15pt;'>좌석 정보</span><br><br><br></div>
    <div>선택가능한 좌석수 : <?= $num ?> 개</div>선택한 좌석 번호 : 
    
<?php 
echo "<div id='no'></div>";

?>
    </form>
</div>

<div style="text-align:center; float:left;">
    <input type="button" id="select_ok"  style="width:100px; height:36px;" value="좌석 예약하기"
    onclick="input_check('reserve.php?num=<?=$num?>&fly=<?=$fly?>&sapnum=<?=$sapnum?>&bapnum=<?=$bapnum?>')">
</div>

</div><br><br><br><br>

<footer>
<?php include_once '../../common_lib/footer2.php';?>
</footer>

</body>
</html>




