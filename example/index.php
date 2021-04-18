<?php

namespace codesaur\Localization\Example;

/* DEV: v1.2021.03.25
 * 
 * This is an example script!
 */

require_once '../vendor/autoload.php';

use PDO;
use Exception;

use codesaur\Localization\LanguageModel;
use codesaur\Localization\CountriesModel;
use codesaur\Localization\TranslationModel;

ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

try {
    $dsn = 'mysql:host=localhost;charset=utf8';
    $username = 'root';
    $passwd = '';
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    
    $pdo = new PDO($dsn, $username, $passwd, $options);
    echo 'connected to mysql...<br/>';
    
    $database = 'localization_example';
    if ($_SERVER['HTTP_HOST'] === 'localhost'
            && in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))
    ) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $database COLLATE " . $pdo->quote('utf8_unicode_ci'));
    }

    $pdo->exec("USE $database");
    echo "starting to use database [$database]<br/>";
    
    $language = new LanguageModel($pdo);
    var_dump($language->retrieve(), $language->getByCode('mn'));

    $countries = new CountriesModel($pdo);
    var_dump($countries->retrieve(), $countries->retrieve('en'), $countries->getById('MN'), $countries->getById('US', 'mn'));

    $translation = new TranslationModel($pdo);
    $translation->setTable('default', 'utf8_unicode_ci');
    $default_translations = $translation->retrieve();
    $translation->setTable('dashboard', 'utf8_unicode_ci');
    $dashboard_translations_en = $translation->retrieve('en');
    var_dump($default_translations, $dashboard_translations_en);
} catch (Exception $ex) {
    die('<br />{' . date('Y-m-d H:i:s') . '} Error[' . $ex->getCode() . '] => ' . $ex->getMessage());
}
