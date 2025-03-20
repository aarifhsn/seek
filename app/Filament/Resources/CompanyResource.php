<?php

namespace App\Filament\Resources;

use App\Enums\CompanyStatus;
use App\Enums\CompanyType;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Company Management';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Company Details')->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->preload()
                        ->required()
                        ->hint('Select a User or Create a User from User Column'),
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('contact_number')->required(),
                    Select::make('industry')->options([
                        'IT' => 'IT',
                        'Healthcare' => 'Healthcare',
                        'Finance' => 'Finance',
                        'Education' => 'Education',
                    ])->nullable(),
                    TextInput::make('website')->url()->nullable(),
                    FileUpload::make('logo')->avatar()->label('Company Logo')->disk('public')->nullable(),
                    TextInput::make('slug')->required()->unique(),
                ])->collapsible(),
                Section::make('Address Information')->schema([
                    TextInput::make('address')->nullable(),
                    TextInput::make('city')->nullable(),
                    TextInput::make('state')->nullable(),
                    TextInput::make('country')->nullable(),
                    TextInput::make('pincode')->nullable(),
                ])->collapsed(),
                Section::make('Additional Info')->schema([
                    Textarea::make('description')->nullable(),
                    Select::make('status')
                        ->options(CompanyStatus::options())
                        ->default(CompanyStatus::PENDING->value)
                        ->reactive(),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->visible(fn(callable $get) => $get('status') === CompanyStatus::REJECTED->value)
                        ->placeholder('Provide a reason for rejection'),
                ])->collapsed(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->size(40)
                    ->circular()
                    ->defaultImageUrl('/images/favicon.ico'),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('subscription_type')
                    ->label('Company Type')
                    ->icon(
                        'heroicon-s-check-badge',
                    )
                    ->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('contact_number')->sortable(),
                TextColumn::make('city')->sortable(),
                SelectColumn::make('status')
                    ->options(CompanyStatus::options()),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(CompanyStatus::options()),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->visible(fn(Company $record) => $record->status === CompanyStatus::PENDING)
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function (Company $record) {
                        $record->status = CompanyStatus::APPROVED;
                        $record->save();
                    }),
                Action::make('reject')
                    ->visible(fn(Company $record) => $record->status === CompanyStatus::PENDING)
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->modalHeading('Reject Company')
                    ->modalSubheading('Please provide a reason for rejection')
                    ->modalButton('Reject Company')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for Rejection')
                            ->required(),
                    ])
                    ->action(function (Company $record, array $data) {
                        $record->status = CompanyStatus::REJECTED;
                        $record->rejection_reason = $data['rejection_reason'];
                        $record->save();
                    }),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
