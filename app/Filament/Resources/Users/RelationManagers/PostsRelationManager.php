<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    protected static ?string $relatedResource = PostResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('content')
                    ->limit(50), // cant I add show more button here?
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('showContent')
                    ->label('Read more')
                    ->modalHeading('Full Content')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn($record) => view('filament.posts.show-content', [
                        'content' => $record->content,
                    ])),
            ]);
    }
}