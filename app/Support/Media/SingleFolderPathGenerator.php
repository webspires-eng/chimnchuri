<?php

namespace App\Support\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class SingleFolderPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return 'uploads/';
    }

    public function getPathForConversions(Media $media): string
    {
        return 'uploads/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return 'uploads/';
    }
}
