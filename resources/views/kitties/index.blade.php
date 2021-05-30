@extends('master')
@section('page-title', 'Kitty List')
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
    .paginator {
        text-align: center;
    }
    .paginator .item.active {
        font-weight: 600;
        color: darkturquoise;
    }
    nav li {
        list-style: none;
        display: inline-block;
    }
    nav li a.active {
        color: turquoise;
    }
    .page-heading {
        clear: both;
    }
    .pagination {
        display: block;
    }
</style>
@endprepend
@section('content')
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <div class="page-heading">
                <h2>List of kitties from thecatapi.com</h2>
                <p>A get request with the provided api key is sent to thecatapi server</p>
                <p>After success you get a random list of kitties</p>
                <p>When sorting by order, a get request is sent to thecatapi.com with coresponding data</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="order">Sort by order</label>
                    <select class="form-control" name="order" id="order">
                        <option {{ request('order') === 'desc' ? 'selected' : '' }} value="desc">Descending</option>
                        <option {{ request('order') === 'asc' ? 'selected' : '' }} value="asc">Ascending</option>
                        <option {{ request('order') === 'rand' ? 'selected' : '' }} value="rand">Random</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mt-8 bg-white dark:bg-gray-800 sm:rounded-lg kitty-grid">
            @foreach($kitties as $kitty)
                <div class="kitty-container">
                    <div style='background-image: url("{{$kitty->url}}"); background-position: center center; background-size: cover; height: 200px'></div>
                </div>
            @endforeach
            <div class="paginator" style="clear: both">
                {{ $kitties->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
@prepend('scripts')
    <script>
        $(document).ready(function() {
            $('#order').on('change', function () {
                let order = $(this).val();
                let base_url = '//' + location.host + location.pathname
                window.location = base_url + '?order=' + order;
            })
        })
    </script>
@endprepend
