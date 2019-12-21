<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'profile' => [
                'fname' => $this->fname,
                'mname' => $this->mname,
                'lname' => $this->lname,
            ],
            'class' => $this->class,
            'college' => $this->college,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
