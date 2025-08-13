<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email')
            ])
            ->filters([
                Filter::make('name_filter_broken')
                    ->schema([
                        TextInput::make('name_filter_input_broken')
                            ->label('Name'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if(!array_key_exists('name_filter_input_broken', $data)) {
                            return $query;
                        }
                        return $query->where('name', 'like', "%{$data['name_filter_input_broken']}%");
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!isset($data['name_filter_input_broken']) || !$data['name_filter_input_broken']) {
                            return null;
                        }

                        return "Name broken: {$data['name_filter_input_broken']}";
                    }),
                Filter::make('name_filter_working')
                    ->schema([
                        TextInput::make('name_filter_input_working')
                            ->label('Name'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if(!array_key_exists('name_filter_input_working', $data)) {
                            return $query;
                        }
                        return $query->where('name', 'like', "%{$data['name_filter_input_working']}%");
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!isset($data['name_filter_input_working']) || !$data['name_filter_input_working']) {
                            return null;
                        }

                        return "Name working: {$data['name_filter_input_working']}";
                    })
            ])
            ->filtersFormSchema(fn(array $filters) => [
                $filters['name_filter_working'],
                Section::make('Name')
                    ->schema([
                        $filters['name_filter_broken']
                    ])
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
