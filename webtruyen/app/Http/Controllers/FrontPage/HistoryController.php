<?php

namespace App\Http\Controllers\FrontPage;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function destroy(Request $request): JsonResponse
    {
        $story_id = $request->get('story_id');
        if (Auth::check()) {
            History::query()
                ->where('user_id', Auth::id())
                ->where('story_id', $story_id)
                ->delete();

            return response()->json(['success' => 'thành công']);
        }
        $name = 'history_Stories';
        $time = time() + (86400 * 30);
        $histories = History::getHistoriesByGuest();
        unset($histories->$story_id);

        setcookie($name, json_encode($histories), $time, '/');

        return response()->json(['success' => 'thành công']);
    }
}
