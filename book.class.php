<?php

require_once "database.php";

class Book {

    public $id = "";
    public $barcode = "";
    public $title = "";
    public $author = "";
    public $genre = "";
    public $publisher = "";
    public $publication_date = "";
    public $edition = "";
    public $copies = "";
    public $format = "";
    public $age_group = "";
    public $rating = "";
    public $description = "";

    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        $addquery = "INSERT INTO book (barcode, book_title, book_author, book_genre, book_publisher, publication_date, 
                                    book_edition, book_copies, book_format, age_group, book_rating, book_description) 
                                    VALUES (:barcode, :title, :author, :genre, :publisher, :publication_date, :edition, :copies, :format, :age_group, :rating, :description);";
        $prepquery = $this->db->connect()->prepare($addquery);
        $prepquery->bindParam(':barcode', $this->barcode);
        $prepquery->bindParam(':title', $this->title);
        $prepquery->bindParam(':author', $this->author);
        $prepquery->bindParam(':genre', $this->genre);
        $prepquery->bindParam(':publisher', $this->publisher);
        $prepquery->bindParam(':publication_date', $this->publication_date);
        $prepquery->bindParam(':edition', $this->edition);
        $prepquery->bindParam(':copies', $this->copies);
        $prepquery->bindParam(':format', $this->format);
        $prepquery->bindParam(':age_group', $this->age_group);
        $prepquery->bindParam(':rating', $this->rating);
        $prepquery->bindParam(':description', $this->description);

        if ($prepquery->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update($book_id) {
        $edit_query = "UPDATE book SET barcode = :barcode, book_title = :title, book_author = :author,
                                        book_genre = :genre, book_publisher = :publisher, publication_date = :pub_date, book_edition = :edition,
                                        book_copies = :copies, book_format = :format, age_group = :group, book_rating = :rating,
                                        book_description = :description
                                        WHERE book_id = :id";
        $prepquery = $this->db->connect()->prepare($edit_query);
        $prepquery->bindParam(':id', $book_id);
        $prepquery->bindParam(':barcode', $this->barcode);
        $prepquery->bindParam(':title', $this->title);
        $prepquery->bindParam(':author', $this->author);
        $prepquery->bindParam(':genre', $this->genre);
        $prepquery->bindParam(':publisher', $this->publisher);
        $prepquery->bindParam(':pub_date', $this->publication_date);
        $prepquery->bindParam(':edition', $this->edition);
        $prepquery->bindParam(':copies', $this->copies);
        $prepquery->bindParam(':format', $this->format);
        $prepquery->bindParam(':group', $this->age_group);
        $prepquery->bindParam(':rating', $this->rating);
        $prepquery->bindParam(':description', $this->description);

        if ($prepquery->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function get_all_coloumn($keyword = '', $genre = '', $format = '', $age_group = "") {
        $array = (isset($age_group) ? explode(',', $age_group) : []);
        $array[0] = (isset($array[0]) ? $array[0] : "");
        $array[1] = (isset($array[1]) ? $array[1] : "");
        $array[2] = (isset($array[2]) ? $array[2] : "");

        $choose = "SELECT * FROM book WHERE status = 1 AND (book_title LIKE '%' :keyword '%' OR book_author LIKE '%' :keyword '%' OR book_genre LIKE '%' :keyword '%' OR book_publisher LIKE '%' :keyword '%' OR book_format LIKE '%' :keyword '%' OR age_group LIKE '%' :keyword '%' OR book_description LIKE '%' :keyword '%')   AND (book_genre LIKE '%' :genre '%') AND (book_format LIKE '%' :format '%')
                                AND (age_group LIKE '%' :arr1 '%' :arr2 '%' :arr3 '%')
                                ORDER BY book_title ASC;";
        $query = $this->db->connect()->prepare($choose);
        $query->bindParam(':keyword', $keyword);
        $query->bindParam(':genre', $genre);
        $query->bindParam(':format', $format);
        $query->bindParam(':arr1', $array[0]);
        $query->bindParam(':arr2', $array[1]);
        $query->bindParam(':arr3', $array[2]);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
            return $data ? $data : [];
        }

        return [];
    }

    function delete($id) {
        $query = "UPDATE book SET status = 0 WHERE status = 1 AND id = :id;";
        $prep_query = $this->db->connect()->prepare($query);
        $prep_query->bindParam(':id', $id);
        return $prep_query->execute();
    }

    function get_row($book_id) {
        $query = "SELECT * FROM book WHERE status = 1 AND id = :id;";
        $prep_query = $this->db->connect()->prepare($query);
        $prep_query->bindParam(':id', $book_id);

        if ($prep_query->execute()) {
            $data = $prep_query->fetch();
            return $data ? $data : [];
        }
        return [];
    }

    function wrong_barcode($id, $barcode) {
        $query = "SELECT barcode FROM book WHERE status = 1 AND id = :id;";
        $prep_query = $this->db->connect()->prepare($query);
        $prep_query->bindParam(':id', $id);

        if ($prep_query->execute()) {
            $data = $prep_query->fetch();
            if ($data['barcode'] != $barcode && $this->is_barcode_unique($barcode)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function barcode_unique($barcode) {
        $query = "SELECT barcode FROM book WHERE status = 1 AND barcode = :barcode;";
        $prep_query = $this->db->connect()->prepare($query);
        $prep_query->bindParam(':barcode', $barcode);

        if ($prep_query->execute()) {
            $data = $prep_query->fetch();
            return $data ? true : false;
        }
    }
}
