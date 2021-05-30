@extends('master')
@section('page-title', 'Your Uploaded Images')
@prepend('styles')
<style>
    .bg-white {
        padding: 20px;
        float: left;
        width: 100%;
    }
    .form-inline .form-control-file {
        width: auto;
        margin-left: 10px;
    }
    .items-center {
        margin: 0 auto;
    }
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
    .upload.btn-primary {
        color: #fff !important;
        cursor: pointer;
    }
    #spinner {
        display: none;
    }
</style>
@endprepend
@section('content')
    <div id="message"></div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <div class="page-heading">
                <h2>A list from your uploaded images to the thecatapi.com</h2>
                <p>Your uploaded photos are fetched by a get request to server</p>
                <p>You can upload new images to thecatapi.com with the form</p>
                <p>After submiting, a post request is sent to thecatapi.com.</p>
                <p>If the picture is uploaded, a get request is sent to server to analyze the uploaded picture</p>
                <p>After analysis, the picture is approved or rejected to be displayed publicly</p>
                <p>You can also delete the images you uploaded.</p>
                <p>After pressing on the delete button a delete request is sent to server</p>
            </div>
        </div>
    </div>
    <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid">
        @if($userImages === null)
            <h3>You haven't uploaded any images to thecatapi.com</h3>
        @else
            @foreach($userImages as $userImage)
                <div class="kitty-container">
                    <div style='background-image: url("{{$userImage->url}}"); background-position: center center;
                        background-size: cover; height: 200px; display: flex'>
                        <a class="action-button delete-item" href="javascript:void(0)"
                           data-type="user_image"
                           data-url="{{ route('kitties.user.images.delete', ['id' => $userImage->id]) }}"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            @endforeach
            <div class="paginator" style="clear: both">
                {{ $userImages->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
    <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg">
        <div class="form-inline">
            <div class="flex justify-center items-center">
                <div class="form-group">
                    <label for="file">Upload kitty image:</label>
                    <input type="file" accept="mage/x-png,image/gif,image/jpeg" class="form-control-file" id="file">
                </div>
                <a data-url="{{ route('kitties.user.images.upload') }}" class="btn btn-primary upload">Upload Image</a>
                <div id="spinner">
                    <img src="{{ asset('assets/images/spinner.gif') }}">
                </div>
            </div>
        </div>
    </div>
@endsection
