<?php
/**
 * MGS_ResponsiveSlideshow Extension
 *
 * @category    Local
 * @package     MGS_ResponsiveSlideshow
 * @author      dungnv (dungnv@arrowhitech.com)
 * @copyright   Copyright(c) 2011 Arrowhitech Inc. (http://www.arrowhitech.com)
 *
 */

/**
 *
 * @category   Local
 * @package    MGS_ResponsiveSlideshow
 * @author     dungnv <dungnv@arrowhitech.com>
 */
 
class MGS_ResponsiveSlideshow_Block_Adminhtml_Slideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $_model = Mage::registry('slideshow_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

//General Information

        $fieldset = $form->addFieldset('aslideshow_form', array('legend'=>Mage::helper('aslideshow')->__('General Information')));
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
            'value'     => $_model->getName()
        ));

        $fieldset->addField('slideshow_for', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow For'),
            'name'      => 'slideshow_for',
			'required'  => true,
            'values'    => Mage::getSingleton('aslideshow/config_source_position')->toSlideshowForArray(),
            'value'     => $_model->getSlideshowFor()
        ));

        $fieldset->addField('slideshow_position', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Position'),
            'name'      => 'slideshow_position',
			'required'  => true,
            'values'    => Mage::getSingleton('aslideshow/config_source_position')->toOptionArray(),
            'value'     => $_model->getSlideshowPosition()
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Sort Order'),
            'required'  => false,
            'name'      => 'sort_order',
            'value'     => $_model->getSortOrder()
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Is Active'),
            'name'      => 'is_active',
			'required'  => true,
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getIsActive()
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'multiselect', array(
                'label'     => Mage::helper('aslideshow')->__('Visible In'),
                'required'  => true,
                'name'      => 'stores[]',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
                'value'     => $_model->getStoreId()
            ));
        }
        else {
            $fieldset->addField('stores', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        
//Global Slideshow Setting

        $fieldset = $form->addFieldset('aslideshow_form2', array('legend'=>Mage::helper('aslideshow')->__('Global Slideshow Setting')));

        $fieldset->addField('show_text', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Text'),
            'name'      => 'show_text',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getShowText()
        ));

        $fieldset->addField('image_width', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow Width'),
            'required'  => true,
            'name'      => 'image_width',
            'value'     => $_model->getImageWidth()
        ));

        $fieldset->addField('image_height', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow Height'),
            'required'  => true,
            'name'      => 'image_height',
            'value'     => $_model->getImageHeight()
        ));
        
    //FlexSlider: Default Settings

        $fieldset->addField('transition', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Transition'),
            'name'      => 'transition',
	    'required'  => true,
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTransitionArray(),
            'value'     => $_model->getTransition()
        ));
        
        $fieldset->addField('animation', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Animation'),
            'name'      => 'animation',
	    'required'  => true,
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toAnimationArray(),
            'value'     => $_model->getAnimation()
        ));
        
        $fieldset->addField('direction', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Direction'),
            'name'      => 'direction',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toDirectionArray(),
            'value'     => $_model->getDirection()
        ));
        
        $fieldset->addField('animation_loop', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Animation Loop'),
            'name'      => 'animation_loop',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
            'value'     => $_model->getAnimationLoop()
        ));

        $fieldset->addField('smooth_height', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Smooth Height'),
            'name'      => 'smooth_height',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
            'value'     => $_model->getSmoothHeight()
        ));
        
        $fieldset->addField('start_at', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Start At'),
            'required'  => true,
            'name'      => 'start_at',
            'value'     => $_model->getStartAt()
        ));
        
        $fieldset->addField('slideshow', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Auto Play'),
            'name'      => 'slideshow',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
            'value'     => $_model->getSlideshow()
        ));
        
        $fieldset->addField('slideshow_speed', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Slideshow Speed'),
            'required'  => true,
            'name'      => 'slideshow_speed',
            'value'     => $_model->getSlideshowSpeed()
        ));
        
        $fieldset->addField('animation_speed', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Animation Speed'),
            'required'  => true,
            'name'      => 'animation_speed',
            'value'     => $_model->getAnimationSpeed()
        ));
        
        $fieldset->addField('init_delay', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Init Delay'),
            'required'  => true,
            'name'      => 'init_delay',
            'value'     => $_model->getInitDelay()
        ));
        
        $fieldset->addField('randomize', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Randomize'),
            'name'      => 'randomize',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
            'value'     => $_model->getRandomize()
        ));
        
        $fieldset->addField('background_opacity', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Background Opacity'),
            'name'      => 'background_opacity',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toOpacityArray(),
            'value'     => $_model->getBackgroundOpacity()
        ));
        
        $fieldset->addField('control_nav', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Control Nav'),
            'name'      => 'control_nav',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toControlNavArray(),
            'value'     => $_model->getControlNav()
        ));
        
    //FlexSlider: Width & Margin for carousel Settings
        
        $fieldset = $form->addFieldset('aslideshow_form5', array('legend'=>Mage::helper('aslideshow')->__('')));
        
        $fieldset->addField('item_width', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Item (thumbnails) Width'),
            'name'      => 'item_width',
            'value'     => $_model->getItemWidth()
        ));
        
        $fieldset->addField('item_margin', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Item (thumbnails) Margin'),
            'name'      => 'item_margin',
            'value'     => $_model->getItemMargin()
        ));
        
    //FlexSlider: Min & Max items for M-M carousel Settings
    
        $fieldset = $form->addFieldset('aslideshow_form6', array('legend'=>Mage::helper('aslideshow')->__('')));
        
        $fieldset->addField('min_items', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Min Items'),
            'name'      => 'min_items',
            'value'     => $_model->getMinItems()
        ));
        
        $fieldset->addField('max_items', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Max Items'),
            'name'      => 'max_items',
            'value'     => $_model->getMaxItems()
        ));
        
    //FlexSlider: Video Settings
    
        //$fieldset = $form->addFieldset('aslideshow_form7', array('legend'=>Mage::helper('aslideshow')->__('')));
        //
        //$fieldset->addField('use_css', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Use CSS'),
        //    'name'      => 'use_css',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getUseCss()
        //));
        
//        $fieldset->addField('between_block_delay', 'text', array(
//            'label'     => Mage::helper('aslideshow')->__('Delay'),
//            'required'  => true,
//            'name'      => 'between_block_delay',
//            'value'     => $_model->getBetweenBlockDelay()
//        ));

//        $fieldset->addField('auto_rotation', 'select', array(
//            'label'     => Mage::helper('aslideshow')->__('Auto Repeat'),
//            'name'      => 'auto_rotation',
//			'required'  => true,
//            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
//            'value'     => $_model->getAutoRotation()
//        ));

//        $fieldset->addField('auto_rotation_speed', 'text', array(
//            'label'     => Mage::helper('aslideshow')->__('Auto Rotation Speed'),
//            'required'  => true,
//            'name'      => 'auto_rotation_speed',
//            'value'     => $_model->getAutoRotationSpeed()
//        ));

//        $fieldset->addField('slide_controlls', 'select', array(
//            'label'     => Mage::helper('aslideshow')->__('Slide Controllers'),
//            'name'      => 'slide_controlls',
//			'required'  => true,
//            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toSlideControllsArray(),
//            'value'     => $_model->getSlideControlls()
//        ));

//	$fieldset->addField('slider_automatically', 'select', array(
//            'label'     => Mage::helper('aslideshow')->__('Auto Play'),
//            'name'      => 'slider_automatically',
//			'required'  => true,
//            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
//            'value'     => $_model->getSliderAutomatically()
//        ));

//	$fieldset->addField('touch_enabled', 'select', array(
//            'label'     => Mage::helper('aslideshow')->__('Touch Enabled'),
//            'name'      => 'touch_enabled',
//	      'required'  => true,
//            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
//            'value'     => $_model->getTouchEnabled()
//        ));
   
//      $this->setChild('form_aft',$this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
//          ->addFieldMap($transition->getHtmlId(),$transition->getName())
//          ->addFieldMap($animation_speed->getHtmlId(),$animation_speed->getName())
//          ->addFieldDependence($animation_speed->getName(),$transition->getName(),'index'));

        
		
//	$fieldset->addField('display', 'text', array(
//            'label'     => Mage::helper('aslideshow')->__('Start At'),
//            'required'  => true,
//            'name'      => 'display',
//            'value'     => $_model->getDisplay()
//        ));

        

        //$fieldset->addField('pause_on_action', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Pause on Action'),
        //    'name'      => 'pause_on_action',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getPauseOnAction()
        //));
        //
        //$fieldset->addField('pause_on_hover', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Pause on Hover'),
        //    'name'      => 'pause_on_hover',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getPauseOnHover()
        //));
        //
        //
        //
        //$fieldset->addField('touch', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Touch'),
        //    'name'      => 'touch',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getTouch()
        //));
        //
        //$fieldset->addField('video', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Video'),
        //    'name'      => 'video',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getVideo()
        //));
        //
        //$fieldset->addField('direction_nav', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Direction Button'),
        //    'name'      => 'direction_nav',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getDirectionNav()
        //));
        //
        //$fieldset->addField('keyboard', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Allow Keyboard'),
        //    'name'      => 'keyboard',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getKeyboard()
        //));
        //
        //$fieldset->addField('multiple_keyboard', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Multiple Keyboard'),
        //    'name'      => 'multiple_keyboard',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getMultipleKeyboard()
        //));
        //
        //$fieldset->addField('mousewheel', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Mousewheel'),
        //    'name'      => 'mousewheel',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getMousewheel()
        //));
        //
        //$fieldset->addField('pause_play', 'select', array(
        //    'label'     => Mage::helper('aslideshow')->__('Pause & Play'),
        //    'name'      => 'pause_play',
        //    'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toTrueFalseArray(),
        //    'value'     => $_model->getPausePlay()
        //));

//setting for product

        $fieldset = $form->addFieldset('aslideshow_form3', array('legend'=>Mage::helper('aslideshow')->__('')));

        $fieldset->addField('product_image_width', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Product Image Width'),
            'name'      => 'product_image_width',
            'value'     => $_model->getProductImageWidth()
        ));

        $fieldset->addField('product_image_height', 'text', array(
            'label'     => Mage::helper('aslideshow')->__('Product Image Height'),
            'name'      => 'product_image_height',
            'value'     => $_model->getProductImageHeight()
        ));

        $fieldset->addField('show_price', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Price'),
            'name'      => 'show_price',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getShowPrice()
        ));

        $fieldset->addField('show_title', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Product Title'),
            'name'      => 'show_title',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'value'     => $_model->getShowTitle()
        ));

        $fieldset->addField('show_description', 'select', array(
            'label'     => Mage::helper('aslideshow')->__('Show Description'),
            'name'      => 'show_description',
            'values'    => Mage::getSingleton('aslideshow/config_source_setting')->toShowDescriptionArray(),
            'value'     => $_model->getShowDescription()
        ));

//setting for product (Dont need)
        //$fieldset = $form->addFieldset('aslideshow_form4', array('legend'=>Mage::helper('aslideshow')->__('Slideshow Static Block Setting')));
        //
        //$fieldset->addField('staticblock_image_width', 'text', array(
        //    'label'     => Mage::helper('aslideshow')->__('Image Width'),
        //    'name'      => 'staticblock_image_width',
        //    'value'     => $_model->getStaticblockImageWidth()
        //));
        //
        //$fieldset->addField('staticblock_image_height', 'text', array(
        //    'label'     => Mage::helper('aslideshow')->__('Image Height'),
        //    'name'      => 'staticblock_image_height',
        //    'value'     => $_model->getStaticblockImageHeight()
        //));
        
        //Example: (Adding Field Dependency In Magento Admin)
        
        //$transition2 = $fieldset->addField('transition2','select',array(
        //    'label' => Mage::helper('aslideshow')->__('Transition 2'),
        //    'name' => 'transition2',
        //    'values' => array(
        //               0 => 'Index',
        //               1 => 'Carousel',
        //               2 => 'Video'
        //    ),
        //    'required' => true
        //));
        //
        //$animation_speed2 = $fieldset->addField('animation_speed2','text',array(
        //    'label' => Mage::helper('aslideshow')->__('Animation Speed 2'),
        //    'name' =>  'animation_speed2',
        //    'required'=>true,
        //));
        
        //$this->setChild('form_after',$this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
        //    ->addFieldMap($transition2->getHtmlId(),$transition2->getName())
        //    ->addFieldMap($animation_speed2->getHtmlId(),$animation_speed2->getName())
        //    ->addFieldDependence($animation_speed2->getName(),$transition2->getName(),1));

        //End

        if( Mage::getSingleton('adminhtml/session')->getSlideshowData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshowData());
            Mage::getSingleton('adminhtml/session')->setSlideshowData(null);
        }
        
        return parent::_prepareForm();
    }
}
