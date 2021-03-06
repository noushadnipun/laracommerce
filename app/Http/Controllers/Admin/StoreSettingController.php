<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreSettings;
use Artisan;

class StoreSettingController extends Controller
{
     //index
    public function index()
    {
        return view ('admin.common.settings.store-settings');
    }
    //update
    public function update(Request $request){
        Artisan::call('optimize');
        $metaName = $request->meta_name;        
        $getAll = StoreSettings::get();
        foreach($metaName as $item){
            $data = StoreSettings::where('meta_name' , $item)->first();
            if(!empty($data)){
                $data->meta_value = $request->$item;
                $data->save();
            } else {
                return redirect()->back()->with('delete', $request->$item.' field not found');
            }
        }
        return redirect()->back()->with('success', 'Settings saved successfully');
    }
}
