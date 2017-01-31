<?php 
$username = null;
$password = null;
if (isset($_SERVER['PHP_AUTH_USER'])) {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
      list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
}

if ($username != 'roger@tasolglobal.com' && $password != 'dontknow123') {

    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    die();
} 
else {
    
    ini_set('error_reporting', E_ERROR);
    session_start();
    $_SESSION["formatedArray"] = array();
    $_SESSION["isWeight"] = false;
    ?>
    <div style="width:500px;margin: 0 auto;background-color: #F1F1F2;">
        <form style="padding: 10px;text-align: center" method="post" action="" enctype="multipart/form-data">
            <h2>XML to CSV</h2>
            <div>
                <input style="margin-left: 80px;" type="file" name="xmlfile">
                <h6 style="margin:0">Choose XMl file to convert in CSV</h6>
            </div>
            <input style="margin-top: 15px;" type="submit" value="Convert" name="submitform"></input>
            <?php 
            if(isset($_POST['submitform'])){
            

                $target_dir = "media/uploads/";
                // $target_file = $target_dir . basename($_FILES["xmlfile"]["name"]);
                $filename = strtotime(date('Y-m-d h:i:s')).'.xml';
                $target_file = $target_dir . $filename;
                $uploadOk = 1;

                $tmp_name = $_FILES["xmlfile"]["tmp_name"];
                if(move_uploaded_file($tmp_name, $target_file)){
                    
                    $formatedArray = array();
                    $filexml = $target_file;

                    if (file_exists($filexml)) 
                    {
                        
                     $xml = simplexml_load_file($filexml);
                    
                        $csvname = $target_dir . strtotime(date('Y-m-d h:i:s')).'.csv';
                        $f = fopen($csvname, 'w');
                        $header = getHeader();

                        fputcsv($f, $header ,',','"'); 
                        $cnt = 0;
                        foreach ($xml->children() as $item) 
                        {
                          
                            setSessionArray($cnt);
                            $hasChild = (count($item->children()) > 0) ? true:false;
                              
                            
                            if(!$hasChild){
                                addItemInArray($item,$cnt);
                            }
                            else{
                                createCsv($item,$f,$cnt);
                            }
                           //print_r($item);exit;
                            $cnt++;
                                
                        } 

                        
                         $patterStr = "";
                         /** here pdfs description and url key is to be set in formatedarray session array **/
                        foreach($_SESSION["formatedArray"] AS $skey=>$session_format){
                                $patterStr = "";
                            foreach ($_SESSION["formatedArray"][$skey]['pdfs']['description'] as $intKey => $aaDesc) {
                                $patterStr .= $_SESSION["formatedArray"][$skey]['pdfs']['description'][$intKey][0];
                                $patterStr .= "**";
                                $patterStr .= $_SESSION["formatedArray"][$skey]['pdfs']['url'][$intKey][0];
                                $patterStr .= "||";
                            }
                            $_SESSION["formatedArray"][$skey]['pdfs'] = rtrim($patterStr,'||');
                        }
                        
                        /******************Dhaval***********************************
                        $patterStr = "";
                        foreach ($_SESSION["formatedArray"][0]['pdfs']['description'] as $intKey => $aaDesc) {
                            $patterStr .= $_SESSION["formatedArray"][0]['pdfs']['description'][$intKey][0];
                            $patterStr .= "**";
                            $patterStr .= $_SESSION["formatedArray"][0]['pdfs']['url'][$intKey][0];
                            $patterStr .= "||";
                        }
                        $_SESSION["formatedArray"][0]['pdfs'] = rtrim($patterStr,'||');
                        ***********************************************************/
                        /*  echo $count=sizeof($_SESSION["formatedArray"]);
                        $patterStr = "";
                         for($i=0;$i<$count;$i++)
                         {
                            foreach ($_SESSION["formatedArray"][$i]['pdfs']['description'] as $intKey => $aaDesc) {
                                $patterStr .= $_SESSION["formatedArray"][$i]['pdfs']['description'][$intKey][0];
                                $patterStr .= "**";
                                $patterStr .= $_SESSION["formatedArray"][$i]['pdfs']['url'][$intKey][0];
                                $patterStr .= "||";
                            }
                            $_SESSION["formatedArray"][$i]['pdfs'] = rtrim($patterStr,'||');
                        }   */
                            
                        //print_r($_SESSION["formatedArray"]);exit;

                        //$_SESSION["formatedArray"][0]['pdfs'] = rtrim($patterStr,'||');

                        $final_array =reFormateArray($_SESSION["formatedArray"]);
                        
                      
                        foreach ($final_array as $key=>$value) 
                        {
                            fputcsv($f, $value ,',','"');  
                            
                        }
                       
                        echo '<div style="margin-top: 10px;color: green;font-weight: bold;">File is converted successfully</div>';
                        echo '<div>Click <a href="'.$csvname.'">here</a> to download CSV file.</div>';
                    }    
                }
                else{
                    echo '<div>Can not upload File. Please try again</div>';
                }
            }

            ?>
        </form>
    </div>

<?php
                        
}

    function getHeader(){
        $_header = array(
            'sku' => '',
            '_store' =>'',
            '_attribute_set' => 'Default',
            '_type' =>'simple', 
            '_category' =>'Large Appliance/', 
            '_root_category' => 'Default Category',
            '_product_websites' => 'base',
            'cost' => '',
            'created_at' => date('Y-m-d h:i:s'),
            'description' => '',
             'manufacturer' => '',
             'features' => '',
             'spectablehtml' => '',
            'name' => '',
            'price' => '',
            'short_description' => '',
            'status' => '1',
            'tax_class_id' => '2',
            'visibility' => '4',
            'weight' => '',
            'qty' => '1',
            'min_qty' => 0,
            'use_config_min_qty' => 1,
            'is_qty_decimal' => 0,
            'backorders' => 0,
            'use_config_backorders' => 1,
            'min_sale_qty' => 1,
            'use_config_min_sale_qty' => 1,
            'max_sale_qty' => 0,
            'use_config_max_sale_qty' => 1,
            'is_in_stock' => 1,
            'notify_stock_qty' => 1,
            'use_config_notify_stock_qty' => 0,
            'manage_stock' => 1,
            'use_config_manage_stock' => 0,
            'stock_status_changed_auto' => 1,
            'use_config_qty_increments' => 0,
            'qty_incrementsuse_config_enable_qty_inc' => 1,
            'enable_qty_increments' => 0,
            'is_decimal_divided' => 88,
            '_media_attribute_id' => 88,
            '_media_lable'=>'',
            '_media_image' => '',
            'image' => '',
            'small_image' => '',
            'thumbnail' => '',
             'pdfs' => '',
            'special_price' => ''
           

            
        );

        $header = array();
        foreach ($_header as $key => $value) {
            $header[] = $key;
        }

        return $header;
    }

    function createCsv($xml,$f,$cnt)
    {
        foreach ($xml->children() as $item) 
        {
            
            $hasChild = (count($item->children()) > 0)?true:false;

            if( ! $hasChild)
            {
                addItemInArray($item,$cnt);
                // echo '<pre>';print_r();exit;
                // $put_arr = array($item->getName(),$item); 
                // fputcsv($f, $put_arr ,',','"');
                //$child->getName()
            }
            else
            {
                createCsv($item, $f,$cnt);
            }

        }
    }
  

    function setSessionArray($cnt){
        $_SESSION["formatedArray"][$cnt] = array(
            'sku' => '',
            '_store' =>'',
            '_attribute_set' => 'Default',
            '_type' =>'simple', 
            '_category' =>'Large Appliance/', 
            '_root_category' => 'Default Category',
            '_product_websites' => 'base',
            'cost' => '',
            'created_at' => date('Y-m-d h:i:s'),
            'description' => '',
             'manufacturer' => '',
             'features' => '',
             'spectablehtml' => '',
            'name' => '',
            'price' => '',
            'short_description' => '',
            'status' => '1',
            'tax_class_id' => '2',
            'visibility' => '4',
            'weight' => '',
            'qty' => '1',
            'min_qty' => 0,
            'use_config_min_qty' => 1,
            'is_qty_decimal' => 0,
            'backorders' => 0,
            'use_config_backorders' => 1,
            'min_sale_qty' => 1,
            'use_config_min_sale_qty' => 1,
            'max_sale_qty' => 0,
            'use_config_max_sale_qty' => 1,
            'is_in_stock' => 1,
            'notify_stock_qty' => 1,
            'use_config_notify_stock_qty' => 0,
            'manage_stock' => 1,
            'use_config_manage_stock' => 0,
            'stock_status_changed_auto' => 1,
            'use_config_qty_increments' => 0,
            'qty_incrementsuse_config_enable_qty_inc' => 1,
            'enable_qty_increments' => 0,
            'is_decimal_divided' => 88,
            '_media_attribute_id' => 88,
            '_media_lable'=>'',
            '_media_image' => '',
            'image' => '',
            'small_image' => '',
            'thumbnail' => '',
            'pdfs' => '',
            'special_price' => ''
            
        );
    }
    function addItemInArray($item,$cnt){
            

        if($item->getName() == 'key' && $item->__toString() == 'Net weight (lbs)'){
            $_SESSION["isWeight"] = true;
        }

        if($item->getName() == 'value' && $_SESSION["isWeight"] == true){
            $v = explode(' ', $item->__toString());
            $_SESSION["formatedArray"][$cnt]['weight'] = isset($v[0]) ? $v[0] : $item;
            $_SESSION["isWeight"] = false;
        }
        
        if($item->getName() == 'pn'){
            $_SESSION["formatedArray"][$cnt]['sku'] = $item;

        }

        if($item->getName() == 'brand_name'){
            $_SESSION["formatedArray"][$cnt]['manufacturer'] = $item;   
        }
        /** following script for replace tags **/
        $searchReplaceArray = array(
                            '(B)'=>'<b>',
                            '(/B)'=>'</b>',
                            '(UL)'=>'<ul>',
                            '(/UL)'=>'</ul>',
                            '(LI)'=>'<li>',
                            '(/LI)'=>'</li>',
                            '(P)'=>'<p>',
                            '(/P)'=>'</p>',
                            '(BR)'=>'<br>',
                            );
                            
        if($item->getName() == 'paragraph_description'){
            $_SESSION["formatedArray"][$cnt]['description'] = str_replace(
                              array_keys($searchReplaceArray), 
                              array_values($searchReplaceArray), 
                              $item
                            );   
        }

        if($item->getName() == 'short_description'){
            $_SESSION["formatedArray"][$cnt]['name'] = $item;
             //print_r($item);exit;   

        }

        if($item->getName() == 'map'){
            $_SESSION["formatedArray"][$cnt]['price'] = $item;   
        }

        if($item->getName() == 'msrp'){
            $_SESSION["formatedArray"][$cnt]['price'] = $item;   
        }

        if($item->getName() == 'medium_description'){
            $_SESSION["formatedArray"][$cnt]['short_description'] = $item;   
           
        }
        /***************************/     
       if($item->getName() == 'description')
        {
            $_SESSION["formatedArray"][$cnt]['pdfs']['description'][] = $item;   
            
          // print_r($item->media->pdfs);exit;
        } 
        if($item->getName() == 'url')
        {
            $_SESSION["formatedArray"][$cnt]['pdfs']['url'][] = $item; 
           
          // print_r($item->media->pdfs);exit;
        } 
        if($item->getName() == 'hierarchical_features_html')
        {
            $_SESSION["formatedArray"][$cnt]['features']=$item;  
        }
       if($item->getName() == 'spec_table_html')
        {
            $_SESSION["formatedArray"][$cnt]['spectablehtml'] = $item;   
        }
        if($item->getName() == 'major_class_code'){
            $cat = $item->__toString();
            if($cat == 'LAUD')
            {
                $cat = 'Laundry';

            }
            elseif ($cat == 'ER') {
                $cat = 'Electric Ranges';
            }
            elseif ($cat == 'GR') {
                $cat = 'Gas Ranges';
            }
            elseif ($cat == 'REF') {
                $cat = 'Refrigerators';
            }
            elseif ($cat == 'MIC') {
                $cat = 'Microwaves';
            }
            elseif ($cat == 'FR') {
                $cat = 'Freezers';
            }
            elseif ($cat == 'DISH') {
                $cat = 'Dishwashers';
            }
            elseif ($cat == 'DEHUM') {
                $cat = 'Dehumidifiers';
            }
            elseif ($cat == 'LAUND') {
                $cat = 'Laundry';
            }
            elseif ($cat == 'LED') {
                $cat = 'TV';
            }
            elseif ($cat == 'PLASMA') {
                $cat = 'TV';
            }
            elseif ($cat == 'OLED') {
                $cat = 'TV';
            }
            elseif ($cat == 'LCD') {
                $cat = 'TV';
            }
            $_SESSION["formatedArray"][$cnt]['_category'] = 'Large Appliance/'.$cat;   
        }

        if($item->getName() == 'full_size_url'){

            $url = $item->__toString();
            
            // $ex_url = explode('/', $url);
            // $img_name = end($ex_url);
            // $img_path_local = 'media/import/'.$img_name;

            // if(!file_exists($img_path_local)){
            //     $img_cont = file_get_contents($url);
            //     file_put_contents('media/import/'.$img_name, $img_cont);
            // }

            $_SESSION["formatedArray"][$cnt]['_media_lable'] = $url;   
            $_SESSION["formatedArray"][$cnt]['_media_image'] = $url;   
            $_SESSION["formatedArray"][$cnt]['image'] = $url;   
            $_SESSION["formatedArray"][$cnt]['small_image'] = $url;   
            $_SESSION["formatedArray"][$cnt]['thumbnail'] = $url;       
        }
        
    }

    function reFormateArray($inputarray){
        $outputarray = array();
        
        foreach ($inputarray as $index => $childs) {
            foreach ($childs as $key => $value) {

                $outputarray[$index][] = $value;    
            }
            
        }
        return $outputarray;
    }

?>


<!-- sku == pn
_store ==
_attribute_set == Default
_type == 
_category == 
_root_category == Default Category
_product_websites == base,
cost == Not required,
created_at == ok,
description == paragraph_description,
manufacturer == brand_name,
name == '',
price == msrp,
short_description == short_description,
status == status,
tax_class_id == ,
visibility == Catalog,Search,
weight == ,
qty == ,
min_qty == 0,
use_config_min_qty == 1,
is_qty_decimal == 0,
backorders == 0,
use_config_backorders == 1,
min_sale_qty == 1,
use_config_min_sale_qty == 1,
max_sale_qty == 0,
use_config_max_sale_qty == 1,
is_in_stock == 1,
notify_stock_qty == 1,
use_config_notify_stock_qty == 0,
manage_stock == 1,
use_config_manage_stock == 0,
stock_status_changed_auto == 1,
use_config_qty_increments == 0,
qty_incrementsuse_config_enable_qty_inc == 1,
enable_qty_increments == 0,
is_decimal_divided == 88,
_media_attribute_id == 88,
_media_lable,
_media_image == images,
image == images,
small_image == images,
thumbnail == images,
special_price == images -->