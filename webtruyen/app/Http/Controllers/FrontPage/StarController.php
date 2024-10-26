<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use App\Models\Star;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class StarController extends Controller
{

    public function create(Request $request, $story)
    {
        $user_id = $request->getClientIp();
        if (Auth::check()) {
            $user_id = Auth::id();
        }
        $star = Star::query()->where('user_id', $user_id)
            ->where('story_id', $story)
            ->first()
        ;
        if (isset($star)) {
            return response()->json(['error'=>'Bạn đã đánh giá rồi.']);
        }
        try {
            Star::query()->create([
                'user_id' => $user_id,
                'story_id' => $story,
                'total' => $request->get('star'),
            ]);
        } catch (Throwable $e) {
            dd($e);
        }
        return response()->json(['success'=>'Bạn đã đánh giá thành công']);
    }
}
