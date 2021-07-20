@extends('layouts.plain')

@section('content')
    <div class="card">

        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h4 class="text-uppercase mt-0">Status Pendaftaran</h4>
            </div>
            <div class="alert alert-danger" role="alert" style="display: none">
            </div>
            <form action="#" method="get" id="form-check">
                <div class="form-group">
                    <label for="">Nomor HP</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">+62</span>
                        </div>
                        <input type="number" name="phone" id="phone" class="form-control" placeholder="82xxxxxx" value="{{request()->phone}}">
                      </div>
                </div>
                
                
                <div class="form-group mt-2 mb-0 text-center">
                    <button class="btn btn-primary btn-block" id="btn-submit" type="button" onclick="checkRegistrant()"> Cek Status </button>
                </div>
            </form>

            <div id="status-result" style="display: none">
                <p class="mb-1">Nama Lengkap</p>
                <h4 class="mt-0 mb-3 name-result"></h4>
            
                <p class="mb-1">Nomor Handphone</p>
                <h4 class="mt-0 mb-3 phone-result"></h4>
            
                <p class="mb-1">Nomor Induk Kependuudkan (NIK)</p>
                <h4 class="mt-0 mb-3 nik-result"></h4>
            
                <p class="mb-1">Alamat lengkap</p>
                <h4 class="mt-0 mb-3 address-result"></h4>
            
                <p class="mb-1">Jadwal Vaksinasi</p>
                <h4 class="mt-0 mb-3 time-result"></h4>
            
                <p class="mb-1">Tempat Vaksinasi</p>
                <h4 class="mt-0 mb-3 place-result"></h4>
            
                <p class="mb-1">Status</p>
                <div class="alert mt-0 mb-3 status-result" role="alert">
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card" id="qrcode-result">
                    </div>
                    <p class="text-center">Tunjukkan QR Code kepada panitia sebagai bukti kehadiran</p>
                </div>
                <div class="form-group mt-2 mb-0 text-center">
                    <button class="btn btn-primary btn-block" id="btn-qrcode" data-toggle="collapse" data-target="#collapseExample" disabled>
                    </button>
                </div>
                <div class="form-group mt-2 mb-0 text-center">
                    <button class="btn btn-outline-white btn-block btn-back" onclick="back()" type="button">Kembali </button>
                </div>
            </div>

        </div> <!-- end card-body -->
       
    </div>
@endsection

@section('post-script')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const spinner = `<div class="spinner-border spinner-border-sm" role="status" style="color: white;">
                <span class="sr-only">Loading...</span>
                </div>`
        function checkRegistrant() {
            let btn = $('#btn-submit')
            let form = $('#form-check')
            let result = $('#status-result')
            let qrcode =  $('#btn-qrcode')
            btn.text('').append(spinner)
            axios.get('{{route('check')}}?phone=' + $('#phone').val())
                .then(response => {
                    if(response.data.status == 'success') {
                        $('.alert-danger').hide()
                        let {registrant, schedule, status, color} = response.data.data
                        form.hide()
                        result.show()
                        $('.name-result').empty().append(`<b>${registrant.name}</b>`)
                        $('.phone-result').empty().append(`<b>${registrant.phone}</b>`)
                        $('.nik-result').empty().append(`<b>${registrant.nik.substr(0,4)}xxxxxxxxxxx</b>`)
                        $('.address-result').empty().append(`<b>${registrant.address}</b>`)
                        $('.time-result').empty().append(`<b>${schedule.date} (${schedule.time})</b>`)
                        $('.place-result').empty().append(`<b>${schedule.address}</b>`)
                        $('.status-result').addClass(color).empty().append(`<b>${status}</b>`)
                        $('#qrcode-result').empty().append(`<img src="${registrant.qr_code}" alt="" height="100%" width="100%">`)
                        qrcode.prop('disabled', true).empty().append(spinner)
                        setTimeout(() => {
                            qrcode.prop('disabled', false).empty().text('Lihat QR Code')
                        }, 3000);
                    } else {
                        $('.alert-danger').show().text(response.data.message)
                        $('#btn-submit').text('Cek Status')
                        $('#phone').val('')
                    }
                })
                .catch(e => {
                    alert(e.message)
                })
        }
        function back() {
            $('#form-check').show()
            $('#status-result').hide()
            $('#btn-submit').text('Cek Status')
            $('#phone').val('')
        }
      
    </script>
@endsection