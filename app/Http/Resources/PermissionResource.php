<?php

namespace App\Http\Resources;

use App\Http\Resources\Configuration\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'menu' => new MenuResource(Menu::find($this->menu_id))
        ];
    }
}
