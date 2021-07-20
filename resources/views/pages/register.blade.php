@extends('layouts.plain')

@section('content')
    <div class="card">

        <div id="form" class="card-body p-4">
            <div class="text-center mb-4">
                <h4 class="text-uppercase mt-0">Form Pendaftaran</h4>
            </div>
            <div class="alert alert-danger alert-message" role="alert" style="display: none">
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
                    <select name="" id="schedule" class="form-control" onchange="checkQuota()" name="schedule">
                        <option value="" selected disabled>Pilih Jadwal Anda</option>
                        @foreach ($data->schedules as $item)
                            <option value="{{$item->id}}">{{$item->date}} ({{$item->time}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="result-quota text-center"></div>
                <div class="alert alert-success pass" role="alert" style="display: none">
                    <b>Kuota tersedia, silahkan melanjutkan pendaftaran</b>
                </div>
                <div class="alert alert-danger not-pass" role="alert" style="display: none">
                    <b>Kuota habis, silahkan pilih jadwal lain</b>
                </div>
                <div class="form-group mt-4 mb-0 text-center">
                    <button class="btn btn-primary btn-block" id="btn-submit" type="button" disabled onclick="successSubmit()"> Daftar </button>
                </div>
                <div class="form-group mt-2 mb-0 text-center">
                    <a href="{{route('check.page')}}" class="btn btn-outline-white btn-block">Lihat Status Pendaftaran </a>
                </div>
            </form>

        </div> <!-- end card-body -->

        <div class="card-body" id="success-message" style="display: none">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Pendaftaran Berhasil!</h4>
                <p><b>Mohon menunggu konfirmasi dalam waktu 1x24 Jam, informasi akan disampaikan melalui Whatsapp</b></p>
                <hr>
                <p class="mb-0">Selalu terapkan 3M (mangan, mangan, masker)</p>
            </div>
            <div class="form-group mt-2 mb-0 text-center">
                <a href="{{route('check.page')}}" class="btn btn-outline-white btn-block">Lihat Status Pendaftaran </a>
            </div>
        </div>

        <div class="card-body" id="failure-message" style="display: none">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Pendaftaran Gagal!</h4>
                <p><b>Kuota pendaftaran telah habis, mohon memilih jadwal lain</b></p>
                <hr>
                <p class="mb-0">Selalu terapkan 3M (mangan, mangan, masker)</p>
            </div>
            <div class="form-group mt-2 mb-0 text-center">
                <a href="{{route('check.page')}}" class="btn btn-outline-white btn-block">Lihat Status Pendaftaran </a>
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
            let notPass = $('.not-pass')
            let result = $('.result-quota')
            pass.hide()
            notPass.hide()
            result.text('Memeriksa Kuota').append('<br>').append(spinner)

            axios.get('{{route('quota.check')}}?schedule_id=' + $('#schedule').val())
                .then(response => {
                    setTimeout(() => {
                        if(response.data.status == 'success') {
                            result.text('')
                            pass.show()
                            $('#btn-submit').prop('disabled', false)
                        } else if(response.data.status == 'error'){
                            result.text('')
                            notPass.show()
                            $('#btn-submit').prop('disabled', true)
                        } else {
                            alert(response.data.message)
                        } 
                    }, 1000);
                })
                .catch(e => {
                    alert(e.message)
                })
        }

        function successSubmit(){
            $('#btn-submit').prop('disabled', true).text('').append(spinner)
           
            axios.post('{{route('register')}}', {
                _token: '{{csrf_token()}}',
                name: $('#name').val(),
                phone: '+62' + $('#phone').val(),
                nik: $('#nik').val(),
                address: $('#address').val(),
                schedule: $('#schedule').val(),
            }).then(response => {
                let success =  $('#success-message')
                let failure =  $('#failure-message')
                let alert =  $('.alert-message')
                let form =  $('#form')
                if(response.data.status == 'success') {
                    alert.hide()
                    form.hide()
                    success.show()
                } else if(response.data.status == 'error'){
                    alert.hide()
                    form.hide()
                    failure.show()
                } else if(response.data.status == 'validation-error'){
                    success.hide()
                    failure.hide()
                    $('#schedule').val('')
                    alert.show().text(response.data.message)
                    $('#btn-submit').text('Daftar')
                } else {
                    console.log(response.data.message)
                }
            }).catch(e => {
                console.log(e)
                alert(e.message)
            })
        }
    </script>
@endsection