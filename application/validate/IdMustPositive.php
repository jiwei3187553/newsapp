<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/26/0026
 * Time: 14:50
 */
namespace app\validate;
class IdMustPositive extends BaseVailate
{
    protected $rule=[
        'id'=>'require|IdMustPositive',
        'status'=>'in:0,1'
    ];
    protected $message=[
        'id.require'=>'id必须填写',
        'id.IdMustPositive'=>'id必须是正整数',
        'status'=>'状态不正确'
    ];
}