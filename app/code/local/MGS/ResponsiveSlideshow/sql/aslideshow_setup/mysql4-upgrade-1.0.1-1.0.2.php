<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `mgs_reponsiveslideshow_slideshow_image` CHANGE `caption` `caption` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
    ");

$installer->endSetup();
