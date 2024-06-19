<?php

namespace Azuriom\Plugin\Changelog\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Changelog\Models\Category;
use Azuriom\Plugin\Changelog\Models\Update;
use Azuriom\Plugin\Changelog\Requests\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UpdateController extends Controller
{
    /**
     * Show the home admin page of the plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('position')->get();
        $updates = Update::with(['category'])->paginate();

        return view('changelog::admin.updates.index', [
            'categories' => $categories,
            'updates' => $updates,
            'title' => setting('changelog.title', 'Changelog'),
            'webhook' => setting('changelog.webhook'),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $settings = $this->validate($request, [
            'title' => ['required', 'string', 'max:50'],
            'webhook' => ['nullable', 'url'],
        ]);

        Setting::updateSettings(Arr::prependKeysWith($settings, 'changelog.'));

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('admin.settings.updated'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('changelog::admin.updates.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Azuriom\Plugin\Changelog\Requests\UpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateRequest $request)
    {
        $user = $request->user();
        $update = Update::create($request->validated());

        if (($webhookUrl = setting('changelog.webhook')) !== null) {
            rescue(fn () => $update->createDiscordWebhook($user)->send($webhookUrl));
        }

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Azuriom\Plugin\Changelog\Models\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function edit(Update $update)
    {
        return view('changelog::admin.updates.edit', [
            'update' => $update,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Azuriom\Plugin\Changelog\Requests\UpdateRequest  $request
     * @param  \Azuriom\Plugin\Changelog\Models\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Update $update)
    {
        $update->update($request->validated());

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Azuriom\Plugin\Changelog\Models\Update  $update
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Update $update)
    {
        $update->delete();

        return redirect()->route('changelog.admin.updates.index')
            ->with('success', trans('messages.status.success'));
    }
}
