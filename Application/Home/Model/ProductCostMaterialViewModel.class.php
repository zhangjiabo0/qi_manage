<?php
/*---------------------------------------------------------------------------
  小微OA系统 - 让工作更轻松快乐 

  Copyright (c) 2013 http://www.smeoa.com All rights reserved.                                             


  Author:  jinzhu.yin<smeoa@qq.com>                         

  Support: https://git.oschina.net/smeoa/xiaowei               
 -------------------------------------------------------------------------*/

namespace Home\Model;
use Think\Model\ViewModel;

class  ProductCostMaterialViewModel extends ViewModel {
	public $viewFields=array(
		'ProductCostMaterial'=>array('*'),
		'StockInMaterial'=>array('name','unit','model','supplier_id','amount','unit_price','remain_amount','_on'=>'ProductCostMaterial.material_id=StockInMaterial.id'),
        'Product'=>array('name'=>'product_name','unit'=>'product_unit','model'=>'product_model','amount'=>'product_amount','production_date','_on'=>'ProductCostMaterial.product_id=Product.id')
		);
}
?>