<?php

namespace App\Services;

use App\Models\Act;
use Intervention\Image\Laravel\Facades\Image;

class ActImage
{

    protected function getImageFolder(): string
    {
        return public_path('img/' . config('contest.images.subfolder'));
    }

    public function path(Act $act): string
    {
        return sprintf('%s/%s.png', $this->getImageFolder(), $act->slug);
    }

    public function url(Act $act): string
    {
        return asset(sprintf('img/%s/%s.png', config('contest.images.subfolder'), $act->slug), true);
    }

    public function create(Act $act, string $image): void
    {
        // Create the folder if it doesn't exist.
        @mkdir($this->getImageFolder(), 0755, true);

        // Save the image.
        Image::read($image)
             ->scaleDown(...config('contest.images.resize'))
             ->save($this->path($act));
    }

    public function delete(Act $act): void
    {
        @unlink($this->path($act));
    }

    public function exists(Act $act): bool
    {
        return file_exists($this->path($act));
    }

}
