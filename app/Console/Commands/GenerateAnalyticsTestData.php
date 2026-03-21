<?php

namespace App\Console\Commands;

use App\Models\Act;
use App\Models\Round;
use Illuminate\Console\Command;
use Redaelfillali\GoogleAnalyticsEvents\GoogleAnalyticsService;
use function Laravel\Prompts\error;

class GenerateAnalyticsTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analytics-test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Analytics test events.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Generating Analytics test events...');
        $this->generateCollapseEvents();
        $this->generateVoteEvents();
        $this->generateSongPlayEvents();
        $this->generateDonationEvents();
        $this->generateActViewEvents();
        $this->generateSubscriberEvents();
    }

    protected function generateCollapseEvents(): void
    {
        $this->comment('- collapse sections');
        $sections = [
            // Rules
            ['pageTitle' => 'Contest Rules', 'section_id' => 'terminology'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'contest-brief'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'eligibility'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'song-criteria'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'stage-1-knockout-stage'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'stage-2-finals'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'how-votes-are-calculated'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'the-golden-buzzer'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'special-situations'],
            ['pageTitle' => 'Contest Rules', 'section_id' => 'advice-for-visitors'],

            // About
            ['pageTitle' => 'About the Project', 'section_id' => 'about-catawol-records'],
            ['pageTitle' => 'About the Project', 'section_id' => 'about-the-song'],
            ['pageTitle' => 'About the Project', 'section_id' => 'what-is-fold'],
            ['pageTitle' => 'About the Project', 'section_id' => 'who-is-silentmode'],
            ['pageTitle' => 'About the Project', 'section_id' => 'credits'],

        ];

        $event_count = fake()->numberBetween(40, 100);
        foreach (range(1, $event_count) as $ignored)
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
            foreach (range(1, $event_count) as $ignored)
            {
                $this->postEvent('vote', ['round' => $rounds->random()->full_title]);
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
        foreach (range(1, $event_count) as $ignored)
        {
            $this->postEvent('song_play', ['act' => $act_slugs->random()]);
        }
    }

    protected function generateDonationEvents(): void
    {
        $this->comment('- donations');

        $event_count = fake()->numberBetween(10, 50);
        foreach (range(1, $event_count) as $ignored)
        {
            $this->postEvent('donation', [
                'value' => fake()->randomFloat(1, 100),
                'anonymous'  => fake()->boolean
            ]);
        }
    }

    protected function generateActViewEvents(): void
    {
        $this->comment('- Act profile views');

        $acts        = Act::all();
        $event_count = fake()->numberBetween(10, 100);
        foreach (range(1, $event_count) as $ignored)
        {
            $this->postEvent('dialog_open', [
                'type' => 'act',
                'act'  => fake()->randomElement($acts)->slug
            ]);
        }
    }

    protected function generateSubscriberEvents(): void
    {
        $this->comment('- Subscribers');

        $event_count = fake()->numberBetween(10, 100);
        foreach (range(1, $event_count) as $ignored)
        {
            $this->postEvent('subscriber', [
                'value' => fake()->boolean ? 1 : -1
            ]);
        }
    }


    private function postEvent(string $eventName, array $dimensions): void
    {
        app(GoogleAnalyticsService::class)->sendEvent($eventName, $dimensions);
    }
}
