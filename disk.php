<?php
	 


	 
	
	echo 'Total Space: ' . disk_total_space('/var/www/html') . '<hr/>';
	echo 'Disk Free: ' . disk_free_space('/var/www/html') ;exit;

?>