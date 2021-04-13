<?php

function convertBase64ToImageSrc($content, $folder = null) {
    $content = mb_convert_encoding($content, "HTML-ENTITIES", "UTF-8");
    
    libxml_use_internal_errors(true);
    
    $dom = new DomDocument();
    $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $images = $dom->getElementsByTagName("img");

    foreach($images as $img) {
        $src = $img->getAttribute("src");
    
        // if the img source is "data-url"
        if(preg_match("/data:image/", $src)) {

            // get the mimetype
            preg_match("/data:image\/(?<mime>.*?)\;/", $src, $groups);
            $mime_type = $groups["mime"];
            
            // Generating a random filename
            $image_path = $folder."/".\Str::random(40).".".$mime_type;

            // Create file_content
            // $file_content = file_get_contents($src);
            $file_content = base64_decode(preg_replace("#^data:image/\w+;base64,#i", "", $src));
            
            // Upload s3 disk
            Storage::disk("s3")->put($image_path, $file_content, "public");

            $img->removeAttribute("src");
            $img->setAttribute("src", Storage::disk("s3")->url($image_path));
        }
    }    

    return $dom;
}

function deleteImageFromEditor($content)
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

function deleteImageForSingle($url)
{
    $file_exists = Storage::disk("s3")->exists(parse_url($url)["path"]);
    if ($file_exists) {
        Storage::disk("s3")->delete(parse_url($url)["path"]);
    }
}

function deleteImageFromUpdateEditor($old_content, $current_content)
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
        $check = false;

        foreach($current_images as $current_image) 
        {
            $current_src = $current_image->getAttribute("src");
            if ($current_src == $old_src) {
                $check = true;
            }
        }

        if (!$check) {
            // Delete image from s3 disk
            $file_exists = Storage::disk("s3")->exists(parse_url($old_src)["path"]);
            if ($file_exists) {
                Storage::disk("s3")->delete(parse_url($old_src)["path"]);
            } 
        }
    }
}

?>