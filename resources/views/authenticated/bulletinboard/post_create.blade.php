<x-sidebar>
  <div class="post_create_container d-flex">
    <div class="post_create_area border w-50 m-5 p-5">
        <form action="{{ route('post.create') }}" method = "post" id = "postCreate">
          @csrf
          <p class="mb-0">カテゴリー</p>
          <select class="w-100" name="post_category_id" class = "post_category_id">
          @foreach($main_categories as $main_category)
          <optgroup label="{{ $main_category->main_category }}" class = "category_name">
              <!-- サブカテゴリー表示 -->
               @foreach($main_category->SubCategories as $sub_category)
              <option value="{{ $sub_category->id  }}" class="subject_name">
                {{ $sub_category->sub_category }}
              </option>
              @endforeach
            </optgroup>
            @endforeach
          </select>
          <div class="mt-3">
            @error('post_title')
            <span class="error_message">{{ $message }}</span>
            @enderror
            <p class="mb-0">タイトル</p>
            <input type="text" class="w-100" name="post_title" value="{{ old('post_title') }}">
          </div>
          <div class="mt-3">
            @error('post_body')
            <span class="error_message">{{ $message }}</span>
            @enderror
            <p class="mb-0">投稿内容</p>
            <textarea class="w-100" name="post_body">{{ old('post_body') }}</textarea>
          </div>
          <div class="mt-3 text-right">
            <input type="submit" class="btn btn-primary" value="投稿" form = "postCreate">
          </div>
        </form>
      </div>

      <!-- @can('admin') -->
      @if( Auth::user() && in_array(Auth::user()->role,[1,2,3]))
      <div class="w-25 ml-auto mr-auto">
        <div class="category_area mt-5 p-5">
          <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">
            @csrf
            <div class="">
              <p class="m-0">メインカテゴリー</p>
              <input type="text" class="w-100" name="main_category_name" value = "{{ old('main_category_name') }}">
              <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
              @error('main_category_name')
              <div class = "main_category_error"><span>{{$message}}</span></div>
              @enderror
            </div>
          </form>
          <!-- サブカテゴリー追加 -->
          <div class= "subcategory">
            <form action="{{ route('sub.category.create') }}" method="POST" id="subCategoryRequest">
              @csrf
              @error('sub_category_name')
              <div class = "sub_category_error"><span>{{$message}}</span></div>
              @enderror
              <p class="mt-5 mb-0">サブカテゴリー</p>
              <select name="main_category_id" class="w-100">
                @foreach($main_categories as $main_category)
                <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
                @endforeach
              </select>
              <input type="text" class="w-100" name="sub_category_name" value = "{{ old('sub_category_name') }}">
              <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
            </form>
          </div>
        </div>
          @endif
          <!-- <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form> -->
    </div>
  <!-- @endcan -->
</x-sidebar>
