@if($count<1)
    <tr>
        <td colspan="{{$columns}}" class="text-center pt-4">
            <span class="badge badge-default">
                {{$message}}
            </span>
        </td>
    </tr>
@endif