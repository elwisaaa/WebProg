<?php

require_once("functions.php");
require_once("book.class.php");

$age_group = [];    
$title = $author = $genre = $publisher = $date_publish = $edition = $copies = $format =  $description = $barcode = "";
$titleErr = $age_groupErr = $authorErr = $genreErr = $publisherErr = $date_publishErr = $editionErr = $copiesErr = $formatErr = $ratingErr = $barcodeErr = "";
$rating = "1";
$bookOBJ = new Book();

$current_date = new DateTime();
$current_date_format = $current_date->format('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $barcode = clean_input($_POST['barcode']);
    $title = clean_input($_POST['title']);
    $author = clean_input($_POST['author']);
    $genre = clean_input(isset($_POST['genre']) ? clean_input($_POST['genre']) : "");
    $publisher = clean_input($_POST['publisher']);
    $date_publish = clean_input($_POST['date']);
    $edition = clean_input($_POST['edition']);
    $copies = clean_input($_POST['copies']);
    $format = (isset($_POST['format']) ? clean_input($_POST['format']) : "");
    $age_group = isset($_POST['agegroup']) ? $_POST['agegroup'] : [];
    $age_group = array_map('clean_input', $age_group);
    $rating = clean_input($_POST['rating']);
    $description = clean_input($_POST['description']);

    
    if (empty($barcode)) {
        $barcodeErr = "Barcode is Required";
    } else {
        if ($bookOBJ->barcode_unique($barcode)) {
            $barcodeErr = "Barcode $barcode already exists";
        }
    }

    if (empty($title)) {
        $titleErr = "Title is required";
    }
    if (empty($author)) {
        $authorErr = "Name is required";
    }
    if (empty($genre)) {
        $genreErr = "Genre is required";
    }
    if (empty($publisher)) {
        $publisherErr = "Publisher is required";
    }
    if (empty($date_publish)) {
        $date_publishErr = "Publication Date is required";
    } else {
        $date_publish_input = new DateTime($date_publish);
        $date_publish_format = $date_publish_input->format('m-d-Y');
        if ($date_publish_input > $current_date) {
            $date_publishErr = "$date_publish_format is Exceeding the Current date";
        }
    }
    if (empty($edition)) {
        $editionErr = "Book Edition is required";
    }
    if (!empty($edition) && $edition < 1) {
        $editionErr = "Edition cannot be below 0 or empty";
    }
    if (empty($copies)) {
        $copiesErr = "Book copies is required";
    }
    if (!empty($copies) && $copies < 1) {
        $copiesErr = "Book copies cannot be below 0 or empty";
    }
    if (empty($format)) {
        $formatErr = "Format is required";
    }
    if (empty($age_group)) {
        $age_groupErr = "Age Group is required";
    }
    if (empty($rating)) {
        $ratingErr = "Rating is required";
    }

    if (empty($barcodeErr) && empty($titleErr) && empty($authorErr) && empty($genreErr) && empty($publisherErr) && empty($date_publishErr)
        && empty($editionErr) && empty($copiesErr) && empty($formatErr) && empty($age_groupErr) && empty($ratingErr)) {

        $bookOBJ->barcode = $barcode;
        $bookOBJ->title = $title;
        $bookOBJ->author = $author;
        $bookOBJ->genre = $genre;
        $bookOBJ->publisher = $publisher;
        $bookOBJ->publication_date = $date_publish;
        $bookOBJ->edition = $edition;
        $bookOBJ->copies = $copies;
        $bookOBJ->format = $format;
        $bookOBJ->age_group = implode(",", $age_group);
        $bookOBJ->rating = $rating;
        $bookOBJ->description = $description;

        if ($bookOBJ->add()) {
            header('location: showbook.php');
        } else {
            echo "Something went wrong When adding a new book";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="stylesadd.css">
</head>
<body>
    <section class="add_section">
        
    <div class="heading">
            <h1>Book record</h1>
            <a href="showbook.php"><button>View Book</button></a>
        </div>
        <h2>Add Book</h2>

        <form action="" method="POST">
            <h4>Basic Information</h4>
            <div class="basic_info_holder">
                <div class="label_input">
                    <div class="flex"><label for="barcode">Barcode</label><span class="error"><?= $barcodeErr ?></span></div>
                    <input type="text" name="barcode" id="barcode" placeholder="Enter Barcode" value="<?= $barcode ?>">   
                </div>

                <div class="label_input">
                    <div class="flex"><label for="title">Book Title</label><span class="error"><?= $titleErr ?></span></div>
                    <input type="text" name="title" id="title" placeholder="Enter Book Title" value="<?= $title ?>">
                </div>

                <div class="label_input">
                    <div class="flex"><label for="author">Author's Name</label><span class="error"><?= $authorErr ?></span></div>
                    <input type="text" name="author" id="author" placeholder="Enter Author's Name" value="<?= $author ?>">
                </div>   

                <div class="label_input">
                    <div class="flex"><label for="genre">Genre</label><span class="error"><?= $genreErr ?></span></div>
                    <select name="genre" id="genre">
                        <option value="">--Genre--</option>
                        <option value="romance" <?= (isset($genre) && $genre == "romance") ? "selected" : "" ?>>Romance</option>
                        <option value="action" <?= (isset($genre) && $genre == "documentary") ? "selected" : "" ?>>Documentary</option>
                        <option value="horror" <?= (isset($genre) && $genre == "educational") ? "selected" : "" ?>>Educational</option>
                        <option value="comedy" <?= (isset($genre) && $genre == "fiction") ? "selected" : "" ?>>Fiction</option>
                    </select>
                </div>
            </div>
            <h4>Publication Details</h4>
            <div class="basic_info_holder">
                <div class="label_input">
                    <div class="flex"><label for="publisher">Publisher</label><span class="error"><?= $publisherErr ?></span></div>
                    <input type="text" name="publisher" id="publisher" placeholder="Enter Publisher Company" value="<?= $publisher ?>">          
                </div>
                <div class="label_input">
                    <div class="flex"><label for="date">Publication Date</label><span class="error"><?= $date_publishErr ?></span></div>
                    <input type="date" name="date" id="date" value="<?= $date_publish ?>">
                </div>
                
                <div class="label_input">
                    <div class="flex"><label for="edition">Edition</label><span class="error"><?= $editionErr ?></span></div>
                    <input type="number" name="edition" id="edition" placeholder="Enter Edition number" value="<?= $edition ?>">
                </div>
            </div>
            <div class="all_group">
                <div class="groupo">
                    <h4>Availability</h4>
                    <div class="basic_info_holder2">
                        <div class="label_input">
                            <div class="flex"><label for="copies">Number of Copies</label><span class="error"><?= $copiesErr ?></span></div>
                            <input type="number" name="copies" id="copies" placeholder="Enter number of Copies" value="<?= $copies ?>">
                        </div>
                        
                        <div class="label_input">
                            <div class="flex"><label for="format">Format</label><span class="error"><?= $formatErr ?></span></div>
                            <div class="flex2">
                                <input type="radio" name="format" value="Hardbound" id="hardbound" <?= (isset($format) && $format == "Hardbound") ? "checked" : "" ?>>
                                <label for="hardbound">Hardbound</label>
                            </div>
                            <div class="flex2">
                                <input type="radio" name="format" value="Softbound" id="softbound" <?= (isset($format) && $format == "Softbound") ? "checked" : "" ?>>
                                <label for="softbound">Softbound</label>
                            </div>    
                        </div>
                    </div>
                </div>
                <div class="groupo">
                    <h4>Target Audience</h4>
                    <div class="basic_info_holder2">
                        <div class="label_input">
                            <div class="flex"><label for="agegroup">Age Group</label><span class="error"><?= $age_groupErr ?></span></div>
                            <div class="flex2">                        
                                <div class="flex2">
                                    <input type="checkbox" name="agegroup[]" value="Kids" id="kids" <?= in_array("Kids", $age_group) ? "checked" : "" ?>>           
                                    <label for="kids">Kids</label>
                                </div> 
                                <div class="flex2">
                                    <input type="checkbox" name="agegroup[]" value="Teens" id="teens" <?= in_array("Teens", $age_group) ? "checked" : "" ?>>
                                    <label for="teens">Teens</label>
                                </div>
                                <div class="flex2">
                                    <input type="checkbox" name="agegroup[]" value="Adult" id="adult" <?= in_array("Adult", $age_group) ? "checked" : "" ?>>
                                    <label for="adult">Adult</label>
                                </div>
                            </div>
                        </div>
                        <div class="label_input">
                            <div class="flex"><label for="rating">Book Rating</label><span class="error"><?= $ratingErr ?></span></div>
                            <input type="range" name="rating" id="rating" min="1" max="5" value="<?= $rating ?>">
                        </div>
                    </div>
                </div>   
            </div>
            <h4>Additional Information</h4>
            <div class="basic_info_holder3">
                <div class="label_input">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Describe this book (optional)"><?= $description ?></textarea>
                </div>
            </div>
            <div class="submit">
                <input type="submit" id="submit" value="Save Book" class="submit">
            </div>
        </form>
    </section>        
</body>
</html>
