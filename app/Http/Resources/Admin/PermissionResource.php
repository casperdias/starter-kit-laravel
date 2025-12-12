<?php

namespace App\Http\Resources\Admin;

use App\Models\Auth\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Permission
 */
class PermissionResource extends JsonResource
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
                $request->route()->getName() === 'admin.roles.show',
                function () use ($request) {
                    $role = $request->route('role');

                    return $role->hasPermission($this->name);
                }
            ),
        ];
    }
}
