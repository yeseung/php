<?php
//header("Content-type:application/json; charset=utf-8");
header("content-type:text/html; charset=utf-8");
include_once('./common.php');


//echo rand();

include "./Snoopy.class.php";
$snoopy = new Snoopy;
$snoopy->referer = 'https://his.kmc.or.kr';

for ($i = 1; $i <= 14; $i++) {

    $url = 'https://his.kmc.or.kr/address/church?search_ac=12&page='.$i;
    
    $snoopy->fetch($url);
    $html = $snoopy->results;

    // 비고 이후의 tbody 테이블 영역만 추출
    $html = explode('<th style="min-width:100px;">비고</th>', $html)[1];
    $html = explode('<div class="d-flex justify-content-center">', $html)[0];

    // 각 교회 <tr> 태그만 추출
    preg_match_all('/<tr[^>]*class="church-row"[^>]*>(.*?)<\/tr>/s', $html, $matches);

    foreach ($matches[1] as $row) {
        preg_match_all('/<td[^>]*>(.*?)<\/td>/s', $row, $tds);
        $columns = $tds[1];

        // 교회명은 4번째, 전화번호는 6번째, 주소는 7번째 열에 있음
        $church = trim(strip_tags($columns[3]));
        $church = preg_replace('/\s*\(\d+\)/', '', $church);
        $name = trim(strip_tags($columns[4]));
        $tel = trim(strip_tags($columns[5]));
        $addr = trim(strip_tags($columns[6]));

        if ($addr!=""){
    //        echo "교회명: $church\n";
    //        echo "이름: $name\n";
    //        echo "전화번호: $tel\n";
    //        echo "주소: $addr\n";
    //        echo "--------------------------\n";

            echo 'INSERT INTO church1 (church, name, tel, addr) VALUES ("'.$church.'", "'.$name.'", "'.$tel.'", "'.$addr.'");<br>';
        }
    }

    echo "<br>";
}

//echo $html;






