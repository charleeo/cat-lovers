<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateMovieRequest;
use App\Movie;
use getID3;
use App\Rules\MoviesCreationRule;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    // list all the available movies
    public function listMovies()
    {
        $movies = Movie::select('movie_title', 'fight_duration','cat_1_image', 'cat_1_name', 'cat_2_image', 'cat_2_name', 'fight_video', 'description', 'movie_id')->get();
        if($movies->count() == 0)
        {
            return response()->json(['Message'=>'The Movies Collection Is Currently Empty, Please Check Back Later'],200);
        }
        return response()->json($movies, 200);
    }




    // save movies to database
    public function saveMovie(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'fight_video' => ['required', 'mimes:mp4,mvk', 'max:500000'],
            'movie_title' => ['required', 'string', 'min:3', 'max:55'],
            'cat_1_image' => ['required', 'image','max:1500'],
            'cat_2_image' => ['required', 'image', 'max:1500'],
            'cat_1_name' => ['required', 'min:3', 'max:55'],
            'cat_2_name' => ['required', 'min:3', 'max:55'],
            'description' => ['nullable','min:20'],
        ]);

        $cat1Image = $request->file('cat_1_image');
        $cat2Image = $request->file('cat_2_image');
        $video = $request->file('fight_video');
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $extensionImage1 = $cat1Image->getClientOriginalExtension();
        $extensionImage2 = $cat2Image->getClientOriginalExtension();
        $videoExtension = $video->getClientOriginalExtension();
        $cat1ImageName =  pathinfo($cat1Image->getClientOriginalName(), PATHINFO_FILENAME);
        $cat2ImageName =  pathinfo($cat2Image->getClientOriginalName(), PATHINFO_FILENAME);
        $videoName =  pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
        $catUploadName1 = time().'.'.$extensionImage1;
        $catUploadName2 = time().'.'.$extensionImage2;
        $videoUploadName = time().'.'.$videoExtension;


        if($validateData->fails())
        {
            return response()->json(['error'=>$validateData->errors()], 401);
        }
        if(!in_array($extensionImage1, $extensions)|| !in_array($extensionImage2, $extensions))
        {
            return back()->with('error', 'supported file types are: jpg, gif, png, jpeg');
        }

           ($cat1Image->move(public_path('images/cat1/'), $catUploadName1));
           ($cat2Image->move(public_path('images/cat2/'), $catUploadName2));
           ($video->move(public_path('videos/'), $videoUploadName));

        //    dd(public_path('videos/'), $videoUploadName);
           $getID3 = new getID3;



           $video_file = $getID3->analyze(public_path('videos/'. $videoUploadName));
            // Get the duration in string, e.g.: 4:37 (minutes:seconds)
           $duration_string = $video_file['playtime_string'];
           // // Get the duration in seconds, e.g.: 277 (seconds)
        //    $duration_seconds = $video_file['playtime_seconds'];


               $movie = new  Movie();
               $movie->movie_title = $request->movie_title;
               $movie->cat_1_image = $catUploadName1;
               $movie->cat_2_image = $catUploadName2;
               $movie->cat_1_name = $request->cat_2_name;
               $movie->cat_2_name = $request->cat_2_name;
               $movie->fight_duration = $duration_string;
               $movie->description = $request->description;
               $movie->fight_video = $videoUploadName;
               $movie->user_id = 1;
                // Auth::user()->id;
               $movie->video_real_name = $videoName;
               $movie->cat_1_real_name = $cat1ImageName;
               $movie->cat_2_real_name = $cat2ImageName;
               //save the movie object and return a feedback
               if($movie->save())
               {
                   return response()->json(['Success'=>"Moving Created Successfully",'Movie' => $movie], 201);

                }
                else
                {
                    return response()->json(['Error'=>"Moving was not created please try again"], 500);

               }
    }

    public function updateMovie(Request $request, $id)
    {
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $validateData = Validator::make($request->all(), [
            'movie_title' => ['required', 'string', 'min:3', 'max:55'],
            'cat_1_name' => ['required', 'min:3', 'max:55'],
            'cat_2_name' => ['required', 'min:3', 'max:55'],
            'fight_duration' => ['required', 'max:55'],
            'description' => ['nullable','min:20'],
        ]);
        if($validateData->fails())
        {
            return response()->json(['Error'=>$validateData->errors()], 401);
        }

        $movie = Movie::where('movie_id', $id)->first();

        // check if the video attribute is among the selected attributes for editingand process the file for upload
        if($request->file('fight_video') != null)
        {
            $validateData = Validator::make($request->file('fight_video'), [
                'fight_video' => ['mimes:mp4,mvk', 'max:500000'],
            ]);

            if($validateData->fails())
            {
                return response()->json(['Error'=>$validateData->errors()], 401);
            }
            //remove the old file from the storage
            $oldVideo = public_path('videos/'.$movie->fight_video);
            if(File::exist($oldVideo))
            {
                unlink($oldVideo);
            }

            $video = $request->file('fight_video');
            $videoExtension = $video->getClientOriginalExtension();
            $videoName =  pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
            $videoUploadName = time().'.'.$videoExtension;
            ($video->move(public_path('videos/'), $videoUploadName));
            $movie->fight_video = $videoUploadName;
            $movie->video_real_name = $videoName;
        }
        // check if the cat 1 image attribute is among the selected attributes for editingand process the file for upload
        if($request->file('cat_1_image') != null)
        {
            $validateData = Validator::make($request->file('cat_1_image'), [
                'cat_1_image' => ['image', 'max:1500'],
            ]);

            if($validateData->fails())
            {
                return response()->json(['Error'=>$validateData->errors()], 401);
            }
            $cat1Image = $request->file('cat_1_image');
            $extensionImage1 = $cat1Image->getClientOriginalExtension();
            if(!in_array($extensionImage1, $extensions))
            {
                return response()->json(['Error'=>'The file extension must either be a jpg, jpeg, png, gif'], 401);

            }

            //remove the old file from the storage
            $oldCatImage1 = public_path('image/cat1/'.$movie->cat_1_image);
            if(File::exist($oldCatImage1))
            {
                unlink($oldCatImage1);
            }
            //upload the file
            $cat1ImageName =  pathinfo($cat1Image->getClientOriginalName(), PATHINFO_FILENAME);
            $catUploadName1 = time().'.'.$extensionImage1;
            ($video->move(public_path('images/cat1/'), $catUploadName1));
            $movie->cat_1_image = $catUploadName1;
            $movie->cat_1_real_name = $cat1ImageName;
        }
        // check if the cat 2 image attribute is among the selected attributes for editingand process the file for upload
        if($request->file('cat_2_image') != null)
        {
            $validateData = Validator::make($request->file('cat_2_image'), [
                'cat_2_image' => ['image', 'max:1500'],
            ]);

            if($validateData->fails())
            {
                return response()->json(['Error'=>$validateData->errors()], 401);
            }
            $cat2Image = $request->file('cat_2_image');
            $extensionImage2 = $cat2Image->getClientOriginalExtension();
            if(!in_array($extensionImage2, $extensions))
            {
                return response()->json(['Error'=>'The file extension must either be a jpg, jpeg, png, gif'], 401);

            }

            //remove the old file from the storage
            $oldCatImage1 = public_path('image/cat2/'.$movie->cat_1_image);
            if(File::exist($oldCatImage1))
            {
                unlink($oldCatImage1);
            }
            //upload the file
            $cat2ImageName =  pathinfo($cat2Image->getClientOriginalName(), PATHINFO_FILENAME);
            $catUploadName2 = time().'.'.$extensionImage2;
            ($video->move(public_path('images/cat2/'), $catUploadName2));
            $movie->cat_2_image = $catUploadName1;
            $movie->cat_2_real_name = $cat2ImageName;
        }
        // proccess other form fields
               $movie->movie_title = $request->movie_title;
               $movie->cat_1_name = $request->cat_2_name;
               $movie->cat_2_name = $request->cat_2_name;
               $movie->fight_duration = $request->fight_duration;
               $movie->description = $request->description;
               $movie->user_id = Auth::user()->id;
        //savethe movie object and return ajson response
        if($movie->save())
        {
            return response()->json(['Message'=>'Movie updated succesfully'], 200);
        }
        else
        {
            return response()->json(['Message'=>'Movie could not be updated please try again'], 200);

        }
    }

    //lists of movies by a single user
    public function userMovieLists($id)
    {
        $movies = Movie::where('user_id',$id)->get();
        if($movies->count() >0 )
        {
            return $movies;
        }
        else { return response()->json(["Message"=>"No movie collection for user of id of ".$id], 400);}
    }
    public function singleMovie($id)
    {
        $movie = Movie::where('movie_id',$id)->first();
        if($movie )
        {
            return $movie;
        }
        else { return response()->json(["Message"=>"No movie collection with an id of ".$id], 400);}
    }

    public function createMovie()
    {
        return view('create_movie');
    }
}






//    $movie = new  Movie();
    //    $movie->movie_title = $request->movie_title;
    //    $movie->cat_1_image = $request->cat_1_image;
    //    $movie->cat_2_image = $request->cat_2_image;
    //    $movie->cat_1_name = $request->cat_2_name;
    //    $movie->cat_2_name = $request->cat_2_name;
    //    $movie->fight_duration = $request->fight_duration;
    //    $movie->description = $request->description;
    //    $movie->fight_video = $request->fight_video;
