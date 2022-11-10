<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContactRequest;
use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(CompanyRepository $company, Request $request)
    {
        // 
        // dd($request->sort_by);
        // $companies = [
        //     1 => ['name' => 'Company One', 'contacts' => 3],
        //     2 => ['name' => 'Company Two', 'contacts' => 5],
        // ];

        $user = auth()->user();
        $companies = $user->companies()->orderBy('name')->pluck('name', 'id');

        // Display Soft deleted models
        // Pagination, Sorts, Filters, and Search
        // DB::enableQueryLog();
        $contacts = $user->contacts()->allowedTrash()
            ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
            ->allowedFilters('company_id')
            ->allowedSearch('first_name', 'last_name', 'email')
            ->paginate(10);
        // dump(DB::getQueryLog());
        return view('contacts.index', compact('contacts', 'companies'));
    }

    public function create()
    {
        // dd(request()->method());
        // $companies = $this->company->pluck();
        $contact = new Contact();
        $companies = auth()->user()->companies()->orderBy('name')->pluck('name', 'id');
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function store(ContactRequest $request)
    {
        // Contact::create($request->all());
        $request->user()->contacts()->create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully!');
    }

    public function show(Contact $contact)
    {
        return view('contacts.show')->with('contact', $contact);
    }

    public function edit(Request $request, Contact $contact)
    {
        // $companies = $this->company->pluck();
        $companies = auth()->user()->companies()->orderBy('name')->pluck('name', 'id');
        return view('contacts.edit', compact('contact', 'companies'));
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been added updated successfully.');
    }

    public function destroy(Request $request, Contact $contact)
    {
        $contact->delete();
        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())
        ->with('message', 'Contact has been moved to trash.')
        ->with('undoRoute', $this->getUndoRoute('contacts.restore', $contact));
    }

    public function restore(Request $request, Contact $contact)
    {
        $contact->restore();
        return back()
        ->with('message', 'Contact has been restored from trash.')
        ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
    }

    public function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) :null;
    }

    public function forceDelete(Contact $contact)
    {
        $contact->forceDelete();
        return back()
            ->with('message', 'Contact has been removed permanently.');
    }
}
