<form
    action="{{ isset($knowledgeBase) ? route('admin.knowledge-bases.update', $knowledgeBase->id) : route('admin.knowledge-bases.store') }}"
    method="POST">
    @csrf
    @if (isset($knowledgeBase))
        @method('PUT')
    @endif

    <h5 class="mb-3">Knowledge Base Details</h5>
    <div class="row">
        {{-- Chat Category --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Category <span class="text-danger">*</span></label>
            <select name="chat_category_id" class="form-select search-select">
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('chat_category_id', isset($knowledgeBase) ? $knowledgeBase->chat_category_id : '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('chat_category_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Title --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                value="{{ old('title', isset($knowledgeBase) ? $knowledgeBase->title : '') }}">
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Content --}}
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Content <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control" id="content-editor" rows="5">{{ old('content', isset($knowledgeBase) ? $knowledgeBase->content : '') }}</textarea>
            @error('content')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Keywords --}}
        <div class="col-lg-12 mb-3 o-f-inp">
            <label>Keywords <span class="text-danger">*</span></label>
            <select name="keywords[]" id="keywords" class="form-select" multiple="multiple">
                @php
                    $selectedKeywords = old('keywords', isset($knowledgeBase) ? $knowledgeBase->keywords : []);
                @endphp
                @foreach ($selectedKeywords as $keyword)
                    <option value="{{ $keyword }}" selected>{{ $keyword }}</option>
                @endforeach
            </select>
            @error('keywords')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        {{-- Status --}}
        <div class="col-lg-6 mb-3 o-f-inp">
            <label>Status <span class="text-danger">*</span></label>
            @php
                $statuses = ['draft' => 'Draft', 'published' => 'Published', 'unpublished' => 'Unpublished'];
            @endphp
            <select name="status" class="form-select search-select">
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('status', isset($knowledgeBase) ? $knowledgeBase->status : '') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <hr>
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('admin.knowledge-bases.index') }}" class="btn btn-danger">
            Back
        </a>
        <button type="submit" class="btn btn-success">
            {{ isset($knowledgeBase) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>
