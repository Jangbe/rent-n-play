import Swal from "sweetalert2";

export const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    timer: 3000,
    timerProgressBar: true,
    showConfirmButton: false,
    icon: 'success'
})

export const Confirm = Swal.mixin({
    title: 'Apakah kamu yakin?',
    text: 'Kamu tidak bisa mengembalikanya lagi!',
    showCancelButton: true,
    cancelButtonText: 'Batal',
    cancelButtonColor: 'var(--bs-primary)',
    showConfirmButton: true,
    confirmButtonText: 'Ya',
    confirmButtonColor: 'var(--bs-danger)'
})
