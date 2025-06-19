<?php

namespace App\Filament\Admin\Pages;

use App\Services\SettingsManager;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class Settings extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.admin.pages.settings';

    public ?array $data = [];

    public function mount(SettingsManager $settings): void
    {

        $this->form->fill([
            'email' => [

                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'scheme' => config('mail.mailers.smtp.scheme'),
                'username' => config('mail.mailers.smtp.username'),
                'password' => config('mail.mailers.smtp.password'),
                'email' => config('mail.from.address'),
                'name' => config('mail.from.name'),
            ],
            'payments' => [
                    'publishable' => config('payments.drivers.stripe.public_key'),
                    'secret' => config('payments.drivers.stripe.secret_key'),
                    'webhook_secret' => config('payments.drivers.stripe.webhook_secret'),
                    'webhook' => route('cashier.webhook')

                ]
                + $this->getSettings($settings)
        ]);
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
                                    ->required() ,
                                Forms\Components\TextInput::make('site_description')
                                    ->label('Site Description'),
                                Forms\Components\FileUpload::make('site_logo')
                                    ->label('Site Logo')
                                    ->image()
                                    ->directory('site'),
                                Forms\Components\FileUpload::make('site_favicon')
                                    ->label('Site Favicon')
                                    ->image()
                                    ->directory('site'),
//                                add support email
                            ]),

                        Forms\Components\Tabs\Tab::make('Landing Page')
                            ->schema([
                                Forms\Components\TextInput::make('hero_title')
                                    ->label('Hero Title')
//                                    ->required()
                                ,
                                Forms\Components\TextInput::make('hero_subtitle')
                                    ->label('Hero Subtitle')
//                                    ->required()
                                ,
                                Forms\Components\TextInput::make('hero_cta_text')
                                    ->label('Hero CTA Text')
//                                    ->required()
                                ,
                                Forms\Components\Repeater::make('features')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
//                                            ->required()
                                        ,
                                        Forms\Components\TextInput::make('description')
//                                            ->required()
                                        ,
                                        Forms\Components\TextInput::make('icon')
//                                            ->required()
                                        ,
                                    ])
                                    ->columns(3)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('FAQ')
                            ->schema([
                                Forms\Components\TextInput::make('faq_title')
                                    ->label('FAQ Title')
                                    ->required(),
                                Forms\Components\TextInput::make('faq_description')
                                    ->label('FAQ Description')
                                    ->required(),

                                Forms\Components\Repeater::make('faq_items')
                                    ->schema([
                                        Forms\Components\TextInput::make('question')
//                                            ->required()
                                        ,
                                        Forms\Components\Textarea::make('answer')
//                                            ->required()
                                        ,
                                    ])
                                    ->columns(2)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),


                        Forms\Components\Tabs\Tab::make('Payments')
                            ->schema([
                                Forms\Components\TextInput::make('payments.publishable')
                                    ->label('Stripe Publishable Key')
                                    ->required(),

                                Forms\Components\TextInput::make('payments.secret')
                                    ->label('Stripe Secret Key')
                                    ->required(),

                                Forms\Components\TextInput::make('payments.webhook_secret')
                                    ->label(' Stripe Webhook Secret ')
                                    ->required(),


                                Forms\Components\TextInput::make('payments.webhook')
                                    ->label('Webhook URL')
                                    ->disabled()
                                    ->suffixAction(CopyAction::make()),
                            ]),

                        Forms\Components\Tabs\Tab::make('Email Configuration')
                            ->schema([
                                Forms\Components\Select::make('email.driver')
                                    ->label('Mail Driver')
                                    ->options([
                                        'log' => 'Log',
                                        'smtp' => 'SMTP',
                                    ])
                                    ->required(),


                                Forms\Components\TextInput::make('email.host')
                                    ->label('SMTP Host'),


                                Forms\Components\TextInput::make('email.port')
                                    ->label('SMTP Port'),


                                Forms\Components\Select::make('email.scheme')
                                    ->label('SMTP Scheme')
                                    ->options([
                                        'null' => 'null',
                                        'smtp' => 'SMTP',
                                        'smtps' => 'SMTPS',
                                    ]),


                                Forms\Components\TextInput::make('email.name')
                                    ->label('From Name')
                                    ->required(),


                                Forms\Components\TextInput::make('email.email')
                                    ->label('From Email Address')
                                    ->email()
                                    ->required(),

                                Forms\Components\TextInput::make('email.username')
                                    ->label('SMTP Username'),

                                Forms\Components\TextInput::make('email.password')
                                    ->label('SMTP Password')
                                    ->password(),


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

    public function save(SettingsManager $settings): void
    {
        $data = $this->form->getState();
        $env = DotenvEditor::load();

        if (!empty($data['email'])) {
            $d = $data['email'];

            $envKeys = [
                'MAIL_MAILER' => $d['driver'],
                'MAIL_SCHEME' => $d['scheme'],
                'MAIL_HOST' => $d['host'],
                'MAIL_PORT' => $d['port'],
                'MAIL_USERNAME' => $d['username'],
                'MAIL_PASSWORD' => $d['password'],
                'MAIL_FROM_ADDRESS' => $d['email'],
                'MAIL_FROM_NAME' => $d['name'],
            ];

            $changed = false;

            foreach ($envKeys as $key => $newValue) {
                if ($env->getValue($key) !== $newValue) {
                    $env->setKey($key, $newValue);
                    $changed = true;
                }
            }

            if ($changed) {
                $env->save();
            }
        }
        if (!empty($data['payments'])) {
            $d = $data['payments'];

            $envKeys = [
                'STRIPE_KEY' => $d['publishable'],
                'STRIPE_SECRET' => $d['secret'],
                'STRIPE_WEBHOOK_SECRET' => $d['webhook_secret'],

            ];


            $changed = false;

            foreach ($envKeys as $key => $newValue) {
                if ($env->getValue($key) !== $newValue) {
                    $env->setKey($key, $newValue);
                    $changed = true;
                }
            }

            if ($changed) {
                $env->save();
            }
        }
        foreach ($data as $key => $value) {
            $type = match (true) {
                is_bool($value) => 'bool',
                is_int($value) => 'int',
                is_array($value) => 'json',
                default => 'string',
            };
            $settings->set($key, $value, $type);
        }

        \Cache::forget('settings');

        $this->notify('success', 'Settings saved successfully');
    }

    protected function getSettings(SettingsManager $settings = null): array
    {
        $settings ??= app(SettingsManager::class);
        return \Cache::remember('settings', 3600, function () use ($settings) {
            return $settings->all();
        });
    }
}
