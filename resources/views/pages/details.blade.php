<x-layouts.app :title="$meta['title']" :description="$meta['description']" :keywords="$meta['keywords']" :image="$meta['image']" :page="$page">


    @if ($page->show_breadcrumb)
        <x-sections.breadcrumbs :page="$page" />
    @endif

    @foreach ($sections as $section)
        @if ($section['requires_entity'])
            <x-dynamic-component :component="$section['component']" :entity="$entity" />
        @else
            <x-dynamic-component :component="$section['component']" />
        @endif
    @endforeach

</x-layouts.app>
