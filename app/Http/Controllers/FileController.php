<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Scout;
use App\Models\Person;

class FileController extends Controller{
    public function storePay(Request $request){
        // IMG_6403_(2)_(1)_jpg
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->first();
        $route = hash('tiger192,3', $user->email);
        if($request->file('image')){
            $images = $request->file('image')->store('public/images/'.$route);
            $url = Storage::url($images);
            return asset($url);
            $scout->image_pay = asset($url);
            $scout->save();
            return true;
           
        }
        return false;
    }
    public function store(Request $request){
        // IMG_6403_(2)_(1)_jpg
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->first();
        $route = hash('tiger192,3', $user->email);
        if($request->file('image')){
            $images = $request->file('image')->store('public/images/'.$route);
            $url = Storage::url($images);
            return asset($url);
            $scout->image_permissions = asset($url);
            $scout->save();
            return true;
           
        }
        return false;
    }

    public function storeGroup(Request $request){
        if($request->file('image')){
            $images = $request->file('image')->store('public/images/groups/');
            $url = Storage::url($images);
            return asset($url);
        }
        return false;
    }

    public function storeUnit(Request $request){
        if($request->file('image')){
            $images = $request->file('image')->store('public/images/units');
            $url = Storage::url($images);
            return asset($url);
        }
    }

}