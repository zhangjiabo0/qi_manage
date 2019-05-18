<?php
/*---------------------------------------------------------------------------
  小微OA系统 - 让工作更轻松快乐 

  Copyright (c) 2013 http://www.smeoa.com All rights reserved.                                             


  Author:  jinzhu.yin<smeoa@qq.com>                         

  Support: https://git.oschina.net/smeoa/xiaowei               
 -------------------------------------------------------------------------*/

namespace Home\Model;
use Think\Model;

class  ProductModel extends CommonModel {
    protected $_validate	=	array(
        array('name', 'require', '产品名称必填', 1),
        array('unit', 'require', '单位必填', 1),
        array('model', 'require', '型号必填', 1),
        array('amount','0','请输入大于0的数量!',1,'>'),
    );
}
?>