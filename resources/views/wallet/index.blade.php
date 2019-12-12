@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Wallets</div>

                    <div class="card-body">
                        @if($wallets->isNotEmpty())
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Wallet address</th>
                                <th>Balance, ETH</th>
                                <th>Created at</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($wallets as $wallet)
                                    <tr>
                                        <td>{{$wallet->id}}</td>
                                        <td>{{$wallet->address}}</td>
                                        <td>{{$wallet->balance}}</td>
                                        <td>{{$wallet->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            @else
                        There is no wallet yet
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let ws = new WebSocket('ws://localhost:7070');
    </script>
@endpush
