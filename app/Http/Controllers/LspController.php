<?php

namespace App\Http\Controllers;

use App\Entity\Lsp;
use Illuminate\Http\Request;
use App\Laravue\Models\User;
use App\Entity\Formulator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Laravue\Models\Role;
use Illuminate\Support\Str;
use App\Http\Resources\LspResource;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Acl;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ChangeUserEmailNotification;

class LspController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->options){
            return LspResource::collection(Lsp::all());
        }

        if($request->byUserId) {
            $lsp = Lsp::where('email', $request->email)->first();
            return $lsp;
        }

        if($request->formulators) {
            $formulators = Formulator::where('id_lsp', $request->idLsp)->orWhere('id_lsp', null)->orderBy('name')->get();
            return $formulators;
        }

        if($request->member) {
            $lsp = Lsp::select('id', 'lsp_name')->whereKey($request->idLsp)->first();
            $members = Formulator::where('id_lsp', $request->idLsp)
            ->where(function ($query) use ($request) {
                return $request->membershipStatus ? $query->where('membership_status', $request->membershipStatus) : '';
            })
            // ->where('membership_status', $request->membershipStatus ? $request->membershipStatus : '')
            ->orderBy($request->orderBy ? $request->orderBy : 'id', $request->order ? $request->order : 'asc' )->get();
            $formulators = [];

            $num = 1;
            foreach($members as $m) {
                $formulators[] = [
                    'num' => $num,
                    'id' => $m->id,
                    'name' => $m->name,
                    'reg_no' => $m->reg_no,
                    'cert_no' => $m->cert_no,
                    'membership_status' => $m->membership_status,
                    'cert_file' => $m->cert_file,
                    'date_start' => $m->date_start,
                    'date_end' => $m->date_end,
                    'cv_file' => $m->file,
                    'type' => 'update'
                ];
                $num++;
            }

            return response()->json([
                'lsp' => $lsp,
                'members' => $formulators
            ]);

        }

        return LspResource::collection(Lsp::where(function ($query) use ($request) {
            // if ($request->active == '1' || $request->status === '1') {
            //     $query->where([['date_lsp_start', '<=', date('Y-m-d H:i:s')], ['date_lsp_end', '>=', date('Y-m-d H:i:s')]])
            //         ->orWhere([['date_lsp_start', null], ['date_lsp_end', '>=', date('Y-m-d H:i:s')]]);
            // } else if($request->status === '0') {
            //     $query->where('date_lsp_end', '<', date('Y-m-d H:i:s'));
            // }
        })->where(function($query) use($request) {
            if($request->search) {
                $search = trim(str_replace('provinsi', '', strtolower($request->search)));
                $query->where(function($q) use($search) {
                    // $q->whereRaw("LOWER(license_no) LIKE '%" . strtolower($search) . "%'");
                })->orWhere(function($q) use($search) {
                    $q->whereRaw("LOWER(lsp_name) LIKE '%" . strtolower($search) . "%'");
                })->orWhere(function($q) use($search) {
                    $q->whereRaw("LOWER(address) LIKE '%" . strtolower($search) . "%'");
                })->orWhere(function($q) use($search) {
                    // $q->whereHas('dataProvince', function($que) use($search) {
                        // $que->whereRaw("LOWER(name) LIKE '%" . strtolower($search) . "%'");
                    // })->orWhereHas('dataDistrict', function($que) use($search) {
                        // $que->whereRaw("LOWER(name) LIKE '%" . strtolower($search) . "%'");
                    // });
                });
            }
        })
        // ->with(['dataProvince' => function ($query) {
        //     return $query->select(['id', 'name']);
        // }, 'dataDistrict' => function ($query) {
        //     return $query->select(['id', 'name']);
        // }])
        ->orderBy('id', 'desc')->paginate($request->limit));
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
        if($request->member) {
            $members = $request->members;
            $deleted_members = $request->deletedMembers;
            $id_lsp = $request->idLsp;

            if(count($members) > 0) {
                Formulator::whereIn('id', $members)->update(['id_lsp' => $id_lsp]);
            }

            if(count($deleted_members) > 0) {
                Formulator::whereIn('id', $deleted_members)->update(['id_lsp' => null]);
            }

            return response()->json(['message' => 'success']);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'lsp_name'          => 'required',
                // 'license_no'        => 'required',
                // 'sk_no'             => 'required',
                // 'province'          => 'required',
                // 'city'              => 'required',
                'address'           => 'required',
                'phone_no'          => 'required',
                // 'file'              => 'required',
                'email'             => 'required',
                // 'date_lsp_start'    => 'required',
                // 'date_lsp_end'      => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();

            DB::beginTransaction();

            //create file
            // $file = $request->file('file');
            // $name = 'lsp/' . uniqid() . '.' . $file->extension();
            // $file->storePubliclyAs('public', $name);

            $email = $request->get('email') ? strtolower($request->get('email')) : $request->get('email');
            $found = User::where('email', $email)->first();
            if (!$found) {
                $lspRole = Role::findByName(Acl::ROLE_LSP);
                $password = Str::random(8);
                $user = User::create([
                    'name' => ucfirst($params['lsp_name']),
                    'email' => $params['email'] ? strtolower($params['email']) : $params['email'],
                    'password' => Hash::make($password),
                    'original_password' => $password
                ]);
                $user->syncRoles($lspRole);
            } else {
                return response()->json(['errors' => 'Email yang anda masukkan sudah terpakai']);
            }

            $check_lsp = Lsp::where('email', $email)->first();
            if($check_lsp) {
                return response()->json(['errors' => 'Email yang anda masukkan sudah terpakai']);
            }

            //create lsp
            $lsp = Lsp::create([
                'lsp_name' => $params['lsp_name'],
                // 'license_no' => $params['license_no'],
                // 'sk_no' => $params['sk_no'],
                // 'province' => $params['province'],
                // 'city' => $params['city'],
                'address' => $params['address'],
                'phone_no' => $params['phone_no'],
                // 'lsp_file' => $name,
                'email' => $params['email'] ? strtolower($params['email']) : $params['email'],
                // 'date_lsp_start' => $params['date_lsp_start'],
                // 'date_lsp_end' => $params['date_lsp_end'],
            ]);

            if (!$lsp) {
                DB::rollback();
            } else {
                DB::commit();
            }

            return new LspResource($lsp);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lsp  $lsp
     * @return \Illuminate\Http\Response
     */
    public function show(Lsp $lsp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lsp  $lsp
     * @return \Illuminate\Http\Response
     */
    public function edit(Lsp $lsp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lsp  $lsp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lsp $lsp)
    {
        if ($lsp === null) {
            return response()->json(['error' => 'lsp not found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'lsp_name'          => 'required',
                // 'license_no'        => 'required',
                // 'sk_no'             => 'required',
                // 'province'          => 'required',
                // 'city'              => 'required',
                'address'           => 'required',
                'phone_no'          => 'required',
                'email'             => 'required',
                // 'date_lsp_start'    => 'required',
                // 'date_lsp_end'      => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $email_changed_notif = null;
            $old_email = null;
            $password = Str::random(8);

            if($request->email) {
                if(strtolower($request->email) != $lsp->email) {
                    $found = User::where('email', strtolower($request->email))->first();
                    if($found) {
                        return response()->json(['errors' => 'Email yang anda masukkan sudah terpakai']);
                    } else {
                        $create_user = 0;
                        if($lsp->email) {
                            $lsp_user = User::where('email', $lsp->email)->first();
                            if($lsp_user) {
                                $old_email = $lsp->email;
                                $lsp_user->name = $request->name;
                                $lsp_user->email = strtolower($request->email);
                                $lsp_user->password = Hash::make($password);
                                $lsp_user->save();
                                $email_changed_notif = $lsp_user;
                            } else {
                                $create_user = 1;
                            }
                        } else {
                           $create_user = 1;
                        }

                        if($create_user == 1) {
                            $lspRole = Role::findByName(Acl::ROLE_LSP);
                            $random_password = Str::random(8);
                            $user = User::create([
                                'name' => ucfirst($params['name']),
                                'email' => strtolower($params['email']),
                                'password' => isset($params['password']) ? Hash::make($params['password']) : Hash::make($random_password),
                                'original_password' => isset($params['password']) ? $params['password'] : $random_password
                            ]);
                            $user->syncRoles($lspRole);
                        }
                    }
                } else {
                    $user = User::where('email', strtolower($request->email))->first();
                    if(!$user) {
                        $lspRole = Role::findByName(Acl::ROLE_LSP);
                        $random_password = Str::random(8);
                        $user = User::create([
                            'name' => ucfirst($params['name']),
                            'email' => strtolower($params['email']),
                            'password' => isset($params['password']) ? Hash::make($params['password']) : Hash::make($random_password),
                            'original_password' => isset($params['password']) ? $params['password'] : $random_password
                        ]);
                        $user->syncRoles($lspRole);
                    }
                }
            }

            // if ($request->file('file') !== null) {
            //     //create file
            //     $file = $request->file('file');
            //     $name = 'lsp/' . uniqid() . '.' . $file->extension();
            //     $file->storePubliclyAs('public', $name);
            //     $lsp->lsp_file = $name;
            // }

            $lsp->lsp_name = $params['lsp_name'];
            // $lsp->license_no = $params['license_no'];
            // $lsp->sk_no = $params['sk_no'];
            // $lsp->province = $params['province'];
            // $lsp->city = $params['city'];
            $lsp->address = $params['address'];
            $lsp->phone_no = $params['phone_no'];
            $lsp->email = $params['email'] ? strtolower($params['email']) : $params['email'];
            // $lsp->date_lsp_start = $params['date_lsp_start'];
            // $lsp->date_lsp_end = $params['date_lsp_end'];
            $lsp->save();

            if($email_changed_notif) {
                Notification::send([$email_changed_notif], new ChangeUserEmailNotification(null,null,null,$password));
                Notification::route('mail', $old_email)->notify(new ChangeUserEmailNotification($email_changed_notif->name, $email_changed_notif->email, $email_changed_notif->roles->first()->name));
            }
        }

        return new LspResource($lsp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lsp  $lsp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lsp $lsp)
    {
        try {
            $user = User::where('email', $lsp->email)->first();
            if($user) {
                $user->delete();
            }
            $lsp->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
        return response()->json(null, 204);
    }

    public function showByEmail(Request $request)
    {
        if ($request->email) {
            $lsp = Lsp::where('lsp.email', $request->email)
              ->select('lsp.*')
              ->addSelect('users.avatar as avatar')
              ->leftJoin('users', 'users.email', '=', 'lsp.email')
              ->first();

            if ($lsp) {
                return $lsp;
            } else {
                return response()->json(null, 200);
            }
        }
    }
}
