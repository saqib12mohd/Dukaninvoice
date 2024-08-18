<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Itementry;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mockery\Matcher\Closure as MatcherClosure;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;



    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Invoice Info')
                ->collapsible()
                ->schema([


                    Select::make('customer_id')
                    ->label('Customer Name')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                    Forms\Components\TextInput::make('number')
                    ->label('Invoice Number')
                    ->numeric(),


                    DatePicker::make('date')
                    ->default(now())
                        ->required(),


                ])
                ->columns(2),

                Section::make('Invoice Info')
                ->schema([

                    TableRepeater::make('ItemDataEntry')
                    ->relationship()
                    ->schema([

                        Select::make('item_id')
                        ->label('Item Name')
                        ->relationship('item','name')
                        ->searchable()
                        ->preload()
                        ->required(),

                        TextInput::make('qty')
                        ->live()
                        ->label('Quantity')
                        ->required(),

                        TextInput::make('rate')
                        ->live(onBlur:true)
                        ->afterStateUpdated(fn (Set $set, $state, Get $get) => $set('amount', ($state) * $get('qty') - $get('discount')))
                        // ->afterStateUpdated(function(Set $set)
                        // {
                        //     $set('qty','amount');

                        // })
                        ->label('Rate')
                        ->required(),

                        TextInput::make('discount')
                        ->afterStateUpdated(fn (Set $set, $state, Get $get ) => $set('amount', $get('qty') * $get('rate') - $state))

                        // ->afterStateUpdated(function($get)
                        // )
                        ->live(onBlur:true)
                        ->required(),

                        Textarea::make('description'),


                        TextInput::make('amount')
                        ->label('Total')
                        ->readOnly()
                        ->columnSpan(1),

                    ]),

                    Placeholder::make('sale')
                    ->label('Total Amount')
                    ->content(function ($get){
                        return collect($get('ItemDataEntry'))->sum('amount');
                    })


                    // ->('amount')(fn ($record) => $record->one + $record->two + $record->three)



                    //->numeric()
                    // ->default(function ($get){
                    //     return $get()->sum('rate');
                    // }),
                    // ->default(function (Get $get, Set $set) {
                    //     self::updateTotals($get, $set);
                    // }),
                    //->content(total)//(Itementry::where('invoice_id', '../../number')->sum('rate')),
                    // ->default(fn ($get) => Count($get('ItemDataEntry'))),




                    // Placeholder::make('sale')
                    // ->live()
                    // ->default($record->Itementry->sum('amount'))


                    // ->label('Subtotal')
                    // ->content(function($get)
                    // {
                    //     return Collect($get('amount'))
                    //     ->pluck('amount')
                    //     ->sum();
                    // }),

                ])


            ]);
    }




//  public function updateTotals($get)
//  {
//     $allitemlist = $get();
//     return $allitemlist;
//  }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')

                    ->sortable(),

                TextColumn::make('itemdataentry.item.name')
                ->wrap()
                    ->searchable(),
                // TextColumn::make('itemdataentry_sum_amount')->sum(['itemdataentry' => fn (Builder $query) => $query->where('item_id', '2')], 'amount'),



                Tables\Columns\TextColumn::make('number')
                ->label('Invoice Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('sale')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
