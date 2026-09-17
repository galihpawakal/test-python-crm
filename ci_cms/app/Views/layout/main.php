<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Simulation</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #0d6efd;
            --brand-accent: #ffc107;
        }
        
        body { 
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Navbar Styling */
        .navbar {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .navbar-brand .brand-cms { color: #ffffff; font-weight: 700; }
        .navbar-brand .brand-store { color: var(--brand-accent); font-weight: 700; }
        
        /* Animated Underline on Nav Links */
        .nav-link {
            position: relative;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.3s;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: var(--brand-accent);
            transition: all 0.3s ease-in-out;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 100%;
        }

        /* Generic Card Styling */
        .card-lift {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
            border-radius: 16px;
        }
        .card-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }
        
        /* Main Content Area */
        main {
            flex-grow: 1;
        }

        /* Footer */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #eaeaea;
            padding: 2rem 0;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <i class="bi bi-grid-3x3-gap-fill text-warning"></i>
                <span><span class="brand-cms">CMS</span><span class="brand-store">Store</span></span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item"><a class="nav-link px-3" href="/products">Products</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="/transactions">Transactions</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="/users">Users</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-5">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0" role="alert" data-aos="fade-down">
                <i class="bi bi-check-circle-fill me-2"></i> <strong>Success!</strong> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 border-0" role="alert" data-aos="fade-down">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center text-muted small">
            <p class="mb-1">&copy; <?= date('Y') ?> CMS Store Simulation. Built with CodeIgniter 4.</p>
            <p class="mb-0">Designed with <i class="bi bi-heart-fill text-danger"></i> using Bootstrap 5</p>
        </div>
    </footer>

    <!-- Global Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-trash3-fill me-2"></i> Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 fs-5 text-center">
                    Are you sure you want to delete this item? <br>
                    <span class="text-muted fs-6">This action cannot be undone.</span>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 shadow-sm" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4 shadow-sm">Yes, Delete It</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animation
        AOS.init({
            once: true,
            offset: 50,
            duration: 600,
            easing: 'ease-out-cubic',
        });

        function confirmDelete(url) {
            document.getElementById('confirmDeleteBtn').href = url;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
</body>
</html>
