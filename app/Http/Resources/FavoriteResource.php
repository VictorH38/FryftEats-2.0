<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public static $wrap = 'favorite';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function with(Request $request) {
        $sideloadedData = [];

        if ($request->query('include')) {
            $relationshipsToSideLoad = explode(',', $request->query('include'));

            foreach ($relationshipsToSideLoad as $relationship) {
                if (method_exists($this->resource, $relationship)) {
                    if ($this->resource->$relationship()->exists()) {
                        $sideloadedData[$relationship] = $this->resource->$relationship;
                    }
                }
            }
        }

        return $sideloadedData;
    }
}
