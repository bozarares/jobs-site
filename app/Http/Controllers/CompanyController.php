<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyContact;
use App\Http\Requests\Company\UpdateCompanyDescription;
use App\Http\Requests\Company\UpdateCompanyLogo;
use App\Http\Resources\CompanyMinimalResource;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index(): \Inertia\Response
    {
        $user = Auth::user();
        $companies = CompanyMinimalResource::collection(
            $user->companies()->get()
        );
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
        $request['owner'] = Auth::user()->id;
        Company::create($request);
        return redirect()->route('welcome');
    }
    public function show(Company $company): \Inertia\Response
    {
        return Inertia::render('Companies/Show', [
            'company' => CompanyResource::make($company),
            'isOwner' => $company->owner == Auth::id(),
        ]);
    }

    public function updateLogo(UpdateCompanyLogo $request, Company $company)
    {
        $company->fill($request->validated())->save();
    }

    public function updateDescription(
        UpdateCompanyDescription $request,
        Company $company
    ) {
        $company->description = $request->description; // Purified in UpdateCompanyDescription
        $company->save();
    }

    public function updateContact(
        UpdateCompanyContact $request,
        Company $company
    ) {
        $company->fill($request->validated());
        $company->save();
    }
}
