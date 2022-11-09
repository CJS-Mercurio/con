<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{

    public function __construct(protected CompanyRepository $company)
    {
        
    }

    public function index(CompanyRepository $company, Request $request)
    {

        // dd($request->sort_by);
        // $companies = [
        //     1 => ['name' => 'Company One', 'contacts' => 3],
        //     2 => ['name' => 'Company Two', 'contacts' => 5],
        // ];
        $companies = $company->pluck();

        // Display Soft deleted models
        // Pagination, Sorts, Filters, and Search
        // DB::enableQueryLog();
        $contacts = Contact::allowedTrash()
            ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
            ->allowedFilters('company_id')
            ->allowedSearch('first_name', 'last_name', 'email')
            ->paginate(10);
        // dump(DB::getQueryLog());
        return view('contacts.index', compact('contacts', 'companies'));

        // // Creating Pagination Manually
        // $contactsCollection = Contact::latest()->get();
        // $perPage = 10;
        // $currentPage = request()->query('page', 1);
        // $items = $contactsCollection->slice(($currentPage * $perPage) - $perPage, $perPage);
        // $total = $contactsCollection->count();
        // $contacts = new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
        //     'path' => request()->url(),
        //     'query' => request()->query()
        // ]);
        // $contacts = $this->getContacts();
    }

    public function create()
    {
        // dd(request()->method());
        $companies = $this->company->pluck();
        $contact = new Contact();
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function store(ContactRequest $request)
    {
        Contact::create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully!');
    }

    public function show(Contact $contact)
    {
        return view('contacts.show')->with('contact', $contact);
    }

    public function edit(Request $request, Contact $contact)
    {
        $companies = $this->company->pluck();
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
