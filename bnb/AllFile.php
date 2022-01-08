<?php
    include 'lib/Medoo.php';
     
    include 'lib/Curl/CaseInsensitiveArray.php';
    include 'lib/Curl/Curl.php';
    include 'lib/Curl/MultiCurl.php';

    include 'lib/DiDom/Document.php';
    include 'lib/DiDom/Element.php';
    include 'lib/DiDom/Query.php';

    use Curl\Curl;
    use DiDom\Document;
    use DiDom\Element;

    use Medoo\Medoo;

    define('BASE_URL', 'https://www.mangareader.net');

    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'bnb',
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]);

    set_time_limit(100000);

    $url = "https://www.mangareader.net/naruto";
    

    if(get_data($url, $content)){

        $details = get_info($content);
        $store = array();
        $store['store_name'] = $details[1];
        $store['store_alternate_name'] = $details[2];
        $store['store_release'] = $details[3];
        $store['store_status'] = $details[4];
        $store['store_author'] = $details[5];
        $store['store_artist'] = $details[6];
        $store['store_reading_direction'] = $details[7];
        $store['store_category_id'] = json_encode($details[8]);
        $store['store_link'] = $url;
        $store['store_picture'] = $details[9];
        $data = insert_store($store);

        $store_id = $data['store_id'];

        echo $content;

      //  save_all_chapter($store_id,$content);

        // echo BASE_URL;
    }else{
        echo "NONONOO";
    }
       
    function insert_store($store){
        $store_name = $store['store_name'];
        $store_alternate_name = $store['store_alternate_name'];
        $store_release = $store['store_release'];
        $store_status  = $store['store_status'];
        $store_author  = $store['store_author'];
        $store_artist  = $store['store_artist'];
        $store_reading_direction = $store['store_reading_direction'];
        $store_link = $store['store_link'];
        $store_category_id = $store['store_category_id'];
        $store_picture = $store['store_picture'];

        $sql = "INSERT INTO store (store_category_id, store_name, store_alternate_name, store_artist,store_author,
        store_picture, store_status, store_reading_direction, store_release, store_link)".
               " SELECT '$store_category_id','$store_name','$store_alternate_name','$store_artist','$store_author','$store_picture',
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

        $data = $database->query("SELECT * FROM chapter WHERE chapter_link = '$chapter_link'")->fetch();
        return $data;
    }


    function insert_image($chapter_id, $image){
        $image_link_json = json_encode($image['image_link']);
        $image_path_json = json_encode($image['image_path']);

        $sql = "INSERT INTO image (image_link, image_path, chapter_id)".
             "VALUES ('$image_link_json', '$image_path_json', '$chapter_id')";

        // echo $sql;

        global $database;
        $database->query($sql);

        // return $sql;
    }

    // echo download_file("//i3.imggur.net/shingeki-no-kyojin/7/shingeki-no-kyojin-2078787.jpg","data/test/3.jpg");



    function download_file($url, $path){
            $curl = new Curl();
            // echo 'start download: '.$url."\n";
            $curl->setConnectTimeout(10000);
            $curl->setTimeout(10000);

            $result = $curl->download($url, $path);

            if($result){
                echo 'download success: '.$url."\n";
                return 1;
            }else{
                echo 'download fail: '.$url."\n";
                echo 'Error download: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
                return 0;
            }
            $curl->close();
    }

    function get_data($url, &$content){
        // $header = array();
        // $header[] = 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        // $header[] = 'accept-encoding: gzip, deflate, br';
        // $header[] = 'accept-language: en-US,en;q=0.9';
        // $header[] = 'cookie: __cfduid=d8de1490e4d969e8e733b703485ba483f1597976183; panel-fb-comment=fb-comment-title-show; _ga=GA1.2.1391424987.1597976185; _gid=GA1.2.1160333547.1597976185; content_server=server1; BB_plg=yn|pm; bidswitch_last_time=1598064007879; rekmob_props_610542=%7B%22date%22%3A1598063908169%2C%22rekJs%22%3A%7B%22rekmob_ad_unit_type%22%3A1%2C%22rekmob_native_type%22%3Anull%2C%22rekmob_ad_width%22%3A300%2C%22rekmob_fixed_cpm%22%3A0%2C%22rekmob_network_ids%22%3A%22crt_id%3D0%22%2C%22rekmob_ad_unit%22%3A%22451a4bc2ba9f4d3385d09e4837ff4492%22%2C%22rekmob_app_type%22%3A1%2C%22rekmob_ad_height%22%3A250%2C%22region_id%22%3A610542%7D%2C%22countryCode%22%3A%22VN%22%2C%22cookieTime%22%3A1598064009012%7D; ci_session=ndhE0awHTvfQj%2Fr2potFAHkPaI75O%2BPa%2B1CDWtUhPFzY3me2OH5tgyrSlBX5zoImZZyCxd%2BIhNwd%2Fr5iKGwNN0HkwBdagimpx5aSuSegQRG17vTmQuwzZ%2BOREecR5xfFL0vMQmobgA9A9TUlRXzvcwE9IUMWFt8YkEvIl47bltfHp8r31gTHDo3YhqZ9FZctEDGKSL%2FL4y6%2BFh1AlmpLJDj%2BtnEjcCXiiUkkKdfW3B%2BpG7GGmfmpJbjUU0IKe%2BkiC5oT72ZgGWkwPIg710TR6kjqilsMHYcX6liGvmgzs4Ma67jRoQPbnzOk7R54Llt3iJHrbtdK4MYCOrgvl3S%2Be7T5%2B9hQRZSwxWLQyzvHFasfwFvCkKvde6LcAaF9fiPLOt7rHRBFy2vSOh49Zu4F4a0mb%2BkCd7hTSZan6wC2oelH%2BvBpMPV%2FzuYlI3NN%2FTMM3Yl8xNpmSdvX9LYyfzyFyw%3D%3Dc55b96e97ec841e52ff70fa580ac4e5c990fefd9; content_lazyload=off; rekmob_last_seen_451a4bc2ba9f4d3385d09e4837ff4492=1598064501196; cto_bundle=ZYpgn19SQ3RvcCUyRndqRTVjbzFZYWglMkZ0ZTVHZUJaQVpJMDhqSGVZcSUyQkw1bjlBZFZRM2hBczYyY1JSdUh4NEdKY1RWdSUyQnQzd1lTTGlsYlRQdUw4bXhsSUJRdVZrMHE5RHFGbjFUT0F0S1Bzbnk5aFA1dnJNOWZQc1dIVWZuOVlLbmlVcXJSZ0hNVDU1b3BGNnBGJTJCaW9YOXZwdjdBJTNEJTNE; _gat_gtag_UA_110559829_1=1';
        // $header[] = 'referer: https://w11.mangafreak.net/?__cf_chl_jschl_tk__=9beeeb541b161fc1075d4a6e39d405b9202bfa41-1598062194-0-AYUWMwibTAAR493PufHbUF3qMPkjoJFLtdFKFHeBIDiMqPYuGDtQvXj7wzHfuKFs2xEeZ8mShodBofmOcLZb6gdi4A0tOGXVZyGhuV3VzCrHimpSz_0l0W2uvWeRANI1sFQ-FO6FeifrpGMblKw5W9pE8Seywg6r6oXtm_MzOPZ5IFFACgMa3leme9hebMToxUwdMbNDAh0GZk3IMI76uIONuVOTCWi6KWCweHNwbjv4uB8n23f2OSnI4yWrx-4ngieafkfptGf1KINb55_kJxg';
        // $header[] = 'cache-control: max-age=0';
        // $header[] = 'sec-fetch-dest: document';
        // $header[] = 'TE: Trailers';
        // $header[] = 'Upgrade-Insecure-Requests: 1';
        // $header[] = 'Cookie: PHPSESSID=ce0dd2e99958088e76767d943cc99214; _ga=GA1.2.83804295.1598066120; _gid=GA1.2.1303117335.1598066120; __gads=ID=3a84116224089147:T=1598066123:S=ALNI_MaldwVdgA6sG0olN-t9qBKqtUCttA; wpmanga-reading-history=W3siaWQiOjMxMjYsImMiOiIzNDQ3NiIsInAiOjEsImkiOiIiLCJ0IjoxNTk4MDY2MzQzfV0%3D; _gat_gtag_UA_151921522_1=1';
        // $header[] = 'Connection: keep-alive';
        // $header[] = 'Host: mangabob.com';
        // $header[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:79.0) Gecko/20100101 Firefox/79.0';
        // $header[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
        // $header[] = 'Accept-Language: en-US,en;q=0.5';
        // $header[] = 'Accept-Encoding: gzip, deflate, br';
        $curl = new Curl();

        echo 'start crawl: '.$url."\n";

        $curl->setConnectTimeout(10000);
        $curl->setTimeout(10000);
        // $curl->setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36 Edg/84.0.522.61');
        // $curl->setHeader('X-Requested-With', 'XMLHttpRequest');
        // $curl->setOpt(CURLOPT_ENCODING , '');
        // $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setReferer('https://www.google.com/');
        // $curl->setHeaders($header);
        // echo 'ddfdd';
        $curl->get($url);  
        // echo $curl->getErrorCallback.'\n';
        // echo 'ahah';

        if ($curl->error) {
            echo 'Error curl: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            $content = $curl->response;
           
            // echo 'Response:' . "\n";
            // var_dump($curl->response);
            // echo $content;
        }
        
        // var_dump($curl->requestHeaders);
        // echo "\n";
        // var_dump($curl->responseHeaders);

        $curl->close();
        echo 'end crawl: '.$url."\n";
        return !$curl->error;
    }

    function get_info($content){
        $info = array();

        $dom = new Document();
        $dom->load($content);

        $temp = $dom->find('div[class=d39]')[0];
        $info[0] = $temp->find('div[class=d40]')[0]->text();
        $info[1] = $temp->find('table[class=d41]')[0]->find('tr')[0]->find('td')[1]->find('span[class=name]')[0]->text();
        $info[2] = $temp->find('table[class=d41]')[0]->find('tr')[1]->find('td')[1]->text();
        $info[3] = $temp->find('table[class=d41]')[0]->find('tr')[2]->find('td')[1]->text();
        $info[4] = $temp->find('table[class=d41]')[0]->find('tr')[3]->find('td')[1]->text();
        $info[5] = $temp->find('table[class=d41]')[0]->find('tr')[4]->find('td')[1]->text();
        $info[6] = $temp->find('table[class=d41]')[0]->find('tr')[5]->find('td')[1]->text();
        $info[7] = $temp->find('table[class=d41]')[0]->find('tr')[6]->find('td')[1]->text();
        $details = $temp->find('table[class=d41]')[0]->find('tr')[7]->find('td')[1]->find('a[class=d42]');
        if(isset($details) && count($details)>0){
            for($i=0; $i<count($details); ++$i){
                $info[8][] = $details[$i]->text();
            }
        }

        $info[9]= $dom->find('div[class=d38]')[0]->find('img')[0]->getAttribute('src');
     
        return $info;
    }  

    function save_all_chapter($store_id,$content){
        $chapter = array();

        $dom = new Document();

    

        $dom->load($content);
        
        //mangatoon $item_chapters = $dom->find('div[class=episodes-wrap]')[0]->find('a[class=episode-item]');
        //  $item_chapters = $dom->find('ul[class=row-content-chapter]')[0]->find('li[class=a-h]');//kakalot
        // $item_chapters = $dom->find('div[class=mb-6 chap-list px-1 pt-6 pb-3 shadow rounded border]')[0]->find('div[class=d-flex justify-content-between mb-2 pb-2 border-bottom align-items-center pl-3]');//truyen vn
        //  $item_chapters = $dom->find('div[class=detail_lst]')[0]->find('ul[id=_listUl]')[0]->find('li');//webtoon
        // $item_chapters = $dom->find('ul[class=main version-chap active]');//mangabob
        

        $item_chapters = $dom->find('table[class=d48]')[0]->find('tr');
       
        // echo count($item_chapters);
        // return;
        if(isset($item_chapters) && count($item_chapters)>0){
            for($i=1; $i < count($item_chapters); ++$i){
                $item_chapter = $item_chapters[$i];

                //mangatoon $chapter_name = $item_chapter->find('div[class=episode-title]')[0]->text();
                //mangatoon $chapter_date = $item_chapter->find('div[class=episode-date]')[0]->find('span')[0]->text();
                //mangatoon $chapter_link = BASE_URL.$item_chapter->find('a')[0]->getAttribute('href');

                //  $chapter_name = $item_chapter->find('a')[0]->text(); //kakalot
                //  $chapter_date = $item_chapter->find('span[class=chapter-time text-nowrap]')[0]->text();//kakalot
                //  $chapter_link = $item_chapter->find('a')[0]->getAttribute('href');//kakalot
                
                // $chapter_name = $item_chapter->find('a')[0]->find('span')[0]->text();// truyen vn 
                // $chapter_date = $item_chapter->find('a')[0]->find('span')[1]->text();// truyen vn
                // $chapter_link = $item_chapter->find('a')[0]->getAttribute('href');// truyen vn 

                //webtoon $chapter_name = $item_chapter->find('a')[0]->find('span[class=subj]')[0]->text();
                //webtoon $chapter_date = $item_chapter->find('a')[0]->find('span[class=date]')[0]->text();
                //webtoon $chapter_link = $item_chapter->find('a')[0]->getAttribute('href');

            
                $chapter_name = $item_chapter->find('td')[0]->text();
                $chapter_date = $item_chapter->find('td')[1]->text();
                $chapter_link = BASE_URL.$item_chapter->find('a')[0]->getAttribute('href');

                $chapter_namefinal =  preg_replace('/[^a-zA-Z0-9_ -]/s', '', $chapter_name);

                $chapter['chapter_name'] = $chapter_namefinal;
                $chapter['chapter_link'] = $chapter_link;
                $chapter['chapter_date'] = $chapter_date;

                

                $data = insert_chapter($store_id, $chapter);
                // echo $data['chapter_id']." ".$chapter_link." ".$chapter_namefinal." ".$chapter_date."</br>";

               
                save_all_image($data['chapter_id'], $chapter_link);
                        
                // break;
            }
        }
    }

    function save_all_image($chapter_id, $url){
        $dataUrl = explode('/', $url);
        $folder_name = $chapter_id.'-'.$dataUrl[3].'-'.$dataUrl[4];
        $folder_path = 'data/'.$folder_name;
        if (is_dir($folder_path)){
            echo "This folder did exist: ". $folder_name." ";
            return;
        } 
        mkdir($folder_path, 0777, true);
        echo 'create folder: '.$folder_name. "\n";

        
        global $database;
        if(get_data($url, $content)){
            $imageArray = array();
            $i = 1;
            preg_match_all('#"u":"(.+?)"}#is',$content, $matches);
            foreach ($matches[1] as &$value) {

                $image_link = stripcslashes($value);
   
                $ext = pathinfo($image_link, PATHINFO_EXTENSION);
                $file = $folder_path.'/'.$i.'.'.$ext;
                if(!download_file($image_link, $file)){
                    download_file($image_link, $file);
                }

                $imageArray['image_link'][] = $image_link; 
                $imageArray['image_path'][] = $file;
                $i++;
            }
            insert_image($chapter_id, $imageArray);
        }
    }



    // function save_all_image($chapter_id, $url){
        // $folder_name = bin2hex(openssl_random_pseudo_bytes(16));
        // $folder_name = $chapter_name."-".$chapter_id;
        // $folder_path = 'data/'.$folder_name;

        // echo 'create folder: '.$folder_name. "\n";

        // mkdir($folder_path, 0777, true);

        // if(get_data($url, $content)){
            // $dom = new Document();
            // $dom->load($content);
       
            // echo $content;
            // $images = $dom->find('div[class=watch-page]')[0]->find('div[class=pictures]')[0]->find('img'); mangatoon
            // $images = $dom->find('div[class=container-chapter-reader]')[0]->find('img');// kakalot
            //  $images = $dom->find('div[class=content-text]')[0]->find('img');// truyen vn
            // $images = $dom->find('div[class=viewer_img _img_viewer_area ]')[0]->find('img');
            // $images = $dom->find('img[id=ci]');
            // echo count($images);

            // if(isset($images) && count($images)>0){
            //     $imageArray = array();
            //     for($i=0; $i < count($images); ++$i){
                    

            //         $image = $images[$i];
            //         $image_link = $image->getAttribute('src');

            //         echo 'LOOK '.$image_link."\n";

            //         $t = $i+1;
            //         $ext = pathinfo($image_link, PATHINFO_EXTENSION);
            //         $file = $folder_path.'/'.$t.'.'.$ext;

            //         download_file($image_link, $file);
                    
            //         $imageArray['image_link'][$t] = $image_link; 
            //         $imageArray['image_path'][$t] = $file;
            //         break;
            //     }
            //     echo print_r($imageArray);
            //     $image_link_json = json_encode($imageArray['image_link']);
            //     echo print_r($image_link_json);

            //     // insert_image($chapter_id, $imageArray);
            // }else{
            //     echo 'LOOK FAIL'.$image_link."\n";
            // }

    //     }

    // }

    // function save_all_image($chapter_id, $url){
        // $folder_name = bin2hex(openssl_random_pseudo_bytes(16));
        // $dataUrl = explode('/', $url);
        // $folder_name = $chapter_id.'-'.$dataUrl[3].'-'.$dataUrl[4];
        // $folder_path = 'data/'.$folder_name;
        // if (is_dir($folder_path)){
        //     echo "da co folder ". $folder_name;
        //     return;
        // } 
        // mkdir($folder_path, 0777, true);
        // echo 'create folder: '.$folder_name. "\n";
        // $dom = new Document();
        // $i = 1;
        // $imageArray = array();
        // while(true){
        //     if(get_data($url.'/'.$i, $content)){

        //         if($content == null){
        //             echo "content null \n";
        //             break;
        //         }
        //         echo $url.'/'.$i.'\n';
                // $dom->load($content);

                // $image_link = $dom->find('img[id=ci]')[0]->getAttribute('src');
                // // echo $image_link."\n";

                // $t = $i;
                // $ext = pathinfo($image_link, PATHINFO_EXTENSION);
                // $file = $folder_path.'/'.$i.'.'.$ext;

                // download_file($image_link, $file);

                // $imageArray['image_link'][$t] = $image_link; 
                // $imageArray['image_path'][$t] = $file;
        //     }else{
        //         break;
        //     }
        //     $i++;    
        // }
        // insert_image($chapter_id, $imageArray);
    // }
?>