<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserTransactionsResource\Pages;
use App\Filament\Admin\Resources\UserTransactionsResource\RelationManagers;
use App\Models\UserTransactions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTransactionsResource extends Resource
{
    protected static ?string $model = UserTransactions::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListUserTransactions::route('/'),
            'create' => Pages\CreateUserTransactions::route('/create'),
            'edit' => Pages\EditUserTransactions::route('/{record}/edit'),
        ];
    }
}
