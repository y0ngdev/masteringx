<?php

namespace App\Filament\Admin\Resources\UserTransactionsResource\Pages;

use App\Filament\Admin\Resources\UserTransactionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserTransactions extends ListRecords
{
    protected static string $resource = UserTransactionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
