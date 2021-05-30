@extends('master')
@section('page-title', 'Favourite Kitties')
@prepend('styles')
    <style>
        .kitty-grid {
            padding: 20px;
        }
        .kitty-container {
            width: 25%;
            display: inline-block;
            float: left;
            box-sizing: border-box;
            padding: 10px;
        }
        #button-container {
            margin-top: 20px;
            text-align: center;
            float: left;
            width: 100%;
        }
        .kitty-grid, #message {
            float: left;
            width: 100%;
        }
        #message {
            margin-top: 2rem;
        }
        .label {
            font-size: 30px;
            position: absolute;
            left: 5px;
        }
        .label .fa-thumbs-up {
            color: green;
        }
        .label .fa-thumbs-down {
            color: red
        }
    </style>
@endprepend
@section('content')
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <div class="page-heading">
                <h2>A list of images from the thecatapi.com for you to vote</h2>
                <p>A list of random kitties is displayed with a get request</p>
                <p>You can vote on photos</p>
                <p>After clicking the vote button, a post request is sent to thecatapi.com</p>
                <p>You can also remove a picture from your votes</p>
                <p>After clicking on the remove button, a delete request is sent to thecatapi.com</p>
            </div>
        </div>
        <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid new">
            <div class="new-kitties">
                <h3>Place your votes now.</h3>
                @foreach($kittyImages as $kittyImage)
                    <div class="kitty-container">
                        <div style='background-image: url("{{$kittyImage->url}}"); background-position: center center;
                            background-size: cover; height: 200px; display: flex'>
                            <a class="action-button vote like" href="javascript:void(0)"
                               data-url="{{ route('kitties.image.vote', ['id' => $kittyImage->id]) }}"><i class="fa fa-thumbs-up"></i></a>
                            <a class="action-button vote dislike" href="javascript:void(0)"
                               data-url="{{ route('kitties.image.vote', ['id' => $kittyImage->id]) }}"><i class="fa fa-thumbs-down"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="button-container">
            <a href="javascript:void(0)" class="random btn btn-primary"><i class="fa fa-sync-alt"></i> New Kitties</a>
        </div>
        <div id="message"></div>
        <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid votes">
            <div class="votes-content">
                @if($votes === null)
                    <h3>You have not voted yet.</h3>
                @else
                    <h3>Your vote history</h3>
                    @foreach($votes as $vote)
                        <div class="kitty-container">
                            <div style='background-image: url("{{ asset('assets/images/no_preview.png') }}"); background-position: center center;
                                background-size: cover; height: 200px; display: flex; position: relative'>
                                <span class="label"><i class="fa fa-thumbs-{{ $vote->value === 1  ? 'up' : 'down' }}"></i></span>
                                <a class="action-button delete-item" href="javascript:void(0)"
                                   data-type="vote"
                                   data-url="{{ route('kitties.user.images.delete', ['id' => $vote->id]) }}"><i class="fa fa-trash"></i></a>
                            </div>
                            <p>Sorry, json response does not include image path for image id: {{ $vote->image_id }}</p>
                        </div>
                    @endforeach
                    <div class="paginator" style="clear: both">
                        {{ $votes->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
