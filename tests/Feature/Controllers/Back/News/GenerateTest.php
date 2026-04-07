<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class GenerateTest extends TestCase
{
    use DatabaseMigrations;

    // This is for testing the admin page!

    public function test_as_guest()
    {
        $this->get(route('admin.news-generate'))->assertRedirect('login');
    }

    public function test_as_user()
    {
        $this->actingAs($this->user)->get(route('admin.news-generate'))->assertInertia(
            fn(Assert $page) => $page->component('back/news-generate-page')
                                     ->has('types')
                                     ->missing('stages')
                                     ->missing('rounds')
                                     ->missing('acts')
                                     ->missing('news')
        );
    }

    #[Depends('test_as_user')]
    public function test_with_stages()
    {
        Stage::factory(2)->create();
        $this->actingAs($this->user)->get(route('admin.news-generate'))
             ->assertInertia(fn(Assert $page) => $page
                 ->reloadOnly('stages', fn(Assert $reload) => $reload
                     ->has('stages')
                 )
             );
    }

    #[Depends('test_as_user')]
    public function test_with_rounds()
    {
        Stage::factory(2)->withRounds()->create();
        $this->actingAs($this->user)->get(route('admin.news-generate'))
             ->assertInertia(fn(Assert $page) => $page
                 ->reloadOnly('rounds', fn(Assert $reload) => $reload
                     ->has('rounds')
                 )
             );
    }

    #[Depends('test_as_user')]
    public function test_with_acts()
    {
        Act::factory(10)->create();
        $this->actingAs($this->user)->get(route('admin.news-generate'))
             ->assertInertia(fn(Assert $page) => $page
                 ->reloadOnly('acts', fn(Assert $reload) => $reload
                     ->has('acts')
                 )
             );
    }

    #[Depends('test_as_user')]
    public function test_with_news()
    {
        NewsPost::factory(10)->published()->create();
        $this->actingAs($this->user)->get(route('admin.news-generate'))
             ->assertInertia(fn(Assert $page) => $page
                 ->reloadOnly('news', fn(Assert $reload) => $reload
                     ->has('news')
                 )
             );
    }
}
