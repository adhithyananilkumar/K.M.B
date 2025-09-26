<x-app-layout>
	<x-slot name="header">
		<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
			<h2 class="h5 mb-0">@lang('admission.admission_details')</h2>
			<div class="small text-muted">@lang('admission.save')</div>
		</div>
	</x-slot>
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
		<form method="POST" action="{{ route('system_admin.inmates.store') }}" enctype="multipart/form-data" novalidate class="admission-form">@csrf
			<div class="row g-3 mb-2">
				<div class="col-md-3"><label class="form-label">@lang('admission.admission_number')</label><input class="form-control form-control-sm" value="(auto)" readonly aria-readonly="true"></div>
				<div class="col-md-3"><label class="form-label">@lang('admission.admission_date') <span class="text-danger">*</span></label><input type="date" name="admission_date" class="form-control form-control-sm" required value="{{ old('admission_date') }}"></div>
				<div class="col-md-3"><label class="form-label">@lang('admission.institution') <span class="text-danger">*</span></label><select name="institution_id" class="form-select form-select-sm" required aria-required="true"><option value="" disabled {{ old('institution_id')?'' :'selected' }}>Select...</option>@foreach($institutions as $inst)<option value="{{ $inst->id }}" @selected(old('institution_id')==$inst->id)>{{ $inst->name }}</option>@endforeach</select></div>
				<div class="col-md-3"><label class="form-label">@lang('admission.enrolled_by')</label><select name="admitted_by" class="form-select form-select-sm"><option value="">—</option>@foreach(($staff ?? []) as $s)<option value="{{ $s->id }}" @selected(old('admitted_by', auth()->id())==$s->id)>{{ $s->name }}</option>@endforeach</select></div>
			</div>
			<div class="card border-0 shadow-sm mt-3">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.personal_info')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="row g-3"><div class="col-md-4"><label class="form-label">@lang('admission.registration_number')</label><input name="registration_number" class="form-control" value="{{ old('registration_number') }}"></div><div class="col-md-4"><label class="form-label">@lang('admission.first_name') <span class="text-danger">*</span></label><input name="first_name" class="form-control" required value="{{ old('first_name') }}"></div><div class="col-md-4"><label class="form-label">@lang('admission.last_name')</label><input name="last_name" class="form-control" value="{{ old('last_name') }}"></div></div>
			<div class="row g-3 mt-1"><div class="col-md-3"><label class="form-label">@lang('admission.dob') <span class="text-danger">*</span></label><input type="date" name="date_of_birth" id="dob" class="form-control" required value="{{ old('date_of_birth') }}"><div class="form-text">@lang('admission.help_age_auto')</div></div><div class="col-md-2"><label class="form-label">@lang('admission.age')</label><input type="number" name="age" id="age" class="form-control" value="{{ old('age') }}" readonly></div><div class="col-md-3"><label class="form-label">@lang('admission.gender') <span class="text-danger">*</span></label><select name="gender" class="form-select" required><option value="" disabled selected>Select...</option>@foreach(['Male','Female','Other'] as $g)<option value="{{ $g }}" @selected(old('gender')===$g)>{{ $g }}</option>@endforeach</select></div><div class="col-md-2"><label class="form-label">@lang('admission.height')</label><input type="number" step="0.01" name="height" class="form-control" value="{{ old('height') }}"></div><div class="col-md-2"><label class="form-label">@lang('admission.weight')</label><input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight') }}"></div></div>
			<div class="row g-3 mt-1">
				<div class="col-md-3"><label class="form-label">@lang('admission.marital_status')</label>
					<select name="marital_status" id="marital_status" class="form-select">
						<option value="">—</option>
						@foreach(['Single','Married','Separated','Divorced','Widowed'] as $ms)
							<option value="{{ $ms }}" @selected(old('marital_status')===$ms)>{{ $ms }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3"><label class="form-label">@lang('admission.blood_group')</label>
					<select name="blood_group" class="form-select">
						<option value="">—</option>
						@foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
							<option value="{{ $bg }}" @selected(old('blood_group')===$bg)>{{ $bg }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6"><label class="form-label">@lang('admission.identification_marks')</label><input name="identification_marks" class="form-control" value="{{ old('identification_marks') }}"></div>
			</div>
			<div class="row g-3 mt-1">
				<div class="col-md-4"><label class="form-label">@lang('admission.religion')</label><input name="religion" class="form-control" value="{{ old('religion') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.caste')</label><input name="caste" class="form-control" value="{{ old('caste') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.nationality')</label><input name="nationality" class="form-control" value="{{ old('nationality') }}"></div>
			</div>
			<div class="row g-3 mt-1">
				<div class="col-md-4"><label class="form-label">Aadhaar Number</label><input name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number') }}"></div>
			</div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.address')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="row g-3">
				<div class="col-md-6"><label class="form-label">@lang('admission.address_line1')</label><input name="address[line1]" class="form-control" value="{{ old('address.line1') }}"></div>
				<div class="col-md-6"><label class="form-label">@lang('admission.address_line2')</label><input name="address[line2]" class="form-control" value="{{ old('address.line2') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.city')</label><input name="address[city]" class="form-control" value="{{ old('address.city') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.state')</label><input name="address[state]" class="form-control" value="{{ old('address.state') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.pincode')</label><input name="address[pincode]" class="form-control" value="{{ old('address.pincode') }}"></div>
	            </div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.documents')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="row g-3">
				<div class="col-md-4">
					<label class="form-label">@lang('admission.photo')</label>
					<input name="photo" type="file" accept="image/*" class="form-control">
				</div>
				<div class="col-md-4">
					<label class="form-label">@lang('admission.aadhaar')</label>
					<input name="aadhaar_card" type="file" class="form-control">
				</div>
				<div class="col-md-4">
					<label class="form-label">@lang('admission.ration_card')</label>
					<input name="ration_card" type="file" class="form-control">
				</div>
			</div>
			<div class="row g-3 mt-1">
				<div class="col-md-4">
					<label class="form-label">@lang('admission.panchayath_letter')</label>
					<input name="panchayath_letter" type="file" class="form-control">
				</div>
				<div class="col-md-4">
					<label class="form-label">@lang('admission.disability_card')</label>
					<input name="disability_card" type="file" class="form-control">
				</div>
				<div class="col-md-4">
					<label class="form-label">@lang('admission.doctor_certificate')</label>
					<input name="doctor_certificate" type="file" class="form-control">
				</div>
			</div>
			<div class="row g-3 mt-1">
				<div class="col-md-4">
					<label class="form-label">@lang('admission.vincent_card')</label>
					<input name="vincent_depaul_card" type="file" class="form-control">
				</div>
			</div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.guardian_emergency')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="row g-3"><div class="col-md-3"><label class="form-label">@lang('admission.guardian_relation')</label><input name="guardian_relation" class="form-control" value="{{ old('guardian_relation') }}"></div><div class="col-md-3"><label class="form-label">@lang('admission.guardian_name')</label><input name="guardian_name" class="form-control" value="{{ old('guardian_name') }}"></div><div class="col-md-3"><label class="form-label">@lang('admission.guardian_phone')</label><input name="guardian_phone" class="form-control" value="{{ old('guardian_phone') }}"></div><div class="col-md-3"><label class="form-label">@lang('admission.guardian_email')</label><input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email') }}"></div></div>
			<div class="row g-3 mt-1"><div class="col-md-12"><label class="form-label">@lang('admission.guardian_address')</label><textarea name="guardian_address" rows="2" class="form-control">{{ old('guardian_address') }}</textarea></div></div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.family_details')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="row g-3">
				<div class="col-md-4"><label class="form-label">@lang('admission.father_name')</label><input name="father_name" class="form-control" value="{{ old('father_name') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.mother_name')</label><input name="mother_name" class="form-control" value="{{ old('mother_name') }}"></div>
				<div class="col-md-4"><label class="form-label">@lang('admission.spouse_name')</label><input name="spouse_name" class="form-control" value="{{ old('spouse_name') }}"></div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.health_needs')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="mb-3"><label class="form-label">Health Info (JSON / notes)</label><textarea name="health_info" rows="3" class="form-control" placeholder='{"allergies":[],"conditions":[]}'>{{ old('health_info') }}</textarea></div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.notes_case_history')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea></div>
			<div class="mb-3"><label class="form-label">Case Notes</label><textarea name="case_notes" rows="4" class="form-control">{{ old('case_notes') }}</textarea></div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2 d-flex justify-content-between align-items-center"><strong class="small text-uppercase">@lang('admission.documents')</strong><span class="small text-muted">@lang('admission.consent_letter')</span></div>
				<div class="card-body pt-3 pb-2">
			<h5>Extra Documents</h5>
			<div id="extra-documents-wrapper"></div>
			<button type="button" id="add-document-btn" class="btn btn-sm btn-outline-primary mb-3">Add Document</button>
			<small class="text-muted d-block mb-3">Add any additional supporting documents.</small>
			<div class="row g-3 mt-1">
				<div class="col-md-6">
					<label class="form-label">@lang('admission.consent_letter')</label>
					<input name="consent_letter" type="file" class="form-control">
				</div>
			</div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.consent_declaration')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="border rounded p-3 bg-light mb-2">{!! __('admission.consent_body_html') !!}</div>
			<div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" id="consentSigned" name="consent_signed" value="1" @checked(old('consent_signed'))>
				<label class="form-check-label" for="consentSigned">@lang('admission.consent_agree')</label>
			</div>
			</div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2 d-flex justify-content-between align-items-center"><strong class="small text-uppercase">@lang('admission.room_assignment')</strong><span class="small text-muted">Optional</span></div>
				<div class="card-body pt-3 pb-2">
			<div class="card mb-3"><div class="card-body">
				<div class="row g-2 align-items-end">
					<div class="col-md-6">
						<label class="form-label">Pick Room</label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" id="roomSearchCreate" placeholder="Search rooms...">
							<button type="button" class="btn btn-outline-secondary" id="loadRoomsCreate"><span class="bi bi-arrow-repeat"></span></button>
						</div>
						<div class="form-check form-switch mt-2 small">
							<input class="form-check-input" type="checkbox" id="showOccupiedCreate">
							<label class="form-check-label" for="showOccupiedCreate">Show occupied</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="small text-muted">Selected</div>
						<div id="selectedRoomCreate" class="fw-semibold">None</div>
					</div>
				</div>
				<div class="list-group small mt-2" id="roomsListCreate" style="max-height: 240px; overflow: auto;"><div class="text-muted text-center py-3">Use reload to list rooms</div></div>
				<input type="hidden" name="location_id" id="locationIdCreate" value="">
			</div></div>
			</div></div></div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-header py-2"><strong class="small text-uppercase">@lang('admission.staff_section')</strong></div>
				<div class="card-body pt-3 pb-2">
			<div class="row g-3 mt-2">
				<div class="col-md-6"><label class="form-label">@lang('admission.inmate_type') <span class="text-danger">*</span></label><select id="inmate_type" name="type" class="form-select form-select-sm" required><option value="" disabled {{ old('type')?'' :'selected' }}>Select type...</option>@foreach(($inmateTypes ?? []) as $label=>$value)<option value="{{ $value }}" @selected(old('type')==$value)>{{ $label }}</option>@endforeach</select></div>
				<div class="col-md-6">
					<label class="form-label">@lang('admission.verified_by')</label>
					<select name="verified_by" class="form-select form-select-sm">
						<option value="">—</option>
						@foreach(($staff ?? []) as $s)
							<option value="{{ $s->id }}" @selected(old('verified_by')==$s->id)>{{ $s->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			{{-- Dynamic Type Sections --}}
			<div id="dynamic-type-sections" class="mt-4">
				<div class="type-block" data-type="child" style="display:none;">
					@include('partials.inmates._intake_history')
					@include('partials.inmates._educational_records')
				</div>
				<div class="type-block" data-type="elderly" style="display:none;">
					@include('partials.inmates._geriatric_care_plan')
				</div>
				<div class="type-block" data-type="mental_health" style="display:none;">
					@include('partials.inmates._mental_health_plan')
				</div>
				<div class="type-block" data-type="rehabilitation" style="display:none;">
					@include('partials.inmates._rehabilitation_plan')
				</div>
			</div>
			<div class="d-flex gap-2 mt-4">
				<button class="btn btn-primary px-4"><span class="bi bi-check2-circle me-1"></span>@lang('admission.save')</button>
				<a href="{{ route('system_admin.inmates.index') }}" class="btn btn-outline-secondary">@lang('admission.cancel')</a>
			</div>
		</div> <!-- end staff card -->
		</form>
	</div></div>
	@push('scripts')
	<script>
	 document.addEventListener('DOMContentLoaded',()=>{
		const sel=document.getElementById('inmate_type');
		const blocks=document.querySelectorAll('.type-block');
		const marital=document.getElementById('marital_status');
		function show(){
			const v=sel?.value;
			blocks.forEach(b=>b.style.display=b.getAttribute('data-type')===v?'':'none');
			// Toggle marital_status required for elderly/rehab
			if(marital){ marital.required = (v==='elderly' || v==='rehabilitation'); }
		}
		if(sel){ sel.addEventListener('change',show); if(sel.value) show(); }

		// Room picker
		const instSel=document.querySelector('select[name="institution_id"]');
		const list=document.getElementById('roomsListCreate');
		const q=document.getElementById('roomSearchCreate');
		const reload=document.getElementById('loadRoomsCreate');
		const showOcc=document.getElementById('showOccupiedCreate');
		const out=document.getElementById('selectedRoomCreate');
		const hidden=document.getElementById('locationIdCreate');
		let all=[];
		async function load(){
			const inst=instSel?.value; if(!inst){ list.innerHTML='<div class="text-muted text-center py-3">Select institution first</div>'; return; }
			const res=await fetch(`{{ url('system-admin/allocation/api/institutions') }}/${inst}/locations?show_occupied=${showOcc.checked?1:0}`,{headers:{'Accept':'application/json'}});
			const data=await res.json(); all=data.locations||[]; render();
		}
		function render(){
			const term=(q.value||'').toLowerCase(); list.innerHTML='';
			const items=all.filter(r=> (r.name||'').toLowerCase().includes(term));
			if(items.length===0){ list.innerHTML='<div class="text-muted text-center py-3">No rooms</div>'; return; }
			items.forEach(r=>{ const isMaint = (r.status==='maintenance'); const occupied=!!r.occupied; const label=isMaint?'Maintenance':(occupied?'Occupied':'Available'); const badgeClass=isMaint?'bg-warning text-dark':(occupied?'bg-secondary':'bg-success'); const btn=document.createElement('button'); btn.type='button'; btn.setAttribute('aria-label',`Select room ${r.name} ${label}`); btn.className='list-group-item list-group-item-action d-flex justify-content-between align-items-center'; btn.innerHTML=`<span><span class="bi bi-door-closed me-2"></span>${r.name}${r.occupant? ' <span class=\"badge bg-secondary ms-2\">'+r.occupant+'</span>':''}</span><span class=\"badge ${badgeClass}\">${label}</span>`; btn.disabled = isMaint || (occupied && !showOcc.checked); btn.addEventListener('click',()=>{ hidden.value=r.id; out.textContent=r.name; document.querySelectorAll('#roomsListCreate .list-group-item').forEach(x=>x.classList.remove('active')); btn.classList.add('active'); }); list.appendChild(btn); });
		}
		reload?.addEventListener('click', load); q?.addEventListener('input', render); showOcc?.addEventListener('change', load); instSel?.addEventListener('change', load);

		// Extra documents dynamic rows
		const addBtn = document.getElementById('add-document-btn');
		const extraWrap = document.getElementById('extra-documents-wrapper');
		let i = 0;
		addBtn?.addEventListener('click', () => {
			const row = document.createElement('div');
			row.className = 'row g-2 align-items-end mb-2';
			row.innerHTML = `
				<div class="col-md-5"><label class="form-label small">Document Name</label><input type="text" name="doc_names[${i}]" class="form-control"></div>
				<div class="col-md-5"><label class="form-label small">File</label><input type="file" name="doc_files[${i}]" class="form-control"></div>
				<div class="col-md-2 d-grid"><button type="button" class="btn btn-outline-danger btn-sm remove-doc">Remove</button></div>
			`;
			extraWrap.appendChild(row); i++;
		});
		extraWrap?.addEventListener('click', (e) => { if(e.target.classList.contains('remove-doc')) e.target.closest('.row')?.remove(); });

		// Age auto-calc
		const dob=document.getElementById('dob'); const age=document.getElementById('age');
		function calcAge(){ if(!dob?.value) { age.value=''; return; } const d=new Date(dob.value); if(isNaN(d.getTime())) { age.value=''; return; } const today=new Date(); let a=today.getFullYear()-d.getFullYear(); const m=today.getMonth()-d.getMonth(); if(m<0 || (m===0 && today.getDate()<d.getDate())) a--; age.value = (a>=0? a : ''); }
		dob?.addEventListener('change',calcAge); if(dob?.value) calcAge();
	 });
	</script>
	@endpush
</x-app-layout>
