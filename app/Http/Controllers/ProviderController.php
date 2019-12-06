<?php

namespace App\Http\Controllers;

use App\User;
use App\Service;
use App\Category;
use App\Location;
use App\Provider;
use App\Confirmation;
use App\Review;
use App\ServiceImage;
use App\ServiceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{

    public function addProvider(Request $request)
    {
        $user = User::whereType('business')->with('provider')->find($request->user()->getKey());
        $cat_id = $request->get('cat_id');
        // $category = Category::find($cat_id);
        if ($user) {
            $provider = $user->provider ?: new Provider();
            $provider->fill($request->all([
                'title', //'business_name',
                'full_name',
                'description',
                'phone_number',
            ]));

            // if ($category)
            //     $provider->cat_id = $cat_id;
            $user->provider()->save($provider);
            $pro_id = $provider->id;

            $catIDs = $request->get('cat_id', []);
            $provider->category()->sync($catIDs);
            // $categories = array_fill_keys($catIDs,$provider->id);
            // $provider->provider_occ()->delete($pro_id);
            // foreach ($catIDs as $id) {
            //     echo 'pro_id:'.$pro_id.' cat_id:'.$id;
            //     $provider->provider_cat()->save(
            //         new ProviderCat([
            //             'provider_id' => $pro_id,
            //             'cat_id' => $id,
            //         ])
            //     );
            // }

            $occIDs = $request->get('occ_id', []);
            $provider->occasion()->sync($occIDs);
            // $provider->provider_occ()->delete($pro_id);
            // foreach ($occIDs as $id) {
            //     $provider->provider_occ()->save(
            //         new ProviderOcc([
            //             'provider_id' => $pro_id,
            //             'occ_id' => $id,
            //         ])
            //     );
            // }

            $location = $provider->location ?: new Location();
            $location->fill($request->all([
                'lat',
                'lng',
                'full_address', //address
                'street_name', //street
                'building_no', //number
            ]));
            $provider->location()->save($location);

            // return response()->json($provider);
            // echo 'thumb:' . $request->get('thumbnail');
            $imageName = $provider->saveImage($request->get('thumbnail'), 'provider');
            $headerImage = $provider->saveImage($request->get('header'), 'provider');

            $provider->header = $headerImage;
            $provider->thumbnail = $imageName;
            $provider->save();

            $serviceData = $request->get('services', []);

            for ($i = 0; $i < count($serviceData); $i++) {
                $item = $serviceData[$i];
                $service = $provider->services()->save(
                    new Service([
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        // 'image' => $service>saveImage($service['image'])
                    ])
                );

                $service->image = $service->saveImage($item['image']);
                $service->save();
                // $services->images()->save(
                //     new Service([
                //         'image' => $services->saveImage($service['image'])
                //     ])
                // );

                $option = $service->options()->save(
                    new ServiceOption([
                        'title' => $item[$i]['title'],
                        'price' => $item[$i]['price'],
                    ])
                );

                $option->image = $option->saveImage($item[$i]['image'], 'service_option');
                $option->save();

                // $ttt = $service[$i]['title'];
                // echo 'this is the title of option 0:'.$ttt;
            }

            // foreach ($serviceData as $service) {
            //     $services = $provider->services()->save(
            //         new Service([
            //             'title' => $service['title'],
            //             'description' => $service['description'],
            //             'price' => $service['price'],
            //         ])
            //     );

            //     // $servImage = $services>saveImage($service['image']);
            //     $services->images()->save(
            //         new ServiceImage([
            //             'image' => $services->saveImage($service['image'])
            //         ])
            //     );

            //     $ttt = $service[]['title'];
            //     echo 'this is the title of option 0:'.$ttt;
            // }


            // Mail::send('mail.provider', ['id' => $provider->id], function ($mail) {
            //     $mail->to(env('EMAIL_ADMIN'),
            //         'A new provider has registered!')->subject('A new provider has registered and waiting for review');
            // });
        }

        return response()->json(['message' => 'ok'], 200);
    }

    public function editProvider(Request $request)
    {
        $user = User::whereType('business')->with(['provider'])->find($request->user()->getKey());

        if ($user) {
            $provider = $user->provider ?: new Provider();

            if ($request->get('title')) {
                $provider->fill($request->all([
                    'title', //business_name
                    'description', //overview
                ]));

                $location = $provider->location ?: new Location();
                $location->fill($request->all([
                    'lat',
                    'lng',
                    'full_address', //address
                    'street_name', //street
                    'building_no', //number
                ]));
                $provider->location()->save($location);
            }

            if ($request->get('thumbnail')) { //image
                Confirmation::create([
                    'resource_id' => $provider->id,
                    'type' => Confirmation::PROVIDER_IMAGE,
                    'image' => $provider->saveImage($request->get('thumbnail'), 'provider'),
                ]);
                //                $provider->checkAndDelete($provider->image);
                //                $provider->image = $provider->saveImage($request->get('image'), 'provider');
            }

            if ($request->get('header')) { //background_image
                Confirmation::create([
                    'resource_id' => $provider->id,
                    'type' => Confirmation::PROVIDER_BACKGROUND,
                    'image' => $provider->saveImage($request->get('header'), 'provider'),
                ]);
                //                $provider->checkAndDelete($provider->background_image);
                //                $provider->background_image = $provider->saveImage($request->get('background_image'), 'provider');
            }

            $provider->save();
        }
        return response()->json(['message' => 'ok'], 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('providers')->get();
        return $result;
    }

    public function providerByID($id)
    {
        // $rating = $this->getRatingOfProvider($id);
        // echo 'rating:'.$rating;
        $result = Provider::with('images', 'location', 'services.options', 'reviews')
        ->withCount('reviews')
        ->where('id', '=', $id)->get();
        // foreach($result as $res){
        //     $res->rating;
        // }
        return $result->first();
    }

    // public function getRatingOfProvider($id)
    // {
    //     $result = Review::where('provider_id','=',$id)->avg('rate');
    //     return $result;
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
