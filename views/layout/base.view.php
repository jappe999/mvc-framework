<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Hello World to {{ $request->getDomain() }}</title>
        @yield('head')
    </head>
    <body>
        @yield('body')
    </body>
</html>
