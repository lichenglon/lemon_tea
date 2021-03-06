<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\BaseController;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use Illuminate\Support\Facades\Session;

class RoleController extends BaseController
{
    public $role_status = ['禁用','启用'];

    private $role;

    public function __construct()
    {
        parent::__construct();
        $this->role = new Role;
    }

    public function index()
    {
        $role_lists = Role::paginate(10);

        foreach($role_lists as &$value){
            $value->department_name = DB::table('departments')->where('id',$value->department_id)->value('name');
            $value->parse_status = $this->role_status[$value->status];
            if($value->pid != 0){
                $rs = Role::where('id',$value->pid)->first(['name']);
                $value->parent_depart_name = $rs->name;
            }
        }
        return view('account.role_index', ['role_lists' => $role_lists]);
    }

    public function create()
    {
        $roles = Role::get(['id','name']);
        $departmentList = $this->getSelectList('departments');
        $menu_lists = $this->getAllMenu(true);
        return view('account.role_create', ['menu_lists'=>$menu_lists,'roles'=>$roles, 'departmentList'=>$departmentList]);
    }

    public function store(Request $request)
    {
        $data = [
            'name' =>$request->name,
            'pid' =>$request->pid,
            'status' =>$request->status,
            'menu_role_id' =>empty($request->menu_role_id) ? '' : implode(',', $request->menu_role_id),
            'create_time' => date('Y-m-d H:i:s')
        ];
        $rs = Role::insert($data);
        if($rs)
        {
            //更新操作日志
            $id = Session::get('user_id');
            $operate_name = DB::table('accounts')->where('id',$id)->value('name');
            $operate = "add a new role , is ".$request->name;
            //$operate = "新增了角色，角色名为：".$request->name;
            $operate_log = [
                'operate' => $operate,
                'operate_name' => $operate_name,
                'operate_time' => time()
            ];

            DB::table('operate_log')->insert($operate_log);

            return $this->ajaxSuccess('新增角色成功！', url('/account/role'));
        }else{
            return $this->ajaxSuccess('新增角色失败！', url('/account/role/create'));
        }
    }

    public function edit($id)
    {
        $lists = Role::find($id);
        $roles = Role::where('status',1)->get(['id','name']);
        $departmentList = $this->getSelectList('departments');
        $menu_lists = $this->getAllMenu(true);
        $lists->menu_role_id_arr = explode(',',$lists->menu_role_id);
        return view('account.role_edit',['menu_lists'=>$menu_lists,'lists'=>$lists,'roles'=>$roles, 'departmentList'=>$departmentList]);
    }

    public function update(Request $request)
    {
        $data['name'] = $request->name;
        $data['pid'] = $request->pid;
        $data['status'] = $request->status;
        $data['menu_role_id'] = empty($request->menu_role_id) ? '' : implode(',', $request->menu_role_id);

        Role::where('id', $request->id)->update($data);

        //更新操作日志
        $id = Session::get('user_id');
        $operate_name = DB::table('accounts')->where('id',$id)->value('name');
        $operate = "modify a role's authority , is ".$request->name;
        //$operate = "更新了角色权限，角色名为：".$request->name;
        $operate_log = [
            'operate' => $operate,
            'operate_name' => $operate_name,
            'operate_time' => time()
        ];

        DB::table('operate_log')->insert($operate_log);


        return $this->ajaxSuccess('编辑角色成功！', url('/account/role'));

    }

    public function updateStatus(Request $request)
    {
        if($request->id == '15'){
            return $this->ajaxError('不允许操作管理员', url('/account/role'));
        }

        $data['status'] = !$request->status;

        $rs = Role::where('id', $request->id)
            ->update($data);
        if($rs){
            $account = new Account();
            $account->where('role_id',$request->id)->update($data);

            //更新操作日志
            $id = Session::get('user_id');
            $operate_name = DB::table('accounts')->where('id',$id)->value('name');
            $name2 = DB::table('roles')->where('id',$request->id)->value('name');
            $operate = "modify a role's status , is ".$name2;
            //$operate = "更新了角色状态，角色名为：".$name2;
            $operate_log = [
                'operate' => $operate,
                'operate_name' => $operate_name,
                'operate_time' => time()
            ];

            DB::table('operate_log')->insert($operate_log);

            return $this->ajaxSuccess('操作成功！', url('/account/role'));
        }
        return $this->ajaxError('操作失败！', url('/account/role'));
    }

    public function destroy($id)
    {
        $pname = Role::where('pid',$id)->first();
        if(is_object($pname)){
            return $this->ajaxError('删除角色失败！请先删除子级', url('/account/role'));
        }else{
            if($id == '15'){
                return $this->ajaxError('删除角色失败！不能删除管理员', url('/account/role'));
            }else{

                $name2 = DB::table('roles')->where('id',$id)->value('name');
                $rs = Role::destroy($id);
                if($rs){
                    $account = new Account();
                    $account->where('role_id',$id)->update(['status'=>'0']);

                    //更新操作日志
                    $uid = Session::get('user_id');
                    $operate_name = DB::table('accounts')->where('id',$uid)->value('name');
                    $operate = "delete a role , is ".$name2;
                    //$operate = "删除了角色，角色名为：".$name2;
                    $operate_log = [
                        'operate' => $operate,
                        'operate_name' => $operate_name,
                        'operate_time' => time()
                    ];

                    DB::table('operate_log')->insert($operate_log);

                    return $this->ajaxSuccess('删除角色成功！', url('/account/role'));
                }
            }

        }
        return $this->ajaxError('删除角色失败！', url('/account/role'));


    }


}
