<?php
$installer = $this;

$installer->startSetup();

$installer->run("
                
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD animation_loop varchar(20) NOT NULL DEFAULT '1';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD reverse varchar(10) NOT NULL DEFAULT '1';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD slideshow varchar(10) NOT NULL DEFAULT '1';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD direction varchar(20) NOT NULL DEFAULT 'horizontal';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD smooth_height varchar(10) NOT NULL DEFAULT 'false';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD start_at int(5) NOT NULL DEFAULT '0';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD slideshow_speed int(10) NOT NULL DEFAULT '7000';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD init_delay int(10) NOT NULL DEFAULT '0';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD randomize varchar(10) NOT NULL DEFAULT 'true';

ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD pause_on_action varchar(10) NOT NULL DEFAULT 'true';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD pause_on_hover varchar(10) NOT NULL DEFAULT 'false';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD use_css varchar(10) NOT NULL DEFAULT 'true';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD touch varchar(10) NOT NULL DEFAULT 'true';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD video varchar(10) NOT NULL DEFAULT 'false';

ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD control_nav varchar(10) NOT NULL DEFAULT 'true';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD direction_nav varchar(10) NOT NULL DEFAULT 'true';

ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD keyboard varchar(10) NOT NULL DEFAULT 'true';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD multiple_keyboard varchar(10) NOT NULL DEFAULT 'false';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD mousewheel varchar(10) NOT NULL DEFAULT 'false';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD pause_play varchar(10) NOT NULL DEFAULT 'false';

ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD item_width int(5) NOT NULL DEFAULT '0';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD item_margin int(5) NOT NULL DEFAULT '0';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD min_items int(5) NOT NULL DEFAULT '0';
ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD max_items int(5) NOT NULL DEFAULT '0';

ALTER TABLE `{$this->getTable('aslideshow/slideshow')}` ADD animation varchar(20) NOT NULL DEFAULT 'fade';
ALTER TABLE `mgs_reponsiveslideshow_slideshow` CHANGE `transition` `transition` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'index';

    ");

$installer->endSetup(); 