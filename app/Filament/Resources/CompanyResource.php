<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use App\Notifications\CompanyApprovalStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use App\Notifications\CompanyApproved;
use Filament\Notifications\Notification as FilamentNotification;


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
                ]),
                Section::make('Address Information')->schema([
                    TextInput::make('address')->nullable(),
                    TextInput::make('city')->nullable(),
                    TextInput::make('state')->nullable(),
                    TextInput::make('country')->nullable(),
                    TextInput::make('pincode')->nullable(),
                ]),
                Section::make('Additional Info')->schema([
                    Textarea::make('description')->nullable(),
                    Select::make('status')->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])->default('pending'),
                    Textarea::make('decline_reason')->nullable(),
                ])
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
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('contact_number')->sortable(),
                TextColumn::make('city')->sortable(),
                SelectColumn::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public function approveCompany($record)
    {
        $record->update(['status' => 'approved']);

        // Notify Company via Email
        $record->user->notify(new CompanyApproved($record));

        // Notify Admins via Filament
        FilamentNotification::make()
            ->title('Company Approved')
            ->body("The company **{$record->name}** has been approved.")
            ->success()
            ->send();
    }
}
