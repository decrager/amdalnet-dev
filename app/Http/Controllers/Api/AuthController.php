<?php
/**
 * File AuthController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers\Api;

use App\Entity\Initiator;
use App\Entity\OssLicense;
use App\Http\Resources\UserResource;
use App\Laravue\Acl;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials["active"]=1;
        // $credentials=>active = 1;

        // return $credentials;
        if (!Auth::attempt($credentials)) {
            return response()->json(new JsonResponse([], 'login_error'), Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();

        return response()->json(new JsonResponse(new UserResource($user)), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json((new JsonResponse())->success([]), Response::HTTP_OK);
    }

    public function validateToken(Request $request)
    {
        $validated = $request->only('token');
        $resp = $this->getValidateToken($validated['token']);
        return $resp->json();
    }

    public function getUserInfo(Request $request)
    {
        $validated = $request->only('token');
        $resp = $this->getOssUserInfo($validated['token']);
        return $resp->json();
    }

    public function isEmailRegistered(Request $request)
    {
        $validated = $request->only('email');
        $user = User::where('email', $validated['email'])->first();
        if ($user) {
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => true,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'code' => 404,
                'data' => false,
            ], 200);
        }
    }

    public function loginOss(Request $request)
    {
        $validated = $request->only('email', 'token');
        $resp = $this->getValidateToken($validated['token']);
        $resp_json = $resp->json();
        $user = User::where('email', $validated['email'])->first();
        if ($user && $resp_json['session'] == 'VALID') {
            Auth::login($user);
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => true,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'code' => 404,
                'message' => 'User not found',
            ], 404);
        }
    }
    public function registerOss(Request $request)
    {
        $validated = $request->only('email', 'username', 'name', 
            'password', 'pic', 'user_type', 'phone', 'address', 'nib');
        $initiatorRole = Role::findByName(Acl::ROLE_INITIATOR);
        DB::beginTransaction();
        $user = User::create([
            'name' => ucfirst($validated['name']),
            'email' => $validated['email'],
            'oss_username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'active' => 1,
        ]);
        $user->syncRoles($initiatorRole);
        $initiator = Initiator::create([
            'name'        =>  $validated['name'],
            'pic'         =>  $validated['pic'],
            'email'       =>  $validated['email'],
            'phone'       =>  $validated['phone'],
            'address'     =>  $validated['address'],
            'user_type'   =>  $validated['user_type'],
            'nib'   =>  $validated['nib'],
        ]);
        if ($user && $initiator) {
            DB::commit();
            $user2 = $this->setUserAsActive($user->id);
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $user,
                'active' => $user->active, // via insert
                'active_2' => $user2->active, // via update
            ], 200);
        } else {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'code' => 500,
                'message' => 'Failed to create User',
            ], 500);
        }
    }

    public function receiveToken(Request $request)
    {
        /*
         * Receive token and licenses from OSS
         * Store access_token & refresh_token to `users` table
         * Store kd_izin & id_izin to `oss_licenses` table
         */
        $validator = Validator::make(
            $request->all(),
            [
                'access_token' => 'required',
                'refresh_token' => 'required',
                'kd_izin' => 'required',
                'id_izin' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Request parameter tidak valid.',
            ], 400);
        }
        $validated = $request->only('access_token', 'refresh_token', 'kd_izin', 'id_izin');
        $resp = $this->getOssUserInfo($validated['access_token'])->json();

        if ($resp['status'] === 200) {
            $user_info = $resp['data'];
            $user = User::where('email', $user_info['email'])->first();
            $initiator = Initiator::where('email', $user_info['email'])->first();
            DB::beginTransaction();
            $user->access_token = $validated['access_token'];
            $user->refresh_token = $validated['refresh_token'];
            $user->save();
            $created = OssLicense::create([
                'id_initiator' => $initiator->id,
                'email' => $initiator->email,
                'id_izin' => $validated['id_izin'],
                'kd_izin' => $validated['kd_izin'],
            ]);
            if ($created) {
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Token berhasil diterima.',
                ], 200);
            } else {
                DB::rollBack();
            }
        }
        return response()->json([
            'status' => 500,
            'message' => 'Gagal menyimpan token',
        ], 500);
    }

    private function setUserAsActive($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->active = 1;
            $user->save();
        }
        return $user;
    }

    private function getValidateToken($accessToken)
    {
        return Http::withHeaders([
            'user_key' => env('OSS_USER_KEY'),
        ])->withToken($accessToken)
        ->post(env('OSS_ENDPOINT_SSO') . '/users/validate-token');
    }

    private function getOssUserInfo($accessToken)
    {
        return Http::withHeaders([
            'user_key' => env('OSS_USER_KEY'),
        ])->withToken($accessToken)
        ->get(env('OSS_ENDPOINT_SSO') . '/users/userinfo-token');
    }

}
