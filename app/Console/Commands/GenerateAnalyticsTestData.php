<?php

namespace App\Console\Commands;

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
    protected $description = 'Generate Analytics test data.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->generateCollapseEvents();
        $this->generateVoteEvents();
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

        foreach (range(1, 50) as $ignored)
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
            foreach (range(1, 50) as $ignored)
            {
                $this->postEvent('vote', ['round' => $rounds->random()->full_title]);
            }
        }

    }

    private function postEvent(string $eventName, array $dimensions): void
    {
        app(GoogleAnalyticsService::class)->sendEvent($eventName, $dimensions);
    }
}
