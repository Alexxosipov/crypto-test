@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Add wallet</div>

                    <div class="card-body">
                        <form action="{{route('wallet.create')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">ETH address</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address" autofocus>

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Добавить
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/web3@1.2.4/dist/web3.min.js"></script>
    <script>
    const web3 = new Web3('wss://kovan.infura.io/ws/v3/e2a05c9a3fed4565bc05f93839d526e7/');
    const subscription = web3.eth.subscribe('logs', {
        address: '0x75e1864609B8386b2739687108d40070f546D721'
    }, function(error, result) {
        if (!error)
            console.log(result);
    })
        .on("data", function(log) {
            console.log(log);
        })
        .on("changed", function(log) {});


    </script>
@endpush
