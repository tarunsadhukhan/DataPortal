<?php
// Include the generated proxy classes
require_once "documentConverterServices.php";
// Check the uploaded file
if ($_FILES["file"]["error"] > 0)
{
    echo "Error uploading file: " . $_FILES["file"]["error"];
}
else 
{
    // Get the uploaded file content
    $sourceFile = file_get_contents($_FILES["file"]["tmp_name"]);
    
    // Create OpenOptions
    $openOptions = new OpenOptions();
    // set file name and extension
    $openOptions->FileExtension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $openOptions->OriginalFileName = $_FILES["file"]["name"];
    // Create conversionSettings
    $conversionSettings = new ConversionSettings();
    // Set the output format
    if(isset($_POST["outputFormat"]))
    {
        $conversionSettings->Format = $_POST["outputFormat"];
    } else {
        $conversionSettings->Format = "PDF";
    }
    // Set fidelity
    $conversionSettings->Fidelity = "Full";
    // These values must be set to empty strings or actual passwords when converting to non PDF formats
    $conversionSettings->OpenPassword="";
    $conversionSettings->OwnerPassword="";
    // Set some of the other conversion settings. Completely optional and just an example
    $conversionSettings->StartPage = 0;
    $conversionSettings->EndPage = 0;
    $conversionSettings->Range = "VisibleDocuments";
    $conversionSettings->Quality = "OptimizeForPrint";
    $conversionSettings->PDFProfile = "PDF_1_5";
    $conversionSettings->GenerateBookmarks = "Automatic";
    $conversionSettings->PageOrientation="Default";
    // Create the Convert parameter that is send to the server
    $convert = new Convert($sourceFile, $openOptions, $conversionSettings);
    // Create the service client and point it to the correct Conversion Service
    $url = "http://localhost:41734/Muhimbi.DocumentConverter.WebService/?wsdl";
    $serviceClient = new DocumentConverterService(array(), $url);
    
    // If you are expecting long running operations then consider longer timeouts
    ini_set('default_socket_timeout', 60);
    
    try 
    {
        // Execute the web service call
        $result = $serviceClient->Convert($convert)->ConvertResult;
        // Send the resulting file to the client.
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"convert." . $conversionSettings->Format . "\"");
        echo $result;
    }
    catch (Exception $e) 
    {
        print "Error converting document: ".$e->getMessage();
    }    
}
?>