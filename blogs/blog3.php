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
                <h1 class="display-4">Predicting Crop Prices with AI</h1>
                <p class="lead">Learn how AI models are used to forecast crop prices and how farmers can leverage this information to make informed decisions.</p>
            </div>
        </header>

        <!-- Blog Content Section -->
        <section class="blog-detail py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="../assets/img/blog3.png" class="img-fluid mb-4" alt="Blog Image">
                        <p class="lead mb-4">Crop price prediction has always been a challenge for farmers, but with the advancement of AI and machine learning, we now have better tools to forecast prices. By analyzing historical price data, weather patterns, and other economic factors, AI models can predict crop prices with a higher degree of accuracy.</p>

                        <h4>The Role of AI in Price Prediction</h4>
                        <p>AI models use a variety of machine learning techniques, including regression and time series analysis, to predict future crop prices. By training the models with historical data, they can learn patterns and trends that help them make predictions. These predictions are valuable for farmers who need to decide when to sell their crops to maximize profits.</p>

                        <h4>Factors Influencing Crop Prices</h4>
                        <p>The prices of crops are influenced by a variety of factors, including weather conditions, demand, global market trends, and government policies. AI models can incorporate all of these factors into their predictions, providing farmers with a more holistic view of what to expect in terms of price fluctuations.</p>

                        <h4>Conclusion</h4>
                        <p>AI-powered crop price prediction systems are a game-changer for the agriculture industry. By leveraging these technologies, farmers can make more informed decisions, reduce risks, and maximize their profits.</p>
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
