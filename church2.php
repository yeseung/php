<?php
//header("Content-type:application/json; charset=utf-8");
header("content-type:text/html; charset=utf-8");
//include_once('./common.php');


//echo rand();

include "./Snoopy.class.php";
$snoopy = new Snoopy;
$snoopy->referer = 'http://kcm.kr';

for ($i = 901; $i <= 1225; $i++) {

    $url = 'http://kcm.kr/index.php?cat=65&u_sort=visit&u_order=desc&page='.$i;

    $snoopy->fetch($url);
    $html = $snoopy->results;

    $html = iconv("EUC-KR", "UTF-8", $html);

    $html = explode('<table width="100%" border=0><tr>', $html)[1];
    $html = explode('<!-- DIRECT BAR START-->', $html)[0];
    //echo $html;
    
    // 각 <tr> 태그 추출
    preg_match_all('/<tr>(.*?)<\/tr>/s', $html, $rows);

    foreach ($rows[1] as $row) {
        preg_match_all('/<td[^>]*>(.*?)<\/td>/s', $row, $cells);
        $tds = $cells[1];

        if (count($tds) < 6) continue; // 데이터 누락 방지

        // 교회명
        preg_match('/>([^<]+)<\/font>/', $tds[1], $nameMatch);
        $name = trim($nameMatch[1] ?? '');

        // 주소
        //$address = trim(strip_tags($tds[2]));
        
        // 전체 주소
        $address_full = trim(strip_tags($tds[2]));

        // 우편번호 + 주소 분리
        preg_match('/^(\d{5,6})\s+(.+)/', $address_full, $addrMatch);
        $zipcode = $addrMatch[1] ?? '';
        $address = $addrMatch[2] ?? $address_full;

        // 전화번호
        $tel = trim(strip_tags($tds[3]));

        // 이메일 주소
        preg_match('/mailto:([^\'"]+)/', $tds[5], $emailMatch);
        $email = strtolower(trim($emailMatch[1] ?? ''));

//        echo "교회명: $name\n";
//        echo "주소: $address\n";
//        echo "전화: $tel\n";
//        echo "메일: $email\n";
//        echo "---------------------------\n";
        if ($address!=""){
            echo 'INSERT INTO church2 (church, tel, addr, zip, email) VALUES ("'.$name.'", "'.$tel.'", "'.$address_full.'", "'.$zipcode.'", "'.$email.'");<br>';
        }
    }
    echo "<br>";
}
//echo $html;






