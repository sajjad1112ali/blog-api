<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->readOnly()
                    ->reactive(),
                MarkdownEditor::make('content')
                    ->toolbarButtons([
                        ['bold', 'italic', 'strike', 'link'],
                        ['heading'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table'],
                        ['undo', 'redo'],
                    ]),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('published_at')
                    ->label('Published At')
                    ->disabled()
                    ->default(Carbon::now()->format('Y-m-d H:i:s')),
                Hidden::make('user_id')
                    ->default(fn() => auth()->id())
                    ->required(),
                SpatieMediaLibraryFileUpload::make('poster')
                    ->label('Imge')
                    ->collection('poster')
                    ->disk('media')
                    ->image()
                    ->imageEditor()
                    ->required(),
            ]);
    }
}
