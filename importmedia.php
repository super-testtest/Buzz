<?php 
	if(copy('http://192.168.5.210/magento1922/media/catalog/product/cache/1/small_image/210x/9df78eab33525d08d6e5fb8d27136e95/d/i/diner_1.png', 'dinner.png'))
    {
        echo "Copy file";
    }
    else{
    	echo 'Failed';
    }
    exit;
	
?>