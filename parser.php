<?php
require_once 'phpQuery.php';

$arr = array(); // массив для url изображений

$url = 'http://getwall.ru/search/cat/'; // сайт с которого парсим

$content = file_get_contents($url); // берем файл
	
$doc = phpQuery::newDocument($content); // для работы с библ создаем объект библ

$findCont = $doc->find('.screen-link'); // 

foreach ($findCont as $el){
	$pq = pq($el);
	$pq = $pq->find('img')->attr('src');
	$arr[] = $pq;
}
$i = 1;
foreach ($arr as $el) {
	echo $i.') '.$el.'<br>';
	$i++;
}

/*******************with*mysqli****************************************/

$link = mysqli_connect('localhost', 'root', '', 'img_db'); // устанавливаем соединение с бд // 'img_db' - имя бд

if (mysqli_connect_errno()) { // проверяем на ошибки
	echo 'Ошибка подключения к БД ('.mysqli_connect_errno().'): '.mysqli_connect_error();
	exit(); // если произошла ошибка, останавливаем выполнение скрипта
}

for ($i=0; $i < count($arr); $i++) { 
	addToDB($link, $arr[$i]);
}

function addToDB($link, $urlIm){

	$sql = mysqli_query($link, 'INSERT INTO `parse_img`
			(`url`) 
	VALUES ("'.$urlIm.'")');

    if (!$sql) {
        echo "Ошибка при добавлении записи в БД (INSERT)";
    }
}

?>
