<?php

namespace App\Filament\Doctor\Resources\ArticleResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Doctor\Resources\ArticleResource;
use Illuminate\Support\Facades\Auth;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['author_id'] = Auth::user()->id;
        return static::getModel()::create($data);
    }
}
