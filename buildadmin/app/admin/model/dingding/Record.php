<?php

namespace app\admin\model\dingding;

use think\Model;

/**
 * Record
 * @controllerUrl 'dingdingRecord'
 */
class Record extends Model
{
    // 表名
    protected $name = 'dingding_record';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    public function getContentAttr($value, $row)
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function config()
    {
        return $this->belongsTo(Config::class, 'config_id', 'id');
    }
}
