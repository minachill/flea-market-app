<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        $validated = $request->validated();

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'comment' => $validated['comment'],
        ]);

        return redirect()->back();
    }
}
