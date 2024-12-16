<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Dormitory\Entities\Dormitory;
use Modules\Dormitory\Entities\DormitoryRoom;
use Modules\ControlSystem\Entities\Modelset_time;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\User\Entities\Modules_Users_Comment;
use Modules\User\Entities\Modules_User_review;
use Modules\User\Entities\Modules_User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        return view('core::home');
    }

    public function account(Request $request)
    {
        $id = auth()->user()->id;
        $previousUrl = session('ooo');
        
        $review = Modules_User_review::where('users_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->paginate(10);
    
        $data_user = DormitoryUser::where('users_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->first();
    
        $user = Modules_User::where('id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->first();
    
        $comments = Modules_Users_Comment::where('users_id', $id)
            ->where('status_delete', '!=', 'Disable')
            ->get();
    
        // Initialize default values for room and dormitory
        $room = new \stdClass();
        $dormitory = new \stdClass();
        $room->name = 'ไม่มีห้องพัก';
        $dormitory->name = 'ไม่มีหอพัก';
    
        if ($data_user) {
            $room = DormitoryRoom::where('id', $data_user->room_id)
                ->where('status_delete', '!=', 'Disable')
                ->first();
    
            if ($room) {
                $dormitory = Dormitory::where('id', $room->dormitorys_id)
                    ->where('status_delete', '!=', 'Disable')
                    ->first();
            }
        }
    
        return view('core::account', compact('data_user','review', 'id', 'user', 'previousUrl', 'comments', 'dormitory', 'room'));
    }
    


    public function change_img_pro(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($user->imgpro && Storage::disk('public')->exists($user->imgpro)) {
                Storage::disk('public')->delete($user->imgpro);
            }

            // Store the new image
            $path = $request->file('image')->store('profile_images', 'public');
            $user->imgpro = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }
}