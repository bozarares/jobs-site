<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $companies = $user->companies()->get();
        return Inertia::render('Companies/Index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Companies/Create', ['csrf' => csrf_token()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $request_validated = $request->validated();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $filename = $request_validated['logo'];
        $filePath = storage_path('app/tmp/' . $filename);

        // Verifica și creează directorul dacă nu există
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

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return Inertia::render('Companies/Show', [
            'company' => $company,
            'isOwner' => $company->owner == Auth::id(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
