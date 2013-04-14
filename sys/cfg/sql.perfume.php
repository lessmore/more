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
    * 001 -> behaivor nesting
    * @todo most AI
    */
    'behavior' => array(
        '100000000' => array('desc' => '','def' => ''),
        '100100000' => array('desc' => '','def' => ''),

        '100200000' => array('desc' => '','def' => ''),
        '101000000' => array('desc' => '','def' => ''),
    )
);



/*
|| behavior || attribute & state
||
\-------------------------------------
    数据结构设计小贴士（随记）
/-------------------------------------
|
|
|
|
|
|






CREATE TABLE `timeline` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `when` int unsigned NOT NULL COMMENT '时间轴',
  `who` int unsigned NOT NULL COMMENT '主体',
  `how` int unsigned NOT NULL COMMENT '行为',
  `what` int unsigned NOT NULL COMMENT '状态,读取how定义绑定what到指向关联内容表',
  `prep` int unsigned NOT NULL COMMENT '预备',
  `brep` int unsigned NOT NULL COMMENT '预备',
  PRIMARY KEY (`id`),
  KEY `when` (`when`),
  KEY `who` (`who`),
  KEY `how` (`how`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时间线(behavior), 类似加分行为：用户一条，管理员一条'

CREATE TABLE `space_sql` (
  `id` int unsigned NOT NULL COMMENT '时间线ID',
  `sql` blob NOT NULL COMMENT '存储空间影响的sql',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='时光机(behavior)'


CREATE TABLE `space_what` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '',
  `des` varbinary(1024) NOT NULL COMMENT '一句话简评？',
  `def` blob NOT NULL COMMENT 'json for coding ?',//结构是可变的？不可排序？
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='what迷茫区'


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




什么是产品？
1.产品定义：产品即SPU(Standard Product Unit)，全称 标准产品单元，是对某一类标准产品的共同特征属性的描述. 是商品信息共有属性的一种抽取。
2.产品商品：商品是对进入销售周期的产品的一种描述, 它会在产品的基础上添加一些销售属性(比如卖家, 库存, 颜色,尺寸等等销售属性). 
            SPU 是一个介于类目(仅叶子类目)和商品之间的概念, 是对类目的细化,是淘宝网标准化, 规范化运营的基础.不同的商家可以使用同一个产品。

CREATE TABLE `space_spu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tl` int unsigned NOT NULL COMMENT '管理员或用户的tl行为',
  `name` varbinary(64) NOT NULL COMMENT '名称',
  `ename` varbinary(64) NOT NULL COMMENT '名称',
  `brand_id` int unsigned NOT NULL COMMENT '品牌id',
  `series` varbinary(128) NOT NULL COMMENT '品牌系列',
  `des` varbinary(3000) NOT NULL COMMENT '描述',
  `photos` varbinary(255) NOT NULL COMMENT '封面，photo_id,p',
  `type` int unsigned NOT NULL COMMENT '类型',
  `created` int unsigned NOT NULL COMMENT '创建时间',
  `updated` int unsigned NOT NULL COMMENT '修改时间',
  `deleted` tinyint unsigned NOT NULL COMMENT '删除状态',
  PRIMARY KEY (`id`),
  KEY `tl` (`tl`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品SPU';


CREATE TABLE `space_brand` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌Id',
  `name` varbinary(64) DEFAULT NULL COMMENT '品牌名称',
  `ename` varbinary(64) DEFAULT NULL COMMENT '英文名',
  `des` blob COMMENT '品牌描述',
  `nation` varbinary(64) DEFAULT NULL COMMENT '国家地域',
  `logo` varbinary(255) DEFAULT NULL COMMENT '品牌logo',
  `imgs` varbinary(1024) DEFAULT NULL COMMENT '品牌图集;隔开',
  `website` varbinary(1024) DEFAULT NULL COMMENT '官方网址',
  `birth` int NOT NULL DEFAULT '0' COMMENT '品牌创立时间',
  `rank` tinyint NOT NULL DEFAULT '0' COMMENT '热门知名分级',
  `ctspu` mediumint unsigned NOT NULL DEFAULT '0' COMMENT '品牌下的产品数',
  `idx_name` tinyint DEFAULT NULL COMMENT '首字母索引1-26→A-Z', 
  `parent` int unsigned NOT NULL COMMENT '父品牌Id',
  `created` int unsigned NOT NULL COMMENT '创建时间',
  `updated` int unsigned NOT NULL COMMENT '更新时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '删除状态: 0正常',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='品牌';


CREATE TABLE `space_item` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `spu_id` int unsigned NOT NULL COMMENT '产品ID',
  `platform` varbinary(20) NOT NULL COMMENT '商品来源,taobao',
  `pf_item_id` varbinary(32) unsigned NOT NULL COMMENT '商品来源的ID',
  `created_tl` int unsigned NOT NULL DEFAULT '0' COMMENT '创建的行为',
  `seller_tl` int unsigned NOT NULL DEFAULT '0' COMMENT '卖家的最后行为',
  `buyer_tl` int unsigned NOT NULL DEFAULT '0' COMMENT '买家的买家行为',
  `alipay` varbinary(64) NOT NULL COMMENT '支付宝ID',
  `title` varbinary(255) NOT NULL COMMENT '商品名称',
  `seller` varbinary(32) NOT NULL COMMENT '商家名称',
  `price` int unsigned NOT NULL COMMENT '商品价格x100',
  `quantity` int NOT NULL COMMENT '商品可售数量,负的表示预定',
  `discount` tinyint unsigned NOT NULL COMMENT '折扣0~100',
  `freight` int unsigned NOT NULL COMMENT '运费x100',
  `location` varbinary(32) COMMENT '商品仓库地址',
  `Shipping` varbinary(32) COMMENT '全国',
  `photos` varbenary(255) NOT NULL COMMENT '商家自定义图片ID',
  `onsale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '在售',
  `ct_pv` varbinary(32) COMMENT '全国',
  `ct_uv` varbinary(32) COMMENT '全国',
  `ct_review` int unsigned NOT NULL COMMENT '评价计数',
  `ct_sold` int unsigned NOT NULL COMMENT '销量计数',
  `ct_weight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品权重',
  `ct_fav` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '该商品被分享的次数，即对应推的条数',
  `isDeleted` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态',
  `created` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品的商品关联';


CREATE TABLE `space_photo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '图片ID',
  `tl` int DEFAULT NULL COMMENT '图片行为bind',
  `src` varbinary(255) NOT NULL COMMENT '地址',
  `alt` varbinary(255) NOT NULL COMMENT '来源信息',
  PRIMARY KEY (`id`),
  KEY `tl` (`tl`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='当我想到photoshop时，我确定用photo不用image'






