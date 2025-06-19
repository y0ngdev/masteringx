<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Cache;

class Settings extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.admin.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettings());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required(),
                                Forms\Components\TextInput::make('site_description')
                                    ->label('Site Description'),
                                Forms\Components\FileUpload::make('site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->directory('logos'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Landing Page')
                            ->schema([
                                Forms\Components\TextInput::make('hero_title')
                                    ->label('Hero Title')
                                    ->required(),
                                Forms\Components\TextInput::make('hero_subtitle')
                                    ->label('Hero Subtitle')
                                    ->required(),
                                Forms\Components\TextInput::make('hero_cta_text')
                                    ->label('Hero CTA Text')
                                    ->required(),
                                Forms\Components\Repeater::make('features')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required(),
                                        Forms\Components\TextInput::make('description')
                                            ->required(),
                                        Forms\Components\TextInput::make('icon')
                                            ->required(),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('pricing_title')
                                    ->label('Pricing Title')
                                    ->required(),
                                Forms\Components\TextInput::make('pricing_subtitle')
                                    ->label('Pricing Subtitle')
                                    ->required(),
                                Forms\Components\Repeater::make('plans')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('description')
                                            ->required(),
                                        Forms\Components\TagsInput::make('features')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('FAQ')
                            ->schema([
                                Forms\Components\TextInput::make('faq_title')
                                    ->label('FAQ Title')
                                    ->required(),
                                Forms\Components\Repeater::make('faq_items')
                                    ->schema([
                                        Forms\Components\TextInput::make('question')
                                            ->required(),
                                        Forms\Components\Textarea::make('answer')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Testimonials')
                            ->schema([
                                Forms\Components\TextInput::make('testimonials_title')
                                    ->label('Testimonials Title')
                                    ->required(),
                                Forms\Components\Repeater::make('testimonials')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('position')
                                            ->required(),
                                        Forms\Components\Textarea::make('content')
                                            ->required(),
                                        Forms\Components\FileUpload::make('avatar')
                                            ->image()
                                            ->directory('testimonials'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Payments')
                            ->schema([
                                Forms\Components\Select::make('default_payment_provider')
                                    ->label('Default Payment Provider')
                                    ->options(fn () => \App\Models\PaymentProvider::pluck('name', 'code'))
                                    ->required(),

                                Forms\Components\TextInput::make('currency')
                                    ->label('Default Currency')
                                    ->default('USD')
                                    ->required(),

                                Forms\Components\Toggle::make('enable_trial')
                                    ->label('Enable Trial Period')
                                    ->default(true),

                                Forms\Components\TextInput::make('trial_days')
                                    ->label('Trial Period (Days)')
                                    ->numeric()
                                    ->default(14)
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('enable_trial')),

                                Forms\Components\Toggle::make('enable_coupons')
                                    ->label('Enable Coupons')
                                    ->default(true),

                                Forms\Components\Toggle::make('enable_refunds')
                                    ->label('Enable Refunds')
                                    ->default(true),
                            ]),

                        Forms\Components\Tabs\Tab::make('Notifications')
                            ->schema([
                                Forms\Components\Toggle::make('notify_subscription_created')
                                    ->label('Notify on Subscription Created')
                                    ->default(true),

                                Forms\Components\Toggle::make('notify_subscription_cancelled')
                                    ->label('Notify on Subscription Cancelled')
                                    ->default(true),

                                Forms\Components\Toggle::make('notify_payment_failed')
                                    ->label('Notify on Payment Failed')
                                    ->default(true),

                                Forms\Components\Toggle::make('notify_trial_ending')
                                    ->label('Notify on Trial Ending')
                                    ->default(true),

                                Forms\Components\TextInput::make('trial_ending_days')
                                    ->label('Days Before Trial Ends to Notify')
                                    ->numeric()
                                    ->default(3)
                                    ->required()
                                    ->visible(fn (Forms\Get $get) => $get('notify_trial_ending')),
                            ]),

                        Forms\Components\Tabs\Tab::make('Email')
                            ->schema([
                                Forms\Components\TextInput::make('mail_from_address')
                                    ->label('From Email Address')
                                    ->email()
                                    ->required(),

                                Forms\Components\TextInput::make('mail_from_name')
                                    ->label('From Name')
                                    ->required(),

                                Forms\Components\Select::make('mail_mailer')
                                    ->label('Mail Driver')
                                    ->options([
                                        'smtp' => 'SMTP',
                                        'mailgun' => 'Mailgun',
                                        'ses' => 'Amazon SES',
                                        'postmark' => 'Postmark',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('mail_host')
                                    ->label('SMTP Host')
                                    ->visible(fn (Forms\Get $get) => $get('mail_mailer') === 'smtp'),

                                Forms\Components\TextInput::make('mail_port')
                                    ->label('SMTP Port')
                                    ->numeric()
                                    ->visible(fn (Forms\Get $get) => $get('mail_mailer') === 'smtp'),

                                Forms\Components\TextInput::make('mail_username')
                                    ->label('SMTP Username')
                                    ->visible(fn (Forms\Get $get) => $get('mail_mailer') === 'smtp'),

                                Forms\Components\TextInput::make('mail_password')
                                    ->label('SMTP Password')
                                    ->password()
                                    ->visible(fn (Forms\Get $get) => $get('mail_mailer') === 'smtp'),

                                Forms\Components\Select::make('mail_encryption')
                                    ->label('SMTP Encryption')
                                    ->options([
                                        'tls' => 'TLS',
                                        'ssl' => 'SSL',
                                    ])
                                    ->visible(fn (Forms\Get $get) => $get('mail_mailer') === 'smtp'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Cache::forget('settings');

        $this->notify('success', 'Settings saved successfully');
    }

    protected function getSettings(): array
    {
        return Cache::remember('settings', 3600, function () {
            return Setting::all()
                ->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])
                ->toArray();
        });
    }
}
