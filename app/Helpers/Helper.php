<?php 

namespace App\Helpers;

use Auth;
use App\Confirmation;

/**
 * 
 */
class Helper
{
	
	public static function hello(){
		echo "hello"; die;
	}

	public static function handleuserdata($model, $request,$id){
	    
	    $arrayoriginal = json_decode(json_encode($model), true);

	    $changes = $request->all();

	    unset($changes['_token']);
	    unset($changes['id']);

	    if (!empty($arrayoriginal)) {
	        $name = "Update";
	    }else{
	        $name = "Create";
	    }
	    
	    $data = array(
	        'user_id' => Auth::user()->id,
	        'name' => $name,
	        'actionable_type' => $model->getMorphClass(),
	        'actionable_id' => $model->getKey(),
	        'target_type' => $model->getMorphClass(),
	        'target_id' => $model->getKey(),
	        'model_type' => $model->getMorphClass(),
	        'model_id' => $id,
	        'fields' => '',
	        'original' => $arrayoriginal,
	        'changes' => $changes,//"changes":{"title":"Promotional 1 updated","description":"This is a pomotional updated","price":"11111"}
	        'status' => 'finished',
	        'exception' => '',
	     );

	    $conmodel = new Confirmation;

	    $conmodel->data = $data;

	    $conmodel->type = $model->getMorphClass();

	    if (!empty($arrayoriginal)) {
	        $conmodel->resource_id = $model->getKey();
	    }else{
	        $conmodel->resource_id = null;
	    }

	    $conmodel->save();
	    
	}
}
