<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class postController extends Controller
{
    //
    public function clickLike(Request $request)
    {
        $contentId = $request->contentId;

        if (Auth::user() != null) {

            $likepostcount = DB::table('post_user')->where('user_id', Auth::user()->id)->where('post_id', $contentId)
                ->where('like', Auth::user()->id)->get()->count();
            if ($likepostcount == 0) {
                Db::table('post_user')->insert(['like' => Auth::user()->id, 'user_id' => Auth::user()->id, 'post_id' => $contentId, 'notLike' => 0]);
            }
        }

        $likecount = DB::table('post_user')->where('post_id', $contentId)->where('notLike', 0)->get()->count();



        // DB::table('post_user')->where('post_id', $contentId)->insert(['like' => 1]);

        // $like=

        return $likecount;
    }

    public function clickNotLike(Request $request)
    {
        $contentId = $request->contentId;

        if (Auth::user() != null) {

            $notlikepostcount = DB::table('post_user')->where('user_id', Auth::user()->id)->where('post_id', $contentId)
                ->where('notLike', Auth::user()->id)->get()->count();
            if ($notlikepostcount == 0) {
                Db::table('post_user')->insert(['notLike' => Auth::user()->id, 'user_id' => Auth::user()->id, 'post_id' => $contentId, 'like' => 0]);
            }
        }

        $likecount = DB::table('post_user')->where('post_id', $contentId)->where('like', 0)->get()->count();

        return $likecount;
    }

    public function showContent(Request $request)
    {
        $contentId = $request->contentId;

        $content =       Post::where('id', $contentId)->with('user')->get();
        return $content;
    }

    public function update(Request $request)
    {
        $contentId = $request->contentId;
        $updatedContent = $request->updatedContent;
        $updatedTitle = $request->updatedTitle;
        $deleteImage = $request->deleteImage;

        $imageFile = $request->imageFile;

        if ($imageFile) {
            Storage::delete($deleteImage);
            $name = $imageFile->getClientOriginalName();
            $extension = $imageFile->extension();
            $extensionout = Str::of($name)->basename('.' . $extension);
            $filename = $extensionout . '_' . time() . '.' . $extension;
            $imageFile->storeAs('public/image', $filename);
        } else {
            $filename = null;
        }

        DB::table('posts')->where('id', $contentId)->update(['title' => $updatedContent, 'content' => $updatedTitle, 'image' => $filename]);
    }



    public function index()
    {
        return Inertia::render('post/dong');
    }
    public function trance(Request $request)
    {
        $lang = $request->lang;
        $tr = new GoogleTranslate('ja');
        return $tr->translate($lang);
    }

    public function createPage(Request $request)
    {
        return Inertia::render('post/create');
    }

    public function delete(Request $request)
    {
        $contentId = $request->contentId;

        DB::table('posts')->where('id', $contentId)->delete();
    }

    public function imageDelete(Request $request)
    {
        $contentId = $request->contentId;

        DB::table('posts')->where('id', $contentId)->update(['image' => null]);
    }


    public function get()
    {

        $post = Post::where('user_id', auth()->user()->id)->with('user')->with('post_user')->latest()->get();
        // return Dongpost::all()->with('user');


        return $post;
    }

    public function create(Request $request)
    {
        $content = $request->content;
        $title = $request->title;
        $imageFile = $request->imageFile;

        if ($imageFile) {
            $name = $imageFile->getClientOriginalName();
            $extension = $imageFile->extension();
            $extensionout = Str::of($name)->basename('.' . $extension);
            $filename = $extensionout . '_' . time() . '.' . $extension;
            $imageFile->storeAs('public/image', $filename);
        } else {
            $filename = null;
        }

        DB::table('posts')->insert(['user_id' => 1, 'title' => $title, 'content' => $content, 'image' => $filename]);
    }
}
