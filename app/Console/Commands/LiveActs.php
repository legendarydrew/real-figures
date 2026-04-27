<?php

namespace App\Console\Commands;

use App\Models\Act;
use App\Models\ActMetaGenre;
use App\Models\ActMetaLanguage;
use App\Models\ActMetaNote;
use App\Models\ActMetaTrait;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Round;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\Stage;
use App\Support\RehearseData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * LiveActs
 * This command sets up the Contest for the live site.
 * It's virtually identical to rehearsal, except for the Act videos.
 */
#[Signature('app:live-acts')]
#[Description('Set up Acts for the Contest.')]
class LiveActs extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("\nSetting up the Contest...");

        $this->removeExistingData();
        $this->createStages();
        $this->createActs();

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
     */
    protected function createActs(): void
    {
        $this->comment('- creating Acts (with Songs)');

        // Copy Act images to the respective folder.
        (new Filesystem)->copyDirectory(resource_path('rehearsal/img'), public_path('img/act'));

        // Create the Acts with their respective information - only if a Song is defined.
        foreach (RehearseData::ACTS as $act)
        {
            if (empty($act['song']['url']['live']))
            {
                $this->error("No Song URL for $act[name].");
                continue;
            }

            $row = Act::factory()->createOne([
                'name'             => $act['name'],
                'subtitle'         => $act['subtitle'],
                'slug'             => Str::slug("{$act['name']} {$act['subtitle']}"),
                'is_fan_favourite' => $act['is_fan_favourite'],
                'rank'             => $act['rank'],
            ]);

            // Song.
            Song::factory()->for($row)
                ->withUrl($act['song']['url']['live'])
                ->createOne([
                    'title'       => $act['song']['title'] ?? config('contest.song.default-title'),
                    'act_id'      => $row->id,
                    'language_id' => Language::whereCode($act['song']['language'] ?? 'en')->first()->id,
                ]);

            // languages
            $languages = Language::whereIn('code', $act['languages'])->pluck('id');
            ActMetaLanguage::factory()->createMany(
                $languages->map(fn($language_id) => [
                    'act_id'      => $row->id,
                    'language_id' => $language_id,
                ]));

            // genres
            $act['genres'] = array_map(fn($genre) => ucwords($genre), $act['genres']);
            Genre::upsert(array_map(fn($genre) => ['name' => $genre], $act['genres']), 'name');
            $genres = Genre::whereIn('name', $act['genres'])->pluck('id');
            ActMetaGenre::factory()->createMany(
                $genres->map(fn($genre_id) => [
                    'act_id'   => $row->id,
                    'genre_id' => $genre_id,
                ]));

            // traits
            ActMetaTrait::factory()->createMany(collect($act['traits'])->map(fn($trait) => [
                'act_id' => $row->id,
                'trait'  => $trait,
            ]));

            // notes
            ActMetaNote::factory()->createMany(collect($act['notes'])->map(fn($note) => [
                'act_id' => $row->id,
                'note'   => $note,
            ]));
        }
    }

}
