@foreach($attributes as $attribute)
	<option value="{{$attribute->id()}}">{{$attribute->name()}}</option>
@endforeach