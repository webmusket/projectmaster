<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Collection;
use App\Confirmation;
use App\Helpers\Helper;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('home')->with(compact('user'));
    }

    public function updateuserData(Request $request,$id = null){
        //$model = User::findOrFail(1);

        $model = new User;

        // handleuserdata($model, $request,$id);
        Helper::handleuserdata($model, $request,$id);

        // //$original = User::where('id',1)->first();

        // $arrayoriginal = json_decode(json_encode($model), true);

        // $changes = $request->all();

        // unset($changes['_token']);
        // unset($changes['id']);

        // if (!empty($arrayoriginal)) {
        //     $name = "Update";
        // }else{
        //     $name = "Create";
        // }
        
        // $data = array(
        //     'user_id' => Auth::user()->id,
        //     'name' => $name,
        //     'actionable_type' => $model->getMorphClass(),
        //     'actionable_id' => $model->getKey(),
        //     'target_type' => $model->getMorphClass(),
        //     'target_id' => $model->getKey(),
        //     'model_type' => $model->getMorphClass(),
        //     'model_id' => $id,
        //     'fields' => '',
        //     'original' => $arrayoriginal,
        //     'changes' => $changes,//"changes":{"title":"Promotional 1 updated","description":"This is a pomotional updated","price":"11111"}
        //     'status' => 'finished',
        //     'exception' => '',
        //  );



        // $conmodel = new Confirmation;

        // $conmodel->data = $data;

        // $conmodel->type = $model->getMorphClass();

        // if (!empty($arrayoriginal)) {
        //     $conmodel->resource_id = $model->getKey();
        // }else{
        //     $conmodel->resource_id = null;
        // }

        

        // $conmodel->save();
        
        // echo "<pre>"; print_r($data); die;
        
    }
}
