<?php

namespace App\Http\Controllers;

use App\Entity\Announcement;
use App\Entity\Feedback;
use App\Entity\Project;
use App\Http\Resources\FeedbackResource;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $search = $request->search;
        return FeedbackResource::collection(Feedback::where([['announcement_id', $request->announcement_id],['deleted', $request->deleted]])
                ->where(function($q) {
                    if(Auth::check()) {
                        if(Auth::user()->roles->first()->name == 'initiator') {
                            $q->where('is_relevant', true);
                        }
                    }
                })
                ->where(function($q) use($search) {
                    if($search) {
                        if(str_contains(strtolower($search),'relevan')) {
                            if(str_contains(strtolower($search),'tidak')) {
                                $q->where('is_relevant', false);
                            } else {
                                $q->where('is_relevant', true);
                            }
                        } else if(str_contains(strtolower($search), 'peserta')) {
                            $q->where('responder_type_id', 3);
                        } else if(str_contains(strtolower($search), 'masyarakat')) {
                            if(str_contains(strtolower($search), 'terdampak')) {
                                $q->where('responder_type_id', 1);
                            } else if(str_contains(strtolower($search), 'pemerhati')) {
                                $q->where('responder_type_id', 2);
                            } else {
                                $q->whereIn('responder_type_id', [1,2]);
                            }
                        } else {
                            $q->where(DB::raw('lower(name)'), 'LIKE', '%' . strtolower($search) . '%');
                        }
                    }
                })
                ->orderBy('id', 'ASC')->get());
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
        if($request->relevance) {
            $relevance = $request->updatedspt;
            if(count($relevance) > 0) {
                for($i = 0; $i < count($relevance); $i++) {
                    $feedback = Feedback::findOrFail($relevance[$i]['id']);
                    $feedback->is_relevant = $relevance[$i]['is_relevant'];
                    $feedback->save();
                }
            }

            return response()->json(['message' => 'success']);
        }

        $validator = $request->validate([
            'name' => 'required',
            'id_card_number' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'concern' => 'required',
            'expectation' => 'required',
            'rating' => 'required',
            'responder_type_id' => 'required',
            'environment_condition' => 'nullable',
            'local_impact' => 'nullable',
            'community_type' => 'nullable',
            'community_gender' => 'required',
        ]);

        if ($request->file('photo_filepath')) {
            // $name = uniqid() . '.' . $request->file('photo_filepath')->extension();
            // $nameWithPath = 'images/spt/' . uniqid() . '.' . $request->file('photo_filepath')->extension();
            // $request->file('photo_filepath')->move(public_path('images/spt/'), $name);
            // $validator['photo_filepath'] = $nameWithPath;

            $file = $request->file('photo_filepath');
            $name = 'spt/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $validator['photo_filepath'] = $name;
        };

        DB::beginTransaction();
        $validator['announcement_id'] = $request->announcement_id;
        if($validator['responder_type_id'] == '1' || $validator['responder_type_id'] == '3') {
            $validator['is_relevant'] = true;
        } else {
            $validator['is_relevant'] = false;
        }

        $feedback = Feedback::create($validator);
        if ($feedback){
            DB::commit();
        } else {
            DB::rollBack();
        }

        return new FeedbackResource($feedback);
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
                'concern' => 'required',
                'photo_filepath' => 'required',
                'responder_type_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();
            // check base64 string
            $data = $params['photo_filepath'];
            $photo_filepath = '';
            try {
                list($type, $data) = explode(';', $data);
                list(,$extension) = explode('/',$type);
                list(,$data) = explode(',', $data);
                $decoded_data = base64_decode($data);
                // put to laravel storage
                $img_filepath = 'images/spt/' . uniqid() . '.' . $extension;
                Storage::put($img_filepath, $decoded_data);
                // don't forget to make symlink:
                // php artisan storage:link
                $photo_filepath = 'storage/' . $img_filepath;
            } catch (Exception $e) {
                $photo_filepath = $data;
            }

            // save entity
            $feedback->announcement_id = $params['announcement_id'];
            $feedback->name = $params['name'];
            $feedback->id_card_number = $params['id_card_number'];
            $feedback->phone = $params['phone'];
            $feedback->email = $params['email'];
            $feedback->concern = $params['concern'];
            $feedback->expectation = $params['expectation'];
            $feedback->rating = $params['rating'];
            $feedback->photo_filepath = $photo_filepath;
            $feedback->responder_type_id = $params['responder_type_id'];
            $feedback->is_relevant = $params['is_relevant'];
            $feedback->set_relevant_by = $params['set_relevant_by'];
            $feedback->set_relevant_at = $params['set_relevant_at'];
            $feedback->deleted = $params['deleted'];
            //change timezone from UTC to GMT+7
            if ($params['deleted_at'] != null) {
                $datetime = DateTime::createFromFormat('Y-m-d\TH:i:s+', $params['deleted_at']);
                $datetime->add(new DateInterval('PT7H'));
                $deleted_at = $datetime->format('Y-m-d\TH:i:s');
                $feedback->deleted_at = $deleted_at;
            }

            $saved = $feedback->save();

            if ($saved){
                DB::commit();
            } else {
                DB::rollBack();
            }
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
