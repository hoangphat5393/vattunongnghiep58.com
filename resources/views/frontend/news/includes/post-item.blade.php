<div class="blog-post">
      <div class="blog-post-image">
        <div class="img">
          <a href="{{ route('news.single', ['id' => $post->id, 'slug' => $post->slug], true, $lc) }}">
            <img class="img-fluid" src="{{ asset($post->image) }}" onerror="this.src='{{ asset('assets/images/placeholder.png') }}';">
          </a>
        </div>
      </div>
      <div class="blog-post-content">
        <div class="blog-post-details">
          <div class="blog-post-title">
            <h5><a href="{{ route('news.single', ['id' => $post->id, 'slug' => $post->slug], true, $lc) }}">{{ $post->title }}</a></h5>
          </div>
          @if($post->description!='')
          <div class="blog-post-description">
              {!! htmlspecialchars_decode($post->description) !!}
          </div>
          @endif
        </div>
      </div>
    </div>