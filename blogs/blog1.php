<?php
// Start the session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Change this to your login page's actual name if different
    exit();
}

// If the user is logged in, display the blog content
?>

<!DOCTYPE html>
<html lang="en">

<?php include('../header.php'); ?>

<head>
    <!-- Add Bootstrap CSS, FontAwesome for icons, and custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styling for Header */
        .jumbotron {
            background-color: #A9BFA8; /* Soft orange */
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
                height: auto; /* Adjust height on small screens */
            }
        }
    </style>
</head>

<body class="bg-light" id="top">

    <!-- Navigation -->
    <?php include('../nav.php'); ?>


    <div class="wrapper">

        <!-- Header Section -->
        <header class="jumbotron text-center fade-in">
            <div class="container">
                <h1 class="display-4">How AI is Revolutionizing Agriculture</h1>
                <p class="lead">Explore how machine learning and AI are changing the farming industry by improving crop yields and reducing costs.</p>
            </div>
        </header>

        <!-- Blog Content Section -->
        <section class="blog-detail py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img src="../assets/img/leonardo1.jpg" class="img-fluid mb-4" alt="Blog Image">
                        <p class="lead mb-4">The application of Artificial Intelligence (AI) in agriculture has revolutionized the way farmers grow crops, manage resources, and predict outcomes. By leveraging data and machine learning algorithms, AI systems can predict crop yields, identify potential risks such as pests or diseases, and provide recommendations for optimal planting schedules. These innovations enable farmers to make more informed decisions, reduce costs, and increase efficiency.</p>

                        <h4>AI-Powered Solutions for Agriculture</h4>
                        <p>AI is applied in various forms across the agriculture industry, from predictive models that forecast weather patterns and crop diseases to machine learning algorithms that analyze soil conditions and crop performance. Through satellite imagery, AI systems can assess vast agricultural areas, identifying areas that require attention, such as soil quality or water availability. This data-driven approach significantly enhances decision-making capabilities, reducing waste, and improving overall farm productivity.</p>

                        <h4>Predicting Crop Yields</h4>
                        <p>One of the most exciting advancements is AI's ability to predict crop yields based on factors like weather forecasts, soil conditions, and market trends. With predictive models, farmers can accurately estimate the quantity and quality of their harvest, helping them plan ahead for storage, transportation, and sale. This helps in reducing waste by providing an accurate estimate of the produce expected.</p>

                        <h4>Crop Disease Detection</h4>
                        <p>AI also plays a significant role in the early detection of crop diseases. With machine learning models that analyze plant images, farmers can quickly identify signs of disease and take preventive measures. This is done by comparing current images with a database of known plant diseases and spotting symptoms early, ensuring the farmer can take action before the disease spreads.</p>

                        <h4>Conclusion</h4>
                        <p>In conclusion, AI technologies are transforming agriculture into a more data-driven, efficient, and sustainable industry. With the continuous advancement of AI tools, farmers now have more power than ever to optimize their farming practices, increase yields, and contribute to food security globally.</p>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <?php include('../footer.php'); ?>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
