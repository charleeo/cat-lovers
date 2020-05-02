{{-- @if ($errors->any())
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>
    <div class="col-md-8 offset-md-2 alert-danger">
        @foreach ($errors->all() as $error)
        <span>{{ $error }}</span>
        @break;
        @endforeach
    </div>
</div>
@endif --}}


<form action="{{route('save-movie')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="movie_title">Movie Title</label> <br>
    <input type="text" name="movie_title"> <br> <br>

    <label for="movie_title">Cat 1  Image</label> <br>
    <input type="file" name="cat_1_image"> <br> <br>

    <label for="movie_title">Cat 1 Name</label> <br>
    <input type="text" name="cat_1_name"> <br> <br>

    <label for="movie_title">Cat 2  Image</label> <br>
    <input type="file" name="cat_2_image"> <br> <br>

    <label for="movie_title">Cat 2 Name</label> <br>
    <input type="text" name="cat_2_name"> <br> <br>

    <label for="fight_duration"> Fight Duration</label> <br>
    <input type="text" name="fight_duration"> <br> <br>

    <label for="fight_video">Video</label> <br>
    <input type="file" name="fight_video"> <br> <br>

    <label for="description">Fight Description</label> <br>
    <textarea name="description" id="" cols="30" rows="10"></textarea>
    <button>Submit</button>
</form>
