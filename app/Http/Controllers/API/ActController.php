<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActRequest;
use App\Models\Act;
use App\Models\ActMetaGenre;
use App\Models\ActMetaLanguage;
use App\Models\Genre;
use App\Models\Language;
use App\Transformers\ActTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ActController extends Controller
{
    public function index(): JsonResponse
    {
        return fractal(Act::paginate(), new ActTransformer())->withResourceName('data')->respond();
    }

    public function show(int $act_id): JsonResponse
    {
        return fractal(Act::findOrFail($act_id), new ActTransformer())->includeProfile()->respond();
    }

    public function store(ActRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $act  = DB::transaction(function () use ($data)
        {
            $act = Act::factory()->create([
                'name'             => $data['name'],
                'slug' => $data['slug'],
                'is_fan_favourite' => $data['is_fan_favourite'] ?? false,
            ]);
            $this->updateActImage($act, $data);
            $this->updateActProfile($act, $data);
            $this->updateActMeta($act, $data);

            return $act;
        });

        if (isset($act))
        {
            return to_route('admin.acts.edit', ['id' => $act->id]);
        }
    }

    public function update(ActRequest $request, int $act_id): RedirectResponse
    {
        $act  = Act::findOrFail($act_id);
        $data = $request->validated();

        DB::transaction(function () use ($act, $data)
        {
            $act->update([
                'name'             => $data['name'],
                'slug' => $data['slug'],
                'is_fan_favourite' => $data['is_fan_favourite'] ?? false,
            ]);
            $this->updateActImage($act, $data);
            $this->updateActProfile($act, $data);
            $this->updateActMeta($act, $data);
        });

        return to_route('admin.acts.edit', ['id' => $act->id]);
    }

    protected function updateActImage(Act $act, array $data): void
    {
        if (!empty($data['image']))
        {
            $act->picture()->updateOrCreate(['act_id' => $act->id], ['image' => $data['image']]);
        }
        else
        {
            $act->picture()->delete();
        }
    }

    protected function updateActProfile(Act $act, array $data): void
    {
        if (isset($data['profile']))
        {
            $act->profile()->updateOrCreate(['act_id' => $act->id], $data['profile']);
            // https://stackoverflow.com/a/62489173/4073160
        }
        else
        {
            $act->profile()->delete();
        }
    }

    protected function updateActMeta(Act $act, array $data): void
    {
        $meta = ['members', 'notes', 'traits'];
        foreach ($meta as $meta_column)
        {
            $this->updateActMetaRelation($act, $meta_column, $data);
        }

        // Languages are passed as a list of language codes.
        if (isset($data['meta']['languages']))
        {
            $language_ids = Language::whereIn('code', $data['meta']['languages'])->pluck('id')->toArray();
            foreach ($language_ids as $language_id)
            {
                ActMetaLanguage::updateOrCreate([
                    'act_id'      => $act->id,
                    'language_id' => $language_id
                ]);
            }
            $act->languages()->whereNotIn('language_id', $language_ids)->delete();
        }

        // Genres are passed as a list of names.
        // These might include newly-created ones.
        if (isset($data['meta']['genres']))
        {
            // Save the list of genres, using title case for the genre name.
            $genres = array_map(fn($genre) => ucwords($genre), $data['meta']['genres']);
            Genre::upsert(array_map(fn($genre) => ['name' => $genre], $genres), 'name');

            // Associate the Act with the genres.
            $genre_ids = Genre::whereIn('name', $genres)->pluck('id')->toArray();
            foreach ($genre_ids as $genre_id)
            {
                ActMetaGenre::updateOrCreate([
                    'act_id'   => $act->id,
                    'genre_id' => $genre_id
                ]);
            }
            ActMetaGenre::whereActId($act->id)
                        ->whereNotIn('genre_id', $genre_ids)
                        ->delete();
        }
    }

    protected function updateActMetaRelation(Act $act, string $relation, array $data): void
    {
        if (isset($data['meta'][$relation]))
        {
            $existing_ids = array_filter(array_map(fn($row) => $row['id'] ?? null, $data['meta'][$relation]));
            if (count($existing_ids))
            {
                $act->$relation()->whereNotIn('id', $existing_ids)->delete();
            }
            else
            {
                $act->$relation()->delete();
            }

            foreach ($data['meta'][$relation] as $row)
            {
                $act->$relation()->updateOrCreate($row);
            }
        }
    }

    public function destroy(int $act_id): RedirectResponse
    {
        Act::findOrFail($act_id)->delete();

        return to_route('admin.acts');
    }
}
