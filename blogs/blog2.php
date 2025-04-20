<?php
// Start the session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// If the user is logged in, display the blog content
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>

<head>
    <!-- Add Bootstrap CSS, FontAwesome for icons, and custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styling for Header */
        .jumbotron {
            background-color: #A9BFA8;
            color: white;
            padding: 80px 0;
            border-radius: 15px;
        }

        .jumbotron h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .jumbotron p {
            font-size: 1.4rem;
            font-style: italic;
        }

        /* Simple Animations for Elements */
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            background-color: #2d6a4f;
            color: white;
            border-radius: 30px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-custom:hover {
            background-color: #1b4d3e;
            transform: scale(1.05);
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .jumbotron h1 {
                font-size: 2.5rem;
            }

            .img-fluid {
                height: auto;
            }
        }
    </style>
</head>

<body class="bg-light" id="top">

    <!-- Navigation -->
    <?php include('nav.php'); ?>

    <div class="wrapper">

        <!-- Header Section -->
        <header class="jumbotron text-center fade-in">
            <div class="container">
                <h1 class="display-4">Understanding Fertilizer Recommendation Systems</h1>
                <p class="lead">An in-depth look at how fertilizer recommendation systems work and how they assist farmers in achieving better crop health.</p>
            </div>
        </header>

        <!-- Blog Content Section -->
        <section class="blog-detail py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="../assets/img/blog1.jpg" class="img-fluid mb-4" alt="Blog Image">
                        <p class="lead mb-4">Fertilizer recommendations are essential for optimizing crop growth and yield. By using soil health data, crop type, and environmental conditions, these systems help farmers determine the most suitable fertilizers for their crops. With this precision, farmers can enhance crop health, reduce costs, and avoid over-fertilizing, which can harm the environment.</p>

                        <h4>How Fertilizer Recommendation Systems Work</h4>
                        <p>Fertilizer recommendation systems collect data on soil conditions such as nitrogen, phosphorus, potassium levels, and pH. These systems analyze the data using algorithms and provide precise recommendations based on crop types and growth stages. The goal is to ensure that crops receive the nutrients they need without excess or deficiency, leading to higher yields and healthier crops.</p>

                        <h4>Environmental Impact of Fertilizer Recommendations</h4>
                        <p>By optimizing fertilizer use, these systems also contribute to environmental sustainability. Over-fertilization can lead to runoff, contaminating water sources and harming local ecosystems. Fertilizer recommendation systems help reduce this risk by providing accurate amounts needed for each crop, minimizing the environmental impact while maximizing crop output.</p>

                        <h4>Conclusion</h4>
                        <p>Fertilizer recommendation systems are a crucial tool for modern farmers. By using data to make informed decisions, farmers can ensure that their crops receive the right nutrients at the right time, increasing yields while protecting the environment.</p>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <?php require("footer.php"); ?>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
