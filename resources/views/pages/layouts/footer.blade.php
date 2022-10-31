<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; <i class="fas fa-thumbs-up"></i> Approver</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->
<!--update-->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>



<!-- Bootstrap core JavaScript-->
<script src="{{ url('src/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ url('src/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ url('src/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ url('src/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
{{-- <script src="{{ url('src/vendor/chart.js/Chart.min.js') }}"></script> --}}
<script src="{{ url('src/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('src/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
{{-- <script src="{{ url('src/js/demo/chart-area-demo.js') }}"></script> --}}
{{-- <script src="{{ url('src/js/demo/chart-pie-demo.js') }}"></script> --}}
<script src="{{ url('src/js/demo/datatables-demo.js') }}"></script>
<script src="https://kit.fontawesome.com/88197b63d0.js" crossorigin="anonymous"></script>
@php
$user = Auth()->user();

$total_payable = App\Models\Brid::where('user_id', $user->id)
    ->where('status', 'Approved')
    ->sum('rate');
$paid = App\Models\Payment::where('user_id', $user->id)
    ->where('status', 'Approved')
    ->sum('taka');

$balance = $paid - $total_payable;

$payments_date = DB::table('payments')
    ->where('user_id', $user->id)
    ->orderBy('created_at', 'DESC')
    ->first(['created_at']);

if ($user->is_admin == '0') {
    $last_pay_day = strtotime($payments_date->created_at ?? '2022-01-01');

    $now = time();
    $your_date = $last_pay_day;
    $datediff = $now - $your_date;
    $day_diff = round($datediff / (60 * 60 * 24));

    if ($day_diff > 7 && $balance < -500) {
        echo '<script>
            ';
            echo '$(document).ready(function() {
            $("#myModal").modal("show");
            });
            ';
            echo '
        </script>';

    }
}
if ($user->is_admin == '0') {

    if ($balance < -1000) {
        echo '<script>
            ';
            echo '$(document).ready(function() {
            $("#myModal2").modal("show");
            });
            ';
            echo '
        </script>';
    }
}
@endphp

<script>
    function copyToClipboard(text) {
        if (window.clipboardData && window.clipboardData.setData) {
            // IE specific code path to prevent textarea being shown while dialog is visible.
            return clipboardData.setData("Text", text);

        } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            try {
                return document.execCommand("copy"); // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }

    function status(clickedBtn) {
        var descRef = clickedBtn.dataset.descRef;

        copyToClipboard(descRef);

        clickedBtn.value = "Copied to clipboard";
        clickedBtn.disabled = true;
        clickedBtn.style.color = 'white';
        clickedBtn.style.background = 'green';
    }
    $(document).ready(function() {
        $('#example').DataTable({
            iDisplayLength: 100,
            order: [
                [2, 'desc'],
                [0, 'desc']
            ],
        });

        $('#example-user').DataTable({
            order: [
                [2, 'desc'],
                [0, 'desc']
            ],
        });

        // Activate the 'selected' class
        // on clicking the rows
        $('#example tbody').on('click', 'tr', function() {
            $(this).toggleClass('selected');
        });

        //on click exampleApprove button
        $('#exampleApprove').on('click', function() {
            const ids = [];
            $('#example tbody tr.selected').each(function() {
                ids.push($(this).find('span').attr('id'));
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('approveAll') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ids': ids,
                },
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        });
        //on click exampleApprove button
        $('#exampleReject').on('click', function() {
            const ids = [];
            $('#example tbody tr.selected').each(function() {
                ids.push($(this).find('span').attr('id'));
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('rejectAll') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ids': ids,
                },
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        });
        //on click exampleDelete button
        $('#exampleDelete').on('click', function() {
            const ids = [];
            $('#example tbody tr.selected').each(function() {
                ids.push($(this).find('span').attr('id'));
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('deleteAll') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ids': ids,
                },
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        });
    });
</script>
</body>

</html>
