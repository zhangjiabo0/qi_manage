<?php
/*--------------------------------------------------------------------
 小微OA系统 - 让工作更轻松快乐

 Copyright (c) 2013 http://www.smeoa.com All rights reserved. 
 
 Author:  jinzhu.yin<smeoa@qq.com>

 Support: https://git.oschina.net/smeoa/xiaowei
--------------------------------------------------------------*/

namespace Home\Controller;

class StockOutController extends HomeController {
	protected $config = array('app_type' => 'common','write' => 'modify,save_product,del_product','read'=>'winpop');
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

		$model = D("StockOut");
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

        $model = D("StockOutProductView");
        $map['stock_out_id'] = I('id');
        if (!empty($model)) {
            $this -> _list($model, $map,'id asc');
        }

        $this -> assign("plugin", $plugin);
        $this -> assign("id", I('id'));
        $this -> display();
    }

    public function save_product(){
        $stock_out_id = I('stock_out_id');
        $id = I('id');
        $amount = I('amount');
        $product_id = I('product_id');
        if(empty($stock_out_id)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'stock_out_id不能为空'));
        }
        if(empty($amount) || $amount<=0){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'数量必须大于0'));
        }
        if(empty($product_id)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'产品不能为空'));
        }
        $find = D('Product')->field('remain_amount')->where(array('id'=>$product_id))->find();

        $model = D('StockOutProduct');
        if (false === $model -> create()) {
            $this -> ajaxReturn(array('status'=>0,'msg'=>$model -> getError()));
        }
        if(empty($id)){
            /*保存当前数据对象 */
            if($find && $find['remain_amount']>=$amount){
                $list = $model -> add();
                if ($list !== false) {//保存成功
                    D('Product')->where(array('id'=>$product_id))->save(array('remain_amount'=>$find['remain_amount']-$amount));
                    $this -> ajaxReturn(array('status'=>1,'msg'=>'ok','id'=>$list));
                } else {
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'新增失败!'));
                }
            }else{
                $this -> ajaxReturn(array('status'=>0,'msg'=>'新增失败，消耗数量大于剩余数量!'));
            }

        }else{
            $last_cost_amount = M('StockOutProduct')->where(array('id'=>$id))->find();
            if($last_cost_amount['product_id'] == $product_id) {
                //还是选的同一种产品
                if($find && $last_cost_amount && $find['remain_amount']>=$amount-$last_cost_amount['amount']){
                    $list = $model -> save();
                    if ($list !== false) {//保存成功
                        D('Product')->where(array('id'=>$product_id))->save(array('remain_amount'=>$find['remain_amount']-$amount+$last_cost_amount['amount']));
                        $this -> ajaxReturn(array('status'=>11,'msg'=>'ok'));
                    } else {
                        $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败!'));
                    }
                }else{
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败，消耗数量大于剩余数量!'));
                }
            }else{
                //选的不同材料
                if($find && $last_cost_amount && $find['remain_amount']>=$amount){
                    $list = $model -> save();
                    if ($list !== false) {//保存成功
                        $find_last_amount = D('Product')->field('remain_amount')->where(array('id'=>$last_cost_amount['product_id']))->find();
                        if($find_last_amount){
                            $res1 = D('Product')->where(array('id'=>$last_cost_amount['product_id']))->save(array('remain_amount'=>$find_last_amount['remain_amount']+$last_cost_amount['amount']));
                            $res2 = D('Product')->where(array('id'=>$product_id))->save(array('remain_amount'=>$find['remain_amount']-$amount));
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

    function del_product(){
        $id = I('id');
        $find_stock_out_product = M('StockOutProduct')->where(array('id'=>$id))->find();
        $find_product = M('Product')->where(array('id'=>$find_stock_out_product['product_id']))->find();
        if($find_product && $find_stock_out_product){
            $res = M('Product')->where(array('id'=>$find_stock_out_product['product_id']))->save(array('remain_amount'=>$find_product['remain_amount']+$find_stock_out_product['amount']));
            if($res){
                $r = M('StockOutProduct')->where(array('id'=>$id))->delete();
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
	protected function _insert($name="StockIn") {
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

    public function winpop() {
        $node = M("StockInMaterial");
        $id = $_GET['id'];
        $menu = array();
        $menu = $node ->where(array('remain_amount'=>array('gt',0))) -> select();
        $tree = list_to_tree($menu);
        //dump($node);
        $this -> assign('menu', popup_tree_menu_material($tree));
        $this -> assign('id', $id);
        $this -> display();
    }

}
