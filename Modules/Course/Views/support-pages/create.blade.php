@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection

@section('content')
<div>
    @php
        $pageTitle = $data ? 'Edit Support Page' : 'Add New Support Page';
        echo breadcrums($pageTitle, ['common.home', 'Course Support Pages', $pageTitle], ['dashboard', 'course-support-page-list']);
    @endphp
    @section('back_button')
        @php echo back_button('course-support-page-list') @endphp
    @endsection
</div>

<div class="text-xs w-full bg-white border rounded-md p-5 shadow-sm mt-3">
    <form action="{{ route('course-support-page-store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if ($data)
            <input type="hidden" name="id" value="{{ encrypt($data->id) }}">
        @endif

        <div class="border-b pb-3 mb-4">
            <h3 class="text-base font-semibold text-gray-800">
                {{ $data ? 'Edit Course Support Page' : 'Create Course Support Page' }}
            </h3>
            <p class="text-gray-500 text-xs">
                Configure content for <strong>Exam Format</strong>, <strong>Syllabus</strong>, or <strong>Study Notes</strong>. Choose <em>Published</em> to display live on the frontend website, or <em>Draft</em> to disable it (404).
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Course Selection -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Target Course <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="course_id" id="course_id" class="form-select select2 w-full text-xs p-2 border rounded" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" @selected(old('course_id', $data?->course_id) == $c->id)>
                            {{ $c->course_title }} ({{ $c->url_title }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Page Type -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Page Type <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="type" id="type" class="form-select w-full text-xs p-2 border rounded font-medium" required>
                    <option value="syllabus" @selected(old('type', $data?->type) === 'syllabus')>
                        📘 Course Syllabus (URL: /{slug}/syllabus)
                    </option>
                    <option value="exam-format" @selected(old('type', $data?->type) === 'exam-format' || old('type', $data?->type) === 'exam')>
                        📋 Exam Format & Structure (URL: /{slug}/exam)
                    </option>
                    <option value="eligibility" @selected(old('type', $data?->type) === 'eligibility')>
                        🎓 Certification Eligibility (URL: /{slug}/eligibility)
                    </option>
                    <option value="study-plan" @selected(old('type', $data?->type) === 'study-plan')>
                        📅 Study Plan & Roadmap (URL: /{slug}/study-plan)
                    </option>
                    <option value="corporate-training" @selected(old('type', $data?->type) === 'corporate-training')>
                        🏢 Corporate Training (URL: /{slug}/corporate-training)
                    </option>
                    <option value="practice-test" @selected(old('type', $data?->type) === 'practice-test')>
                        📝 Practice Test / Mock Overview (URL: /{slug}/practice-test)
                    </option>
                    <option value="faqs" @selected(old('type', $data?->type) === 'faqs')>
                        ❓ Frequently Asked Questions (URL: /{slug}/faqs)
                    </option>
                    <option value="certification-benefits" @selected(old('type', $data?->type) === 'certification-benefits')>
                        ⭐ Certification Benefits (URL: /{slug}/certification-benefits)
                    </option>
                    <option value="certification-guide" @selected(old('type', $data?->type) === 'certification-guide')>
                        📖 Certification Guide (URL: /{slug}/certification-guide)
                    </option>
                    <option value="notes" @selected(old('type', $data?->type) === 'notes')>
                        📝 Study Notes (URL: /{slug}/notes)
                    </option>
                </select>
            </div>

            <!-- Status / Visibility -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Frontend Visibility / Permission <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="status" id="status" class="form-select w-full text-xs p-2 border rounded font-semibold text-gray-800" required>
                    <option value="published" class="text-green-600" @selected(old('status', $data?->status ?? 'published') === 'published')>
                        ✅ Published (Enabled on Website)
                    </option>
                    <option value="draft" class="text-red-600" @selected(old('status', $data?->status) === 'draft')>
                        ❌ Draft (Disabled / 404 on Website)
                    </option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Page Title -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Page Heading / Title <span class="text-red-500 font-bold">*</span>
                </label>
                <input type="text" name="title" id="title" class="form-input w-full text-xs p-2 border rounded"
                    placeholder="e.g. PRINCE2 7 Foundation Exam Format & Structure"
                    value="{{ old('title', $data?->title ?? '') }}" required />
            </div>

            <!-- Custom URL Slug (Optional) -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Custom URL Slug (Optional)
                </label>
                <input type="text" name="slug" id="slug" class="form-input w-full text-xs p-2 border rounded bg-gray-50"
                    placeholder="e.g. prince2-exam-format (leave blank for default course subpage)"
                    value="{{ old('slug', $data?->slug ?? '') }}" />
                <span class="text-gray-400 text-xs">Default: <code>/{course-slug}/{type}</code> (e.g. /prince2-7-foundation-course/exam)</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-4">

            <!-- Page Rich Content -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Page Body Content (Summernote WYSIWYG Editor)
                </label>
                <textarea name="content" id="content_editor" class="form-input w-full summernote" rows="12">{{ old('content', $data?->content ?? '') }}</textarea>
                <p class="text-gray-400 text-xs mt-1">
                    Write or paste rich formatting, headings, tables, exam rules, or syllabus modules. This content renders directly on the dedicated page.
                </p>
            </div>
        </div>

        <!-- SEO Accordion / Card -->
        <div class="border rounded-md p-4 bg-gray-50 mb-5">
            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-1">
                <i class="ri-search-eye-line text-sm text-primary"></i> SEO & Search Engine Optimization
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">SEO Meta Title</label>
                    <input type="text" name="seo_title" class="form-input w-full text-xs p-2 border rounded bg-white"
                        placeholder="Meta title for Google and social previews"
                        value="{{ old('seo_title', $data?->seo_title ?? '') }}" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Canonical URL (Optional)</label>
                    <input type="text" name="canonical_url" class="form-input w-full text-xs p-2 border rounded bg-white"
                        placeholder="https://scholaracad.com/..."
                        value="{{ old('canonical_url', $data?->canonical_url ?? '') }}" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">SEO Meta Description</label>
                    <textarea name="seo_description" rows="2" class="form-input w-full text-xs p-2 border rounded bg-white"
                        placeholder="Brief summary for search engine snippets">{{ old('seo_description', $data?->seo_description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_indexable" id="is_indexable" value="1"
                        @checked(old('is_indexable', $data ? $data->is_indexable : true)) class="rounded text-primary">
                    <label for="is_indexable" class="text-xs text-gray-700 cursor-pointer font-medium">
                        Allow Search Engines to Index this Page (Recommended)
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between border-t pt-4">
            <a href="{{ route('course-support-page-list') }}" class="btn-secondary-sm text-gray-600 hover:text-gray-900 text-xs px-4 py-2 border rounded">
                Cancel
            </a>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-5 py-2 rounded-md font-semibold text-xs flex items-center gap-1 shadow">
                    <i class="ri-check-line text-sm"></i>
                    {{ $data ? 'Update Support Page' : 'Save & Publish Page' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@include('layouts.summer_note_js')
<script>
    $(document).ready(function() {
        if ($('.summernote').length > 0) {
            $('.summernote').summernote({
                height: 300,
                placeholder: 'Enter rich text content for this page...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
@endsection
