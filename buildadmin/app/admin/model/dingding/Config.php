<?php

namespace app\admin\model\dingding;

use think\Model;

/**
 * Config
 * @controllerUrl 'dingdingConfig'
 */
class Config extends Model
{
    // 表名
    protected $name = 'dingding_config';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    public function setDefaultSwitchAttr($value, $row)
    {
        return $value ? '1' : '0';
    }
}