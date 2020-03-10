<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->title,
            'content' => $this->body
        ];

    }

    public function with($request)
    {
        return [
            'version'=>'1.0.1',
            'author'=>url('http://abc.com')
        ];
    }
}
