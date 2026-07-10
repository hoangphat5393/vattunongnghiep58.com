<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Contact\StoreContact;
use App\Http\Requests\Admin\Contact\UpdateContact;
use App\Models\Backend\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Contact::filter($request)->orderByDesc('id')->paginate(20)->appends($request->all());

        $count_item = $data->total();

        return view('backend.contact.index')->with(['data' => $data, 'total_item' => $count_item]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.contact.single', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContact $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact, $id)
    {
        $contact = $contact->find($id);

        return view('backend.contact.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact, $id)
    {
        $contact = $contact->findorfail($id);
        if ($contact) {
            return view('backend.contact.single', compact('contact'));
        } else {
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContact $request, Contact $contact) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact, $id)
    {
        $contact->find($id)->delete();

        return redirect()->route('admin.contact.index')->with('success', 'Contact deleted successfully.');
    }
}
