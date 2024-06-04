<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpQuestion;
use App\Models\HelpTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpQuestionController extends Controller
{
    public function index()
    {
        $helpQuestions = HelpQuestion::paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('admin.helpQuestions.partials.list', compact('helpQuestions'))->render());
        }

        return view('admin.helpQuestions.index', compact('helpQuestions'));
    }

    public function create()
    {
        // Get Help Topics
        $helpTopics = HelpTopic::all();

        return view('admin.helpQuestions.create', compact('helpTopics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'help_topic_id' => 'required',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // Get a unique UUID
        $uuid = Str::random(8);

        do {
            $uuid = Str::random(8);
            $uuidExist = HelpQuestion::where('uuid', $uuid)->first();
        } while ($uuidExist);

        $helpQuestion = new HelpQuestion();
        $helpQuestion->uuid = $uuid;
        $helpQuestion->help_topic_id = $request->help_topic_id;
        $helpQuestion->question = $request->question;
        $helpQuestion->answer = $request->answer;
        $helpQuestion->save();

        return redirect()->route('admin.helpQuestions')->with('success', 'Help Question created successfully');
    }

    public function edit(Request $request)
    {
        // Get Help Topics
        $helpTopics = HelpTopic::all();

        $helpQuestion = HelpQuestion::where('id', $request->id)->first();

        // Redirect to listing page if not found
        if (!$helpQuestion) {
            return redirect()->back()->with('error', 'HelpQuestion not found');
        }
        return view('admin.helpQuestions.edit', compact('helpQuestion', 'helpTopics'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        // dd($request->all());
        $helpQuestion = HelpQuestion::find($request->id); // Using find() instead of where()->first()

        if ($helpQuestion) {
            // If the admin is found, update its attributes
            $helpQuestion->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.helpQuestions')->with('success', 'HelpQuestion updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'HelpQuestion not found or not authorized!');
        }
    }

    public function updateStatus(Request $request)
    {
        $helpQuestion = HelpQuestion::where('id', $request->id)
            ->first();

        if ($helpQuestion) {
            $helpQuestion->update(['is_active' => !$helpQuestion->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$helpQuestion->is_active, 'message' => 'HelpQuestion status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'HelpQuestion not found']);
    }
}
