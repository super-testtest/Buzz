<?php
class FeaturedProduct_Catalog_Block_Product_Featured extends Mage_Catalog_Block_Product_Abstract
{
public function getFeaturedProduct()
{
//database connection object
$storeId = Mage::app()->getStore()->getId();
$resource = Mage::getSingleton('core/resource');
$read = $resource->getConnection('catalog_read');
$categoryProductTable = $resource->getTableName('catalog/category_product');
$productEntityIntTable = (string)Mage::getConfig()->getTablePrefix() . 'catalog_product_entity_int';
$eavAttributeTable = $resource->getTableName('eav/attribute');
// Query for featured product
$select = $read->select()
->from(array('cp'=>$categoryProductTable))
->join(array('pei'=>$productEntityIntTable), 'pei.entity_id=cp.product_id', array())
->joinNatural(array('ea'=>$eavAttributeTable))
->where('pei.value=1')
->where('ea.attribute_code="featured"');
$row = $read->fetchRow($select);
return Mage::getModel('catalog/product')->setStoreId($storeId)->load($row['product_id']);
}
}