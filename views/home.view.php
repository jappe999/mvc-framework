@extend('layout/base.view.php')

@section('body')
    <h1>Hello World!</h1>
    <p>{{ $request->getDomain() }}{{ $request->getPath() }}</p>
@endsection
