<?php

namespace app\admin\model\dingding;

use think\Model;

/**
 * Variable
 * @controllerUrl 'dingdingVariable'
 */
class Variable extends Model
{
    // 表名
    protected $name = 'dingding_variable';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

}