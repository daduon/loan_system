import Swal from 'sweetalert2';

export class ToastService {
      toastConfirm(icon?: any, title?: any, text?: any, showCancelButton?: any, confirmButtonText?: any, cancelButtonText?: any) {
            Swal.fire({
                  title: title,
                  text: text,
                  icon: icon,
                  showCancelButton: showCancelButton,
                  confirmButtonText: confirmButtonText,
                  cancelButtonText: cancelButtonText
            }).then((result) => {
                  if (result.isConfirmed) {
                        Swal.fire(
                              'Deleted!',
                              'Your imaginary file has been deleted.',
                              'success'
                        )
                  } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                              'Cancelled',
                              'Your imaginary file is safe :)',
                              'error'
                        )
                  }
            })
      }

      toastMessage(icon?: any, title?: any, text?:any) {
            Swal.fire({
                  icon: icon,
                  title: title,
                  text:text,
                  showConfirmButton: false,
                  timer: 2000
            })
      }
}