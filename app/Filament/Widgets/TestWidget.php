<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Invoices', Invoice::count())
            ->description('New Invoice that has been Inculded')
            ->descriptionIcon('heroicon-m-clipboard-document-list', IconPosition::Before)
            ->chart([1000,4000 ,8000 , 10000, 25000 , 40000])
            ->color('success'),

            Stat::make('Customer', Customer::count())
            ->description('New Customer that has been Joined')
            ->descriptionIcon('heroicon-m-users', IconPosition::Before)
            ->chart([1000,4000 ,8000 , 10000, 25000 , 40000])
            ->color('info'),

            Stat::make('Items', Item::count())
            ->description('New Items that has been Joined')
            ->descriptionIcon('heroicon-m-users', IconPosition::Before)
            ->chart([1000,4000 ,8000 , 10000, 25000 , 40000])
            ->color('danger'),
        ];
    }
}
