<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Mail\CustomEmail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $_app = "";
    private $_page = "pages.users.";
    private $_data = [];

    public function __construct()
    {
        $this->_data['page_title'] = 'User';
    }

    public function index($status = '')
    {
        $this->_data['users'] = User::allUser();
        // return ([$this->_data]);
        $this->_data['roles'] = Role::pluck('name', 'id')->prepend('Select Role', '');
        $this->_data['approval_status'] = User::select('approval_status')->distinct()->get();
        // dd($this->_data['users'] );
        return view($this->_page . 'index', $this->_data);
    }
    public function approvalFilter(Request $request)
    {
        $status = $request->approval_status;
        if (is_null($status)) {
            return redirect(route('users.index'));
        }
        $this->_data['users'] = User::where('approval_status', $status)->get();
        $this->_data['roles'] = Role::pluck('name', 'id')->prepend('Select Role', '');
        $this->_data['approval_status'] = User::select('approval_status')->distinct()->get();
        return view($this->_page . 'index', $this->_data);
    }

    public function approveUser($id)
    {
        $user = User::find($id);
        $user->approval_status = '1';
        $user->save();
        Mail::to($user->email)->send(new CustomEmail([
            'body' => 'Your account has been approved'
        ]));

        return redirect(route('users.index'));
    }


    public function unapproveUser($id)
    {
        $user = User::find($id);
        $user->approval_status = '0';
        $user->save();
        Mail::to($user->email)->send(new CustomEmail([
            'body' => 'Your account has been suspended !!'
        ]));
        return redirect(route('users.index'));
    }

    public function create()
    {
        $this->_data['roles'] = Role::allRoles();
        return view($this->_page . 'create', $this->_data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role_id' => 'required',
            'password' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->only('email', 'name', 'role_id'))->with(['fail' => 'Fill all necessary data']);
        } else {
            DB::transaction(function () use ($request) {

                $data = $request->except('_token');
                $user = new User();
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->role_id = $data['role_id'];
                $user->password = Hash::make($data['password']);
                $user->save();
            });
            return redirect()->route('users.index')->with('success', 'Your Information has been Added .');
        }
    }

    public function edit($id)
    {
        $this->_data['roles'] = Role::allRoles();
        $this->_data['data'] = User::find($id);
        return view($this->_page . 'edit', $this->_data);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->only('email', 'name', 'role_id'))->with(['fail' => 'Fill all necessary data']);
        } else {
            DB::transaction(function () use ($request, $id) {


                $data = array_filter($request->input());

                $user = User::findOrFail($id);
                if (!empty($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                }
                $user->fill($data);
                $user->save();
            });
            return redirect()->route('users.index')->with('success', 'Your Information has been Updated .');
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (!in_array($user->role_id, [1])) {
            $user->delete();
            return redirect()->route('users.index')->with('delete-success', "Deleted");
        } else {
            return redirect()->route('users.index')->with('delete-fail', "This role user cannot be deleted .");
        }

        return redirect()->route('users.index')->with('delete-fail', "User could not be deleted.");
    }

    public function checkOldPassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
        ]);
        if (Hash::check($request['old_password'], Auth::user()->password)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateProfile()
    {
        $this->_data['data'] = User::where('id', Auth::user()->id)->first();
        return view($this->_page . 'update-profile', $this->_data);
    }

    public function updateProfileAction(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'new_c_password' => 'required|same:new_password'
        ]);

        if (Hash::check($request['old_password'], Auth::user()->password)) {
            if (User::where(['id' => Auth::user()->id])->update(['password' => Hash::make($request->new_password)])) {
                return redirect()->back()->with('success', 'Your password has been changed .');
            } else {
                return redirect()->back()->with('fail', 'Your password could not be changed .');
            }
        } else {
            return redirect()->back()->with('fail', 'Your Old Password is incorrect');
        }
    }

    public function checkUsername(Request $request)
    {
        $username = User::where(['username' => $request->username])->pluck('username')->first();
        if (!empty($username)) {
            return false;
        } else {
            return true;
        }
    }

    public function disabledUsersList()
    {
        $this->_data['users'] = User::whereNotIn('role_id', [1, 2])->where('status', 0)->get();
        $this->_data['roles'] = Role::pluck('name', 'id')->prepend('Select Role', '');
        return view($this->_page . 'disabled-users', $this->_data);
    }

    public function enableUser($id)
    {
        if ($id) {
            if (User::where('id', $id)->update(['status' => 1, 'attempts' => 0])) {
                return redirect()->back()->with('success', 'User has been successfully enabled .');
            }
        }
        return redirect()->back()->with('fail', 'User could not be enabled at the moment.');
    }
}
