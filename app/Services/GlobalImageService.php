<?php

namespace App\Services;

use App\Interfaces\GlobalImageRepositoryInterface;

class GlobalImageService
{
    /*
    * |--------------------------------------------------------------------------
    * | GlobalImageService the repository services for global module by Christoper Patiño
    */
    protected $global_image_repository;

    public function __construct(GlobalImageRepositoryInterface $global_image_repository)
    {
        $this->global_image_repository = $global_image_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all images
    */
    public function getAll()
    {
        $global_image = $this->global_image_repository->getAll();

        return $global_image;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new image
    */
    public function save(array $data, $folder_name = 'global_images')
    {

        try {
            /*
            * Manage the image upload
            */
            $unique_file_name = uniqid() . '.' . $data['global_image']->extension();
            $data_global_image = [
                'file_name' => $unique_file_name,
                'file_path' => $data['global_image']->storeAs($folder_name, $unique_file_name, 'public'),
                'file_extension' => $data['global_image']->extension(),
                'file_size' => $data['global_image']->getSize(),
                'file_type' => $data['global_image']->getMimeType(),
                'is_active' => true,
            ];

            $global_image = $this->global_image_repository->save($data_global_image);

            return $global_image;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}

