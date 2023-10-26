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
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(): \Inertia\Response
    {
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

        $user = Auth::user();

        $filename =
            $request_validated['logo'] .
            '.' .
            $request_validated['logo_extension'];
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

    public function updateLogo(Request $request, Company $company)
    {
        $request_validated = $request->validate([
            'logo' => ['required', 'string', 'max:255'],
            'extension' => [
                'required',
                'string',
                'in:png,jpg,jpeg,JPEG,PNG,JPG',
            ],
        ]);

        $filename =
            $request_validated['logo'] . '.' . $request_validated['extension'];
        $filePath = storage_path('app/tmp/' . $filename);
        $targetDirectory = storage_path('app/public/logos/companies');
        if (!File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        if (File::exists($filePath)) {
            File::move($filePath, $targetDirectory . '/' . $filename);
        }

        $current_logo_path =
            $targetDirectory .
            '/' .
            $company->logo .
            '.' .
            $company->logo_extension;
        if (File::exists($current_logo_path)) {
            File::delete($current_logo_path);
        }

        $company->logo = $request_validated['logo'];
        $company->logo_extension = $request_validated['extension'];

        $company->save();
    }

    public function updateDescription(Request $request, Company $company)
    {
        $request_validated = $request->validate([
            'description' => ['required', 'string', 'max:2048'],
        ]);
        $request_validated['description'] = Purifier::clean(
            $request_validated['description']
        );
        $company->description = $request_validated['description'];
        $company->save();
    }

    public function updateContact(Request $request, Company $company)
    {
        $request_validated = $request->validate([
            'country' => 'string|max:255',
            'state' => 'string|max:255',
            'town' => 'string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:20',
            'email' => 'string|email|max:255',
        ]);

        $company->country = $request_validated['country'];
        $company->state = $request_validated['state'];
        $company->town = $request_validated['town'];
        $company->address = $request_validated['address'];
        $company->phone_number = $request_validated['phone_number'];
        $company->email = $request_validated['email'];

        $company->save();
    }

    public function destroy(Company $company)
    {
        //
    }
}
