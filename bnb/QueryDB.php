<?php
    include_once 'Config.php';

    use Medoo\Medoo;

    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'limabnb',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]);

    
    function insert_store($store){
        $store_name = $store['store_name'];
        $store_alternate_name = $store['store_alternate_name'];
        $store_release = $store['store_release'];
        $store_status  = $store['store_status'];
        $store_author  = $store['store_author'];
        //$store_artist  = $store['store_artist'];
        $store_reading_direction = $store['store_reading_direction'];
        $store_link = $store['store_link'];
        $store_category_id = $store['store_category_id'];
        $store_picture = $store['store_picture'];

        $sql = "INSERT INTO store (store_category_id, store_name, store_alternate_name,store_author,
        store_picture, store_status, store_reading_direction, store_release, store_link)".
               " SELECT '$store_category_id','$store_name','$store_alternate_name','$store_author','$store_picture',
               '$store_status','$store_reading_direction','$store_release','$store_link' FROM DUAL".
               " WHERE NOT EXISTS (SELECT * FROM store WHERE store_link = '$store_link') LIMIT 1";

        // $sql = "INSERT INTO store (store_name, store_link) VALUES ('$store_name','$store_link')";


        global $database;
        $database->query($sql);

        $data = $database->query("SELECT * FROM store WHERE store_link = '$store_link'")->fetch();
        return $data;
    }

    function insert_chapter($store_id, $chapter){
        $chapter_name = $chapter['chapter_name'];
        $chapter_link = $chapter['chapter_link'];
        $chapter_date = $chapter['chapter_date'];

        $sql = "INSERT INTO chapter (chapter_name, chapter_date, chapter_link, store_id)".
               " SELECT '$chapter_name', '$chapter_date', '$chapter_link', '$store_id' FROM DUAL".
               " WHERE NOT EXISTS (SELECT * FROM chapter WHERE 'chapter_link' = '$chapter_link') LIMIT 1";


        global $database;
        $database->query($sql);
        
        // echo $sql;

        $data = $database->query("SELECT * FROM chapter WHERE chapter_link = '$chapter_link'")->fetch();
        return $data;
    }


    function insert_image($chapter_id, $image){
        $image_link_json = json_encode($image['image_link']);
        $image_path_json = json_encode($image['image_path']);
        $image_download_folder_path = $image['image_download_folder_path'];

        $sql = "INSERT INTO image (image_link, image_path, chapter_id, image_download_folder_path)".
             "VALUES ('$image_link_json', '$image_path_json', '$chapter_id', '$image_download_folder_path')";

        // echo $sql;

        global $database;
        $database->query($sql);

        // return $sql;
    }
    
     function update_image($chapter_id, $image_download_fail){
         if($image_download_fail!=null){
            $image_download_fail_json = json_encode($image_download_fail);

            $sql = "UPDATE image SET image_download_fail = '$image_download_fail_json', image_download_firsttime = 1
            WHERE chapter_id = '$chapter_id'";
         }else{
            $sql = "UPDATE image SET image_download_firsttime = 1
            WHERE chapter_id = '$chapter_id'";
         }

        global $database;
        $database->query($sql);

        // return $sql;
    }


    function get_image($chapter_id){
        $data = array(); 
        $sql = "SELECT image_link, image_path, image_download_fail, image_download_firsttime FROM `image` WHERE chapter_id= '$chapter_id'";

        // echo $sql;

        global $database;
        $data = $database->query($sql)->fetch();

        return $data;
    }

    function get_image_folder($chapter_id){
        $data = array(); 
        $sql = "SELECT image_download_folder_path FROM `image` WHERE chapter_id= '$chapter_id'";

        // echo $sql;

        global $database;
        $data = $database->query($sql)->fetch();

        return $data;
    }
?>
