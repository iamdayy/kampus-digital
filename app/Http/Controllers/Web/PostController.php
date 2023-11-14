<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\SpladeTable;

class PostController extends Controller
{
    /**
     * display all post data
     */
    public function index()
    {
        $posts = Post::latest()->paginate(7);

        return view('posts.index', [
            'posts' => SpladeTable::for($posts)
            ->column('image')
            ->column('title')
            ->column('content')
            ->column('action')
        ]);
    }
    public function create()
    {
        return view('posts.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);
        return redirect(route('posts.index'));
    }
}
