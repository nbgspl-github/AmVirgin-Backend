<table id="datatable" class="table table-hover pr-0 pl-0 " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
	<thead>
	<tr>
		<th>#</th>
		<th>Started At</th>
		<th>Ended At</th>
		<th>Progress</th>
		<th>Status</th>
	</tr>
	</thead>
	<tbody>
	@foreach ($queues as $queue)
		<tr>
			<td>{{($queues->firstItem()+$loop->index)}}</td>
			<td>{{$queue->started_at}}</td>
			<td>{{$queue->completed_at}}</td>
			<td>
				<div class="rounded progress-bar progress-bar-striped progress-bar-animated shadow-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {{$queue->progress}}px; height: 8px;"></div>
			</td>
			<td>{{$queue->status}}</td>
		</tr>
	@endforeach
	</tbody>
</table>