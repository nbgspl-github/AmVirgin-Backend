@if($count<1)
    <tr>
        <td colspan="{{$columns}}" class="text-center pt-4">
            <span class="">
                {{$message}}
            </span>
        </td>
    </tr>
@endif