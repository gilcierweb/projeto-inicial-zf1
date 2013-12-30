<?php
//chama a biblioteca do zend para usar componentes avulsos
set_include_path(get_include_path() . PATH_SEPARATOR . '../library/');

require_once '/Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance()
        ->setFallbackAutoloader(true);

$adapter = 'Pdo_Mysql';
$config = array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'xxxxxxxx',
    'dbname' => 'test',
    'charset' => 'utf8'
);

$db = Zend_Db::factory($adapter, $config);

$select = $db->select()
        ->from('tabela');

$rows = $db->fetchAll($select);

foreach ($rows as $row) {
    echo $row->titulo;
    echo $row->subtitulo;
    echo $row->descricao;
}

