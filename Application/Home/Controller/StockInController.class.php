<?php
/*--------------------------------------------------------------------
 小微OA系统 - 让工作更轻松快乐

 Copyright (c) 2013 http://www.smeoa.com All rights reserved. 
 
 Author:  jinzhu.yin<smeoa@qq.com>

 Support: https://git.oschina.net/smeoa/xiaowei
--------------------------------------------------------------*/

namespace Home\Controller;

class StockInController extends HomeController {
	protected $config = array('app_type' => 'common','write' => 'modify,save_material,del_material','read'=>'winpop');
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

		$model = D("StockIn");
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

        $model = D("StockInMaterial");
        $map['stock_in_id'] = I('id');
        if (!empty($model)) {
            $this -> _list($model, $map,'id asc');
        }

        $this -> assign("plugin", $plugin);
        $this -> assign("id", I('id'));
        $this -> display();
    }

    public function save_material(){
        $stock_in_id = I('stock_in_id');
        $id = I('id');
        $name = I('name');
        $model = I('model');
        $unit = I('unit');
        $amount = I('amount');
        $unit_price = I('unit_price');
        $supplier_id = I('supplier_id');
        if(empty($stock_in_id)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'stock_in_id不能为空'));
        }
        if(empty($name)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'原材料名称不能为空'));
        }
        if(empty($model)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'批号不能为空'));
        }
        if(empty($unit)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'单位不能为空'));
        }
        if(empty($amount) || $amount<=0){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'数量必须大于0'));
        }
        if(empty($unit_price) || $unit_price<=0){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'单价必须大于0'));
        }
        if(empty($supplier_id)){
            $this -> ajaxReturn(array('status'=>0,'msg'=>'供应商不能为空'));
        }
        $model = D('StockInMaterial');
        if (false === $model -> create()) {
            $this -> ajaxReturn(array('status'=>0,'msg'=>$model -> getError()));
        }
        if(empty($id)){
            /*保存当前数据对象 */
            $model -> remain_amount = $model -> amount;
            $list = $model -> add();
            if ($list !== false) {//保存成功
                $this -> ajaxReturn(array('status'=>1,'msg'=>'ok','id'=>$list));
            } else {
                $this -> ajaxReturn(array('status'=>0,'msg'=>'新增失败!'));
            }
        }else{
            $last = M('StockInMaterial')->where(array('id'=>$id))->find();
            if($last['amount']-$last['remain_amount']<=$amount){
                $model->remain_amount = $last['remain_amount']-($last['amount']-$amount);
                $list = $model -> save();
                if ($list !== false) {//保存成功
                    $this -> ajaxReturn(array('status'=>11,'msg'=>'ok'));
                } else {
                    $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败!'));
                }
            }else{
                $this -> ajaxReturn(array('status'=>0,'msg'=>'修改失败,该材料已消耗'.($last['amount']-$last['remain_amount']).$last['unit'].'!'));
            }

        }
    }

    function del_material(){
        $id = I('id');
        $find = M('StockInMaterial')->where(array('id'=>$id))->find();
        if($find['amount'] > $find['remain_amount']){
            //已经有原材料消耗，不允许删除
            $this -> ajaxReturn(array('status'=>0,'msg'=>'删除失败，已经有原材料消耗，不允许删除!'));
        }
        $r = M('StockInMaterial')->where(array('id'=>$id))->delete();
        if($r){
            $this -> ajaxReturn(array('status'=>1,'msg'=>'ok'));
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
