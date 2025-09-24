<x-app-layout>
  <x-slot name="header"><h2 class="h5 mb-0">Edit Inmate</h2></x-slot>
  <div class="card"><div class="card-body">
    @if($errors->any())
      <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Please fix the following:</div>
        <ul class="mb-0 small">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <form action="{{ route('system_admin.inmates.update',$inmate) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">Institution</label>
          <select name="institution_id" class="form-select" required>
            @foreach($institutions as $inst)
              <option value="{{ $inst->id }}" @selected(old('institution_id',$inmate->institution_id)==$inst->id)>{{ $inst->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Type</label>
          <select name="type" id="inmate_type" class="form-select">
            <option value="">Select...</option>
            @foreach(['child'=>'Child Resident','elderly'=>'Elderly Resident','mental_health'=>'Mental Health Patient','rehabilitation'=>'Rehabilitation Patient'] as $v=>$l)
              <option value="{{ $v }}" @selected(old('type',$inmate->type)===$v)>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Registration #</label>
          <input name="registration_number" class="form-control" value="{{ old('registration_number',$inmate->registration_number) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Aadhaar Number</label>
          <input name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number',$inmate->aadhaar_number) }}">
        </div>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-4"><label class="form-label">First Name<span class="text-danger">*</span></label><input name="first_name" class="form-control" value="{{ old('first_name',$inmate->first_name) }}" required></div>
        <div class="col-md-4"><label class="form-label">Last Name</label><input name="last_name" class="form-control" value="{{ old('last_name',$inmate->last_name) }}"></div>
        <div class="col-md-4"><label class="form-label">Gender<span class="text-danger">*</span></label><select name="gender" class="form-select" required>@foreach(['Male','Female','Other'] as $g)<option value="{{ $g }}" @selected(old('gender',$inmate->gender)===$g)>{{ $g }}</option>@endforeach</select></div>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-4"><label class="form-label">Date of Birth<span class="text-danger">*</span></label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth',$inmate->date_of_birth?->format('Y-m-d')) }}" required></div>
        <div class="col-md-4"><label class="form-label">Admission Date<span class="text-danger">*</span></label><input type="date" name="admission_date" class="form-control" value="{{ old('admission_date',$inmate->admission_date?->format('Y-m-d')) }}" required></div>
        <div class="col-md-4">
          <label class="form-label">Photo</label>
          @if($inmate->photo_path)
            <div class="mb-1"><img src="{{ $inmate->avatar_url }}" alt="Photo" class="img-thumbnail" style="max-height:100px"></div>
          @endif
          <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">Aadhaar Card</label>
          @if($inmate->aadhaar_card_path)
            @php $u = Storage::disk(config('filesystems.default')); try { $a1 = config('filesystems.default')==='s3' ? $u->temporaryUrl($inmate->aadhaar_card_path, now()->addMinutes(5)) : $u->url($inmate->aadhaar_card_path); } catch (\Throwable $e) { $a1 = null; } @endphp
            @if($a1)<div class="mb-1"><a href="{{ $a1 }}" target="_blank">Current</a></div>@endif
          @endif
          <input type="file" name="aadhaar_card" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Ration Card</label>
          @if($inmate->ration_card_path)
            @php $u = Storage::disk(config('filesystems.default')); try { $a2 = config('filesystems.default')==='s3' ? $u->temporaryUrl($inmate->ration_card_path, now()->addMinutes(5)) : $u->url($inmate->ration_card_path); } catch (\Throwable $e) { $a2 = null; } @endphp
            @if($a2)<div class="mb-1"><a href="{{ $a2 }}" target="_blank">Current</a></div>@endif
          @endif
          <input type="file" name="ration_card" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Panchayath Letter</label>
          @if($inmate->panchayath_letter_path)
            @php $u = Storage::disk(config('filesystems.default')); try { $a3 = config('filesystems.default')==='s3' ? $u->temporaryUrl($inmate->panchayath_letter_path, now()->addMinutes(5)) : $u->url($inmate->panchayath_letter_path); } catch (\Throwable $e) { $a3 = null; } @endphp
            @if($a3)<div class="mb-1"><a href="{{ $a3 }}" target="_blank">Current</a></div>@endif
          @endif
          <input type="file" name="panchayath_letter" class="form-control">
        </div>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">Disability Card</label>
          @if($inmate->disability_card_path)
            @php $d = Storage::disk(config('filesystems.default')); try { $u = config('filesystems.default')==='s3' ? $d->temporaryUrl($inmate->disability_card_path, now()->addMinutes(5)) : $d->url($inmate->disability_card_path); } catch (\Throwable $e) { $u = null; } @endphp
            @if($u)<div class="mb-1"><a href="{{ $u }}" target="_blank">Current</a></div>@endif
          @endif
          <input type="file" name="disability_card" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Doctor Certificate</label>
          @if($inmate->doctor_certificate_path)
            @php $d = Storage::disk(config('filesystems.default')); try { $u = config('filesystems.default')==='s3' ? $d->temporaryUrl($inmate->doctor_certificate_path, now()->addMinutes(5)) : $d->url($inmate->doctor_certificate_path); } catch (\Throwable $e) { $u = null; } @endphp
            @if($u)<div class="mb-1"><a href="{{ $u }}" target="_blank">Current</a></div>@endif
          @endif
          <input type="file" name="doctor_certificate" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Vincent Depaul Card</label>
          @if($inmate->vincent_depaul_card_path)
            @php $d = Storage::disk(config('filesystems.default')); try { $u = config('filesystems.default')==='s3' ? $d->temporaryUrl($inmate->vincent_depaul_card_path, now()->addMinutes(5)) : $d->url($inmate->vincent_depaul_card_path); } catch (\Throwable $e) { $u = null; } @endphp
            @if($u)<div class="mb-1"><a href="{{ $u }}" target="_blank">Current</a></div>@endif
          @endif
          <input type="file" name="vincent_depaul_card" class="form-control">
        </div>
      </div>
      <hr class="my-3">
      <h6>Guardian</h6>
      <div class="row g-3 mb-3">
        <div class="col-md-3"><label class="form-label">Relation</label><input name="guardian_relation" class="form-control" value="{{ old('guardian_relation',$inmate->guardian_relation) }}"></div>
        <div class="col-md-3"><label class="form-label">First Name</label><input name="guardian_first_name" class="form-control" value="{{ old('guardian_first_name',$inmate->guardian_first_name) }}"></div>
        <div class="col-md-3"><label class="form-label">Last Name</label><input name="guardian_last_name" class="form-control" value="{{ old('guardian_last_name',$inmate->guardian_last_name) }}"></div>
        <div class="col-md-3"><label class="form-label">Phone</label><input name="guardian_phone" class="form-control" value="{{ old('guardian_phone',$inmate->guardian_phone) }}"></div>
        <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email',$inmate->guardian_email) }}"></div>
        <div class="col-md-8"><label class="form-label">Address</label><textarea name="guardian_address" rows="2" class="form-control">{{ old('guardian_address',$inmate->guardian_address) }}</textarea></div>
      </div>
      <hr class="my-3">
      <h6>Notes</h6>
      <div class="mb-3"><textarea name="notes" rows="4" class="form-control">{{ old('notes',$inmate->notes) }}</textarea></div>

      <hr class="my-3">
      <h6>Room Assignment</h6>
      <div class="card mb-3"><div class="card-body">
        <div class="mb-2">
          <div class="small text-muted">Current</div>
          <div class="fw-semibold">{{ optional($inmate->currentLocation?->location)->name ?? 'Not assigned' }}</div>
        </div>
        <div class="row g-2 align-items-end">
          <div class="col-md-8">
            <div class="input-group input-group-sm">
              <input type="text" id="editRoomSearch" class="form-control" placeholder="Search rooms...">
              <button type="button" id="editRoomReload" class="btn btn-outline-secondary"><span class="bi bi-arrow-repeat"></span></button>
            </div>
            <div class="form-check form-switch mt-2 small">
              <input class="form-check-input" type="checkbox" id="editRoomShowOcc">
              <label class="form-check-label" for="editRoomShowOcc">Show occupied</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="small text-muted">Selected</div>
            <div id="editRoomSelected" class="fw-semibold">None</div>
          </div>
        </div>
        <div class="list-group small mt-2" id="editRoomsList" style="max-height:240px; overflow:auto"></div>
        <div class="d-flex justify-content-between mt-2">
          <input type="hidden" id="editRoomLocationId">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="editRoomClear">Clear</button>
          <button type="button" class="btn btn-primary btn-sm" id="editRoomUpdate">Update</button>
        </div>
      </div></div>

      <hr class="my-3">
      <h6>Extra Documents</h6>
      <div id="extra-documents-wrapper"></div>
      <button type="button" id="add-document-btn" class="btn btn-sm btn-outline-primary mb-3">Add Document</button>
      <small class="text-muted d-block mb-3">Upload new supporting documents.</small>
      <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('system_admin.inmates.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div></div>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded',()=>{
    // documents
    const btn=document.getElementById('add-document-btn'); const wrapper=document.getElementById('extra-documents-wrapper'); let i=0;
    btn?.addEventListener('click',()=>{ const row=document.createElement('div'); row.className='row g-2 align-items-end mb-2'; row.innerHTML=`<div class="col-md-5"><label class="form-label small">Document Name</label><input type="text" name="doc_names[${i}]" class="form-control"></div><div class="col-md-5"><label class="form-label small">File</label><input type="file" name="doc_files[${i}]" class="form-control"></div><div class="col-md-2 d-grid"><button type="button" class="btn btn-outline-danger btn-sm remove-doc">Remove</button></div>`; wrapper.appendChild(row); i++; });
    wrapper?.addEventListener('click',e=>{ if(e.target.classList.contains('remove-doc')) e.target.closest('.row').remove(); });

    // room picker
  const list=document.getElementById('editRoomsList'); const q=document.getElementById('editRoomSearch'); const reload=document.getElementById('editRoomReload'); const showOcc=document.getElementById('editRoomShowOcc'); const out=document.getElementById('editRoomSelected'); const hid=document.getElementById('editRoomLocationId'); const btnUpdate=document.getElementById('editRoomUpdate'); let rooms=[];
    async function load(){ const inst={{ (int)$inmate->institution_id }}; const res=await fetch(`{{ url('system-admin/allocation/api/institutions') }}/${inst}/locations?show_occupied=${showOcc.checked?1:0}`,{headers:{'Accept':'application/json'}}); const data=await res.json(); rooms=data.locations||[]; render(); }
  function render(){ const term=(q.value||'').toLowerCase(); list.innerHTML=''; const items=rooms.filter(r=> (r.name||'').toLowerCase().includes(term)); if(items.length===0){ list.innerHTML='<div class="text-muted text-center py-3">No rooms</div>'; return; } items.forEach(r=>{ const isMaint = (r.status === 'maintenance'); const occupied = !!r.occupied; const label = isMaint ? 'Maintenance' : (occupied ? 'Occupied' : 'Available'); const badgeClass = isMaint ? 'bg-warning text-dark' : (occupied ? 'bg-secondary' : 'bg-success'); const btn=document.createElement('button'); btn.type='button'; btn.className='list-group-item list-group-item-action d-flex justify-content-between align-items-center'; btn.innerHTML=`<span><span class=\"bi bi-door-closed me-2\"></span>${r.name}${r.occupant ? ' <span class=\"badge bg-secondary ms-2\">'+r.occupant+'</span>' : ''}</span><span class=\"badge ${badgeClass}\">${label}</span>`; btn.disabled=(isMaint || (occupied && !showOcc.checked)); btn.addEventListener('click',()=>{ hid.value=r.id; out.textContent=r.name; document.querySelectorAll('#editRoomsList .list-group-item').forEach(x=>x.classList.remove('active')); btn.classList.add('active'); }); list.appendChild(btn); }); }
    q?.addEventListener('input', render); reload?.addEventListener('click', load); showOcc?.addEventListener('change', load); document.getElementById('editRoomClear')?.addEventListener('click', ()=>{ hid.value=''; out.textContent='None'; document.querySelectorAll('#editRoomsList .list-group-item').forEach(x=>x.classList.remove('active')); }); load();

    // AJAX update for room assignment (avoid nested forms)
    btnUpdate?.addEventListener('click', async ()=>{
      if(!hid.value){ const warn=document.createElement('div'); warn.className='alert alert-warning small mt-2'; warn.textContent='Please select a room to assign.'; list.parentElement.appendChild(warn); setTimeout(()=>warn.remove(),2500); return; }
      try{
        const res = await fetch(`{{ route('system_admin.inmates.assign-location',$inmate) }}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
          body: (()=>{ const fd=new FormData(); fd.append('location_id', hid.value); return fd; })()
        });
        if(!res.ok){ const d = await res.json().catch(()=>({message:'Update failed'})); throw new Error(d.message || 'Update failed'); }
        const ok=document.createElement('div'); ok.className='alert alert-success small mt-2'; ok.textContent='Location updated.'; list.parentElement.appendChild(ok); setTimeout(()=>ok.remove(),2000);
      }catch(err){ const er=document.createElement('div'); er.className='alert alert-danger small mt-2'; er.textContent=err.message || 'Update failed'; list.parentElement.appendChild(er); setTimeout(()=>er.remove(),3000); }
    });
  });
  </script>
  @endpush
</x-app-layout>
