<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Request\Admin\MenuRequest;
use Donjan\Permission\Models\Permission;
use Hyperf\Utils\ApplicationContext;
use Donjan\Permission\PermissionRegistrar;

class MenuController extends BaseController
{
	public function index(Permission $permission)
	{
		$list = $permission::getMenuList();
		return $this->render->render('admin.menu.index',compact('list'));
	}

	public function store(MenuRequest $request,Permission $permission)
	{
		$data['parent_id']=$request->input('parent_id');
		$data['name']=$request->input('name');
		$data['display_name']=$request->input('display_name');
		$data['url']=$request->input('url');
		$data['icon']=$request->input('icon');
		$data['guard_name']=$request->input('guard_name');
		$data['sort']=$request->input('sort');
		$data['status']=$request->input('status');

		$result = $permission->create($data);

		if($result){

			return $this->helper->success();
		}

		throw new AppBadRequestException('操作失败',Code::OPERATE_ERROR);
	}

	public function update(MenuRequest $request,Permission $permission)
	{
		$data['parent_id']=$request->input('parent_id');
		$data['name']=$request->input('name');
		$data['display_name']=$request->input('display_name');
		$data['url']=$request->input('url');
		$data['icon']=$request->input('icon');
		$data['guard_name']=$request->input('guard_name');
		$data['sort']=$request->input('sort');
		$data['status']=$request->input('status');
		$result = $permission->where('id',$request->input('id'))->update($data);

		if($result){
			ApplicationContext::getContainer()->get(PermissionRegistrar::class)->forgetCachedPermissions();
			return $this->helper->success();
		}

		throw new AppBadRequestException('操作失败',Code::OPERATE_ERROR);
	}

	public function del(Permission $permission)
	{
		$id = $this->request->input('id');
		if(!is_numeric($id)){
			throw new AppBadRequestException('操作失败',Code::OPERATE_ERROR);
		}

		$result = $permission->where('id',$id)->delete();

		if($result){
			ApplicationContext::getContainer()->get(PermissionRegistrar::class)->forgetCachedPermissions();
			return $this->helper->success();
		}

		throw new AppBadRequestException('操作失败',Code::OPERATE_ERROR);
	}
}