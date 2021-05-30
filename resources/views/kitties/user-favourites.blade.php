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
</style>
@endprepend
@section('content')
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        <div class="page-heading">
            <h2>A list of your favourite kitty images from the thecatapi.com</h2>
            <p>A list of random kitties is displayed with a get request</p>
            <p>You can add photos to favourites</p>
            <p>After clicking the add to favourite button, a post request is sent to thecatapi.com</p>
            <p>You can also remove a picture from favourites</p>
            <p>After clicking on the remove button, a delete request is sent to thecatapi.com</p>
        </div>
    </div>
    <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid new">
        <div class="new-kitties">
            <h3>Choose your favourite</h3>
            @foreach($kittyImages as $kittyImage)
            <div class="kitty-container">
                <div style='background-image: url("{{$kittyImage->url}}"); background-position: center center;
                    background-size: cover; height: 200px; display: flex'>
                    <a class="action-button favourite-item" href="javascript:void(0)"
                       data-url="{{ route('kitties.image.favourite.add', ['id' => $kittyImage->id]) }}"><i class="fa fa-star"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div id="button-container">
        <a href="javascript:void(0)" class="random btn btn-primary"><i class="fa fa-sync-alt"></i> New Kitties</a>
    </div>
    <div id="message"></div>
    <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid favourites">
        <div class="favourites-content">
            @if($favourites === null)
                <h3>You don't have any favourite kitty images yet.</h3>
            @else
                <h3>Your favourite kitties</h3>
                @foreach($favourites as $favourite)
                <div class="kitty-container">
                    <div style='background-image: url("{{$favourite->image->url}}"); background-position: center center;
                        background-size: cover; height: 200px; display: flex'>
                        <a class="action-button delete-item" href="javascript:void(0)"
                           data-type="favourite"
                           data-url="{{ route('kitties.user.images.delete', ['id' => $favourite->id]) }}"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                @endforeach
                <div class="paginator" style="clear: both">
                    {{ $favourites->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
