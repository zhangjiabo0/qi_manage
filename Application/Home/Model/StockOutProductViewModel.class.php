<?php
/*---------------------------------------------------------------------------
  小微OA系统 - 让工作更轻松快乐 

  Copyright (c) 2013 http://www.smeoa.com All rights reserved.                                             


  Author:  jinzhu.yin<smeoa@qq.com>                         

  Support: https://git.oschina.net/smeoa/xiaowei               
 -------------------------------------------------------------------------*/

namespace Home\Model;
use Think\Model\ViewModel;

class  StockOutProductViewModel extends ViewModel {
	public $viewFields=array(
		'StockOutProduct'=>array('*'),
		'Product'=>array('name'=>'product_name','unit','model','production_date','_on'=>'StockOutProduct.product_id=Product.id'),
	    'StockOut'=>array('no','user_id','user_name','created_time','updated_time','_on'=>'StockOutProduct.stock_out_id=StockOut.id'),
    );
}
?>