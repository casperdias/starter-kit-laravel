<?php

namespace App\Http\Resources\Admin;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Permission
 */
class PermissionResource extends JsonResource
{
    private ?Role $role = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->getName() === 'admin.roles.show') {
            $role = $request->route('role');
            $this->role = $role instanceof Role ? $role : null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s T'),
            'status' => $this->when(
                $this->role !== null,
                fn () => $this->role->hasPermission($this->name)
            ),
        ];
    }
}
