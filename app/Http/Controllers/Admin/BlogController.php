<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('is_deleted', 0)->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('admin.blogs.partials.list', compact('blogs'))->render());
        }

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->author = Auth::user()->id;
        $blog->body = $request->body;
        $blog->save();

        return redirect()->route('admin.blogs')->with('success', 'Blog created successfully');
    }

    public function edit(Request $request)
    {
        $blog = Blog::where('id', $request->id)->first();

        // Redirect to listing page if not found
        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        }
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // dd($request->all());
        $blog = Blog::find($request->id); // Using find() instead of where()->first()

        if ($blog) {
            // If the admin is found, update its attributes
            $blog->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.blogs')->with('success', 'Blog updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'Blog not found or not authorized!');
        }
    }

    public function updateStatus(Request $request)
    {
        $blog = Blog::where('id', $request->id)
            ->first();

        if ($blog) {
            $blog->update(['is_active' => !$blog->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$blog->is_active, 'message' => 'Blog status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Blog not found']);
    }
}
