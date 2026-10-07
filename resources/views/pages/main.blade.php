<x-layouts.app :title="$page->header_title" :description="$page->meta_description" :keywords="$page->meta_keywords" :image="$page->image_url" :page="$page">

    @if ($page->show_breadcrumb)
        <x-sections.breadcrumbs :page="$page" />
    @endif

    @foreach ($sections as $section)
        <x-dynamic-component :component="$section['component']" />
    @endforeach


</x-layouts.app>
