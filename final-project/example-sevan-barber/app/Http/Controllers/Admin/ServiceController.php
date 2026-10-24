<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;

/** Route::resource('services')-ի ադմին կոնտրոլերը․ Gate-ը՝ ուղիների խմբում (can:manage-services) */
class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', [
            'services' => Service::withCount('bookings')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service(['is_active' => true])]);
    }

    public function store(ServiceRequest $request)
    {
        $service = new Service($request->validated());
        $service->slug = Service::uniqueSlugFor($service->name);
        $service->save();

        return redirect()->route('admin.services.index')->with('success', "«{$service->name}»-ը ավելացվեց։");
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->fill($request->validated());
        if ($service->isDirty('name')) {
            $service->slug = Service::uniqueSlugFor($service->name, $service->id);
        }
        $service->save();

        return redirect()->route('admin.services.index')->with('success', "«{$service->name}»-ը պահպանվեց։");
    }

    /** Soft delete․ հին ամրագրումները պահում են ծառայության անունը */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', "«{$service->name}»-ը տեղափոխվեց ջնջվածների մեջ։");
    }

    public function trashed()
    {
        return view('admin.services.trashed', ['services' => Service::onlyTrashed()->latest('deleted_at')->get()]);
    }

    public function restore(int $id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();

        return redirect()->route('admin.services.index')->with('success', "«{$service->name}»-ը վերականգնվեց։");
    }
}
