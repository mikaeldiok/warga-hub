@php
    $status = \Modules\Mkstarter\Services\MkdumService::prepareStatusFilter();
    \Log::debug($status);
    $firstStat = reset($status);
@endphp
<script>

    $(document).ready(function(){
        $(document).on('click', 'button.choose-mkdum', function(e){
            var ele = $(this);  
            var fireAjax = false;

            if(ele.hasClass('with-warning') && ele.hasClass('btn-danger')){
                Swal.fire({
                title: "PERHATIAN!!!",
                text: "Membatalkan siswa dengan status 'tidak tersedia' dapat membuat anda tidak bisa memilih siswa ini hingga statusnya 'tersedia' lagi setelah memuat ulang halaman ini.",
                type: "warning",
                showCancelButton: true,
                cancelButtonColor: "#dc3545",
                confirmButtonColor: "#a8a8a8",
                confirmButtonText: "Ya, saya ingin membatalkan siswa ini",
                closeOnConfirm: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        callPickMkdum(ele);
                    }
                });
            }else{
                callPickMkdum(ele);
            }

        });

        function callPickMkdum(ele){
                $.ajax({
                    type: "POST",
                    url: '{{route("frontend.bookings.pickMkdum")}}',
                    data: {
                        "corporation_id" : "{{auth()->user()->corporation->id ?? 0}}",
                        "mkdum_id" : ele.attr("data-id"),
                        "status" : "{{$firstStat}}",
                        "_method":"POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                    if(response.isPicked){

                        ele.removeClass( 'btn-success');
                        ele.addClass( 'btn-danger');
                        ele.html( 'BATAL');

                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                        })

                        Toast.fire({
                        icon: 'success',
                        title: 'Siswa ditambahkan ke daftar anda'
                        })
                    }else{

                        ele.removeClass( 'btn-danger');
                        ele.addClass( 'btn-success');
                        ele.html( 'PILIH');
                        
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                        })

                        Toast.fire({
                        icon: 'warning',
                        title: response.message
                        })
                    }
                    },
                    error: function (request, status, error,dudu) {
                        console.log("error nih");
                        console.log(request);
                        console.log(status);
                        console.log(error);
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                        })

                        Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan! Silakan coba beberapa saat lagi.'
                        })
                    }
                });
            }
    });
</script>