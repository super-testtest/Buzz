<?php
class Tailored_Events_Model_Observer
{
	/**
	* function will delete products not listed in csv before import
	* @param $observer
	*/
	public function beforeImportFunction(Varien_Event_Observer $observer){
		$data = $observer->getEvent()->getData();
		$adpter = $data['adapter'];
		$behaviour = $adpter->getParameters();
		$newSku = $adpter->getNewSku();
		$updateableSku = array();
		if(isset($behaviour['behavior']) && $behaviour['behavior'] != 'delete'){
			foreach ($newSku as $sku => $value) {
				$updateableSku[] = $sku;
			}

			$idToDelete = array();
			$collections = Mage::getModel('catalog/product')->getCollection(); 
			foreach ($collections as $_product) {
				$_sku = $_product->getSku();
				if(!in_array($_sku, $updateableSku)){
					$idToDelete[] = $_product->getId();
				}
			}

			if(!empty($idToDelete)){
				$productEntityTable = Mage::getModel('importexport/import_proxy_product_resource')->getEntityTable();
				$importClass = new Tailored_RewriteClasses_Model_ImportExport_Import_Entity_Product;
				$_connection = $importClass->getConnection();
				
				$_connection->query(
	                $_connection->quoteInto(
	                    "DELETE FROM `{$productEntityTable}` WHERE `entity_id` IN (?)", $idToDelete
	                )
	            );	
			}
		}
	}

	/**
	* Reindexing start after import done successfully
	* @param $observer
	*/
	public function reIndexing(Varien_Event_Observer $observer)
	{
		$indexCollection = Mage::getModel('index/process')->getCollection();
		foreach ($indexCollection as $index) {
			$status = $index->getStatus();
			if($status == 'require_reindex'){
				$index->reindexEverything();	
			}
		}

		$collections = Mage::getModel('catalog/product')->getCollection(); 
		$count = 1;
		$message = '';
		foreach ($collections as $_product) {
			
			$sku = $_product->getSku();
			if($sku == '210') continue;

			$_data = array($sku);
			
			if($this->_checkIfSkuExists($sku)){
		        try{
		            $productId = $this->_getIdFromSku($sku);
		            $this->_updateMissingImages($count, $productId, $_data);
		            $message = $count . '> Success:: While Updating Images of Sku (' . $sku . '). <br />';
		 
		        }catch(Exception $e){
		            $message =  $count .'> Error:: While Upating Images of Sku (' . $sku . ') => '.$e->getMessage().'<br />';
		        }
		    }else{
		        $message =  $count .'> Error:: Product with Sku (' . $sku . ') does\'t exist.<br />';
		    }
		    $count++;
		    Mage::log($message,null,'imageImport.log');
		}
	}

	function _log($message, $file = 'update_missing_images.log'){
	    Mage::log($message, null, $file);
	}
	 
	function _getIndex($field) {
	    global $fields;
	    $result = array_search($field, $fields);
	    if($result === false){
	        $result = -1;
	    }
	    return $result;
	}
	 
	function _getConnection($type = 'core_read'){
	    return Mage::getSingleton('core/resource')->getConnection($type);
	}
	 
	function _getTableName($tableName){
	    return Mage::getSingleton('core/resource')->getTableName($tableName);
	}
	 
	function _getAttributeId($attribute_code = 'price'){
	    $connection = $this->_getConnection('core_read');
	    $sql = "SELECT attribute_id
	                FROM " . $this->_getTableName('eav_attribute') . "
	            WHERE
	                entity_type_id = ?
	                AND attribute_code = ?";
	    $entity_type_id = $this->_getEntityTypeId();
	    return $connection->fetchOne($sql, array($entity_type_id, $attribute_code));
	}
	 
	function _getEntityTypeId($entity_type_code = 'catalog_product'){
	    $connection = $this->_getConnection('core_read');
	    $sql        = "SELECT entity_type_id FROM " . $this->_getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
	    return $connection->fetchOne($sql, array($entity_type_code));
	}
	 
	function _getIdFromSku($sku){
	    $connection = $this->_getConnection('core_read');
	    $sql        = "SELECT entity_id FROM " . $this->_getTableName('catalog_product_entity') . " WHERE sku = ?";
	    return $connection->fetchOne($sql, array($sku));
	}
	 
	function _checkIfSkuExists($sku){
	    $connection = $this->_getConnection('core_read');
	    $sql        = "SELECT COUNT(*) AS count_no FROM " . $this->_getTableName('catalog_product_entity') . " WHERE sku = ?";
	    $count      = $connection->fetchOne($sql, array($sku));
	    if($count > 0){
	        return true;
	    }else{
	        return false;
	    }
	}
	 
	function _checkIfRowExists($productId, $attributeId, $value){
	    $tableName  = $this->_getTableName('catalog_product_entity_media_gallery');
	    $connection = $this->_getConnection('core_read');
	    $sql        = "SELECT COUNT(*) AS count_no FROM " . $this->_getTableName($tableName) . " WHERE entity_id = ? AND attribute_id = ?  AND value = ?";
	    $count      = $connection->fetchOne($sql, array($productId, $attributeId, $value));
	    if($count > 0){
	        return true;
	    }else{
	        return false;
	    }
	}
	 
	function _insertRow($productId, $attributeId, $value){
	    $connection             = $this->_getConnection('core_write');
	    $tableName              = $this->_getTableName('catalog_product_entity_media_gallery');
	 
	    $sql = "INSERT INTO " . $tableName . " (attribute_id, entity_id, value) VALUES (?, ?, ?)";
	    $connection->query($sql, array($attributeId, $productId, $value));
	}
	 
	function _updateMissingImages($count, $productId, $data){
	    $connection             = $this->_getConnection('core_read');
	    $smallImageId           = $this->_getAttributeId('small_image');
	    $imageId                = $this->_getAttributeId('image');
	    $thumbnailId            = $this->_getAttributeId('thumbnail');
	    $mediaGalleryId         = $this->_getAttributeId('media_gallery');
	 
	    //getting small, base, thumbnail images from catalog_product_entity_varchar for a product
	    $sql    = "SELECT * FROM " . $this->_getTableName('catalog_product_entity_varchar') . " WHERE attribute_id IN (?, ?, ?) AND entity_id = ? AND `value` != 'no_selection'";
	    $rows   = $connection->fetchAll($sql, array($imageId, $smallImageId, $thumbnailId, $productId));
	    if(!empty($rows)){
	        foreach($rows as $_image){
	            //check if that images exist in catalog_product_entity_media_gallery table or not
	            if(!$this->_checkIfRowExists($productId, $mediaGalleryId, $_image['value'])){
	                //insert that image in catalog_product_entity_media_gallery if it doesn't exist
	                $this->_insertRow($productId, $mediaGalleryId, $_image['value']);
	                /* Output / Logs */
	                $missingImageUpdates = $count . '> Updated:: $productId=' . $productId . ', $image=' . $_image['value'];
	                Mage::log($productId,null,'Updated_product.log');
	                $this->_log($missingImageUpdates);
	            }
	        }
	    }
	}
}
