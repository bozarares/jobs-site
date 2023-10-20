<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class CompanyController extends Controller
{
    public function index(): \Inertia\Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $companies = $user->companies()->get();
        return Inertia::render('Companies/Index', [
            'companies' => $companies,
        ]);
    }
    public function create()
    {
        return Inertia::render('Companies/Create');
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $request_validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $filename = $request_validated['logo'];
        $filePath = storage_path('app/tmp/' . $filename);

        $targetDirectory = storage_path('app/public/logos/companies');
        if (!File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        if (File::exists($filePath)) {
            File::move($filePath, $targetDirectory . '/' . $filename);
        }
        Log::info($request_validated['description']);
        $request_validated['description'] = Purifier::clean(
            $request_validated['description']
        );
        Log::info($request_validated['description']);
        $request_validated['owner'] = $user->id;

        Company::create($request_validated);

        return redirect()->route('welcome');
    }
    public function show(Company $company): \Inertia\Response
    {
        return Inertia::render('Companies/Show', [
            'company' => $company,
            'isOwner' => $company->owner == Auth::id(),
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $request_validated = $request->validated();
        $request_validated['description'] = Purifier::clean(
            $request_validated['description']
        );
        if ($request_validated['logo'] !== $company->logo) {
            $filename = $request_validated['logo'];
            $filePath = storage_path('app/tmp/' . $filename);
            $targetDirectory = storage_path('app/public/logos/companies');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }
            if (File::exists($filePath)) {
                File::move($filePath, $targetDirectory . '/' . $filename);
            }
            if (File::exists($targetDirectory . '/' . $company->logo)) {
                File::delete($targetDirectory . '/' . $company->logo);
            }
        }
        $company->update($request_validated);
    }

    public function destroy(Company $company)
    {
        //
    }
}
