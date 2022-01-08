<?php
    include_once 'QueryDB.php';
    use Curl\Curl;
    
    if(isset($_GET["id"]) && $_GET["id"] != 0){
      
        $chapter_id = $_GET["id"];
        $data = get_image($chapter_id);

        $image_link_array = json_decode($data['image_link'],true);
        $image_path_array = json_decode($data['image_path'],true);
        $image_download_fail = json_decode($data['image_download_fail'],true);
        $image_download_firsttime = $data['image_download_firsttime'];

        download_create_folder($chapter_id);

        if($image_download_firsttime == 0 || $image_download_firsttime == null){
            download_file($image_link_array, $image_path_array);
        }else if($image_download_fail != null){
            download_file($image_link_array, $image_path_array, 1);
        }else{
            echo "deo can download, cai nay co roi!!";
        }
   
    }else{
        echo "deo co gi";
    }

    // function download_test(){
    //     // Initialize a file URL to the variable
    //     $url = 
    //     'https://cm.blazefast.co/86/c6/86c6b13b79e79fc92c3ef37581e2b259.jpg';
    
    //     // Initialize the cURL session
    //     $ch = curl_init($url);
        
    //     // Initialize directory name where
    //     // file will be save
    //     $dir = './res';
        
    //     // Use basename() function to return
    //     // the base name of file
    //     $file_name = basename($url);
        
    //     // Save file into file location
    //     $save_file_loc = $dir . $file_name;
        
    //     // Open file
    //     $fp = fopen($save_file_loc, 'wb');
        
    //     // It set an option for a cURL transfer
    //     curl_setopt($ch, CURLOPT_FILE, $fp);
    //     curl_setopt($ch, CURLOPT_HEADER, 0);
        
    //     // Perform a cURL session
    //     curl_exec($ch);
        
    //     // Closes a cURL session and frees all resources
    //     curl_close($ch);
        
    //     // Close file
    //     fclose($fp);
    // }

    function download_file($url, $path, $check=0){
        $curl = new Curl();
        // echo 'start download: '.$url."\n";
        $curl->setConnectTimeout(10000);
        $curl->setTimeout(10000);

        global $chapter_id;
        $dataFail = array();
        if($check == 0){
            for($i = 0; $i < count($url); ++$i) {
                $result = $curl->download($url[$i], $path[$i]);
                if($result){
                    echo 'download success: '.$url[$i]."\n";
                }else{
                    echo 'download fail: '.$url[$i]."\n";
                    echo 'Error download: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
                    $dataFail[] = $i;
                }
            }
            update_image($chapter_id, $dataFail);
        }else{
            for($i = 0; $i < count($image_download_fail); ++$i) {
                $result = $curl->download($url[$image_download_fail[$i]], $path[$image_download_fail[$i]]);
                if($result){
                    echo 'download success: '.$url[$image_download_fail[$i]]."\n";
                }else{
                    echo 'download fail: '.$url[$image_download_fail[$i]]."\n";
                    echo 'Error download: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
                    $dataFail[] = $image_download_fail[$i];
                }
            }
            update_image($chapter_id, $dataFail);
        }
        $curl->close();
    }

    function download_create_folder($chapter_id){
        $image_download_folder_path = get_image_folder($chapter_id);
        $folder_path = $image_download_folder_path[0]; 
        var_dump($folder_path);
        if (is_dir($folder_path)){
            echo "This folder did exist: ". $folder_path." ";
            return;
        } 
        mkdir($folder_path, 0777, true);
        echo 'create folder: '.$folder_path. "\n";
    }
?>