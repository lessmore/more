<?php
/*
| ------------------------------------
|   √.Demands Make Perfect
| ------------------------------------
|
*/
return array(
    /*
    * 100000001
    *
    * 100 -> space
    * 000 -> behaivor
    * 001 -> nesting
    * @todo most AI
    */
    'behavior' => array(
        '100000000' => array('desc' => '','def' => ''),
        '100100000' => array('desc' => '','def' => ''),

        '100200000' => array('desc' => '','json' => ''),
        '101000000' => array('desc' => '','json' => ''),

        '100000000' => array('desc' => '','json' => ''),
        '100000000' => array('desc' => '','json' => ''),

        '100000000' => array('desc' => '','json' => ''),
        '100000000' => array('desc' => '','json' => ''),

        '100000000' => array('desc' => '','json' => ''),
        '100000000' => array('desc' => '','json' => ''),

        '100000000' => array('desc' => '','json' => ''),
        '100000000' => array('desc' => '','json' => ''),

        '100000000' => array('desc' => '','json' => ''),
        '100000000' => array('desc' => '','json' => ''),
    )
);








/*
|| behavior || attribute & state
||

CREATE TABLE `space_sql` (
  `id` int unsigned NOT NULL COMMENT '时间线ID',
  `sql` blob NOT NULL COMMENT '存储空间影响的sql',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时光机(behavior)'


CREATE TABLE `timeline` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `when` int unsigned NOT NULL COMMENT '时间轴',
  `who` int unsigned NOT NULL COMMENT '主体',
  `how` int unsigned NOT NULL COMMENT '行为',
  `what` int unsigned NOT NULL COMMENT '状态',
  `pos` tinyint unsigned NOT NULL COMMENT '位置',
  `prep` int unsigned NOT NULL COMMENT '预备',
  `brep` int unsigned NOT NULL COMMENT '预备',
  PRIMARY KEY (`id`),
  KEY `when` (`when`),
  KEY `who` (`who`),
  KEY `how` (`how`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时间线(behavior), 类似加分行为：用户一条，管理员一条'


CREATE TABLE `space_what` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '',
  `des` varbinary(1024) NOT NULL COMMENT '一句话简评？',
  `def` blob NOT NULL COMMENT 'json for coding ?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='行为定义'


CREATE TABLE `space_how` (
  `id` int unsigned NOT NULL COMMENT '如:101100101,前三数指代一级类目业务,中三为二级类目基于类一,后三为三级类目基于类二,设想有个页面可以多级下拉选择,没有则创建,从100起配',
  `des` varbinary(1024) NOT NULL COMMENT 'description【行为描述:定义(规则)】【备注：参与人：项目,产品等经手人,逗号隔开】',
  `def` blob NOT NULL COMMENT 'definition【行为定义:代码化(json format)】{['bindtable':"space_spu',
  UNIQUE KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='行为定义'


CREATE TABLE `space_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uname` varbinary(32) NOT NULL COMMENT '用户名',
  `upass` varbinary(32) NOT NULL COMMENT '用户密码',
  `utype` tinyint unsigned NOT NULL COMMENT '用户类型',
  `ufrom` tinyint unsigned NOT NULL COMMENT '用户来源',
  `deleted` tinyint unsigned NOT NULL COMMENT '状态',
  `created` int unsigned NOT NULL COMMENT '创建时间',
  `updeted` int unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='用户'





CREATE TABLE `space_brand` (
  `brandId` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌Id',
  `name` varchar(64) DEFAULT NULL COMMENT '品牌名称',
  `ename` varchar(64) DEFAULT NULL COMMENT '英文名',
  `fletter` tinyint(1) DEFAULT NULL COMMENT '首字母索引1-26→A-Z', 
  `desc` text COMMENT '品牌描述',
  `nation` varchar(64) DEFAULT NULL COMMENT '国家地域',
  `logo` varchar(255) DEFAULT NULL COMMENT '品牌logo',
  `imgs` varchar(1024) DEFAULT NULL COMMENT '品牌图集;隔开',
  `categoryId` int(11) unsigned NOT NULL COMMENT '分类ID',
  `parentId` int(11) unsigned NOT NULL COMMENT '父品牌Id',
  `website` varchar(128) DEFAULT NULL COMMENT '官方网址',
  `weibourls` varchar(512) DEFAULT NULL COMMENT '微博地址;分号隔开',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '品牌创立时间',
  `isHot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热门，0冷门，10热门，20知名',
  `cspu` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌下的产品计数',
  `created` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated` int(11) unsigned NOT NULL COMMENT '更新时间',
  `isDeleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态: 0正常，1删除',
  PRIMARY KEY (`brandId`),
  KEY `name` (`name`),
  KEY `idx_fletter` (`fletter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='品牌';






CREATE TABLE `space_crawl` (
  `uid` int unsigned NOT NULL AUTO_INCREMENT,
  `uname` varbinary(32) NOT NULL COMMENT '用户名', `upss` varbinary(32) NOT NULL COMMENT '用户密码',
  `utype` tinyint unsigned NOT NULL COMMENT '用户类型',
  `ufrom` tinyint unsigned NOT NULL COMMENT '用户来源',
  `deleted` tinyint unsigned NOT NULL COMMENT '状态',
  `created` int unsigned NOT NULL COMMENT '创建时间',
  `updeted` int unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  KEY `uname` (`uname`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT=''

*/
