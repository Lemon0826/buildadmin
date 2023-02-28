<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 测试用例管理
 *
 */
class HmzxCsal extends Backend
{
    /**
     * HmzxCsal模型对象
     * @var \app\admin\model\HmzxCsal
     */
    protected $model = null;
    
    protected $defaultSortField = 'ID,asc';

    protected $quickSearchField = ['ID', 'radio', 'terrace', 'function_obj', 'case_ID', 'case_level', 'case_danger', 'case_infor', 'case_stand', 'case_envir', 'case_place'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\HmzxCsal;
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}