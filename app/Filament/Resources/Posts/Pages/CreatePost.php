<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($data); // dump submitted form data
    //     return $data;
    // }
}
