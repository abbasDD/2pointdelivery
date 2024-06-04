<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HelpTopicController extends Controller
{

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);

        // Get a unique UUID
        $uuid = Str::random(8);

        do {
            $uuid = Str::random(8);
            $uuidExist = HelpTopic::where('uuid', $uuid)->first();
        } while ($uuidExist);

        $helpTopic = new HelpTopic();
        $helpTopic->uuid = $uuid;
        $helpTopic->name = $request->name;
        $helpTopic->content = $request->content;
        $helpTopic->save();

        return redirect()->back()->with('success', 'Help Topic created successfully');
    }
}
