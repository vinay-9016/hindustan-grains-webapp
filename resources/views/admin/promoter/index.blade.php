@extends('admin.layouts.app')

@section('content')
    <div class="row card">
        <div class="col-md-12 card-body">
            <h4 class="card-title">Promoter List</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promoters as $promoter)
                        <tr>
                            <td>{{ $promoter->name }}</td>
                            <td>{{ $promoter->email }}</td>
                            <td>{{ $promoter->phone }}</td>
                            <td>{{ $promoter->address }}</td>
                            <td>
                                @if(Auth::user()->usertype == 1)
                                    <a href="{{ route('promoter.edit', $promoter->id) }}"
                                        class="btn btn-outline-primary btn-sm">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
