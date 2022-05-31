<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Goutte\Client;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\DB;

class scrapDB extends Controller
{
    //
    public function testConnectionDB() {
        if(DB::connection()->getDatabaseName())
        {
            echo "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
        }
    }
    public function test2() {
        $csvFile = file('D:\Download\Compressed\imdb_scraper__3.csv');
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        return response()->json([
            'success' => true,
            'message' => $data
        ]);
    }
    public function modifyCSV() {
        //$handle = fopen("test.csv", "r");
        $data = [];
        $rep = '_V1_FMjpg_UY1440_.jpg';
        $csv_handler = fopen ('D:\Download\Compressed\csvfile2.csv','w');
        $i = 1;
        if (($handle = fopen("D:\Download\Compressed\imdb_scraper__test.csv", "r")) !== FALSE) {
            $data = fgetcsv($handle);
            array_unshift($data, "movieId");
            array_push($data, "image");
            fputcsv($csv_handler, $data);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $data[1] = str_replace([',',' '],['|',''], $data[1]);
                $data[5] = str_replace('-','', $data[5]);
                //echo $data[9];
                $a = strpos($data[9], '_'); //position of _
                $data[9] = substr($data[9], 0, $a) . $rep; // anh chat luong cao - sieu cao
                //$sub = substr($data[9], 0, $a) . $rep;
                echo $data[9] . "\n";
                array_unshift($data, $i);
                array_push($data, "/storage/images/" . $i . ".jpg");
                fputcsv($csv_handler, $data);
                $i++;
            }
            
            //fwrite ($csv_handler, $handle);
            fclose ($csv_handler);
            fclose($handle);
        }
        return response()->json([
            'success' => true,
            'message' => $data
        ]);
    }
    public function modifyCSV_addVideoUrl() {
        //$handle = fopen("test.csv", "r");
        $data = [];
        $rep = '_V1_QL75_UX380_CR0,18,380,562_.jpg';
        $csv_handler = fopen ('D:\Download\Compressed\csvfile3.csv','w');
        $i = 1;
        if (($handle = fopen("D:\Download\Compressed\csvfile2.csv", "r")) !== FALSE) {
            $data = fgetcsv($handle);
            //array_unshift($data, "movieId");
            array_push($data, "video_url");
            fputcsv($csv_handler, $data);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //$data[1] = str_replace([',',' '],['|',''], $data[1]);
                //$data[5] = str_replace('-','', $data[5]);
                //echo $data[9];
                //$a = strpos($data[9], '_'); //position of _
                //$data[9] = substr($data[9], 0, $a); // anh chat luong cao - sieu cao
                //$sub = substr($data[9], 0, $a) . $rep;
                //echo $data[9] . "\n";
                //array_unshift($data, $i);
                array_push($data, "/storage/videos/" . $i . ".mp4");
                fputcsv($csv_handler, $data);
                $i++;
            }
            
            //fwrite ($csv_handler, $handle);
            fclose ($csv_handler);
            fclose($handle);
        }
        return response()->json([
            'success' => true,
            'message' => $data
        ]);
    }
    public function modifyCSV_addDirector_stars() {
        //$handle = fopen("test.csv", "r");
        $data = [];
        $rep = '_V1_QL75_UX380_CR0,18,380,562_.jpg';
        $csv_handler = fopen ('D:\Download\Compressed\csvfile4.csv','w');
        $i = 1;
        if (($handle = fopen("D:\Download\Compressed\csvfile3.csv", "r")) !== FALSE) {
            $data = fgetcsv($handle);
           //array_unshift($data, "movieId");
            //array_push($data, "image");
            fputcsv($csv_handler, $data);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //$data[1] = str_replace([',',' '],['|',''], $data[1]);
                //$data[5] = str_replace('-','', $data[5]);
                //echo $data[9];
                //$a = strpos($data[9], '_'); //position of _
                //$data[9] = substr($data[9], 0, $a); // anh chat luong cao - sieu cao
                //$sub = substr($data[9], 0, $a) . $rep;
                //echo $data[9] . "\n";
                //array_unshift($data, $i);
                //array_push($data, "/storage/images/" . $i . ".jpg");
                //fputcsv($csv_handler, $data);
                //$i++;
                $data[6] = preg_replace('!\s+!', ' ', $data[6]);
                fputcsv($csv_handler, $data);
            }
            
            //fwrite ($csv_handler, $handle);
            fclose ($csv_handler);
            fclose($handle);
        }
        return response()->json([
            'success' => true,
            'message' => $data
        ]);
    }
    public function downImage() {
        //$handle = fopen("test.csv", "r");
        $data = [];
        //$rep = '_V1_QL75_UX380_CR0,18,380,562_.jpg';
        //$csv_handler = fopen ('D:\Download\Compressed\csvfile.csv','w');
        $handle = fopen("D:\Download\csvfile5.csv", "r");
        $destination = 'D:\Download\Compressed\images\\';
        
        if ($handle) {
            fgetcsv($handle);
            //$data = fgetcsv($handle, 1000, ",");
            //file_put_contents(basename($destination . $data[10] . 'jpg'), file_get_contents($data[10]));

            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //echo $data[10];
                $my_image = file_get_contents($data[11]);
                if ($my_image !== false) {
                    // download the image and store in the database.
                    $my_file = fopen($destination . $data[0] . '.jpg', 'w+');
                
                    fwrite($my_file, $my_image);
                    fclose($my_file);
                }
                echo 'save movie id = ' . $data[0] . '\\n';
            }
            
            fclose($handle);
            
        }
        return response()->json([
            'success' => true,
            'message' => $data
        ]);
    }
    
    // Change video name from XXX to 1.mp4, 2.mp4...
    public function changeVideoName() {
        $directory = 'D:\Download\Compressed\videos\\';
        $i = 1;
        //echo count(glob($directory));
        foreach (glob($directory."*.mp4") as $filename) {
            //echo $filename;
            $file = realpath($filename);
            echo $filename . " thu " . $i . "\n";
            //rename($file, $directory . $i . '.mp4');
            $i++;
        }
        echo $i . "so luong###";
    }

    

    public function downVideo() {
        function parseToArray($xpath,$class)
    {
        $xpathquery="//span[@class='".$class."']";
        $elements = $xpath->query($xpathquery);

        if (!is_null($elements)) {  
            $resultarray=array();
            foreach ($elements as $element) {
                $nodes = $element->childNodes;
                foreach ($nodes as $node) {
                    $resultarray[] = $node->nodeValue;
                }
            }
        return $resultarray;
        }
    }
        var_dump(libxml_use_internal_errors(true));
        $client = new Client();
        if (($handle = fopen("D:\Download\Compressed\csvfile2.csv", "r")) !== FALSE) {
            fgetcsv($handle); // skip first line
            $dom = new DOMDocument();
            if (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $url = $data[11];
                echo $url . "\n";
                $crawler = $client->request('GET', $url);
                $slider_info = 'https://m.imdb.com' . $crawler->filter('.sc-5ea2f380-2')->attr('href'); // done link site video

                // $client2 = new Client();
                // $crawler2 = $client2->request('GET', $slider_info);
                // $video = $crawler2->filterXPath('//div/*')->attr('class');
                echo $slider_info . "\n";
            }
        }
        
    }

    public function genresToNum() {
        //$handle = fopen("test.csv", "r");
        $data = [];
        $rep = '_V1_QL75_UX380_CR0,18,380,562_.jpg';
        $csv_handler = fopen ('D:\Download\csvfile5.csv','w');
        $i = 1;
        $searchs = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Film-Noir', 'Game-Show', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'News', 'Reality-TV', 'Romance', 'Sci-Fi', 'Sport', 'Talk-Show', 'Thriller', 'War', 'Western'];
        $replaces = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26'];
        if (($handle = fopen("D:\Download\csvfile4.csv", "r")) !== FALSE) {
            $data = fgetcsv($handle);
           //array_unshift($data, "movieId");
            //array_push($data, "image");
            fputcsv($csv_handler, $data);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //$data[1] = str_replace([',',' '],['|',''], $data[1]);
                //$data[5] = str_replace('-','', $data[5]);
                //echo $data[9];
                //$a = strpos($data[9], '_'); //position of _
                //$data[9] = substr($data[9], 0, $a); // anh chat luong cao - sieu cao
                //$sub = substr($data[9], 0, $a) . $rep;
                //echo $data[9] . "\n";
                //array_unshift($data, $i);
                //array_push($data, "/storage/images/" . $i . ".jpg");
                //fputcsv($csv_handler, $data);
                //$i++;
                //$data[6] = preg_replace('!\s+!', ' ', $data[6]);
                echo $data[2] . "\n";
                $data[2] = str_replace($searchs, $replaces, $data[2]);
                fputcsv($csv_handler, $data);
            }
            $_hello = "";
            //fwrite ($csv_handler, $handle);
            fclose ($csv_handler);
            fclose($handle);
        }
        return response()->json([
            'success' => true,
            'message' => $data
        ]);
    }
}
