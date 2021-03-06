<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/2/0002
 * Time: 20:50
 */
namespace app\api\controller\v1;
use app\admin\model\UserNews;
use app\validate\IdMustPositive;
use think\Request;
use app\admin\model\News as NewsModel;
class Upvote extends AuthBase
{
    /**
     * 给文章点赞
     * @return array
     */
    public function save(Request $request)
    {

        (new IdMustPositive())->goCheck();
        $data=$request->param();
        $id=$data['id'];
        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->find();
        if(empty($news))
        {
            return showJson(config('admin.app_error'),'文章不存在',[],404);
        }
        $where=[
            'user_id'=>$this->user->id,
            'news_id'=>$id
        ];
        $usernews=UserNews::where($where)->find();
        if($usernews)
        {
            return showJson(config('admin.app_error'),'该文章已经被您点赞过了,不能再赞了',[],403);
        }
        $where['create_time']=time();
        try{
           if(UserNews::create($where))
           {
               $news->upvote_count=$news->upvote_count+1;
               if($news->save()!==false)
               {
                   return showJson(config('admin.app_success'),'点赞成功',[],201);
               }
               return showJson(config('admin.app_error'),'点赞失败',[],403);
           }
           else
           {
               return showJson(config('admin.app_error'),'点赞失败',[],403);
           }
        }catch (\Exception $e)
        {
            return showJson(config('admin.app_error'),'内部错误',[],500);
        }
    }

    /**
     * 取消赞
     * @param Request $request
     * @return \think\response\Json
     */
    public function delete(Request $request)
    {

        (new IdMustPositive())->goCheck();
        $data=$request->param();
        $id=$data['id'];
        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->find();
        if(empty($news))
        {
            return showJson(config('admin.app_error'),'文章不存在',[],404);
        }
        $where=[
            'user_id'=>$this->user->id,
            'news_id'=>$id
        ];
        $usernews=UserNews::where($where)->find();
        if(empty($usernews))
        {
            return showJson(config('admin.app_error'),'没有点赞过这篇文章',[],403);
        }
        try{
            if(UserNews::destroy($where)!==false)
            {
                $news->upvote_count=$news->upvote_count-1;
                if($news->save()!==false)
                {
                    return showJson(config('admin.app_success'),'取消赞成功',[],201);
                }
                return showJson(config('admin.app_error'),'取消赞失败',[],403);
            }
            else
            {
                return showJson(config('admin.app_error'),'取消赞失败',[],403);
            }
        }catch (\Exception $e)
        {
            return showJson(config('admin.app_error'),'内部错误',[],500);
        }
    }

    /**
     * 获取是否已经给某篇文章点赞
     * @param Request $request
     * @return \think\response\Json
     */
    public function read(Request $request)
    {
        (new IdMustPositive())->goCheck();
        $data=$request->param();
        $id=$data['id'];
        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->find();
        if(empty($news))
        {
            return showJson(config('admin.app_error'),'文章不存在',[],404);
        }
        $where=[
            'user_id'=>$this->user->id,
            'news_id'=>$id
        ];
        $usernews=UserNews::where($where)->find();
        if(empty($usernews))
        {
            return showJson(config('admin.app_error'),'OK',['isUpvote'=>0],404);
        }else
        {
            return showJson(config('admin.app_success'),'OK',['isUpvote'=>1],200);
        }
    }
}