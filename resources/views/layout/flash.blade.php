{{--@if (Session::has('flash_notification.message'))--}}
    {{--<div class="alert text-center alert-{{ Session::get('flash_notification.level') }}">--}}
        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}

        {{--{{ Session::get('flash_notification.message') }}--}}
    {{--</div>--}}
{{--@endif--}}

{{--@if (count($errors) > 0)--}}
    {{--<div class="alert alert-danger">--}}
        {{--<ul>--}}
            {{--@foreach ($errors->all() as $error)--}}
                {{--<li>{{ $error }}</li>--}}
            {{--@endforeach--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--@endif--}}


@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
    @else
        <div class="alert alert-{{ Session::get('flash_notification.level') }} alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>
                @if ("danger" == Session::get('flash_notification.level'))
                    <i class="icon fa fa-ban"></i>
                @elseif("success" == Session::get('flash_notification.level'))
                    <i class="icon fa fa-check"></i>
                @else
                    <i class="icon fa fa-{{ Session::get('flash_notification.level') }}"></i>
                @endif
                {{ ucwords(Session::get('flash_notification.level')) }}!</h4>
            {{ Session::get('flash_notification.message') }}
        </div>
    @endif
@endif