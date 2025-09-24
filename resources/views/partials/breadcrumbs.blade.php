@php
    $breadcrumbs = App\Helpers\Helper::generateBreadcrumbs();
@endphp

@if(count($breadcrumbs) > 1)
<div class="bg-gray-100 dark:bg-gray-800  px-4 sm:px-6 py-3">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            @foreach($breadcrumbs as $index => $breadcrumb)
                @if($index > 0)
                    <li class="flex items-center">
                        <span class="mx-2 text-gray-400 dark:text-gray-500">/</span>
                    </li>
                @endif
                <li>
                    @if($breadcrumb['url'])
                        <a href="{{ $breadcrumb['url'] }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                            {{ $breadcrumb['title'] }}
                        </a>
                    @else
                        <span class="text-gray-900 dark:text-white font-medium" aria-current="page">
                            {{ $breadcrumb['title'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>
@endif