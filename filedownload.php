<?php

require(dirname(__FILE__).'/../../config.php');
require $CFG->dirroot . '/theme/howcollege/extras/autoload.php';
global $PAGE, $OUTPUT;

$fileId = optional_param('fileid', '', PARAM_TEXT);
$fileName = optional_param('name', '', PARAM_TEXT);

$errorthrown = false;

/*$the_big_array = [];
$array_update = [];

$filename = "M:\Moodle-WWWRoots\cff-project.howcollege.ac.uk\wwwroot\local\scorm_downloads\DfEMetadata.csv";

if (($h = fopen("{$filename}", "r")) !== FALSE)
{
    // Each line in the file is converted into an individual array that we call $data
    // The items of the array are comma separated
    while (($data = fgetcsv($h, 1000, ",")) !== FALSE)
    {
        // Each individual array is being pushed into the nested array
        $the_big_array[] = $data;
        foreach ($the_big_array as $key => $value) {
            $filetitle = str_ireplace('.zip', '', $value[1]);
            $filetilefinal = strtolower($filetitle);
            $array_update[$filetilefinal] = ["filename" => $value[1], "shortdescription" => $value[2], "objectives" => $value[3]];
        }
        array_shift($array_update);
        ksort($array_update);
    }

    // Close the file
    fclose($h);
}
var_dump($array_update);*/


if($fileId) {

    global $CFG;
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $CFG->dirroot . '/theme/howcollege/extras/test.json');
    $client = new Google_Client();
    $client->setApplicationName('moodle scorm');
    $client->addScope(\Google_Service_Drive::DRIVE);
    $client->useApplicationDefaultCredentials();
    $service = new Google_Service_Drive($client);

    try {
        $getScorm = $service->files->get($fileId, array('alt' => 'media'));
        $testcontent = $getScorm->getBody()->getContents();
        $length = $getScorm->getBody()->getSize();

        ob_end_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $length);
        flush();
        echo $testcontent;
        exit();

    } catch (\Throwable $e) {
        print_r($e->getMessage());
        print_r($e->getCode());
        print "The Google Drive API threw an error, but dont worry, we'll come back for this.";
        return "error";
    }
}


$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('course');
$PAGE->set_title("Scorm Title");
$PAGE->set_heading("File Download");
$PAGE->set_url($CFG->wwwroot.'/local/scorm_downloads/filedownload.php');

echo $OUTPUT->header();
echo $OUTPUT->footer();


