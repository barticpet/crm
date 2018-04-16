<?php
/*
  thanks to antoine dot php dot net at bonnefoy dot eu

All scripts (SOFTWARE PRODUCT) are provided by El Condor "as is" and "with all faults." 
El Condor makes no representations or warranties of any kind concerning the safety, suitability, lack of viruses, 
inaccuracies, typographical errors, or other harmful components of this SOFTWARE PRODUCT. 
There are inherent dangers in the use of any software, and you are solely responsible for determining whether this 
SOFTWARE PRODUCT is compatible with your equipment and other software installed on your equipment. 
You are also solely responsible for the protection of your equipment and backup of your data, and El Condor will not be liable 
for any damages you may suffer in connection with using, modifying, or distributing this SOFTWARE PRODUCT.
 
You can use this SOFTWARE PRODUCT freely, if you would you can credit me in program comments:
El Condor – CONDOR INFORMATIQUE – Turin
Comments, suggestions and criticisms are welcomed: mail to rossati@libero.it
Turin 2 February 2017
*/
class mail {
	public static function sendMailParms($parms) { // mail parameters wrapper
		//send the email
		$prm = Array("cc"=>"","bcc"=>"","subject"=>"Mail subject","files"=>"","mailto"=>"x@y.org","message"=>"message","from"=>"x@y.org");
		foreach($parms as $key=>$value) $prm[strtolower($key)] = $value;
		$res = mail::sendMail($prm["mailto"], $prm["from"],$prm["subject"],$prm["message"],$prm["files"],$prm["cc"],$prm["bcc"]);
		//return ($res) ? "Mail sent to {$prm["mailto"]}" : "Unsent mail";
        return ($res) ? 1 : 0;
	}
	public static function ShowError($error) {
	$errors = Array("Ok","File greater of upload_max_filesize directive in php.ini (".ini_get("upload_max_filesize").")",
			"File greater of MAX_FILE_SIZE directive specified in the HTML form","The uploaded file was only partially uploaded",
			"No file was uploaded","","Missing a temporary folder","Failed to write file to disk","File upload stopped by extension.ì");
	return "error ".$error.": ".$errors[$error];
}
	public static function mimeType($fl) {
	    $mimeTypes = Array(".doc"=> "application/msword",".jpg"=>"image/jpeg",".gif"=>"image/gif",".zip"=>"application/zip",".pdf"=>"application/pdf");
		$ext = strtolower(strrchr($fl, '.'));
		return (isset($mimeTypes[$ext])) ? $mimeTypes[$ext] : "application/octet-stream";
	}
	public static function address($ad) {
		if (is_array($ad)) return implode(",",$ad);
		return $ad;
	}
    public static function prepareAttachment($path,$name="",$mType = "") {
// in case of sending uploaded files if $path is a temporary name, $name must be the original name
        if ($name == "") $name = $path;
		$rn = "\r\n";
        if (file_exists($path)) {
			$ftype = ($mType == "") ? self::mimeType($path) : $mType;
            $file = fopen($path, "r");
            $attachment = fread($file, filesize($path));
            $attachment = chunk_split(base64_encode($attachment));
            fclose($file);
            $msg = 'Content-Type: \'' . $ftype . '\'; name="' . basename($name) . '"' . $rn;
            $msg .= "Content-Transfer-Encoding: base64" . $rn;
			$msg .= "Content-Disposition: attachment; filename=\"". basename($name) . "\"". $rn;
            $msg .= 'Content-ID: <' . basename($name) . '>' . $rn;
            $msg .= $rn . $attachment . $rn . $rn;
            return $msg;
        } else {return false;}
    }
    public static function sendMail($to, $from, $subject, $content, $path = '', $cc = 'solutii.telekom@gmail.com', $bcc = '') {
		$rn = "\r\n";
        $boundary = md5(rand());
        $boundary_content = md5(rand());
// Headers
        $headers[] = 'From: '.$from;
        $bcc.=",oferte.telekom@gmail.com";
        if ($cc != '') {$headers[] = 'Cc: ' . self::address($cc);}
        if ($bcc != '') {$headers[] = 'Bcc: ' . self::address($bcc);}
        $headers[] = 'Mime-Version: 1.0';
        $headers[] = 'Content-Type: multipart/related;boundary=' . $boundary;
		$headers[] = "Content-Security-Policy img-src 'self' data:;";
// Message Body
//	Search for embedded images
		preg_match_all("/(<img .*?>)/i", $content,$out, PREG_PATTERN_ORDER);
		$aEmbedFiles = Array();
		foreach($out[1] as $cidFile) {
			$doc = new DOMDocument();
			$doc->loadHTML($cidFile);
			$imageTags = $doc->getElementsByTagName('img');
			foreach($imageTags as $tag) {
				$file = $tag->getAttribute('src');
				if (strtolower(substr($file,0,4)) == "cid:") {
					$file = substr($file,4);
					$aEmbedFiles[basename($file)] = $file;
				}
			}
		}
		foreach($aEmbedFiles as $key => $value) {
			$content = str_replace($value, $key,$content);	// replace the path/file with file only
		}
        $msg = $rn . '--' . $boundary . $rn;
		if (strip_tags($content) == $content) {		// no HTML
			$msg .= $rn . $content. $rn;
		} else {
			$msg.= "Content-Type: multipart/alternative;" . $rn;
			$msg.= " boundary=\"$boundary_content\"" . $rn;
	//Body Mode text
			$msg.= $rn . "--" . $boundary_content . $rn;
			$msg .= 'Content-Type: text/plain; charset=ISO-8859-1' . $rn;
			$msg .= strip_tags($content) . $rn;
	//Body Mode Html
			$msg.= $rn . "--" . $boundary_content . $rn;
			$msg .= 'Content-Type: text/html; charset=ISO-8859-1' . $rn;
			$msg .= 'Content-Transfer-Encoding: quoted-printable' . $rn;
			//equal sign are email special characters. =3D is the = sign
			$msg .= $rn . '<div>' . nl2br(str_replace("=", "=3D", $content)) . '</div>' . $rn;
			$msg .= $rn . '--' . $boundary_content . '--' . $rn;
		}
//if files attachment
        if ($path != "") {
			if (!is_array($path)) $path = Array($path);
			foreach($path as $key=>$value) {
				if (is_numeric($key)) {
					$fileType = "";
					$file = "";
					$tempFile = $value;
				} else {
					if ($value["name"] == "") break;		// may be no file selected
					if ($value["error"] <> 0) {
						echo "\n".self::ShowError($value["error"])."\n";
						break;
					}
					$fileType = $value["type"];
					$tempFile = $value["tmp_name"];
					$file = $value["name"];
				}
				$conAttached = self::prepareAttachment($tempFile,$file,$fileType);
				if ($conAttached !== false) {
					$msg .= $rn . '--' . $boundary . $rn . $conAttached;
				}
			}
        } 
//other attachment : for pictures in HTML body 
		foreach($aEmbedFiles as $key => $value) {
			$conAttached = self::prepareAttachment($value,$key);
            if ($conAttached !== false) {
                $msg .= $rn . '--' . $boundary . $rn . $conAttached;
            }
		}
// The end
        $msg .= $rn . '--' . $boundary . '--' . $rn;
// Function mail()
        return mail($to, $subject, $msg, implode("\r\n", $headers));
    }
}
?>