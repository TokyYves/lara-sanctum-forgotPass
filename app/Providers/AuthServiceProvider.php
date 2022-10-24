<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });
        Gate::define('update-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id;
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url ){
            $spaUrl = "http://spa-url?email_verify_url=".$url;

            return (new MailMessage)
                ->subject("verification d'email")
                ->line('blablabla')
                ->action('verifier l\'email',$spaUrl)
                ->line('verifier l\'email',$url);
        });

    }
}
