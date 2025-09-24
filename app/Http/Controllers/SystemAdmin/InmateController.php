<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\Inmate;
use App\Models\Institution;
use App\Models\InmateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InmateController extends Controller
{
    public function index(Request $request){
        $query = Inmate::with('institution');
        $institutionId = $request->get('institution_id');
        $type = $request->get('type');
        $sort = $request->get('sort','name_asc');
        if($institutionId){ $query->where('institution_id',$institutionId); }
        if($type){ $query->where('type',$type); }
        match($sort){
            'name_desc' => $query->orderBy('first_name','desc'),
            'created_asc' => $query->orderBy('id','asc'),
            'created_desc' => $query->orderBy('id','desc'),
            default => $query->orderBy('first_name','asc'),
        };
        $inmates = $query->paginate(15)->appends($request->only('institution_id','type','sort'));
        $institutions = Institution::orderBy('name')->get(['id','name']);
        $types = Inmate::select('type')->whereNotNull('type')->where('type','!=','')->distinct()->orderBy('type')->pluck('type');
        return view('system_admin.inmates.index', compact('inmates','institutions','institutionId','types','type','sort'));
    }

    public function create(){
        $institutions = Institution::orderBy('name')->get();
        $inmateTypes = [
            'Child Resident' => 'child',
            'Elderly Resident' => 'elderly',
            'Mental Health Patient' => 'mental_health',
            'Rehabilitation Patient' => 'rehabilitation',
        ];
        return view('system_admin.inmates.create', compact('institutions','inmateTypes'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'registration_number' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'admission_date' => 'required|date',
            'notes' => 'nullable|string',
            'critical_alert' => 'nullable|string|max:1000',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'guardian_address' => 'nullable|string',
            'aadhaar_number' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:50',
            'intake_history' => 'nullable|string',
            'mobility_status' => 'nullable|string|max:255',
            'dietary_needs' => 'nullable|string|max:255',
            'emergency_contact_details' => 'nullable|string',
            'rehab_primary_issue' => 'nullable|string|max:255',
            'rehab_program_phase' => 'nullable|string|max:255',
            'rehab_goals' => 'nullable|string',
            'mh_diagnosis' => 'nullable|string|max:255',
            'mh_therapy_frequency' => 'nullable|string|max:255',
            'mh_current_meds' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'aadhaar_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'ration_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'panchayath_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'disability_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doctor_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'vincent_depaul_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_names.*' => 'nullable|string|max:255',
            'doc_files.*' => 'nullable|file|max:8192',
        ]);

        $fileMap = [
            'photo' => 'photo_path',
            'aadhaar_card' => 'aadhaar_card_path',
            'ration_card' => 'ration_card_path',
            'panchayath_letter' => 'panchayath_letter_path',
            'disability_card' => 'disability_card_path',
            'doctor_certificate' => 'doctor_certificate_path',
            'vincent_depaul_card' => 'vincent_depaul_card_path',
        ];
        // Defer file storage until inmate is created to place under structured dirs
        $pendingFiles = [];
        foreach ($fileMap as $input => $column) {
            if ($request->hasFile($input)) {
                $pendingFiles[$column] = $request->file($input);
                unset($data[$input]);
            }
        }

        $inmate = Inmate::create($data);

        // Store core files into final directories with unique names
        foreach ($pendingFiles as $column => $file) {
            $dir = $column === 'photo_path'
                ? \App\Support\StoragePath::inmatePhotoDir($inmate->id)
                : \App\Support\StoragePath::inmateDocDir($inmate->id);
            $name = \App\Support\StoragePath::uniqueName($file);
            $path = \Storage::putFileAs($dir, $file, $name);
            $inmate->{$column} = $path;
        }
        if (!empty($pendingFiles)) { $inmate->save(); }

        // optional initial location assignment
        if($request->filled('location_id')){
            $location = \App\Models\Location::where('id',$request->input('location_id'))
                ->where('institution_id', $inmate->institution_id)->first();
            if($location){
                \App\Models\LocationAssignment::create([
                    'inmate_id' => $inmate->id,
                    'location_id' => $location->id,
                    'start_date' => now(),
                    'end_date' => null,
                ]);
            }
        }

    if($inmate->type==='elderly'){
            if($request->filled('mobility_status') || $request->filled('dietary_needs') || $request->filled('emergency_contact_details')){
                $ecd = $request->filled('emergency_contact_details') ? json_decode($request->emergency_contact_details,true) : [];
                $inmate->geriatricCarePlan()->create([
                    'mobility_status'=>$request->mobility_status,
                    'dietary_needs'=>$request->dietary_needs,
                    'emergency_contact_details'=>$ecd ?? [],
                ]);
            }
        }
    if($inmate->type==='mental_health'){
            if($request->filled('mh_diagnosis') || $request->filled('mh_therapy_frequency') || $request->filled('mh_current_meds')){
                $inmate->mentalHealthPlan()->create([
                    'diagnosis'=>$request->mh_diagnosis,
                    'therapy_frequency'=>$request->mh_therapy_frequency,
                    'current_meds'=>$request->mh_current_meds,
                ]);
            }
        }
    if($inmate->type==='rehabilitation'){
            if($request->filled('rehab_primary_issue') || $request->filled('rehab_program_phase') || $request->filled('rehab_goals')){
                $inmate->rehabilitationPlan()->create([
                    'primary_issue'=>$request->rehab_primary_issue,
                    'program_phase'=>$request->rehab_program_phase,
                    'goals'=>$request->rehab_goals,
                ]);
            }
        }

    if ($request->filled('doc_names')) {
            foreach ($request->doc_names as $idx => $docName) {
                if ($docName && isset($request->doc_files[$idx])) {
            $file = $request->doc_files[$idx];
            $dir = \App\Support\StoragePath::inmateDocDir($inmate->id);
            $name = \App\Support\StoragePath::uniqueName($file);
            $path = \Storage::putFileAs($dir, $file, $name);
                    InmateDocument::create([
                        'inmate_id' => $inmate->id,
                        'document_name' => $docName,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('system_admin.inmates.index')->with('success', 'Inmate created successfully.');
    }

    public function edit(Inmate $inmate){
        $institutions = Institution::orderBy('name')->get(['id','name']);
        return view('system_admin.inmates.edit', compact('inmate','institutions'));
    }

    public function show(Inmate $inmate){
        $inmate->loadMissing(
            'geriatricCarePlan','mentalHealthPlan','rehabilitationPlan',
            'educationalRecords','documents','medications','labTests','therapySessionLogs','appointments','caseLogEntries','institution'
        );
        // If AJAX or explicit partial param, return tab partials
        $partial = request('partial');
        if (request()->ajax() && $partial) {
            return match($partial){
                'overview' => view('system_admin.inmates.tabs.overview', compact('inmate')),
                'medical' => view('system_admin.inmates.tabs.medical', compact('inmate')),
                'history' => view('system_admin.inmates.tabs.history', compact('inmate')),
                'documents' => view('system_admin.inmates.tabs.documents', compact('inmate')),
                'allocation' => view('system_admin.inmates.tabs.allocation', compact('inmate')),
                'settings' => view('system_admin.inmates.tabs.settings', compact('inmate')),
                default => view('system_admin.inmates.tabs.overview', compact('inmate')),
            };
        }
        return view('system_admin.inmates.show', compact('inmate'));
    }

    public function assignLocation(Request $request, Inmate $inmate)
    {
        $data = $request->validate([
            'location_id' => 'nullable|exists:locations,id'
        ]);
        // Create/validate new assignment if provided
        if(!empty($data['location_id'])){
            $location = \App\Models\Location::where('id',$data['location_id'])->where('institution_id',$inmate->institution_id)->firstOrFail();
            if($location->status === 'maintenance'){
                $msg = 'Cannot allocate to a location under maintenance.';
                return $request->wantsJson()
                    ? response()->json(['ok'=>false,'message'=>$msg], 422)
                    : back()->with('error',$msg);
            }
            $activeCount = \App\Models\LocationAssignment::where('location_id',$location->id)->whereNull('end_date')->count();
            if($location->type === 'bed' && $activeCount > 0){
                $msg = 'This bed is already occupied.';
                return $request->wantsJson()
                    ? response()->json(['ok'=>false,'message'=>$msg], 422)
                    : back()->with('error',$msg);
            }
            // Close current assignment if exists
            $current = \App\Models\LocationAssignment::where('inmate_id',$inmate->id)->whereNull('end_date')->first();
            if($current){ $current->end_date = now(); $current->save(); }
            // Create new assignment
            \App\Models\LocationAssignment::create([
                'inmate_id' => $inmate->id,
                'location_id' => $location->id,
                'start_date' => now(),
                'end_date' => null,
            ]);
        } else {
            // Clearing assignment
            $current = \App\Models\LocationAssignment::where('inmate_id',$inmate->id)->whereNull('end_date')->first();
            if($current){ $current->end_date = now(); $current->save(); }
        }
        if ($request->wantsJson()) { return response()->json(['ok'=>true,'message'=>'Location updated.']); }
        return back()->with('success','Location updated.');
    }

    /**
     * Upload/replace a single core file (photo, aadhaar_card, etc.) for an inmate.
     * Accepts field and file; returns JSON on success.
     */
    public function uploadFile(Request $request, Inmate $inmate)
    {
        $field = $request->input('field');
        $rules = [
            'field' => 'required|in:photo,aadhaar_card,ration_card,panchayath_letter,disability_card,doctor_certificate,vincent_depaul_card',
        ];
        // Conditional file validation based on field
        if ($field === 'photo') {
            $rules['file'] = 'required|image|max:2048';
        } else {
            $rules['file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:4096';
        }
        $data = $request->validate($rules);

        $map = [
            'photo' => 'photo_path',
            'aadhaar_card' => 'aadhaar_card_path',
            'ration_card' => 'ration_card_path',
            'panchayath_letter' => 'panchayath_letter_path',
            'disability_card' => 'disability_card_path',
            'doctor_certificate' => 'doctor_certificate_path',
            'vincent_depaul_card' => 'vincent_depaul_card_path',
        ];
        $column = $map[$field];

        // Remove old file if present
        if (!empty($inmate->{$column})) {
            Storage::delete($inmate->{$column});
        }
        $file = $request->file('file');
        $dir = $field === 'photo'
            ? \App\Support\StoragePath::inmatePhotoDir($inmate->id)
            : \App\Support\StoragePath::inmateDocDir($inmate->id);
        $name = \App\Support\StoragePath::uniqueName($file);
        $path = \Storage::putFileAs($dir, $file, $name);
        $inmate->update([$n = $column => $path]);
        $disk = \Storage::disk(config('filesystems.default'));
        try {
            $url = config('filesystems.default') === 's3'
                ? $disk->temporaryUrl($path, now()->addMinutes(5))
                : $disk->url($path);
        } catch (\Throwable $e) {
            $url = null;
        }
        return response()->json([
            'ok' => true,
            'field' => $field,
            'column' => $column,
            'url' => $url,
            'path' => $path,
        ]);
    }

    /**
     * Store a new extra inmate document (name + file) via AJAX.
     */
    public function storeDocument(Request $request, Inmate $inmate)
    {
        $data = $request->validate([
            'document_name' => 'required|string|max:255',
            'doc_file' => 'required|file|max:8192',
        ]);
    $file = $request->file('doc_file');
    $dir = \App\Support\StoragePath::inmateDocDir($inmate->id);
    $name = \App\Support\StoragePath::uniqueName($file);
    $path = \Storage::putFileAs($dir, $file, $name);
        $doc = InmateDocument::create([
            'inmate_id' => $inmate->id,
            'document_name' => $data['document_name'],
            'file_path' => $path,
        ]);
        $disk = \Storage::disk(config('filesystems.default'));
        try {
            $url = config('filesystems.default') === 's3'
                ? $disk->temporaryUrl($doc->file_path, now()->addMinutes(5))
                : $disk->url($doc->file_path);
        } catch (\Throwable $e) {
            $url = null;
        }
        return response()->json([
            'ok' => true,
            'document' => [
                'id' => $doc->id,
                'name' => $doc->document_name,
                'url' => $url,
            ]
        ]);
    }

    public function update(Request $request, Inmate $inmate){
        $data = $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'registration_number' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'admission_date' => 'required|date',
            'notes' => 'nullable|string',
            'critical_alert' => 'nullable|string|max:1000',
            'type' => 'nullable|in:child,elderly,mental_health,rehabilitation',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'guardian_address' => 'nullable|string',
            'aadhaar_number' => 'nullable|string|max:100',
            'intake_history' => 'nullable|string',
            'mobility_status' => 'nullable|string|max:255',
            'dietary_needs' => 'nullable|string|max:255',
            'emergency_contact_details' => 'nullable|string',
            'rehab_primary_issue' => 'nullable|string|max:255',
            'rehab_program_phase' => 'nullable|string|max:255',
            'rehab_goals' => 'nullable|string',
            'mh_diagnosis' => 'nullable|string|max:255',
            'mh_therapy_frequency' => 'nullable|string|max:255',
            'mh_current_meds' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'aadhaar_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'ration_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'panchayath_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'disability_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doctor_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'vincent_depaul_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'doc_names.*' => 'nullable|string|max:255',
            'doc_files.*' => 'nullable|file|max:8192',
        ]);

        $fileMap = [
            'photo' => 'photo_path',
            'aadhaar_card' => 'aadhaar_card_path',
            'ration_card' => 'ration_card_path',
            'panchayath_letter' => 'panchayath_letter_path',
            'disability_card' => 'disability_card_path',
            'doctor_certificate' => 'doctor_certificate_path',
            'vincent_depaul_card' => 'vincent_depaul_card_path',
        ];
        foreach ($fileMap as $input => $column) {
            if ($request->hasFile($input)) {
                if ($inmate->{$column}) {
                    Storage::delete($inmate->{$column});
                }
                $file = $request->file($input);
                $dir = $input === 'photo'
                    ? \App\Support\StoragePath::inmatePhotoDir($inmate->id)
                    : \App\Support\StoragePath::inmateDocDir($inmate->id);
                $name = \App\Support\StoragePath::uniqueName($file);
                $data[$column] = \Storage::putFileAs($dir, $file, $name);
            }
        }

        $oldInstitutionId = $inmate->institution_id;
        $inmate->update($data);

        // If institution changed, close any active location assignment tied to old institution
        if(isset($data['institution_id']) && (int)$data['institution_id'] !== (int)$oldInstitutionId){
            $current = \App\Models\LocationAssignment::where('inmate_id',$inmate->id)->whereNull('end_date')->first();
            if($current){ $current->end_date = now(); $current->save(); }
        }

        switch($inmate->type){
            case 'elderly':
                if($request->filled('mobility_status') || $request->filled('dietary_needs') || $request->filled('emergency_contact_details')){
                    $ecd = [];
                    if($request->filled('emergency_contact_details')){
                        $decoded = json_decode($request->emergency_contact_details, true);
                        if(is_array($decoded)) $ecd = $decoded;
                    }
                    $plan = $inmate->geriatricCarePlan; 
                    $payload = [
                        'mobility_status' => $request->mobility_status,
                        'dietary_needs' => $request->dietary_needs,
                        'emergency_contact_details' => $ecd,
                    ];
                    $plan ? $plan->update($payload) : $inmate->geriatricCarePlan()->create($payload);
                }
                break;
            case 'mental_health':
                if($request->filled('mh_diagnosis') || $request->filled('mh_therapy_frequency') || $request->filled('mh_current_meds')){
                    $payload = [
                        'diagnosis' => $request->mh_diagnosis,
                        'therapy_frequency' => $request->mh_therapy_frequency,
                        'current_meds' => $request->mh_current_meds,
                    ];
                    $plan = $inmate->mentalHealthPlan;
                    $plan ? $plan->update($payload) : $inmate->mentalHealthPlan()->create($payload);
                }
                break;
            case 'rehabilitation':
                if($request->filled('rehab_primary_issue') || $request->filled('rehab_program_phase') || $request->filled('rehab_goals')){
                    $payload = [
                        'primary_issue' => $request->rehab_primary_issue,
                        'program_phase' => $request->rehab_program_phase,
                        'goals' => $request->rehab_goals,
                    ];
                    $plan = $inmate->rehabilitationPlan;
                    $plan ? $plan->update($payload) : $inmate->rehabilitationPlan()->create($payload);
                }
                break;
            case 'child':
                break;
        }

    if ($request->filled('doc_names')) {
            foreach ($request->doc_names as $idx => $docName) {
                if ($docName && isset($request->doc_files[$idx])) {
            $file = $request->doc_files[$idx];
            $dir = \App\Support\StoragePath::inmateDocDir($inmate->id);
            $name = \App\Support\StoragePath::uniqueName($file);
            $path = \Storage::putFileAs($dir, $file, $name);
                    InmateDocument::create([
                        'inmate_id' => $inmate->id,
                        'document_name' => $docName,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['ok'=>true,'message'=>'Inmate updated successfully.']);
        }
        return redirect()->route('system_admin.inmates.edit',$inmate)->with('success', 'Inmate updated successfully.');
    }

    public function destroy(Inmate $inmate){
        $inmate->delete();
        return redirect()->route('system_admin.inmates.index')->with('success', 'Inmate deleted successfully.');
    }

    /**
     * Toggle guardian share flag for an inmate document (System Admin scope).
     */
    public function toggleDocumentShare(Request $request, Inmate $inmate, InmateDocument $document)
    {
        abort_unless($document->inmate_id === $inmate->id, 404);
        $document->is_sharable_with_guardian = !$document->is_sharable_with_guardian;
        $document->save();
        return $request->wantsJson()
            ? response()->json(['ok'=>true,'shared'=>$document->is_sharable_with_guardian])
            : back()->with('success', 'Share setting updated.');
    }
}
