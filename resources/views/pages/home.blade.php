<x-app :title="$page->title" :description="$page->meta_description" :keywords="$page->meta_keywords" :image="$page->image_url">
    @foreach ($page->sections ?? [] as $section)
        @continue(!($section['is_visible'] ?? true))

        @php($component = config("sections.{$page->name}.{$section['name']}"))

        @if ($component)
            <x-dynamic-component :component="$component" :section="$section['name']" />
        @endif
    @endforeach
</x-app>
