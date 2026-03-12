<?php

namespace Database\Seeders;

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
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
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
                    'The winning Song becomes the official anthem of the Contest',
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

        // Remove existing Act images.
        (new Filesystem)->cleanDirectory(public_path('img/act'));

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
            default:
                $this->command->comment('Nothing to do.');
        }
    }

    protected function stateStage1Countdown()
    {
        $songs = Song::all();
        $stage = Stage::first();

        RoundAllocateFacade::songs(
            $stage, $songs,
            songs_per_round: 4,
            round_start: now()->addDay(),
            round_duration: "1 days");
    }

    protected function stateStage1Active()
    {
        $songs = Song::all();
        $stage = Stage::first();

        RoundAllocateFacade::songs(
            $stage, $songs,
            songs_per_round: 4,
            round_start: now(),
            round_duration: "1 days");
    }

    protected function stateStage1Ended()
    {
        $songs = Song::all();
        $stage = Stage::first();

        RoundAllocateFacade::songs(
            $stage, $songs,
            songs_per_round: 4,
            round_start: now()->subDays(9),
            round_duration: "1 days");

        $stage->refresh();
        foreach ($stage->rounds as $round)
        {
            // Randomise the chance of a Stage having votes vs. resorting to a "manual vote".
            if (fake()->boolean(80))
            {
                $song_ids = $round->songs->pluck('id')->toArray();
                for ($i = 0; $i < 100; $i++)
                {
                    $votes = fake()->randomElements($song_ids, 3);
                    RoundVote::create([
                        'round_id'         => $round->id,
                        'first_choice_id'  => $votes[0],
                        'second_choice_id' => $votes[1],
                        'third_choice_id'  => $votes[2],
                    ]);
                }
            }

            // The possibility of being awarded a Golden Buzzer.
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
        }
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
