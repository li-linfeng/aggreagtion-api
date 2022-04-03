<?php

namespace App\Providers;

use App\Response\FormatResponse;
use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\Weixin\WeixinExtendSocialite@handle',
            'SocialiteProviders\Apple\AppleExtendSocialite@handle',
        ],
        'Illuminate\Database\Events\QueryExecuted'            => [
            'App\Listeners\QueryListener',
        ],
        // 监听 Dingo API发送响应之前对响应进行转化的事件
        ResponseWasMorphed::class => [
            FormatResponse::class
        ],
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
