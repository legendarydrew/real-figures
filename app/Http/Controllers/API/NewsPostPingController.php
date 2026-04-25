<?php

namespace App\Http\Controllers\API;

use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Models\NewsPost;

class NewsPostPingController extends Controller
{
    public function update(int $id)
    {
        $post = NewsPost::findOrFail($id);
        ContestFacade::pingNewsPost($post);
    }
}
