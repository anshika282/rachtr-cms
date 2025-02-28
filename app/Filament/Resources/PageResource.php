<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CommonComponents;
use Filament\Resources\Resource;
use RalphJSmit\Filament\SEO\SEO;
use App\Models\Pages as PageModel;
use Filament\Forms\Components\Select;
use App\Filament\Components\CustomSEO;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PageResource\RelationManagers;

class PageResource extends Resource
{
    protected static ?string $model = PageModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Title name')->required(),
                TextInput::make('slug')
                        ->label('Slug')
                        ->placeholder('Enter the slug')
                        ->visible(fn (callable $get) => !$get('is_hompage')),
                Select::make('parent_id')
                        ->relationship('parent', 'title')
                        ->nullable()
                        ->label('Parent Page'),
                Select::make('header_id')
                        ->label('Header Template')
                        ->options(function () {
                            try {
                                return CommonComponents::where('type', 'header')
                                    ->pluck('type', 'id')
                                    ->toArray() ?: [];
                            } catch (\Exception $e) {
                                return [];
                            }
                        })
                        ->placeholder('Select Template'),
                    
                Select::make('footer_id')
                        ->label('Footer Template')
                        ->options(function () {
                            try {
                                return CommonComponents::where('type', 'footer')
                                    ->pluck('type', 'id')
                                    ->toArray() ?: [];
                            } catch (\Exception $e) {
                                return [];
                            }
                        })
                        ->placeholder('Select Template'),
                Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'archived' => 'Archived',
                            'published' => 'Published',
                        ]),
                        SEO::make()
                        ->schema([
                            Forms\Components\TextInput::make('title')->label('SEO Title'),
                            Forms\Components\TextInput::make('description')->label('SEO Description'),
                            Forms\Components\TextInput::make('robots')->label('Robots'),
                            Forms\Components\TextInput::make('canonical_url')
                                ->label('Canonical URL')
                                ->url()
                                ->nullable(),
                        ]),
                TagsInput::make('seo.meta.focus_keywords')
                        ->label('Focus Keywords')
                        ->placeholder('Enter keywords separated by commas...')
                        ->helperText('Add focus keywords for SEO analysis.')
                        ->required()
                        ->afterStateHydrated(function ($component, $record) {
                            if ($record && $record->seo) {
                                $meta = json_decode($record->seo->meta, true) ?? [];
                                // Set the focus_keywords state with the existing tags
                                $component->state($meta['focus_keywords'] ?? []);
            
                            }
                        })
                        ->afterStateUpdated(function ($state, $record) {
                            if ($record && $record->seo) {
                                $seo = $record->seo;
                                $meta = json_decode($seo->meta, true) ?? []; // Decode the JSON
                                $meta['focus_keywords'] = $state;  // Update focus_keywords
                                $seo->update(['meta' => json_encode($meta)]);  // Re-encode the meta as JSON
                            }
                        }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                ->label('Title')
                ->searchable()
                ->sortable(),

            TextColumn::make('slug')
                ->label('Slug')
                ->searchable()
                ->sortable()
                ->toggleable(),

            TextColumn::make('header_id')
                ->label('Header Template')
                ->sortable()
                ->toggleable()
                ->formatStateUsing(fn ($state) => 
                    CommonComponents::find($state)?->type ?? 'N/A'
                ),

            TextColumn::make('footer_id')
                ->label('Footer Template')
                ->sortable()
                ->toggleable()
                ->formatStateUsing(fn ($state) => 
                    CommonComponents::find($state)?->type ?? 'N/A'
                ),

            TextColumn::make('status')
                ->label('Status')
                ->sortable()
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'draft' => 'gray',
                    'archived' => 'red',
                    'published' => 'green',
                    default => 'gray',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    protected static function getCachedHeaderOptions()
    {
        try {
            $headers = CommonComponents::where('type', 'header')->pluck('type', 'id')->toArray();
            return $headers ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }

    protected static function getCachedFooterOptions()
    {
        try {
            $footers = CommonComponents::where('type', 'footer')->pluck('type', 'id')->toArray();
            return $footers ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
