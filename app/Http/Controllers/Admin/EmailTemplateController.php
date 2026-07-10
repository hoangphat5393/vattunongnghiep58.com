<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailTemplate\StoreEmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\UpdateEmailTemplate;
use App\Models\Backend\EmailTemplate;
use App\Support\EmailTemplateCodes;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $data = EmailTemplate::filter($request)->orderByDesc('sort')->paginate(20)->appends($request->all());

        $total_item = $data->count();

        return view('backend.email-template.index', compact('data', 'total_item'));
    }

    public function create()
    {
        return view('backend.email-template.single');
    }

    public function store(StoreEmailTemplate $request)
    {
        $data = $this->templatePayload($request);

        $shortcode = EmailTemplate::create($data);
        $insert_id = $shortcode->id;

        $shortcode->update(['sort' => $insert_id]);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = 'Email template has been created successfully';
            $url = route('admin.email-template.edit', [$insert_id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.email-template.index'));
        }
    }

    public function show(EmailTemplate $emailTemplate, $id)
    {
        $emailTemplate = EmailTemplate::find($id);

        return view('backend.email-template.show', compact('emailTemplate'));
    }

    public function edit(EmailTemplate $emailTemplate, $id)
    {
        $emailTemplate = EmailTemplate::findorfail($id);

        if ($emailTemplate) {
            return view('backend.email-template.single', compact('emailTemplate'));
        } else {
            return view('404');
        }
    }

    public function update(UpdateEmailTemplate $request, EmailTemplate $emailTemplate)
    {
        $data = $this->templatePayload($request);

        $emailTemplate = EmailTemplate::findOrFail($request->id);
        $emailTemplate->update($data);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = 'Email template has been updated successfully';
            $url = route('admin.email-template.edit', [$request->id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.email-template.index'));
        }
    }

    public function destroy(EmailTemplate $emailTemplate, $id)
    {
        $emailTemplate->find($id)->delete();

        return redirect()->route('admin.email-template.index')->with('success', 'Email template deleted successfully.');
    }

    /**
     * @return array{name: string, code: string, text: string, status: int}
     */
    private function templatePayload(StoreEmailTemplate|UpdateEmailTemplate $request): array
    {
        $validated = $request->validated();

        return [
            'name' => (string) ($validated['name'] ?? ''),
            'code' => EmailTemplateCodes::normalize((string) ($validated['code'] ?? '')),
            'text' => (string) ($validated['text'] ?? ''),
            'status' => (int) ($validated['status'] ?? 1),
        ];
    }
}
