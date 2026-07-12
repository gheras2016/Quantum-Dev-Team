<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Technology;
use App\Models\Testimonial;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\ContactPolicy;
use App\Policies\FaqPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ProjectRequestPolicy;
use App\Policies\ServicePolicy;
use App\Policies\SocialLinkPolicy;
use App\Policies\TeamMemberPolicy;
use App\Policies\TechnologyPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Service::class => ServicePolicy::class,
        TeamMember::class => TeamMemberPolicy::class,
        Technology::class => TechnologyPolicy::class,
        Category::class => CategoryPolicy::class,
        SocialLink::class => SocialLinkPolicy::class,
        Contact::class => ContactPolicy::class,
        ProjectRequest::class => ProjectRequestPolicy::class,
        Post::class => PostPolicy::class,
        Testimonial::class => TestimonialPolicy::class,
        Faq::class => FaqPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        // Super admins bypass every gate/policy check.
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
