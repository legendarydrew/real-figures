<?php

namespace App\Console\Commands;

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
use App\Support\RehearseData;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Str;
use Throwable;

/**
 * Rehearse
 * This command is used for setting up a "dress rehearsal" at different stages of the Contest,
 * using information more closely resembling the actual Contest.
 */
#[Signature('app:rehearse '. '{state? : The state of the Contest to set up.} '. '{manual? : Require a manual vote.}')]
#[Description('Set up a "dress rehearsal" for the Contest.')]
class Rehearse extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $state = $this->argument('state');
        $manual_vote = $this->argument('manual');
        $this->info("\nSetting up a dress rehearsal...");

        $this->removeExistingData();
        $this->createStages();
        $this->createActs();

        if (! ($state || array_key_exists($state, RehearseData::STATES))) {
            $answer = $this->choice('Which state of the Contest should be set up?', RehearseData::STATES);
            $state = array_search($answer, RehearseData::STATES);
            // NOTE: choice() returns the text corresponding to the selected option.
        }
        if (is_null($manual_vote) && in_array($state, [4, 7])) {
            $manual_vote = $this->confirm('Set up manual voting?');
        }
        $this->setupState($state, $manual_vote);

        $this->info('Done.');
    }

    /**
     * Remove existing Contest information.
     */
    protected function removeExistingData(): void
    {
        $this->comment('- removing existing Rounds');
        RoundVote::truncate();
        Round::truncate();
        $this->comment('- removing existing Stages');
        Stage::truncate();
        $this->comment('- removing existing Songs');
        Song::truncate();
        $this->comment('- removing existing Acts');
        Act::truncate();

        // Remove existing Act images, replacing them with those for the defined Acts.
        (new Filesystem)->cleanDirectory(public_path('img/act'));
    }

    /**
     * Create all the Stages for the Contest.
     */
    protected function createStages(): void
    {
        $this->comment('- creating Stages');
        Stage::factory()->createMany(RehearseData::STAGES);
    }

    /**
     * Create the participating Acts, each with a Song.
     * At the moment there isn't an actual video associated with each Song.
     */
    protected function createActs(): void
    {
        $this->comment('- creating Acts (with Songs)');

        // Copy Act images to the respective folder.
        (new Filesystem)->copyDirectory(resource_path('rehearsal/img'), public_path('img/act'));

        // Create the Acts with their respective information.
        foreach (RehearseData::ACTS as $act) {
            $row = Act::factory()->withSong()->createOne([
                'name' => $act['name'],
                'subtitle' => $act['subtitle'],
                'slug' => Str::slug("{$act['name']} {$act['subtitle']}"),
                'is_fan_favourite' => $act['is_fan_favourite'],
            ]);

            // languages
            $languages = Language::whereIn('code', $act['languages'])->pluck('id');
            ActMetaLanguage::factory()->createMany(
                $languages->map(fn ($language_id) => [
                    'act_id' => $row->id,
                    'language_id' => $language_id,
                ]));

            // genres
            $act['genres'] = array_map(fn ($genre) => ucwords($genre), $act['genres']);
            Genre::upsert(array_map(fn ($genre) => ['name' => $genre], $act['genres']), 'name');
            $genres = Genre::whereIn('name', $act['genres'])->pluck('id');
            ActMetaGenre::factory()->createMany(
                $genres->map(fn ($genre_id) => [
                    'act_id' => $row->id,
                    'genre_id' => $genre_id,
                ]));

            // traits
            ActMetaTrait::factory()->createMany(collect($act['traits'])->map(fn ($trait) => [
                'act_id' => $row->id,
                'trait' => $trait,
            ]));

            // notes
            ActMetaNote::factory()->createMany(collect($act['notes'])->map(fn ($note) => [
                'act_id' => $row->id,
                'note' => $note,
            ]));
        }
    }

    /**
     * Set up the Contest stages and rounds, based on the requested state.
     *
     * @throws Throwable
     */
    protected function setupState(int $state, ?bool $manual_vote): void
    {
        if (array_key_exists($state, RehearseData::STATES)) {
            $this->info('Setting up '.RehearseData::STATES[$state]);
            if ($manual_vote) {
                $this->info('- manual voting required');
            }
        } else {
            $this->error('No such state.');

            return;
        }

        match ($state) {
            2 => $this->stateStage1Countdown(),
            3 => $this->stateStage1Active(),
            4 => $this->stateStage1Ended($manual_vote),
            5 => $this->stateStage2Countdown(),
            6 => $this->stateStage2Active(),
            7 => $this->stateStage2Ended($manual_vote),
            8 => $this->stateOver(),
            default => $this->comment('Nothing to do.')
        };
    }

    /**
     * Set up a countdown to the beginning of Stage 1.
     *
     * @throws Throwable
     */
    protected function stateStage1Countdown(): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();

        $this->allocateStage($stage1, $songs, now()->addDay(), judgement: false);
    }

    /**
     * Set up a currently running Stage 1.
     *
     * @throws Throwable
     */
    protected function stateStage1Active(): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();

        $this->allocateStage($stage1, $songs, now());
    }

    /**
     * Set up an ended Stage 1, awaiting the determination of winning and runner-up Acts.
     *
     * @throws Throwable
     */
    protected function stateStage1Ended(bool $manual_vote): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true, manual_vote: $manual_vote);
    }

    /**
     * Set up a countdown to the beginning of Stage 2.
     * Stage 1 should be completed.
     *
     * @throws Throwable
     */
    protected function stateStage2Countdown(): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now()->addDay(), songs_per_round: 32, round_duration: 7, judgement: false);
    }

    /**
     * Set up a currently running Stage 2.
     * Stage 1 should be completed.
     *
     * @throws Throwable
     */
    protected function stateStage2Active(): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now(), songs_per_round: 32, round_duration: 7, judgement: false);
    }

    /**
     * Set up an ended Stage 2, awaiting the determination of winning and runner-up Acts.
     * Stage 1 should be completed.
     *
     * @throws Throwable
     */
    protected function stateStage2Ended(bool $manual_vote): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now()->subDays(8), songs_per_round: 32, round_duration: 7, judgement: false, manual_vote: $manual_vote);
    }

    /**
     * Set up an ended Contest, with winning and runner-up Acts.
     * Both Stages should be completed.
     *
     * @throws Throwable
     */
    protected function stateOver(): void
    {
        $songs = Song::all();
        $stage1 = Stage::first();
        $stage2 = Stage::skip(1)->first();

        $this->allocateStage($stage1, $songs, now()->subDays(12), judgement: true);
        $finalists = $this->getWinningSongs($stage1, 3);

        $this->allocateStage($stage2, $finalists, now()->subDays(8), songs_per_round: 32, round_duration: 7, judgement: true, runner_up_count: 3);
    }

    /**
     * A convenience method for populating a Stage with Song entries.
     *
     * @param  Carbon  $start_time
     * @param  int  $round_duration  in days.
     * @param  bool  $judgement  if the Stage has ended, whether to determine the winners.
     * @param  int  $runner_up_count  the number of runner-ups to choose.
     *
     * @throws Throwable
     */
    protected function allocateStage(Stage $stage, Collection $songs, CarbonInterface $start_time,
        int $songs_per_round = 4, int $round_duration = 1, bool $judgement = false,
        bool $manual_vote = false,
        int $runner_up_count = 2): void
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
            ->filter(fn (Round $round) => $round->hasStarted())
            ->each(function (Round $round) use ($judgement, $manual_vote) {
                $song_ids = $round->songs->pluck('id')->toArray();

                // Potentially cast some votes for a random selection of Songs.
                // This should always happen if we want to determine which Songs qualify.
                if (! $manual_vote) {
                    $this->castVotes($judgement, $song_ids, $round);
                }

                // Potentially randomly award a Golden Buzzer to Songs.
                $this->awardGoldenBuzzers($song_ids, $round);
            });

        if ($stage->hasEnded()) {
            // Calculate scores for each Song.
            $stage->rounds()->each(function ($round) {
                ContestFacade::buildRoundOutcomes($round);
            });
            $stage->refresh();

            // Determine the winner and runner(s)-up, if requested.
            if ($judgement && ! $stage->requiresManualVote()) {
                $this->createStageWinners($stage, $runner_up_count);
            }
        }

    }

    /**
     * Returns a list of Songs that have qualified from the specified Stage.
     *
     * @param  int  $runners_up  the number of runners-up to choose.
     */
    protected function getWinningSongs(Stage $stage, int $runners_up): Collection
    {
        [$winners, $runners_up] = ContestFacade::determineStageWinners($stage, $runners_up);

        return $winners->map(fn ($winner) => $winner->song)
            ->concat($runners_up->map(fn ($runner) => $runner->song));

    }

    protected function castVotes(bool $judgement, array $song_ids, Round $round): int
    {
        $vote_count = ($judgement || fake()->boolean(70)) ?
            fake()->numberBetween(1, 200) : 0;
        for ($i = 0; $i < $vote_count; $i++) {
            $votes = fake()->randomElements($song_ids, 3);
            RoundVote::create([
                'round_id' => $round->id,
                'first_choice_id' => $votes[0],
                'second_choice_id' => $votes[1],
                'third_choice_id' => $votes[2],
            ]);
        }

        return $i;
    }

    protected function awardGoldenBuzzers(array $song_ids, Round $round): void
    {
        for ($i = 0; $i < 30; $i++) {
            if (fake()->boolean(10)) {
                $song_id = fake()->randomElement($song_ids);
                GoldenBuzzer::factory()->create([
                    'round_id' => $round->id,
                    'song_id' => $song_id,
                ]);
            }
        }
    }

    /**
     * @throws Throwable
     */
    protected function createStageWinners(Stage $stage, int $runner_up_count): void
    {
        [$winners, $runners_up] = ContestFacade::determineStageWinners($stage, $runner_up_count);
        DB::transaction(function () use ($stage, $winners, $runners_up) {
            $winners->each(function ($winner) use ($stage) {
                StageWinner::create([
                    'stage_id' => $stage->id,
                    'round_id' => $winner->round_id,
                    'song_id' => $winner->song_id,
                    'is_winner' => true,
                ]);
            });
            $runners_up->each(function ($winner) use ($stage) {
                StageWinner::create([
                    'stage_id' => $stage->id,
                    'round_id' => $winner->round_id,
                    'song_id' => $winner->song_id,
                    'is_winner' => false,
                ]);
            });
        });
    }
}
