<?php

namespace App\Console\Commands;

use App\Facades\AnalyticsEventsFacade;
use App\Models\Act;
use App\Models\Round;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Redaelfillali\GoogleAnalyticsEvents\GoogleAnalyticsService;
use function Laravel\Prompts\error;

#[Signature('app:analytics-test-data')]
#[Description('Generate Analytics test events.')]
class GenerateAnalyticsTestData extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Generating Analytics test events...');
        $this->generateCollapseEvents();
        $this->generateVoteEvents();
        $this->generateSongPlayEvents();
        $this->generatePlaylistEvents();
        $this->generateDonationEvents();
        $this->generateActViewEvents();
        $this->generateSubscriberEvents();
        $this->generateContactMessageEvents();
    }

    protected function generateCollapseEvents(): void
    {
        $this->comment('- collapse sections');
        $sections = [
            // Rules
            ['page_title' => 'Contest Rules', 'section_id' => 'terminology'],
            ['page_title' => 'Contest Rules', 'section_id' => 'contest-brief'],
            ['page_title' => 'Contest Rules', 'section_id' => 'eligibility'],
            ['page_title' => 'Contest Rules', 'section_id' => 'song-criteria'],
            ['page_title' => 'Contest Rules', 'section_id' => 'stage-1-knockout-stage'],
            ['page_title' => 'Contest Rules', 'section_id' => 'stage-2-finals'],
            ['page_title' => 'Contest Rules', 'section_id' => 'how-votes-are-calculated'],
            ['page_title' => 'Contest Rules', 'section_id' => 'the-golden-buzzer'],
            ['page_title' => 'Contest Rules', 'section_id' => 'special-situations'],
            ['page_title' => 'Contest Rules', 'section_id' => 'advice-for-visitors'],

            // About
            ['page_title' => 'About the Project', 'section_id' => 'about-catawol-records'],
            ['page_title' => 'About the Project', 'section_id' => 'about-the-song'],
            ['page_title' => 'About the Project', 'section_id' => 'what-is-fold'],
            ['page_title' => 'About the Project', 'section_id' => 'who-is-silentmode'],
            ['page_title' => 'About the Project', 'section_id' => 'credits'],

        ];

        $event_count = fake()->numberBetween(40, 100);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('collapse_open', $sections[array_rand($sections)]);
        }
    }

    protected function generateVoteEvents(): void
    {
        $this->comment('- votes');
        $rounds = Round::get();
        if ($rounds->isEmpty())
        {
            error('No Rounds exist.');
        }
        else
        {
            $event_count = fake()->numberBetween(10, 50);
            for ($i = 1; $i <= $event_count; $i++)
            {
                $this->postEvent('vote', [
                    'round'   => $rounds->random()->full_title,
                    'choices' => fake()->numberBetween(1, 3)
                ]);
            }
        }

    }

    protected function generateSongPlayEvents(): void
    {
        $this->comment('- song plays');
        $act_slugs = Act::whereHas('songs')->pluck('slug');

        if ($act_slugs->isEmpty())
        {
            error('No Acts with Songs available.');

            return;
        }

        $event_count = fake()->numberBetween(100, 500);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('song_play', ['act' => $act_slugs->random()]);
        }
    }

    protected function generateDonationEvents(): void
    {
        $this->comment('- donations');

        $event_count = fake()->numberBetween(10, 50);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('donation', [
                'value'     => fake()->randomFloat(2, 1, 100),
                'anonymous' => fake()->boolean,
            ]);
        }
    }

    protected function generateActViewEvents(): void
    {
        $this->comment('- Act profile views');

        $acts        = Act::all();
        $event_count = fake()->numberBetween(10, 100);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('dialog_open', [
                'type' => 'act',
                'act'  => fake()->randomElement($acts)->slug,
            ]);
        }
    }

    protected function generateSubscriberEvents(): void
    {
        $this->comment('- Subscribers');

        $event_count = fake()->numberBetween(10, 100);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('subscriber', [
                'value' => fake()->boolean ? 1 : -1,
            ]);
        }
    }

    protected function generateContactMessageEvents(): void
    {
        $this->comment('- contact messages');

        $event_count = fake()->numberBetween(10, 100);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('contact_sent', [
                'subscribed' => fake()->boolean,
            ]);
        }
    }

    protected function generatePlaylistEvents(): void
    {
        $this->comment('- playlist');

        $rounds      = Round::pluck('id');
        $event_count = fake()->numberBetween(10, 50);
        for ($i = 1; $i <= $event_count; $i++)
        {
            $this->postEvent('playlist', [
                'round_id' => fake()->randomElement($rounds),
                'button'   => fake()->randomElement(['prev', 'next']),
            ]);
        }
    }

    private function postEvent(string $eventName, array $dimensions): void
    {
        AnalyticsEventsFacade::send($eventName, $dimensions);
    }
}
