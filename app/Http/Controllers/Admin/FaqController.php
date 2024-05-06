<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_deleted', 0)->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('admin.faqs.partials.list', compact('faqs'))->render());
        }

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return redirect()->route('admin.faqs')->with('success', 'FAQ created successfully');
    }

    public function edit(Request $request)
    {
        $faq = Faq::where('id', $request->id)->first();

        // Redirect to listing page if not found
        if (!$faq) {
            return redirect()->back()->with('error', 'Faq not found');
        }
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // dd($request->all());
        $faq = Faq::find($request->id); // Using find() instead of where()->first()

        if ($faq) {
            // If the admin is found, update its attributes
            $faq->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.faqs')->with('success', 'FAQ updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'FAQ not found or not authorized!');
        }
    }

    public function updateStatus(Request $request)
    {
        $faq = Faq::where('id', $request->id)
            ->first();

        if ($faq) {
            $faq->update(['is_active' => !$faq->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$faq->is_active, 'message' => 'FAQ status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'FAQ not found']);
    }
}
