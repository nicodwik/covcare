@extends('layouts.plain')

@section('content')
    <div class="card">

        <div id="form" class="card-body p-4">
            <div class="text-center mb-4">
                <h4 class="text-uppercase mt-0">Login Admin</h4>
            </div>
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{Session::get('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form action="{{route('login')}}" id="login" method="POST">
                @csrf
                <div class="form-group">
                    <label for="fullname">Email</label>
                    <input class="form-control" type="email" name="email" id="fullname" placeholder="Email anda" required>
                </div>
                <div class="form-group">
                    <label for="fullname">Password</label>
                    <input class="form-control" type="password" name="password" id="fullname" placeholder="Password anda" required>
                </div>
                <div class="form-group mt-4 mb-0 text-center">
                    <button class="btn btn-primary btn-block" id="btn-submit" type="button" onclick="login()"> Masuk </button>
                </div>
            </form>

        </div> <!-- end card-body -->

        <div class="card-body" id="success-message" style="display: none">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Pendaftaran Berhasil!</h4>
                <p>Mohon menunggu konfirmasi dalam waktu 1x24 Jam, informasi akan disampaikan melalui Whatsapp</p>
                <hr>
                <p class="mb-0">Selalu terapkan 3M (mangan, mangan, masker)</p>
            </div>
            <div class="form-group mt-2 mb-0 text-center">
                <a href="{{route('check')}}" class="btn btn-outline-secondary btn-block">Lihat Status Pendaftaran </a>
            </div>
        </div>

        <div class="card-body" id="failure-message" style="display: none">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Pendaftaran Gagal!</h4>
                <p>Quota pendaftaran telah habis, mohon memilih jadwal lain</p>
                <hr>
                <p class="mb-0">Selalu terapkan 3M (mangan, mangan, masker)</p>
            </div>
            <div class="form-group mt-2 mb-0 text-center">
                <a href="{{route('check')}}" class="btn btn-outline-secondary btn-block">Lihat Status Pendaftaran </a>
            </div>
        </div>
    </div>
@endsection

@section('post-script')
    <script>
       function login() {
            $('#btn-submit').text('').append(`<div class="spinner-border spinner-border-sm" role="status" style="color: white;">
                <span class="sr-only">Loading...</span>
                </div>`)
            $('#login').submit()
       }
    </script>
@endsection