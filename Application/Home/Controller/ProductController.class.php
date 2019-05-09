<?php
/*--------------------------------------------------------------------
 小微OA系统 - 让工作更轻松快乐

 Copyright (c) 2013 http://www.smeoa.com All rights reserved. 
 
 Author:  jinzhu.yin<smeoa@qq.com>

 Support: https://git.oschina.net/smeoa/xiaowei
--------------------------------------------------------------*/

namespace Home\Controller;

class ProductController extends HomeController {
	protected $config = array('app_type' => 'common','write' => 'modify,save_material,del_material','read'=>'winpop,stock_out');
	//过滤查询字段
	function _search_filter(&$map) {
		$map['is_del'] = array('eq', '0');
		if (!empty($_POST['keyword'])) {
			$where['content'] = array('like', '%' . $_POST['keyword'] . '%');
			$where['plan'] = array('like', '%' . $_POST['keyword'] . '%');
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}
	}

	public function index() {
		$plugin['date'] = true;
		$plugin['uploader'] = true;
		$plugin['editor'] = true;
		$this -> assign("plugin", $plugin);
		$this -> assign('user_id', get_user_id());

		$auth = $this -> config['auth'];
		$this -> assign('auth', $auth);

		$model = D("Product");
		$map = $this -> _search($model);
		if ($auth['admin']) {

		} else {
//			$map['user_id'] = get_user_id();
		}

		if (method_exists($this, '_search_filter')) {
			$this -> _search_filter($map);
		}

		if (!empty($model)) {
			$this -> _list($model, $map);
		}
		$this -> display();
	}

	function edit($id) {
		$plugin['date'] = true;
		$plugin['uploader'] = true;
		$plugin['editor'] = true;
		$this -> assign("plugin", $plugin);

		$this -> _edit($id);
	}

	public function add() {
		$plugin['date'] = true;
		$plugin['uploader'] = true;
		$plugin['editor'] = true;
		$no = date('YmdHis');
        $this -> assign("no", $no);
		$this -> assign("plugin", $plugin);
		$this -> display();
	}

    public function modify() {
        $plugin['date'] = true;
        $plugin['uploader'] = true;
        $plugin['editor'] = true;

        $model = D("ProductCostMaterialView");
        $map['product_id'] = I('id');
        if (!empty($model)) {
            $this -> _list($model, $map,'id asc');
        }

        $this -> assign("plugin", $plugin);
        $this -> assign("id", I('id'));
        $this -> display();
    }

    public function save_material(){
        $product_id = I('product_id');
        $id = I('id');
        $material_id = I('material_id');
        $cost_amount = I('cost_amount');
        if(empty($product_id)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'product_id不能为空'));
        }
        if(empty($material_id)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'原材料不能为空'));
        }
        if(empty($cost_amount) || $cost_amount<=0){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'消耗数量必须大于0'));
        }
        $find = D('StockInMaterial')->field('remain_amount')->where(array('id'=>$material_id))->find();

        $model = D('ProductCostMaterial');
        if (false === $model -> create()) {
            $this -> ajaxReturn(array('status'=>0,'msg'=>$model -> getError()));
        }
        if(empty($id)){
            /*新增当前数据对象 */
            if($find && $find['remain_amount']>=$cost_amount){
                $list = $model -> add();
                if ($list !== false) {//保存成功
                    D('StockInMaterial')->where(array('id'=>$material_id))->save(array('remain_amount'=>$find['remain_amount']-$cost_amount));
                    $this -> ajaxReturn(array('status'=>1,'msg'=>'ok','id'=>$list));
                } else {
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'新增失败!'));
                }
            }else {
                $this -> ajaxReturn(array('status'=>0,'msg'=>'新增失败，消耗数量大于剩余数量!'));
            }
        }else{
            /*修改当前数据对象 */
            $last_cost_amount = M('ProductCostMaterial')->where(array('id'=>$id))->find();
            if($last_cost_amount['material_id'] == $material_id){
                //还是选的同一种材料
                if($find && $last_cost_amount && $find['remain_amount']>=$cost_amount-$last_cost_amount['cost_amount']){
                    $list = $model -> save();
                    if ($list !== false) {//保存成功
                        D('StockInMaterial')->where(array('id'=>$material_id))->save(array('remain_amount'=>$find['remain_amount']-$cost_amount+$last_cost_amount['cost_amount']));
                        $this -> ajaxReturn(array('status'=>11,'msg'=>'ok'));
                    } else {
                        $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败!'));
                    }
                }else{
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败，消耗数量大于剩余数量!'));
                }
            }else{
                //选的不同材料
                if($find && $last_cost_amount && $find['remain_amount']>=$cost_amount){
                    $list = $model -> save();
                    if ($list !== false) {//保存成功
                        $find_last_amount = D('StockInMaterial')->field('remain_amount')->where(array('id'=>$last_cost_amount['material_id']))->find();
                        if($find_last_amount){
                            $res1 = D('StockInMaterial')->where(array('id'=>$last_cost_amount['material_id']))->save(array('remain_amount'=>$find_last_amount['remain_amount']+$last_cost_amount['cost_amount']));
                            $res2 = D('StockInMaterial')->where(array('id'=>$material_id))->save(array('remain_amount'=>$find['remain_amount']-$cost_amount));
                            if($res1 && $res2){
                                $this -> ajaxReturn(array('status'=>11,'msg'=>'ok'));
                            }else{
                                $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败!'));
                            }
                        }else{
                            $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败!'));
                        }
                    } else {
                        $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败!'));
                    }
                }else{
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败，消耗数量大于剩余数量!'));
                }
            }

        }
    }

    function del_material(){
        $id = I('id');
        $find_cost = M('ProductCostMaterial')->where(array('id'=>$id))->find();
        $find_material = M('StockInMaterial')->where(array('id'=>$find_cost['material_id']))->find();
        if($find_cost && $find_material){
            $res = M('StockInMaterial')->where(array('id'=>$find_cost['material_id']))->save(array('remain_amount'=>$find_material['remain_amount']+$find_cost['cost_amount']));
            if($res){
                $r = M('ProductCostMaterial')->where(array('id'=>$id))->delete();
                if($r){
                    $this -> ajaxReturn(array('status'=>1,'msg'=>'ok'));
                }else{
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'删除失败!'));
                }
            }else{
                $this -> ajaxReturn(array('status'=>0,'msg'=>'删除失败!'));
            }
        }else{
            $this -> ajaxReturn(array('status'=>0,'msg'=>'删除失败!'));
        }
    }
	
	function upload() {
		$this -> _upload();
	}

	function down($attach_id) {
		$this -> _down($attach_id);
	}

	/** 插入新新数据  **/
	protected function _insert($name = CONTROLLER_NAME) {
		$model = D($name);
		if (false === $model -> create()) {
			$this -> error($model -> getError());
		}
		if (in_array('user_id', $model -> getDbFields())) {
			$model -> user_id = get_user_id();
		};
		if (in_array('user_name', $model -> getDbFields())) {
			$model -> user_name = get_user_name();
		};
		if (in_array('created_time', $model -> getDbFields())) {
			$model -> created_time = time();
		};
		if (in_array('updated_time', $model -> getDbFields())) {
			$model -> updated_time = time();
		};
        if (in_array('remain_amount', $model -> getDbFields())) {
            $model -> remain_amount = $model -> amount;
        };
		/*保存当前数据对象 */
		$list = $model -> add();
		if ($list !== false) {//保存成功
			$this -> assign('jumpUrl', get_return_url());
			$this -> success('新增成功!');
		} else {
			$this -> error('新增失败!');
			//失败提示
		}
	}

    protected function _update($name = CONTROLLER_NAME) {
        $model = D($name);
        if (false === $model -> create()) {
            $this -> error($model -> getError());
        }

        if (in_array('updated_time', $model -> getDbFields())) {
            $model -> updated_time = time();
        };
        $id = I('id');
        $find = M('Product')->where(array('id'=>$id))->find();
        if($find){
            if(I('amount')>=$find['amount']-$find['remain_amount']){
                $model -> remain_amount = I('amount')-($find['amount']-$find['remain_amount']);
                $list = $model -> save();
                if (false !== $list) {
                    $this -> assign('jumpUrl', get_return_url());
                    $this -> success('编辑成功!');
                    //成功提示
                } else {
                    $this -> error('编辑失败!');
                    //错误提示
                }
            }else{
                $this -> error('编辑失败!,数量不能小于已出库的数量');
            }
        }else{
            $this -> error('编辑失败!');
        }

    }

    public function winpop() {
        $node = M("Product");
        $id = $_GET['id'];
        $menu = array();
        $menu = $node ->where(array('remain_amount'=>array('gt',0))) -> select();
        $tree = list_to_tree($menu);
        //dump($node);
        $this -> assign('menu', popup_tree_menu_product($tree));
        $this -> assign('id', $id);
        $this -> display();
    }

    public function stock_out(){
	    $model = D('StockOutProductView');
        $map = $this -> _search($model);
        $map['product_id'] = I('id');
        if (!empty($model)) {
            $this -> _list($model, $map);
        }
        $this -> display();
    }

}
