<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/14/0014
 * Time: 13:09
 */
namespace app\admin\model;
use think\Model;
class AdminUser extends Model
{
    protected $insert = ['status' => 1];
    protected $autoWriteTimestamp = true;
}