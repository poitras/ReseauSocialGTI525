<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use Image;
use File;

class ProfileSettingsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('users.profileSettings', ['user' => $user]);

    }

    public function update_avatar(Request $request)
    {
        $user = Auth::user();
        $filename = "default.png";

        //delete users old image before uploading new one
        if($user->avatar != "default.png")
        {
            File::Delete( public_path( $user->avatar ) );
        }

        if($request->avatarURL != null)
        {
            $filename = $request->avatarURL;
        }

        // Handle user upload of avatar
        if ($request->hasFile('avatarFile'))
        {
            $avatarFILE = $request->file('avatarFile');
            $filename = 'uploads/avatars/' . time() . '.' . $avatarFILE->getClientOriginalExtension();
            Image::make($avatarFILE)->fit(300, 300)->save( public_path( $filename ) );
        }

        $user->avatar = $filename;
        $user->save();

        return redirect('profileSettings')->with('succes', 'Votre photo de profil à été modifié avec succès');

    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if($request->first_name != null AND $request->last_name != null AND $request->email != null AND $request->address != null AND $request->city != null AND $request->province != null AND $request->postal_code != null)
        {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->province = $request->province;
            $user->postal_code = $request->postal_code;

            $user->save();

            return redirect('profileSettings')->with('succes', 'Vos paramètres on été modifiés');
        }



        return redirect('profileSettings')->with('fail', 'Erreur, Champs incorrect');

    }

    public function updatePassword(Request $request)
    {
        if(Auth::Check())
        {
            if($request->password == $request->password_confirmation)
            {
                $current_password = Auth::User()->password;
                if (Hash::check($request->current_password, $current_password))
                {
                    $user = Auth::User();
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return redirect('profileSettings')->with('succes', 'Mot de passe modifé');
                }

            }
        }
        return redirect('profileSettings')->with('fail', 'Erreur, mot de passe non modifié');

    }
}
