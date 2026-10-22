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

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded text-xs">
                <p class="font-bold mb-1">Please fix the following issues:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="border-b pb-3 mb-4">
            <h3 class="text-base font-semibold text-gray-800">
                {{ $data ? 'Edit Course Support Page' : 'Create Course Support Page' }}
            </h3>
            <p class="text-gray-500 text-xs">
                Configure content for <strong>Exam Format</strong>, <strong>Syllabus</strong>, or <strong>Study Notes</strong>. Choose <em>Published</em> to display live on the frontend website, or <em>Draft</em> to disable it (404).
            </p>
        </div>

    @php
        $predefinedTypes = [
            'syllabus', 'exam-format', 'exam', 'case-study', 'eligibility', 'study-plan',
            'corporate-training', 'practice-test', 'faqs', 'certification-benefits',
            'certification-guide', 'notes'
        ];
        $currentType = old('type', $data?->type);
        $isCustomType = $currentType && !in_array($currentType, $predefinedTypes) && $currentType !== 'custom';
    @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Course Selection -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Target Course <span class="text-red-500 font-bold">*</span>
                </label>
                <select name="course_id" id="course_id" class="form-select select2 w-full text-xs p-2 border rounded" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" data-slug="{{ $c->url_title }}" @selected(old('course_id', $data?->course_id) == $c->id)>
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
                    <option value="case-study" @selected(old('type', $data?->type) === 'case-study')>
                        💼 Case Study & Scenarios (URL: /{slug}/case-study)
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
                    <option value="custom" @selected($isCustomType || old('type') === 'custom') class="font-bold text-purple-700 bg-purple-50">
                        ✨ Custom Page (Create Custom Subpage)
                    </option>
                </select>

                <!-- Custom Page Type Name Input (Shown when 'custom' selected or custom type exists) -->
                <div id="custom_type_container" class="{{ ($isCustomType || old('type') === 'custom') ? '' : 'hidden' }} mt-2 p-2.5 bg-purple-50 border border-purple-200 rounded">
                    <label class="block text-xs font-semibold text-purple-900 mb-1">
                        Custom Page Identifier / Slug <span class="text-red-500 font-bold">*</span>
                    </label>
                    <input type="text" name="custom_type" id="custom_type" class="form-input w-full text-xs p-1.5 border border-purple-300 rounded bg-white"
                        placeholder="e.g. interview-questions or career-scope"
                        value="{{ old('custom_type', ($isCustomType && $data) ? $data->type : '') }}" />
                    <span class="text-purple-700 text-[11px] block mt-1">
                        💡 This will be used in the page URL: <code>/{course-slug}/{custom-page-name}</code>
                    </span>
                </div>
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
                <span class="text-gray-500 text-xs mt-1 block">
                    Default URL pattern: <code>/{course-slug}/{type}</code> (leave blank to use default URL, or specify a custom slug)
                </span>
                <span class="text-blue-600 text-xs mt-1 block font-medium">
                    🔗 Live URL Preview: <code id="preview_slug_text" class="bg-blue-50 px-1 py-0.5 rounded text-blue-700">/{course-slug}/{type}</code>
                </span>
            </div>
        </div>

        <!-- Case Study / Matching Visual Image -->
        <div class="mb-4 bg-gray-50 p-3 rounded border">
            <label class="block text-xs font-semibold text-gray-800 mb-1 flex items-center gap-1">
                <i class="ri-image-line text-sm text-primary"></i> Case Study / Matching Visual Image (Optional)
            </label>
            <div class="flex items-center gap-3">
                <input type="file" name="image" id="image" accept="image/*" class="form-input text-xs p-1.5 border rounded bg-white w-full max-w-md">
                @if(!empty($data?->image))
                    <div class="flex items-center gap-2 bg-white border p-1 rounded">
                        <img src="{{ asset($data->image) }}" alt="Preview" class="w-8 h-8 object-cover rounded border">
                        <a href="{{ asset($data->image) }}" target="_blank" class="text-[11px] text-blue-600 underline font-medium">View current image</a>
                    </div>
                @endif
            </div>
            <span class="text-gray-500 text-[11px] mt-1 block">
                💡 Scenario diagram, flow chart, or matching prompt visual embedded for this page.
            </span>
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
                    <span class="text-gray-500 text-[11px] mt-1 block">
                        💡 Leave blank for new unique pages. Only specify if this page content is a canonical duplicate of another URL.
                    </span>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">SEO Meta Description</label>
                    <textarea name="seo_description" rows="2" class="form-input w-full text-xs p-2 border rounded bg-white"
                        placeholder="Brief summary for search engine snippets">{{ old('seo_description', $data?->seo_description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_indexable" id="is_indexable" value="1"
                        @checked(old('is_indexable', $data?->is_indexable ?? true)) class="rounded text-primary">
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
        // Custom Text Highlighter Button for Case Study / Review
        var HighlightButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.buttonGroup([
                ui.button({
                    className: 'dropdown-toggle',
                    contents: '<span style="background-color: #fef08a; padding: 2px 6px; border-radius: 3px; font-weight: bold; border: 1px solid #eab308; color: #854d0e;">🖍️ Highlight <span class="caret"></span></span>',
                    tooltip: 'Text Highlighter (Yellow / Green / Pink / Blue)',
                    data: { toggle: 'dropdown' }
                }),
                ui.dropdown({
                    className: 'dropdown-menu',
                    items: [
                        '<span style="background-color: #fef08a; padding: 3px 8px; border-radius: 3px; font-weight: bold; color: #854d0e; display: block;">🟡 Yellow Marker</span>',
                        '<span style="background-color: #bbf7d0; padding: 3px 8px; border-radius: 3px; font-weight: bold; color: #166534; display: block;">🟢 Green Marker</span>',
                        '<span style="background-color: #fbcfe8; padding: 3px 8px; border-radius: 3px; font-weight: bold; color: #9d174d; display: block;">🔴 Pink Marker</span>',
                        '<span style="background-color: #bae6fd; padding: 3px 8px; border-radius: 3px; font-weight: bold; color: #075985; display: block;">🔵 Blue Marker</span>',
                        '<span style="color: #6b7280; font-weight: bold; display: block; padding: 3px 8px;">❌ Clear Highlight</span>'
                    ],
                    callback: function ($dropdown) {
                        $dropdown.find('li, a').each(function (idx) {
                            $(this).on('click', function (e) {
                                e.preventDefault();
                                var colors = ['#fef08a', '#bbf7d0', '#fbcfe8', '#bae6fd', 'transparent'];
                                context.invoke('editor.restoreRange');
                                if (colors[idx] === 'transparent') {
                                    document.execCommand('removeFormat', false, 'backColor');
                                    document.execCommand('hiliteColor', false, 'transparent');
                                } else {
                                    document.execCommand('hiliteColor', false, colors[idx]);
                                }
                            });
                        });
                    }
                })
            ]);
            return button.render();
        };

        if ($('.summernote').length > 0) {
            $('.summernote').summernote({
                height: 320,
                placeholder: 'Enter rich text content for this page...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['custom', ['highlighter']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                buttons: {
                    highlighter: HighlightButton
                }
            });
        }

        // Toggle Custom Page Type container
        function handleTypeToggle() {
            var val = $('#type').val();
            if (val === 'custom') {
                $('#custom_type_container').removeClass('hidden');
            } else {
                $('#custom_type_container').addClass('hidden');
            }
            updateUrlPreview();
        }

        function updateUrlPreview() {
            var courseSlug = $('#course_id option:selected').data('slug') || '{course-slug}';
            var customSlug = ($('#slug').val() || '').trim();
            var pageType = $('#type').val();
            var customType = ($('#custom_type').val() || '').trim();

            var finalSlug = '';
            if (customSlug) {
                finalSlug = customSlug;
            } else if (pageType === 'custom') {
                finalSlug = customType || '{custom-slug}';
            } else if (pageType === 'exam-format') {
                finalSlug = 'exam';
            } else if (pageType) {
                finalSlug = pageType;
            } else {
                finalSlug = '{type}';
            }

            $('#preview_slug_text').text('/' + courseSlug + '/' + finalSlug);
        }

        $('#type').on('change', handleTypeToggle);
        $('#course_id, #slug, #custom_type').on('input change', updateUrlPreview);

        // Auto sync slug and custom_type if one is typed
        $('#slug').on('input', function() {
            if ($('#type').val() === 'custom' && !$('#custom_type').val()) {
                $('#custom_type').val($(this).val());
            }
        });
        $('#custom_type').on('input', function() {
            if (!$('#slug').val()) {
                $('#slug').val($(this).val());
            }
        });

        handleTypeToggle();
        updateUrlPreview();
    });
</script>
@endsection
