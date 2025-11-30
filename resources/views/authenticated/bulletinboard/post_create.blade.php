<x-sidebar>
  <div class="post_create_container d-flex">
    <div class="post_create_area border w-50 m-5 p-5">
      <div class="">
        <p class="mb-0">カテゴリー</p>
        <select class="w-100" form="postCreate" name="post_category_id" class = "post_category_id">
          @foreach($main_categories as $main_category)
          <optgroup label="{{ $main_category->main_category }}" class = "category_name">
            <!-- サブカテゴリー表示 -->
               <option value = "kokugo" class = "subject_name">国語</option>
               <option value = "suugaku" class = "subject_name">数学</option>
               <option value = "eigo" class = "subject_name">英語</option>
             </optgroup>
            @endforeach
          </select>
      </div>
      <div class="mt-3">
        @if($errors->first('post_title'))
        <span class="error_message">{{ $errors->first('post_title') }}</span>
        @endif
        <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <div class="w-25 ml-auto mr-auto">
    <div class="category_area mt-5 p-5">
      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">
        @csrf
      <div class="">
        @error('main_category_name')
        <div class = "error"><span>{{$message}}</span></div>
        @enderror
        <p class="m-0">メインカテゴリー</p>
        <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
      </div>

      <!-- サブカテゴリー追加 -->
      @if( Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 3)
      <form action="{{ route('sub.category.create') }}" method="POST" id="subCategoryRequest">
            @csrf
       <div class= "subcategory">
         <p class="m-0">サブカテゴリー</p>
         <select name="" id="" class="w-100">
           <optgroup>
            <option value="---">---</option>
             <option value="国語">国語</option>
             <option value="数学">数学</option>
             <option value="英語">英語</option>
           </optgroup>
           <input type="text" class="w-100" name="sub_category_name" form="subCategoryRequest">
           <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
         </select>
         @error('sub_category_name')
         <div class = "error"><span>{{$message}}</span></div>
        @enderror
        </div>
        @endif
      <!-- <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form> -->
    </div>
  </div>
  @endcan
</div>
</x-sidebar>
