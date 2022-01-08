<?php
    include_once 'QueryDB.php';

    use Curl\Curl;
    use DiDom\Document;
    use DiDom\Element;

    define('BASE_URL', 'https://mangareader.tv');
    
    $url = "https://mangareader.tv/manga/manga-vi952091";
    if(get_data($url, $content)){

        $details = get_info($content);
        $store = array();
        $store['store_name'] = $details[1];
        $store['store_alternate_name'] = $details[2];
        $store['store_release'] = $details[3];
        $store['store_status'] = $details[4];
        $store['store_author'] = $details[5];
 //       $store['store_artist'] = $details[6]; ko con
        $store['store_reading_direction'] = $details[6];
        $store['store_category_id'] = json_encode($details[7]);
        $store['store_link'] = $url;
        $store['store_picture'] = $details[8];

        var_dump($store);
        $data = insert_store($store);

        $store_id = $data['store_id'];

        save_all_chapter($store_id,$content);

    }else{
        echo "NONONOO";
    }

    function get_data($url, &$content){
       
        $curl = new Curl();

        echo 'start crawl: '.$url."\n";

        $curl->setConnectTimeout(10000);
        $curl->setTimeout(10000);
  
        $curl->setReferer('https://www.google.com/');
    
        $curl->get($url);  
       
        if ($curl->error) {
            echo 'Error curl: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            $content = $curl->response;
           
        }
        
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
    //    $info[7] = $temp->find('table[class=d41]')[0]->find('tr')[6]->find('td')[1]->text(); ko con 
        $details = $temp->find('table[class=d41]')[0]->find('tr')[6]->find('td')[1]->find('a[class=d42]');
        if(isset($details) && count($details)>0){
            for($i=0; $i<count($details); ++$i){
                $info[7][] = $details[$i]->text();
            }
        }

        $info[8]= $dom->find('div[class=d38]')[0]->find('img')[0]->getAttribute('src');
     
        return $info;
    }  

    function save_all_chapter($store_id,$content){
        $chapter = array();

        $dom = new Document();
        $dom->load($content);

        $item_chapters = $dom->find('table[class=d48]')[0]->find('tr');
       
        if(isset($item_chapters) && count($item_chapters)>0){
            for($i=1; $i < count($item_chapters); ++$i){
                $item_chapter = $item_chapters[$i];

                $chapter_name = $item_chapter->find('td')[0]->text();
                $chapter_date = $item_chapter->find('td')[1]->text();
                $chapter_link = BASE_URL.$item_chapter->find('a')[0]->getAttribute('href');

                // $chapter_namefinal =  preg_replace('/[^a-zA-Z0-9_ -]/s', '', $chapter_name);
                $chapter_namefinal = addslashes($chapter_name);

                $chapter['chapter_name'] = $chapter_namefinal;
                $chapter['chapter_link'] = $chapter_link;
                $chapter['chapter_date'] = $chapter_date;

                $data = insert_chapter($store_id, $chapter);

                //var_dump($data);

                echo $data['store_id']." - ".$chapter_link." - ".$chapter_namefinal." - ".$chapter_date."</br>";

                save_all_image($data['chapter_id'], $chapter_link);
                break;
            }
        }
    }

    function save_all_image($chapter_id, $url){
        $dataUrl = explode('/', $url);
        $folder_name = $chapter_id.'-'.$dataUrl[3].'-'.$dataUrl[4];
        $folder_path = 'res/'.$folder_name;

        if(get_data($url, $content)){
            $imageArray = array();
            $i = 1;
            // preg_match_all('#"u":"(.+?)"}#is',$content, $matches);
            // o giua data-src=" va width="#
            // is k phan biet hoa thuong, xuong dong
            preg_match_all('#data-src="(.+?)" width="#is',$content, $matches);

            foreach ($matches[1] as &$value) {

                $image_link = stripcslashes($value);

                $ext = pathinfo($image_link, PATHINFO_EXTENSION);
                $file = $folder_path.'/'.$i.'.'.$ext;
                //https
                $new_image_link = substr_replace($image_link, 's', 4, 0);
                $imageArray['image_link'][] = $new_image_link; 
                $imageArray['image_path'][] = $file;
                
                $i++;
            }

            $imageArray['image_download_folder_path'] = $folder_path;
            insert_image($chapter_id, $imageArray);
        }
    }

?>