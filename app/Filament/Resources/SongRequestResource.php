<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SongRequestResource\Pages;
use App\Models\SongRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class SongRequestResource extends Resource
{
    protected static ?string $model = SongRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-musical-note';

    protected static string|UnitEnum|null $navigationGroup = 'Radio';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Details')
                    ->schema([
                        TextInput::make('song_title')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        TextInput::make('song_artist')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'playing' => 'Playing',
                                'played' => 'Played',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])->columns(2),

                Section::make('Requester Info')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled(),
                        TextInput::make('ip_address')
                            ->disabled(),
                        TextInput::make('guest_email')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('song_title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('song_artist')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Requester')
                    ->default('Guest')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'playing' => 'primary',
                        'played' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'playing' => 'Playing',
                        'played' => 'Played',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markPlayed')
                    ->label('Mark Played')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (SongRequest $record) => $record->update([
                        'status' => 'played',
                        'played_at' => now(),
                    ]))
                    ->visible(fn (SongRequest $record) => $record->status === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (SongRequest $record) => $record->update(['status' => 'rejected']))
                    ->visible(fn (SongRequest $record) => $record->status === 'pending')
                    ->requiresConfirmation(),
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
            'index' => Pages\ListSongRequests::route('/'),
            'edit' => Pages\EditSongRequest::route('/{record}/edit'),
        ];
    }
}
