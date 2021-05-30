@extends('master')
@section('page-title', 'Kitty List')
@prepend('styles')
<style>
    .bg-white {
        padding: 20px;
    }
    .characteristics {
        list-style: none;
        padding: 0;
    }
    .rating-label {
        display: inline-block;
        margin-left: 10px;
        width: 80px;
    }
    .rating1 {
        content: url("{{ asset('assets/images/rating/rating_1.png') }}");
    }
    .rating2 {
        content: url("{{ asset('assets/images/rating/rating_2.png') }}");
    }
    .rating3 {
        content: url("{{ asset('assets/images/rating/rating_3.png') }}");
    }
    .rating4 {
        content: url("{{ asset('assets/images/rating/rating_4.png') }}");
    }
    .rating5 {
        content: url("{{ asset('assets/images/rating/rating_5.png') }}");
    }
    .ratingno:after {
        content: "N/A";
    }
    .label {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: darkturquoise;
        color: #fff;
        margin-right: 10px;
        font-size: 14px;
    }
    .description {
        margin-top: 1rem;
    }
    .kitty-grid {
        overflow: auto;
    }
    .kitty-container {
        display: inline-block;
        box-sizing: border-box;
        width: 50%;
        float: left;
        padding: 10px;
    }
</style>
@endprepend
@section('content')
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <div class="page-heading">
                <h2>List of kitty breeds from thecatapi.com</h2>
                <p>This page displays the kitty breeds</p>
                <p>After selecting a breed, an api call is sent to thecatapi.com, then the breed's info is displayed</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="order">Get breed information:</label>
                    <select class="form-control" name="breed" id="breed">
                        @foreach($kittyBreeds as $kittyBreed)
                            <option
                                @if(!request()->has('breed'))
                                {{ $kittyBreed->id === 'beng' ? 'selected' : '' }}
                                @else
                                {{ request('breed') === $kittyBreed->id ? 'selected' : '' }}
                                @endif
                                value="{{ $kittyBreed->id }}">{{ $kittyBreed->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg">
                    @if(is_null($kittyBreedInfo))
                    <h2>Sorry, there is no information about this kitty breed :(</h2>
                    @else
                    <h2>{{ $kittyBreedInfo->name }}</h2>
                    <span class="label">Country: {{ $kittyBreedInfo->origin }}</span><span class="label">Lifespan: {{ $kittyBreedInfo->life_span }}</span>
                    <p class="description">{{ $kittyBreedInfo->description }}</p>
                    <i>Temperament: {{ $kittyBreedInfo->temperament }}</i>
                    <ul class="characteristics">
                        <li>Adaptability <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'adaptability') ? $kittyBreedInfo->adaptability : 'no' }}"></span></li>
                        <li>Affection Level <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'affection_level') ? $kittyBreedInfo->affection_level : 'no' }}"></span></li>
                        <li>Child Friendly <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'child_friendly') ? $kittyBreedInfo->child_friendly : 'no' }}"></span></li>
                        <li>Cat Friendly <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'cat_friendly') ? $kittyBreedInfo->cat_friendly : 'no' }}"></span></li>
                        <li>Dog Friendly <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'dog_friendly') ? $kittyBreedInfo->dog_friendly : 'no' }}"></span></li>
                        <li>Energy Level <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'energy_level') ? $kittyBreedInfo->energy_level : 'no' }}"></span></li>
                        <li>Grooming <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'grooming') ? $kittyBreedInfo->grooming : 'no' }}"></span></li>
                        <li>Health Issues <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'health_issues') ? $kittyBreedInfo->health_issues : 'no' }}"></span></li>
                        <li>Intelligence <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'intelligence') ? $kittyBreedInfo->intelligence : 'no' }}"></span></li>
                        <li>Shedding Level <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'shedding_level') ? $kittyBreedInfo->shedding_level : 'no' }}"></span></li>
                        <li>Social Needs <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'social_needs') ? $kittyBreedInfo->social_needs : 'no' }}"></span></li>
                        <li>Stranger Friendly <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'stranger_friendly') ? $kittyBreedInfo->stranger_friendly : 'no' }}"></span></li>
                        <li>Vocalisation <span class="rating-label rating{{ property_exists($kittyBreedInfo, 'vocalisation') ? $kittyBreedInfo->vocalisation : 'no' }}"></span></li>
                    </ul>
                    @endif
                </div>
            </div>
            <div class="col-6">
                <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid">
                    @foreach($kittyImages as $kittyImage)
                        <div class="kitty-container">
                            <div style='background-image: url("{{$kittyImage->url}}"); background-position: center center; background-size: cover; height: 200px'></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@prepend('scripts')
    <script>
        $(document).ready(function() {
            $('#breed').on('change', function () {
                let breed = $(this).val();
                let base_url = '//' + location.host + location.pathname
                window.location = base_url + '?breed=' + breed;
            })
        })
    </script>
@endprepend
