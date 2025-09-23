<?php

namespace App\Helpers;

use App\Models\Media;

class ImageHelper
{
    /**
     * Get image URL safely with null check
     */
    public static function getImageUrl($mediaId, $defaultImage = null)
    {
        if (empty($mediaId)) {
            return $defaultImage ?? asset('public/frontend/images/no-images.svg');
        }

        $media = Media::find($mediaId);
        
        if (!$media || empty($media->filename)) {
            return $defaultImage ?? asset('public/frontend/images/no-images.svg');
        }

        return asset('public/uploads/images/' . $media->filename);
    }

    /**
     * Get image with fallback
     */
    public static function getImageWithFallback($mediaId, $fallbackImage = null)
    {
        $imageUrl = self::getImageUrl($mediaId, $fallbackImage);
        
        return [
            'url' => $imageUrl,
            'exists' => !empty($mediaId) && Media::find($mediaId) && !empty(Media::find($mediaId)->filename)
        ];
    }

    /**
     * Check if media exists
     */
    public static function mediaExists($mediaId)
    {
        if (empty($mediaId)) {
            return false;
        }

        $media = Media::find($mediaId);
        return $media && !empty($media->filename);
    }

    /**
     * Get media filename safely
     */
    public static function getMediaFilename($mediaId)
    {
        if (empty($mediaId)) {
            return null;
        }

        $media = Media::find($mediaId);
        return $media ? $media->filename : null;
    }
}








