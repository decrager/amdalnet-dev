<?php

namespace App\Http\Controllers;

use App\Entity\Feedback;
use App\Http\Resources\FeedbackResource;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // filter by project/announcement ID;
        return FeedbackResource::collection(Feedback::where(function ($query) use ($request) {
                $query->where('announcement_id', $request->announcement_id)
                ->where('deleted', $request->deleted);
        })->get());
    }

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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'id_card_number' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'description' => 'required',
                'photo_filepath' => 'required',
                'responder_type_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            // check base64 string
            $data = $params['photo_filepath'];
            list($type, $data) = explode(';', $data);
            list(,$extension) = explode('/',$type);
            list(,$data) = explode(',', $data);
            $decoded_data = base64_decode($data);
            // put to laravel storage
            $img_filepath = 'public/images/spt/' . uniqid() . '.' . $extension;
            Storage::put($img_filepath, $decoded_data);
            // don't forget to make symlink:
            // php artisan storage:link

            // save entity
            $feedback = Feedback::create([
                'announcement_id' => $params['announcement_id'],
                'name' => $params['name'],
                'id_card_number' => $params['id_card_number'],
                'phone' => $params['phone'],
                'email' => $params['email'],
                'description' => $params['description'],
                'photo_filepath' => str_replace('public', 'storage', $img_filepath),
                'responder_type_id' => $params['responder_type_id'],
                'is_relevant' => false,
                'set_relevant_by' => 0,
                'set_relevant_at' => null,
            ]);
            return new FeedbackResource($feedback);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entity\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        return $feedback;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entity\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entity\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'id_card_number' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'description' => 'required',
                'photo_filepath' => 'required',
                'responder_type_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {            
            $params = $request->all();
            // check base64 string
            $data = $params['photo_filepath'];
            $photo_filepath = '';
            try {
                list($type, $data) = explode(';', $data);
                list(,$extension) = explode('/',$type);
                list(,$data) = explode(',', $data);
                $decoded_data = base64_decode($data);
                // put to laravel storage
                $img_filepath = 'public/images/spt/' . uniqid() . '.' . $extension;
                Storage::put($img_filepath, $decoded_data);
                // don't forget to make symlink:
                // php artisan storage:link
                $photo_filepath = str_replace('public', 'storage', $img_filepath);
            } catch (Exception $e) {
                $photo_filepath = $data;
            }

            // save entity
            $feedback->announcement_id = $params['announcement_id'];
            $feedback->name = $params['name'];
            $feedback->id_card_number = $params['id_card_number'];
            $feedback->phone = $params['phone'];
            $feedback->email = $params['email'];
            $feedback->description = $params['description'];
            $feedback->photo_filepath = $photo_filepath;
            $feedback->responder_type_id = $params['responder_type_id'];
            $feedback->is_relevant = $params['is_relevant'];
            $feedback->set_relevant_by = $params['set_relevant_by'];
            $feedback->set_relevant_at = $params['set_relevant_at'];
            $feedback->deleted = $params['deleted'];
            //change timezone from UTC to GMT+7
            $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s+', $params['deleted_at']);
            $datetime->add(new DateInterval('PT7H'));
            $deleted_at = $datetime->format('Y-m-d\TH:i:s');
            $feedback->deleted_at = $deleted_at;

            $feedback->save();
        }
        return new FeedbackResource($feedback);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entity\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}
