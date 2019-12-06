<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class ConfirmUpdate extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {

        foreach ($models as $model) {

            $data = DB::table('confirmations')->where('id',$model->id)->first();

            $modeldata = json_decode($data->data); //json string to associative array

            if ($modeldata->name == 'Update') {
                $newmodel = $modeldata->model_type::find($modeldata->model_id);
            }else{
                $newmodel = new $modeldata->model_type;
            }

            

            $chagesdataobj = $modeldata->changes;

            $changedataarr = json_decode(json_encode($chagesdataobj), true); //object to associative array


            //echo "<pre>"; print_r($chagesdata); die;

            foreach ($changedataarr as $key => $value) {
                $newmodel->$key = $changedataarr[$key];
            }

            $newmodel->save();
            //$data = DB::table('confirmations')->where('id',$model->id)->first();

            // $modeldata = $model->data;

            // $newmodel = $modeldata->model_type::find($modeldata->model_id);

            // $chagesdata = json_decode($newmodel->changes,true);

            // foreach ($chagesdata as $key => $value) {
            //     $newmodel->$key = $chagesdata[$key];
            // }

            // $newmodel->save();

            //echo "<pre>"; print_r($model); die;


            // $data = DB::table('action_events')->where('model_type',$model)->where('model_id',$model->id)->get()->last();

            // $changes = json_decode($data->changes);

            // $newmodel = $data->model_type::find($data->model_id);

            // $newmodel->title = $changes->title;

            // $newmodel->save();


            $model->is_checked = 1;

            $model->save();

            return Action::message('Confirmed');
        }

    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
