<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cat Lovers</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            ol{
                background: #636b6f;
                padding:1rem 0;
            }
            ol li{
                margin: 2rem;
                color: #fff;
            }
            ol li span{
                color: peru;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Cat Lovers
                </div>

                <div class="links">
                    <p>Note: Use this url to access the various api resources</p>
                    <ol>
                        <li class="red-color">To submit a movie, send a post request to <span>api/movies</span></li>
                        <li class="red-color">To get list of movies, send a get request to  <span>api/movies</span></li>
                        <li class="red-color">To get a single movie, send a get request to <span>api/movie/{id}</span> </li>
                        <li class="red-color">To get a user movies collection, send a get request to <span>api/movies/{user_id}</span></li>
                        <li class="red-color">To update a movie,send a put request to <span>api/movies/{user_id}</span></li>
                        <li class="red-color">To register a user, send a post request to <span>api/register</span></li>
                        <li class="red-color">To login, send a post request to <span>api/login</span></li>
                        <li class="red-color">To get all users, send a get request to <span>api/users</span></li>
                        <li class="red-color">To get user details, send a get request to <span>api/user/{user_id}</span></li>
                        <li class="red-color">To logout, send a get request to <span>api/user/logout</span></li>
                    </ol>
                </div>
            </div>
        </div>
    </body>
</html>
