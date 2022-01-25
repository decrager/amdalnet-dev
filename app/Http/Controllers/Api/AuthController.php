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
            'password' => Hash::make($validated['password'])
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
            return response()->json([
                'status' => 200,
                'code' => 200,
                'data' => $user,
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
