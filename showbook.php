
<?php
    require_once("book.class.php");

    $books = new Book();
    $array = $books->get_all_coloumn();
    $keyword = $category ="";
    $age_group=[];
    $string_group = "";
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['search']){
        $keyword =htmlentities( $_POST['searchbar']);
        $category = htmlentities($_POST['genre']);
        $format = htmlentities($_POST['format']);
        $age_group =  isset(($_POST['agegroup'])) ? $_POST['agegroup'] : [] ;
        $string_group = implode(',',$age_group);

        $array=$books->get_all_coloumn($keyword, $category, $format, $string_group);
        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Book</title>
    <link rel="stylesheet" href="show.css">
</head>
<body>
    <div class="heading">

        <a href="addbook.php"><button>Add book</button></a>
    </div>
    <h2>BOOK TABLE</h2>

        <form action="" method="POST">

            
            <label for="genre">Genre</label>
            <select name="genre" id="genre">
                        <option value="">All</option>
                        <option value="romance" <?= (isset($_POST['genre']) && ($_POST['genre']) == "romance") ? "selected " : "" ?>>Romance</option>
                        <option value="action" <?= (isset($_POST['genre']) && ($_POST['genre']) == "documentary") ? "selected" : "" ?>>Documentary</option>
                        <option value="horror" <?= (isset($_POST['genre']) && ($_POST['genre']) == "educational") ? "selected " : "" ?>>Educational</option>
                        <option value="comedy" <?= (isset($_POST['genre']) && ($_POST['genre'])== "fiction") ? "selected " : "" ?>>Fiction</option>
                    </select>

            <label for="format">Format</label>
            <select  id="format" name="format">
                <option value="">All</option>
                <option value="Hardbound" <?=(isset($_POST['format']) && $_POST['format'] == "Hardbound")? "selected = true" : "" ?>>Hardbound</option>
                <option value="Softbound" <?=(isset($_POST['format']) && $_POST['format'] == "Softbound")? "selected = true" : "" ?>>Softbound</option>
            </select>

            <br><br><div class="flex"><label for="agegroup">Age Group</label></div>
                    <input type="checkbox" name="agegroup[]" value="Kids" id="kids" <?= (in_array("Kids",$age_group))? "checked" : "" ?>>           
                    <label for="kids">Kids</label>
                    <input type="checkbox" name="agegroup[]" value="Teens" id="kids" <?= (in_array("Teens",$age_group))? "checked" : "" ?>>           
                    <label for="teens">Teens</label>
                    <input type="checkbox" name="agegroup[]" value="Adult" id="kids" <?= (in_array("Adult",$age_group))? "checked" : "" ?>>           
                    <label for="adult">Adult</label>
              

            <br><br><label for="searchbard">SEARCH</label>
            <input type="text" name="searchbar" value="<?= $keyword ?>">
            <input type="submit" name="search">

        </form>

    <table >
            <tr class="title_row">
                <th>No.</th>
                <th>Barcode</th>
                <th >Title</th>
                <th >Author</th>
                <th>Genre</th>
                <th>Publisher</th>
                <th>Date Published</th>
                <th>Edition</th>
                <th>Copies</th>
                <th>Format</th>
                <th >Age Group</th>
                <th>Rating</th> 
                <th >Description</th>
                <th>Action</th>
            </tr>
            
            <?php
                $counter = 1;
                $bg="";
                foreach($array as $arr){
                if($counter%2==1){
                    $bg="bg";
                }
                else{
                    $bg="";
                }
             ?>       

                <tr class="<?= $bg ?>">
                    <td ><?= $counter ?></td>
                    <td><?= $arr['barcode']?></td>
                    <td ><?= $arr['book_title'] ?></td>
                    <td><?= $arr['book_author'] ?></td>
                    <td><?= $arr['book_genre'] ?></td>
                    <td><?= $arr['book_publisher'] ?></td>
                    <td><?= $arr['publication_date'] ?></td>
                    <td><?= $arr['book_edition'] ?></td>
                    <td><?= $arr['book_copies'] ?></td>
                    <td><?= $arr['book_format'] ?></td>
                    <td><?= $arr['age_group'] ?></td>
                    <td ><?= $arr['book_rating'] ?>/5</td>
                    <td ><?= $arr['book_description'] ?></td>
                    
                    <td>
                        <a href="editbook.php?id=<?= $arr['id']?>" class="edit">Edit</a>
                        <a href="deletebook.php?id=<?= $arr['id']?>" class="delete">Delete</a>
                    </td>
                    
                </tr>

            <?php
                $counter++;
                }
            ?>
    </table>
    
<script src="book.js"></script>
</body>

</html>
