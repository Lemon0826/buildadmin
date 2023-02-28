<?php

namespace app\admin\controller\dingding;

use app\common\controller\Backend;
use Exception;
use think\db\exception\PDOException;
use think\exception\ValidateException;
use think\facade\Db;

/**
 * 钉钉配置管理
 *
 */
class Config extends Backend
{
    /**
     * Config模型对象
     * @var \app\admin\model\dingding\Config
     */
    protected $model = null;

    protected $quickSearchField = ['id', 'name'];

    protected $defaultSortField = 'id,desc';

    protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\dingding\Config;
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data) {
                $this->error(__('Parameter %s can not be empty', ['']));
            }

            $data   = $this->excludeFields($data);
            $result = false;
            Db::startTrans();
            try {
                if (isset($data['default_switch']) && $data['default_switch'] == '1') {
                    $this->model->where('default_switch', '1')->save(['default_switch' => '0']);
                }

                if (isset($data['security']) && ($data['security'] == 'none' || $data['security'] == 'ip')) {
                    $data['security_value'] = null;
                }

                $result = $this->model->save($data);
                Db::commit();
            } catch (ValidateException|PDOException|Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                $this->success(__('Added successfully'));
            } else {
                $this->error(__('No rows were added'));
            }
        }

        $this->error(__('Parameter error'));
    }

    public function edit($id = null)
    {
        $row = $this->model->find($id);
        if (!$row) {
            $this->error(__('Record not found'));
        }

        $dataLimitAdminIds = $this->getDataLimitAdminIds();
        if ($dataLimitAdminIds && !in_array($row[$this->dataLimitField], $dataLimitAdminIds)) {
            $this->error(__('You have no permission'));
        }

        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!$data) {
                $this->error(__('Parameter %s can not be empty', ['']));
            }

            if (isset($data['default_switch']) && $data['default_switch'] != $row['default_switch'] && $data['default_switch'] == '1') {
                $this->model->where('default_switch', '1')->save(['default_switch' => '0']);
            }

            if (isset($data['security']) && ($data['security'] == 'none' || $data['security'] == 'ip')) {
                $data['security_value'] = null;
            }

            $data   = $this->excludeFields($data);
            $result = false;
            Db::startTrans();
            try {
                $result = $row->save($data);
                Db::commit();
            } catch (PDOException|Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                $this->success(__('Update successful'));
            } else {
                $this->error(__('No rows updated'));
            }
        }

        $this->success('', [
            'row' => $row
        ]);
    }
}
