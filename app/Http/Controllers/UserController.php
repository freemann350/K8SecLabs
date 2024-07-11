<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSelfUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return view('users.index', ['users' =>$users]);
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        try {
            $formData = $request->validated();

            User::create([
                'name' => $formData['name'],
                'password' => $formData['password'],
                'email' => $formData['email'],
                'role' => $formData['role'],
            ]);
    
            return redirect()->route('Users.index')->with('success-msg', "An User was added with success");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('users.edit', ['user' => $user]);
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $formData = $request->validated();

            $user = User::findOrFail($id);
            $user->update($formData);
            
            $name = $formData['name'];
            return redirect()->route('Users.index')->withInput()->with('success-msg', "$name was updated with success");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function editMe()
    {
        try {
            $user = User::findOrFail(auth()->user()->id);

            return view('users.editMe', ['user' => $user]);
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function updateMe(UserSelfUpdateRequest $request)
    {
        try {
            $formData = $request->validated();
            $user = User::findOrFail(auth()->user()->id);
            $user->update($formData);
            
            return redirect()->back()->withInput()->with('success-msg', "Your information was updated with success");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function updatePassword(PasswordRequest $request, $id)
    {
        try {
            $formData = $request->validated();

            $user = User::findOrFail($id);
            $user->update($formData);
    
            return redirect()->back()->withInput()->with('success-msg', "Your password was updated with success");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $name=$user->name;
            
            $user->delete();
    
            return redirect()->route('Users.index')->with('success-msg', "$name was deleted with success");
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->back()->withInput()->with('error_msg', $errormsg);
        }
    }

    private function createError($code, $status, $message) {
        $errormsg['code']= $code;
        $errormsg['status']= $status;
        $errormsg['message']= $message;

        return $errormsg;
    }
}
