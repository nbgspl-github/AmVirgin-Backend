<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;
use App\Interfaces\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends WebController {
	public function index($id = null) {
		if ($id == null) {
			$users = User::where('role', Roles::Customer)->get();
			return view('admin.users.list')->with('users', $users);
		} else {
			$user = User::find($id);
			if ($user == null) {
				return redirect(route('users.all'))->with('error', 'Could not find user by that Id.');
			} else {
				return view('admin.users.edit')->with('user', $user);
			}
		}
	}

	public function create(Request $request) {
		return view('admin.users.add');
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10'],
			'email' => ['bail', 'required', 'email', 'unique:users,email'],
			'password' => ['bail', 'nullable', 'string', 'min:4', 'max:128'],
			'status' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			flash($validator->errors()->first())->error()->important();
			return redirect(route('users.forms.add'));
		} else {
			User::instance()->
			setName($request->name)->
			setMobile($request->mobile)->
			setEmail($request->email)->
			setPassword($request->password)->
			setStatus($request->status)->
			save();
			flash('User added successfully.')->success()->important();
			return redirect(route('users.all'));
		}
	}

	public function update(Request $request, $id = null) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:4', 'max:50'],
			'mobile' => ['bail', 'required', 'digits:10'],
			'email' => ['bail', 'required', 'email', 'unique:users,email'],
			'status' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			flash($validator->errors()->first())->error()->important();
			return redirect(route('users.forms.add'));
		} else {
			User::instance()->
			setName($request->name)->
			setMobile($request->mobile)->
			setEmail($request->email)->
			setPassword($request->password)->
			setStatus($request->status)->
			save();
			flash('User added successfully.')->success()->important();
			return redirect(route('users.all'));
		}
	}
}