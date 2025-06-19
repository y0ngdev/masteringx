<?php

namespace App\Filament\Admin\Pages;

use App\Services\SettingsManager;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

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
                ] + $this->getSettings($settings)
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
//                                    ->required()
                                ,
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

                        Forms\Components\Tabs\Tab::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('pricing_title')
                                    ->label('Pricing Title')
//                                    ->required()
                                ,
                                Forms\Components\TextInput::make('pricing_subtitle')
                                    ->label('Pricing Subtitle')
//                                    ->required()
                                ,
                                Forms\Components\Repeater::make('plans')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
//                                            ->required()
                                        ,
                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
//                                            ->required()
                                        ,
                                        Forms\Components\TextInput::make('description')
//                                            ->required()
                                        ,
                                        Forms\Components\TagsInput::make('features')
//                                            ->required()
                                        ,
                                    ])
                                    ->columns(2)
                                    ->defaultItems(3)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('FAQ')
                            ->schema([
                                Forms\Components\TextInput::make('faq_title')
                                    ->label('FAQ Title')
//                                    ->required()
                                ,
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
//                                Forms\Components\Select::make('default_payment_provider')
//                                    ->label('Default Payment Provider')
//                                    ->options(fn () => \App\Models\PaymentProvider::pluck('name', 'code'))
//                                    ->required(),

                                Forms\Components\TextInput::make('currency')
                                    ->label('Default Currency')
                                    ->default('USD')
//                                    ->required()
                                ,

                                Forms\Components\Toggle::make('enable_trial')
                                    ->label('Enable Trial Period')
                                    ->default(true),

                                Forms\Components\TextInput::make('trial_days')
                                    ->label('Trial Period (Days)')
                                    ->numeric()
                                    ->default(14)
                                    ->required()
                                    ->visible(fn(Forms\Get $get) => $get('enable_trial')),

                                Forms\Components\Toggle::make('enable_coupons')
                                    ->label('Enable Coupons')
                                    ->default(true),

                                Forms\Components\Toggle::make('enable_refunds')
                                    ->label('Enable Refunds')
                                    ->default(true),
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

        if ($data['email']) {
            $d = $data['email'];

            $env = DotenvEditor::load();
            if ($env->getValue('MAIL_MAILER') !== $d['driver']) {

                $env->setKey('MAIL_MAILER', $d['driver']);
                $env->save();
            }

            if ($env->getValue('MAIL_SCHEME') !==$d['scheme']) {

                $env->setKey('MAIL_SCHEME',$d['scheme']);
                $env->save();
            }

            if ($env->getValue('MAIL_HOST') !==$d['host'] ) {

                $env->setKey('MAIL_HOST',$d['host']);
                $env->save();
            }

            if ($env->getValue('MAIL_PORT') !== $d['port']) {

                $env->setKey('MAIL_PORT',$d['port']);
                $env->save();
            }

            if ($env->getValue('MAIL_USERNAME') !== $d['username']) {

                $env->setKey('MAIL_USERNAME', $d['username']);
                $env->save();
            }

            if ($env->getValue('MAIL_PASSWORD') !==$d['password']) {

                $env->setKey('MAIL_PASSWORD',$d['password'] );
                $env->save();
            }

            if ($env->getValue('MAIL_FROM_ADDRESS') !== $d['email']) {

                $env->setKey('MAIL_FROM_ADDRESS', $d['email']);
                $env->save();
            }
            if ($env->getValue('MAIL_FROM_NAME') !== $d['name']) {

                $env->setKey('MAIL_FROM_NAME', $d['name']);
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
