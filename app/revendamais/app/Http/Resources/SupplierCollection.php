<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data'          => $this->collection,
            'current_page'  => $this->currentPage(),
            'per_page'      => $this->perPage(),
            'total'         => $this->total(),
            'total_pages'   => $this->lastPage(),
            'from'          => $this->firstItem(),
            'to'            => $this->lastItem(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'path'          => $this->path(),
            'links'         => $this->linkCollection()->toArray(),
        ];
    }
}
