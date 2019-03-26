<?php

namespace Vnn\WpApiClient\Endpoint;

use GuzzleHttp\Psr7\Request;
use RuntimeException;

/**
 * Class Media
 * @package Vnn\WpApiClient\Endpoint
 */
class Media extends AbstractWpEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/wp/v2/media';
    }

    /**
     * @param string $filePath - absolute path of file to upload
     * @param array $data
     * @return array
     */

    public upload($filePath, $data = [])
    {
      $url = $this->getEndpoint();

      if (isset($data['id'])) {
          $url .= '/' . $data['id'];
          unset($data['id']);
      }

      $fileName = basename($filePath);
      $fileHandle = fopen($filePath, "r");

      if ($fileHandle !== false) {
        $mimeType = mime_content_type($filePath);
        $request = new \GuzzleHttp\Psr7\Request('POST', $url, ['Content-Type' => $mimeType, 'Content-Disposition' => 'attachment; filename="'.$fileName.'"'], $fileHandle);
        $response = $this->client->send($request);
        fclose($fileHandle);
        if ($response->hasHeader('Content-Type')
            && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json') {
            return json_decode($response->getBody()->getContents(), true);
        }
      }
      throw new RuntimeException('Unexpected response');
    }
}
