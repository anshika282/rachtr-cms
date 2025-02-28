<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CommonComponents;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CommonComponentResource\Pages;
use App\Filament\Resources\CommonComponentResource\RelationManagers;

class CommonComponentResource extends Resource
{
    protected static ?string $model = CommonComponents::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Template name')->required(),
                Select::make('type')
                        ->options([
                            'header' => 'Header',
                            'footer' => 'Footer'
                        ]),
                
                        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
            
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(['header' => 'Header', 'footer' => 'Footer']),
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
            'index' => Pages\ListCommonComponents::route('/'),
            'create' => Pages\CreateCommonComponent::route('/create'),
            'edit' => Pages\EditCommonComponent::route('/{record}/edit'),
        ];
    }
}
