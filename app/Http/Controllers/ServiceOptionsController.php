<?php

namespace App\Http\Controllers;

use App\Service;
use App\Confirmation;
use App\ServiceImage;
use App\ServiceOption;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ServiceCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ServiceOptionCollection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceOptionsController extends Controller
{
    protected $rules = [
        'service' => [
            'title' => ['required', 'string', 'min:6', 'max:254'],
            // 'description' => ['required', 'string', 'min:6', 'max:254'],
        ],
    ];

    /**
     * Create a new ServiceController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('jwt.verify');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index($id)
    {
        $service = auth()->user()->provider->services()->find($id);
        // echo $service->id;
        return ServiceOptionCollection::collection($service->options()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return ServiceResource
     * @throws ApiException
     */
    public function store(Request $request,$id)
    {
        // echo 'test';

        $validator = Validator::make($request->all(),[
            'title' => ['required', 'string', 'min:6', 'max:254'],
            // 'description' => ['required', 'string', 'min:6', 'max:254'],
            ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
            // throw new ApiException($validator->errors()->toArray(), 422);
        }

        // $options = array();
        // $optionsData = $request->get('options', []);
        // foreach($optionsData as $option){
        //     // echo 'option:'.$option['title'];
        //     $options = [
        //         'title'=> $option['title'],
        //         'price' => $option['price'],
        //         'image' => $option['image'],
        //         ];
        // }
        $service = auth()->user()->provider->services()->find($id);
        $confirmation = Confirmation::create([
            'resource_id' => $service->id,
            'type' => Confirmation::OPTION_NEW,
            'data' => [
                'title' => $request->get('title'),
                'price' => $request->get('price'),

                // 'options' => $options,
            ],
        ]);

        if ($request->get('image')) {
            // $serviceImage = new ServiceOption();
            //using $service to store the image in service id
            $confirmation->image = $service->saveImage($request->get('image'), 'service_option');
        }
        $confirmation->save();

        return response()->json(['message' => 'ok'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return ServiceResource
     * @throws ApiException
     */
    public function update(Request $request,$service_id ,$id)
    {

        $validator = Validator::make($request->all(),[
            'title' => ['required', 'string', 'min:6', 'max:254'],
            // 'description' => ['required', 'string', 'min:6', 'max:254'],
            ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
            // throw new ApiException($validator->errors()->toArray(), 422);
        };
        // $service = auth()->user()->provider->services()->find($id);
        $service = auth()->user()->provider->services()->find($service_id);
        $serviceOption = $service->options()->find($id);
        if (!$serviceOption) {
            return response()->json([
                'message' => 'User service not found'
            ], 404);
            // throw new ApiException('User service not found', 404);
        }

        $confirmation = Confirmation::create([
            'resource_id' => $serviceOption->id,
            'type' => Confirmation::OPTION_EDIT,
            'data' => [
                'title' => $request->get('title'),
                'price' => $request->get('price'),
                // 'description' => $request->get('description'),
            ],
        ]);

        if ($request->get('image')) {
            // $service = new ServiceImage();
            // $confirmation->image = $service->saveImage($request->get('image'), 'service');
            $confirmation->image = $service->saveImage($request->get('image'), 'service_option');
        }
        $confirmation->save();
        return response()->json(['message' => 'ok'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws ApiException
     */
    public function destroy($service_id,$id)
    {
        $service = auth()->user()->provider->services()->find($service_id);
        $serviceOption = $service->options()->find($id);
        if (!$serviceOption) {
            return response()->json([
                'message' => 'User service not found'
            ], 404);
            // throw new ApiException('User service not found', 404);
        }
        $serviceOption->delete();
        return response()->json(['message' => 'ok'], 200);
    }
}
