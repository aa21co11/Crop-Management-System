<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<?php include('header.php'); ?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AI-Powered Farming Solutions</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <!-- Custom Styling -->
  <style>
    :root {
      --primary: #70a1a1;
      --secondary: #f0f8f7;
      --accent: #4a7c7c;
    }

    body {
      background-color: #fdfefe;
      color: #333;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: var(--accent);
    }

    .navbar-brand,
    .nav-link {
      color: white !important;
    }

    .hero {
      background: linear-gradient(to right, var(--secondary), #ffffff);
      padding: 80px 20px;
      text-align: center;
    }

    .hero h1 {
      color: var(--accent);
      font-size: 3rem;
      font-weight: bold;
    }

    .hero p {
      font-size: 1.3rem;
      color: #555;
    }

    .section-title {
      font-weight: bold;
      text-align: center;
      margin-bottom: 40px;
      color: var(--accent);
      font-size: 2.5rem;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-body i {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .btn-custom {
      background-color: var(--primary);
      color: white;
      border-radius: 30px;
      padding: 8px 20px;
    }

    .btn-custom:hover {
      background-color: var(--accent);
    }

    footer {
      background-color: var(--accent);
      color: white;
      padding: 40px 0;
    }

    footer a {
      color: white;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    .social-icons a {
      font-size: 1.3rem;
      margin-right: 15px;
      color: white;
    }

    @media (max-width: 767px) {
      .hero h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <?php include('nav.php'); ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>AI-Powered Farming Solutions</h1>
      <p class="lead mt-3">A True Farmer's Friend - Smart, Sustainable, Scalable</p>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-5" id="features">
    <div class="container">
      <h2 class="section-title">Our Features</h2>
      <div class="row g-4">
        <?php
        $features = [
          ["icon" => "chart-line", "title" => "Crop Price Prediction", "desc" => "Get accurate price forecasts using market trends and ML analytics.", "link" => "./crop_prediction.php"],
          ["icon" => "seedling", "title" => "Crop Recommendation", "desc" => "Suggests best crops based on soil, weather, and region.", "link" => "./crop_recommendation.php"],
          ["icon" => "flask", "title" => "Fertilizer Suggestion", "desc" => "Recommends suitable fertilizers to improve soil productivity.", "link" => "./fertilizer_recommendation.php"],
          ["icon" => "cloud-rain", "title" => "Rainfall Forecasting", "desc" => "Helps plan irrigation by predicting rainfall in your area.", "link" => "./rainfall_prediction.php"],
          ["icon" => "tachometer-alt", "title" => "Dashboard", "desc" => "View real-time analytics like temperature, rainfall, and crop health in one place.", "link" => "./management.php"],
          // ["icon" => "leaf", "title" => "Yield Prediction", "desc" => "Estimate future crop yield using past performance & environment.", "link" => "./yield_prediction.php"]
        ];

        foreach ($features as $f) {
          echo '
          <div class="col-md-6 col-lg-4">
            <a href="' . $f["link"] . '" class="text-decoration-none text-dark">
              <div class="card h-100 text-center p-3">
                <div class="card-body">
                  <i class="fas fa-' . $f["icon"] . '"></i>
                  <h5 class="card-title mt-3">' . $f["title"] . '</h5>
                  <p class="card-text">' . $f["desc"] . '</p>
                </div>
              </div>
            </a>
          </div>';
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Blog Section -->
  <section class="py-5 bg-light" id="blogs">
    <div class="container">
      <h2 class="section-title">Latest Blogs</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <i class="fas fa-robot"></i>
              <h5 class="card-title mt-3">AI in Farming</h5>
              <p class="card-text">Explore how artificial intelligence is revolutionizing agriculture.</p>
              <a href="blogs/blog1.php" class="btn btn-custom mt-2">Read More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <i class="fas fa-book-open"></i>
              <h5 class="card-title mt-3">Fertilizer Education</h5>
              <p class="card-text">Know the science behind fertilizer usage and smarter farming.</p>
              <a href="blogs/blog2.php" class="btn btn-custom mt-2">Read More</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <i class="fas fa-tree"></i>
              <h5 class="card-title mt-3">Best Crops by Season</h5>
              <p class="card-text">Find the ideal crops to grow depending on the month & region.</p>
              <a href="blogs/blog3.php" class="btn btn-custom mt-2">Read More</a>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

  <!-- Footer -->
  <footer class="text-white">
    <div class="container">
      <div class="row text-center text-md-start">
        <div class="col-md-4 mb-4">
          <h5>About Us</h5>
          <p>We provide AI-based solutions for farmers to make agriculture more efficient, smart, and data-driven.</p>
        </div>
        <div class="col-md-4 mb-4">
          <h5>Contact Us</h5>
          <p>Email: support@aifarming.com</p>
          <p>Phone: +91 98765 43210</p>
          <p>Address: Mumbai, India</p>
        </div>
        <div class="col-md-4 mb-4">
          <h5>Follow Us</h5>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <hr class="bg-white" />
      <p class="text-center mb-0">© 2025 AI-Powered Farming Solutions. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>