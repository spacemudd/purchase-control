<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class InvitesController extends Controller
{
    public function index()
    {
        $invites = Invite::get();
        return view('invites.index', compact('invites'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|max:255',
            'role_name' => 'nullable|max:255',
        ]);

        do {
            $token = str_random();
        }
        while (Invite::where('token', $token)->first());

        $invite = Invite::create([
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'token' => $token,
            'role_name' => $request->get('role_name'),
        ]);

        Mail::to($invite->email)
            ->queue(new InvitationMail($invite));

        session()->flash('status', 'Successfully invited: ' . $invite->email);

        return redirect()->route('users.index');
    }

    public function accept($token)
    {
        if (!$invite = Invite::where('token', $token)->first()) abort(404);

        // If the user is logged in, log them out.
        if(!Auth::guest()) Auth::logout();

        return view('invites.accept', compact('invite'));
    }

    public function processAccept(Request $request)
    {
        // If the user is logged in, log them out.
        if(!Auth::guest()) Auth::logout();

        $request->validate([
            'token' => 'required|exists:invites,token',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $invite = Invite::where('token', $request['token'])->firstOrFail();

        // If the username already exists, delete the invite.
        if(User::where('username', $invite->username)->exists()) {
            $invite->delete();

            return redirect()->to('/');
        }

        $user = DB::transaction(function() use ($request, $invite) {

            $user = User::create([
                'username' => $invite->username,
                'name' => $invite->name,
                'email' => $invite->email,
                'phone' => $invite->phone,
                'password' => Hash::make($request['password']),
            ]);

            if(Role::findByName($invite->role_name)) {
                $user->assignRole($invite->role_name);
            }

            $invite->delete();

            return $user;
        }, 2);

        Auth::loginUsingId($user->id);

        return redirect()->route('dashboard.index');
    }

    public function delete($id)
    {
        $this->authorize('invite-users');

        Invite::where('id', $id)->delete();

        return redirect()->back();
    }
}
