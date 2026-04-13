<form action="{{ isset($document) ? route('admin.documents.update', $document->id) : route('admin.documents.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($document))
        @method('PUT')
    @endif

    <h5 class="mb-3">Document Details</h5>
    <div class="row">
        @if (isset($companyId))
            <input type="hidden" name="redirect" value="1">
        @endif
        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Type <span class="text-danger">*</span></label>
            <select name="company_type_id" id="company_type_id" class="form-select search-select">
                <option value="">-- Select Type --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('company_type_id', $document->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            @error('company_type_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        @if(Auth::user()->hasRole('Super Admin'))
            <div class="col-lg-4 mb-3 o-f-inp">
                <label>Corp User <span class="text-danger">*</span></label>
                <select name="business_user_id" id="business_user_id" class="form-select search-select">
                    <option value="">-- Select Corp User --</option>
                    @foreach ($corpUsers as $user)
                        <option value="{{ $user->id }}" {{ old('business_user_id', $document->business_user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('business_user_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="business_user_id" value="{{ Auth::id() }}">
        @endif

        @if (is_null($companyId))
            <div class="col-lg-4 mb-3 o-f-inp">
                <label>Company <span class="text-danger">*</span></label>
                <select name="company_id" id="company_id" class="form-select search-select">
                    <option value="">-- Select Company --</option>
                </select>
                @error('company_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        @else
            <input type="hidden" name="company_id" id="company_id" value="{{ $companyId }}">
        @endif

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Total Group <span class="text-danger">*</span></label>
            <select name="total_group_id" id="total_group_id" class="form-select search-select">
                <option value="">-- Select Total Group --</option>
                @foreach ($totalGroups as $group)
                    <option value="{{ $group->id }}" {{ old('total_group_id', $document->total_group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->customer_name }}
                    </option>
                @endforeach
            </select>
            @error('total_group_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                value="{{ old('title', isset($document) ? $document->title : '') }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Financial Year <span class="text-danger">*</span></label>
            <select name="financial_year_id" class="form-select search-select">
                <option value="">-- Select year --</option>
                @foreach ($years as $year)
                    <option value="{{ $year->id }}" {{ old('financial_year_id', isset($document) ? $document->financial_year_id : '') == $year->id ? 'selected' : '' }}>
                        {{ $year->year }}
                    </option>
                @endforeach
            </select>
            @error('financial_year_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Document Type <span class="text-danger">*</span></label>
            <select name="document_type" class="form-select">
                <option value="">-- Select Document Type --</option>
                @foreach (['pdf' => 'PDF', 'word' => 'Word', 'image' => 'Image', 'power_point' => 'Power Point', 'excel' => 'Excel'] as $key => $value)
                    <option value="{{ $key }}" {{ old('document_type', $document->document_type ?? '') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('document_type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Document File <span class="text-danger">*</span></label>
            <input type="file" name="document" class="form-control" id="documentInput">

            <div id="documentPreview" class="mt-2">
                @if (isset($document) && $document->document)
                    <a href="{{ asset('storage/' . $document->document) }}" target="_blank" class="d-block">
                        View Current Document
                    </a>
                @endif
            </div>
            @error('document')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Document Issued</label>
            <input type="date" name="valid_from" class="form-control"
                value="{{ old('valid_from', isset($document) && $document->valid_from ? $document->valid_from->format('Y-m-d') : '') }}">
            @error('valid_from')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp d-none">
            <label>Document Expiry</label>
            <input type="date" name="valid_to" class="form-control"
                value="{{ old('valid_to', isset($document) && $document->valid_to ? $document->valid_to->format('Y-m-d') : '') }}">
            @error('valid_to')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-lg-4 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select">
                <option value="active" {{ old('status', $document->status ?? '') == 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ old('status', $document->status ?? '') == 'inactive' ? 'selected' : '' }}>
                    Inactive</option>
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>
    </div>

    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route_with_query('admin.documents.index', [
    'company_id' => $companyId ?? null,
]) }}" class="btn btn-danger">
            Back
        </a>
        <button type="submit" class="btn btn-success">
            {{ isset($document) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>