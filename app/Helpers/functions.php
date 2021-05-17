<?php
header('Content-type: text/html; charset=utf-8');

function convert_image_src_editor($content, $folder = null) {
    $content = mb_convert_encoding($content, "HTML-ENTITIES", "UTF-8");
    
    libxml_use_internal_errors(true);
    
    $dom = new DomDocument();
    $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $images = $dom->getElementsByTagName("img");

    foreach($images as $img) {
        $src = $img->getAttribute("src");
    
        // if base64
        if (preg_match("/data:image/", $src)) {

            // get the mimetype
            preg_match("/data:image\/(?<mime>.*?)\;/", $src, $groups);
            $mime_type = $groups["mime"];
            
            // Generating a random filename
            $img_name = $folder."/".\str_random(40).".".$mime_type;

            // Create file_content
            // $file_content = file_get_contents($src);
            $file_content = base64_decode(preg_replace("#^data:image/\w+;base64,#i", "", $src));
            
            Storage::disk('s3')->put($img_name, $file_content, 'public');

            $img->removeAttribute("src");
            $img->setAttribute("src", Storage::disk('s3')->url($img_name));
        } 

        if (str_contains($src, "/temp/")) {
            if (filter_var($src, FILTER_VALIDATE_URL)) {
                $file_exists = Storage::disk("s3")->exists(parse_url($src)["path"]);
                if ($file_exists && !empty($folder)) {
                    $new_path = $folder . "/" . basename($src);

                    Storage::disk("s3")->move(parse_url($src)["path"], $new_path);
                    $url = Storage::disk("s3")->url($new_path);

                    $img->removeAttribute("src");
                    $img->setAttribute("src", $url);
                }    
            }
        }
    }    

    return $dom;
}


function delete_one_image($url)
{
    $file_exists = Storage::disk("s3")->exists(parse_url($url)["path"]);
    if ($file_exists) {
        Storage::disk("s3")->delete(parse_url($url)["path"]);
    }
}

function delete_all_image_editor($content)
{
    if (!empty($content)) {
        libxml_use_internal_errors(true);
    
        $dom = new DomDocument();
        $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        $images = $dom->getElementsByTagName("img");

        foreach($images as $img) {
            $src = $img->getAttribute("src");
            
            if (filter_var($src, FILTER_VALIDATE_URL)) {
                // Delete image from s3 disk
                $file_exists = Storage::disk("s3")->exists(parse_url($src)["path"]);
                if ($file_exists) {
                    Storage::disk("s3")->delete(parse_url($src)["path"]);
                } 
            }
        }
    }
}

function delete_all_image_old_editor($old_content, $current_content)
{
    libxml_use_internal_errors(true);
    
    $dom = new DomDocument();
    $dom->loadHtml($old_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $old_images = $dom->getElementsByTagName("img");

    $dom2 = new DomDocument();
    $dom2->loadHtml($current_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $current_images = $dom2->getElementsByTagName("img");

    foreach($old_images as $old_image)
    {
        $old_src = $old_image->getAttribute("src");
        $old_image_exists = false;

        foreach($current_images as $current_image) 
        {
            $current_src = $current_image->getAttribute("src");
            if ($current_src == $old_src) {
                $old_image_exists = true;
            }
        }

        if (!$old_image_exists) {
            // Delete image from s3 disk
            $file_exists = Storage::disk("s3")->exists(parse_url($old_src)["path"]);
            if ($file_exists) {
                Storage::disk("s3")->delete(parse_url($old_src)["path"]);
            } 
        }
    }
}

function str_random($length = 40) {
    $characters = '-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function rest_call($url, $headers, $postdata = NULL) {
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $curl_response = curl_exec($curl);
    $info = curl_getinfo($curl);
    
    if ($curl_response === false) {
        curl_close($curl);
        $decoded = new stdClass();
        $decoded->http_code = 500;
        $decoded->error = "server_response_false";
        return $decoded;
    }
    
    curl_close($curl);

    $curl_response = substr($curl_response, $info['header_size']);
    $decoded       = json_decode($curl_response, true);
    $decoded['http_code'] = $info['http_code'];
  
    return $decoded;
}

?>