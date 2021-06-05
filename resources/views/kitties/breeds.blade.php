@extends('master')
@section('page-title', 'Kitty List')
@prepend('styles')
<style>
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
        width: 50%;
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
                    @if($breeds['status'] === 'error')
                        <h4>{{ $breeds['message'] }}</h4>
                    @else
                    <label for="order">Get breed information:</label>
                    <select class="form-control" name="breed" id="breed">
                        @foreach($breeds['data'] as $breed)
                            <option
                                @if(!request()->has('breed'))
                                {{ $breed->id === 'beng' ? 'selected' : '' }}
                                @else
                                {{ request('breed') === $breed->id ? 'selected' : '' }}
                                @endif
                                value="{{ $breed->id }}">{{ $breed->name }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg">
                    @if($breedInfo['status'] === 'error')
                        <h4>{{ $breedInfo['message'] }}</h4>
                    @else
                        @if(empty($breedInfo['data']))
                        <h2>Sorry, there is no information about this kitty breed :(</h2>
                        @else
                        <h2>{{ $breedInfo['data']->name }}</h2>
                        <span class="label">Country: {{ $breedInfo['data']->origin }}</span><span class="label">Lifespan: {{ $breedInfo['data']->life_span }}</span>
                        <p class="description">{{ $breedInfo['data']->description }}</p>
                        <i>Temperament: {{ $breedInfo['data']->temperament }}</i>
                        <ul class="characteristics">
                            <li>Adaptability <span class="rating-label rating{{ property_exists($breedInfo['data'], 'adaptability') ? $breedInfo['data']->adaptability : 'no' }}"></span></li>
                            <li>Affection Level <span class="rating-label rating{{ property_exists($breedInfo['data'], 'affection_level') ? $breedInfo['data']->affection_level : 'no' }}"></span></li>
                            <li>Child Friendly <span class="rating-label rating{{ property_exists($breedInfo['data'], 'child_friendly') ? $breedInfo['data']->child_friendly : 'no' }}"></span></li>
                            <li>Cat Friendly <span class="rating-label rating{{ property_exists($breedInfo['data'], 'cat_friendly') ? $breedInfo['data']->cat_friendly : 'no' }}"></span></li>
                            <li>Dog Friendly <span class="rating-label rating{{ property_exists($breedInfo['data'], 'dog_friendly') ? $breedInfo['data']->dog_friendly : 'no' }}"></span></li>
                            <li>Energy Level <span class="rating-label rating{{ property_exists($breedInfo['data'], 'energy_level') ? $breedInfo['data']->energy_level : 'no' }}"></span></li>
                            <li>Grooming <span class="rating-label rating{{ property_exists($breedInfo['data'], 'grooming') ? $breedInfo['data']->grooming : 'no' }}"></span></li>
                            <li>Health Issues <span class="rating-label rating{{ property_exists($breedInfo['data'], 'health_issues') ? $breedInfo['data']->health_issues : 'no' }}"></span></li>
                            <li>Intelligence <span class="rating-label rating{{ property_exists($breedInfo['data'], 'intelligence') ? $breedInfo['data']->intelligence : 'no' }}"></span></li>
                            <li>Shedding Level <span class="rating-label rating{{ property_exists($breedInfo['data'], 'shedding_level') ? $breedInfo['data']->shedding_level : 'no' }}"></span></li>
                            <li>Social Needs <span class="rating-label rating{{ property_exists($breedInfo['data'], 'social_needs') ? $breedInfo['data']->social_needs : 'no' }}"></span></li>
                            <li>Stranger Friendly <span class="rating-label rating{{ property_exists($breedInfo['data'], 'stranger_friendly') ? $breedInfo['data']->stranger_friendly : 'no' }}"></span></li>
                            <li>Vocalisation <span class="rating-label rating{{ property_exists($breedInfo['data'], 'vocalisation') ? $breedInfo['data']->vocalisation : 'no' }}"></span></li>
                        </ul>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-6">
                @if($breedImages['status'] === 'error')
                <h4>{{ $breedImages['message'] }}</h4>
                @else
                <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid">
                    @foreach($breedImages['data'] as $breedImage)
                        <div class="kitty-container">
                            <div style='background-image: url("{{$breedImage->url}}"); background-position: center center; background-size: cover; height: 200px'></div>
                        </div>
                    @endforeach
                </div>
                @endif
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
