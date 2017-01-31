<?php
    

    require '../app/Mage.php';
    Mage::app('admin');
    Mage::register('isSecureArea', 1);
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    error_reporting(E_ALL);
    set_time_limit(0);
    ini_set('memory_limit','1024M');

    $read= Mage::getSingleton('core/resource')->getConnection('core_read'); //Only for reading the data from the database tables
    $write = Mage::getSingleton('core/resource')->getConnection('core_write'); // Only for writing the data into the database tables

    $query = 'SELECT * FROM core_email_queue';

    $results = $read->fetchAll($query);

    echo '<pre>';print_r($results);exit;

    exit;


    // $ss = '733652146535';
    // $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $ss);
    // echo '<pre>';print_r($product);exit;
    
    $collections = Mage::getModel('catalog/product')->getCollection(); 

    $file = fopen("var/allsku.csv","w");
    // $file = fopen("var/missing_images.csv", "w");
    // fputcsv($file,array('Sr No','Name','Sku'));

    $cnt = 1;
    foreach ($collections as $_product) {

        if($_product->getSku() == '210') continue;

        $_sku = array($_product->getSku());
        fputcsv($file,$_sku);

        // $product = Mage::getModel('catalog/product')->load($_product->getId());
        // $img = $product->getImage();

        // if($img == '' || $img == 'no_selection'){
        //  $_sku = (string) $product->getSku();
        //  fputcsv($file,array($cnt,$product->getName(),$_sku));
        //  Mage::log($cnt.' ) '.$product->getSku(),null,'missingImg.log');
        //  echo $cnt.' ) '.$product->getSku().' -- '.$img.'<hr/>';
        //  $cnt++;
        // }
    }
    fclose($file);
    echo 'Sku List Generated Successfully.';
?>