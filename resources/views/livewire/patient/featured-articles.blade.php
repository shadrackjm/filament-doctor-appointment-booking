<!-- Card Blog -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Featured Articles</h2>
  </div>
  <!-- Grid -->
  <div class="grid lg:grid-cols-2 gap-6">
    @if (count($featured_articles) > 0)
        @foreach ($featured_articles as $article)
             <!-- Card -->
    <a class="group sm:flex rounded-xl" href="/article/{{$article->id}}">
      <div class="flex-shrink-0 relative rounded-xl overflow-hidden h-[200px] sm:w-[250px] sm:h-[350px] w-full">
        <img class="size-full absolute top-0 start-0 object-cover" src="{{ Storage::url($article->image) }}" alt="Image Description">
      </div>

      <div class="grow">
        <div class="p-4 flex flex-col h-full sm:p-6">
          <div class="mb-3">
            <p class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-neutral-200">
              {{ $article->category}}
            </p>
          </div>
          <h3 class="text-lg sm:text-2xl font-semibold text-gray-800 group-hover:text-blue-600 dark:text-neutral-300 dark:group-hover:text-white">
            {{ $article->title }}
          </h3>
          <p class="mt-2 text-gray-600 dark:text-neutral-400">
            {!! str($article->content)->words(5) !!}
          </p>

          <div class="mt-5 sm:mt-auto">
            <!-- Avatar -->
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <img class="size-[46px] rounded-full" src="{{ Storage::url($article->author->doctor->image )}}" alt="Image Description">
              </div>
              <div class="ms-2.5 sm:ms-4">
                <h4 class="font-semibold text-gray-800 dark:text-neutral-200">
                  {{ $article->author->name }}
                </h4>
                <p class="text-xs text-gray-500 dark:text-neutral-500">
                  {{ $article->created_at->format('M d, Y') }}
                </p>
              </div>
            </div>
            <!-- End Avatar -->
          </div>
        </div>
      </div>
    </a>
    <!-- End Card -->
        @endforeach
    @else
        <h1>No articles found!</h1>
    @endif
  </div>
  <!-- End Grid -->
</div>
<!-- End Card Blog -->