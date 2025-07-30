<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s T'),
            'status' => $this->when(
                $request->route()->getName() === 'admin.users.show',
                function () use ($request) {
                    $user = $request->route('user');

                    return $user->hasRole($this->name);
                }
            ),
            'active' => $this->when(
                $request->route()->getName() === 'admin.users.show',
                function () use ($request) {
                    $user = $request->route('user');

                    return $user->role ? $user->role->name === $this->name : false;
                }
            ),
        ];
    }
}
