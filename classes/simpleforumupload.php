<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of simpleforumupload
 *
 * @author jobou
 */
class simpleForumUpload extends eZContentUpload 
{
    function handleForumUpload( &$result, $httpFileIdentifier )
    {
        $result = array( 'errors' => array(),
                         'notices' => array(),
                         'result' => false,
                         'redirect_url' => false );

        $this->fetchHTTPFile( $httpFileIdentifier, $result['errors'], $file, $mimeData );
        if ( !$file )
        {
            $result['errors'][] =
                array( 'description' => ezpI18n::tr( 'kernel/content/upload',
                                                'No HTTP file found, cannot fetch uploaded file.' ) );
            return false;
        }
        $mime = $mimeData['name'];
        if ( $mime == '' )
            $mime = $file->attribute( "mime_type" );

        $classIdentifier = $this->detectClassIdentifier( $mime );
        if ($classIdentifier == 'image')
        {
            if ($file->store());
            {
                $fileHandler = eZClusterFileHandler::instance();
                if ( is_object( $fileHandler ) )
                {
                    $filePath = $file->Filename;
                    $mimeData = eZMimeType::findByFileContents( $filePath );
                    $fileHandler->fileStore( $filePath, 'image', false, $mimeData['name'] );
                }
                
                return $file;
            }
        }
        
        return false;
    }
}

?>
