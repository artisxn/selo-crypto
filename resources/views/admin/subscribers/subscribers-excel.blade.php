<table>
        <thead>
            <tr>
                <th>#Sl</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
        @php $no=1 @endphp
        @foreach($subscribers as $subs)
            <tr>
                <td>{{ $subs->id }}</td>
                <td>{{ $subs->email }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>