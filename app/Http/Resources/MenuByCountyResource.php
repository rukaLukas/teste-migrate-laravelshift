<?php

namespace App\Http\Resources;

use App\Http\Resources\Configuration\MenuResource;
use App\Models\County;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuByCountyResource extends JsonResource
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
            'id' => $this->uuid,
            'menu' => new MenuResource(Menu::find($this->menu_id)),
        ];
    }
}
