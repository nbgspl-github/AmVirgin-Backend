@extends('admin.app.app')
@section('content')

@stop

@section('javascript')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.1.0/socket.io.js"></script>
	<script type="application/javascript">
		$(document).ready(() => {
			const socket = io('http://localhost:6001', {path: '/laravel-websockets'});
			socket.on('news', function (data) {
				console.log(data);
				socket.emit('my other event', {my: 'data'});
			});
		});
	</script>
@stop