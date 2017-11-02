@extend('layout/base.view.php')

@section('body')
    <h1>Hello World!</h1>
    <form action="/" method="post">
        <h4>Click the submit button to see this page with POST headers</h4>
        <input type="submit" name="" value="Click me!">
    </form>
@endsection
