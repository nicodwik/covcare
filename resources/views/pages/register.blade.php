@extends('layouts.plain')

@section('content')
    <div class="card">

        <div id="form" class="card-body p-4">
            <div class="text-center mb-4">
                <h4 class="text-uppercase mt-0">Form Pendaftaran</h4>
            </div>
            <div class="alert alert-danger" role="alert" style="display: none">
            </div>
            <form action="">
                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input class="form-control" type="text" name="name" id="name" placeholder="Nama lengkap anda" required>
                </div>
                <div class="form-group">
                    <label for="emailaddress">Nomor Handphone</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">+62</span>
                        </div>
                        <input type="number" name="phone" id="phone" class="form-control" placeholder="82xxxxxx">
                      </div>
                </div>
                <div class="form-group">
                    <label for="password">Nomor Induk Kependuudkan (NIK)</label>
                    <input class="form-control" type="number" name="nik" id="nik" required id="password" placeholder="NIK Anda">
                </div>
                <div class="form-group">
                    <label for="address">Alamat lengkap</label>
                    <textarea name="" id="address" cols="30" rows="5" name="address" class="form-control" placeholder="Alamat Anda"></textarea>
                </div>
                <div class="form-group">
                    <label for="address">Pilih Jadwal</label>
                    <select name="" id="time" class="form-control" onchange="checkQuota()" name="time">
                        <option value="" selected disabled>Pilih Jadwal Anda</option>
                        <option value="Senin, 2 Agustus 2021 (12.00 - 15.00)">Senin, 2 Agustus 2021 (12.00 - 15.00)</option>
                        <option value="Selasa, 3 Agustus 2021 (12.00 - 15.00)">Selasa, 3 Agustus 2021 (12.00 - 15.00)</option>
                        <option value="Rabu, 4 Agustus 2021 (12.00 - 15.00)">Rabu 4 Agustus 2021 (12.00 - 15.00)</option>
                    </select>
                </div>
                <div class="result-quota text-center"></div>
                    <div class="alert alert-success pass" role="alert" style="display: none">
                        Kuota tersedia, silahkan melanjutkan pendaftaran
                    </div>
                <div class="form-group mt-4 mb-0 text-center">
                    <button class="btn btn-primary btn-block" id="btn-submit" type="button" disabled onclick="successSubmit()"> Daftar </button>
                </div>
                <div class="form-group mt-2 mb-0 text-center">
                    <a href="{{route('check-page')}}" class="btn btn-outline-white btn-block">Lihat Status Pendaftaran </a>
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
                <a href="{{route('check-page')}}" class="btn btn-outline-white btn-block">Lihat Status Pendaftaran </a>
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
                <a href="{{route('check')}}" class="btn btn-outline-white btn-block">Lihat Status Pendaftaran </a>
            </div>
        </div>
    </div>
@endsection

@section('post-script')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const spinner = `<div class="ml-1 spinner-border spinner-border-sm" role="status" style="color: white;">
                <span class="sr-only">Loading...</span>
                </div>`
        function checkQuota() {
            let pass = $('.pass')
            pass.hide()
            let result = $('.result-quota')
            result.text('Memeriksa Kuota').append('<br>').append(spinner)
            setTimeout(() => {
                result.text('')
                pass.show()
                $('#btn-submit').prop('disabled', false)
            }, 1000);
        }

        function successSubmit(){
            $('#btn-submit').prop('disabled', true).text('').append(spinner)
            axios.post('{{route('register')}}', {
                _token: '{{csrf_token()}}',
                name: $('#name').val(),
                phone: '+62' + $('#phone').val(),
                nik: $('#nik').val(),
                address: $('#address').val(),
                time: $('#time').val(),
            }).then(response => {
                if(response.data.status == 'success') {
                    $('.alert-danger').hide()
                    $('#form').hide()
                    $('#success-message').show()
                } else if(response.data.status == 'error'){
                    $('.alert-danger').hide()
                    $('#form').hide()
                    $('#failure-message').show()
                } else if(response.data.status == 'validation-error'){
                    $('.alert-danger').show().text(response.data.message)
                    $('#btn-submit').text('Daftar')
                } else {
                    alert(response.data.message)
                }
            }).catch(e => {
                console.log(e)
                alert(e.message)
            })
        }
    </script>
@endsection