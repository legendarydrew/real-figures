<?php

namespace Database\Seeders;

use App\Facades\ContestFacade;
use App\Facades\RoundAllocateFacade;
use App\Models\Act;
use App\Models\ActMetaGenre;
use App\Models\ActMetaLanguage;
use App\Models\ActMetaNote;
use App\Models\ActMetaTrait;
use App\Models\Genre;
use App\Models\GoldenBuzzer;
use App\Models\Language;
use App\Models\Round;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use App\Models\StageWinner;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Str;

class DressRehearsal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("\nStarting a dress rehearsal...");

        $this->removeExistingData();
        $this->createStages();
        $this->createActs();

        $answer = $this->command->choice('Which state of the Contest should there be?', STATES);
        $this->setState($answer);

        $this->command->info('Done.');
    }

    protected function stageDefinitions(): array
    {
        return [
            [
                'title'               => 'Stage 1: Knockouts',
                'description'         => 'Eight rounds with four Acts each, to determine which Songs go through to the finals.',
                'golden_buzzer_perks' => 'Acts will be given a profile and a new promotional image.'
            ],
            [
                'title'               => 'Stage 2: Finals',
                'description'         => 'Qualifying Acts go head-to-head to determine a Grand Winner and three Runners-Up. ' .
                    'The winning Song becomes the official anthem of the Contest.',
                'golden_buzzer_perks' => 'Acts will be represented as 3D-printed figures in SilentMode\'s style.'
            ]
        ];

    }

    /**
     * @return void
     */
    protected function removeExistingData(): void
    {
        $this->command->comment('- removing existing Rounds');
        RoundVote::truncate();
        Round::truncate();
        $this->command->comment('- removing existing Stages');
        Stage::truncate();
        $this->command->comment('- removing existing Songs');
        Song::truncate();
        $this->command->comment('- removing existing Acts');
        Act::truncate();
    }

    /**
     * @return void
     */
    protected function createStages(): void
    {
        $this->command->comment('- creating Stages');
        Stage::factory()->createMany($this->stageDefinitions());
    }

    /**
     * @return void
     */
    protected function createActs(): void
    {
        $this->command->comment('- creating Acts (with Songs)');

        // Remove existing Act images, replacing them with those for the defined Acts.
        $fs = new Filesystem();
        $fs->cleanDirectory(public_path('img/act'));
        $fs->copyDirectory(resource_path('rehearsal'), public_path('img/act'));

        foreach (ACTS as $act)
        {
            $row = Act::factory()->withSong()->createOne([
                'name'             => $act['name'],
                'slug'             => Str::slug("{$act['name']} {$act['subtitle']}"),
                'subtitle'         => $act['subtitle'],
                'is_fan_favourite' => $act['is_fan_favourite'],
            ]);

            $languages = Language::whereIn('code', $act['languages'])->pluck('id');
            ActMetaLanguage::factory()->createMany(
                $languages->map(fn($language_id) => [
                    'act_id'      => $row->id,
                    'language_id' => $language_id
                ]));

            $act['genres'] = array_map(fn($genre) => ucwords($genre), $act['genres']);
            Genre::upsert(array_map(fn($genre) => ['name' => $genre], $act['genres']), 'name');

            $genres = Genre::whereIn('name', $act['genres'])->pluck('id');
            ActMetaGenre::factory()->createMany(
                $genres->map(fn($genre_id) => [
                    'act_id'   => $row->id,
                    'genre_id' => $genre_id
                ]));

            ActMetaTrait::factory()->createMany(collect($act['traits'])->map(fn($trait) => [
                'act_id' => $row->id,
                'trait'  => $trait,
            ]));

            ActMetaNote::factory()->createMany(collect($act['notes'])->map(fn($note) => [
                'act_id' => $row->id,
                'note'   => $note,
            ]));
        }
    }

    /**
     * @param string $answer
     * @return void
     */
    protected function setState(string $answer): void
    {
        $this->command->info($answer);
        $last_step = array_search($answer, STATES);

        switch ($last_step)
        {
            case 1:
                $this->stateStage1Countdown();
                break;
            case 2:
                $this->stateStage1Active();
                break;
            case 3:
                $this->stateStage1Ended();
                break;
            case 4:
                $this->stateStage2Countdown();
                break;
            case 5:
                $this->stateStage2Active();
                break;
            case 6:
                $this->stateStage2Ended();
                break;
            case 7:
                $this->stateOver();
                break;
            default:
                $this->command->comment('Nothing to do.');
        }
    }

    protected function stateStage1Countdown(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();

        $this->allocateStage($stage1, $songs, now()->addDay(), judgement: false);
    }

    protected function stateStage1Active(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();

        $this->allocateStage($stage1, $songs, now());
    }

    protected function stateStage1Ended(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
    }

    protected function stateStage2Countdown(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now()->addDay(), songs_per_round: 32, round_duration: 7, judgement: false);
    }

    protected function stateStage2Active(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now(), songs_per_round: 32, round_duration: 7, judgement: false);
    }

    protected function stateStage2Ended(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now()->subDays(8), songs_per_round: 32, round_duration: 7, judgement: false);
    }

    protected function stateOver(): void
    {
        $songs  = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now()->subDays(8), songs_per_round: 32, round_duration: 7, judgement: true, runner_up_count: 3);
    }

    /**
     * A convenience method for populating a Stage with Song entries.
     *
     * @param Stage      $stage
     * @param Collection $songs
     * @param Carbon     $start_time
     * @param int        $songs_per_round
     * @param int        $round_duration in days.
     * @param bool       $judgement      if the Stage has ended, whether to determine the winners.
     * @param int        $runner_up_count
     * @return void
     * @throws \Throwable
     */
    protected function allocateStage(Stage $stage, Collection $songs, CarbonInterface $start_time, int $songs_per_round = 4, int $round_duration = 1, bool $judgement = false, int $runner_up_count = 2): void
    {
        // Allocate Songs to Rounds for the specified stage.
        RoundAllocateFacade::songs(
            $stage, $songs,
            songs_per_round: $songs_per_round,
            round_start: $start_time,
            round_duration: "$round_duration days");
        $stage->refresh();

        // For each Round in the Stage...
        $stage->rounds
            ->filter(fn(Round $round) => $round->hasStarted())
            ->each(function (Round $round) use ($judgement)
            {
                $song_ids = $round->songs->pluck('id')->toArray();

                // Potentially cast some votes for a random selection of Songs.
                // This should always happen if we want to determine which Songs qualify.
                $vote_count = ($judgement || fake()->boolean(70)) ?
                    fake()->numberBetween(1, 200) : 0;
                for ($i = 0; $i < $vote_count; $i++)
                {
                    $votes = fake()->randomElements($song_ids, 3);
                    RoundVote::create([
                        'round_id'         => $round->id,
                        'first_choice_id'  => $votes[0],
                        'second_choice_id' => $votes[1],
                        'third_choice_id'  => $votes[2],
                    ]);
                }

                // Potentially randomly award a Golden Buzzer to Songs.
                for ($i = 0; $i < 30; $i++)
                {
                    if (fake()->boolean(10))
                    {
                        $song_id = fake()->randomElement($song_ids);
                        GoldenBuzzer::factory()->create([
                            'round_id' => $round->id,
                            'song_id'  => $song_id
                        ]);
                    }
                }
            });

        if ($stage->hasEnded())
        {
            // Calculate scores for each Song.
            $stage->rounds()->each(function ($round)
            {
                ContestFacade::buildRoundOutcome($round);
            });
            $stage->refresh();

            // Determine the winner and runner(s)-up, if requested.
            if ($judgement && !$stage->requiresManualVote())
            {
                [$winners, $runners_up] = ContestFacade::determineStageWinners($stage, $runner_up_count);
                DB::transaction(function () use ($stage, $winners, $runners_up)
                {
                    $winners->each(function ($winner) use ($stage)
                    {
                        StageWinner::create([
                            'stage_id'  => $stage->id,
                            'round_id'  => $winner->round_id,
                            'song_id'   => $winner->song_id,
                            'is_winner' => true
                        ]);
                    });
                    $runners_up->each(function ($winner) use ($stage)
                    {
                        StageWinner::create([
                            'stage_id'  => $stage->id,
                            'round_id'  => $winner->round_id,
                            'song_id'   => $winner->song_id,
                            'is_winner' => false
                        ]);
                    });
                });
            }
        }

    }

    protected function getWinningSongs(Stage $stage, int $runners_up): Collection
    {
        [$winners, $runners_up] = ContestFacade::determineStageWinners($stage, $runners_up);
        return $winners->map(fn($winner) => $winner->song)
                       ->concat($runners_up->map(fn($runner) => $runner->song));

    }
}

const STATES = [
    'Coming soon',
    'Stage 1: Knockouts - Countdown',
    'Stage 1: Knockouts - Active',
    'Stage 1: Knockouts - End',
    'Stage 2: Finals - Countdown',
    'Stage 2: Finals - Active',
    'Stage 2: Finals - End',
    'Contest over'
];

const ACTS = [
    [
        'name'             => 'Airi Kisaragi',
        'subtitle'         => null,
        'genres'           => ['J-Pop'],
        'traits'           => [],
        'languages'        => ['jp'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Axel King',
        'subtitle'         => null,
        'genres'           => ['Blues', 'Rock'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Bryknii',
        'subtitle'         => null,
        'genres'           => ['EDM', 'Pop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => true,
        'notes'            => ['Has a bitter rivalry with Saima Gaines.']
    ],
    [
        'name'             => 'Bryknii',
        'subtitle'         => 'ft. Kat Soo',
        'genres'           => ['EDM', 'K-Pop', 'Pop'],
        'traits'           => [],
        'languages'        => ['en', 'ko'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    ['name'             => 'Buck & Jeb',
     'subtitle'         => null,
     'genres'           => ['Country & Western'],
     'traits'           => [],
     'languages'        => ['en'],
     'is_fan_favourite' => false,
     'notes'            => []
    ],
    ['name'             => 'BZpeople',
     'subtitle'         => null,
     'genres'           => ['EDM'],
     'traits'           => [],
     'languages'        => ['en'],
     'is_fan_favourite' => false,
     'notes'            => []
    ],
    ['name'             => 'Chelsea Chapel',
     'subtitle'         => null,
     'genres'           => ['Classical'],
     'traits'           => [],
     'languages'        => ['en', 'jp'],
     'is_fan_favourite' => false,
     'notes'            => []
    ],
    [
        'name'             => 'Cielo Groove',
        'subtitle'         => 'ft. Saima Gaines',
        'genres'           => ['Latin', 'R&B'],
        'traits'           => [],
        'languages'        => ['en', 'es'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Clémence Duval',
        'subtitle'         => null,
        'genres'           => [],
        'traits'           => [],
        'languages'        => ['en', 'fr'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Coastliners, the',
        'subtitle'         => null,
        'genres'           => ['Barbershop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Elora James',
        'subtitle'         => null,
        'genres'           => ['Soul'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Emma Finch',
        'subtitle'         => null,
        'genres'           => ['Americana', 'Folk'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Forty Twos, the',
        'subtitle'         => null,
        'genres'           => ['Barbershop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'GRMLN',
        'subtitle'         => null,
        'genres'           => ['EDM'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'High School Dropout',
        'subtitle'         => null,
        'genres'           => ['Rock'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => true,
        'notes'            => ['Has the closest relationship to the MODE Family of all the Acts.']
    ],
    [
        'name'             => 'Kendra Blaze',
        'subtitle'         => null,
        'genres'           => ['Pop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Loop Theory',
        'subtitle'         => null,
        'genres'           => ['Hip Hop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Lorien',
        'subtitle'         => null,
        'genres'           => ['Downtempo', 'EDM'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Magenta Men',
        'subtitle'         => null,
        'genres'           => ['Funk', 'Soul'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Marissa Wild',
        'subtitle'         => null,
        'genres'           => ['Pop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => ['Came out of retirement to enter the Contest.']
    ],
    [
        'name'             => 'Max Bellamy',
        'subtitle'         => 'and The Moonlight Swingers',
        'genres'           => ['Big Band', 'Swing'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Miles Everly',
        'subtitle'         => null,
        'genres'           => ['Soft Rock'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Raya Vibes',
        'subtitle'         => null,
        'genres'           => ['Reggae', 'R&B'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'RJ "Hound" Mercer',
        'subtitle'         => null,
        'genres'           => ['Blues'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Soline Bellefort',
        'subtitle'         => null,
        'genres'           => [],
        'traits'           => [],
        'languages'        => ['fr'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Sonder Drift',
        'subtitle'         => null,
        'genres'           => ['Downtempo', 'EDM'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => ['Only recently signed to CATAWOL Records after success as an independent Act.']
    ],
    [
        'name'             => 'SoraNami',
        'subtitle'         => null,
        'genres'           => ['J-Pop'],
        'traits'           => [],
        'languages'        => ['en', 'jp'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Synth & Son',
        'subtitle'         => null,
        'genres'           => ['EDM', 'Pop'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Vexon',
        'subtitle'         => null,
        'genres'           => ['EDM'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Violet Riot',
        'subtitle'         => null,
        'genres'           => ['Punk', 'Rock'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Violeta Montenegro',
        'subtitle'         => null,
        'genres'           => ['Latin'],
        'traits'           => [],
        'languages'        => ['en', 'es'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
    [
        'name'             => 'Westbound',
        'subtitle'         => null,
        'genres'           => ['Pop', 'Rock'],
        'traits'           => [],
        'languages'        => ['en'],
        'is_fan_favourite' => false,
        'notes'            => []
    ],
];
