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
<script src="{{ url('src/vendor/chart.js/Chart.min.js') }}"></script>
<script src="{{ url('src/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('src/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ url('src/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ url('src/js/demo/chart-pie-demo.js') }}"></script>
<script src="{{ url('src/js/demo/datatables-demo.js') }}"></script>
<script src="https://kit.fontawesome.com/88197b63d0.js" crossorigin="anonymous"></script>
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
        var text = document.getElementById(clickedBtn.dataset.descRef).innerText;

        copyToClipboard(text);

        clickedBtn.value = "Copied to clipboard";
        clickedBtn.disabled = true;
        clickedBtn.style.color = 'white';
        clickedBtn.style.background = 'green';
    }
</script>
</body>

</html>
