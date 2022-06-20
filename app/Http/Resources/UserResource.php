<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Entity\Initiator;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->load('notifications');
        // get initiator fotos

        $roles = array_map(
            function ($role) {
                return $role['name'];
            },
            $this->roles->toArray()
        );
        $avatar = $this->avatar;
        if(in_array('initiator', $roles)){
            $initiator = Initiator::where('email', $this->email)->first();
            if($initiator && $initiator->logo){
                $avatar = $initiator->logo;
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $roles,
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->getAllPermissions()->toArray()
            ),
            'notifications' => $this->notifications,
            'avatar' => $avatar, // 'avatar' => $this->avatar,
        ];
    }
}
