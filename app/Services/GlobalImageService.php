<?php

namespace App\Services;

use App\Interfaces\GlobalImageRepositoryInterface;
use Illuminate\Support\Facades\Storage;

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
            if( $data['global_image']->getSize() !== false){

                $extension = $data['global_image']->extension();
                $getMimeType = $data['global_image']->getMimeType();
                $getSize = $data['global_image']->getSize();
                $unique_file_name = uniqid() . '.' .$extension;
                $file_path = $data['global_image']->storeAs($folder_name, $unique_file_name, 'public');


            }else{

                $svgPath = public_path('img/user-img.svg');

                $extension = 'svg';
                $getMimeType = 'image/svg+xml';
                $getSize = filesize($svgPath);
                $unique_file_name = uniqid() . '.' .$extension;
                $file_path = Storage::disk('public')->putFileAs($folder_name, new \Illuminate\Http\File($svgPath), $unique_file_name);

            }

            $data_global_image = [
                'file_name' => $unique_file_name,
                'file_path' => $file_path,
                'file_extension' => $extension,
                'file_size' => $getSize,
                'file_type' => $getMimeType,
                'is_active' => true,
            ];

            $global_image = $this->global_image_repository->save($data_global_image);

            return $global_image;


        } catch (\Exception $e) {

            throw $e;
        }
    }
}

