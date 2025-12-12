<?php

namespace App\Http\Resources\Admin;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Role
 */
class RoleResource extends JsonResource
{
    private ?User $user = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->getName() === 'admin.users.show') {
            $user = $request->route('user');
            $this->user = $user instanceof User ? $user : null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s T'),
            'status' => $this->when(
                $this->user !== null,
                fn () => $this->user->hasRole($this->name)
            ),
            'active' => $this->when(
                $this->user !== null,
                fn () => $this->user->role ? $this->user->role->name === $this->name : false
            ),
        ];
    }
}
