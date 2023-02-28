<?php

namespace app\admin\controller\dingding;

use app\common\controller\Backend;

/**
 * 通知记录管理
 *
 */
class Record extends Backend
{
    /**
     * Record模型对象
     * @var \app\admin\model\dingding\Record
     */
    protected $model = null;

    protected $quickSearchField = ['id'];

    protected $defaultSortField = 'id,desc';

    protected $withJoinTable = ['template', 'config'];

    protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\dingding\Record;
    }

    /**
     * 查看
     */
    public function index()
    {
        // 设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);

        // 如果是select则转发到select方法,若select未重写,其实还是继续执行index
        if ($this->request->param('select')) {
            $this->select();
        }

        list($where, $alias, $limit, $order) = $this->queryBuilder();
        $res = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);
        $res->visible(['template' => ['name'], 'config' => ['name']]);

        $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }
}
