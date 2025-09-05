       <?php require __DIR__ . '/../../config/app.php'; ?>


       <!-- Back to Top -->
       <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
       </div>

       <!-- JavaScript Libraries -->
       <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/chart/chart.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/easing/easing.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/waypoints/waypoints.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/owlcarousel/owl.carousel.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/tempusdominus/js/moment.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/tempusdominus/js/moment-timezone.min.js"></script>
       <script src="<?= $baseUrl ?>/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

       <!-- Template Javascript -->
       <script src="<?= $baseUrl ?>/js/main.js"></script>
       </body>

       </html>

       <?php unset($_SESSION['success'], $_SESSION['fail']); ?>