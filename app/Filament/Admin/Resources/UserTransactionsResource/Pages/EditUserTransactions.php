<?php

namespace App\Filament\Admin\Resources\UserTransactionsResource\Pages;

use App\Filament\Admin\Resources\UserTransactionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserTransactions extends EditRecord
{
    protected static string $resource = UserTransactionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
