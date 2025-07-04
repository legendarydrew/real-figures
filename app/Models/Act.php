<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Act extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug')
                          ->usingSeparator('-');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(ActProfile::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    public function picture(): HasOne
    {
        return $this->hasOne(ActPicture::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ActMetaMember::class);
    }

    public function genres(): HasManyThrough
    {
        return $this->hasManyThrough(Genre::class, ActMetaGenre::class, 'act_id', 'id', 'id', 'genre_id');
    }

    public function languages(): HasManyThrough
    {
        return $this->hasManyThrough(Language::class, ActMetaLanguage::class, 'act_id', 'id', 'id', 'language_id');
    }

    public function traits(): HasMany
    {
        return $this->hasMany(ActMetaTrait::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ActMetaNote::class);
    }

    public function goldenBuzzers(): HasManyThrough
    {
        return $this->hasManyThrough(GoldenBuzzer::class, Song::class, 'act_id', 'song_id', 'id', 'id');
    }

    /**
     * A shortcut attribute for returning the Act's associated picture data.
     *
     * @return string|null
     */
    public function getImageAttribute(): string|null
    {
        return $this->picture?->image ?? null;
    }

}
